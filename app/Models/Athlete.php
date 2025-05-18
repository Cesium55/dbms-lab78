<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{

    public $timestamps = false;
    protected $fillable = ['name', 'main_sport_id', 'date_of_birth'];

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'main_sport_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_athlete');
    }
}
