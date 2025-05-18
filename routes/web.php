<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebController::class, "index"])->name('mainpage');
Route::get('/sport/{id}', [WebController::class, "sport"])->name('sport');
Route::get('/event/{id}', [WebController::class, "event"])->name('event');
Route::get('/match/{id}', [WebController::class, "match"])->name('match');
Route::get('/athlete/{id}', [WebController::class, "athlete"])->name('athlete');
Route::get('/team/{id}', [WebController::class, "team"])->name('team');
Route::get('/top_athletes', [WebController::class, "top_athletes"])->name('top_athletes');
Route::get('/top_teams', [WebController::class, "top_teams"])->name('top_teams');
