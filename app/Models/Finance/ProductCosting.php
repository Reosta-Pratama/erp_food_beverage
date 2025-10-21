<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class ProductCosting extends Model
{
    //
    protected $table = 'product_costing';
    protected $primaryKey = 'costing_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'material_cost',
        'labor_cost',
        'overhead_cost',
        'total_cost',
        'selling_price',
        'profit_margin',
        'effective_date',
    ];

    protected $casts = [
        'material_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'overhead_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'effective_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
