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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
