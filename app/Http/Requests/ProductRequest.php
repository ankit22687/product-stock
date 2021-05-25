<?php

namespace App\Http\Requests;

use Elegant\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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
        if ($this->product) {
            $asValidation = [
                'code'=>'required|unique:products,id,'.$this->product
            ];
        } else {
            $asValidation = [
                'code'=>'required|unique:products'
            ];
        }
        return array_merge($asValidation, [
            'name'=>'required',
            'description'=>'required'
        ]);
    }

    /**
    * sanitise request data
    *
    */
    public function filters()
    {
        return [
            'code' => 'trim|escape|strip_tags|digit',
            'name' => 'trim|escape|strip_tags',
            'description' => 'trim|escape|strip_tags',
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
