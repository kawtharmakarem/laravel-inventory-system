<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin_treasury extends Model
{
    use HasFactory;
    protected $table='admins_treasuries';
    protected $fillable=[
        'admin_id', 'treasuries_id', 'active', 'added_by', 'created_at', 'updated_by', 'updated_at', 'com_code','date'
    ];
}
