<?php

namespace App\Http\Controllers;

use App\Services\AthletesService;
use Illuminate\Http\Request;

class AthletesController extends Controller
{

    public function create(Request $request, AthletesService $athletesService){

        foreach ($request->athletes ?? [] as $athlete){
            $athletesService->create([
                "name" => $athlete["name"],
                "main_sport_id" => $athlete["main_sport_id"],
                "date_of_birth" => $athlete["date_of_birth"]
            ]);
        }

    }

    // public function get_all(SportsService $sportsService){
    //     return $sportsService->get_all();
    // }

    // public function get(int $id, SportsService $sportsService){
    //     return $sportsService->get(["id" => $id]);
    // }

    // public function update(Request $request, int $id, SportsService $sportsService){
    //     return $sportsService->update([
    //         "id" => $id,
    //         "name" => $request->name ?? ""
    //     ]);
    // }

    // public function delete(int $id, SportsService $sportsService){
    //     return $sportsService->delete(["id" => $id]);
    // }

}
