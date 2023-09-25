<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountsRequest;
use App\Http\Requests\AccountsRequestUpdate;
use App\Models\Account;
use App\Models\Account_types;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Http\Request;

class AccountsController extends Controller
{

    public function index()
    {
        $com_code=auth()->user()->com_code;
        $data=Account::select()->where(['com_code'=>$com_code])->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
                $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');
                if($info->updated_by>0 and $info->updated_by!=null)
                {
                    $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');
                }
                $info->account_types_name=Account_types::where('id',$info->account_type)->value('name');
                if($info->is_parent==0){
                    $info->parent_account_name=Account::where('account_number',$info->parent_account_number)->value('name');
                }else{
                    $info->parent_account_name="Notfound";
                }
            }
        }
        $account_type=Account_types::select('id','name')->where(['active'=>1])->orderby('id','ASC')->get();
        return view('admin.accounts.index',['data'=>$data,'account_type'=>$account_type]);
    }

    public function create()
    {
        $com_code=auth()->user()->com_code;
        $account_type=Account_types::select('id','name')->where(['active'=>1,'relatedinternalaccounts'=>0])->orderby('id','ASC')->get();

        $parent_account_type=Account::select('account_number','name')->where(['is_parent'=>1,'com_code'=>$com_code])->orderby('id','ASC')->get();

        return view('admin.accounts.create',['account_type'=>$account_type,'parent_account_type'=>$parent_account_type]);
    }

    public function store(AccountsRequest $request)
    {
     try{
        $com_code=auth()->user()->com_code;
        //check if name is exists before
        $checkExists_name=Account::select('id')->where(['name'=>$request->name,'com_code'=>$com_code])->first();
        if($checkExists_name!=null)
        {
            return redirect()->back()->with(['error'=>'This account name is already existed'])->withInput();
        }
        //set account number
        $row=Account::select('account_number')->where(['com_code'=>$com_code])->orderby('id','DESC')->first();
        if($row!=null)
        {
            $data_to_insert['account_number']=$row['account_number']+1;
        }else{
            $data_to_insert['account_number']=1;
        }

        $data_to_insert['name']=$request->name;
        $data_to_insert['account_type']=$request->account_type;
        $data_to_insert['is_parent']=$request->is_parent;

        if($data_to_insert['is_parent']==0){
            $data_to_insert['parent_account_number']=$request->parent_account_number;
        }
        $data_to_insert['start_balance_status']=$request->start_balance_status;
        if($data_to_insert['start_balance_status']==1){
            //credit
            $data_to_insert['start_balance']=$request->start_balance*(-1);
        }elseif($data_to_insert['start_balance_status']==2){
            //debit
            $data_to_insert['start_balance']=$request->start_balance;
            if($data_to_insert['start_balance']<0){
                $data_to_insert['start_balance']=$data_to_insert['start_balance']*(-1);
            }

        }elseif($data_to_insert['start_balance_status']==3){
            //balanced
            $data_to_insert['start_balance']=0;

        }else{
            $data_to_insert['start_balance_status']=3;
            $data_to_insert['start_balance']=0;
        }
        $data_to_insert['current_balance']=$data_to_insert['start_balance'];
        $data_to_insert['notes']=$request->notes;
        $data_to_insert['active']=$request->active;
        $data_to_insert['added_by']=auth()->user()->id;
        $data_to_insert['created_at']=date("Y-m-d H:i:s");
        $data_to_insert['date']=date("Y-m-d");
        $data_to_insert['com_code']=$com_code;
        Account::create($data_to_insert);
        return redirect()->route('admin.accounts.index')->with(['success'=>'Data added successfully .']);  
     }catch(\Exception $ex)
     {
      return redirect()->back()->with(['error'=>"Sorry ! Something went wrong ..".$ex->getMessage()])->withInput();
     }
    }

    public function edit($id)
    {
        $com_code=auth()->user()->com_code;
        $account_type=Account_types::select('id','name')->where(['active'=>1])->orderby('id','ASC')->get();
        $parent_account_type=Account::select('account_number','name')->where(['is_parent'=>1,'com_code'=>$com_code])->orderby('id','ASC')->get();
        $data=Account::select()->where(['id'=>$id,'com_code'=>$com_code])->first();
        return view('admin.accounts.edit',['data'=>$data,'account_type'=>$account_type,'parent_account_type'=>$parent_account_type]);
    }
    public function update($id,AccountsRequestUpdate $request)
    {
        try
        {
            $com_code=auth()->user()->com_code;
            $data=get_cols_where2_row(new Account(),array('id','account_number','other_table_FK','account_type'),array('id'=>$id,'com_code'=>$com_code));
            if(empty($data))
            {
                return redirect()->route('admin.accounts.index')->with(['error'=>'Sorry ! unable to find required data .']);
            }
            $checkExists = Account::where(['name' => $request->name, 'com_code' => $com_code])->where('id', '!=', $id)->first();
            if($checkExists!=null)
            {
                return redirect()->back()->with(['error'=>'This account_name is already existed .'])->withInput();
            }
            $data_to_update['name']=$request->name;
            $data_to_update['account_type']=$request->account_type;
            $data_to_update['is_parent']=$request->is_parent;
            if($data_to_update['is_parent']==0){
                $data_to_update['parent_account_number']=$request->parent_account_number;
            }
            $data_to_update['active']=$request->active;
            $data_to_update['updated_by']=auth()->user()->id;
            $data_to_update['updated_at']=date("Y-m-d H:i:s");
           $flag= update(new Account(),$data_to_update,array('id'=>$id,'com_code'=>$com_code));
          if($flag){
            if($data['account_type']==3){
                //update customer table row
                $data_to_update_customer['name']=$request->name;
                $data_to_update_customer['updated_by']=auth()->user()->id;
                $data_to_update_customer['updated_at']=date("Y-m-d H:i:s");
                $flag=update(new Customer(),$data_to_update_customer,array('account_number'=>$data['account_number'],'customer_code'=>$data['other_table_FK'],'com_code'=>$com_code));

            }
          }
            return redirect()->route('admin.accounts.index')->with(['success' => 'Data updated successfully.']);


          
        }
        catch(\Exception $ex)
        {
           return redirect()->back()->with(['error'=>'Sorry! Something went wrong ..'.$ex->getMessage()])->withInput();
        }
    }
    public function delete($id)
    {
        try
        {
            $com_code=auth()->user()->com_code;
            $row=Account::select()->where(['id'=>$id,'com_code'=>$com_code])->first();
            if($row!=null)
            {
             $flag=$row->delete();
             if($flag)
             {
               return redirect()->route('admin.accounts.index')->with(['success'=>'Data is deleted successfully.']);
             }else
             {
                return redirect()->back()->with(['error'=>'Sorry ! Something went wrong try again please']);
             }
            }else
            {
              return redirect()->back()->with(['error'=>'Unable to find required data .']);
            }
        }
        catch(\Exception $ex)
        {
           return redirect()->back()->with(['error'=>'Sorry ! Something went wrong ..'.$ex->getMessage()]);
        }
    }
    public function ajax_search(Request $request)
    {
        if($request->ajax())
        {
            $search_by_text=$request->search_by_text;
            $account_type=$request->account_type;
            $is_parent=$request->is_parent;
            $active=$request->active;
            $searchbyradio=$request->searchbyradio;

            if($is_parent=='all')
            {
              $field1="id";
              $operator1=">";
              $value1=0;
            }else
            {
                $field1="is_parent";
              $operator1="=";
              $value1=$is_parent;
               
            }


            if($account_type=='all')
            {
              $field2="id";
              $operator2=">";
              $value2=0;
            }else
            {
                $field2="account_type";
              $operator2="=";
              $value2=$account_type;
               
            }

            if($search_by_text!='')
            {
                if($searchbyradio=='account_number')
                {
                    $field3='account_number';
                    $operator3='=';
                    $value3=$search_by_text;
                }else
                {
                 $field3='name';
                 $operator3='LIKE';
                 $value3="%{$search_by_text}%"; 
                }
            }else
            {
               $field3='id';
               $operator3='>';
               $value3=0;
            }
            if($active=='all'){
             $field4="id";
             $operator4=">";
             $value4=0;
            }else{
                $field4='active';
                $operator4='=';
                $value4=$active;
            }
            $data=Account::where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->where($field3,$operator3,$value3)->where($field4,$operator4,$value4)->orderby('id','DESC')->paginate(PAGINATION_COUNT);
            if(!empty($data))
            {
            foreach ($data as $info) {
                $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');
                if($info->updated_by>0 and $info->updated_by!=null)
                {
                    $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');
                }
                $info->account_types_name=Account_types::where('id',$info->account_type)->value('name');
                if($info->is_parent==0){
                    $info->parent_account_name=Account::where('account_number',$info->parent_account_number)->value('name');
                }else{
                    $info->parent_account_name="Notfound";
                }
            }

           }
           return view('admin.accounts.ajax_search',['data'=>$data]);

           

        }

       
    }
}
