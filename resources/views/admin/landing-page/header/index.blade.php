@extends('layouts.main')
@section('title', __('Header Setting'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Header Setting') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            @include('superadmin.landing-page.landingpage-sidebar')
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <h5>{{ __('Header Sub Menu') }}</h5>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                    <a href="{{ route('header.sub.menu.create') }}" data-ajax-popup="true"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        class="btn btn-sm btn-primary mx-1" data-bs-original-title="{{ __('Create') }}">
                                        <i class="ti ti-plus text-light"></i>
                                    </a>
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
@endpush
