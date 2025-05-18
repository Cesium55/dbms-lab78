<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventMatch extends Model
{
    public $timestamps = false;

    protected $table = 'event_match';

    protected $fillable = ['event_id', 'match_id'];

    protected $primaryKey = null;
    public $incrementing = false;

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function match()
    {
        return $this->belongsTo(SportMatch::class, 'match_id');
    }
}
