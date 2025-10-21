<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    //
    protected $table = 'deliveries';
    protected $primaryKey = 'delivery_id';

    protected $fillable = [
        'delivery_code',
        'so_id',
        'customer_id',
        'delivery_date',
        'warehouse_id',
        'driver_id',
        'vehicle_id',
        'shipping_address',
        'status',
        'departure_time',
        'arrival_time',
        'delivered_by',
        'signature_path',
        'notes',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
