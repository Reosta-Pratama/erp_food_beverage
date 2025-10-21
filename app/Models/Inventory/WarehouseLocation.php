<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class WarehouseLocation extends Model
{
    //
    protected $table = 'warehouse_locations';
    protected $primaryKey = 'location_id';
    public $timestamps = false;

    protected $fillable = [
        'warehouse_id',
        'location_code',
        'location_name',
        'aisle',
        'rack',
        'bin',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
