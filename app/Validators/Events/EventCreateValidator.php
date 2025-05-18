<?php

namespace App\Validators\Events;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EventCreateValidator
{
    public static $rules = [
        'name' => 'required|string|min:1|max:255',
        'main_sport_id' => 'required|int|min:1',
        'start_datetime' => 'required|string|min:4',
        'finish_datetime' => 'required|string|min:4',
        'description' => "string"
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
