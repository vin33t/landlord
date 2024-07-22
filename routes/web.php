<?php

use App\Http\Controllers\Superadmin\ActivityLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Superadmin\AnnouncementController;
use App\Http\Controllers\Superadmin\HomeController;
use App\Http\Controllers\Superadmin\LanguageController;
use App\Http\Controllers\Superadmin\LoginSecurityController;
use App\Http\Controllers\Superadmin\ModuleController;
use App\Http\Controllers\Superadmin\PlanController;
use App\Http\Controllers\Superadmin\ProfileController;
use App\Http\Controllers\Superadmin\ChangeDomainController;
use App\Http\Controllers\Superadmin\ConversionsController;
use App\Http\Controllers\Superadmin\EmailTemplateController;
use App\Http\Controllers\Superadmin\RequestDomainController;
use App\Http\Controllers\Superadmin\RoleController;
use App\Http\Controllers\Superadmin\SupportTicketController;
use App\Http\Controllers\Superadmin\SettingsController;
use App\Http\Controllers\Superadmin\UserController;
use App\Http\Controllers\Superadmin\CouponController;
use App\Http\Controllers\Superadmin\SmsTemplateController;
use App\Http\Controllers\Superadmin\NotificationsSettingController;
use App\Http\Controllers\Superadmin\PageSettingController;
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

require __DIR__ . '/auth.php';
Route::group(['middleware' => ['Setting', 'xss', 'Upload']], function () {
    // request Domain
    Route::get('terms-conditions', [RequestDomainController::class, 'termsAndConditions'])->name('terms.and.conditions');
    Route::get('contactus', [RequestDomainController::class, 'contactUs'])->name('contact.us');
    Route::get('all/faqs', [RequestDomainController::class, 'faqs'])->name('faqs.pages');
    Route::post('contact-mail', [RequestDomainController::class, 'contactMail'])->name('add.contact.mail');
    Route::get('request-domain/create/{id}', [RequestDomainController::class, 'create'])->name('request.domain.create');
    Route::get('request-domain/payment/{id}', [RequestDomainController::class, 'payment'])->name('request.domain.payment');
    Route::post('request-domain/store', [RequestDomainController::class, 'store'])->name('request.domain.store');
    Route::post('offline-paysuccess', [RequestDomainController::class, 'offlinePaymentEntry'])->name('offline.payment.entry');

    Route::get('apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');
    Route::post('coupon-status/{id}', [CouponController::class, 'couponStatus'])->name('coupon.status');

    Route::resource('email-template', EmailTemplateController::class);
    Route::resource('sms-template', SmsTemplateController::class);
});

Route::get('show-public/announcement/{slug}', [AnnouncementController::class, 'showPublicAnnouncement'])->name('show.public.announcement');
Route::group(['middleware' => ['auth', 'Setting', '2fa', 'Upload']], function () {
    Route::resource('announcement', AnnouncementController::class);
    Route::post('announcement-status/{id}', [AnnouncementController::class, 'announcementStatus'])->name('announcement.status');
});

