<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
    //
    protected $table = 'sales_return_items';
    protected $primaryKey = 'sr_item_id';
    public $timestamps = false;

    protected $fillable = [
        'sr_id',
        'product_id',
        'lot_id',
        'quantity_returned',
        'uom_id',
        'unit_price',
        'line_total',
        'defect_type',
        'notes',
    ];

    protected $casts = [
        'quantity_returned' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
