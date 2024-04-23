<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

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


// Route::middleware('auth','guest.api')->group(function() {
    
// });
Route::get('config/item', [\App\Http\Controllers\Api\ConfigController::class,'Item'])->name('api.config.item');        

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


