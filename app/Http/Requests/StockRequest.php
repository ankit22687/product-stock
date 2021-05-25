<?php

namespace App\Http\Requests;

use Elegant\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StockRequest extends FormRequest
{
    use SanitizesInput;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'product_code'=>'required|exists:products,code',
            'on_hand'=>'required|numeric',
            'production_date'=>'required|date_format:d/m/y'
        ];
    }

    /**
    * sanitise request data
    */
    public function filters()
    {
        return [
            'product_code' => 'trim|escape|strip_tags|digit',
            'on_hand' => 'trim|escape|strip_tags|digit',
            'production_date' => 'trim',
        ];
    }

    /**
     * Set validation message
     */
    public function messages()
    {
        return [
            'product_code.exists' => 'Product not exist'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = [
            'success' => false,
            'message' => 'Failed to validate data',
            'data' => $validator->errors()
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
