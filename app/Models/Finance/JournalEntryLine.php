<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class JournalEntryLine extends Model
{
    //
    protected $table = 'journal_entry_lines';
    protected $primaryKey = 'line_id';
    public $timestamps = false;

    protected $fillable = [
        'journal_id',
        'account_id',
        'debit_amount',
        'credit_amount',
        'description',
    ];

    protected $casts = [
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
