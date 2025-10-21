<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class PlannedProduction extends Model
{
    //
    protected $table = 'planned_productions';
    protected $primaryKey = 'planned_production_id';
    public $timestamps = false;

    protected $fillable = [
        'plan_id',
        'product_id',
        'planned_quantity',
        'uom_id',
        'target_date',
        'priority',
    ];

    protected $casts = [
        'planned_quantity' => 'decimal:4',
        'target_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
