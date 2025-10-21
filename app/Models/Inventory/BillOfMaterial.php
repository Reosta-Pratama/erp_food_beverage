<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    //
    protected $table = 'bill_of_materials';
    protected $primaryKey = 'bom_id';

    protected $fillable = [
        'product_id',
        'bom_version',
        'effective_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
