<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    //
    protected $table = 'machines';
    protected $primaryKey = 'machine_id';

    protected $fillable = [
        'machine_code',
        'machine_name',
        'machine_type',
        'manufacturer',
        'model',
        'serial_number',
        'purchase_date',
        'installation_date',
        'warehouse_id',
        'status',
        'specifications',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'installation_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
