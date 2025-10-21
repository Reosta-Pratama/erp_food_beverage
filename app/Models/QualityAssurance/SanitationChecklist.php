<?php

namespace App\Models\QualityAssurance;

use Illuminate\Database\Eloquent\Model;

class SanitationChecklist extends Model
{
    //
    protected $table = 'sanitation_checklists';
    protected $primaryKey = 'checklist_id';
    public $timestamps = false;

    protected $fillable = [
        'checklist_name',
        'area_type',
        'checklist_items',
        'is_active',
    ];

    protected $casts = [
        'checklist_items' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
