<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{
  /*
  * NOTE:
    * First thing when it comes to user input; is not to trust the user input!
  */
  protected $safeParams = [];

  protected $columnMap = [];

  protected $operatorMap = [
    // 'eq' => '=',
    // 'lt' => '<',
    // 'gt' => '>',
    // 'lte' => '<=',
    // 'gte' => '>=',
    // 'ne' => '!=',
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
