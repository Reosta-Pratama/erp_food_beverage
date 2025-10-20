<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class TrainingParticipants extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'training_participants';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'participant_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'training_id',
        'employee_id',
        'status',
        'score',
        'is_certified',
        'certificate_date',
        'certificate_number',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
