<?php

namespace App\Http\Controllers;

use App\Services\EventsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventsController extends Controller
{
    public function create(Request $request, EventsService $eventsService){
        $length = count($request->events);
        Log::info("creating events $length");

        foreach ($request->events ?? [] as $event){
            $name = $event["name"];
            Log::info("creating event '$name'");


            $eventsService->create([
                "name" => $event["name"],
                "main_sport_id" => $event["main_sport_id"],
                "start_datetime" => $event["start_datetime"],
                "finish_datetime" => $event["finish_datetime"],
                "description" => $event["description"]
            ]);
        }

    }

    public function register_participants(int $id, Request $request, EventsService $eventsService){
        foreach($request->participants as $participant){
            $eventsService->register_participant([
                "event_id" => $id,
                "participant_id" => $participant["id"],
                "is_team" => $participant["is_team"],
                "place" => $participant["place"]
            ]);
        }
    }

    public function register_matches(int $id, Request $request, EventsService $eventsService){
        foreach($request->matches as $match){
            $eventsService->register_match([
                "event_id" => $id,
                "match_id" => $match["id"],
            ]);
        }
    }


}
