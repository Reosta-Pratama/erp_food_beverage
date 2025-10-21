<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class EmailSetting extends Model
{
    //
    protected $table = 'email_settings';
    protected $primaryKey = 'setting_id';

    protected $fillable = [
        'event_type',
        'email_template',
        'subject_template',
        'is_enabled',
        'recipient_type',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
