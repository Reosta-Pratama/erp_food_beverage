<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    //
    protected $table = 'cost_centers';
    protected $primaryKey = 'cost_center_id';
    public $timestamps = false;

    protected $fillable = [
        'cost_center_code',
        'cost_center_name',
        'department_id',
        'manager_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
