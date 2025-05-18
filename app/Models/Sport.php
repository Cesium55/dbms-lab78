<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{

    public $timestamps = false;
    protected $fillable = ['name'];

    public function athletes()
    {
        return $this->hasMany(Athlete::class, 'main_sport_id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'main_sport_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'main_sport_id');
    }
}
