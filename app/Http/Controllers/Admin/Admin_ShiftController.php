<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminShiftsRequest;
use App\Models\Admin;
use App\Models\Admin_Shift;
use App\Models\Admin_treasury;
use App\Models\Treasury;
use Illuminate\Http\Request;

class Admin_ShiftController extends Controller
{
    public function index()
    {
        $com_code=auth()->user()->com_code;
        $data=Admin_Shift::select()->where(['com_code'=>$com_code])->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
                $info->admin_name=Admin::where(['id'=>$info->admin_id])->value('name');
                $info->treasuries_name=Treasury::where('id',$info->treasuries_id)->value('name');
            }
        }
        $checkExistsOpenShift=Admin_Shift::select('id')->where(['com_code'=>$com_code,'admin_id'=>auth()->user()->id,'is_finished'=>0])->first();
        return view('admin.admins_shifts.index',['data'=>$data,'checkExistsOpenShift'=>$checkExistsOpenShift]);
    }
    public function create()
    {
      $com_code=auth()->user()->com_code;
      $admins_treasuries=Admin_treasury::select('treasuries_id')->where(['com_code'=>$com_code,'active'=>1,'admin_id'=>auth()->user()->id])->orderby('id','DESC')->get();  
      if(!empty($admins_treasuries))
      {
        foreach ($admins_treasuries as $info) {
          $info->treasuries_name=Treasury::where('id',$info->treasuries_id)->value('name');
          $check_exists_admin_shifts=Admin_Shift::select('id')->where(['treasuries_id'=>$info->treasuries_id,'com_code'=>$com_code,'is_finished'=>0])->first();
          if(!empty($check_exists_admin_shifts) and $check_exists_admin_shifts!=null)
          {
            $info->avaliable=false;
          }else{
            $info->avaliable=true;
          }
        }
      }
      return view('admin.admins_shifts.create',['admins_treasuries'=>$admins_treasuries]);
    }
    public function store(AdminShiftsRequest $request)
    {
        try
        {
          $com_code=auth()->user()->com_code;
          $admin_id=auth()->user()->id;
          //check exists for open shift
          $checkExistsOpenShift=Admin_Shift::select('id')->where(['com_code'=>$com_code,'admin_id'=>$admin_id,'is_finished'=>0])->first();
          if($checkExistsOpenShift!=null and !empty($checkExistsOpenShift))
          {
            return redirect()->route('admin.admin_shift.index')->with(['error'=>'Sorry , you already have an open shift and you cannot open new shift know']);
          }
          //check exists for open treasury
          $checkExistsOpenTreasuries=Admin_Shift::select('id')->with(['com_code'=>$com_code,'treasuries_id'=>$request->treasuries_id,'is_finished'=>0])->first();
          if($checkExistsOpenTreasuries!=null and !empty($checkExistsOpenTreasuries))
          {
            return redirect()->route('admin.admin_shift.index')->with(['error'=>'Sorry ,this treasury is already used by another shift and cannot be used know !!!']);
          }
           
          $row=Admin_Shift::select('shift_code')->where(['com_code'=>$com_code])->orderby('id','DESC')->first();
          if(!empty($row))
          {
            $data_insert['shift_code']=$row['shift_code']+1;
          }else{
            $data_insert['shift_code']=1;
          }
          
          $data_insert['admin_id']=$admin_id;
           $data_insert['treasuries_id']=$request->treasuries_id;
           $data_insert['start_date']=date('Y-m-d H:i:s');
           $data_insert['created_at']=date('Y-m-d H:i:s');
           $data_insert['added_by']=auth()->user()->id;
           $data_insert['com_code']=$com_code;
           $data_insert['date']=date('Y-m-d');
           $flag=Admin_Shift::create($data_insert);
           if($flag){
            return redirect()->route('admin.admin_shift.index')->with(['success'=>'Data is added successfully..']);
           }else{
            return redirect()->route('admin.admin_shift.index')->with(['error'=>'Sorry ,Something went wrong try again please !!!']);
           }
        }catch(\Exception $ex){
          return redirect()->back()->with(['error'=>'Sorry ,Something went wrong ..'.$ex->getMessage()]);
        }
    }
}
