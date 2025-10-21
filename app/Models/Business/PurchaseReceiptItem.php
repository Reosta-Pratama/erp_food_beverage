<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class PurchaseReceiptItem extends Model
{
    //
    protected $table = 'purchase_receipt_items';
    protected $primaryKey = 'receipt_item_id';
    public $timestamps = false;

    protected $fillable = [
        'receipt_id',
        'po_item_id',
        'product_id',
        'lot_id',
        'quantity_received',
        'uom_id',
        'manufacture_date',
        'expiry_date',
        'condition',
        'notes',
    ];

    protected $casts = [
        'quantity_received' => 'decimal:4',
        'manufacture_date' => 'date',
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
