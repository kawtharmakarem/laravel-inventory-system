<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierCategory extends Model
{
    use HasFactory;
    protected $table='suppliers_categories';
    protected $fillable=[
        'name','created_at','updated_at','added_by','updated_by','com_code','date','active'
    ];
}
