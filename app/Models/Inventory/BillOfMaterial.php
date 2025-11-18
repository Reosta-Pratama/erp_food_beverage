<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    //
    protected $table = 'bill_of_materials';
    protected $primaryKey = 'bom_id';

    protected $fillable = [
        'bom_code',
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

    /**
     * Get the product that this BOM belongs to
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    
    /**
     * Get all items/materials in this BOM
     */
    public function items()
    {
        return $this->hasMany(BOMItem::class, 'bom_id', 'bom_id');
    }
}
