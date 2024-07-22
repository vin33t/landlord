<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ConversionsController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DocumentGenratorController;
use App\Http\Controllers\Admin\DocumentMenuController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FlightController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\LandingController;
use App\Http\Controllers\Admin\LandingPageController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LoginSecurityController;
use App\Http\Controllers\Admin\NotificationsSettingController;
use App\Http\Controllers\Admin\OfflineRequestController;
use App\Http\Controllers\Admin\PageSettingController;
use App\Http\Controllers\Admin\Payment\AamarpayController;
use App\Http\Controllers\Admin\Payment\BenefitPaymentController;
use App\Http\Controllers\Admin\Payment\CashFreeController;
use App\Http\Controllers\Admin\Payment\CoingateController;
use App\Http\Controllers\Admin\Payment\EasebuzzPaymentController;
use App\Http\Controllers\Admin\Payment\FlutterwaveController;
use App\Http\Controllers\Admin\Payment\IyziPayController;
use App\Http\Controllers\Admin\Payment\MercadoController;
use App\Http\Controllers\Admin\Payment\MolliePaymentController;
use App\Http\Controllers\Admin\Payment\PayfastController;
use App\Http\Controllers\Admin\Payment\PaypalController;
use App\Http\Controllers\Admin\Payment\PaystackController;
use App\Http\Controllers\Admin\Payment\PaytabController;
use App\Http\Controllers\Admin\Payment\PaytmController;
use App\Http\Controllers\Admin\Payment\PayuMoneyController;
use App\Http\Controllers\Admin\Payment\RazorpayController;
use App\Http\Controllers\Admin\Payment\SkrillPaymentController;
use App\Http\Controllers\Admin\Payment\SSPayController;
use App\Http\Controllers\Admin\Payment\StripeController;
use App\Http\Controllers\Admin\Payment\ToyyibpayController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SmsController;
use App\Http\Controllers\Admin\SmsTemplateController;
use App\Http\Controllers\Admin\SocialLoginController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Features\UserImpersonation;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomainOrSubdomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    require __DIR__ . '/auth.php';
    Route::get('/tenant-impersonate/{token}', function ($token) {
        return UserImpersonation::makeResponse($token);
    });
    Route::group(['middleware' => ['auth', 'Setting', '2fa', 'Upload']], function () {
        Route::get('show-announcement-list/', [AnnouncementController::class, 'showAnnouncementList'])->name('show.announcement.list');
        Route::get('show-announcement/{id}', [AnnouncementController::class, 'showAnnouncement'])->name('show.announcement');
    });
    Route::group(['middleware' => ['Setting', 'xss', 'Upload']], function () {
        Route::get('redirect/{provider}', [SocialLoginController::class, 'redirect']);
        Route::get('callback/{provider}', [SocialLoginController::class, 'callback'])->name('social.callback');

        Route::get('contactus', [LandingController::class, 'contactUs'])->name('contact.us');
        Route::get('all/faqs', [LandingController::class, 'faqs'])->name('faqs.pages');
        Route::get('terms-conditions', [LandingController::class, 'termsAndConditions'])->name('terms.and.conditions');
        Route::post('contact-mail', [LandingController::class, 'contactMail'])->name('contact.mail');

        //sms
        Route::get('sms/notice', [SmsController::class, 'smsNoticeIndex'])->name('smsindex.noticeverification');
        Route::post('sms/notice', [SmsController::class, 'smsNoticeVerify'])->name('sms.noticeverification');
        Route::get('sms/verify', [SmsController::class, 'smsIndex'])->name('smsindex.verification');
        Route::post('sms/verify', [SmsController::class, 'smsVerify'])->name('sms.verification');
        Route::post('sms/verifyresend', [SmsController::class, 'smsResend'])->name('sms.verification.resend');

        //Blogs pages
        Route::get('blog/{slug}', [PostsController::class, 'viewBlog'])->name('view.blog');
        Route::get('see/blogs', [PostsController::class, 'seeAllBlogs'])->name('see.all.blogs');

        Route::get('pages/{slug}', [LandingPageController::class, 'pageDescription'])->name('description.page');
    });

    Route::group(['middleware' => ['auth', 'Setting', 'xss', '2fa', 'verified', 'verified_phone', 'Upload']], function () {
        Route::impersonate();

        // activity log
        Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity.log.index');

        Route::resource('faqs', FaqController::class);
        Route::resource('blogs', PostsController::class)->except(['show']);
        Route::post('notification/status/{id}', [NotificationsSettingController::class, 'changeStatus'])->name('notification.status.change');
        Route::resource('support-ticket', SupportTicketController::class);
        Route::resource('email-template', EmailTemplateController::class);
        Route::resource('sms-template', SmsTemplateController::class);
        Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
        Route::post('support-ticket/{id}/conversion', [ConversionsController::class, 'store'])->name('conversion.store');
        Route::resource('pagesetting', PageSettingController::class);

        // user
        Route::resource('users', UserController::class);
        Route::get('user-emailverified/{id}', [UserController::class, 'userEmailVerified'])->name('user.email.verified');
        Route::get('user-phoneverified/{id}', [UserController::class, 'userPhoneVerified'])->name('user.phone.verified');
        Route::post('user-status/{id}', [UserController::class, 'userStatus'])->name('user.status');

        // client
        Route::group(['prefix' => 'client'], function () {
            Route::get('/', [ClientController::class, 'index'])->name('client.index');
            Route::get('form/{client:uuid?}', [UserController::class, 'clientCreate'])->name('client.create');
            Route::post('store', [UserController::class, 'clientStore'])->name('client.store');
            Route::get('show/{id}', [UserController::class, 'clientShow'])->name('client.show');
            Route::get('destroy/{id}', [UserController::class, 'clientDestroy'])->name('client.destroy');
        });

        Route::group(['prefix' => 'hotel'], function () {
            Route::get('/', [HotelController::class, 'index'])->name('hotel.index');
        });

        Route::group(['prefix' => 'flight'], function () {
            Route::get('/', [FlightController::class, 'index'])->name('flight.index');
            Route::get('/booked', [FlightController::class, 'booked'])->name('flight.booked');
        });

        // role
        Route::resource('roles', RoleController::class);
        Route::post('role-permission/{id}', [RoleController::class, 'assignPermission'])->name('role.permission');

        // home
        Route::post('change/theme/mode', [HomeController::class, 'changeThemeMode'])->name('change.theme.mode');
        Route::get('home', [HomeController::class, 'index'])->name('home');
        Route::post('chart', [HomeController::class, 'chart'])->name('get.chart.data');
        Route::post('read/notification', [HomeController::class, 'readNotification'])->name('admin.read.notification');
        Route::get('sales', [HomeController::class, 'sales'])->name('sales.index');


        // testimonial
        Route::resource('testimonial', TestimonialController::class);
        Route::post('testimonial-status/{id}', [TestimonialController::class, 'testimonialStatus'])->name('testimonial.status');

        //event
        Route::get('event', [EventController::class, 'index'])->name('event.index');
        Route::post('event/getdata', [EventController::class, 'getEventData'])->name('event.get.data');
        Route::get('event/create', [EventController::class, 'create'])->name('event.create');
        Route::post('event/store', [EventController::class, 'store'])->name('event.store');
        Route::get('event/edit/{event}', [EventController::class, 'edit'])->name('event.edit');
        Route::any('event/update/{event}', [EventController::class, 'update'])->name('event.update');
        Route::DELETE('event/delete/{event}', [EventController::class, 'destroy'])->name('event.destroy');



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

        // profile
        Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::delete('/profile-destroy/delete', [ProfileController::class, 'destroy'])->name('profile.delete');
        Route::get('profile-status', [ProfileController::class, 'profileStatus'])->name('profile.status');
        Route::post('update-avatar', [ProfileController::class, 'updateAvatar'])->name('update.avatar');
        Route::post('profile/basicinfo/update/', [ProfileController::class, 'BasicInfoUpdate'])->name('profile.update.basicinfo');
        Route::post('update-login-details', [ProfileController::class, 'LoginDetails'])->name('update.login.details');

        //setting
        Route::get('settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('settings/app-name/update', [SettingsController::class, 'appNameUpdate'])->name('settings.appname.update');
        Route::post('settings/pusher-setting/update', [SettingsController::class, 'pusherSettingUpdate'])->name('settings.pusher.setting.update');
        Route::post('settings/s3-setting/update', [SettingsController::class, 's3SettingUpdate'])->name('settings.s3.setting.update');
        Route::post('settings/email-setting/update', [SettingsController::class, 'emailSettingUpdate'])->name('settings.email.setting.update');
        Route::post('settings/sms-setting/update', [SettingsController::class, 'smsSettingUpdate'])->name('settings.sms.setting.update');
        Route::post('settings/social-setting/update', [SettingsController::class, 'socialSettingUpdate'])->name('settings.social.setting.update');
        Route::post('settings/google-calender/update', [SettingsController::class, 'GoogleCalenderUpdate'])->name('settings.google.calender.update');
        Route::post('settings/auth-settings/update', [SettingsController::class, 'authSettingsUpdate'])->name('settings.auth.settings.update');
        Route::post('test-mail', [SettingsController::class, 'testSendMail'])->name('test.send.mail');
        Route::post('ckeditor/upload', [SettingsController::class, 'upload'])->name('ckeditor.upload');
        Route::post('settings/change-domain', [SettingsController::class, 'changeDomainRequest'])->name('settings.change.domain');
        Route::get('test-mail', [SettingsController::class, 'testMail'])->name('test.mail');
        Route::post('settings/cookie-setting/update', [SettingsController::class, 'cookieSettingUpdate'])->name('settings.cookie.setting.update');
        Route::post('setting/seo/save', [SettingsController::class, 'SeoSetting'])->name('setting.seo.save');


    });

    // cache
    Route::any('config-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');
        return redirect()->back()->with('success', __('Cache Clear Successfully'));
    })->name('config.cache');

  });
