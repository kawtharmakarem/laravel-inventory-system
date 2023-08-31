<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuppliersCategoryRequest;
use App\Models\Admin;
use App\Models\SupplierCategory;
use Illuminate\Http\Request;

class SuppliersCategoriesController extends Controller
{
    public function index()
    {
        $com_code=auth()->user()->com_code;
        $data=SupplierCategory::select()->where(['com_code'=>$com_code])->orderby('id','DESC')->paginate(PAGINATION_COUNT);
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
        return view('admin.suppliers_categories.index',['data'=>$data]);
    }
    public function create()
    {
        return view('admin.suppliers_categories.create');
    }
    public function store(SuppliersCategoryRequest $request)
    {
      try
      {
        $com_code=auth()->user()->com_code;
        $checkExists=SupplierCategory::where(['name'=>$request->name,'com_code'=>$com_code])->first();
        if($checkExists!=null)
        {
            return redirect()->back()->with(['error'=>'This supplier name is already existed.'])->withInput();
        }
        $data['name']=$request->name;
        $data['active']=$request->active;
        $data['created_at']=date("Y-m-d H:i:s");
        $data['added_by']=auth()->user()->id;
        $data['date']=date("Y-m-d");
        $data['com_code']=$com_code;
        SupplierCategory::create($data);
        return redirect()->route('admin.suppliers_categories.index')->with(['success'=>'Data added successfully.']);
      }
      catch(\Exception $ex)
      {
        return redirect()->back()->with(['error'=>'Sorry ! Something went wrong ..'.$ex->getMessage()])->withInput();

      }
    }
    public function edit($id)
    {
        $com_code=auth()->user()->com_code;
        $data=SupplierCategory::select()->where(['id'=>$id,'com_code'=>$com_code])->first();
        return view('admin.suppliers_categories.edit',['data'=>$data]);

    }
    public function update(SuppliersCategoryRequest $request,$id)
    {
        try
        {
         $com_code=auth()->user()->com_code;
         $data=SupplierCategory::select()->where(['id'=>$id,'com_code'=>$com_code])->first();
         if(empty($data))
         {
            return redirect()->route('admin.suppliers_categories.index')->with(['error'=>'Sorry ! unable to find required data .']);
         }
         //check exists for name
         $checkExists=SupplierCategory::select()->where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
         if($checkExists!=null){
            return redirect()->back()->with(['error'=>'Supplier name is already existed .'])->withInput();
         }
         
        $data_to_update['name']=$request->name;
        $data_to_update['active']=$request->active;
        $data_to_update['updated_by']=auth()->user()->id;
        $data_to_update['updated_at']=date('Y-m-d H:i:s');
        $data_to_update['date']=date('Y-m-d');
        SupplierCategory::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
        return redirect()->route('admin.suppliers_categories.index')->with(['success'=>'Data updated successfully .']);
        }
        catch(\Exception $ex)
        {
         return redirect()->back()->with(['error'=>'Sorry ! Something went error ..'.$ex->getMessage()])->withInput();
        }
    }
    public function delete($id)
    {
        try
        {
            $com_code=auth()->user()->com_code;
            $data_to_delete=SupplierCategory::select()->where(['id'=>$id,'com_code'=>$com_code])->first();
            if($data_to_delete!=null)
            {
                $flag=$data_to_delete->delete();
                if($flag)
                {
                    return redirect()->route('admin.suppliers_categories.index')->with(['success'=>'Data is deleted successfully.']);
                }else{
                    return redirect()->back()->with(['error'=>'Sorry! Something went wrong ,try again']);
                }

            }else
            {
              return redirect()->back()->with(['error'=>'Unable to find required data .']);
            }
        }
        catch(\Exception $ex)
        {
            return redirect()->back()->with(['error'=>'Sorry!Something went wrong ..'.$ex->getMessage()]);
        }
    }
}
