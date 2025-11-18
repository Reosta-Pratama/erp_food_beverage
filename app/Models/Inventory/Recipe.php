<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    //
    protected $table = 'recipes';
    protected $primaryKey = 'recipe_id';

    protected $fillable = [
        'recipe_code',
        'product_id',
        'recipe_name',
        'recipe_version',
        'instructions',
        'batch_size',
        'uom_id',
        'preparation_time',
        'cooking_time',
        'is_active',
    ];

    protected $casts = [
        'batch_size' => 'decimal:4',
        'preparation_time' => 'decimal:2',
        'cooking_time' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product this recipe produces
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    
    /**
     * Get all ingredients in this recipe
     */
    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class, 'recipe_id', 'recipe_id')
            ->orderBy('sequence_order');
    }
    
    /**
     * Get the unit of measure for batch size
     */
    public function unitOfMeasure()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'uom_id', 'uom_id');
    }
}
