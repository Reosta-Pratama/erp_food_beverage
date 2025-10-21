<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    //
    protected $table = 'work_orders';
    protected $primaryKey = 'work_order_id';

    protected $fillable = [
        'work_order_code',
        'product_id',
        'bom_id',
        'quantity_ordered',
        'quantity_produced',
        'scheduled_start',
        'scheduled_end',
        'actual_start',
        'actual_end',
        'status',
        'assigned_to',
        'instructions',
    ];

    protected $casts = [
        'quantity_ordered' => 'decimal:4',
        'quantity_produced' => 'decimal:4',
        'scheduled_start' => 'date',
        'scheduled_end' => 'date',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
