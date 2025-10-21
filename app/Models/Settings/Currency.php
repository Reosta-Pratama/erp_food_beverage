<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    //
    protected $table = 'currencies';
    protected $primaryKey = 'currency_id';

    protected $fillable = [
        'currency_code',
        'currency_name',
        'symbol',
        'exchange_rate',
        'is_base_currency',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_base_currency' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
