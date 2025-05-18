<?php

namespace App\Services;

use App\Models\EventMatch;
use App\Models\EventParticipant;
use App\Validators\Events\EventCreateValidator;
use Illuminate\Support\Facades\DB;

class EventsService
{
    public function create($data)
    {
        $validated = EventCreateValidator::validate($data);

        $name = $validated["name"];
        $main_sport_id = $validated["main_sport_id"];
        $start_datetime = $validated["start_datetime"];
        $finish_datetime = $validated["finish_datetime"];
        $description = $validated["description"];

        try {
            DB::insert("INSERT INTO events(name, main_sport_id, start_datetime, finish_datetime, description) VALUES (?, ?, ?, ?, ?)", [$name, $main_sport_id, $start_datetime, $finish_datetime, $description]);
        } catch (\Throwable $e) {
            abort(500, 'Error: ' . $e->getMessage());
        }
    }



    public function get_all(){
        $events = DB::select("SELECT * FROM events;");

        return ["events" => $events];
    }

    public function get_participants(int $event_id){
        return DB::select("SELECT * FROM get_event_participants(?);", [$event_id]);
    }

    public function get_matches(int $event_id){
        return DB::select("SELECT * FROM get_event_matches(?);", [$event_id]);
    }

    public function register_participant($data){
        return EventParticipant::create([
            "event_id" => $data['event_id'],
            "participant_id" => $data['participant_id'],
            "is_team" => $data['is_team'],
            "place" => $data['place']
        ]);
    }

    public function register_match($data){
        return EventMatch::create([
            "event_id" => $data['event_id'],
            "match_id" => $data['match_id']
        ]);
    }

}
