<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\CustomerFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCustomerRequest;
use App\Http\Requests\V1\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\V1\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    /**
     * Thinking how could we filter the data
     */
    $filter = new CustomerFilter();
    $filterItems = $filter->transform($request); // [['column', 'operator', 'value']]

    $includeInvoices = $request->query('includeInvoices');

    $customers = Customer::where($filterItems);

    if ($includeInvoices) {
      $customers = Customer::with('invoices');
    }

    return new CustomerCollection($customers->paginate()->appends($request->query()));



    /**
     * Old; before 'includeInvioces'
     */
    // if (count($filterItems) == 0) {
    //   return new CustomerCollection(Customer::paginate());
    // } else {
    //   // return new CustomerCollection(Customer::where($queryItems)->paginate());
    //   $invoices = Customer::where($filterItems)->paginate();

    //   return new CustomerCollection($invoices->appends($request->query()));
    // }

    // OLD
    // return Customer::all();
    // return Customer::paginate(10);
    // return new CustomerCollection(Customer::all());
    // return new CustomerCollection(Customer::paginate(10));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreCustomerRequest $request)
  {
    // NOTE: return the new entity
    // dd($request->validated(),   $request->all());

    return new CustomerResource(Customer::create($request->all()));
  }

  /**
   * Display the specified resource.
   */
  public function show(Customer $customer)
  {
    // return $customer;
    $includeInvoices = request()->query('includeInvoices');

    if ($includeInvoices) {
      /**
       * loadMissing() method
       * Eager load relations on the model if they are not already eager loaded.
       */
      return new CustomerResource($customer->loadMissing('invoices'));
    }

    return new CustomerResource($customer);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateCustomerRequest $request, Customer $customer)
  {


    return new CustomerResource($customer->update($request->all()));
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Customer $customer)
  {
    //
  }
}
