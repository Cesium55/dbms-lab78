<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class APIController extends Controller
{
    public function createAthlete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'main_sport_id' => 'nullable|integer',
            'date_of_birth' => 'nullable|date|before:today'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();

        $athlete = DB::selectOne("
            INSERT INTO athletes (name, main_sport_id, date_of_birth)
            VALUES (:name, :main_sport_id, :date_of_birth)
            RETURNING *
        ", $data);

        return response()->json($athlete);
    }

    public function createSport(Request $request)
    {
        $name = $request->name ?? "";

        $sport = DB::selectOne("
            INSERT INTO sports (name)
            VALUES (?)
            RETURNING *
        ", [$name]);

        return response()->json($sport);
    }

    public function deleteSport(int $id){
        DB::delete("DELETE FROM sports WHERE id=?", [$id]);
        return;
    }

    public function renameSport(int $id, Request $request){
        $name = $request->name ?? '';
        DB::update("UPDATE sports SET name=? WHERE id=?", [$name, $id]);
        return;
    }

    public function createTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'main_sport_id' => 'required|integer|exists:sports,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $team = DB::selectOne("
            INSERT INTO teams (name, main_sport_id)
            VALUES (:name, :main_sport_id)
            RETURNING *
        ", $validator->validated());

        return response()->json($team);
    }

    public function createTeamAthlete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required|integer|exists:teams,id',
            'athlete_id' => 'required|integer|exists:athletes,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $teamAthlete = DB::selectOne("
            INSERT INTO team_athlete (team_id, athlete_id)
            VALUES (:team_id, :athlete_id)
            RETURNING *
        ", $validator->validated());

        return response()->json($teamAthlete);
    }

    public function createEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:2',
            'description' => 'string',
            'main_sport_id' => 'nullable|integer|exists:sports,id',
            'start_datetime' => 'required|date',
            'finish_datetime' => 'required|date|after_or_equal:start_datetime'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $validator->validated();
        $data['description'] = $data['description'] ?? '';

        $event = DB::selectOne("
            INSERT INTO events (name, description, main_sport_id, start_datetime, finish_datetime)
            VALUES (:name, :description, :main_sport_id, :start_datetime, :finish_datetime)
            RETURNING *
        ", $data);

        return response()->json($event);
    }

    public function createMatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|min:2',
            'match_datetime' => 'required|date',
            'main_sport_id' => 'required|integer|exists:sports,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $match = DB::selectOne("
            INSERT INTO matches (name, match_datetime, main_sport_id)
            VALUES (:name, :match_datetime, :main_sport_id)
            RETURNING *
        ", $validator->validated());

        return response()->json($match);
    }

    public function createEventMatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer|exists:events,id',
            'match_id' => 'required|integer|exists:matches,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $eventMatch = DB::selectOne("
            INSERT INTO event_match (event_id, match_id)
            VALUES (:event_id, :match_id)
            RETURNING *
        ", $validator->validated());

        return response()->json($eventMatch);
    }

    public function createEventParticipant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|integer|exists:events,id',
            'participant_id' => 'required|integer',
            'is_team' => 'required|boolean',
            'place' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $eventParticipant = DB::selectOne("
            INSERT INTO event_participant (event_id, participant_id, is_team, place)
            VALUES (:event_id, :participant_id, :is_team, :place)
            RETURNING *
        ", $validator->validated());

        return response()->json($eventParticipant);
    }

    public function createMatchParticipant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_id' => 'required|integer',
            'participant_id' => 'required|integer',
            'is_team' => 'required|boolean',
            'place' => 'nullable|integer|min:1',
            'score' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $matchParticipant = DB::selectOne("
            INSERT INTO match_participant (match_id, participant_id, is_team, place, score)
            VALUES (:match_id, :participant_id, :is_team, :place, :score)
            RETURNING *
        ", $validator->validated());

        return response()->json($matchParticipant);
    }
}
