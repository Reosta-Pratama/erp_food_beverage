<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class BomItem extends Model
{
    //
    protected $table = 'bom_items';
    protected $primaryKey = 'bom_item_id';
    public $timestamps = false;

    protected $fillable = [
        'bom_id',
        'material_id',
        'quantity_required',
        'uom_id',
        'item_type',
        'scrap_percentage',
    ];

    protected $casts = [
        'quantity_required' => 'decimal:4',
        'scrap_percentage' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
