<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. Thesel
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
})->name('home');



Route::prefix('enquiry')->group(function(){
    Route::resource('/',App\Http\Controllers\EnquiryController::class)->names('enquiry');
    Route::get('faqs',[\App\Http\Controllers\EnquiryController::class,'faqs'])->name('enquiry.faqs');
    Route::get('answer_question/{enquiry}/{token}',[\App\Http\Controllers\EnquiryController::class,'answerQuestion'])->name('enquiry.answerQuestion');
    Route::post('submit_question/{enquiry}',[\App\Http\Controllers\EnquiryController::class,'submitQuestion'])->name('enquiry.submitQuestion');
    Route::patch('no_question/{enquiry}',[\App\Http\Controllers\EnquiryController::class,'noQuestion'])->name('enquiry.noQuestion');
    Route::get('thank_question',[\App\Http\Controllers\EnquiryController::class,'thankQuestion'])->name('enquiry.thankQuestion');
    Route::get('ticket/{response}/{token}',[\App\Http\Controllers\EnquiryTicketController::class,'ticket'])->name('enquiry.ticket');
    Route::post('ticket',[\App\Http\Controllers\EnquiryTicketController::class,'store'])->name('enquiry.ticket.store');
});
Route::resource('forms',\App\Http\Controllers\FormController::class)->names('forms');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/member/dashboard', function () {
        return Inertia::render('Member/Dashboard');
    })->name('dashboard');
    Route::prefix('/member')->group(function(){
        Route::get('/', [\App\Http\Controllers\Member\DashboardController::class,'index'])->name('member');
        Route::get('member/recruitment/notifications',[App\Http\Controllers\Member\RecruitmentController::class,'notifications'])->name('member.recruitment.notifications');
    });
    Route::resource('professionals',App\Http\Controllers\Member\ProfessionalController::class);
    Route::get('membership',[App\Http\Controllers\Member\MembershipController::class,'index'])->name('membership');

    Route::prefix('student')->group(function(){
        Route::get('/',[App\Http\Controllers\Student\DashboardController::class,'index'])->name('student.dashboard');
    })->name('student');

});







