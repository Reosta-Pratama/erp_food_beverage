<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    //
    protected $table = 'tax_rates';
    protected $primaryKey = 'tax_id';
    public $timestamps = false;

    protected $fillable = [
        'tax_name',
        'tax_code',
        'tax_percentage',
        'tax_type',
        'is_active',
        'effective_date',
    ];

    protected $casts = [
        'tax_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'effective_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
