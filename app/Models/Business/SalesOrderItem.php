<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    //
    protected $table = 'sales_order_items';
    protected $primaryKey = 'so_item_id';
    public $timestamps = false;

    protected $fillable = [
        'so_id',
        'product_id',
        'quantity_ordered',
        'quantity_delivered',
        'uom_id',
        'unit_price',
        'discount_percentage',
        'tax_percentage',
        'line_total',
        'notes',
    ];

    protected $casts = [
        'quantity_ordered' => 'decimal:4',
        'quantity_delivered' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'line_total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
