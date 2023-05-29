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
    ]));

    Route::post('/manage/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('manage.logout');
});



Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
    'role:department',
])->group(function () {
    Route::prefix('/manage')->group(function(){
        Route::get('/',[App\Http\Controllers\Department\DashboardController::class,'index']);
        Route::get('dashboard',[App\Http\Controllers\Department\DashboardController::class,'index'])->name('manage.dashboard');
        Route::get('department/{department}',[App\Http\Controllers\Department\DashboardController::class,'index'])->name('manage.department');
        Route::resource('departments',App\Http\Controllers\Department\DepartmentController::class)->names('manage.departments');
        Route::resource('department/{department}/inquiries',App\Http\Controllers\Department\InquiryController::class)->names('manage.department.inquiries');
        Route::resource('notification_mailers',App\Http\Controllers\NotificationMailerController::class)->names('manage.notification_mailers');
        Route::get('notification_mailer/send_mail',[App\Http\Controllers\NotificationMailerController::class,'sendMail']);
        Route::resource('mailers',App\Http\Controllers\MailerController::class)->names('manage.mailers');
    })->name('manage');
});