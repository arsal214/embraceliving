@extends('layouts.app')

@section('template_title')
    {{ $theme->name ?? "{{ __('Show') Theme" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Theme</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('themes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Group Id:</strong>
                            {{ $theme->group_id }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $theme->name }}
                        </div>
                        <div class="form-group">
                            <strong>Logo:</strong>
                            {{ $theme->logo }}
                        </div>
                        <div class="form-group">
                            <strong>Background Image:</strong>
                            {{ $theme->background_image }}
                        </div>
                        <div class="form-group">
                            <strong>Background Property:</strong>
                            {{ $theme->background_property }}
                        </div>
                        <div class="form-group">
                            <strong>Background Color:</strong>
                            {{ $theme->background_color }}
                        </div>
                        <div class="form-group">
                            <strong>Overlay:</strong>
                            {{ $theme->overlay }}
                        </div>
                        <div class="form-group">
                            <strong>Active Overlay:</strong>
                            {{ $theme->active_overlay }}
                        </div>
                        <div class="form-group">
                            <strong>Footer Logo:</strong>
                            {{ $theme->footer_logo }}
                        </div>
                        <div class="form-group">
                            <strong>Footer Border:</strong>
                            {{ $theme->footer_border }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $theme->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
