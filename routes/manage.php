<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Inertia\Inertia;


Route::group(['middleware' => config('fortify.middleware', ['admin_web'])], function () {
    $limiter = config('fortify.limiters.login');
    Route::get('/manage/login', function () {
        return Inertia::render('Department/Login');
    })->middleware(['guest:'.config('fortify.guard')]);
    Route::post('/manage/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:'.config('fortify.guard'),
            $limiter ? 'throttle:'.$limiter : null,
        ])
    )->name('manage.login');
    Route::get('/manage/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('manage.logout');
});

Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
])->group(function() {
    Route::get('abcd', function () {
        dd('what is');
        return Inertia::render('GeneralStaff',[
        ]);
    });
});


Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
])->group(function () {
    Route::prefix('/manage')->group(function(){
        Route::get('/',[App\Http\Controllers\Department\DashboardController::class,'index']);
        Route::get('dashboard',[App\Http\Controllers\Department\DashboardController::class,'index'])->name('manage.dashboard');
        Route::resource('departments',App\Http\Controllers\Department\DepartmentController::class)->names('manage.departments');
        Route::resource('department/{department}/inquiries',App\Http\Controllers\Department\InquiryController::class)->names('manage.department.inquiries');
        Route::post('department/inquiry/response',[App\Http\Controllers\Department\InquiryController::class,'response'])->name('department.inquiry.response');
        Route::resource('department/{department}/faqs',App\Http\Controllers\Department\FaqController::class)->names('manage.department.faqs');
        
        Route::resource('notification_mailers',App\Http\Controllers\NotificationMailerController::class)->names('manage.notification_mailers');
        Route::get('notification_mailer/send_mail',[App\Http\Controllers\NotificationMailerController::class,'sendMail']);
        Route::resource('mailers',App\Http\Controllers\MailerController::class)->names('manage.mailers');
    })->name('manage');
});



Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
    'role:master'
])->group(function () {
    Route::prefix('/master')->group(function(){
        Route::get('/',[App\Http\Controllers\Master\DashboardController::class,'index']);
        Route::resource('/admin_users',App\Http\Controllers\Master\AdminUserController::class)->names('master.adminUsers');
    })->name('master');
});