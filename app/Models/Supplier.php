<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table='suppliers';
    protected $fillable=[
        'supplier_code','suppliers_categories_id','name','account_number','start_balance','start_balance_status','current_balance','notes','added_by',
        'updated_by','created_at','updated_at','active','com_code','date','phones','address'
    ];
}
