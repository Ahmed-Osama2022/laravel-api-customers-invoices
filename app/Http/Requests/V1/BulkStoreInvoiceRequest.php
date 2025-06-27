<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
// use Illuminate\Validation\ValidationException;

class BulkStoreInvoiceRequest extends FormRequest
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
    /**
     * The data will be like this:
     * data : [
     *  {}, {} , {}
     * ];
     * Array-of-objects
     */
    return [
      // Make the Rules here for the request
      '*.customerId' => ['required', 'integer'],
      '*.amount' => ['required', 'numeric'],
      '*.status' => ['required', Rule::in(['B', 'b', 'V', 'v', 'P', 'p'])],
      '*.billedDate' => ['required', 'date_format:Y-m-d H:i:s'],
      '*.paidDate' => ['date_format:Y-m-d H:i:s', 'nullable'],
    ];
  }

  protected function prepareForValidation()
  {
    $data = [];
    // dd($this->toArray()); //TEST:
    foreach ($this->toArray() as $obj) {
      $obj['customer_id'] = $obj['customerId'] ?? null;
      $obj['billed_date'] = $obj['billedDate'] ?? null;
      $obj['paid_date'] = $obj['paidDate'] ?? null;

      $data[] = $obj;
    }
    // dd($data); // TEST:
    $this->merge($data);
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
}
