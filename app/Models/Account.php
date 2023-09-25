<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table='accounts';
    protected $fillable=[
        'name','account_type','is_parent','parent_account_number','account_number','start_balance','start_balance_status','current_balance','other_table_FK','notes',
        'added_by','updated_by','created_at','updated_at','active','com_code','date'
    ];
}
