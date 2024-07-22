@extends('layouts.main')
@section('title', __('Clients'))
@section('content')
    <div class="row ">
<livewire:client-form />
    </div>
@endsection
@push('css')
@endpush
@push('javascript')

@endpush
