<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\InvoiceFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BulkStoreInvoiceRequest;
use App\Http\Requests\V1\StoreInvoiceRequest;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Resources\V1\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class InvoiceController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    // return new InvoiceCollection(Invoice::paginate(10));

    /**
     * Thinking how could we filter the data
     */
    $filter = new InvoiceFilter();
    $queryItems = $filter->transform($request); // [['column', 'operator', 'value']]

    if (count($queryItems) == 0) {
      return new InvoiceCollection(Invoice::paginate());
    } else {
      // return new InvoiceCollection(Invoice::where($queryItems)->paginate()); // OLD; But will lose the query parameters for the pagination
      $invoices = Invoice::where($queryItems)->paginate();

      return new InvoiceCollection($invoices->appends($request->query()));
    }
  }


  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreInvoiceRequest $request)
  {
    //
  }

  public function bulkStore(BulkStoreInvoiceRequest $request)
  {
    // We are getting an array from the request
    $bulk  = collect($request->all())->map(function ($arr, $key) {
      return Arr::except($arr, ['customerId', 'billedDate', 'paidDate']);
    });
    // dd($bulk); // TEST:
    Invoice::insert($bulk->toArray());
  }



  /**
   * Display the specified resource.
   */
  public function show(Invoice $invoice)
  {
    return new InvoiceResource($invoice);
  }



  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateInvoiceRequest $request, Invoice $invoice)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Invoice $invoice)
  {
    //
  }
}
