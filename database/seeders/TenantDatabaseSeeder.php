<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\NotificationsSetting;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\SmsTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Spatie\MailTemplates\Models\MailTemplate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;

class TenantDatabaseSeeder extends Seeder
{
    public function run()
    {
        Artisan::call('cache:clear');
        $allpermissions  = [
            'role'                  => ['manage', 'create', 'edit', 'delete', 'show'],
            'user'                  => ['manage', 'create', 'edit', 'delete', 'impersonate'],
            'client'                => ['manage', 'create', 'edit', 'delete'],
            'setting'               => ['manage'],
            'transaction'           => ['manage'],
            'landingpage'           => ['manage'],
            'chat'                  => ['manage'],
            'plan'                  => ['manage', 'create', 'edit', 'delete'],
            'blog'                  => ['manage', 'create', 'edit', 'delete'],
            'category'              => ['manage', 'create', 'edit', 'delete'],
            'email-template'        => ['manage', 'edit'],
            'sms-template'          => ['manage', 'edit'],
            'testimonial'           => ['manage', 'create', 'edit', 'delete'],
            'event'                 => ['manage', 'create', 'edit', 'delete'],
            'faqs'                  => ['manage', 'create', 'edit', 'delete'],
            'coupon'                => ['manage', 'create', 'edit', 'delete', 'show', 'mass-create', 'upload'],
            'document'              => ['manage', 'create', 'edit', 'delete'],
            'page-setting'          => ['manage', 'create', 'edit', 'delete'],
            'support-ticket'        => ['manage', 'create', 'edit', 'delete'],
            'announcement'          => ['manage', 'create', 'edit', 'delete'],
            'activity-log'          => ['manage'],
        ];

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        foreach ($allpermissions as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission . '-' . $module]);
            }
        }

        $adminpermissions  = [
            'role'                  => ['manage', 'create', 'edit', 'delete', 'show'],
            'user'                  => ['manage', 'create', 'edit', 'delete', 'impersonate'],
            'client'                => ['manage', 'create', 'edit', 'delete'],
            'setting'               => ['manage'],
            'landingpage'           => ['manage'],
            'email-template'        => ['manage', 'edit'],
            'sms-template'          => ['manage', 'edit'],
            'event'                 => ['manage', 'create', 'edit', 'delete'],
            'support-ticket'        => ['manage', 'create', 'edit', 'delete'],
            'announcement'          => ['manage'],
            'activity-log'          => ['manage'],
        ];

        $adminRole = Role::firstOrCreate([
            'name' => 'Agency'
        ]);

        foreach ($adminpermissions as $adminmodule => $adminpermissions) {
            Module::firstOrCreate(['name' => $adminmodule]);
            foreach ($adminpermissions as $adminpermission) {
                $temp1 = Permission::firstOrCreate(['name' => $adminpermission . '-' . $adminmodule]);
                $adminRole->givePermissionTo($temp1);
            }
        }

        $centralUser = tenancy()->central(function ($tenant) {
            return User::find($tenant->id);
        });

        $role = Role::firstOrCreate([
            'name'  => 'User'
        ], ['tenant_id' => $centralUser->tenant_id]);

        $user = User::firstOrCreate(['email' =>  $centralUser->email], [
            'name'              => $centralUser->name,
            'email'             =>  $centralUser->email,
            'password'          =>  $centralUser->password,
            'avatar'            => 'avatar/avatar.png',
            'type'              => 'Agency',
            'lang'              => 'en',
            'plan_id'           => 1,
            'plan_expired_date' => null,
            'email_verified_at' => $centralUser->email_verified_at,
            'phone_verified_at' => $centralUser->phone_verified_at,
            'country_code'      => $centralUser->country_code,
            'dial_code'         => $centralUser->dial_code,
            'phone'             => $centralUser->phone,
        ]);

        $user->assignRole($adminRole->id);

        $settings = [
            ['key' => 'app_name', 'value' => 'Cloud Saas'],
            ['key' => 'app_logo', 'value' => 'logo/app-logo.png'],
            ['key' => 'app_dark_logo', 'value' => 'logo/app-dark-logo.png'],
            ['key' => 'favicon_logo', 'value' => 'logo/app-favicon-logo.png'],
            ['key' => 'default_language', 'value' => 'en'],
            ['key' => 'currency', 'value' => 'USD'],
            ['key' => 'currency_symbol', 'value' => '$'],
            ['key' => 'date_format', 'value' => 'M j, Y'],
            ['key' => 'time_format', 'value' => 'g:i A'],
            ['key' => 'color', 'value' => 'theme-2'],
            ['key' => 'storage_type', 'value' => 'local'],
            ['key' => 'dark_mode', 'value' => 'off'],
            ['key' => 'transparent_layout', 'value' => '1'],
            ['key' => 'landing_page_status', 'value' => '0'],
            ['key' => 'roles', 'value' => 'User'],

            ['key' => 'apps_setting_enable', 'value' => 'on'],
            ['key' => 'apps_name', 'value' => 'Cloud Saas'],
            ['key' => 'apps_bold_name', 'value' => 'Cloud Saas'],
            ['key' => 'app_detail', 'value' => 'Flight, Hotel, Visa Saas'],
            ['key' => 'apps_image', 'value' => 'seeder-image/app.png'],
            ['key' => 'apps_multiple_image_setting', 'value' => '[
                {"apps_multiple_image":"seeder-image/1.png"},
                {"apps_multiple_image":"seeder-image/2.png"},
                {"apps_multiple_image":"seeder-image/3.png"},
                {"apps_multiple_image":"seeder-image/4.png"},
                {"apps_multiple_image":"seeder-image/5.png"},
                {"apps_multiple_image":"seeder-image/6.png"},
                {"apps_multiple_image":"seeder-image/7.png"},
                {"apps_multiple_image":"seeder-image/8.png"},
                {"apps_multiple_image":"seeder-image/9.png"}
            ]'],

            ['key' => 'feature_setting_enable', 'value' => 'on'],
            ['key' => 'feature_name', 'value' => 'Cloud Saas'],
            ['key' => 'feature_bold_name', 'value' => 'Features'],
            ['key' => 'feature_detail', 'value' => 'Cloud Saas'],
            ['key' => 'feature_setting', 'value' => '[
                {"feature_image":"seeder-image/feature1.svg","feature_name":"Sms Notification","feature_bold_name":"On From Submit","feature_detail":"You can send a notification sms to someone in your organization when a contact submits a form. You can use this type of form processing step so that..."},
                {"feature_image":"seeder-image/feature2.svg","feature_name":"Two Factor","feature_bold_name":"Authentication","feature_detail":"Security is our priority. With our robust two-factor authentication (2FA) feature, you can add an extra layer of protection to your Full Tenancy Form"},
                {"feature_image":"seeder-image/feature3.svg","feature_name":"Events With","feature_bold_name":"Google Calender","feature_detail":"Assign roles and permissions to different users based on their responsibilities and requirements. Admins can manage user accounts, define access level"},
                {"feature_image":"seeder-image/feature4.svg","feature_name":"Multi Feature","feature_bold_name":"Dark Layout","feature_detail":"Template Library: Offer a selection of pre-designed templates for various document types. Template Creation: Allow users to create custom templates with placeholders for dynamic content.Template Library: Offer a selection of pre-designed templates for various document types.Template Creation: Allow users to create custom templates with placeholders for dynamic content."}
            ]'],

            ['key' => 'menu_setting_section1_enable', 'value' => 'on'],
            ['key' => 'menu_name_section1', 'value' => 'Feature'],
            ['key' => 'menu_bold_name_section1', 'value' => 'Events'],
            ['key' => 'menu_detail_section1', 'value' => 'Cloud Saas'],
            ['key' => 'menu_image_section1', 'value' => 'seeder-image/menusection1.png'],

            ['key' => 'menu_setting_section2_enable', 'value' => 'on'],
            ['key' => 'menu_name_section2', 'value' => 'Multi Builder With'],
            ['key' => 'menu_bold_name_section2', 'value' => 'Plans'],
            ['key' => 'menu_detail_section2', 'value' => 'Cloud Saas'],
            ['key' => 'menu_image_section2', 'value' => 'seeder-image/menusection2.png'],

            ['key' => 'menu_setting_section3_enable', 'value' => 'on'],
            ['key' => 'menu_name_section3', 'value' => 'Multi Tenant With'],
            ['key' => 'menu_bold_name_section3', 'value' => 'Documents'],
            ['key' => 'menu_detail_section3', 'value' => 'Cloud Saas'],
            ['key' => 'menu_image_section3', 'value' => 'seeder-image/menusection3.png'],

            ['key' => 'business_growth_setting_enable', 'value' => 'on'],
            ['key' => 'business_growth_front_image', 'value' => 'seeder-image/thumbnail.png'],
            ['key' => 'business_growth_video', 'value' => 'seeder-image//video.webm'],
            ['key' => 'business_growth_name', 'value' => 'Makes Quick'],
            ['key' => 'business_growth_bold_name', 'value' => 'Business Growth'],
            ['key' => 'business_growth_detail', 'value' => 'Flight, Hotel, Visa'],
            ['key' => 'business_growth_view_setting', 'value' => '[
                {"business_growth_view_name":"Positive Reviews","business_growth_view_amount":"20 k+"},
                {"business_growth_view_name":"Total Sales","business_growth_view_amount":"300 +"},
                {"business_growth_view_name":"Happy Users","business_growth_view_amount":"100 k+"}
            ]'],
            ['key' => 'business_growth_setting', 'value' => '[
                {"business_growth_title":"User Friendly"},
                {"business_growth_title":"Products Analytic"},
                {"business_growth_title":"Manufacturers"},
                {"business_growth_title":"Order Status Tracking"},
                {"business_growth_title":"Supply Chain"},
                {"business_growth_title":"Chatting Features"},
                {"business_growth_title":"Workflows"},
                {"business_growth_title":"Transformation"},
                {"business_growth_title":"Easy Payout"},
                {"business_growth_title":"Data Adjustment"},
                {"business_growth_title":"Order Status Tracking"},
                {"business_growth_title":"Store Swap Link"},
                {"business_growth_title":"Manufacturers"},
                {"business_growth_title":"Order Status Tracking"}
            ]'],

            ['key' => 'contactus_setting_enable', 'value' => 'on'],
            ['key' => 'contactus_name', 'value' => 'Enterprise'],
            ['key' => 'contactus_bold_name', 'value' => 'Custom pricing'],
            ['key' => 'contactus_detail', 'value' => 'Offering tiered pricing options based on different levels of features or services allows customers.'],

            ['key' => 'faq_setting_enable', 'value' => 'on'],
            ['key' => 'faq_name', 'value' => 'Frequently asked questions'],

            ['key' => 'start_view_setting_enable', 'value' => 'on'],
            ['key' => 'start_view_name', 'value' => 'Cloud Saas'],
            ['key' => 'start_view_detail', 'value' => 'Cloud Saas'],
            ['key' => 'start_view_link_name', 'value' => 'Contact Us'],
            ['key' => 'start_view_link', 'value' => route('contact.us')],
            ['key' => 'start_view_image', 'value' => 'seeder-image/startview.png'],

            ['key' => 'login_setting_enable', 'value' => 'on'],
            ['key' => 'login_image', 'value' => 'seeder-image/login.svg'],
            ['key' => 'login_name', 'value' => 'Attention is the new currency'],
            ['key' => 'login_detail', 'value' => 'The more effortless the writing looks, the more effort the writer actually put into the process.'],

            ['key' => 'testimonial_setting_enable', 'value' => 'on'],
            ['key' => 'testimonial_name', 'value' => 'Full Tenancy Laravel Admin Saas'],
            ['key' => 'testimonial_bold_name', 'value' => 'Testimonial'],
            ['key' => 'testimonial_detail', 'value' => 'A testimonial is an honest endorsement of your product or service that usually comes from a customer, colleague, or peer who has benefited from or experienced success as a result of the work you did for them.'],

            ['key' => 'footer_setting_enable', 'value' => 'on'],
            ['key' => 'footer_description', 'value' => 'A feature is a unique quality or characteristic that something has. Real-life examples: Elaborately colored tail feathers are peacocks most well-known feature.'],

            ['key' => 'blog_setting_enable', 'value' => 'on'],
            ['key' => 'blog_name', 'value' => 'Blogs'],
            ['key' => 'blog_detail', 'value' => 'Optimize your manufacturing business with Quebix, offering a seamless user interface for streamlined operations, one convenient platform.'],
        ];

        tenancy()->central(function ($tenant) {
            Storage::copy('logo/app-logo.png', $tenant->id . '/logo/app-logo.png');
            Storage::copy('logo/app-favicon-logo.png', $tenant->id . '/logo/app-favicon-logo.png');
            Storage::copy('logo/app-dark-logo.png', $tenant->id . '/logo/app-dark-logo.png');
            Storage::copy('avatar/avatar.png', $tenant->id . '/avatar/avatar.png');

            Storage::copy('seeder-image/admin/app.png', $tenant->id . '/seeder-image/app.png');
            Storage::copy('seeder-image/1.png', $tenant->id . '/seeder-image/1.png');
            Storage::copy('seeder-image/2.png', $tenant->id . '/seeder-image/2.png');
            Storage::copy('seeder-image/3.png', $tenant->id . '/seeder-image/3.png');
            Storage::copy('seeder-image/4.png', $tenant->id . '/seeder-image/4.png');
            Storage::copy('seeder-image/5.png', $tenant->id . '/seeder-image/5.png');
            Storage::copy('seeder-image/6.png', $tenant->id . '/seeder-image/6.png');
            Storage::copy('seeder-image/7.png', $tenant->id . '/seeder-image/7.png');
            Storage::copy('seeder-image/8.png', $tenant->id . '/seeder-image/8.png');
            Storage::copy('seeder-image/9.png', $tenant->id . '/seeder-image/9.png');
            Storage::copy('seeder-image/thumbnail.png', $tenant->id . '/seeder-image/thumbnail.png');
            Storage::copy('seeder-image/admin/video.webm', $tenant->id . '/seeder-image/video.webm');
            Storage::copy('seeder-image/admin/13.jpg', $tenant->id . '/seeder-image/13.jpg');
            Storage::copy('seeder-image/admin/14.jpg', $tenant->id . '/seeder-image/14.jpg');
            Storage::copy('seeder-image/admin/15.jpg', $tenant->id . '/seeder-image/15.jpg');
            Storage::copy('seeder-image/admin/16.jpg', $tenant->id . '/seeder-image/16.jpg');
            Storage::copy('seeder-image/admin/feature1.svg', $tenant->id . '/seeder-image/feature1.svg');
            Storage::copy('seeder-image/admin/feature2.svg', $tenant->id . '/seeder-image/feature2.svg');
            Storage::copy('seeder-image/admin/feature3.svg', $tenant->id . '/seeder-image/feature3.svg');
            Storage::copy('seeder-image/admin/feature4.svg', $tenant->id . '/seeder-image/feature4.svg');
            Storage::copy('seeder-image/login.svg', $tenant->id . '/seeder-image/login.svg');
            Storage::copy('seeder-image/admin/menusection1.png', $tenant->id . '/seeder-image/menusection1.png');
            Storage::copy('seeder-image/admin/menusection2.png', $tenant->id . '/seeder-image/menusection2.png');
            Storage::copy('seeder-image/admin/menusection3.png', $tenant->id . '/seeder-image/menusection3.png');
            Storage::copy('seeder-image/admin/startview.png', $tenant->id . '/seeder-image/startview.png');
        });

        foreach ($settings as $setting) {
            Setting::firstOrCreate($setting);
        }



        SmsTemplate::firstOrCreate(['event' => 'verification code sms'], [
            'event'     => 'verification code sms',
            'template'  => 'Hello :name, Your verification code is :code',
            'variables' => 'name,code'
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Admin\TestMail::class], [
            'mailable'      => \App\Mail\Admin\TestMail::class,
            'subject'       => 'Mail send for testing purpose',
            'html_template' => '<p><strong>This Mail For Testing</strong></p>
            <p><strong>Thanks</strong></p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Admin\ApproveOfflineMail::class], [
            'mailable'      => \App\Mail\Admin\ApproveOfflineMail::class,
            'subject'       => 'Offline Payment Request Verified',
            'html_template' => '<p><strong>Hi&nbsp;&nbsp;{{ name }}</strong></p>
            <p><strong>Your Plan Update Request {{ email }}&nbsp;is Verified By Super Admin</strong></p>
            <p><strong>Please Check</strong></p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Admin\OfflineMail::class], [
            'mailable'      => \App\Mail\Admin\OfflineMail::class,
            'subject'       => 'Offline Payment Request Unverified',
            'html_template' => '<p><strong>Hi&nbsp;{{ name }}</strong></p>
            <p><strong>Your Request Payment {{ email }}&nbsp;Is Disapprove By Super Admin</strong></p>
            <p><strong>Because&nbsp;{{ disapprove_reason }}</strong></p>
            <p><strong>Please Contact to Super Admin</strong></p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Admin\ConatctMail::class], [
            'mailable'      => \App\Mail\Admin\ConatctMail::class,
            'subject'       => 'New Enquiry Details',
            'html_template' => '<p><strong>Name : {{name}}</strong></p>
            <p><strong>Email : </strong><strong>{{email}}</strong></p>
            <p><strong>Contact No : {{ contact_no }}&nbsp;</strong></p>
            <p><strong>Message :&nbsp;</strong><strong>{{ message }}</strong></p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Admin\PasswordResets::class], [
            'mailable'      => \App\Mail\Admin\PasswordResets::class,
            'subject'       => 'Reset Password Notification',
            'html_template' => '<p><strong>Hello!</strong></p><p>You are receiving this email because we received a password reset request for your account. To proceed with the password reset please click on the link below:</p><p><a href="{{url}}">Reset Password</a></p><p>This password reset link will expire in 60 minutes.&nbsp;<br>If you did not request a password reset, no further action is required.</p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Admin\RegisterMail::class], [
            'mailable'      => \App\Mail\Admin\RegisterMail::class,
            'subject'       => 'Register Mail',
            'html_template' => '<p><strong>Hi {{name}}</strong></p>
            <p><strong>Email {{email}}</strong></p>
            <p><strong>Register Successfully</strong></p>',
            'text_template' => null,
        ]);

        NotificationsSetting::firstOrCreate(['title' => 'Testing Purpose'], [
            'title'                 => 'Testing Purpose',
            'email_notification'    => '1',
            'sms_notification'      => '0',
            'notify'                => '0',
            'status'                => '2',
        ]);

        NotificationsSetting::firstOrCreate(['title' => 'Register Mail'], [
            'title'                 => 'Register Mail',
            'email_notification'    => '1',
            'sms_notification'      => '2',
            'notify'                => '1',
            'status'                => '1',
        ]);

        NotificationsSetting::firstOrCreate(['title' => 'New Enquiry Details'], [
            'title'                 => 'New Enquiry Details',
            'email_notification'    => '1',
            'sms_notification'      => '2',
            'notify'                => '1',
            'status'                => '2',
        ]);

        NotificationsSetting::firstOrCreate(['title' => 'Send Ticket Reply'], [
            'title'                 => 'Send Ticket Reply',
            'email_notification'    => '1',
            'sms_notification'      => '2',
            'notify'                => '1',
            'status'                => '2',
        ]);

        NotificationsSetting::firstOrCreate(['title' => 'Offline Payment Request Verified'], [
            'title'                 => 'Offline Payment Request Verified',
            'email_notification'    => '1',
            'sms_notification'      => '2',
            'notify'                => '1',
            'status'                => '2',
        ]);

        NotificationsSetting::firstOrCreate(['title' => 'Offline Payment Request Unverified'], [
            'title'                 => 'Offline Payment Request Unverified',
            'email_notification'    => '1',
            'sms_notification'      => '2',
            'notify'                => '1',
            'status'                => '2',
        ]);
    }
}
