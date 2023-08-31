<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inv_Itemcard_Movement extends Model
{
    use HasFactory;
    protected $table="inv_itemcard_movements";
    public $timestamps=false;
    protected $fillable=[
        'inv_itemcard_movements_categories', 'item_code','store_id','items_movements_types', 
        'FK_table', 'FK_table_details', 'byan', 'quantity_befor_movement', 
        'quantity_after_move','quantity_befor_move_store','quantity_after_move_store','added_by', 'date', 'created_at', 'com_code'
    ];
}
