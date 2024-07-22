@extends('layouts.main')
@section('title', __('Search Hotels'))
@section('content')
    <div class="row ">
        <livewire:hotel-search/>
    </div>
@endsection
@push('css')
@endpush
@push('javascript')

@endpush
