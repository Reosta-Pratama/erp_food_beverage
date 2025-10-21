<?php

namespace App\Models\Logistics;

use Illuminate\Database\Eloquent\Model;

class DeliveryConfirmation extends Model
{
    //
    protected $table = 'delivery_confirmations';
    protected $primaryKey = 'confirmation_id';
    public $timestamps = false;

    protected $fillable = [
        'delivery_id',
        'delivery_date',
        'delivery_time',
        'recipient_name',
        'recipient_position',
        'signature_path',
        'delivery_notes',
        'proof_of_delivery_path',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
