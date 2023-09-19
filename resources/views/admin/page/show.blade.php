@extends('layouts.app')

@section('template_title')
    {{ $page->name ?? "{{ __('Show') Page" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Page</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('pages.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Group Id:</strong>
                            {{ $page->group_id }}
                        </div>
                        <div class="form-group">
                            <strong>Theme Id:</strong>
                            {{ $page->theme_id }}
                        </div>
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $page->title }}
                        </div>
                        <div class="form-group">
                            <strong>Slug:</strong>
                            {{ $page->slug }}
                        </div>
                        <div class="form-group">
                            <strong>Top Line:</strong>
                            {{ $page->top_line }}
                        </div>
                        <div class="form-group">
                            <strong>Bottom Line:</strong>
                            {{ $page->bottom_line }}
                        </div>
                        <div class="form-group">
                            <strong>Page Type:</strong>
                            {{ $page->page_type }}
                        </div>
                        <div class="form-group">
                            <strong>Reference:</strong>
                            {{ $page->reference }}
                        </div>
                        <div class="form-group">
                            <strong>Redirect Type:</strong>
                            {{ $page->redirect_type }}
                        </div>
                        <div class="form-group">
                            <strong>Box Color:</strong>
                            {{ $page->box_color }}
                        </div>
                        <div class="form-group">
                            <strong>Line Color:</strong>
                            {{ $page->line_color }}
                        </div>
                        <div class="form-group">
                            <strong>Border Type:</strong>
                            {{ $page->border_type }}
                        </div>
                        <div class="form-group">
                            <strong>Attachment Id:</strong>
                            {{ $page->attachment_id }}
                        </div>
                        <div class="form-group">
                            <strong>Is Default:</strong>
                            {{ $page->is_default }}
                        </div>
                        <div class="form-group">
                            <strong>Is Monitor:</strong>
                            {{ $page->is_monitor }}
                        </div>
                        <div class="form-group">
                            <strong>Script:</strong>
                            {{ $page->script }}
                        </div>
                        <div class="form-group">
                            <strong>Page Icon:</strong>
                            {{ $page->page_icon }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
