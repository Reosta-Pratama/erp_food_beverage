<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class PurchaseReceipt extends Model
{
    //
    protected $table = 'purchase_receipts';
    protected $primaryKey = 'receipt_id';
    public $timestamps = false;

    protected $fillable = [
        'receipt_code',
        'po_id',
        'supplier_id',
        'receipt_date',
        'warehouse_id',
        'received_by',
        'status',
        'notes',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
