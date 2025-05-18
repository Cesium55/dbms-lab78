<?php

namespace App\Services;

use App\Models\Team;
use Illuminate\Support\Facades\DB;

class TeamsService{
    public function get(int $id){
        return Team::findOrFail($id);
    }

    public function get_events($id){
        $events = DB::select("SELECT * FROM events e
        JOIN event_participant ep ON ep.participant_id=? AND ep.is_team AND e.id = ep.event_id
        ", [$id]);

        return $events;
    }

    public function get_matches($id){
        $matches = DB::select("SELECT * FROM matches m
        JOIN match_participant mp ON mp.participant_id=? AND mp.is_team AND m.id = mp.match_id
        ", [$id]);

        return $matches;
    }

    public function get_athletes($id){
        $athletes = DB::select("SELECT * FROM athletes a
        JOIN team_athlete ta ON a.id=ta.athlete_id AND ta.team_id=?
        ", [$id]);

        return $athletes;
    }
}
