<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $method = $this->method(); // 'PUT' || 'PATCH'

    if ($method == 'PUT') {
      return [
        // Make the Rules here for the request
        'name' => ['required'],
        'type' => ['required', Rule::in(['I', 'B', 'i', 'b'])],
        'email' => ['required', 'email', 'unique:customers,email'],
        'address' => ['required'],
        'city' => ['required'],
        'state' => ['required'],
        'postalCode' => ['required'],
      ];
    } else {
      return [
        /**
         * "sometimes" Validation Rule
         *  What it does:
         * If the field is not present in the request, Laravel skips validation for it.
         * If the field is present, it must pass the other validation rules that are defined for it.
         */
        'name' => ['sometimes', 'required'],
        'type' => ['sometimes', 'required', Rule::in(['I', 'B', 'i', 'b'])],
        'email' => ['sometimes', 'required', 'email', 'unique:customers,email'],
        'address' => ['sometimes', 'required'],
        'city' => ['sometimes', 'required'],
        'state' => ['sometimes', 'required'],
        'postalCode' => ['sometimes', 'required'],
      ];
    }
  }

  protected function prepareForValidation()
  {
    /**
     * NOTE: if we don't have postal code;
     * We have not change anything about the data we working with.
     */
    if ($this->postalCode) {
      $this->merge([
        'postal_code' => $this->postalCode
      ]);
    }
  }

  /**
   * Handle a failed validation attempt.
   *
   * @param  \Illuminate\Contracts\Validation\Validator  $validator
   * @return void
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  protected function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(response()->json([
      'message'   => 'Validation errors',
      'data'      => $validator->errors()
    ], 422));
  }

  /**
   * Get custom messages for validator errors.
   *
   * @return array
   */
  public function messages()
  {
    return [
      'type.in' => 'The type must be I, B, i or b.',
    ];
  }
}
