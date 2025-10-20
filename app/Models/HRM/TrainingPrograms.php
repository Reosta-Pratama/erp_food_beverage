<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class TrainingPrograms extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'training_programs';
    
    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'training_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'training_name',
        'training_code',
        'description',
        'start_date',
        'end_date',
        'trainer_name',
        'location',
        'max_participants',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
