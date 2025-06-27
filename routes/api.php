<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Auth:
// use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

// api/vi/
// Route::prefix('v1')->group(function () {});
// Route::prefix('v1')->namespace('App\Http\Controllers\Api\V1')->group(function () {});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => 'auth:sanctum'], function () {
  Route::apiResource('customers', CustomerController::class);
  Route::apiResource('invoices', InvoiceController::class);

  Route::post('invoices/bulk', [InvoiceController::class, 'bulkStore']);
});


/**
 * For the Authentication
 */
Route::post('/sanctum/token', function (Request $request) {
  $request->validate([
    'email' => 'required|email',
    // 'password' => 'required',
    // 'device_name' => 'required',
  ]);

  $user = Customer::where('email', $request->email)->first();

  if (! $user) {
    throw ValidationException::withMessages([
      'email' => ['The provided credentials are incorrect.'],
    ]);
  }

  return $user->createToken($request->email)->plainTextToken;
});
