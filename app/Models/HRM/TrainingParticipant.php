<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class TrainingParticipant extends Model
{
    //
    protected $table = 'training_participants';
    protected $primaryKey = 'participant_id';
    public $timestamps = false;

    protected $fillable = [
        'training_id',
        'employee_id',
        'status',
        'score',
        'is_certified',
        'certificate_date',
        'certificate_number',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'is_certified' => 'boolean',
        'certificate_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
