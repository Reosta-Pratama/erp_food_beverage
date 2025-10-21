<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    //
    protected $table = 'notification_settings';
    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'user_id',
        'notification_type',
        'email_enabled',
        'push_enabled',
        'sms_enabled',
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'sms_enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
