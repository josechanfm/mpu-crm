<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Inertia\Inertia;


Route::group(['middleware' => config('fortify.middleware', ['admin_web'])], function () {
    $limiter = config('fortify.limiters.login');
    Route::get('/manage/login', function () {
        return Inertia::render('Auth/AdminLogin');
    })->middleware(['guest:'.config('fortify.guard')]);
    Route::post('/manage/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:'.config('fortify.guard'),
            $limiter ? 'throttle:'.$limiter : null,
        ])
    )->name('manage.login');
    Route::post('/manage/logout', [AuthenticatedSessionController::class, 'destroy'])->name('manage.logout');
});

Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
])->group(function() {
    Route::get('staff', function () {
        return Inertia::render('GeneralStaff',[
        ]);
    });
});

Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
])->group(function () {
    Route::prefix('/manage')->group(function(){
        Route::get('email/test',[App\Http\Controllers\Department\EmailController::class,'test'])->name('manage.email.test');
        Route::get('/',[App\Http\Controllers\Department\DashboardController::class,'index']);
        Route::get('dashboard',[App\Http\Controllers\Department\DashboardController::class,'index'])->name('manage.dashboard');
        Route::resource('departments',App\Http\Controllers\Department\DepartmentController::class)->names('manage.departments');
        Route::resource('enquiry/questions',App\Http\Controllers\Department\EnquiryQuestionController::class)->names('manage.enquiry.questions');
        Route::post('enquiry/question/response',[App\Http\Controllers\Department\EnquiryQuestionController::class,'response'])->name('manage.enquiry.question.response');
        Route::resource('enquiries',App\Http\Controllers\Department\EnquiryController::class)->names('manage.enquiries');
        //Route::resource('department/{department}/faqs',App\Http\Controllers\Department\FaqController::class)->names('manage.department.faqs');
        Route::resource('enquiry/faqs',App\Http\Controllers\Department\FaqController::class)->names('manage.enquiry.faqs');
        //Route::resource('department/{department}/forms',App\Http\Controllers\Department\FormController::class)->names('manage.department.forms');
        Route::resource('forms',App\Http\Controllers\Department\FormController::class)->names('manage.forms');
        Route::resource('form/{form}/fields',App\Http\Controllers\Department\FormFieldController::class)->names('manage.form.fields');
        Route::resource('form/{form)/entries',App\Http\Controllers\Department\EntryController::class)->names('manage.form.entries');
            Route::get('entry/{form}/export', [App\Http\Controllers\Department\EntryController::class, 'export'])->name('manage.entry.export');
            
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
        Route::get('/',[App\Http\Controllers\Master\DashboardController::class,'index'])->name('master');
        Route::resource('/admin_users',App\Http\Controllers\Master\AdminUserController::class)->names('master.adminUsers');
        Route::resource('/departments',App\Http\Controllers\Master\DepartmentController::class)->names('master.departments');
    });
});