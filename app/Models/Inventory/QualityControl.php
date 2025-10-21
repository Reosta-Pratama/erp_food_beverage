<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class QualityControl extends Model
{
    //
    protected $table = 'quality_control';
    protected $primaryKey = 'qc_id';
    public $timestamps = false;

    protected $fillable = [
        'qc_code',
        'inspection_type',
        'work_order_id',
        'batch_id',
        'product_id',
        'inspection_date',
        'inspector_id',
        'result',
        'quantity_inspected',
        'quantity_passed',
        'quantity_failed',
        'remarks',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'quantity_inspected' => 'decimal:4',
        'quantity_passed' => 'decimal:4',
        'quantity_failed' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
