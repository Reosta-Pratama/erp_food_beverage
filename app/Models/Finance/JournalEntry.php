<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    //
    protected $table = 'journal_entries';
    protected $primaryKey = 'journal_id';
    public $timestamps = false;

    protected $fillable = [
        'journal_code',
        'journal_date',
        'journal_type',
        'reference_type',
        'reference_id',
        'description',
        'total_debit',
        'total_credit',
        'status',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'journal_date' => 'date',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
