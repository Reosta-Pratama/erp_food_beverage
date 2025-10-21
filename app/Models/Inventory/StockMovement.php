<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    //
    protected $table = 'stock_movements';
    protected $primaryKey = 'movement_id';
    public $timestamps = false;

    protected $fillable = [
        'movement_code',
        'movement_type',
        'product_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'from_location_id',
        'to_location_id',
        'lot_id',
        'quantity',
        'uom_id',
        'movement_date',
        'performed_by',
        'reference_type',
        'reference_id',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'movement_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
