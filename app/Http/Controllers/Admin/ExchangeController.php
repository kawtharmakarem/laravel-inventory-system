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
        $com_code = auth()->user()->com_code;
        $data = Treasury_Transaction::select('*')->where(['com_code' => $com_code,])->where('money', '<', 0)->orderby('id', 'DESC')->paginate(PAGINATION_COUNT);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin = Admin::where(['id' => $info->added_by])->value('name');
                $info->treasury_name = Treasury::where('id', $info->treasuries_id)->value('name');
                $info->mov_type_name = Mov_Type::where('id', $info->mov_type)->value('name');
                if ($info->is_account) {
                    $info->account_type = Account::where(['com_code' => $com_code, 'account_number' => $info->account_number])->value('account_type');
                    $info->account_type_name = Account_types::where(['id' => $info->account_type])->value('name');
                    $info->account_name = Account::where(['com_code' => $com_code, 'account_number' => $info->account_number])->value('name');
                }
            }
        }
        $checkExistsOpenShift = Admin_Shift::select('treasuries_id', 'shift_code')->where(['com_code' => $com_code, 'admin_id' => auth()->user()->id, 'is_finished' => 0])->first();
        if (!empty($checkExistsOpenShift)) {
            $checkExistsOpenShift['treasuries_name'] = Treasury::where(['id' => $checkExistsOpenShift['treasuries_id']])->value('name');
            $checkExistsOpenShift['treasuries_balance_now'] = get_sum_where(new Treasury_Transaction(), 'money', array('com_code' => $com_code, 'shift_code' => $checkExistsOpenShift['shift_code']));
        }
        $mov_type = Mov_Type::select('id', 'name')->where(['active' => 1, 'in_screen' => 1, 'is_private_internal' => 0])->orderby('id', 'ASC')->get();
        $accounts = Account::select('name', 'account_number', 'account_type')->where(['com_code' => $com_code, 'active' => 1, 'is_parent' => 0])->orderby('id', 'DESC')->get();
        if (!empty($accounts)) {
            foreach ($accounts as $info) {
                $info->account_type_name = Account_types::where(['id' => $info->account_type])->value('name');
            }
        }
        $accounts_search=get_cols_where(new Account(),array("name","account_number","account_type"),array("com_code"=>$com_code,"is_parent"=>0),'id','DESC');
        if(!empty($accounts_search)){
          foreach ($accounts_search as $info) {
            $info->account_type_name=Account_types::where(['id'=>$info->account_type])->value('name');
          }
        }
        $treasuries = Treasury::select('id', 'name')->where(['com_code' => $com_code])->orderby('id', 'ASC')->get();
        $admins = Admin::select('id', 'name')->where(['com_code' => $com_code])->orderby('id', 'ASC')->get();
        return view('admin.exchange_transactions.index', ['data' => $data, 'checkExistsOpenShift' => $checkExistsOpenShift, 'mov_type' => $mov_type, 'accounts' => $accounts, 'treasuries' => $treasuries, 'admins' => $admins,'accounts_search'=>$accounts_search]);
    }
    public function store(Exchange_TransactionRequest $request)
    {
        try {
            $com_code = auth()->user()->com_code;
            //check if user has open shift or not
            $checkExistsOpenShift = Admin_Shift::select('treasuries_id', 'shift_code')->where(['com_code' => $com_code, 'admin_id' => auth()->user()->id, 'treasuries_id' => $request->treasuries_id, 'is_finished' => 0])->first();
            if (empty($checkExistsOpenShift)) {
                return redirect()->back()->with(['error' => 'Sorry ,there is no open shift know !!!'])->withInput();
            }
            //first isal number with treasury
            $treasury_data = Treasury::select('last_isal_exchange')->where(['com_code' => $com_code, 'id' => $request->treasuries_id])->first();
            if (empty($treasury_data)) {
                return redirect()->back()->with(['error' => 'Sorry , data for selected treasury is not existed !!!'])->withInput();
            }
            $last_record_treasuries_transactions_record = Treasury_Transaction::select('auto_serial')->where(['com_code' => $com_code])->orderby('auto_serial', 'DESC')->first();
            if (!empty($last_record_treasuries_transactions_record)) {
                $data_insert['auto_serial'] = $last_record_treasuries_transactions_record['auto_serial'] + 1;
            } else {
                $data_insert['auto_serial'] = 1;
            }
            $data_insert['isal_number'] = $treasury_data['last_isal_exchange'] + 1;
            $data_insert['shift_code'] = $checkExistsOpenShift['shift_code'];
            //credit
            $data_insert['money'] = $request->money * (-1);
            $data_insert['treasuries_id'] = $request->treasuries_id;
            $data_insert['mov_type'] = $request->mov_type;
            $data_insert['move_date'] = $request->move_date;
            $data_insert['account_number'] = $request->account_number;
            $data_insert['is_account'] = 1;
            $data_insert['is_approved'] = 1;
            //debit
            $data_insert['money_for_account'] = $request->money;
            $data_insert['byan'] = $request->byan;
            $data_insert['created_at'] = date('Y-m-d H:i:s');
            $data_insert['added_by'] = auth()->user()->id;
            $data_insert['com_code'] = $com_code;
            $flag = Treasury_Transaction::create($data_insert);
            if ($flag) {
                $dataUpdateTreasuries['last_isal_exchange'] = $data_insert['isal_number'];
                Treasury::where(['com_code' => $com_code, 'id' => $request->treasuries_id])->update($dataUpdateTreasuries);
                $account_type = Account::where(['account_number' => $request->account_number])->value('account_type');
                if ($account_type == 2) {
                    refresh_account_balance_supplier($request->account_number, new Account(), new Supplier(), new Treasury_Transaction(), new SupplierWithOrder(), false);
                } elseif ($account_type == 3) {
                    refresh_account_balance_customer($request->account_number, new Account(), new Sale_Invoice(), new Treasury_Transaction(), new Customer(), false);
                } else {
                    //after
                }
                return redirect()->route('admin.exchange_transaction.index')->with(['success' => 'Data is updated successfully ..']);
            } else {
                return redirect()->back()->with(['error' => 'Sorry ,something went wrong !!'])->withInput();
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Sorry ,something went wrong ..' . $ex->getMessage()])->withInput();
        }
    }
    public function get_account_balance(Request $request)
    {
        if ($request->ajax()) {
            $com_code = auth()->user()->com_code;
            $account_number = $request->account_number;
            $AccountData = Account::select("account_type")->where(['com_code' => $com_code, 'account_number' => $account_number])->first();
            if (!empty($AccountData)) {
                if ($AccountData['account_type'] == 2) {
                    $the_final_balance = refresh_account_balance_supplier($account_number, new Account(), new Supplier(), new Treasury_Transaction(), new SupplierWithOrder(), true);
                    return view('admin.exchange_transactions.get_account_balance', ['the_final_balance' => $the_final_balance]);
                } elseif ($AccountData['account_type'] == 3) {
                    $the_final_balance = refresh_account_balance_customer($account_number, new Account(), new Sale_Invoice(), new Treasury_Transaction(), new Customer(), true);
                    return view('admin.exchange_transactions.get_account_balance', ['the_final_balance' => $the_final_balance]);
                } else {
                    $the_final_balance = refresh_account_balance_general($account_number, new Account(), new Treasury_Transaction(), true);
                    return view('admin.exchange_transactions.get_account_balance', ['the_final_balance' => $the_final_balance]);
                }
            }
        }
    }
    public function ajax_search(Request $request)
    {
      if ($request->ajax()) {
        $com_code=auth()->user()->com_code;
        $account_number = $request->account_number;
        $mov_type = $request->mov_type;
        $treasuries = $request->treasuries;
        $admins = $request->admins;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $searchbyradio = $request->searchbyradio;
        $search_by_text = $request->search_by_text;
        if ($account_number == 'all') {
          //دائما  true
          $field1 = "id";
          $operator1 = ">";
          $value1 = 0;
        }  else {
          $field1 = "account_number";
          $operator1 = "=";
          $value1 = $account_number;
        }
    
        if ($mov_type == 'all') {
          //دائما  true
          $field2 = "id";
          $operator2 = ">";
          $value2 = 0;
        } else {
          $field2 = "mov_type";
          $operator2 = "=";
          $value2 = $mov_type;
        }
    
    
        if ($treasuries == 'all') {
          //دائما  true
          $field3 = "id";
          $operator3 = ">";
          $value3 = 0;
        }  else {
          $field3 = "treasuries_id";
          $operator3 = "=";
          $value3 = $treasuries;
        }
    
    
    
        if ($admins == 'all') {
          //دائما  true
          $field4 = "id";
          $operator4 = ">";
          $value4 = 0;
        }  else {
          $field4 = "added_by";
          $operator4 = "=";
          $value4 = $admins;
        }
    
    
        
    
        if ($from_date == '') {
          //دائما  true
          $field5 = "id";
          $operator5 = ">";
          $value5 = 0;
        } else {
          $field5 = "move_date";
          $operator5 = ">=";
          $value5 = $from_date;
        }
    
        if ($to_date == '') {
          //دائما  true
          $field6 = "id";
          $operator6 = ">";
          $value6 = 0;
        } else {
          $field6 = "move_date";
          $operator6 = "<=";
          $value6 = $to_date;
        }
    
        if ($search_by_text != '') {
    
          if ($searchbyradio == 'auto_serial') {
    
            $field7 = "auto_serial";
            $operator7 = "=";
            $value7 = $search_by_text;
          } elseif ($searchbyradio == 'isal_number') {
    
            $field7 = "isal_number";
            $operator7 = "=";
            $value7 = $search_by_text;
          }
          elseif ($searchbyradio == 'account_number') {
    
            $field7 = "account_number";
            $operator7 = "=";
            $value7 = $search_by_text;
          }
          else {
            $field7 = "shift_code";
            $operator7 = "=";
            $value7 = $search_by_text;
          }
        } else {
          //true 
          $field7 = "id";
          $operator7 = ">";
          $value7 = 0;
        }
    
    
        $data = Treasury_Transaction::where($field1, $operator1, $value1)->
        where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where
        ($field4, $operator4, $value4)->
        where($field5, $operator5, $value5)->
        
        where($field6, $operator6, $value6)
        ->
        where($field7, $operator7, $value7) -> where("money", "<", 0)->
        orderBy('id', 'DESC')->paginate(PAGINATION_COUNT);
        if (!empty($data)) {
          foreach ($data as $info) {
            $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
            $info->treasuries_name = Treasury::where('id', $info->treasuries_id)->value('name');
            $info->mov_type_name = Mov_type::where('id', $info->mov_type)->value('name');
            if($info->is_account==1){
              $info->account_type = Account::where(["account_number" => $info->account_number, "com_code" => $com_code])->value("account_type");
              $info->account_type_name = Account_types::where(["id" => $info->account_type])->value("name");
              $info->account_name = Account::where(["account_number" => $info->account_number, "com_code" => $com_code])->value("name");
    
            }
          
          }
        }
    $totalCollectInSearch=Treasury_Transaction::where($field1, $operator1, $value1)->
    where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where
    ($field4, $operator4, $value4)->
    where($field5, $operator5, $value5)->
    
    where($field6, $operator6, $value6)
    ->
    where($field7, $operator7, $value7) -> where("money", "<", 0)->sum('money');
    
    
        return view('admin.exchange_transactions.ajax_search', ['data' => $data,'totalCollectInSearch'=>$totalCollectInSearch]);
      }
    }
    }

