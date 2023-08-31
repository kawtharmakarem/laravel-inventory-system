<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Addtreasuries_deliveryRequest;
use App\Http\Requests\TreasuriesRequest;
use App\Models\Admin;
use App\Models\Treasury;
use App\Models\Treasury_delivery;
use Illuminate\Http\Request;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class TreasuriesController extends Controller
{
    public function index()
    {
        $data=Treasury::select()->orderby('id','DESC')->paginate(PAGINATION_COUNT);
       
        if(!empty($data))
        {
            foreach ($data as $info)
             {
                //fetch the admin name who added the treasury by his id
              $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');
              if($info->updated_by>0 and $info->updated_by!=null)
              {
                $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');
              }  
            }
        }

        return view('admin.treasuries.index',['data'=>$data]);
    }
    public function create()
    {
      return view('admin.treasuries.create');
    }

    public function store(TreasuriesRequest $request)
    {
      try
      {
       $com_code=auth()->user()->com_code;
       $checkExists=Treasury::where(['name'=>$request->name,'com_code'=>$com_code])->first();
       if($checkExists==null){

        if($request->is_master==1){
          $checkExists_isMaster=Treasury::where(['is_master'=>1,'com_code'=>$com_code])->first();
          if($checkExists_isMaster!=null){
           // return redirect()->route('admin.treasuries.create')->with(['error'=>'One MainTreasury is allowed only.']);
           return redirect()->back()->with(['error'=>'One MainTreasury is allowed only.'])->withInput();
          }
          
        }
        $data['name']=$request->name;
          $data['is_master']=$request->is_master;
          $data['last_isal_exchange']=$request->last_isal_exchange;
          $data['last_isal_collect']=$request->last_isal_collect;
          $data['active']=$request->active;
          $data['created_at']=date('Y-m-d H:i:s');
          $data['date']=date("Y-m-d");
          $data['added_by']=auth()->user()->id;
          $data['com_code']=$com_code;
        Treasury::create($data);
          return redirect()->route('admin.treasuries.index')->with(['success'=>'Treasury is added successfully']);



       }else{
       // return redirect()->route('admin.treasuries.create')->with(['error'=>'Treasury already exists']);
       return redirect()->back()->with(['error'=>'Sorry ! Something went wrong'])->withInput();

       }
      }
      catch(\Exception $ex)
      {

        //return redirect()->route('admin.treasuries.create')->with(['error'=>'Error !'.$ex->getMessage()]);
        return redirect()->back()->with(['error'=>'Error is happend / '.$ex->getMessage()])->withInput();
      }
    }
    public function edit($id)
    {
     $data=Treasury::select()->find($id);
     return view('admin.treasuries.edit',['data'=>$data]);
    }
    public function update($id,TreasuriesRequest $request)
    {
     try
     {
      $com_code=auth()->user()->com_code;
      $data=Treasury::select()->find($id);
      if(empty($data))
      {
        return redirect()->route('admin.treasuries.index')->with(['error'=>'Sorry ! Unable to find required data']);
      }
      //اذا حاول المستخدم نعديل اي اسم إلى اسم موحود مسبفا سأخبره ان هذا الاسم موجد
      $checkExists=Treasury::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
      if($checkExists!=null)
      {
        return redirect()->back()->with(['error'=>'Treasury is already existed'])->withInput();
      }
      if($request->is_master==1)
      {
        $checkExists_isMaster=Treasury::where(['is_master'=>1,'com_code'=>$com_code])->where('id','!=',$id)->first();
        if($checkExists_isMaster!=null)
        {
          return redirect()->back()->with(['error'=>'One MainTreasury is allowed only'])->withInput();
        }
      }
      $data_to_update['name']=$request->name;
      $data_to_update['is_master']=$request->is_master;
      $data_to_update['last_isal_exchange']=$request->last_isal_exchange;
      $data_to_update['last_isal_collect']=$request->last_isal_collect;
      $data_to_update['active']=$request->active;
      $data_to_update['updated_by']=auth()->user()->id;
      $data_to_update['updated_at']=date('Y-m-d H:i:s');
      
      Treasury::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
      return redirect()->route('admin.treasuries.index')->with(['success'=>'Data updated successfully']);



     }
     catch(\Exception $ex)
     {
      return redirect()->back()->with(['error'=>'Sorry ! ,Something went wrong'.$ex->getMessage()])->withInput();
     } 
    }
    public function ajax_search(Request $request)
    {
      if($request->ajax())
      {
        $search_by_text=$request->search_by_text;
        $data=Treasury::where('name','LIKE',"%{$search_by_text}%")->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        return view('admin.treasuries.ajax_search',['data'=>$data]);
      }
    }
    public function details($id)
    {
      try
      {
        $com_code=auth()->user()->com_code;
        $data=Treasury::select()->find($id);
        if(empty($data))
        {
          return redirect()->route('admin.treasuries.index')->with(['error'=>'Sorry ! Unable to find required data']);
        }
        $data['added_by_admin']=Admin::where('id',$data['added_by'])->value('name');
        if($data['updated_by']>0 and $data['updated_by']!=null){
          $data['updated_by_admin']=Admin::where('id',$data['updated_by'])->value('name');
        }

        $treasuries_delivery=Treasury_delivery::select()->where(['treasuries_id'=>$id])->orderby('id','DESC')->get();
        if(!empty($treasuries_delivery)){
          foreach ($treasuries_delivery as $info) {
            //اسم الخزنة التي سيتم تسليمها
            //سوف يخنار خزانة من جدول الخزن سوف يتم تسليمها
            $info->name=Treasury::where('id',$info->treasuries_can_delivery_id)->value('name');
            $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');
          }
        }
     


        return view('admin.treasuries.details',['data'=>$data,'treasuries_delivery'=>$treasuries_delivery]);
      }
      catch(\Exception $ex)
      {
        return redirect()->back()->with(['error'=>'Sorry !Something went wrong'.$ex->getMessage()]);

      }
    }
    public function add_treasuries_delivery($id)
    {
      try
      {
        $com_code=auth()->user()->com_code;
        //هنا جلبنا لبانات الرئيسية التي سوف تتم الاضافة اليها
        $data=Treasury::select('id','name')->find($id);
        if(empty($data))
        {
          return redirect()->route('admin.treasuries.index')->with(['error'=>"Unable to find required data"]);
        }
        //بيانات الخزن الفرعية التي يمكن اضافنها
        $treasuries=Treasury::select('id','name')->where(['com_code'=>$com_code,'active'=>1])->get();
        //شاشة العرض مع بيانات الرئيسية و الفرعية
        return view('admin.treasuries.add_treasuries_delivery',['data'=>$data,'treasuries'=>$treasuries]);
      }
      catch(\Exception $ex)
      {
        return redirect()->back()->with(['error'=>'Sorry ! Something went wrong'.$ex->getMessage()]);
      }
    }
    public function store_treasuries_delivery($id,Addtreasuries_deliveryRequest $request)
    {
      try
      {
        $com_code=auth()->user()->com_code;
        $data=Treasury::select('id','name')->find($id);
        if(empty($data))
        {
          return redirect()->route('admin.treasuries.index')->with(['error'=>'Unable to find required data']);
        }
        //الخزنة في جدول الخزن المضافة مسبقا
        $checkExists=Treasury_delivery::where(['treasuries_id'=>$id,'treasuries_can_delivery_id'=>$request->treasuries_can_delivery_id,'com_code'=>$com_code])->first();
        if($checkExists!=null)
        {
          return redirect()->back()->with(['error'=>'This sub_treasury is already existed'])->withInput();
        }
        $data_insert_details['treasuries_id']=$id;
        $data_insert_details['treasuries_can_delivery_id']=$request->treasuries_can_delivery_id;
        $data_insert_details['created_at']=date("Y-m-d H:i:s");
        $data_insert_details['added_by']=auth()->user()->id;
        $data_insert_details['com_code']=$com_code;
        Treasury_delivery::create($data_insert_details);
        return redirect()->route('admin.treasuries.details',$id)->with(['success'=>'Data added successfully']);



      }catch(\Exception $ex){
        return redirect()->back()->with(['error'=>'Sorry ! Something went wrong '.$ex->getMessage()]);
      }
    }

     public function delete_treasuries_delivery($id)
     {
      try
      {
        $treasuries_delivery=Treasury_delivery::find($id);
        if(!empty($treasuries_delivery))
        {
          $flag=$treasuries_delivery->delete();
          if($flag){
            return redirect()->back()->with(['success'=>'Treasury deleted successfully ']);

          }else
          {
            return redirect()->back()->with(['error'=>'Sorry ! Something went wrong please try again ']);

          }
        }else
        {
          return redirect()->back()->with(['Unable to find required data ']);
        }

      }
      catch(\Exception $ex)
      {
        return redirect()->back()->with(['error'=>'Sorry! Something went wrong :'.$ex->getMessage()]);
      }
     }

}
