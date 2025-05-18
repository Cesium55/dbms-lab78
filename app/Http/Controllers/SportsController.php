<?php

namespace App\Http\Controllers;

use App\Services\SportsService;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;

class SportsController extends Controller
{
    public function create(Request $request, SportsService $sportsService){

        foreach ($request->sports ?? [] as $sport){
            $sportsService->create(["name" => $sport['name']]);
        }

    }

    public function get_all(SportsService $sportsService){
        return $sportsService->get_all();
    }

    public function get(int $id, SportsService $sportsService){
        return $sportsService->get(["id" => $id]);
    }

    public function update(Request $request, int $id, SportsService $sportsService){
        return $sportsService->update([
            "id" => $id,
            "name" => $request->name ?? ""
        ]);
    }

    public function delete(int $id, SportsService $sportsService){
        return $sportsService->delete(["id" => $id]);
    }
}
