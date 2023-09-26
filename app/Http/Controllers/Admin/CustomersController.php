<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerEditRequest;
use App\Http\Requests\CustomerRequest;
use App\Models\Account;
use App\Models\Admin;
use App\Models\Admin_panel_setting;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index()
    {
        $com_code=auth()->user()->com_code;
        $data=Customer::select()->where(['com_code'=>$com_code])->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
              $info->added_by_admin=Admin::where(['id'=>$info->added_by,'com_code'=>$com_code])->value('name');
              if($info->updated_by>0 and $info->updated_by!=null)
              {
                $info->updated_by_admin=Admin::where(['id'=>$info->updated_by,'com_code'=>$com_code])->value('name');
              }
            }
        }
        return view('admin.customers.index',['data'=>$data]);
    }
    public function create()
    {
      return view('admin.customers.create');
    }
    
    public function store(CustomerRequest $request)
    {
  
      try {
  
        $com_code = auth()->user()->com_code;
  
        //check if not exsits for name
        $checkExists_name = get_cols_where_row(new Customer(), array("id"), array('name' => $request->name, 'com_code' => $com_code));
  
        if (!empty($checkExists_name)) {
          return redirect()->back()
            ->with(['error' => 'Sorry ! customer name is already existed .'])
            ->withInput();
        }
  
        //set customer code
        $row = get_cols_where_row_orderby(new Customer(), array("customer_code"), array("com_code" => $com_code), 'id', 'DESC');
        if (!empty($row)) {
          $data_insert['customer_code'] = $row['customer_code'] + 1;
        } else {
          $data_insert['customer_code'] = 1;
        }
  
        //set account number
        $row = get_cols_where_row_orderby(new Account(), array("account_number"), array("com_code" => $com_code), 'id', 'DESC');
        if (!empty($row)) {
          $data_insert['account_number'] = $row['account_number'] + 1;
        } else {
          $data_insert['account_number'] = 1;
        }
  
  
        $data_insert['name'] = $request->name;
        $data_insert['address'] = $request->address;
        $data_insert['start_balance_status'] = $request->start_balance_status;
        if ($data_insert['start_balance_status'] == 1) {
          //credit
          $data_insert['start_balance'] = $request->start_balance * (-1);
        } elseif ($data_insert['start_balance_status'] == 2) {
          //debit
          $data_insert['start_balance'] = $request->start_balance;
          if ($data_insert['start_balance'] < 0) {
            $data_insert['start_balance'] = $data_insert['start_balance'] * (-1);
          }
        } elseif ($data_insert['start_balance_status'] == 3) {
          //balanced
          $data_insert['start_balance'] = 0;
        } else {
          $data_insert['start_balance_status'] = 3;
          $data_insert['start_balance'] = 0;
        }
        $data_insert['phones']=$request->phones;
        $data_insert['current_balance']=$data_insert['start_balance'];
        $data_insert['notes'] = $request->notes;
        $data_insert['active'] = $request->active;
        $data_insert['added_by'] = auth()->user()->id;
        $data_insert['created_at'] = date("Y-m-d H:i:s");
        $data_insert['date'] = date("Y-m-d");
        $data_insert['com_code'] = $com_code;
        $flag = insert(new Customer(), $data_insert);
        if ($flag) {
          //insert into accounts
          $data_insert_account['name'] = $request->name;
          $data_insert_account['start_balance_status'] = $request->start_balance_status;
          if ($data_insert_account['start_balance_status'] == 1) {
            //credit
            $data_insert_account['start_balance'] = $request->start_balance * (-1);
          } elseif ($data_insert_account['start_balance_status'] == 2) {
            //debit
            $data_insert_account['start_balance'] = $request->start_balance;
            if ($data_insert_account['start_balance'] < 0) {
              $data_insert_account['start_balance'] = $data_insert_account['start_balance'] * (-1);
            }
          } elseif ($data_insert_account['start_balance_status'] == 3) {
            //balanced
            $data_insert_account['start_balance'] = 0;
          } else {
            $data_insert_account['start_balance_status'] = 3;
            $data_insert_account['start_balance'] = 0;
          }
          $data_insert_account['current_balance']=$data_insert_account['start_balance'];
          $customer_parent_account_number = get_field_value(new Admin_panel_setting(), "customer_parent_account_number", array('com_code' => $com_code));
          $data_insert_account['notes'] = $request->notes;
          $data_insert_account['parent_account_number'] = $customer_parent_account_number;
          $data_insert_account['is_parent'] = 0;
          $data_insert_account['account_number'] = $data_insert['account_number'];
          $data_insert_account['account_type'] = 3;
          $data_insert_account['active'] = $request->active;
          $data_insert_account['added_by'] = auth()->user()->id;
          $data_insert_account['created_at'] = date("Y-m-d H:i:s");
          $data_insert_account['date'] = date("Y-m-d");
          $data_insert_account['com_code'] = $com_code;
          $data_insert_account['other_table_FK'] = $data_insert['customer_code'];
          $flag = insert(new Account(), $data_insert_account);
        }
        return redirect()->route('admin.customers.index')->with(['success' => 'Data added successfully.']);
      } catch (\Exception $ex) {
  
        return redirect()->back()
          ->with(['error' => 'Sorry ! Something went wrong ..' . $ex->getMessage()])
          ->withInput();
      }
    }
    
    public function edit($id)
    {
      $com_code=auth()->user()->com_code;
      $data=Customer::select()->where(['id'=>$id,'com_code'=>$com_code])->first();
      return view('admin.customers.edit',['data'=>$data]);
    }
    public function update($id,CustomerEditRequest $request)
    {
      try
      {
        $com_code=auth()->user()->com_code;
        $data=Customer::select('id','customer_code','account_number')->where(['id'=>$id,'com_code'=>$com_code])->first();
        if(empty($data))
        {
          return redirect()->route('admin.customers.index')->with(['error'=>'Sorry !Unable to find required data .']);
        }
        //check exists for name
        $checkExists_name=Customer::select()->where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
        if($checkExists_name!=null)
        {
          return redirect()->back()->with(['error'=>'This Customer name is already existed ..'])->withInput();
        }
        $data_to_update['name']=$request->name;
        $data_to_update['phones']=$request->phones;
        $data_to_update['address']=$request->address;
        $data_to_update['notes']=$request->notes;
        $data_to_update['active']=$request->active;
        $data_to_update['updated_by']=auth()->user()->id;
        $data_to_updated['updated_at']=date("Y-m-d H:i:s");
        $flag=update(new Customer(),$data_to_update,array('id'=>$id,'com_code'=>$com_code));
        if($flag)
        {
          $data_to_update_account['name']=$request->name;
          $data_to_update_account['updated_by']=auth()->user()->id;
          $data_to_update_account['updated_at']=date("Y-m-d H:i:s");
          $flag=update(new Account(),$data_to_update_account,array('account_number'=>$data['account_number'],'other_table_FK'=>$data['customer_code'],'account_type'=>3));
        }
        return redirect()->route('admin.customers.index')->with(['success'=>'Data updated successfully']);


      }
      catch(\Exception $ex)
      {
        return redirect()->back()->with(['error'=>'Something went wrong..'.$ex->getMessage()])->withInput();
      }
    }
    public function delete($id)
    {
      try
      {
        $com_code=auth()->user()->com_code;
        $item_row=Customer::select()->where(['id'=>$id,'com_code'=>$com_code])->first();
        if(empty($item_row))
        {
          return redirect()->back()->with(['error'=>'Unable to find required data.']);

        }else{
          $flag=$item_row->delete();
          if($flag)
          {
            return redirect()->route('admin.customers.index')->with(['success'=>'Data deleted successfully']);

          }else
          {
             return redirect()->back()->with(['error'=>'Sorry ! Something went wrong .']);
          }
        }
      }
      catch(\Exception $ex)
      {
        return redirect()->back()->with(['error'=>'Sorry ! unable to delete required data ..'.$ex->getMessage()]);
      }
    }
    public function ajax_search(Request $request)
    {
      if($request->ajax())
      {
        $com_code=auth()->user()->com_code;

        $search_by_text=$request->search_by_text;
        $searchbyradio=$request->searchbyradio;
        if($search_by_text!='')
        {
         if($searchbyradio=='customer_code')
         {
           $field3='customer_code';
           $operation3='=';
           $value3=$search_by_text;
         }
         elseif($searchbyradio=='account_number')
         {
          $field3='account_number';
          $operation3='=';
          $value3=$search_by_text;

         }else{

          $field3='name';
          $operation3='LIKE';
          $value3="%{$search_by_text}%";
         }
        
        }
        else
        {
          $field3='id';
          $operation3='>';
          $value3=0;
        }
        $data=Customer::where($field3,$operation3,$value3)->where(['com_code'=>$com_code])->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
          foreach ($data as $info) {
            $info->added_by_admin=Admin::where(['id'=>$info->added_by,'com_code'=>$com_code])->value('name');
            if($info->updated_by>0 and $info->updated_by!=null)
            {
              $info->updated_by_admin=Admin::where(['id'=>$info->updated_by,'com_code'=>$com_code])->value('name');
            }
          }
        }
        return view('admin.customers.ajax_search',['data'=>$data]);

      }
    }
}
