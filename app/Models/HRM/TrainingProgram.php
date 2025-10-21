<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    //
    protected $table = 'training_programs';
    protected $primaryKey = 'training_id';
    public $timestamps = false;

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

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
