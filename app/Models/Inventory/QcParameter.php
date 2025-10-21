<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class QcParameter extends Model
{
    //
    protected $table = 'qc_parameters';
    protected $primaryKey = 'parameter_id';
    public $timestamps = false;

    protected $fillable = [
        'qc_id',
        'parameter_name',
        'standard_value',
        'actual_value',
        'unit',
        'is_passed',
    ];

    protected $casts = [
        'is_passed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
