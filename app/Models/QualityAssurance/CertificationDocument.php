<?php

namespace App\Models\QualityAssurance;

use Illuminate\Database\Eloquent\Model;

class CertificationDocument extends Model
{
    //
    protected $table = 'certification_documents';
    protected $primaryKey = 'doc_id';

    protected $fillable = [
        'certification_type',
        'certification_number',
        'issuing_authority',
        'issue_date',
        'expiry_date',
        'document_path',
        'status',
        'responsible_person_id',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
