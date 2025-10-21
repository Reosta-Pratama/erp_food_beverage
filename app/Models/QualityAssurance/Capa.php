<?php

namespace App\Models\QualityAssurance;

use Illuminate\Database\Eloquent\Model;

class Capa extends Model
{
    //
    protected $table = 'capa';
    protected $primaryKey = 'capa_id';

    protected $fillable = [
        'capa_code',
        'ncr_id',
        'action_type',
        'root_cause_analysis',
        'corrective_action',
        'preventive_action',
        'responsible_person_id',
        'target_date',
        'completion_date',
        'status',
        'verification_notes',
        'verified_by',
    ];

    protected $casts = [
        'target_date' => 'date',
        'completion_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
