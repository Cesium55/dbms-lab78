<?php

namespace App\Validators\Athletes;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AthleteCreateValidator
{
    public static $rules = [
        'name' => 'required|string|min:1|max:255',
        'main_sport_id' => 'required|int|min:1',
        'date_of_birth' => 'required|string|min:4'
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
