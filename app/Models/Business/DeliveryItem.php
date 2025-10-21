<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class DeliveryItem extends Model
{
    //
    protected $table = 'delivery_items';
    protected $primaryKey = 'delivery_item_id';
    public $timestamps = false;

    protected $fillable = [
        'delivery_id',
        'so_item_id',
        'product_id',
        'lot_id',
        'quantity_delivered',
        'uom_id',
        'condition',
        'notes',
    ];

    protected $casts = [
        'quantity_delivered' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
