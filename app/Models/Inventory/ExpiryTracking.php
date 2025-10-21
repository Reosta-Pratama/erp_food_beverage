<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class ExpiryTracking extends Model
{
    //
    protected $table = 'expiry_tracking';
    protected $primaryKey = 'expiry_id';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'lot_id',
        'warehouse_id',
        'expiry_date',
        'quantity',
        'status',
        'alert_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity' => 'decimal:4',
        'alert_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
