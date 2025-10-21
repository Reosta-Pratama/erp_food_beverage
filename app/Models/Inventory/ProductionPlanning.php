<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class ProductionPlanning extends Model
{
    //
    protected $table = 'production_planning';
    protected $primaryKey = 'plan_id';

    protected $fillable = [
        'plan_code',
        'plan_date',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'plan_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
