<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;
use Illuminate\Http\Request;


class CustomerFilter extends ApiFilter
{

  /*
  * NOTE:
    * First thing when it comes to user input; is not to trust the user input!
  */
  protected $safeParams = [
    'name' => ['eq'],
    'type' => ['eq'],
    'email' => ['eq'],
    'address' => ['eq'],
    'city' => ['eq'],
    'state' => ['eq'],
    'postalCode' => ['eq', 'gt', 'lt'],

  ];

  protected $columnMap = [
    'postalCode' => 'postal_code'
  ];

  protected $operatorMap = [
    'eq' => '=',
    'lt' => '<',
    'gt' => '>',
    'lte' => '<=',
    'gte' => '>=',
    'ne' => '!=',
  ];

  public function transform(Request $request)
  {
    $eloquentQuery = [];

    foreach ($this->safeParams as $parm => $operators) {
      $query = $request->query($parm);

      if (isset($query))
        continue;
    }

    $column  = $this->columnMap[$parm] ?? $parm;

    foreach ($operators as $operator) {
      if (isset($query[$operator])) {
        $eloquentQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
      }
    }
    return $eloquentQuery;
  }
}
