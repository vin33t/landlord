<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Client;
use App\Models\Module;
use App\Observers\AnnouncementObserver;
use App\Models\Conversions;
use App\Observers\ClientObserver;
use App\Observers\ConversionsObserver;
use App\Models\Coupon;
use App\Observers\CouponObserver;
use App\Models\Event;
use App\Observers\EventObserver;
use App\Models\Plan;
use App\Observers\ModuleObserver;
use App\Observers\PlanObserver;
use App\Models\RequestDomain;
use App\Observers\RequestDomainObserver;
use App\Models\Role;
use App\Observers\RoleObserver;
use App\Models\Setting;
use App\Observers\SettingObserver;
use App\Models\SmsTemplate;
use App\Observers\SmsTemplateObserver;
use App\Models\SupportTicket;
use App\Observers\SupportTicketObserver;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\UserCode;
use App\Observers\UserCodeObserver;
use Spatie\MailTemplates\Models\MailTemplate;
use App\Observers\MailTemplateObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    public function boot()
    {
        Coupon::observe(CouponObserver::class);
        Plan::observe(PlanObserver::class);
        Role::observe(RoleObserver::class);
        SupportTicket::observe(SupportTicketObserver::class);
        User::observe(UserObserver::class);
        Conversions::observe(ConversionsObserver::class);
        MailTemplate::observe(MailTemplateObserver::class);
        SmsTemplate::observe(SmsTemplateObserver::class);
        Setting::observe(SettingObserver::class);
        Announcement::observe(AnnouncementObserver::class);
        RequestDomain::observe(RequestDomainObserver::class);
        Event::observe(EventObserver::class);
        UserCode::observe(UserCodeObserver::class);
        Client::observe(ClientObserver::class);
        Module::observe(ModuleObserver::class);
    }

    public function shouldDiscoverEvents()
    {
        return false;
    }
}
