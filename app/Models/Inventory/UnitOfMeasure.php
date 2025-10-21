<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    //
    protected $table = 'units_of_measure';
    protected $primaryKey = 'uom_id';
    public $timestamps = false;

    protected $fillable = [
        'uom_name',
        'uom_code',
        'uom_type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
