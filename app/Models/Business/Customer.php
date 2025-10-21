<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'customer_code',
        'customer_name',
        'contact_person',
        'email',
        'phone',
        'shipping_address',
        'billing_address',
        'city',
        'country',
        'tax_id',
        'payment_terms',
        'credit_limit',
        'customer_type',
        'is_active',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
