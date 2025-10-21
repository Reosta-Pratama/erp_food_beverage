<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    //
    protected $table = 'sales_returns';
    protected $primaryKey = 'sr_id';
    public $timestamps = false;

    protected $fillable = [
        'sr_code',
        'customer_id',
        'delivery_id',
        'return_date',
        'return_reason',
        'status',
        'total_amount',
        'refund_method',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
