<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_code',
        'product_name',
        'product_type',
        'category_id',
        'uom_id',
        'standard_cost',
        'selling_price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'standard_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

     /**
     * Get BOMs for this product
     */
    public function billsOfMaterials()
    {
        return $this->hasMany(BillOfMaterial::class, 'product_id', 'product_id');
    }
    
    /**
     * Get recipes for this product
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'product_id', 'product_id');
    }
}
