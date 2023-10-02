<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
