<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    //
    protected $table = 'meetings';
    protected $primaryKey = 'meeting_id';

    protected $fillable = [
        'meeting_title',
        'meeting_agenda',
        'meeting_date',
        'start_time',
        'end_time',
        'location',
        'organizer_id',
        'status',
        'minutes',
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
