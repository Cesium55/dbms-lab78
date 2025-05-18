<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    public $timestamps = false;
    protected $fillable = ['name', 'description', 'main_sport_id', 'start_datetime', 'finish_datetime'];

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'main_sport_id');
    }

    public function matches()
    {
        return $this->belongsToMany(SportMatch::class, 'event_match');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class, 'event_id');
    }
}
