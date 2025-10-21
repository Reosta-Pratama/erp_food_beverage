<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    //
    protected $table = 'spare_parts';
    protected $primaryKey = 'spare_part_id';

    protected $fillable = [
        'part_code',
        'part_name',
        'machine_id',
        'quantity_on_hand',
        'reorder_point',
        'reorder_quantity',
        'uom_id',
        'unit_cost',
        'supplier_id',
    ];

    protected $casts = [
        'quantity_on_hand' => 'decimal:4',
        'reorder_point' => 'decimal:4',
        'reorder_quantity' => 'decimal:4',
        'unit_cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
