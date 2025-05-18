<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamAthlete extends Model
{
    public $timestamps = false;

    protected $table = 'team_athlete';

    protected $fillable = ['team_id', 'athlete_id'];

    protected $primaryKey = null;
    public $incrementing = false;
}
