<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    //
    protected $table = 'chart_of_accounts';
    protected $primaryKey = 'account_id';
    public $timestamps = false;

    protected $fillable = [
        'account_code',
        'account_name',
        'account_type',
        'parent_account_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
