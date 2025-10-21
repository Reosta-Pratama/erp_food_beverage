<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    //
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'po_item_id';
    public $timestamps = false;

    protected $fillable = [
        'po_id',
        'product_id',
        'quantity_ordered',
        'quantity_received',
        'uom_id',
        'unit_price',
        'discount_percentage',
        'tax_percentage',
        'line_total',
        'expected_date',
        'notes',
    ];

    protected $casts = [
        'quantity_ordered' => 'decimal:4',
        'quantity_received' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'line_total' => 'decimal:2',
        'expected_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
