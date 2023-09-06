@extends('layouts.admin.app')

@section('title')
    {{ $group->name ?? 'Show Group' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Group</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('groups.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $group->name }}
                        </div>
                        <div class="form-group">
                            <strong>Region:</strong>
                            {{ $home->headline }}
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
