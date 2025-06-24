<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;
use Illuminate\Http\Request;


class InvoiceFilter extends ApiFilter
{
  /*
  * NOTE:
    * First thing when it comes to user input; is not to trust the user input!
  */
  protected $safeParams = [
    'customerId' => ['eq'],
    'amount' => ['eq', 'lt', 'gt', 'lte', 'gte', 'ne'],
    'status' => ['eq', 'ne'],
    'billedDate' => ['eq', 'lt', 'gt', 'lte', 'gte', 'ne'],
    'paidDate' => ['eq', 'lt', 'gt', 'lte', 'gte', 'ne'],
  ];

  protected $columnMap = [
    'customerId' => 'customer_id',
    "billedDate" => 'billed_date',
    "paidDate" => 'paid_date',
  ];

  protected $operatorMap = [
    'eq' => '=',
    'lt' => '<',
    'gt' => '>',
    'lte' => '<=',
    'gte' => '>=',
    'ne' => '!=',
  ];
}
