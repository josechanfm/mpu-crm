<?php

use App\Models\Department;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Inertia\Inertia;

// Route::get('/get-permissions', function () {
//     return 'abc';
//     return auth()->check()?auth()->user()->jsPermissions():0;
// });

Route::resource('/accountcharts',App\Http\Controllers\AccountchartController::class)->names('accountcharts');


Route::group(['middleware' => config('fortify.middleware', ['admin_web'])], function () {
    $limiter = config('fortify.limiters.login');
    Route::get('/staff/login', function () {
        return Inertia::render('Auth/AdminLogin');
    })->middleware(['guest:'.config('fortify.guard')]);
    Route::post('/staff/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:'.config('fortify.guard'),
            $limiter ? 'throttle:'.$limiter : null,
        ])
    )->name('staff.login');
    Route::post('/staff/logout', [AuthenticatedSessionController::class, 'destroy'])->name('staff.logout');
});

Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
])->group(function() {
    Route::prefix('/staff')->group(function(){
        Route::get('/',[App\Http\Controllers\Staff\DashboardController::class,'index'])->name('staff');
        Route::resource('forms',\App\Http\Controllers\Staff\FormController::class)->names('staff.forms');
    });
    Route::get('/get-permissions', function () {
        return auth()->check()?auth()->user()->jsPermissions():0;
    });
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
        Route::resource('/articles',App\Http\Controllers\Master\ArticleController::class)->names('master.articles');
        Route::resource('/medias',App\Http\Controllers\Master\MediaController::class)->names('master.medias');
    });
});

Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
])->group(function () {
    Route::prefix('/manage')->group(function(){
        Route::get('/',[App\Http\Controllers\Department\DashboardController::class,'index'])->name('manage');
        Route::get('/masqueradeAdminUser/{department}',[App\Http\Controllers\Department\DashboardController::class,'masqueradeAdminUser'])->name('manage.masqueradeAdminUser');
        Route::get('dashboard',[App\Http\Controllers\Department\DashboardController::class,'index'])->name('manage.dashboard');
        Route::resource('departments',App\Http\Controllers\Department\DepartmentController::class)->names('manage.departments');
        Route::get('department/{roleName}',[App\Http\Controllers\Department\DepartmentController::class,'redirect'])->name('manage.department.redirect');
        //Route::resource('department/{department}/forms',App\Http\Controllers\Department\FormController::class)->names('manage.department.forms');
        Route::resource('forms',App\Http\Controllers\Department\FormController::class)->names('manage.forms');
        Route::resource('form/{form}/fields',App\Http\Controllers\Department\FormFieldController::class)->names('manage.form.fields');
        Route::post('form/{form}/fields_sequence',[App\Http\Controllers\Department\FormFieldController::class,'fieldsSequence'])->name('manage.form.fieldsSequence');
        Route::resource('form/{form}/entries',App\Http\Controllers\Department\EntryController::class)->names('manage.form.entries');
        Route::get('entry/{form}/export', [App\Http\Controllers\Department\EntryController::class, 'export'])->name('manage.entry.export');
        Route::get('form/{form}/entry/{entry}/success', [App\Http\Controllers\Department\EntryController::class, 'success'])->name('manage.form.entry.success');

        Route::resource('notification_mailers',App\Http\Controllers\NotificationMailerController::class)->names('manage.notification_mailers');
        Route::get('notification_mailer/send_mail',[App\Http\Controllers\NotificationMailerController::class,'sendMail']);

        Route::resource('mailers',App\Http\Controllers\MailerController::class)->names('manage.mailers');

        // Route::get('/personnel',[App\Http\Controllers\Department\Personnel\DashboardController::class,'index'])->name('personnel.dashboard');
    })->name('manage');
});




Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
    'role:DAMIA|admin|master'
])->group(function () {
    Route::prefix('/registry')->group(function(){
        Route::get('/',[App\Http\Controllers\Department\Registry\DashboardController::class,'index'])->name('registry.dashboard');
        Route::resource('enquiry/questions',App\Http\Controllers\Department\Registry\EnquiryQuestionController::class)->names('registry.enquiry.questions');
        Route::post('enquiry/question/response',[App\Http\Controllers\Department\Registry\EnquiryQuestionController::class,'response'])->name('registry.enquiry.question.response');
        Route::resource('enquiries',App\Http\Controllers\Department\Registry\EnquiryController::class)->names('registry.enquiries');
        Route::resource('faqs',App\Http\Controllers\Department\Registry\FaqController::class)->names('registry.faqs');
        Route::get('report',[App\Http\Controllers\Department\Registry\ReportController::class,'index'])->name('registry.report.index');
        Route::get('report/export_enquiries',[App\Http\Controllers\Department\Registry\ReportController::class,'exportEnquiries'])->name('registry.report.exportEnquiries');
        Route::get('report/export_questions',[App\Http\Controllers\Department\Registry\ReportController::class,'exportQuestions'])->name('registry.report.exportQuestions');
    });
});

