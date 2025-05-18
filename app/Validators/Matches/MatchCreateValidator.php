<?php

namespace App\Validators\Matches;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MatchCreateValidator
{
    public static $rules = [
        'name' => 'string',
        'main_sport_id' => 'required|int|min:1',
        'match_datetime' => 'required|string|min:4'
    ];

    public static function validate($data)
    {
        $validator = Validator::make($data, self::$rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
