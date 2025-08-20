<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

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

Route::prefix('souvenir')->group(function(){
    Route::get('/login',[App\Http\Controllers\Souvenir\LoginController::class,'login'])->name('souvenir.login');
    Route::post('/login',[App\Http\Controllers\Souvenir\LoginController::class,'checker'])->name('souvenir.loginChecker');
    Route::post('/logout',[App\Http\Controllers\Souvenir\LoginController::class,'logout'])->name('souvenir.logout');
    Route::get('/',[App\Http\Controllers\Souvenir\OrderController::class,'index'])->name('souvenir');
    Route::post('checkout',[App\Http\Controllers\Souvenir\OrderController::class,'checkout'])->name('souvenir.checkout');
    Route::get('checkout_order',[App\Http\Controllers\Souvenir\OrderController::class,'checkoutOrder'])->name('souvenir.checkoutOrder');
    Route::get('pickup_code',[App\Http\Controllers\Souvenir\OrderController::class,'pickupCode'])->name('souvenir.pickupCode');
    Route::post('to_pay/{souvenirUser}/',[App\Http\Controllers\Souvenir\PaymentController::class,'toPay'])->name('souvenir.toPay');
    Route::get('payment/notify',[App\Http\Controllers\Souvenir\PaymentController::class,'notify'])->name('souvenir.payment.notify');

});

Route::get('ebooks',[App\Http\Controllers\EbookController::class,'index'])->name('ebooks');

Route::get('/language/{language}', function ($language) {
    Session::put('applocale', $language);
    return Redirect::back();
})->name('language');

Route::get('/manual', function (Request $request) {
    $manual=App\Models\Manual::where('route',$request->route)->first();
    if(empty($manual)){
        $manual=App\Models\Manual::where('route','default')->first();
    }else if($manual->reroute){
        $manual=App\Models\Manual::where('route',$manual->reroute)->first();
    }
    return Inertia::render('Manual', [
        'manual' => $manual,
    ]);
})->name('manual');

Route::prefix('enquiry')->group(function(){
    Route::get('faqs',[\App\Http\Controllers\EnquiryController::class,'faqs'])->name('enquiry.faqs');
    Route::get('answer_question/{enquiry}/{token}',[\App\Http\Controllers\EnquiryController::class,'answerQuestion'])->name('enquiry.answerQuestion');
    Route::post('submit_question/{enquiry}',[\App\Http\Controllers\EnquiryController::class,'submitQuestion'])->name('enquiry.submitQuestion');
    Route::patch('no_question/{enquiry}',[\App\Http\Controllers\EnquiryController::class,'noQuestion'])->name('enquiry.noQuestion');
    Route::get('thank_question',[\App\Http\Controllers\EnquiryController::class,'thankQuestion'])->name('enquiry.thankQuestion');
    Route::get('ticket/{response}/{token}',[\App\Http\Controllers\EnquiryTicketController::class,'ticket'])->name('enquiry.ticket');
    Route::post('ticket',[\App\Http\Controllers\EnquiryTicketController::class,'store'])->name('enquiry.ticket.store');
});
Route::resource('enquiry',App\Http\Controllers\EnquiryController::class)->names('enquiry');
Route::resource('forms',\App\Http\Controllers\FormController::class)->names('forms');
// Route::post('forms',\App\Http\Controllers\FormController::class)->names('form.clone');

Route::get('form/entry/{entry}/thank_you',[\App\Http\Controllers\FormController::class,'thankYou'])->name('form.entry.thankYou');



Route::group([
    'prefix' => '/member',
    'middleware' => [
        'auth:sanctum',
    ]
], function () {
    // Route::get('dashboard', function () {
    //     return Inertia::render('Member/Dashboard');
    // })->name('member.dashboard');
    Route::get('/', [\App\Http\Controllers\Member\DashboardController::class,'index'])->name('member');
    Route::get('recruitment/notifications',[App\Http\Controllers\Member\RecruitmentController::class,'notifications'])->name('member.recruitment.notifications');
    Route::get('profile',[App\Http\Controllers\Member\ProfileController::class,'index'])->name('member.profile');

    Route::get('membership',[App\Http\Controllers\Member\MembershipController::class,'index'])->name('membership');

    Route::prefix('student')->group(function(){
        Route::get('/',[App\Http\Controllers\Student\DashboardController::class,'index'])->name('student.dashboard');
    })->name('student');

});

