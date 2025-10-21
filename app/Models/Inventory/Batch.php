<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    //
    protected $table = 'batches';
    protected $primaryKey = 'batch_id';
    public $timestamps = false;

    protected $fillable = [
        'batch_code',
        'work_order_id',
        'product_id',
        'production_date',
        'quantity_produced',
        'quantity_approved',
        'quantity_rejected',
        'status',
    ];

    protected $casts = [
        'production_date' => 'date',
        'quantity_produced' => 'decimal:4',
        'quantity_approved' => 'decimal:4',
        'quantity_rejected' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
