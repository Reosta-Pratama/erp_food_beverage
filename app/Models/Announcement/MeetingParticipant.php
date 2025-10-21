<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model
{
    //
    protected $table = 'meeting_participants';
    protected $primaryKey = 'participant_id';
    public $timestamps = false;

    protected $fillable = [
        'meeting_id',
        'employee_id',
        'attendance_status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
