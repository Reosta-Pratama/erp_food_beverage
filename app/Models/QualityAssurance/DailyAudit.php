<?php

namespace App\Models\QualityAssurance;

use Illuminate\Database\Eloquent\Model;

class DailyAudit extends Model
{
    //
    protected $table = 'daily_audits';
    protected $primaryKey = 'audit_id';
    public $timestamps = false;

    protected $fillable = [
        'audit_type',
        'audit_date',
        'auditor_id',
        'department_id',
        'area_audited',
        'compliance_score',
        'findings',
        'recommendations',
        'status',
    ];

    protected $casts = [
        'audit_date' => 'date',
        'compliance_score' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
