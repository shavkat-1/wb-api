<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dateFrom' => 'required|date_format:Y-m-d',
            'dateTo'   => 'required|date_format:Y-m-d|after_or_equal:dateFrom',
            'page'     => 'nullable|integer|min:1',
            'limit'    => 'nullable|integer|min:1|max:500',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json($validator->errors(), 400)
        );
    }
}