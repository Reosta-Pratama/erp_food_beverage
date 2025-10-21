<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class AccountsPayable extends Model
{
    //
    protected $table = 'accounts_payable';
    protected $primaryKey = 'ap_id';

    protected $fillable = [
        'ap_code',
        'supplier_id',
        'po_id',
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
