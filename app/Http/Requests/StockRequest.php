<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dateFrom' => 'required|date_format:Y-m-d|date_equals:today',
            'page'     => 'nullable|integer|min:1',
            'limit'    => 'nullable|integer|min:1|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'dateFrom.date_equals' => 'Склады доступны только за текущий день.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json($validator->errors(), 400)
        );
    }
}