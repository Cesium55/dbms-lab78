<?php

use App\Http\Controllers\MatchesController;
use App\Http\Controllers\SportsController;
use App\Http\Controllers\AthletesController;
use App\Http\Controllers\EventsController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\APIController;

Route::get('/', function () {
    return "123";
});


Route::prefix("sports")->group(function(){

    Route::post("/", [SportsController::class, "create"]);
    Route::get("/{id}", [SportsController::class, "get"]);
    Route::get("/", [SportsController::class, "get_all"]);
    Route::delete("/{id}", [SportsController::class, "delete"]);
    Route::put("/{id}", [SportsController::class, "update"]);

});

Route::prefix("athletes")->group(function(){

    Route::post("/", [AthletesController::class, "create"]);

});

Route::prefix("events")->group(function(){

    Route::post("/", [EventsController::class, "create"]);
    Route::post("/{id}/register-participants", [EventsController::class, "register_participants"]);
    Route::post("/{id}/register-matches", [EventsController::class, "register_matches"]);
});


Route::prefix("matches")->group(function(){

    Route::post("/", [MatchesController::class, "create"]);
    Route::post("/{id}/register-participants", [MatchesController::class, "register_participants"]);
});



Route::prefix('v1')->group(function () {
    Route::post('/athletes', [APIController::class, 'createAthlete']);
    Route::post('/sports', [APIController::class, 'createSport'])->name("create_sport");
    Route::post('/teams', [APIController::class, 'createTeam']);
    Route::post('/teams/{team}/athletes', [APIController::class, 'createTeamAthlete']);
    Route::post('/events', [APIController::class, 'createEvent']);
    Route::post('/matches', [APIController::class, 'createMatch']);
    Route::post('/events/{event}/matches', [APIController::class, 'createEventMatch']);
    Route::post('/events/{event}/participants', [APIController::class, 'createEventParticipant']);
    Route::post('/matches/{match}/participants', [APIController::class, 'createMatchParticipant']);

    Route::post('/matches/{match}/auto_rename', [MatchesController::class, 'auto_rename'])->name("match_auto_rename");

    Route::delete('/sports/{id}', [APIController::class, 'deleteSport'])->name("delete_sport");
    Route::put('/sports/{id}', [APIController::class, 'renameSport'])->name("rename_sport");
});
