<?php

namespace App\Http\Controllers;

use App\Services\MatchesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchesController extends Controller
{
    public function create(Request $request, MatchesService $matchesService){

        foreach ($request->matches ?? [] as $match){


            $matchesService->create([
                "name" => $match["name"],
                "main_sport_id" => $match["main_sport_id"],
                "match_datetime" => $match["match_datetime"],
            ]);
        }

    }

    public function register_participants(int $id, Request $request, MatchesService $matchesService){
        foreach($request->participants as $participant){
            $matchesService->register_participant([
                "match_id" => $id,
                "participant_id" => $participant["id"],
                "is_team" => $participant["is_team"],
                "place" => $participant["place"],
                "score" => $participant["score"]
            ]);
        }
    }


    public function auto_rename(int $id){
        DB::select("CALL match_auto_rename(?)", [$id]);
    }


}
