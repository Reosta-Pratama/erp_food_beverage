<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    //
    protected $table = 'lots';
    protected $primaryKey = 'lot_id';
    public $timestamps = false;

    protected $fillable = [
        'lot_code',
        'product_id',
        'manufacture_date',
        'expiry_date',
        'quantity',
        'status',
        'supplier_id',
        'notes',
    ];

    protected $casts = [
        'manufacture_date' => 'date',
        'expiry_date' => 'date',
        'quantity' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
