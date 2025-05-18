<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\Sport;
use App\Services\AthletesService;
use App\Services\EventsService;
use App\Services\MatchesService;
use App\Services\SportsService;
use App\Services\TeamsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebController extends Controller
{
    public function index(SportsService $sportsService){


        $sports = $sportsService->get_all()["sports"];
        // $sports = Sport::all();

        return view("mainpage", compact('sports'));

    }

    public function sport(int $id){

        $sport = Sport::findOrFail($id);

        $events = Event::where("main_sport_id", $id)->get();

        return view("sport", compact("sport", "events"));
    }

    public function event(int $id, EventsService $eventsService){
        $event = Event::findOrFail($id);

        $event->participants = $eventsService->get_participants($id);
        $event->matches = $eventsService->get_matches($id);

        return view("event", compact("event"));

    }

    public function match(int $id, MatchesService $matchesService){
        $match = $matchesService->get($id);
        $participants = $matchesService->get_participants($id);

        return view("match", compact("match", "participants"));
    }

    public function athlete(int $id, AthletesService $athletesService){
        $athlete = Athlete::findOrFail($id);
        $sport = Sport::findOrFail($athlete->main_sport_id);
        $events = $athletesService->get_events($id);
        $matches = $athletesService->get_matches($id);

        return view("athlete", compact("athlete", "events", "matches", "sport"));
    }

    public function team(int $id, TeamsService $teamsService){
        $team = $teamsService->get($id);
        $athletes = $teamsService->get_athletes($id);
        $matches = $teamsService->get_matches($id);
        $events = $teamsService->get_events($id);

        return view("team", compact("team", "athletes", "matches", "events"));
    }

    public function top_athletes(){
        $is_team = false;
        $topParticipants = DB::select("SELECT * from top_10_athletes");

        return view("top", compact("is_team", "topParticipants"));
    }

    public function top_teams(){
        $is_team = true;
        $topParticipants = DB::select("SELECT * from top_10_teams");

        return view("top", compact("is_team", "topParticipants"));
    }
}
