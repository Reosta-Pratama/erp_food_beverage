<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    protected $table = 'warehouses';
    protected $primaryKey = 'warehouse_id';
    public $timestamps = false;

    protected $fillable = [
        'warehouse_code',
        'warehouse_name',
        'warehouse_type',
        'address',
        'manager_id',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
