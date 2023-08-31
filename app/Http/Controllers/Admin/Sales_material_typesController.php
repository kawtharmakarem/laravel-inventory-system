<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesMaterialTypesRequest;
use App\Models\Admin;
use App\Models\Sales_material_types;
use Illuminate\Http\Request;

class Sales_material_typesController extends Controller
{
    public function index()
    {
        $data=Sales_material_types::select()->orderby('id','DESC')->paginate(PAGINATION_COUNT);
        if(!empty($data))
        {
            foreach ($data as $info) 
            {
                $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');
                if($info->updated_by>0 and $info->updated_by!=null)
                {
                    $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');
                }
            }
            
        }
        return view('admin.sales_material_types.index',['data'=>$data]);
        
       
    }
    public function create()
    {
        return view('admin.sales_material_types.create');
    }
    public function store(SalesMaterialTypesRequest $request)
    {
        try
        {
        $com_code=auth()->user()->com_code;
        $checkExists=Sales_material_types::where(['name'=>$request->name,'com_code'=>$com_code])->first();
        if($checkExists==null)
        {
            $data['name']=$request->name;
            $data['active']=$request->active;

            $data['created_at']=date("Y-m-d H:i:s");
            $data['added_by']=auth()->user()->id;
            $data['com_code']=$com_code;
            $data['date']=date("Y-m-d");
            Sales_material_types::create($data);
            return redirect()->route('admin.sales_material_types.index')->with(['success'=>'Category added successfully ']);





        }else
        {
          return redirect()->back()->with(['error'=>'This Category is already existed'])->withInput();
        }


        }catch(\Exception $ex)
        {
            return redirect()->back()->with(['error'=>'Sorry ! Something went wrong ..'.$ex->getMessage()])->withInput();
        }
    }
    public function edit($id)
    {
       $data=Sales_material_types::select()->find($id);
       return view('admin.sales_material_types.edit',['data'=>$data]); 
    }
    public function update($id,SalesMaterialTypesRequest $request)
    {
        try
        {
          $com_code=auth()->user()->com_code;
          $data=Sales_material_types::select()->find($id);
          if(empty($data))
          {
            return redirect()->route('admin.sales_material_types.index')->with(['error'=>'Unable to find required data.']);
          }
          $checkExists=Sales_material_types::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
          if($checkExists==null){
            $data_to_update['name']=$request->name;
            $data_to_update['active']=$request->active;
            $data_to_update['updated_at']=date('Y-m-d H:i:s');
            $data_to_update['updated_by']=auth()->user()->id;
            $data_to_update['date']=date('Y-m-d');
            Sales_material_types::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
            return redirect()->route('admin.sales_material_types.index')->with(['success'=>'Category updated successfully']);
          }
          else{
            return redirect()->back()->with(['error'=>'This Category is already existed'])->withInput();
          }
        }
        catch(\Exception $ex)
        {
          return redirect()->back()->with(['error'=>'Sorry ! Something went wrong ..'.$ex->getMessage()])->withInput();  
        }
    }
    public function delete($id)
    {
      try
      {
       $data_to_delete=Sales_material_types::find($id);
       if(!empty($data_to_delete))
       {
        $flag=$data_to_delete->delete();
        if($flag){
          return redirect()->back()->with(['success'=>'Category deleted successfully .']);
        }else{
          return redirect()->back()->with(['error'=>'Something went wrong try again please']);
        }
       }else{
        return redirect()->back()->with(['error'=>'Unable to find required data']);
       }
      }
      catch(\Exception $ex)
      {
        return redirect()->back()->with(['error'=>'Sorry !Something went wrong ..'.$ex->getMessage()]);
      }
    }
    
}
