<?php


namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

trait InitialRequestValidation
{
    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            "code" => Response::HTTP_UNPROCESSABLE_ENTITY,
            "success" => false,
            'message' => $errors->first(),
            'data' => ""
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
