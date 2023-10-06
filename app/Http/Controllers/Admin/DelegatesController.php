<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DelegateRequestAdd;
use App\Models\Account;
use App\Models\Admin;
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
           }


        } catch (\Exception $ex) {
            //throw $th;
        }
    }
}
