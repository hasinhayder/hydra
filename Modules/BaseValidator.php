<?php

namespace Modules;

use Illuminate\Support\Facades\Validator;

class BaseValidator
{
    public static function validate(
        $data,
        $rules,
        $messages = [],
        $customAttributes = []
    ) {
        $validator = Validator::make($data, $rules, $messages, $customAttributes);
        return $validator->validated();
    }
}