Route::group(['middleware' => ['auth', 'Setting', 'xss', '2fa', 'Upload']], function () {
    // home
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::post('chart', [HomeController::class, 'chart'])->name('get.chart.data');
    Route::post('read/notification', [HomeController::class, 'readNotification'])->name('read.notification');
    Route::get('sales', [HomeController::class, 'sales'])->name('sales.index');
    Route::post('change/theme/mode', [HomeController::class, 'changeThemeMode'])->name('change.theme.mode');

    // activity log
    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity.log.index');

    Route::post('notification/status/{id}', [NotificationsSettingController::class, 'changeStatus'])->name('notification.status.change');
    Route::resource('support-ticket', SupportTicketController::class);
    Route::resource('modules', ModuleController::class);
    Route::post('support-ticket/{id}/conversion', [ConversionsController::class, 'store'])->name('conversion.store');
    Route::resource('pagesetting', PageSettingController::class);

    Route::resource('roles', RoleController::class);
    Route::post('role-permission/{id}', [RoleController::class, 'assignPermission'])->name('role.permission');

    Route::resource('plans', PlanController::class);
    Route::post('plan-status/{id}', [PlanController::class, 'planStatus'])->name('plan.status');
    Route::get('myplans', [PlanController::class, 'myPlan'])->name('plans.myplan');

    //user
    Route::resource('users', UserController::class);
    Route::get('users/{id}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate');
    Route::post('user-status/{id}', [UserController::class, 'userStatus'])->name('user.status');

    // coupon
    Route::resource('coupon', CouponController::class);
    Route::get('coupon/show', [CouponController::class, 'show'])->name('coupons.show');
    Route::get('coupon/csv/upload', [CouponController::class, 'uploadCsv'])->name('coupon.upload');
    Route::post('coupon/csv/upload/store', [CouponController::class, 'uploadCsvStore'])->name('coupon.upload.store');
    Route::get('coupon/mass/create', [CouponController::class, 'massCreate'])->name('coupon.mass.create');
    Route::post('coupon/mass/store', [CouponController::class, 'massCreateStore'])->name('coupon.mass.store');

    //request-domain
    Route::get('request-domain/{id}/edit', [RequestDomainController::class, 'edit'])->name('request.domain.edit');
    Route::post('request-domain/{id}/update', [RequestDomainController::class, 'requestDomainUpdate'])->name('request.domain.update');
    Route::delete('request-domain/{id}/delete', [RequestDomainController::class, 'destroy'])->name('request.domain.delete');
    Route::post('user/update/{id}', [RequestDomainController::class, 'update'])->name('create.user');
    Route::get('request-domain', [RequestDomainController::class, 'index'])->name('request.domain.index');
    Route::get('request-domain/approve/{id}', [RequestDomainController::class, 'approveStatus'])->name('request.domain.approve.status');
    Route::get('request-domain/disapprove/{id}', [RequestDomainController::class, 'disApproveStatus'])->name('request.domain.disapprove.status');
    Route::post('request-domain/disapprove-status-update/{id}', [RequestDomainController::class, 'updateStatus'])->name('status.update');

    // change domain
    Route::get('change-domain', [ChangeDomainController::class, 'changeDomainIndex'])->name('changedomain');
    Route::post('change-domain/approve/{id}', [ChangeDomainController::class, 'changeDomainApprove'])->name('change.domain.approve');
    Route::get('change-domain/disapprove/{id}', [ChangeDomainController::class, 'domainDisApprove'])->name('change.domain.disapprove');
    Route::post('change-domain/disapprove-status-update/{id}', [ChangeDomainController::class, 'domainDisApproveUpdate'])->name('change.domain.update');

    //2fa
    Route::group(['prefix' => '2fa'], function () {
        Route::get('/', [LoginSecurityController::class, 'show2faForm']);
        Route::post('generateSecret', [LoginSecurityController::class, 'generate2faSecret'])->name('generate2faSecret');
        Route::post('enable2fa', [LoginSecurityController::class, 'enable2fa'])->name('enable2fa');
        Route::post('disable2fa', [LoginSecurityController::class, 'disable2fa'])->name('disable2fa');
        Route::post('2faVerify', function () {
            return redirect(route('home'));
            // return redirect(URL()->previous());
        })->name('2faVerify');
    });

    //profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::delete('/profile-destroy/delete', [ProfileController::class, 'destroy'])->name('profile.delete');
    Route::get('profile-status', [ProfileController::class, 'profileStatus'])->name('profile.status');
    Route::post('update-avatar', [ProfileController::class, 'updateAvatar'])->name('update.avatar');
    Route::post('profile/basicinfo/update/', [ProfileController::class, 'BasicInfoUpdate'])->name('profile.update.basicinfo');
    Route::post('update-login-details', [ProfileController::class, 'LoginDetails'])->name('update.login.details');

    //setting
    Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('settings/app-name/update', [SettingsController::class, 'appNameUpdate'])->name('settings.appname.update');
    Route::post('settings/s3-setting/update', [SettingsController::class, 's3SettingUpdate'])->name('settings.s3.setting.update');
    Route::post('settings/domain-config-setting/update', [SettingsController::class, 'domainConfigSettingUpdate'])->name('settings.domain.config.setting.update');
    Route::post('settings/email-setting/update', [SettingsController::class, 'emailSettingUpdate'])->name('settings.email.setting.update');
    Route::post('settings/auth-settings/update', [SettingsController::class, 'authSettingsUpdate'])->name('settings.auth.settings.update');
    Route::post('test-mail', [SettingsController::class, 'testSendMail'])->name('test.send.mail');
    Route::post('ckeditor/upload', [SettingsController::class, 'upload'])->name('ckeditor.upload');
    Route::post('settings/cookie-setting/update', [SettingsController::class, 'cookieSettingUpdate'])->name('settings.cookie.setting.update');
    Route::post('setting/seo/save', [SettingsController::class, 'SeoSetting'])->name('setting.seo.save');
    Route::get('test-mail', [SettingsController::class, 'testMail'])->name('test.mail');


    //language
    Route::get('change-language/{lang}', [LanguageController::class, 'changeLanguage'])->name('change.language');
    Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
    Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
    Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
    Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
    Route::delete('lang/{lang}', [LanguageController::class, 'destroyLang'])->name('lang.destroy');
});

// cookie
Route::get('cookie/consent', [SettingsController::class, 'CookieConsent'])->name('cookie.consent');

// cache
Route::any('config-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return redirect()->back()->with('success', __('Cache Clear Successfully.'));
})->name('config.cache');

Route::get('/', [RequestDomainController::class, 'landingPage'])->name('landingpage')->middleware('Upload');
Route::get('changeLang/{lang?}', [RequestDomainController::class, 'changeLang'])->name('change.lang');
