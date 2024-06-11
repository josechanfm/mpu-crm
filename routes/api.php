<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;

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
Route::post('boc/notify',[\App\Http\Controllers\Api\BocController::class,'notify']);
Route::post('boc/result',[\App\Http\Controllers\Api\BocController::class,'result']);

Route::get('/',[\App\Http\Controllers\Api\CalendarController::class,'index'])->name('calendar');

Route::get('/services', function () {
    return Inertia::render('API/Services', [

    ]);
})->name('api.services');

Route::prefix('/calendar')->group(function(){
    Route::get('sync',[\App\Http\Controllers\Api\CalendarController::class,'sync'])->name('calendar.sync');
    Route::get('working_days',[\App\Http\Controllers\Api\CalendarController::class,'workingDays'])->name('calendar.workingDays');
    Route::get('is_holiday',[\App\Http\Controllers\Api\CalendarController::class,'isHoliday'])->name('calendar.isHoliday');
    Route::get('is_working_day',[\App\Http\Controllers\Api\CalendarController::class,'isWorkingDay'])->name('calendar.isWorkingDay');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



