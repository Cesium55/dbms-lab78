<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchParticipant extends Model
{
    public $timestamps = false;

    protected $table = 'match_participant';

    protected $fillable = ['match_id', 'participant_id', 'is_team', 'place', 'score'];

    protected $primaryKey = null;
    public $incrementing = false;
}
