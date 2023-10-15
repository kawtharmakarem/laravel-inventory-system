<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DelegateRequestAdd;
use App\Http\Requests\DelegateUpdateRequest;
use App\Models\Account;
use App\Models\Admin;
use App\Models\Admin_panel_setting;
use App\Models\Delegate;
use Illuminate\Http\Request;

class DelegatesController extends Controller
{
    public function index()
    {
        $com_code=auth()->user()->com_code;
        $data=Delegate::select('*')->where(['com_code'=>$com_code])->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
                $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');
                if($info->updated_by>0 && $info->updated_by!=null)
                {
                    $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');
                }
            }
        }
        return view('admin.delegates.index',['data'=>$data]);
    }
    public function create()
    {
        return view('admin.delegates.create');
    }
    public function store(DelegateRequestAdd $request)
    {
        try {
            $com_code=auth()->user()->com_code;
            $checkExists_name=Delegate::select('id')->where(['com_code'=>$com_code,'name'=>$request->name])->first();
            if(!empty($checkExists_name))
            {
                return redirect()->back()->with(['error'=>'this delegate name is already exists!!'])->withInput();
            }
            //set delegate code
            $row=Delegate::select('delegate_code')->where(['com_code'=>$com_code])->orderby('id','DESC')->first();
            if(!empty($row))
            {
                $data_insert['delegate_code']=$row['delegate_code']+1;
            }else{
                $data_insert['delegate_code']=1;
            }
            //set account number
           $row=Account::select('account_number')->where(['com_code'=>$com_code])->orderby('id','DESC')->first();
           if(!empty($row))
           {
            $data_insert['account_number']=$row['account_number']+1;
           }else{
            $data_insert['account_number']=1;
           } 
           $data_insert['name']=$request->name;
           $data_insert['address']=$request->address;
           $data_insert['start_balance_status']=$request->start_balance_status;
           if($data_insert['start_balance_status']==1){
            //credit
            $data_insert['start_balance']=$request->start_balance*(-1);

           }elseif($data_insert['start_balance_status']==2){
            //debit
            $data_insert['start_balance']=$request->start_balance*(1);
            if($data_insert['start_balance']<0){
                $data_insert['start_balance']=$data_insert['start_balance']*(-1);
            }
           }elseif($data_insert['start_balance_status']==3){
         //balanced
         $data_insert['start_balance']=0;


           }else{
            $data_insert['start_balance_status']=3;
            $data_insert['start_balance']=0;
           }
           $data_insert['percent_type']=$request->percent_type;
           $data_insert['percent_sales_commission_kataei']=$request->percent_sales_commission_kataei;
           $data_insert['percent_sales_commission_nosjomla']=$request->percent_sales_commission_nosjomla;
           $data_insert['percent_sales_commission_jomla']=$request->percent_sales_commission_jomla;
           $data_insert['percent_collect_commission']=$request->percent_collect_commission;
           $data_insert['address']=$request->address;
           $data_insert['phones']=$request->phones;
           $data_insert['current_balance']=$data_insert['start_balance'];
           $data_insert['notes']=$request->notes;
           $data_insert['active']=$request->active;
           $data_insert['added_by']=auth()->user()->id;
           $data_insert['created_at']=date("Y-m-d H:i:s");
           $data_insert['date']=date('Y-m-d');
           $data_insert['com_code']=$com_code;
           $flag=Delegate::create($data_insert);
           if($flag){
            //insert to accounts
            $data_insert_account['name']=$request->name;
            $data_insert_account['start_balance_status']=$request->start_balance_status;
            if($data_insert_account['start_balance_status']==1){
                //credit
                $data_insert_account['start_balance']=$request->start_balance*(-1);
            }elseif($data_insert_account['start_balance_status']==2){
                //debit
                $data_insert_account['start_balance']=$request->start_balance*(1);
                if($data_insert_account['start_balance']<0){
                    $data_insert_account['start_balance']=$data_insert_account['start_balance']*(-1);
                }
            }elseif($data_insert_account['start_balance_status']==3){
                //balanced
                $data_insert_account['start_balance']=0;
            }else{
                $data_insert_account['start_balance_status']=3;
                $data_insert_account['start_balance']=0;
            }
            $data_insert_account['current_balance']=$data_insert_account['start_balance'];
            $delegate_parent_account_number=get_field_value(new Admin_panel_setting(),'delegate_parent_account_number',array('com_code'=>$com_code));
            $data_insert_account['notes']=$request->notes;
            $data_insert_account['parent_account_number']=$delegate_parent_account_number;
            $data_insert_account['is_parent']=0;
            $data_insert_account['account_number']=$data_insert['account_number'];
            $data_insert_account['account_type']=4;
            $data_insert_account['active']=$request->active;
            $data_insert_account['added_by']=auth()->user()->id;
            $data_insert_account['created_at']=date('Y-m-d H:i:s');
            $data_insert_account['date']=date('Y-m-d');
            $data_insert_account['com_code']=$com_code;
            $data_insert_account['other_table_FK']=$data_insert['delegate_code'];
            $flag=Account::create($data_insert_account);
           }
           return redirect()->route('admin.delegates.index')->with(['success'=>'Data Added Successfully']);

        } catch (\Exception $ex) {
            return redirect()->back()->with(['error'=>'Sorry!Something went wrong'.$ex->getMessage()])->withInput();
        }
    }
    public function edit($id)
    {
        $com_code=auth()->user()->com_code;
        $data=Delegate::select('*')->where(['id'=>$id,'com_code'=>$com_code])->first();
        return view('admin.delegates.edit',['data'=>$data]);
    }
    public function update($id,DelegateUpdateRequest $request)
    {
        try
        {
            $com_code=auth()->user()->com_code;
            $data=Delegate::select('id','account_number','delegate_code')->where(['com_code'=>$com_code,'id'=>$id])->first();
            if(empty($data)){
                return redirect()->route('admin.delegates.index')->with(['error'=>'Unable to fetch required data']);
            }
            $checkExists_name=Delegate::where('id','!=',$id)->where(['name'=>$request->name,'com_code'=>$com_code])->first();
            if($checkExists_name!=null){
                return redirect()->back()->with(['error'=>'Sorry ! this account is registerd before'])->withInput();
            }
            $data_to_update['name']=$request->name;
            $data_to_update['phones']=$request->phones;
            $data_to_update['address']=$request->address;
            $data_to_update['percent_type']=$request->percent_type;
            $data_to_update['percent_sales_commission_kataei']=$request->percent_sales_commission_kataei;
            $data_to_update['percent_sales_commission_nosjomla']=$request->percent_sales_commission_nosjomla;
            $data_to_update['percent_sales_commission_jomla']=$request->percent_sales_commission_jomla;
            $data_to_update['percent_collect_commission']=$request->percent_collect_commission;
            $data_to_update['notes']=$request->notes;
            $data_to_update['active']=$request->active;
            $data_to_update['updated_by']=auth()->user()->id;
            $data_to_update['updated_at']=date("Y-m-d H:i:s");
            $flag=Delegate::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
            if($flag){
                $data_to_update_account['name']=$request->name;
                $data_to_update_account['updated_by']=auth()->user()->id;
                $data_to_update_account['updated_at']=date("Y-m-d H:i:s");
                $data_to_update_account['active']=$request->active;
                $flag=Account::where(['com_code'=>$com_code,'account_number'=>$data['account_number'],'other_table_FK'=>$data['delegate_code'],'account_type'=>4])->update($data_to_update_account);
            }
            return redirect()->route('admin.delegates.index')->with(['success'=>'Data updated successfully']);


        }catch(\Exception $ex){
            return redirect()->back()->with(['error'=>'Sorry ! Something went wrong'.$ex->getMessage()]);
        }

    }
    public function show(Request $request)
    {
        if($request->ajax())
        {
            $com_code=auth()->user()->com_code;
            $id=$request->id;
            $data=Delegate::select('*')->where(['com_code'=>$com_code,'id'=>$id])->first();
            if(!empty($data))
            {
                $data['added_by_admin']=Admin::where(['com_code'=>$com_code,'id'=>$data["added_by"]])->value('name');
                if($data['updated_by']>0 && $data['updated_by']!=null){
                    $data['updated_by_admin']=Admin::where(['com_code'=>$com_code,'id'=>$data["updated_by"]])->value('name');
                }
            }
            return view('admin.delegates.show',['data'=>$data]);
        }
    }
    public function ajax_search(Request $request)
    {
     if($request->ajax()){
        $com_code=auth()->user()->com_code;
        $search_by_text=$request->search_by_text;
        $searchbyradio=$request->searchbyradio;
        if($search_by_text!='')
        {
        if($searchbyradio=='delegate_code')
        {
            $field1="delegate_code";
            $operator1="=";
            $value1=$search_by_text;
        }elseif($searchbyradio=='account_number'){
            $field1="account_number";
            $operator1="=";
            $value1=$search_by_text;
        }else{
            $field1="name";
            $operator1="Like";
            $value1="%{$search_by_text}%";
        }

        }else
        {
           $field1='id';
           $operator1=">";
           $value1=0;
        }
        $data=Delegate::where($field1,$operator1,$value1)->where(['com_code'=>$com_code])->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data)){
        foreach ($data as $info) {
            $info->added_by_admin=Admin::where(['com_code'=>$com_code,'id'=>$info->added_by])->value('name');
            if($info->updated_by>0 and $info->updated_by!=null)
            {
                $info->updated_by_admin=Admin::where(['com_code'=>$com_code,'id'=>$info->updated_by])->value('name');
            }
        }
        }
        return view('admin.delegates.ajax_search',['data'=>$data]);

     }
    }
}
