<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Admin_treasury;
use App\Models\Treasury;
use Illuminate\Http\Request;

class AdminController extends Controller
{
 public function index()
 {
    $com_code=auth()->user()->com_code;
    $data=get_cols_where_p(new Admin(),array('*'),array('com_code'=>$com_code),'id','DESC',PAGINATION_COUNT);
    if(!empty($data)){
       foreach ($data as $info) {
        $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');
        if($info->updated_by>0 and $info->updated_by!=null)
        {
            $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');
        }
       } 
    }
    return view('admin.admins_accounts.index',['data'=>$data]);
 }
 public function details($id)
 {
   try
   {
      $com_code=auth()->user()->com_code;
      $data = get_cols_where_row(new Admin(), array("*"), array("id" => $id, 'com_code' => $com_code));
      if(empty($data))
      {
         return redirect()->route('admin.admins_accounts.index')->with(['error'=>'Unable to find required data !!']);
      }
      $data['added_by_admin']=Admin::where(['id'=>$data['added_by']])->value('name');
      if($data['updated_by']>0 and $data['updated_by']!=null)
      {
         $data['updated_by_admin']=Admin::where(['id'=>$data['updated_by']])->value('name');

      }
      $treasuries=Treasury::select('id','name')->where(['active'=>1,'com_code'=>$com_code])->orderby('id','ASC')->get();
      $admins_treasuries=Admin_treasury::select()->where(['admin_id'=>$id,'com_code'=>$com_code])->orderby('id','DESC')->get();
      if(!empty($admins_treasuries))
      {
         foreach ($admins_treasuries as $info) {
            $info->name=Treasury::where(['id'=>$info->treasuries_id])->value('name');
            $info->added_by_admin=Admin::where(['id'=>$info->added_by])->value('name');
            if($info->updated_by>0 and $info->updated_by!=null)
            {
               $info->updated_by_admin=Admin::where(['id'=>$info->updated_by])->value('name');
            }
         }
      }
      return view('admin.admins_accounts.details',['data'=>$data,'treasuries'=>$treasuries,'admins_treasuries'=>$admins_treasuries]);

   }
   catch(\Exception $ex)
   {
      return redirect()->back()->with(['error'=>'Sorry ,Something went wrong..'.$ex->getMessage()]);
   }
 }
 public function Add_treasuries_To_Admin($id,Request $request)
 {
   try
   {
      $com_code=auth()->user()->com_code;
      $data=get_cols_where_row(new Admin(),array('*'),array('id'=>$id,'com_code'=>$com_code));
      if(empty($data))
      {
         return redirect()->route('admin.admins_accounts.index')->with(['error'=>'Unable to find required data!!!']);
      }
      //check if not exists
      $admins_treasuries_exists=Admin_treasury::select('id')->where(['admin_id'=>$id,'treasuries_id'=>$request->treasuries_id,'com_code'=>$com_code])->first();
      if(!empty($admins_treasuries_exists))
      {
         return redirect()->route('admin.admins_accounts.details',$id)->with(['error'=>'Sorry ,this treasury has already been added by this user !!!']);
      }
      $data_insert['admin_id']=$id;
      $data_insert['treasuries_id']=$request->treasuries_id;
      $data_insert['active']=1;
      $data_insert['created_at']=date('Y-m-d H:i:s');
      $data_insert['added_by']=auth()->user()->id;
      $data_insert['com_code']=$com_code;
      $data_insert['date']=date('Y-m-d');
      $flag=Admin_treasury::create($data_insert);
      if($flag)
      {
         return redirect()->route('admin.admins_accounts.details',$id)->with(['success'=>'Data is added successfully ..']);
      }else
      {
       return redirect()->route('admin.admins_accounts.details',$id)->with(['error'=>'Sorry , Something went wrong try again !!!']);
      }
   }
   catch(\Exception $ex)
   {
    return redirect()->route('admin.admins_accounts.details',$id)->with(['error'=>'Sorry ,Something went wrong..'.$ex->getMessage()]);
   }
 }
}
