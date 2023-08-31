<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inv_Itemcard_Movement_Type extends Model
{
    use HasFactory;
    protected $table='inv_itemcard_movements_types';
    protected $fillable=[
        'type'
    ];
}
