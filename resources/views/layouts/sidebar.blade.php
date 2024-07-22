@php
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
    $languages = Utility::languages();
@endphp
<nav class="dash-sidebar light-sidebar {{ Utility::getsettings('transparent_layout') == 1 ? 'transprent-bg' : '' }}">
    <div class="navbar-wrapper">
        <div class="m-headers logo-col">
            <a href="{{ route('home') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                @if ($users->dark_layout == 1)
                    <img src="{{ Utility::getsettings('app_logo') ? Utility::getpath('logo/app-logo.png') : asset('assets/images/logo/app-logo.png') }}"
                        class="footer-light-logo">
                @else
                    <img src="{{ Utility::getsettings('app_dark_logo') ? Utility::getpath('logo/app-dark-logo.png') : asset('assets/images/logo/app-dark-logo.png') }}"
                        class="footer-dark-logo">
                @endif
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar">
                <li class="dash-item dash-hasmenu">
                    <a href="{{ route('home') }}" class="dash-link">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @if ($users->type == 'Super Admin')
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('users*') || request()->is('roles*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-layout-2"></i></span><span
                                class="dash-mtext">{{ __('Agency Management') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-user')
                                <li class="dash-item {{ request()->is('users*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('users.index') }}">{{ __('Agencies') }}</a>
                                </li>
                            @endcan
                            @can('manage-role')
                                <li class="dash-item {{ request()->is('roles*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Roles') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('request-domain*') || request()->is('change-domain*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-lock"></i></span><span
                                class="dash-mtext">{{ __('Domains') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-domain-request')
                                <li class="dash-item {{ request()->is('request-domain*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('request.domain.index') }}">{{ __('Domain Requests') }}</a>
                                </li>
                            @endcan
                            @can('manage-domain-request')
                                <li class="dash-item {{ request()->is('change-domain*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('changedomain') }}">{{ __('Change Domain') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('coupon*') || request()->is('plans*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-gift"></i></span><span
                                class="dash-mtext">{{ __('Subscription') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-coupon')
                                <li class="dash-item {{ request()->is('coupon*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('coupon.index') }}">{{ __('Coupons') }}</a>
                                </li>
                            @endcan
                            @can('manage-plan')
                                <li
                                    class="dash-item {{ request()->is('plans*') || request()->is('payment*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('plans.index') }}">{{ __('Plans') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('support-ticket*') || request()->is('announcement*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-database"></i></span><span
                                class="dash-mtext">{{ __('Support') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            <li class="dash-item {{ request()->is('support-ticket*') ? 'active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('support-ticket.index') }}">{{ __('Support Tickets') }}</a>
                            </li>
                            @can('manage-announcement')
                                <li class="dash-item {{ request()->is('announcement*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('announcement.index') }}">{{ __('Announcement') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                    @can('manage-activity-log')
                        <li class="dash-item dash-hasmenu {{ request()->is('activity-log*') ? 'active' : '' }}">
                            <a href="{{ route('activity.log.index') }}" class="dash-link">
                                <span class="dash-micon">
                                    <i class="ti ti-activity">
                                    </i>
                                </span>
                                <span class="dash-mtext">{{ __('Activity Log') }}
                                </span>
                            </a>
                        </li>
                    @endcan
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('email-template*') || request()->is('manage-language*') || request()->is('create-language*') || request()->is('sms-template*') || request()->is('settings*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-apps"></i></span><span
                                class="dash-mtext">{{ __('Account Setting') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-email-template')
                                <li class="dash-item {{ request()->is('email-template*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('email-template.index') }}">{{ __('Email Templates') }}</a>
                                </li>
                            @endcan
                            @can('manage-sms-template')
                                <li class="dash-item {{ request()->is('sms-template*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('sms-template.index') }}">{{ __('Sms Templates') }}</a>
                                </li>
                            @endcan
                            @can('manage-langauge')
                                <li
                                    class="dash-item {{ request()->is('manage-language*') || request()->is('create-language*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('manage.language', [$currantLang]) }}">{{ __('Manage Languages') }}</a>
                                </li>
                            @endcan
                            @can('manage-setting')
                                <li class="dash-item {{ request()->is('settings*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('settings') }}">{{ __('Settings') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @if ($users->type != 'Super Admin')
                    @canany(['manage-user', 'manage-role'])
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('users*') || request()->is('roles*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-layout-2"></i></span><span
                                    class="dash-mtext">{{ __('User Management') }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @can('manage-user')
                                    <li class="dash-item {{ request()->is('users*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                                    </li>
                                @endcan
                                @can('manage-role')
                                    <li class="dash-item {{ request()->is('roles*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Roles') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                        @canany(['manage-client'])
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('client*') || request()->is('client*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-users"></i></span><span
                                    class="dash-mtext">{{ __('Client Management') }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @can('manage-client')
                                    <li class="dash-item {{ request()->is('client*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('client.index') }}">{{ __('All Clients') }}</a>
                                    </li>
                                @endcan
                                @can('create-client')
                                    <li class="dash-item {{ request()->is('client*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('client.create') }}">{{ __('Create') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                        <li
                            class="dash-item dash-hasmenu {{ request()->is('flight*') || request()->is('flight*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="fa fa-plane"></i></span><span
                                    class="dash-mtext">{{ __('Flight') }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                    <li class="dash-item {{ request()->is('flight') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('flight.index') }}">{{ __('Book Flights') }}</a>
                                    </li>
                                    <li class="dash-item {{ request()->is('flight/booked*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('flight.booked') }}">{{ __('Booked Flights') }}</a>
                                    </li>
                                    <li class="dash-item {{ request()->is('client*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('client.create') }}">{{ __('Reports') }}</a>
                                    </li>
                            </ul>
                        </li>


                        <li
                            class="dash-item dash-hasmenu {{ request()->is('hotel*') || request()->is('hotel*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="fa fa-hotel"></i></span><span
                                    class="dash-mtext">{{ __('Hotel') }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                <li class="dash-item {{ request()->is('hotel*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('hotel.index') }}">{{ __('Book Hotels') }}</a>
                                </li>
                                <li class="dash-item {{ request()->is('client*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('client.create') }}">{{ __('Booked Hotels') }}</a>
                                </li>
                                <li class="dash-item {{ request()->is('client*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('client.create') }}">{{ __('Reports') }}</a>
                                </li>
                            </ul>
                        </li>


                        <li
                            class="dash-item dash-hasmenu {{ request()->is('visa*') || request()->is('visa*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="fa fa-passport"></i></span><span
                                    class="dash-mtext">{{ __('Visa') }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                <li class="dash-item {{ request()->is('hotel*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('hotel.index') }}">{{ __('Visa') }}</a>
                                </li>
                                <li class="dash-item {{ request()->is('client*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('client.create') }}">{{ __('Active Files') }}</a>
                                </li>
                                <li class="dash-item {{ request()->is('client*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('client.create') }}">{{ __('Reports') }}</a>
                                </li>
                            </ul>
                        </li>


                        <li class="dash-item dash-hasmenu {{ request()->is('event*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('event.index') }}"><span class="dash-micon">
                                    <i class="fa fa-file-invoice-dollar"></i></span>
                                <span class="dash-mtext">{{ __('Invoice') }}</span>
                            </a>
                        </li>
                    @can('manage-event')
                        <li class="dash-item dash-hasmenu {{ request()->is('event*') ? 'active' : '' }}">
                            <a class="dash-link" href="{{ route('event.index') }}"><span class="dash-micon">
                                    <i class="ti ti-calendar"></i></span>
                                <span class="dash-mtext">{{ __('Event') }}</span>
                            </a>
                        </li>
                    @endcan


                    <li
                        class="dash-item dash-hasmenu {{ request()->is('chat*') || request()->is('show-announcement*') || request()->is('show-announcement-list*') || request()->is('support-ticket*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-database"></i></span><span
                                class="dash-mtext">{{ __('Support') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @if (Utility::getsettings('pusher_status') == '1')
                                <li class="dash-item {{ request()->is('chat*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('chat') }}">{{ __('Chats') }}</a>
                                </li>
                            @endif
                            <li class="dash-item {{ request()->is('support-ticket*') ? 'active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('support-ticket.index') }}">{{ __('Support Tickets') }}</a>
                            </li>
                            @can('manage-announcement')
                                <li
                                    class="dash-item {{ request()->is('show-announcement-list*') || request()->is('show-announcement*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('show.announcement.list') }}">{{ __('Show Announcement List') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                    @can('manage-activity-log')
                        <li class="dash-item dash-hasmenu {{ request()->is('activity-log*') ? 'active' : '' }}">
                            <a href="{{ route('activity.log.index') }}" class="dash-link">
                                <span class="dash-micon">
                                    <i class="ti ti-activity">
                                    </i>
                                </span>
                                <span class="dash-mtext">{{ __('Activity Log') }}
                                </span>
                            </a>
                        </li>
                    @endcan
                    @canany(['manage-setting', 'manage-email-template', 'manage-sms-template'])
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('email-template*') || request()->is('sms-template*') || request()->is('settings*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-apps"></i></span><span
                                    class="dash-mtext">{{ __('Account Setting') }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @can('manage-email-template')
                                    <li class="dash-item {{ request()->is('email-template*') ? 'active' : '' }}">
                                        <a class="dash-link"
                                            href="{{ route('email-template.index') }}">{{ __('Email Templates') }}</a>
                                    </li>
                                @endcan
                                @can('manage-sms-template')
                                    <li class="dash-item {{ request()->is('sms-template*') ? 'active' : '' }}">
                                        <a class="dash-link"
                                            href="{{ route('sms-template.index') }}">{{ __('Sms Templates') }}</a>
                                    </li>
                                @endcan
                                @can('manage-setting')
                                    <li class="dash-item {{ request()->is('settings*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('settings') }}">{{ __('Settings') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif
            </ul>
        </div>
    </div>
</nav>