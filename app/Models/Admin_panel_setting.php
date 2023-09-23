<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin_panel_setting extends Model
{
    use HasFactory;
    protected $table="admin_panel_settings";
    protected $fillable=[
        'system_name','photo','active','general_alert','address','phone','customer_parent_account_number','supplier_parent_account_number','added_by','updated_by','created_at',
        'updated_at','com_code','delegate_parent_account_number','employees_parent_account_number'
    ];

}
