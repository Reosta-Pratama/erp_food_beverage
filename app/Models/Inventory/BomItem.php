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

    /**
     * Get the BOM this item belongs to
     */
    public function billOfMaterial()
    {
        return $this->belongsTo(BillOfMaterial::class, 'bom_id', 'bom_id');
    }
    
    /**
     * Get the material/product
     */
    public function material()
    {
        return $this->belongsTo(Product::class, 'material_id', 'product_id');
    }
    
    /**
     * Get the unit of measure
     */
    public function unitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id', 'uom_id');
    }
}
