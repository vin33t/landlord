@extends('layouts.main')
@section('title', __('Edit Footer Sub Menu'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('landing.footer.index') }}">{{ __('Footer Setting') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Footer Sub Menu') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Edit Footer Sub Menu') }}</h5>
                        </div>
                        <div class="card-body">
                            {!! Form::open([
                                'route' => ['footer.sub.menu.update', $footerPage->id],
                                'method' => 'Post',
                                'class' => 'form-horizontal',
                                'data-validate',
                            ]) !!}
                            <div class="row">
                                <div class="form-group">
                                    {{ Form::label('page_id', __('Select Page'), ['class' => 'form-label']) }}
                                    {!! Form::select('page_id', $pages, $footerPage->page_id, [
                                        'class' => 'form-select',
                                        'required',
                                        'data-trigger',
                                    ]) !!}
                                </div>
                                <div class="form-group">
                                    {{ Form::label('parent_id', __('Menu'), ['class' => 'form-label']) }}
                                    {!! Form::select('parent_id', $footer, $footerPage->parent_id, [
                                        'class' => 'form-select',
                                        'required',
                                        'data-trigger',
                                    ]) !!}
                                </div>
                                <div class="card-footer">
                                    <div class="text-end">
                                        <a href="{{ route('landing.footer.index') }}"><button type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">{{ __('Close') }}</button></a>
                                        {{ Form::button(__('Save'), ['type' => 'submit', 'id' => 'save-btn', 'class' => 'btn btn-primary']) }}
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var genericExamples = document.querySelectorAll('[data-trigger]');
            for (i = 0; i < genericExamples.length; ++i) {
                var element = genericExamples[i];
                new Choices(element, {
                    placeholderValue: 'This is a placeholder set in the config',
                    searchPlaceholderValue: 'This is a search placeholder',
                });
            }
        });
    </script>
@endpush
