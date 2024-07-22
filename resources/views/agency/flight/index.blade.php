@extends('layouts.main')
@section('title', __('Flights'))
@section('content')
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <livewire:flight-search-form />
                </div>
            </div>
        </div>

@endsection
@push('css')
@endpush
@push('javascript')

@endpush
