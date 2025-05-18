<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    public $timestamps = false;

    protected $table = 'event_participant';

    protected $fillable = ['event_id', 'participant_id', 'is_team', 'place'];

    protected $primaryKey = null;
    public $incrementing = false;
}
