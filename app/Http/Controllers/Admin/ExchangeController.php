<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exchange_TransactionRequest;
use App\Models\Account;
use App\Models\Account_types;
use App\Models\Admin;
use App\Models\Admin_Shift;
use App\Models\Customer;
use App\Models\Mov_Type;
use App\Models\Sale_Invoice;
use App\Models\Supplier;
use App\Models\SupplierWithOrder;
use App\Models\Treasury;
use App\Models\Treasury_Transaction;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function index()
    {
        $com_code=auth()->user()->com_code;
        $data=Treasury_Transaction::select('*')->where(['com_code'=>$com_code,])->where('money','<',0)->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
                $info->added_by_admin=Admin::where(['id'=>$info->added_by])->value('name');
                $info->treasury_name=Treasury::where('id',$info->treasuries_id)->value('name');
                $info->mov_type_name=Mov_Type::where('id',$info->mov_type)->value('name');
                if($info->is_account){
                    $info->account_type=Account::where(['com_code'=>$com_code,'account_number'=>$info->account_number])->value('account_type');
                    $info->account_type_name=Account_types::where(['id'=>$info->account_type])->value('name');
                    $info->account_name=Account::where(['com_code'=>$com_code,'account_number'=>$info->account_number])->value('name');
                }
            }
        }
        $checkExistsOpenShift=Admin_Shift::select('treasuries_id','shift_code')->where(['com_code'=>$com_code,'admin_id'=>auth()->user()->id,'is_finished'=>0])->first();
        if(!empty($checkExistsOpenShift))
        {
            $checkExistsOpenShift['treasuries_name']=Treasury::where(['id'=>$checkExistsOpenShift['treasuries_id']])->value('name');
            $checkExistsOpenShift['treasuries_balance_now']=get_sum_where(new Treasury_Transaction(),'money',array('com_code'=>$com_code,'shift_code'=>$checkExistsOpenShift['shift_code']));
        }
        $mov_type=Mov_Type::select('id','name')->where(['active'=>1,'in_screen'=>1,'is_private_internal'=>0])->orderby('id','ASC')->get();
        $accounts=Account::select('name','account_number','account_type')->where(['com_code'=>$com_code,'active'=>1,'is_parent'=>0])->orderby('id','DESC')->get();
        if(!empty($accounts))
        {
            foreach ($accounts as $info) {
                $info->account_type_name=Account_types::where(['id'=>$info->account_type])->value('name');


            }
        }
        return view('admin.exchange_transactions.index',['data'=>$data,'checkExistsOpenShift'=>$checkExistsOpenShift,'mov_type'=>$mov_type,'accounts'=>$accounts]);
    }
    public function store(Exchange_TransactionRequest $request)
    {
        try
        {
            $com_code=auth()->user()->com_code;
            //check if user has open shift or not
            $checkExistsOpenShift=Admin_Shift::select('treasuries_id','shift_code')->where(['com_code'=>$com_code,'admin_id'=>auth()->user()->id,'treasuries_id'=>$request->treasuries_id,'is_finished'=>0])->first();
            if(empty($checkExistsOpenShift))
            {
                return redirect()->back()->with(['error'=>'Sorry ,there is no open shift know !!!'])->withInput();

            }
            //first isal number with treasury
            $treasury_data=Treasury::select('last_isal_exchange')->where(['com_code'=>$com_code,'id'=>$request->treasuries_id])->first();
            if(empty($treasury_data))
            {
                return redirect()->back()->with(['error'=>'Sorry , data for selected treasury is not existed !!!'])->withInput();
            }
            $last_record_treasuries_transactions_record=Treasury_Transaction::select('auto_serial')->where(['com_code'=>$com_code])->orderby('auto_serial','DESC')->first();
            if(!empty($last_record_treasuries_transactions_record))
            {
                $data_insert['auto_serial']=$last_record_treasuries_transactions_record['auto_serial']+1;
            }else{
                $data_insert['auto_serial']=1;
            }
            $data_insert['isal_number']=$treasury_data['last_isal_exchange']+1;
            $data_insert['shift_code']=$checkExistsOpenShift['shift_code'];
            //credit
            $data_insert['money']=$request->money*(-1);
            $data_insert['treasuries_id']=$request->treasuries_id;
            $data_insert['mov_type']=$request->mov_type;
            $data_insert['move_date']=$request->move_date;
            $data_insert['account_number']=$request->account_number;
            $data_insert['is_account']=1;
            $data_insert['is_approved']=1;
            //debit
            $data_insert['money_for_account']=$request->money;
            $data_insert['byan']=$request->byan;
            $data_insert['created_at']=date('Y-m-d H:i:s');
            $data_insert['added_by']=auth()->user()->id;
            $data_insert['com_code']=$com_code;
            $flag=Treasury_Transaction::create($data_insert);
            if($flag)
            {
              $dataUpdateTreasuries['last_isal_exchange']=$data_insert['isal_number'];
              Treasury::where(['com_code'=>$com_code,'id'=>$request->treasuries_id])->update($dataUpdateTreasuries);
              $account_type=Account::where(['account_number'=>$request->account_number])->value('account_type');
              if($account_type==2){
                refresh_account_balance_supplier($request->account_number,new Account(),new Supplier(),new Treasury_Transaction(),new SupplierWithOrder(),false);

              }elseif($account_type==3){
                refresh_account_balance_customer($request->account_number,new Account(),new Sale_Invoice(),new Treasury_Transaction(),new Customer(),false);

              }else{
                //after
              }
              return redirect()->route('admin.exchange_transaction.index')->with(['success'=>'Data is updated successfully ..']);

            }else{
                return redirect()->back()->with(['error'=>'Sorry ,something went wrong !!'])->withInput();
            }

        }
        catch(\Exception $ex)
        {
             return redirect()->back()->with(['error'=>'Sorry ,something went wrong ..'.$ex->getMessage()])->withInput();
        }
    }
    public function get_account_balance(Request $request)
    {
        if($request->ajax())
        {
            $com_code=auth()->user()->com_code;
            $account_number=$request->account_number;
            $AccountData=Account::select("account_type")->where(['com_code'=>$com_code,'account_number'=>$account_number])->first();
            if(!empty($AccountData))
            {
                if($AccountData['account_type']==2)
                {
                    $the_final_balance= refresh_account_balance_supplier($account_number,new Account(),new Supplier(),new Treasury_Transaction(),new SupplierWithOrder(),true);
                    return view('admin.exchange_transactions.get_account_balance',['the_final_balance'=>$the_final_balance]);
                }elseif($AccountData['account_type']==3)
                {
                    $the_final_balance=refresh_account_balance_customer($account_number,new Account(),new Sale_Invoice(),new Treasury_Transaction(),new Customer(),true);
                    return view('admin.exchange_transactions.get_account_balance',['the_final_balance'=>$the_final_balance]);

                }else{
                    $the_final_balance=refresh_account_balance_general($account_number,new Account(),new Treasury_Transaction(),true);
                    return view('admin.exchange_transactions.get_account_balance',['the_final_balance'=>$the_final_balance]);
                }
            }
        }
    }
}
