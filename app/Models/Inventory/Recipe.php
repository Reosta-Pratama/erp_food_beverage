<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    //
    protected $table = 'recipes';
    protected $primaryKey = 'recipe_id';

    protected $fillable = [
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
}
