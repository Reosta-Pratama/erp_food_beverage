<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    //
    protected $table = 'budgets';
    protected $primaryKey = 'budget_id';

    protected $fillable = [
        'budget_name',
        'fiscal_year',
        'cost_center_id',
        'account_id',
        'budgeted_amount',
        'actual_amount',
        'variance',
        'period_type',
        'period_number',
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
