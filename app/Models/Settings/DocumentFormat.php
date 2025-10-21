<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class DocumentFormat extends Model
{
    //
    protected $table = 'document_formats';
    protected $primaryKey = 'format_id';

    protected $fillable = [
        'document_type',
        'template_name',
        'template_content',
        'file_format',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
