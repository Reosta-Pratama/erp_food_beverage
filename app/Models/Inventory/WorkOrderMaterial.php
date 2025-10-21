<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class WorkOrderMaterial extends Model
{
    //
    protected $table = 'work_order_materials';
    protected $primaryKey = 'wom_id';
    public $timestamps = false;

    protected $fillable = [
        'work_order_id',
        'material_id',
        'quantity_required',
        'quantity_consumed',
        'uom_id',
    ];

    protected $casts = [
        'quantity_required' => 'decimal:4',
        'quantity_consumed' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