Route::prefix('/recruitment')->group(function() {
    Route::get('/', [\App\Http\Controllers\Recruitment\VacancyController::class, 'index'])->name('recruitment');
    Route::get('user/profile',[\App\Http\Controllers\Recruitment\UserController::class,'profile'])->name('recruitment.userProfile');
    Route::get('login',[\App\Http\Controllers\Recruitment\UserController::class,'login'])->name('recruitment.login');
    Route::get('logout',[\App\Http\Controllers\Recruitment\UserController::class,'logout'])->name('recruitment.logout');

    Route::prefix('/admin')->group(function(){
        Route::get('apply',[\App\Http\Controllers\Recruitment\AdminController::class,'apply'])->name('recruitment.admin.apply');
        Route::post('save',[\App\Http\Controllers\Recruitment\AdminController::class,'save'])->name('recruitment.admin.save');
        Route::post('submit',[\App\Http\Controllers\Recruitment\AdminController::class,'submit'])->name('recruitment.admin.submit');
        Route::post('file_upload',[\App\Http\Controllers\Recruitment\AdminController::class,'fileUpload'])->name('recruitment.admin.fileUpload');
        Route::delete('file_delete/{rec_upload}',[\App\Http\Controllers\Recruitment\AdminController::class,'fileDelete'])->name('recruitment.admin.fileDelete');
        Route::get('payment',[\App\Http\Controllers\Recruitment\AdminController::class,'payment'])->name('recruitment.admin.payment');
        Route::get('success',[\App\Http\Controllers\Recruitment\AdminController::class,'success'])->name('recruitment.admin.success');
        Route::get('receipt',[\App\Http\Controllers\Recruitment\AdminController::class,'receipt'])->name('recruitment.admin.receipt');

        Route::post('boc_notify',[\App\Http\Controllers\Recruitment\AdminController::class,'bocNotify']);
        Route::post('boc_result',[\App\Http\Controllers\Recruitment\AdminController::class,'bocResult']);
        Route::get('test_boc_payment',[\App\Http\Controllers\Recruitment\AdminController::class,'testBocPayment'])->name('recruitment.admin.testBocPayment');
        Route::post('test_boc_result',[\App\Http\Controllers\Recruitment\AdminController::class,'testBocResult'])->name('recruitment.admin.testBocResult');

    });
    
    Route::prefix('/academic')->group(function(){
        Route::get('apply',[\App\Http\Controllers\Recruitment\AcademicController::class,'apply'])->name('recruitment.academic.apply');
        Route::post('save',[\App\Http\Controllers\Recruitment\AcademicController::class,'save'])->name('recruitment.academic.save');
        Route::post('submit',[\App\Http\Controllers\Recruitment\AcademicController::class,'submit'])->name('recruitment.academic.submit');
        Route::delete('delete',[\App\Http\Controllers\Recruitment\AcademicController::class,'delete'])->name('recruitment.academic.delete');
        Route::post('file_upload',[\App\Http\Controllers\Recruitment\AcademicController::class,'fileUpload'])->name('recruitment.academic.fileUpload');
        Route::delete('file_delete/{rec_upload}',[\App\Http\Controllers\Recruitment\AcademicController::class,'fileDelete'])->name('recruitment.academic.fileDelete');
        Route::get('success',[\App\Http\Controllers\Recruitment\AcademicController::class,'success'])->name('recruitment.academic.success');
        Route::get('receipt',[\App\Http\Controllers\Recruitment\AcademicController::class,'receipt'])->name('recruitment.academic.receipt');
    });
    
    
});

require __DIR__ . '/auth.php';