<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Model;

class BroadcastMessage extends Model
{
    //
    protected $table = 'broadcast_messages';
    protected $primaryKey = 'broadcast_id';
    public $timestamps = false;

    protected $fillable = [
        'message_title',
        'message_content',
        'message_type',
        'sender_id',
        'sent_at',
        'target_roles',
        'is_urgent',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_urgent' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
