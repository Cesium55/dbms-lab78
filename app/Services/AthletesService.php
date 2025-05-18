<?php

namespace App\Services;

use App\Validators\Athletes\AthleteCreateValidator;
use Illuminate\Support\Facades\DB;

class AthletesService
{
    public function create($data)
    {
        $validated = AthleteCreateValidator::validate($data);

        $name = $validated["name"];
        $main_sport_id = $validated["main_sport_id"];
        $date_of_birth = $validated["date_of_birth"];

        try {
            DB::insert("INSERT INTO athletes(name, main_sport_id, date_of_birth) VALUES (?, ?, ?)", [$name, $main_sport_id, $date_of_birth]);
        } catch (\Throwable $e) {
            abort(500, 'Error: ' . $e->getMessage());
        }
    }

    public function get(int $id){
        return DB::select("SELECT * FROM athletes where id=?", [$id]);
    }

    public function get_events($id){
        $events = DB::select("SELECT * FROM events e
        JOIN event_participant ep ON ep.participant_id=? AND (NOT ep.is_team) AND e.id = ep.event_id
        ", [$id]);

        return $events;
    }

    public function get_matches($id){
        $matches = DB::select("SELECT * FROM matches m
        JOIN match_participant mp ON mp.participant_id=? AND (NOT mp.is_team) AND m.id = mp.match_id
        ", [$id]);

        return $matches;
    }

}
