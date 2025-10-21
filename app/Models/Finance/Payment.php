<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    protected $fillable = [
        'payment_code',
        'payment_type',
        'supplier_id',
        'customer_id',
        'ap_id',
        'ar_id',
        'payment_date',
        'payment_amount',
        'payment_method',
        'bank_account',
        'reference_number',
        'status',
        'processed_by',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'payment_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
