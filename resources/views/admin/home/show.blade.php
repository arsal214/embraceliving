@extends('layouts.admin.app')

@section('title')
    {{ $home->name ?? 'Show Home' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Home</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('homes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $home->title }}
                        </div>
                        <div class="form-group">
                            <strong>Region:</strong>
                            {{ $home->region }}
                        </div>
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $home->code }}
                        </div>
                        <div class="form-group">
                            <strong>Identifier:</strong>
                            {{ $home->identifier }}
                        </div>
                        <div class="form-group">
                            <strong>Heygo Token:</strong>
                            {{ $home->heygo_token }}
                        </div>
                        <div class="form-group">
                            <strong>Template Link:</strong>
                            {{ $home->template_link }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