Route::middleware([
    'auth:admin_web',
    config('jetstream.auth_session'),
    'role:PES|admin|master'
])->group(function () {
    Route::prefix('/personnel')->group(function(){
        Route::get('/',[App\Http\Controllers\Department\Personnel\DashboardController::class,'index'])->name('personnel.dashboard');
        Route::get('/gpdps/export',[App\Http\Controllers\Department\Personnel\GpdpController::class,'export'])->name('personnel.gpdps.export');
        Route::post('/gpdps/import',[App\Http\Controllers\Department\Personnel\GpdpController::class,'import'])->name('personnel.gpdps.import');
        Route::get('/gpdps/emails',[App\Http\Controllers\Department\Personnel\GpdpController::class,'listEmails'])->name('personnel.gpdps.emails');
        Route::resource('/gpdps',App\Http\Controllers\Department\Personnel\GpdpController::class)->names('personnel.gpdps');
        Route::post('/gpdp/{gpdp}/sendEmailReminder',[App\Http\Controllers\Department\Personnel\GpdpController::class,'sendEmailReminder'])->name('personnel.gpdp.sendEmailReminder');
        Route::resource('/recruitment/workflows',App\Http\Controllers\Department\Personnel\Recruitment\WorkflowController::class)->names('personnel.recruitment.workflows');
        Route::resource('/recruitment/tasks',App\Http\Controllers\Department\Personnel\Recruitment\TaskController::class)->names('personnel.recruitment.tasks');
        Route::resource('/recruitment/{workflow}/activities',App\Http\Controllers\Department\Personnel\Recruitment\ActivityController::class)->names('personnel.recruitment.activities');

        Route::resource('/recruitment/vacancies',App\Http\Controllers\Department\Personnel\Recruitment\VacancyController::class)->names('personnel.recruitment.vacancies');
        Route::resource('/recruitment/vacancy/{vacancy}/notices',App\Http\Controllers\Department\Personnel\Recruitment\NoticeController::class)->names('personnel.recruitment.vacancy.notices');
        Route::get('/recruitment/notice/delete_media/{media}',[App\Http\Controllers\Department\Personnel\Recruitment\NoticeController::class,'deleteMedia'])->name('personnel.recruitment.notice.deleteMedia');
        Route::resource('/recruitment/{vacancy}/applications',App\Http\Controllers\Department\Personnel\Recruitment\ApplicationController::class)->names('personnel.recruitment.applications');
        Route::get('/recruitment/application/quit_masquerade',[App\Http\Controllers\Department\Personnel\Recruitment\ApplicationController::class,'quitMasquerade'])->name('personnel.recruitment.application.quitMasquerade');

        Route::get('/recruitment/application/check_id_num',[App\Http\Controllers\Department\Personnel\Recruitment\ApplicationController::class,'checkIdNum'])->name('personnel.recruitment.application.checkIdNum');
        Route::get('/recruitment/application/check_email',[App\Http\Controllers\Department\Personnel\Recruitment\ApplicationController::class,'checkEmail'])->name('personnel.recruitment.application.checkEmail');
    });
});

