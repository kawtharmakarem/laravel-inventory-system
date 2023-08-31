<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account_types;
use Illuminate\Http\Request;

class Account_typesController extends Controller
{
    public function index()
    {
        $data=get_cols(new Account_types(),array("*"),'id','ASC');
        return view('admin.account_types.index',['data'=>$data]);
    }
}
