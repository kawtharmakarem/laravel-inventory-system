<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inv_itemcard_categoriesRequest;
use App\Models\Admin;
use App\Models\Inv_Itemcard_Categories;
use Illuminate\Http\Request;

class InvItemCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $data=Inv_Itemcard_Categories::select()->orderby('id','DESC')->paginate(PAGINATION_COUNT);
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
      return view('admin.inv_itemcard_categories.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.inv_itemcard_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Inv_itemcard_categoriesRequest $request)
    {
      try
      {
       $com_code=auth()->user()->com_code;
       
       $checkExists=Inv_Itemcard_Categories::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$request->id)->first();
       if($checkExists==null)
       {
        $data['name']=$request->name;
        $data['active']=$request->active;
        $data['created_at']=date('Y-m-d H:i:s');
        $data['added_by']=auth()->user()->id;
        $data['date']=date('Y-m-d');
        $data['com_code']=$com_code;
        Inv_Itemcard_Categories::create($data);
        return redirect()->route('inv_itemcard_categories.index')->with(['success'=>'Data added successfully.']);

       }else
       {
         return redirect()->back()->with(['error'=>'Sorry! This ItemCategory is already existed'])->withInput();
       }

       
       }
      
      catch(\Exception $ex)
      {
         return redirect()->back()->with(['error'=>'Sorry ! Something went error..'.$ex->getMessage()])->withInput();
      }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data=Inv_Itemcard_Categories::select()->find($id);
        return view('admin.inv_itemcard_categories.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Inv_itemcard_categoriesRequest $request, string $id)
    {
        try
        {
          $com_code=auth()->user()->com_code;
          $data=Inv_Itemcard_Categories::select()->find($id);
          if(empty($data))
          {
            return redirect()->back()->with(['error'=>'Sorry ! Unable to find required data .'])->withInput();
          }else
          {
            $checkExists=Inv_Itemcard_Categories::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
            if($checkExists!=null)
            {
              return redirect()->back()->with(['error'=>'This ItemCategory is already existed .']);
            }else
            {
               $data_to_update['name']=$request->name;
               $data_to_update['active']=$request->active;
               $data_to_update['updated_at']=date('Y-m-d H:i:s');
               $data_to_update['updated_by']=auth()->user()->id;
               $data_to_update['date']=date('Y-m-d');
               Inv_Itemcard_Categories::where(['id'=>$id,'com_code'=>$com_code])->update($data_to_update);
               return redirect()->route('inv_itemcard_categories.index')->with(['success'=>'Data updated successfully .']);

            }
          }
        }
        catch(\Exception $ex)
        {
          return redirect()->back()->with(['error'=>'Sorry ! Something went wrong ..'.$ex->getMessage()])->withInput();
        }
    }
    public function destroy(){}

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        try
        {
          $data_to_delete=Inv_Itemcard_Categories::select()->find($id);
          if(!empty($data_to_delete))
          {
            $flag=$data_to_delete->delete();
            if($flag)
            {
              return redirect()->route('inv_itemcard_categories.index')->with(['success'=>'Data deleted successfully .']);
            }else
            {
             return redirect()->back()->with(['error'=>'Sorry ! Something went wrong ,please try again .']);
            }

          }else
          {
            return redirect()->back()->with(['error'=>'Unable to find required data ..']);
          }
        }
        catch(\Exception $ex)
        {

        }
    }
}
