<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    public $timestamps = false;
    protected $fillable = ['name', 'main_sport_id'];

    public function sport()
    {
        return $this->belongsTo(Sport::class, 'main_sport_id');
    }

    public function athletes()
    {
        return $this->belongsToMany(Athlete::class, 'team_athlete');
    }
}
