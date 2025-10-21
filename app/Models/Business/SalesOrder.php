<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    //
    protected $table = 'sales_orders';
    protected $primaryKey = 'so_id';

    protected $fillable = [
        'so_code',
        'customer_id',
        'order_date',
        'requested_delivery',
        'confirmed_delivery',
        'status',
        'order_type',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_cost',
        'total_amount',
        'payment_status',
        'sales_person_id',
        'created_by',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'requested_delivery' => 'date',
        'confirmed_delivery' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
