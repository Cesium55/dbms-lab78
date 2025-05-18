<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportMatch extends Model
{

    public $timestamps = false;
    protected $table = "matches";
    protected $fillable = ['name', 'match_datetime', 'main_sport_id'];

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'main_sport_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_match');
    }

    public function participants()
    {
        return $this->hasMany(MatchParticipant::class, 'match_id');
    }
}
