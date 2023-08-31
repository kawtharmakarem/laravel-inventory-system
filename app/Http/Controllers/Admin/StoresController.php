<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoresRequest;
use App\Models\Admin;
use App\Models\Store;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    public function index()
    {
        $data=Store::select()->orderby('id','DESC')->paginate(PAGINATION_COUNT);
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
        return view('admin.stores.index',['data'=>$data]);
    }
    public function create()
    {
        return view('admin.stores.create');
    }
    public function store(StoresRequest $request)
    {
        try
        {
            $com_code=auth()->user()->com_code;
            $checkExists=Store::where(['name'=>$request->name,'com_code'=>$com_code])->first();
            if($checkExists==null){
                $data['name']=$request->name;
                $data['phones']=$request->phones;
                $data['address']=$request->address;
                $data['created_at']=date('Y-m-d H:i:s');
                $data['added_by']=auth()->user()->id;
                $data['date']=date('Y-m-d');
                $data['com_code']=$com_code;
                $data['active']=$request->active;

                Store::create($data);
                return redirect()->route('admin.stores.index')->with(['success'=>'Branch added successfully .']);

            }else
            {
             return redirect()->back()->with(['error'=>'This Branch is already existes !'])->withInput();
            }

        }
        catch(\Exception $ex)
        {
         return redirect()->back()->with(['error'=>'Sorry !Somthing went wrong ..'.$ex->getMessage()])->withInput();
        }

    }
    public function edit($id)
    {
       $data=Store::select()->find($id);
       return view('admin.stores.edit',['data'=>$data]);
    }
    public function update($id,StoresRequest $request)
    {
        try
        {
            $com_code=auth()->user()->com_code;
            $data=Store::select()->find($id);
            if(empty($data))
            {
                return redirect()->route('admin.stores.index')->with(['error'=>'Unable to find required data']);
            }
            $checkExists=Store::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
            if($checkExists!=null)
            {
            return redirect()->back()->with(['error'=>'Brach name is already existed .'])->withInput();  
            }
            $data_to_update['name']=$request->name;
            $data_to_update['phones']=$request->phones;
            $data_to_update['address']=$request->address;
            $data_to_update['active']=$request->active;
            $data_to_update['updated_at']=date('Y-m-d H:i:s');
            $data_to_update['updated_by']=auth()->user()->id;
            Store::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
            return redirect()->route('admin.stores.index')->with(['success'=>'Data updated successfully.']);


        }catch(\Exception $ex)
        {
          return redirect()->back()->with(['error'=>'Sorry! Something went wrong..'.$ex->getMessage()])->withInput();
        }
    }
    public function delete($id)
    {
        try
        {
            $data=Store::select()->find($id);
            if(!empty($data))
            {
                $flag=$data->delete();
                if($flag)
                {
                    return redirect()->back()->with(['success'=>'Data deleted successfully .']);
                }else{
                    return redirect()->back()->with(['error'=>'Sorry ! Something went wrong .']);
                }

            }else
            {
              return redirect()->back()->with(['error'=>'Unable to find required data .']);
            }
        }
        catch(\Exception $ex)
        {
            return redirect()->back()->with(['error'=>'Sorry! Somthing went wrong..'.$ex->getMessage()]);
        }
    }
}
