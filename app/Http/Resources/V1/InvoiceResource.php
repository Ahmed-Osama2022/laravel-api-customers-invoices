<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    // return parent::toArray($request);

    return [
      'id' => $this->id,
      // Continue the rest
      'amount' => $this->amount,
      'status' => $this->status,
      'billedDate' => $this->billed_date,
      'paidDate' => $this->paid_date,
    ];
  }
}
