<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Dingo\Api\Exception\ValidationHttpException;

class GeneralApiFormRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        if ($this->wantsJson()) {
            throw new ValidationHttpException($validator->errors());
        }
    }
}
