<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\NotificationsSetting;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\SmsTemplate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\MailTemplates\Models\MailTemplate;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Artisan::call('cache:clear');
        $allpermissions = [
            'role'                  => ['manage', 'create', 'edit', 'delete', 'show'],
            'user'                  => ['manage', 'create', 'edit', 'delete', 'impersonate'],
            'setting'               => ['manage'],
            'plan'                  => ['manage', 'create', 'edit', 'delete'],
            'email-template'        => ['manage', 'edit'],
            'sms-template'          => ['manage', 'edit'],
            'support-ticket'        => ['manage', 'create', 'edit', 'delete'],
            'domain-request'        => ['manage', 'create', 'edit', 'delete'],
            'change-domain'         => ['manage', 'create', 'edit', 'delete'],
            'event'                 => ['manage', 'create', 'edit', 'delete'],
            'page-setting'          => ['manage', 'create', 'edit', 'delete'],
            'announcement'          => ['manage', 'create', 'edit', 'delete'],
            'activity-log'          => ['manage'],
        ];

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin'
        ]);

        foreach ($allpermissions as $module => $permissions) {
            Module::firstOrCreate(['name' => $module]);
            foreach ($permissions as $permission) {
                $temp = Permission::firstOrCreate(['name' => $permission . '-' . $module]);
                $superAdmin->givePermissionTo($temp);
            }
        }

        $adminpermissions  = [
            'role'                  => ['manage', 'create', 'edit', 'delete', 'show'],
            'user'                  => ['manage', 'create', 'edit', 'delete', 'impersonate'],
            'setting'               => ['manage'],
            'plan'                  => ['manage', 'create', 'edit', 'delete'],
            'email-template'        => ['manage', 'edit'],
            'sms-template'          => ['manage', 'edit'],
            'event'                 => ['manage', 'create', 'edit', 'delete'],
            'page-setting'          => ['manage', 'create', 'edit', 'delete'],
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

        $user = User::firstOrCreate(['email' => 'superadmin@example.com'], [
            'name'              => 'Super Admin',
            'email'             => 'superadmin@example.com',
            'password'          => Hash::make('admin@1232'),
            'avatar'            => 'avatar/avatar.png',
            'type'              => 'Super Admin',
            'lang'              => 'en',
            'email_verified_at' => Carbon::now(),
            'phone_verified_at' => Carbon::now(),
            'phone'             => '1234567890',
            'country_code'      => 'in',
            'dial_code'         => '91',
        ]);

        $user->assignRole($superAdmin->id);

        $settings = [
            ['key' => 'app_name', 'value' => 'Cloud SAAS'],
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
            ['key' => 'domain_config', 'value' => 'off'],
            ['key' => 'dark_mode', 'value' => 'off'],
            ['key' => 'roles', 'value' => 'User'],
            ['key' => 'transparent_layout', 'value' => '1'],
            ['key' => 'landing_page_status', 'value' => '0'],

            ['key' => 'apps_setting_enable', 'value' => 'on'],
            ['key' => 'apps_name', 'value' => 'Could SAAS'],
            ['key' => 'apps_bold_name', 'value' => 'Admin Saas'],
            ['key' => 'app_detail', 'value' => 'Full Tenancy is a whole period of the time that the Tenant occupies the Property and / or pays rent for the Property,
                whichever is the longer, including the initial period of the Tenancy, any renewal or extension of the Tenancy and any period of holding over.'],
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
            ['key' => 'feature_name', 'value' => 'Full Multi Tenancy Laravel Admin Saas'],
            ['key' => 'feature_bold_name', 'value' => 'Features'],
            ['key' => 'feature_detail', 'value' => 'Full Multi Tenancy means that a single instance of the software and its supporting infrastructure serves multiple customers.
                In the early days of the cloud, organizations were reluctant to adopt cloud strategies.'],
            ['key' => 'feature_setting', 'value' => '[
                {"feature_image":"seeder-image/feature1.svg","feature_name":"Email Notification","feature_bold_name":"On From Submit","feature_detail":"You can send a notification email to someone in your organization when a contact submits a form. You can use this type of form processing step so that..."},
                {"feature_image":"seeder-image/feature2.svg","feature_name":"Two Factor","feature_bold_name":"Authentication","feature_detail":"Security is our priority. With our robust two-factor authentication (2FA) feature, you can add an extra layer of protection to your Full Tenancy Form"},
                {"feature_image":"seeder-image/feature3.svg","feature_name":"Multi Users With","feature_bold_name":"Role & permission","feature_detail":"Assign roles and permissions to different users based on their responsibilities and requirements. Admins can manage user accounts, define access level"},
                {"feature_image":"seeder-image/feature4.svg","feature_name":"Multiple Section","feature_bold_name":"RTL Setting","feature_detail":"The RTL circuit consists of resistors at inputs and transistors at the output side. Transistors are used as the switching device. The emitter of the transistor is connected to the ground."}
            ]'],

            ['key' => 'menu_setting_section1_enable', 'value' => 'on'],
            ['key' => 'menu_name_section1', 'value' => 'Dashboard'],
            ['key' => 'menu_bold_name_section1', 'value' => 'Apexchart'],
            ['key' => 'menu_detail_section1', 'value' => 'ApexChart enables users to create and customize dynamic, visually engaging charts for data visualization. Users can select chart types, configure data sources, apply filters, customize appearance, and interact with data through various chart-related features. '],
            ['key' => 'menu_image_section1', 'value' => 'seeder-image/menusection1.png'],

            ['key' => 'menu_setting_section2_enable', 'value' => 'on'],
            ['key' => 'menu_name_section2', 'value' => 'Support System With'],
            ['key' => 'menu_bold_name_section2', 'value' => 'Issue Resolution'],
            ['key' => 'menu_detail_section2', 'value' => 'The Support System section is your gateway to comprehensive assistance. It provides access to a knowledge base, FAQs, and direct contact options for user inquiries and assistance.'],
            ['key' => 'menu_image_section2', 'value' => 'seeder-image/menusection2.png'],

            ['key' => 'menu_setting_section3_enable', 'value' => 'on'],
            ['key' => 'menu_name_section3', 'value' => 'Setting Features With'],
            ['key' => 'menu_bold_name_section3', 'value' => 'Multiple Section settings'],
            ['key' => 'menu_detail_section3', 'value' => 'A settings page is a crucial component of many digital products, allowing users to customize their experience according to their preferences. Designing a settings page with dynamic data enhances user satisfaction and engagement. Here s a guide on how to create such a page.'],
            ['key' => 'menu_image_section3', 'value' => 'seeder-image/menusection3.png'],

            ['key' => 'business_growth_setting_enable', 'value' => 'on'],
            ['key' => 'business_growth_front_image', 'value' => 'seeder-image/thumbnail.png'],
            ['key' => 'business_growth_video', 'value' => 'seeder-image/video.webm'],
            ['key' => 'business_growth_name', 'value' => 'Makes Quick'],
            ['key' => 'business_growth_bold_name', 'value' => 'Business Growth'],
            ['key' => 'business_growth_detail', 'value' => 'Offer unique products, services, or solutions that stand out in the market. Innovation and differentiation can attract customers and give you a competitive edge.'],
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

            ['key' => 'plan_setting_enable', 'value' => 'on'],
            ['key' => 'plan_name', 'value' => 'Simple, Flexible'],
            ['key' => 'plan_bold_name', 'value' => 'Pricing'],
            ['key' => 'plan_detail', 'value' => 'The pricing structure is easy to comprehend, and all costs and fees are explicitly stated. There are no hidden charges or surprise costs for customers.'],

            ['key' => 'contactus_setting_enable', 'value' => 'on'],
            ['key' => 'contactus_name', 'value' => 'Enterprise'],
            ['key' => 'contactus_bold_name', 'value' => 'Custom pricing'],
            ['key' => 'contactus_detail', 'value' => 'Offering tiered pricing options based on different levels of features or services allows customers.'],

            ['key' => 'faq_setting_enable', 'value' => 'on'],
            ['key' => 'faq_name', 'value' => 'Frequently asked questions'],

            ['key' => 'start_view_setting_enable', 'value' => 'on'],
            ['key' => 'start_view_name', 'value' => 'Start Using Full Multi Tenancy Laravel Admin Saas'],
            ['key' => 'start_view_detail', 'value' => 'a Full Multi Tenancy Laravel Admin Saas application is a complex process that requires careful planning and development.'],
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
            ['key' => 'footer_description', 'value' => 'SAAS'],

            ['key' => 'announcements_setting_enable', 'value' => 'on'],
            ['key' => 'announcements_title', 'value' => 'Announcements'],
            ['key' => 'announcement_short_description', 'value' => 'An announcement letter is a formal document that can highlight possible changes occurring within a company or other relevant information. Companies send announcement letters to business clients, sales prospects or to their own employees, depending on the focus of the announcement.'],

        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate($setting);
        }

        Plan::firstOrCreate(['name' => 'Free'], [
            'name'              => 'Free',
            'price'             => '0',
            'duration'          => '1',
            'durationtype'      => 'Month',
            'description'       => 'A payment plan an organized payment schedule.',
            'max_users'         => '5',
            'discount_setting'  => 'off',
            'discount'          => null,
        ]);

        SmsTemplate::firstOrCreate(['event' => 'verification code sms'], [
            'event'     => 'verification code sms',
            'template'  => 'Hello :name, Your verification code is :code',
            'variables' => 'name,code'
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\TestMail::class], [
            'mailable'      => \App\Mail\Superadmin\TestMail::class,
            'subject'       => 'Mail send for testing purpose',
            'html_template' => '<p><strong>This Mail For Testing</strong></p>
            <p><strong>Thanks</strong></p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\ApproveMail::class], [
            'mailable'      => \App\Mail\Superadmin\ApproveMail::class,
            'subject'       => 'Domain Verified',
            'html_template' => '<p><strong>Hi {{name}}</strong></p>
            <p><strong>Your Domain&nbsp;{{ domain_name }}&nbsp;&nbsp;is Verified By SuperAdmin</strong></p>
            <p><strong>Please Check with by click below link</strong></p>
            <p><a href="{{login_button_url}}">Login</a></p>
            <p>&nbsp;</p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\DisapprovedMail::class], [
            'mailable'      => \App\Mail\Superadmin\DisapprovedMail::class,
            'subject'       => 'Domain Unverified',
            'html_template' => '<p><strong>Hi&nbsp;{{ name }}</strong></p>
            <p><strong>Your Domain&nbsp;{{ domain_name }}&nbsp;is not Verified By SuperAdmin </strong></p>
            <p><strong>Because&nbsp;{{ reason }}</strong></p>
            <p><strong>Please contact to SuperAdmin</strong></p>
            <p>&nbsp;</p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\ApproveOfflineMail::class], [
            'mailable'      => \App\Mail\Superadmin\ApproveOfflineMail::class,
            'subject'       => 'Offline Payment Request Verified',
            'html_template' => '<p><strong>Hi&nbsp;&nbsp;{{ name }}</strong></p>
            <p><strong>Your Plan Update Request {{ email }}&nbsp;is Verified By Super Admin</strong></p>
            <p><strong>Please Check</strong></p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\OfflineMail::class], [
            'mailable'      => \App\Mail\Superadmin\OfflineMail::class,
            'subject'       => 'Offline Payment Request Unverified',
            'html_template' => '<p><strong>Hi&nbsp;{{ name }}</strong></p>
            <p><strong>Your Request Payment {{ email }}&nbsp;Is Disapprove By Super Admin</strong></p>
            <p><strong>Because&nbsp;{{ disapprove_reason }}</strong></p>
            <p><strong>Please Contact to Super Admin</strong></p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\ConatctMail::class], [
            'mailable'      => \App\Mail\Superadmin\ConatctMail::class,
            'subject'       => 'New Enquiry Details',
            'html_template' => '<p><strong>Name : {{name}}</strong></p>
            <p><strong>Email : </strong><strong>{{email}}</strong></p>
            <p><strong>Contact No : {{ contact_no }}&nbsp;</strong></p>
            <p><strong>Message :&nbsp;</strong><strong>{{ message }}</strong></p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\PasswordReset::class], [
            'mailable'      => \App\Mail\Superadmin\PasswordReset::class,
            'subject'       => 'Reset Password Notification',
            'html_template' => '<p><strong>Hello!</strong></p><p>You are receiving this email because we received a password reset request for your account. To proceed with the password reset please click on the link below:</p><p><a href="{{url}}">Reset Password</a></p><p>This password reset link will expire in 60 minutes.&nbsp;<br>If you did not request a password reset, no further action is required.</p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\SupportTicketMail::class], [
            'mailable'      => \App\Mail\Superadmin\SupportTicketMail::class,
            'subject'       => 'New Ticket Opened',
            'html_template' => '<p><strong>New Ticket Create {{ name }}</strong></p>
            <p><strong>A request for new Ticket&nbsp;&nbsp;{{ ticket_id }}</strong></p>
            <p><strong>Title : {{ title }}</strong></p>
            <p><strong>Email : {{ email }}</strong></p>
            <p><strong>Description :&nbsp;{{ description }}</strong></p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\ReceiveTicketReplyMail::class], [
            'mailable'      => \App\Mail\Superadmin\ReceiveTicketReplyMail::class,
            'subject'       => 'Received Ticket Reply',
            'html_template' => '<p><strong>Your Ticket id&nbsp; {{ ticket_id }} New&nbsp;Reply</strong></p>
            <p><strong>{{ reply }}</strong></p>
            <p><strong>Thank you</strong></p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\SupportTicketReplyMail::class], [
            'mailable'      => \App\Mail\Superadmin\SupportTicketReplyMail::class,
            'subject'       => 'Send Ticket Reply',
            'html_template' => '<p><strong>Your Ticket id&nbsp; {{ ticket_id }} New&nbsp;Reply</strong></p>
            <p><strong>{{ reply }}</strong></p>
            <p><strong>Thank you</strong></p>',
            'text_template' => null,
        ]);

        MailTemplate::firstOrCreate(['mailable' => \App\Mail\Superadmin\RegisterMail::class], [
            'mailable'      => \App\Mail\Superadmin\RegisterMail::class,
            'subject'       => 'Register Mail',
            'html_template' => '<p><strong>Hi {{name}}</strong></p>
            <p><strong>Email : {{email}}</strong></p>
            <p><strong>Domain : {{domain_name}}</strong></p>
            <p><strong>Note:Please link your domain with {{domain_ip}} ip address</strong></p>
            <p><strong>Thanks for registration, your account is in review and you get email when your account active.</strong></p>',
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

        NotificationsSetting::firstOrCreate(['title' => 'New Ticket Opened'], [
            'title'                 => 'New Ticket Opened',
            'email_notification'    => '1',
            'sms_notification'      => '2',
            'notify'                => '1',
            'status'                => '2',
        ]);

        NotificationsSetting::firstOrCreate(['title' => 'Received Ticket Reply'], [
            'title'                 => 'Received Ticket Reply',
            'email_notification'    => '1',
            'sms_notification'      => '2',
            'notify'                => '1',
            'status'                => '2',
        ]);

        NotificationsSetting::firstOrCreate(['title' => 'Domain Verified'], [
            'title'                 => 'Domain Verified',
            'email_notification'    => '1',
            'sms_notification'      => '2',
            'notify'                => '1',
            'status'                => '2',
        ]);

        NotificationsSetting::firstOrCreate(['title' => 'Domain Unverified'], [
            'title'                 => 'Domain Unverified',
            'email_notification'    => '1',
            'sms_notification'      => '2',
            'notify'                => '1',
            'status'                => '2',
        ]);

        NotificationsSetting::firstOrCreate(['title' => 'Offline Payment Request Verified'], [
            'title' => 'Offline Payment Request Verified',
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
