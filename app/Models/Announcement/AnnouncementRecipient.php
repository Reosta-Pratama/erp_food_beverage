<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Model;

class AnnouncementRecipient extends Model
{
    //
    protected $table = 'announcement_recipients';
    protected $primaryKey = 'recipient_id';
    public $timestamps = false;

    protected $fillable = [
        'announcement_id',
        'user_id',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
