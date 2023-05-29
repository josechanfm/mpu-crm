<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::resource('inquiry',\App\Http\Controllers\InquiryController::class)->names('inquiry');
Route::get('question/{inquiry}/{token}',[\App\Http\Controllers\InquiryController::class,'question'])->name('question');
Route::get('inquiry/get_question',[\App\Http\Controllers\InquiryController::class,'getQuestion'])->name('inquiry.getQuestion');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Member/Dashboard');
    // })->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\Member\DashboardController::class,'index'])->name('dashboard');
    Route::resource('professionals',App\Http\Controllers\Member\ProfessionalController::class);
    Route::get('membership',[App\Http\Controllers\Member\MembershipController::class,'index'])->name('membership');

    Route::prefix('student')->group(function(){
        Route::get('/',[App\Http\Controllers\Student\DashboardController::class,'index'])->name('student.dashboard');
    })->name('student');

});


