<?php

namespace App\Validators\Sports;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SportUpdateValidator
{
    public static $rules = [
        'name' => 'required|string|min:1|max:255',
        'id' => 'required|integer|min:1'
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
