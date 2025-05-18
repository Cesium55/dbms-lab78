<?php

namespace App\Services;

use App\Validators\Sports\SportCreateValidator;
use App\Validators\Sports\SportUpdateValidator;
use Illuminate\Support\Facades\DB;

class SportsService
{
    public function create($data){
        $validated = SportCreateValidator::validate($data);
        $name = $validated["name"];

        $sport = DB::select("SELECT * FROM sports where name=?;", [$name]);

        if($sport){
            abort(409, "Such sport has been already created");
        }


        DB::insert("INSERT INTO sports(name) values (?);", [$name]);

        return DB::select("SELECT * FROM sports where name=?;", [$name]);
    }

    public function get_all(){
        $sports = DB::select("select * from sports;");

        return ["sports" => $sports];
    }

    public function delete($data){
        $sport_id = $data["id"];

        DB::delete("DELETE FROM sports WHERE id=?;", [$sport_id]);
    }


    public function update($data){
        $validated = SportUpdateValidator::validate($data);
        $name = $validated["name"];
        $id = $validated["id"];

        $sport = DB::select("SELECT * FROM sports where id=?;", [$id]);

        if(!$sport){
            abort(404);
        }


        return DB::select("SELECT * FROM sports WHERE id=?", [$id]);
    }

    public function get($data){
        $id = $data["id"];

        $sport = DB::select("SELECT * FROM sports WHERE id=?;", [$id]);

        if (count($sport) == 0){
            abort(404);
        }

        return ["sport" => $sport[0]];
    }
}
