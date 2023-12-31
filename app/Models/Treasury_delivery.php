<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treasury_delivery extends Model
{
    use HasFactory;
    protected $table='treasuries_delivery';
    protected $fillable=[
        'treasuries_id','treasuries_can_delivery_id','created_at','added_by','updated_at','updated_by','com_code'
    ];
}
