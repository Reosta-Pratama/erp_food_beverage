<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class AccountsReceivable extends Model
{
    //
    protected $table = 'accounts_receivable';
    protected $primaryKey = 'ar_id';

    protected $fillable = [
        'ar_code',
        'customer_id',
        'so_id',
        'invoice_date',
        'due_date',
        'invoice_amount',
        'paid_amount',
        'balance_amount',
        'status',
        'payment_terms',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'invoice_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
