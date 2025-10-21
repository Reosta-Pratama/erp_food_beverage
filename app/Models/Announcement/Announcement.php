<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    //
    protected $table = 'announcements';
    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'announcement_title',
        'announcement_content',
        'priority',
        'publish_date',
        'expiry_date',
        'created_by',
        'target_audience',
        'is_active',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
