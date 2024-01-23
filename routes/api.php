<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('orders')->group(function () {
    Route::post('create', [OrderController::class, 'createOrder']);
    Route::get('processing', [OrderController::class, 'listOrdersInProcessing']);
    Route::get('{id}/consult', [OrderController::class, 'consultOrder']);
    Route::delete('{id}/cancel', [OrderController::class, 'cancelOrder']);
});
