<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequst extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [

            // // 'date' => 'required|date_format:Y-m-d H:i:s',
            // 'status' => 'required|string',
            // 'detail' => 'required|array',
            // 'detail.*.book_id' => ['required', 'numeric', Rule::exists('books', 'id')],
            // 'detail.*.quantity' => 'required|numeric|gt:0',
            // 'detail.*.discount' => 'required|numeric|gt:-1',

        ];
    }
}
