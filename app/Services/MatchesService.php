<?php

namespace App\Services;

use App\Models\EventParticipant;
use App\Models\MatchParticipant;
use App\Models\SportMatch;
use App\Validators\Events\EventCreateValidator;
use App\Validators\Matches\MatchCreateValidator;
use App\Validators\Matches\MatchParticipantCreateValidator;
use Illuminate\Support\Facades\DB;

class MatchesService
{
    public function create($data)
    {
        $validated = MatchCreateValidator::validate($data);

        $name = $validated["name"];
        $match_datetime = $validated["match_datetime"];
        $main_sport_id = $validated["main_sport_id"];

        try {
            SportMatch::create([
                "name" => $validated["name"],
                "match_datetime" => $validated["match_datetime"],
                "main_sport_id" => $validated["main_sport_id"]

            ]);
        } catch (\Throwable $e) {
            abort(500, 'Error: ' . $e->getMessage());
        }
    }



    public function get_all(){
        $matches = DB::select("SELECT * FROM matches;");

        return ["matches" => $matches];
    }

    public function get_participants(int $match_id){
        return DB::select("SELECT * FROM get_match_participants(?);", [$match_id]);
    }

    public function register_participant($data){


        $validated = MatchParticipantCreateValidator::validate($data);


        return MatchParticipant::create([
            "match_id" => $validated['match_id'],
            "participant_id" => $validated['participant_id'],
            "is_team" => $validated['is_team'],
            "place" => $validated['place'],
            "score"  => $validated['score']
        ]);
    }

    public function get(int $id){
        return SportMatch::findOrFail($id);
    }

}
