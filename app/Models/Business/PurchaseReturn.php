<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    //
    protected $table = 'purchase_returns';
    protected $primaryKey = 'pr_id';
    public $timestamps = false;

    protected $fillable = [
        'pr_code',
        'supplier_id',
        'receipt_id',
        'return_date',
        'return_reason',
        'status',
        'total_amount',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
