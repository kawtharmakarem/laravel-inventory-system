<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table='customers';
    protected $fillable=[
        'customer_code','name','account_number','start_balance','start_balance_status','current_balance','notes','added_by',
        'updated_by','created_at','updated_at','active','com_code','date','city_id','address'
    ];
}
