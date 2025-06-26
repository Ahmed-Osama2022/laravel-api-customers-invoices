<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
// use Illuminate\Validation\ValidationException;

class StoreCustomerRequest extends FormRequest
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
    return [
      // Make the Rules here for the request
      'name' => ['required'],
      'type' => ['required', Rule::in(['I', 'B', 'i', 'b'])],
      'email' => ['required'],
      'address' => ['required'],
      'city' => ['required'],
      'state' => ['required'],
      'postalCode' => ['required'],

    ];
  }

  protected function prepareForValidation()
  {
    $this->merge([
      'postal_code' => $this->postalCode
    ]);
  }

  /**
   * Handle a failed validation attempt.
   *
   * @param  \Illuminate\Contracts\Validation\Validator  $validator
   * @return void
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  // protected function failedValidation(Validator $validator)
  // {
  //   throw new ValidationException($validator);
  // }

  protected function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(response()->json([
      'message'   => 'Validation errors',
      'data'      => $validator->errors()
    ], 422));
  }
}
