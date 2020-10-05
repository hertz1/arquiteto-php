<?php

use App\Http\Controllers\AddressesController;
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

Route::middleware(['auth:api'])->group(function () {
    Route::get('/addresses', [AddressesController::class, 'index']);
    Route::post('/address', [AddressesController::class, 'create']);
    Route::put('/address/{address}', [AddressesController::class, 'update']);
    Route::delete('/address/{address}', [AddressesController::class, 'delete']);
});
