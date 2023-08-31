<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvUomsRequest;
use App\Models\Admin;
use App\Models\Inv_Uom;
use Illuminate\Http\Request;

class InvUomsController extends Controller
{
    public function index()
    {
        $data=Inv_Uom::select()->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
                $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');
                if($info->updated_by>0 and $info->updated_by!=null)
                {
                    $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');
                }
            }
        }
        return view('admin.inv_uoms.index',['data'=>$data]);
    }
    public function create()
    {
        return view('admin.inv_uoms.create');
    }
    public function store(InvUomsRequest $request)
    {
        try
        {
            $com_code=auth()->user()->com_code;
            //todo :I will return 
            $checkExists=Inv_Uom::where(['name'=>$request->name,'com_code'=>$com_code])->first();
            if($checkExists!=null)
            {
                return redirect()->back()->with(['error'=>'This Type is already existed.'])->withInput();
            }
            $data['name']=$request->name;
            $data['is_master']=$request->is_master;
            $data['active']=$request->active;
            $data['created_at']=date("Y-m-d H:i:s");
            $data['added_by']=auth()->user()->id;
            $data['com_code']=$com_code;
            $data['date']=date("Y-m-d");
            Inv_Uom::create($data);
            return redirect()->route('admin.uoms.index')->with(['success'=>'Data Added Successfully .']);

        }
        catch(\Exception $ex)
        {
          return redirect()->back()->with(['error'=>'Sorry ! Something went wrong ..'.$ex->getMessage()])->withInput();
        }

    }
    public function edit($id)
    {
        $data=Inv_Uom::select()->find($id);
        return view('admin.inv_uoms.edit',['data'=>$data]);
    }
    public function update($id,InvUomsRequest $request)
    {
        try
        {
          $com_code=auth()->user()->com_code;
          $data=Inv_Uom::select()->find($id);
          if(empty($data))
          {
            return redirect()->route('admin.uoms.index')->with(['error'=>'Unable to find required data']);
          }
          $checkExists=Inv_Uom::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
          if($checkExists!=null)
          {
            return redirect()->back()->with(['error'=>'This type is already existed'])->withInput();
          }
          $data_to_update['name']=$request->name;
          $data_to_update['is_master']=$request->is_master;
          $data_to_update['active']=$request->active;
          $data_to_update['updated_at']=date('Y-m-d H:i:s');
          $data_to_update['updated_by']=auth()->user()->id;
          $data_to_update['date']=date('Y-m-d');
          Inv_Uom::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
          return redirect()->route('admin.uoms.index')->with(['success'=>'Data updated successfully .']);

        }catch(\Exception $ex)
        {
            return redirect()->back()->with(['error'=>'Sorry! Something went wrong..'.$ex->getMessage()])->withInput();
        }
    }
    public function delete($id)
    {
        try
        {
            $data=Inv_Uom::select()->find($id);
           if(!empty($data))
           {
            $flag=$data->delete();
            if($flag)
            {
             return redirect()->route('admin.uoms.index')->with(['success'=>'Data deleted successfully .']);
            }else
            {
              return redirect()->back()->with(['error'=>'Sorry! Something went wrong ,try again please']);
            }
            

           }else
           {
             return redirect()->back()->with(['error'=>'Unable to find required data .']);
           }
        }
        catch(\Exception $ex)
        {
           return redirect()->back()->with(['error'=>'Sorry !Something went wrong..'.$ex->getMessage()]);
        }
    }
    public function ajax_search(Request $request)
    {
      if($request->ajax())
      {
        $search_by_text=$request->search_by_text;
        $is_master_search=$request->is_master_search;
        if($search_by_text=='')
        {
          $field1='id';
          $operator1='>';
          $value1=0;

        }else{
          $field1='name';
          $operator1='LIKE';
          $value1="%{$search_by_text}%";

        }
        if($is_master_search=='all')
        {
          $field2='id';
          $operator2='>';
          $value2=0;

        }else{
          $field2='is_master';
          $operator2='=';
          $value2=$is_master_search;

        }

        $data=Inv_Uom::where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) {
                $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');
                if($info->updated_by>0 and $info->updated_by!=null)
                {
                    $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');
                }
            }
        }
        
        return view('admin.inv_uoms.ajax_search',['data'=>$data]);
      }
    }
}
