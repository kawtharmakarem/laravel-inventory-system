<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierEditRequest;
use App\Http\Requests\SupplierRequest;
use App\Models\Account;
use App\Models\Admin;
use App\Models\Admin_panel_setting;
use App\Models\Supplier;
use App\Models\SupplierCategory;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index()
    {
        $com_code=auth()->user()->com_code;
        $data=Supplier::select()->where(['com_code'=>$com_code])->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
              $info->added_by_admin=Admin::where(['id'=>$info->added_by,'com_code'=>$com_code])->value('name');
              $info->suppliers_categories_name=SupplierCategory::select()->where(['id'=>$info->suppliers_categories_id])->value('name');
              if($info->updated_by>0 and $info->updated_by!=null)
              {
                $info->updated_by_admin=Admin::where(['id'=>$info->updated_by,'com_code'=>$com_code])->value('name');
              }
            }
        }
        return view('admin.suppliers.index',['data'=>$data]);
    }
    public function create()
    {
      $com_code=auth()->user()->com_code;
      $suppliers_categories=SupplierCategory::select('id','name')->where(['com_code'=>$com_code,'active'=>1])->orderby('id','DESC')->get();
        return view('admin.suppliers.create',['suppliers_categories'=>$suppliers_categories]);
    }
    public function store(SupplierRequest $request)
    {
  
      try {
  
        $com_code = auth()->user()->com_code;
  
        //check if not exsits for name
        $checkExists_name = get_cols_where_row(new Supplier(), array("id"), array('name' => $request->name, 'com_code' => $com_code));
  
        if (!empty($checkExists_name)) {
          return redirect()->back()
            ->with(['error' => 'Sorry ! supplier name is already existed .'])
            ->withInput();
        }
  
        //set supplier code
        $row = get_cols_where_row_orderby(new Supplier(), array("supplier_code"), array("com_code" => $com_code), 'id', 'DESC');
        if (!empty($row)) {
          $data_insert['supplier_code'] = $row['supplier_code'] + 1;
        } else {
          $data_insert['supplier_code'] = 1;
        }
  
        //set account number
        $row = get_cols_where_row_orderby(new Account(), array("account_number"), array("com_code" => $com_code), 'id', 'DESC');
        if (!empty($row)) {
          $data_insert['account_number'] = $row['account_number'] + 1;
        } else {
          $data_insert['account_number'] = 1;
        }
  
  
        $data_insert['name'] = $request->name;
        $data_insert['phones']=$request->phones;
        $data_insert['address'] = $request->address;
        $data_insert['suppliers_categories_id']=$request->suppliers_categories_id;
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
        $data_insert['current_balance']=$data_insert['start_balance'];
  
        $data_insert['notes'] = $request->notes;
        $data_insert['active'] = $request->active;
        $data_insert['added_by'] = auth()->user()->id;
        $data_insert['created_at'] = date("Y-m-d H:i:s");
        $data_insert['date'] = date("Y-m-d");
        $data_insert['com_code'] = $com_code;
        $flag = insert(new Supplier(), $data_insert);
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
          $supplier_parent_account_number = get_field_value(new Admin_panel_setting(), "supplier_parent_account_number", array('com_code' => $com_code));
          $data_insert_account['notes'] = $request->notes;
          $data_insert_account['parent_account_number'] = $supplier_parent_account_number;
          $data_insert_account['is_parent'] = 0;
          $data_insert_account['account_number'] = $data_insert['account_number'];
          $data_insert_account['account_type'] = 2;
          $data_insert_account['is_archived'] = $request->active;
          $data_insert_account['added_by'] = auth()->user()->id;
          $data_insert_account['created_at'] = date("Y-m-d H:i:s");
          $data_insert_account['date'] = date("Y-m-d");
          $data_insert_account['com_code'] = $com_code;
          $data_insert_account['other_table_FK'] = $data_insert['supplier_code'];
          $flag = insert(new Account(), $data_insert_account);
        }
        return redirect()->route('admin.suppliers.index')->with(['success' => 'Data added successfully.']);
      } catch (\Exception $ex) {
  
        return redirect()->back()
          ->with(['error' => 'Sorry ! Something went wrong ..' . $ex->getMessage()])
          ->withInput();
      }
    }
    public function edit($id)
    {
      $com_code=auth()->user()->com_code;
      $data=Supplier::select()->where(['id'=>$id,'com_code'=>$com_code])->first();
      $suppliers_categories=SupplierCategory::select('id','name')->where(['com_code'=>$com_code,'active'=>1])->orderby('id','DESC')->get();
      return view('admin.suppliers.edit',['data'=>$data,'suppliers_categories'=>$suppliers_categories]);
    }
    public function update(SupplierEditRequest $request,$id)
    {
      try
      {
        $com_code=auth()->user()->com_code;
        $data=Supplier::select('id','account_number','supplier_code')->where(['id'=>$id,'com_code'=>$com_code])->first();
        if(empty($data))
        {
          return redirect()->route('admin.suppliers.index')->with(['error'=>'Unable to find required data .']);
        }
        //checkexists  for name
        $checkExists=Supplier::select('id','name')->where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
        if($checkExists!=null)
        {
          return redirect()->back()->with(['error'=>'This Supplier is already existed'])->withInput();
        }
        $data_to_update['name']=$request->name;
        $data_to_update['phones']=$request->phones;
        $data_to_update['address']=$request->address;
        $data_to_update['notes']=$request->notes;
        $data_to_update['active']=$request->active;
        $data_to_update['updated_at']=date("Y-m-d H:i:s");
        $data_to_update['updated_by']=auth()->user()->id;
        $flag=Supplier::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
        if($flag)
        {
          $data_to_update_account['name']=$request->name;
          $data_to_update_account['active']=$request->active;
          $data_to_update_account['updated_by']=auth()->user()->id;
          $data_to_update_account['updated_at']=date('Y-m-d H:i:s');
          Account::where(['account_number'=>$data['account_number'],'other_table_FK'=>$data['supplier_code'],'com_code'=>$com_code,'account_type'=>2])->update($data_to_update_account);
        }
        return redirect()->route('admin.suppliers.index')->with(['success'=>'Data is updated successfully .']);

      }
      catch(\Exception $ex){
        return redirect()->back()->with(['success'=>'Sorry! Something went wrong ..'.$ex->getMessage()]);

      }
    }
    public function delete($id)
    {
      try
      {
        $com_code=auth()->user()->com_code;
        $item_row=Supplier::select()->where(['id'=>$id,'com_code'=>$com_code])->first();
        if(empty($item_row))
        {
          return redirect()->back()->with(['error'=>'Unable to find required data .']);

        }
        $flag=$item_row->delete();
        if($flag)
        {
          return redirect()->route('admin.suppliers.index')->with(['success'=>'Data is deleted successfully']);
        }
      }
      catch(\Exception $ex)
      {
        return redirect()->back()->with(['error'=>'Sorry ! Something went wrong ..'.$ex->getMessage()]);
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
          if($searchbyradio=='supplier_code')
          {
            $field3='supplier_code';
            $operation3='=';
            $value3=$search_by_text;

          }elseif($searchbyradio=='account_number'){
            $field3='account_number';
            $operation3='=';
            $value3=$search_by_text;

          }else{
            $field3='name';
            $operation3='LIKE';
            $value3="%{$search_by_text}%";

          }

        }else
        {
          $field3='id';
          $operation3='>';
          $value3=0;

        }
        $data=Supplier::where(['com_code'=>$com_code])->where($field3,$operation3,$value3)->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
              $info->added_by_admin=Admin::where(['id'=>$info->added_by,'com_code'=>$com_code])->value('name');
              $info->suppliers_categories_name=SupplierCategory::select()->where(['id'=>$info->suppliers_categories_id])->value('name');
              if($info->updated_by>0 and $info->updated_by!=null)
              {
                $info->updated_by_admin=Admin::where(['id'=>$info->updated_by,'com_code'=>$com_code])->value('name');
              }
            }
        }
        return view('admin.suppliers.ajax_search',['data'=>$data]);

      }
    }
}
