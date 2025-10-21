<?php

namespace App\Models\QualityAssurance;

use Illuminate\Database\Eloquent\Model;

class SanitationInspection extends Model
{
    //
    protected $table = 'sanitation_inspections';
    protected $primaryKey = 'inspection_id';
    public $timestamps = false;

    protected $fillable = [
        'checklist_id',
        'warehouse_id',
        'inspection_date',
        'inspector_id',
        'score',
        'result',
        'findings',
        'corrective_actions',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'score' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
