<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    //
    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'location_id',
        'lot_id',
        'quantity_on_hand',
        'quantity_reserved',
        'quantity_available',
        'reorder_point',
        'reorder_quantity',
    ];

    protected $casts = [
        'quantity_on_hand' => 'decimal:4',
        'quantity_reserved' => 'decimal:4',
        'quantity_available' => 'decimal:4',
        'reorder_point' => 'decimal:4',
        'reorder_quantity' => 'decimal:4',
        'last_updated' => 'datetime',
    ];
}
