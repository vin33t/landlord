@extends('layouts.main')
@section('title', __('Create Agency'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Admins') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Admin') }}</li>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            @if (tenant('id') == null)
                @if ($databasePermission == null)
                    <div class="alert alert-warning">
                        {{ __('Please on your database permission to create auto generate DATABASE.') }}<a
                            href="{{ route('settings') }}" target="_blank">{{ __('On database permission') }}</a>
                    </div>
                @else
                    <div class="alert alert-warning">
                        {{ __('Please off your database permission to create your own DATABASE.') }}<a
                            href="{{ route('settings') }}" target="_blank">{{ __('Off database permission') }}</a>
                    </div>
                @endif
            @endif
            <div class="col-sm-12 col-md-8 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Create Admin') }}</h5>
                    </div>
                    <div class="card-body">
                        {!! Form::open([
                            'route' => 'users.store',
                            'method' => 'Post',
                            'data-validate',
                        ]) !!}
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    {{ Form::label('name', __('Agency Name'), ['class' => 'form-label']) }}
                                    {!! Form::text('name', null, ['class' => 'form-control', ' required', 'placeholder' => __('Agency name')]) !!}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('contact_person_name', __('Contact Person Name'), ['class' => 'form-label']) }}
                                    {!! Form::text('contact_person_name', null, ['class' => 'form-control', ' required', 'placeholder' => __('Contact Person Name')]) !!}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                                    {!! Form::text('email', null, [
                                        'class' => 'form-control',
                                        ' required',
                                        'placeholder' => __('Enter email address'),
                                    ]) !!}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('phone', __('Phone'), ['class' => 'form-label']) }}
                                    <input id="phone" name="phone" type="tel" class="form-control"
                                        placeholder="{{ __('Enter phone') }}" required>
                                    {!! Form::hidden('country_code', null, []) !!}
                                    {!! Form::hidden('dial_code', null, []) !!}
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="row ">
                                <div class="form-group col-md-6">
                                    {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
                                    {!! Form::password('password', ['class' => 'form-control', ' required', 'placeholder' => __('Enter password')]) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('confirm-password', __('Confirm Password'), ['class' => 'form-label']) }}
                                    {{ Form::password('confirm-password', ['class' => 'form-control', ' required', 'placeholder' => __('Enter confirm password')]) }}
                                </div>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
                                    <input id="address" name="address" type="text" class="form-control"
                                           placeholder="{{ __('Enter Address') }}" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="row">

                                <div class="form-group col-md-8">
                                    {{ Form::label('zip', __('Zip'), ['class' => 'form-label']) }}
                                    <input id="zip" name="zip_code" type="text" class="form-control"
                                           placeholder="{{ __('Zip') }}" required>
                                </div>
                                        <div class="col-md-4 align-items-center">
                                            <button type="button" class="btn btn-primary btn-sm" id="checkPostcodeButton"><i class="fa fa-upload"></i></button>
                                        </div>
                                    </div>
                                    </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
                                    <input id="city" name="city" type="text" class="form-control"
                                           placeholder="{{ __('City') }}" required>
                                </div>
                                </div>
                                <div class="row">
                                <div class="form-group col-md-6">
                                    {{ Form::label('county', __('County'), ['class' => 'form-label']) }}
                                    <input id="county" name="county" type="text" class="form-control"
                                           placeholder="{{ __('County') }}" required>
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}
                                    <input id="Country" name="country" type="text" class="form-control"
                                           placeholder="{{ __('Country') }}" required>
                                </div>
                                </div>

                            </div>
                        </div>
                        <div class="alert alert-info">
                            <ul>
                                <li>{{ __('If you want to run your website in localhost then it is necessary to be a vhost,
                                                                    because of
                                                                    tenancy-based software it is necessary to create a vhost.') }}
                                </li>
                                <li class="text-danger">
                                    {{ __('If you give incorrect website host,then 404 error will be
                                                                        shown throughout the
                                                                        whole
                                                                        website') }}
                                </li>
                                <li> {{ __('if your website URL is') }} <span
                                        class="text-danger">{{ __('https://example.com/') }}</span>
                                    {{ __(',then host will be') }}
                                    <span class="text-danger">{{ __('example.com') }}</span>
                                </li>
                                <li> {{ __('if your website URL is') }} <span
                                        class="text-danger">{{ __('https://subdomain.example.com/') }}</span>
                                    {{ __(',then host
                                                                            will
                                                                            be') }}
                                    <span class="text-danger">{{ __('subdomain.example.com') }}</span>
                                </li>
                            </ul>
                        </div>
                        <h5 class="mt-5">{{ __('Create Domain and Database') }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    {{ Form::label('domains', __('Domain Configration'), ['class' => 'form-label']) }}
                                    @if (Utility::getsettings('domain_config') == 'on')
                                        <div class="input-group">
                                            {!! Form::text('domains', null, [
                                                'class' => 'form-control',
                                                'id' => 'domains',
                                                'required',
                                                'placeholder' => __('Enter domain name'),
                                            ]) !!}
                                            <span
                                                class="input-group-text">{{ '.' . parse_url(env('APP_URL'), PHP_URL_HOST) }}</span>
                                        </div>
                                    @else
                                        {!! Form::text('domains', null, [
                                            'class' => 'form-control',
                                            'required',
                                            'id' => 'domains',
                                            'placeholder' => __('Enter domain name'),
                                        ]) !!}
                                    @endif
                                    <div class="error-message" id="bouncer-error_domains"></div>
                                    <span>
                                        {{ __('Note: how to add-on domain in your hosting panel.') }}
                                        <a href="https://demo.quebixtechnology.com/document/full-tenancy/" class="m-2"
                                            target="_blank">
                                            {{ __('Document') }}
                                        </a>
                                    </span>
                                </div>
                                @if ($databasePermission == null)
                                    <div class="form-group">
                                        {{ Form::label('db_name', __('Database Name'), ['class' => 'form-label']) }}
                                        {!! Form::text('db_name', null, [
                                            'class' => 'form-control',
                                            ' required',
                                            'placeholder' => __('Enter database name'),
                                        ]) !!}
                                    </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    {{ Form::label('db_username', __('Database User'), ['class' => 'form-label']) }}
                                    {!! Form::text('db_username', null, [
                                        'class' => 'form-control',
                                        ' required',
                                        'placeholder' => __('Enter database username'),
                                    ]) !!}
                                </div>
                                <div class="form-group ">
                                    {{ Form::label('db_password', __('Database Password'), ['class' => 'form-label']) }}
                                    <div class="input-group-prepend">
                                    </div>
                                    {!! Form::password('db_password', [
                                        'class' => 'form-control',
                                        ' required',
                                        'placeholder' => __('Enter database password'),
                                    ]) !!}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                            {{ Form::button(__('Save'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/intl-tel-input/intlTelInput.min.css') }}">
@endpush
@push('javascript')
    <script src="{{ asset('vendor/intl-tel-input/jquery.mask.js') }}"></script>
    <script src="{{ asset('vendor/intl-tel-input/intlTelInput-jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/intl-tel-input/utils.min.js') }}"></script>
    <script>
        $("#phone").intlTelInput({
            geoIpLookup: function(callback) {
                $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            initialCountry: "auto",
            separateDialCode: true,
        });
        $('#phone').on('countrychange', function(e) {
            $(this).val('');
            var selectedCountry = $(this).intlTelInput('getSelectedCountryData');
            var dialCode = selectedCountry.dialCode;
            var maskNumber = intlTelInputUtils.getExampleNumber(selectedCountry.iso2, 0, 0);
            maskNumber = intlTelInputUtils.formatNumber(maskNumber, selectedCountry.iso2, 2);
            maskNumber = maskNumber.replace('+' + dialCode + ' ', '');
            mask = maskNumber.replace(/[0-9+]/ig, '0');
            $('input[name="country_code"]').val(selectedCountry.iso2);
            $('input[name="dial_code"]').val(dialCode);
            $('#phone').mask(mask, {
                placeholder: maskNumber
            });
        });
        $('#checkPostcodeButton').on('click', function() {
            var postcode = $('#zip').val();
            $.ajax({
                url: `https://api.postcodes.io/postcodes?q=${postcode}`,
                method: 'GET',
                success: function(data) {
                    if (data.result && data.result.length > 0) {
                        var result = data.result[0];
                        $('#city').val(result.admin_ward);
                        $('#country').val(result.country);
                        $('#county').val(result.admin_county);
                    } else {
                        console.error('No results found');
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    </script>

@endpush
