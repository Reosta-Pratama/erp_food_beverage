<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    //
    protected $table = 'recipe_ingredients';
    protected $primaryKey = 'ingredient_id';
    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'material_id',
        'quantity',
        'uom_id',
        'preparation_notes',
        'sequence_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'sequence_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the recipe this ingredient belongs to
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'recipe_id');
    }
    
    /**
     * Get the material/ingredient
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
