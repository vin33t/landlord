@php
    $lang = Utility::getValByName('default_language');
    $primary_color = Utility::getsettings('color');
    if (isset($primary_color)) {
        $color = $primary_color;
    } else {
        $color = 'theme-4';
    }
@endphp
@extends('layouts.main')
@section('title', __('Settings'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top stick-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#app_setting" class="border-0 list-group-item list-group-item-action">
                                {{ __('App Setting') }}
                                <div class="float-end">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </a>
                            <a href="#general_setting" class="border-0 list-group-item list-group-item-action">
                                {{ __('General Setting') }}
                                <div class="float-end">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </a>
                            <a href="#domainconfig_setting" class="border-0 list-group-item list-group-item-action">
                                {{ __('Domain Configuration Setting') }}
                                <div class="float-end">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </a>
                            <a href="#storage_setting" class="border-0 list-group-item list-group-item-action">
                                {{ __('Storage Setting') }}
                                <div class="float-end">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </a>
                            <a href="#email_setting" class="border-0 list-group-item list-group-item-action">
                                {{ __('Email Setting') }}
                                <div class="float-end">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </a>
                            <a href="#cookie_setting" class="list-group-item list-group-item-action border-0">
                                {{ __('Cookie Setting') }}
                                <div class="float-end">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </a>
                            <a href="#cache_setting" class="list-group-item list-group-item-action border-0">
                                {{ __('Cache Setting') }}
                                <div class="float-end">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </a>
                            <a href="#seo_setting" class="list-group-item list-group-item-action border-0">
                                {{ __('SEO Setting') }}
                                <div class="float-end">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </a>
                            <a href="#notification_setting" class="list-group-item list-group-item-action border-0">
                                {{ __('Notification Setting') }}
                                <div class="float-end">
                                    <i class="ti ti-chevron-right"></i>
                                </div>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="app_setting">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('App Setting') }}</h5>
                            </div>
                            {!! Form::open([
                                'route' => 'settings.appname.update',
                                'method' => 'Post',
                                'enctype' => 'multipart/form-data',
                                'data-validate',
                            ]) !!}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('App Dark Logo') }}</h5>
                                            </div>
                                            <div class="pt-0 card-body">
                                                <div class="inner-content">
                                                    <div class="py-2 mt-4 text-center logo-content dark-logo-content">
                                                        <a href="{{ Utility::getpath(Utility::getsettings('app_dark_logo')) }}"
                                                            target="_blank">
                                                            <img src="{{ Utility::getpath(Utility::getsettings('app_dark_logo')) }}"
                                                                id="app_dark">
                                                        </a>
                                                    </div>
                                                    <div class="mt-3 text-center choose-files">
                                                        <label for="app_dark_logo">
                                                            <div class="bg-primary company_logo_update"> <i
                                                                    class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            {{ Form::file('app_dark_logo', ['class' => 'form-control file', 'id' => 'app_dark_logo', 'onchange' => "document.getElementById('app_dark').src = window.URL.createObjectURL(this.files[0])", 'data-filename' => 'app_dark_logo']) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('App Light Logo') }}</h5>
                                            </div>
                                            <div class="pt-0 card-body bg-primary">
                                                <div class="inner-content">
                                                    <div class="py-2 mt-4 text-center logo-content light-logo-content">
                                                        <a href="{{ Utility::getpath(Utility::getsettings('app_logo')) }}"
                                                            target="_blank">
                                                            <img src="{{ Utility::getpath(Utility::getsettings('app_logo')) }}"
                                                                id="app_light">
                                                        </a>
                                                    </div>
                                                    <div class="mt-3 text-center choose-files">
                                                        <label for="app_logo">
                                                            <div class="company_logo_update w-logo"> <i
                                                                    class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            {{ Form::file('app_logo', ['class' => 'form-control file', 'id' => 'app_logo', 'onchange' => "document.getElementById('app_light').src = window.URL.createObjectURL(this.files[0])", 'data-filename' => 'app_logo']) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-6 col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>{{ __('App Favicon Logo') }}</h5>
                                            </div>
                                            <div class="pt-0 card-body">
                                                <div class="inner-content">
                                                    <div class="py-2 mt-4 text-center logo-content">
                                                        <a href="{{ Utility::getpath(Utility::getsettings('favicon_logo')) }}"
                                                            target="_blank">
                                                            <img height="35px"
                                                                src="{{ Utility::getpath(Utility::getsettings('favicon_logo')) }}"
                                                                id="app_favicon">
                                                        </a>
                                                    </div>
                                                    <div class="mt-3 text-center choose-files">
                                                        <label for="favicon_logo">
                                                            <div class="bg-primary company_logo_update"> <i
                                                                    class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                            </div>
                                                            {{ Form::file('favicon_logo', ['class' => 'form-control file', 'id' => 'favicon_logo', 'onchange' => "document.getElementById('app_favicon').src = window.URL.createObjectURL(this.files[0])", 'data-filename' => 'favicon_logo']) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('app_name', __('Application Name'), ['class' => 'form-label']) }}
                                        {!! Form::text('app_name', Utility::getsettings('app_name'), [
                                            'class' => 'form-control',
                                            'placeholder' => __('Enter application name'),
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary ']) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div id="general_setting">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('General Setting') }}</h5>
                            </div>
                            {!! Form::open([
                                'route' => 'settings.auth.settings.update',
                                'method' => 'Post',
                                'data-validate',
                            ]) !!}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <strong class="d-block">{{ __('Two Factor Authentication') }}</strong>
                                                    {{ !Utility::getsettings('2fa') ? 'Activate' : 'Deactivate' }}
                                                    {{ __('Two Factor Authentication') }}
                                                </div>
                                                <div class="col-sm-4 text-end">
                                                    {!! Form::checkbox('two_factor_auth', null, Utility::getsettings('2fa') ? true : false, [
                                                        'data-toggle' => 'switchbutton',
                                                        'data-onstyle' => 'primary',
                                                    ]) !!}
                                                </div>
                                                @if (!extension_loaded('imagick'))
                                                    <small>
                                                        {{ __('Note: for 2FA your server must have Imagick.') }} <a
                                                            href="https://www.php.net/manual/en/book.imagick.php"
                                                            target="_new">{{ __('Imagick Document') }}</a>
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <strong class="d-block">{{ __('Database Permission') }}</strong>
                                                    {{ Utility::getsettings('database_permission') == '0' ? __('Activate') : __('Deactivate') }}
                                                    {{ __('To Automatically Generate a User Database.') }}
                                                </div>
                                                <div class="col-sm-4 text-end">
                                                    {!! Form::checkbox(
                                                        'database_permission',
                                                        null,
                                                        Utility::getsettings('database_permission') == 1 ? true : false,
                                                        [
                                                            'data-toggle' => 'switchbutton',
                                                            'data-onstyle' => 'primary',
                                                        ],
                                                    ) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <strong class="d-block">{{ __('Landing Page Setting') }}</strong>
                                                    {{ Utility::getsettings('landing_page_status') == '1' ? __('Deactivate') : __('Activate') }}
                                                    {{ __('Landing Page For Application.') }}
                                                </div>
                                                <div class="col-sm-4 text-end">
                                                    {!! Form::checkbox(
                                                        'landing_page_status',
                                                        null,
                                                        Utility::getsettings('landing_page_status') == 1 ? true : false,
                                                        [
                                                            'data-toggle' => 'switchbutton',
                                                            'data-onstyle' => 'primary',
                                                        ],
                                                    ) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <strong class="d-block">{{ __('RTL Setting') }}</strong>
                                                    {{ Utility::getsettings('rtl') == '0' ? __('Activate') : __('Deactivate') }}
                                                    {{ __('Rtl Setting For Application.') }}
                                                </div>
                                                <div class="col-sm-4 text-end">
                                                    {!! Form::checkbox('rtl_setting', null, Utility::getsettings('rtl') == '1' ? true : false, [
                                                        'data-toggle' => 'switchbutton',
                                                        'data-onstyle' => 'primary',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="my-3">{{ __('Theme Customizer') }}</h5>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6>
                                                        <i data-feather="credit-card"
                                                            class="me-2"></i>{{ __('Primary color Settings') }}
                                                    </h6>
                                                    <hr class="my-2">
                                                    <div class="theme-color themes-color">
                                                        <a href="#!"
                                                            class="{{ $color == 'theme-1' ? 'active_color' : '' }}"
                                                            data-value="theme-1" onclick="check_theme('theme-1')"></a>
                                                        <input type="radio" class="theme_color tm-color" name="color"
                                                            value="theme-1">
                                                        <a href="#!"
                                                            class="{{ $color == 'theme-2' ? 'active_color' : '' }} "
                                                            data-value="theme-2" onclick="check_theme('theme-2')"></a>
                                                        <input type="radio" class="theme_color tm-color" name="color"
                                                            value="theme-2">
                                                        <a href="#!"
                                                            class="{{ $color == 'theme-3' ? 'active_color' : '' }}"
                                                            data-value="theme-3" onclick="check_theme('theme-3')"></a>
                                                        <input type="radio" class="theme_color tm-color" name="color"
                                                            value="theme-3">
                                                        <a href="#!"
                                                            class="{{ $color == 'theme-4' ? 'active_color' : '' }}"
                                                            data-value="theme-4" onclick="check_theme('theme-4')"></a>
                                                        <input type="radio" class="theme_color tm-color" name="color"
                                                            value="theme-4">
                                                        <a href="#!"
                                                            class="{{ $color == 'theme-5' ? 'active_color' : '' }}"
                                                            data-value="theme-5" onclick="check_theme('theme-5')"></a>
                                                        <input type="radio" class="theme_color tm-color" name="color"
                                                            value="theme-5">
                                                        <br>
                                                        <a href="#!"
                                                            class="{{ $color == 'theme-6' ? 'active_color' : '' }}"
                                                            data-value="theme-6" onclick="check_theme('theme-6')"></a>
                                                        <input type="radio" class="theme_color tm-color" name="color"
                                                            value="theme-6">
                                                        <a href="#!"
                                                            class="{{ $color == 'theme-7' ? 'active_color' : '' }}"
                                                            data-value="theme-7" onclick="check_theme('theme-7')"></a>
                                                        <input type="radio" class="theme_color tm-color" name="color"
                                                            value="theme-7">
                                                        <a href="#!"
                                                            class="{{ $color == 'theme-8' ? 'active_color' : '' }}"
                                                            data-value="theme-8" onclick="check_theme('theme-8')"></a>
                                                        <input type="radio" class="theme_color tm-color" name="color"
                                                            value="theme-8">
                                                        <a href="#!"
                                                            class="{{ $color == 'theme-9' ? 'active_color' : '' }}"
                                                            data-value="theme-9" onclick="check_theme('theme-9')"></a>
                                                        <input type="radio" class="theme_color tm-color" name="color"
                                                            value="theme-9">
                                                        <a href="#!"
                                                            class="{{ $color == 'theme-10' ? 'active_color' : '' }}"
                                                            data-value="theme-10" onclick="check_theme('theme-10')"></a>
                                                        <input type="radio" class="theme_color tm-color" name="color"
                                                            value="theme-10">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <h6>
                                                        <i data-feather="layout"
                                                            class="me-2"></i>{{ __('Sidebar Settings') }}
                                                    </h6>
                                                    <hr class="my-2">
                                                    <div class="form-check form-switch">
                                                        {!! Form::checkbox(
                                                            'transparent_layout',
                                                            null,
                                                            Utility::getsettings('transparent_layout') == '1' ? true : false,
                                                            [
                                                                'id' => 'cust-theme-bg',
                                                                'class' => 'form-check-input',
                                                            ],
                                                        ) !!}
                                                        {!! Form::label('cust-theme-bg', __('Transparent layout'), ['class' => 'form-check-label f-w-600 pl-1 me-2']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <h6>
                                                        <i data-feather="sun"
                                                            class="me-2"></i>{{ __('Layout Settings') }}
                                                    </h6>
                                                    <hr class="my-2">
                                                    <div class="mt-2 form-check form-switch">
                                                        {!! Form::checkbox('dark_mode', null, Utility::getsettings('dark_mode') == 'on' ? true : false, [
                                                            'id' => 'cust-darklayout',
                                                            'class' => 'form-check-input',
                                                        ]) !!}
                                                        {!! Form::label('cust-darklayout', __('Dark Layout'), ['class' => 'form-check-label f-w-600 pl-1 me-2']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('default_language', __('Default Language'), ['class' => 'form-label']) }}
                                            <select class="form-control" data-trigger name="default_language"
                                                id="default_language"
                                                placeholder="{{ __('This is a search placeholder') }}">
                                                @foreach (Utility::languages() as $language)
                                                    <option @if ($lang == $language) selected @endif
                                                        value="{{ $language }}">
                                                        {{ Str::upper($language) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('date_format', __('Date Format'), ['class' => 'form-label']) }}
                                            <select name="date_format" class="form-control" id="date_format"
                                                data-trigger>
                                                <option value="M j, Y"
                                                    {{ Utility::getsettings('date_format') == 'M j, Y' ? 'selected' : '' }}>
                                                    {{ __('Jan 1, 2020') }}</option>
                                                <option value="d-M-y"
                                                    {{ Utility::getsettings('date_format') == 'd-M-y' ? 'selected' : '' }}>
                                                    {{ __('01-Jan-20') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('time_format', __('Time Format'), ['class' => 'form-label']) }}
                                            <select name="time_format" class="form-control" id="time_format"
                                                data-trigger>
                                                <option value="g:i A"
                                                    {{ Utility::getsettings('time_format') == 'g:i A' ? 'selected' : '' }}>
                                                    {{ __('hh:mm AM/PM') }}</option>
                                                <option value="H:i:s"
                                                    {{ Utility::getsettings('time_format') == 'H:i:s' ? 'selected' : '' }}>
                                                    {{ __('HH:mm:ss') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    @if (\Auth::user()->type == 'Super Admin')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('gtag', __('Gtag Tracking ID'), ['class' => 'form-label']) }}
                                                {!! Html::link(
                                                    'https://support.google.com/analytics/answer/1008080?hl=en#zippy=%2Cin-this-article',
                                                    __('Document'),
                                                    ['target' => '_blank'],
                                                ) !!}
                                                {!! Form::text('gtag', Utility::getsettings('gtag'), [
                                                    'class' => 'form-control',
                                                    'placeholder' => __('Enter gtag tracking id'),
                                                ]) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div id="domainconfig_setting">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Domain Configuration Setting') }}</h5>
                                <small class="text-muted">{{ __('Domain Configuration') }}</small>
                            </div>
                            {!! Form::open([
                                'route' => 'settings.domain.config.setting.update',
                                'method' => 'Post',
                                'data-validate',
                            ]) !!}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-sm">
                                            {{ __('Note: if you want to use it on sub domain the check it otherwise unchecked for use other domain') }}
                                        </p>
                                        <div class="alert alert-info">
                                            <div class="app-image-set">
                                                <a>
                                                    <img src="{{ Utility::getpath('seeder-image/domain-config.png') }}">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-8">
                                                {{ Form::label('domain_config', __('Domain Config'), ['class' => 'form-label']) }}
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <label class="form-switch custom-switch-v1">
                                                    {!! Form::checkbox('domain_config', null, Utility::getsettings('domain_config') == 'on' ? true : false, [
                                                        'class' => 'form-check-input',
                                                        'id' => 'domain_config',
                                                    ]) !!}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group main-domain {{ Utility::getsettings('domain_config') == 'on' ? 'd-block' : 'd-none' }}">
                                            {{ Form::label('main_domain', __('Main Domain'), ['class' => 'form-label']) }}
                                            {!! Form::text('main_domain', env('APP_URL'), [
                                                'class' => 'form-control',
                                                'readonly',
                                                'disabled',
                                                'placeholder' => __('Enter main domain'),
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div id="storage_setting">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Storage Setting') }}</h5>
                                <small class="text-muted">{{ __('Aws,S3 Storage Configuration') }}</small>
                            </div>
                            {!! Form::open([
                                'route' => 'settings.s3.setting.update',
                                'method' => 'Post',
                                'data-validate',
                            ]) !!}
                            <div class="card-body">
                                <small
                                class="text">{{ __('Notes: If you Add S3 & wasabi Storage settings you have to store images First.') }}</small>
                                <div class="form-group mt-2">
                                    <div class="d-flex">
                                        <div class="pe-2">
                                            {!! Form::radio('storage_type', 'local', Utility::getsettings('storage_type') == 'local' ? true : false, [
                                                'class' => 'btn-check',
                                                'id' => 'local-outlined',
                                            ]) !!}
                                            {!! Form::label('local-outlined', __('Local'), ['class' => 'btn btn-outline-primary']) !!}
                                        </div>
                                        <div class="pe-2">
                                            {!! Form::radio('storage_type', 's3', Utility::getsettings('storage_type') == 's3' ? true : false, [
                                                'class' => 'btn-check',
                                                'id' => 's3-outlined',
                                            ]) !!}
                                            {!! Form::label('s3-outlined', __('AWS S3'), ['class' => 'btn btn-outline-primary']) !!}
                                        </div>

                                        <div class="pe-2">
                                            {!! Form::radio('storage_type', 'wasabi', Utility::getsettings('storage_type') == 'wasabi' ? true : false, [
                                                'class' => 'btn-check',
                                                'id' => 'wasabi-outlined',
                                            ]) !!}
                                            {!! Form::label('wasabi-outlined', __('Wasabi'), ['class' => 'btn btn-outline-primary']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div
                                            class="local-setting {{ Utility::getsettings('storage_type') == 'local' ? 'block' : 'd-none' }}">
                                        </div>
                                        <div
                                            class="s3-setting {{ Utility::getsettings('storage_type') == 's3' ? 'block' : 'd-none' }}">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('s3_key', __('S3 Key'), ['class' => 'form-label']) }}
                                                        {!! Form::text('s3_key', Utility::getsettings('s3_key'), [
                                                            'placeholder' => __('Enter s3 key'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('s3_secret', __('S3 Secret'), ['class' => 'form-label']) }}
                                                        {!! Form::text('s3_secret', Utility::getsettings('s3_secret'), [
                                                            'placeholder' => __('Enter s3 secret'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('s3_region', __('S3 Region'), ['class' => 'form-label']) }}
                                                        {!! Form::text('s3_region', Utility::getsettings('s3_region'), [
                                                            'placeholder' => __('Enter s3 region'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('s3_bucket', __('S3 Bucket'), ['class' => 'form-label']) }}
                                                        {!! Form::text('s3_bucket', Utility::getsettings('s3_bucket'), [
                                                            'placeholder' => __('Enter s3 bucket'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('s3_url', __('S3 URL'), ['class' => 'form-label']) }}
                                                        {!! Form::text('s3_url', Utility::getsettings('s3_url'), [
                                                            'placeholder' => __('Enter s3 url'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('s3_endpoint', __('S3 Endpoint'), ['class' => 'form-label']) }}
                                                        {!! Form::text('s3_endpoint', Utility::getsettings('s3_endpoint'), [
                                                            'placeholder' => __('Enter s3 endpoint'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="wasabi-setting {{ Utility::getsettings('storage_type') == 'wasabi' ? 'block' : 'd-none' }}">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('wasabi_key', __('Wasabi Key'), ['class' => 'form-label']) }}
                                                        {!! Form::text('wasabi_key', Utility::getsettings('wasabi_key'), [
                                                            'placeholder' => __('Enter wasabi key'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('wasabi_secret', __('Wasabi Secret'), ['class' => 'form-label']) }}
                                                        {!! Form::text('wasabi_secret', Utility::getsettings('wasabi_secret'), [
                                                            'placeholder' => __('Enter wasabi secret'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('wasabi_region', __('Wasabi Region'), ['class' => 'form-label']) }}
                                                        {!! Form::text('wasabi_region', Utility::getsettings('wasabi_region'), [
                                                            'placeholder' => __('Enter wasabi region'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('wasabi_bucket', __('Wasabi Bucket'), ['class' => 'form-label']) }}
                                                        {!! Form::text('wasabi_bucket', Utility::getsettings('wasabi_bucket'), [
                                                            'placeholder' => __('Enter wasabi bucket'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('wasabi_url', __('Wasabi URL'), ['class' => 'form-label']) }}
                                                        {!! Form::text('wasabi_url', Utility::getsettings('wasabi_url'), [
                                                            'placeholder' => __('Enter wasabi url'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        {{ Form::label('wasabi_root', __('Wasabi Endpoint'), ['class' => 'form-label']) }}
                                                        {!! Form::text('wasabi_root', Utility::getsettings('wasabi_root'), [
                                                            'placeholder' => __('Enter wasabi endpoint'),
                                                            'class' => 'form-control',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div id="email_setting">
                        <div class="card">
                            <div class="card-header">
                                {!! Form::open([
                                    'route' => 'settings.email.setting.update',
                                    'method' => 'Post',
                                    'data-validate',
                                ]) !!}
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h5> {{ __('Email Setting') }}</h5>
                                        <small
                                            class="text-muted">{{ __('Email Smtp Settings, Notifications And Others Related To Email.') }}</small>
                                    </div>
                                    <div class="col-lg-4 d-flex justify-content-end">
                                        <div class="form-switch custom-switch-v1 d-inline-block">
                                            {!! Form::checkbox(
                                                'email_setting_enable',
                                                null,
                                                Utility::getsettings('email_setting_enable') == 'on' ? true : false,
                                                [
                                                    'class' => 'custom-control custom-switch form-check-input input-primary',
                                                    'data-onstyle' => 'primary',
                                                    'data-toggle' => 'switchbutton',
                                                ],
                                            ) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('mail_mailer', __('Mail Mailer'), ['class' => 'form-label']) }}
                                            {!! Form::text('mail_mailer', Utility::getsettings('mail_mailer'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter mail mailer'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-label']) }}
                                            {!! Form::text('mail_host', Utility::getsettings('mail_host'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter mail host'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-label']) }}
                                            {!! Form::text('mail_port', Utility::getsettings('mail_port'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter mail port'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-label']) }}
                                            {!! Form::text('mail_username', Utility::getsettings('mail_username'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter mail username'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-label']) }}
                                            <input class="form-control"
                                                value="{{ Utility::getsettings('mail_password') }}"
                                                placeholder="{{ __('Enter mail password') }}" name="mail_password"
                                                type="password" id="mail_password">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label']) }}
                                            {!! Form::text('mail_encryption', Utility::getsettings('mail_encryption'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter mail encryption'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label']) }}
                                            {!! Form::text('mail_from_address', Utility::getsettings('mail_from_address'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter mail from address'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label']) }}
                                            {!! Form::text('mail_from_name', Utility::getsettings('mail_from_name'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter mail from name'),
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {!! Form::button(__('Send Test Mail'), [
                                        'class' => 'btn btn-info send_mail float-start',
                                        'data-url' => route('test.mail'),
                                        'id' => 'test-mail',
                                    ]) !!}
                                    {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div id="cookie_setting">
                        <div class="card">
                            <div class="card-header">
                                {!! Form::open([
                                    'route' => 'settings.cookie.setting.update',
                                    'method' => 'Post',
                                    'data-validate',
                                ]) !!}
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h5> {{ __('Cookie Setting') }}</h5>
                                    </div>
                                    <div class="col-lg-4 d-flex justify-content-end">
                                        <div class="form-switch custom-switch-v1 d-inline-block">
                                            {!! Form::checkbox(
                                                'cookie_setting_enable',
                                                null,
                                                Utility::getsettings('cookie_setting_enable') == 'on' ? true : false,
                                                [
                                                    'class' => 'custom-control custom-switch form-check-input input-primary',
                                                    'data-onstyle' => 'primary',
                                                    'data-toggle' => 'switchbutton',
                                                ],
                                            ) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                            <input type="checkbox" name="cookie_logging"
                                                class="form-check-input input-primary cookie_setting" id="cookie_logging"
                                                {{ Utility::getsettings('cookie_logging') == 'on' ? ' checked ' : '' }}>
                                            <label class="form-check-label" for="cookie_logging">
                                                {{ __('Enable logging') }}
                                            </label>
                                        </div>
                                        <small class="text">
                                            {{ __('Notes: After enabling logging, user cookie data will be stored in CSV file.') }}
                                        </small>
                                        <div class="form-group mt-2">
                                            {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'form-label']) }}
                                            {!! Form::text('cookie_title', Utility::getsettings('cookie_title'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter cookie title'),
                                            ]) !!}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('cookie_description', __('Cookie Description'), ['class' => 'form-label']) }}
                                            {!! Form::text('cookie_description', Utility::getsettings('cookie_description'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter cookie description'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch custom-switch-v1 my-2">
                                            <input type="checkbox" name="necessary_cookies"
                                                class="form-check-input input-primary cookie_setting"
                                                id="necessary_cookies"
                                                {{ Utility::getsettings('necessary_cookies') == 'on' ? ' checked ' : '' }}>
                                            <label class="form-check-label"
                                                for="necessary_cookies">{{ __('Strictly necessary cookies') }}</label>
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('strictly_cookie_title', __('Strictly Cookie Title'), ['class' => 'form-label']) }}
                                            {!! Form::text('strictly_cookie_title', Utility::getsettings('strictly_cookie_title'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter strictly cookie title'),
                                            ]) !!}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => 'form-label']) }}
                                            {!! Form::text('strictly_cookie_description', Utility::getsettings('strictly_cookie_description'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter strictly cookie description'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <h5> {{ __('More Information') }}</h5>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('contact_us_description', __('Contact Us Description'), ['class' => 'form-label']) }}
                                            {!! Form::text('contact_us_description', Utility::getsettings('contact_us_description'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter contact us description'),
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            {{ Form::label('contact_us_url', __('Contact Us Url'), ['class' => 'form-label']) }}
                                            {!! Form::text('contact_us_url', Utility::getsettings('contact_us_url'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter contact us url'),
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-6">
                                        @if (Utility::getsettings('cookie_logging') == 'on')
                                            @if (Storage::url('cookie-csv/cookie_data.csv'))
                                                <label for="file"
                                                    class="form-label">{{ __('Download cookie accepted data') }}</label>
                                                <a href="{{ Storage::url('cookie-csv/cookie_data.csv') }}"
                                                    class="btn btn-primary mr-3">
                                                    <i class="ti ti-download"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-6 text-end">
                                        <input class="btn btn-print-invoice btn-primary cookie_btn" type="submit"
                                            value="{{ __('Save') }}">
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div id="cache_setting">
                        <div class="card">
                            <div class="card-header">
                                {!! Form::open([
                                    'route' => 'config.cache',
                                    'method' => 'Post',
                                    'data-validate',
                                ]) !!}
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h5> {{ __('Cache Setting') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 form-group">
                                        {{ Form::label('Current cache size', __('Current cache size'), ['class' => 'col-form-label']) }}
                                        <div class="input-group">
                                            <input type="text" class="form-control"
                                                value="{{ Utility::CacheSize() }}" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">{{ __('MB') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {{ Form::button(__('Cache Clear'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div id="seo_setting">
                        <div class="card">
                            <div class="card-header">
                                {!! Form::open([
                                    'route' => 'setting.seo.save',
                                    'method' => 'Post',
                                    'enctype' => 'multipart/form-data',
                                    'data-validate',
                                ]) !!}
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h5> {{ __('SEO Setting') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('meta_title', __('Meta Title'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('meta_title', Utility::getsettings('meta_title'), ['class' => 'form-control ', 'required', 'placeholder' => 'Meta Title']) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('meta_keywords', __('Meta Keywords'), ['class' => 'col-form-label']) }}
                                            {{ Form::textarea('meta_keywords', Utility::getsettings('meta_keywords'), ['class' => 'form-control ', 'required', 'placeholder' => 'Meta Keywords', 'rows' => 2]) }}
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('meta_description', __('Meta Description'), ['class' => 'col-form-label']) }}
                                            {{ Form::textarea('meta_description', Utility::getsettings('meta_description'), ['class' => 'form-control ', 'required', 'placeholder' => 'Meta Description', 'rows' => 3]) }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label ms-4']) }}
                                            <div class="pt-0 card-body">
                                                <div class="setting_card">
                                                    <div class="logo-content">
                                                        <a href="{{ Utility::getsettings('meta_image_logo')
                                                        ? Utility::getpath(Utility::getsettings('meta_image_logo'))
                                                        : Storage::url('seeder-image/meta-image-logo.jpg') }}"
                                                            target="_blank">
                                                            <img id="meta-image-logo"
                                                                src="{{ Utility::getsettings('meta_image_logo')
                                                                ? Utility::getpath(Utility::getsettings('meta_image_logo'))
                                                                : Storage::url('seeder-image/meta-image-logo.jpg') }}">
                                                        </a>
                                                    </div>
                                                    <div class="mt-4 choose-files">
                                                        <label for="meta_image">
                                                            <div class="bg-primary logo input-img-div">
                                                                <i class="px-1 ti ti-upload"></i>
                                                                {{ __('Choose file here') }}
                                                                <input type="file"
                                                                    class="form-control file image-input"
                                                                    accept="image/png, image/gif, image/jpeg, image/jpg"
                                                                    id="meta_image" property="og:image"
                                                                    onchange="document.getElementById('meta-image-logo').src = window.URL.createObjectURL(this.files[0])"
                                                                    data-fileproperty="og:image">
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-end">
                                    {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <div id="notification_setting">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Notifications Setting ') }}</h5>
                                <small
                                    class="text-muted">{{ __('Here you can setup and manage your integration settings.') }}</small>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive mt-0">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Title') }}</th>
                                                <th class="w-auto text-end">{{ __('Email') }}</th>
                                                <th class="w-auto text-end">{{ __('Notification') }}</th>
                                            </tr>
                                        </thead>
                                        @foreach ($notificationsSettings as $notificationsSetting)
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div>
                                                            <span name="title" class="form-control"
                                                                placeholder="Enter title"
                                                                value="{{ $notificationsSetting->id }}">
                                                                {{ $notificationsSetting->title }}</span>
                                                        </div>
                                                    </td>
                                                    @if ($notificationsSetting->email_notification != 2)
                                                        <td class="text-end">
                                                            <div class="form-check form-switch d-inline-block">
                                                                {!! Form::checkbox('email_notification', null, $notificationsSetting->email_notification == 1 ? true : false, [
                                                                    'class' => 'form-check-input chnageEmailNotifyStatus',
                                                                    'data-url' => route('notification.status.change', $notificationsSetting->id),
                                                                ]) !!}
                                                            </div>
                                                        </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    <td class="text-end">
                                                        <div class="form-check form-switch d-inline-block">
                                                            {!! Form::checkbox('notify', null, $notificationsSetting->notify == 1 ? true : false, [
                                                                'class' => 'form-check-input chnageNotifyStatus',
                                                                'data-url' => route('notification.status.change', $notificationsSetting->id),
                                                            ]) !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">
@endpush
@push('javascript')
    <script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        });

        document.addEventListener('DOMContentLoaded', function() {
            var genericExamples = document.querySelectorAll('[data-trigger]');
            for (i = 0; i < genericExamples.length; ++i) {
                var element = genericExamples[i];
                new Choices(element, {
                    placeholderValue: 'This is a placeholder set in the config',
                    searchPlaceholderValue: 'Select Option',
                });
            }
        });

        function check_theme(color_val) {
            $('.theme-color').prop('checked', false);
            $('input[value="' + color_val + '"]').prop('checked', true);
        }

        // theme color
        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];
            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }

        // transparent card
        var custthemebg = document.querySelector("#cust-theme-bg");
        custthemebg.addEventListener("click", function() {
            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
        });

        // dark layout
        var custdarklayout = document.querySelector("#cust-darklayout");
        custdarklayout.addEventListener("click", function() {
            if (custdarklayout.checked) {
                document.querySelector(".m-headers > .b-brand > img").setAttribute("src",
                    "{{ Utility::getpath('logo/app-logo.png') }}");
                document.querySelector("#main-style-link").setAttribute("href",
                    "{{ asset('assets/css/style-dark.css') }}");
            } else {
                document.querySelector(".m-headers > .b-brand > img").setAttribute("src",
                    "{{ Utility::getpath('logo/app-dark-logo.png') }}");
                document.querySelector("#main-style-link").setAttribute("href",
                    "{{ asset('assets/css/style.css') }}");
            }
        });

        $(document).on('change', 'input[name="domain_config"]', function() {
            if ($(this).is(':checked')) {
                $('.main-domain').addClass('d-block');
                $('.main-domain').removeClass('d-none');
            } else {
                $('.main-domain').addClass('d-none');
                $('.main-domain').removeClass('d-block');
            }
        });

        $('body').on('click', '.send_mail', function() {
            var action = $(this).data('url');
            var modal = $('#common_modal');
            $.get(action, function(response) {
                modal.find('.modal-title').html('{{ __('Test Mail') }}');
                modal.find('.body').html(response);
                modal.modal('show');
            })
        });
        $(document).on('click', "input[name='storage_type']", function() {
            if ($(this).val() == 's3') {
                $('.s3-setting').removeClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').addClass('d-none');
            } else if ($(this).val() == 'wasabi') {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').removeClass('d-none');
                $('.local-setting').addClass('d-none');
            } else {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').removeClass('d-none');
            }
        });

        // change notification status
        $(document).on("change", ".chnageEmailNotifyStatus", function(e) {
            var csrf = $("meta[name=csrf-token]").attr("content");
            var email = $(this).parent().find("input[name=email_notification]").is(":checked");
            var action = $(this).attr("data-url");
            $.ajax({
                type: "POST",
                url: action,
                data: {
                    _token: csrf,
                    type: 'email',
                    email_notification: email,
                },
                success: function(response) {
                    if (response.warning) {
                        show_toastr("Warning!", response.warning, "warning");
                    }
                    if (response.is_success) {
                        show_toastr("Success!", response.message, "success");
                    }
                },
            });
        });
        $(document).on("change", ".chnagesmsNotifyStatus", function(e) {
            var csrf = $("meta[name=csrf-token]").attr("content");
            var sms = $(this).parent().find("input[name=sms_notification]").is(":checked");
            var action = $(this).attr("data-url");
            $.ajax({
                type: "POST",
                url: action,
                data: {
                    _token: csrf,
                    type: 'sms',
                    sms_notification: sms,
                },
                success: function(response) {
                    if (response.warning) {
                        show_toastr("Warning!", response.warning, "warning");
                    }
                    if (response.is_success) {
                        show_toastr("Success!", response.message, "success");
                    }
                },
            });
        });
        $(document).on("change", ".chnageNotifyStatus", function(e) {
            var csrf = $("meta[name=csrf-token]").attr("content");
            var notify = $(this).parent().find("input[name=notify]").is(":checked");
            var action = $(this).attr("data-url");
            $.ajax({
                type: "POST",
                url: action,
                data: {
                    _token: csrf,
                    type: 'notify',
                    notify: notify,
                },
                success: function(response) {
                    if (response.warning) {
                        show_toastr("Warning!", response.warning, "warning");
                    }
                    if (response.is_success) {
                        show_toastr("Success!", response.message, "success");
                    }
                },
            });
        });
    </script>
@endpush
