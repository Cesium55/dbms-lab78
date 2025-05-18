<?php

namespace App\Validators\Events;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ParticipantCreateValidator
{
    public static $rules = [
        'event_id' => 'required|int|min:1',
        'participant_id' => 'required|int|min:1',
        'is_team' => 'required|boolean',
        'place' => "int"
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
