@extends('layouts.admin.app')

@section('title')
    {{ __('Create') }} Page
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first('msg') }}
                </div>
            @endif
            <form method="post" action="{{route('pages.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="container-fluid">
                    <div class="content-header">
                        <div class="row">
                            <div class="col-md-7 col-sm-12">
                                <h1 class="m-0">Create Page </h1>
                            </div>
                            <div class="col-md-5 col-sm-12 text-right">
                                <button type="submit" class="save-btn" id="user"><i
                                        class="fas fa-external-link-square-alt"> </i> <br>
                                    Submit
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="page-breadcrumb breadcrumb">
                                    <li class="breadcrumb-item"><i class="fas fa-home"></i> <a href="/admin/Dashboard">Home</a>
                                        <i
                                            class="fas fa-angle-right"></i></li>
                                    <li class="breadcrumb-item"><a href="{{route('pages.index')}}">Pages</a> <i
                                            class="fas fa-angle-right"></i></li>
                                    <li class="breadcrumb-item active">Create Page</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="content-body">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Create Pages</h3>
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        {{ Form::label('group') }}
                                        {{ Form::select('group_id', $groups, $page->group_id, ['class' => 'form-control select2 form-select' . ($errors->has('group_id') ? ' is-invalid' : '')]) }}
                                        {!! $errors->first('group_id', '<p class="invalid-feedback">:message</p>') !!}
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('theme') }}
                                        {{ Form::select('theme_id', $themes, $page->theme_id, ['class' => 'form-control select2 form-select' . ($errors->has('theme_id') ? ' is-invalid' : '')]) }}
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('title') }}
                                        {{ Form::text('title', $page->title, ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'placeholder' => 'Title']) }}
                                        {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        {{ Form::label('top_line') }}
                                        {{ Form::text('top_line', $page->top_line, ['class' => 'form-control' . ($errors->has('top_line') ? ' is-invalid' : ''), 'placeholder' => 'Top Line']) }}
                                        {!! $errors->first('top_line', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('bottom_line') }}
                                        {{ Form::text('bottom_line', $page->bottom_line, ['class' => 'form-control' . ($errors->has('bottom_line') ? ' is-invalid' : ''), 'placeholder' => 'Bottom Line']) }}
                                        {!! $errors->first('bottom_line', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Page type</label>
                                        <select name="page_type" required="required" class="form-control select2">
                                            <option value="default" {{ $page->page_type == 'default' ? 'selected' : '' }}>default
                                            </option>
                                            <option value="iFrame" {{ $page->page_type == 'iFrame' ? 'selected' : '' }}>
                                                iFrame
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        {{ Form::label('title_color') }}
                                        {{ Form::color('title_color', $page->title_color, ['class' => 'form-control' . ($errors->has('title_color') ? ' is-invalid' : ''), 'placeholder' => 'Box Color']) }}
                                        {!! $errors->first('title_color', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    <div class="form-group col-md-4">
                                        {{ Form::label('text_color') }}
                                        {{ Form::color('text_color', $page->text_color, ['class' => 'form-control' . ($errors->has('text_color') ? ' is-invalid' : ''), 'placeholder' => 'Line Color']) }}
                                        {!! $errors->first('text_color', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        {{ Form::label('is_default') }}
                                        {{ Form::checkbox('is_default', $page->is_default, ['class' => 'form-control' . ($errors->has('is_default') ? ' is-invalid' : ''), 'placeholder' => 'Is Default']) }}
                                        {!! $errors->first('is_default', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('is_monitor') }}
                                        {{ Form::checkbox('is_monitor', $page->is_monitor, ['class' => 'form-control' . ($errors->has('is_monitor') ? ' is-invalid' : ''), 'placeholder' => 'Is Monitor']) }}
                                        {!! $errors->first('is_monitor', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        {{ Form::label('Mind') }}
                                        {{ Form::checkbox('border_type[]', $page->is_monitor, ['class' => 'form-control' . ($errors->has('border_type') ? ' is-invalid' : ''), 'placeholder' => 'Is Monitor']) }}
                                        {!! $errors->first('border_type', '<div class="invalid-feedback">:message</div>') !!}
                                        {{ Form::label('body') }}
                                        {{ Form::checkbox('border_type[]', $page->is_monitor, ['class' => 'form-control' . ($errors->has('is_monitor') ? ' is-invalid' : ''), 'placeholder' => 'Is Monitor']) }}
                                        {!! $errors->first('border_type', '<div class="invalid-feedback">:message</div>') !!}
                                        {{ Form::label('soul') }}
                                        {{ Form::checkbox('border_type[]', $page->is_monitor, ['class' => 'form-control' . ($errors->has('is_monitor') ? ' is-invalid' : ''), 'placeholder' => 'Is Monitor']) }}
                                        {!! $errors->first('border_type', '<div class="invalid-feedback">:message</div>') !!}

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Status</label>
                                        <select name="status" required="required" class="form-control select2">
                                            <option value="">Select Option</option>
                                            <option value="Active" {{ $page->status == 'Active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="InActive" {{ $page->status == 'InActive' ? 'selected' : '' }}>
                                                InActive
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="page_icon">Upload page_icon</label>
                                        <input type="file" id="page_icon" name="page_icon" accept="image/*" class="dropify"
                                               data-default-file="{{ $page->page_icon ?? '' }}"/>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group d-none"  id="script">
                                        {{ Form::label('script') }}
                                        {{ Form::textarea('script', $page->script, ['class' => 'form-control' . ($errors->has('script') ? ' is-invalid' : ''), 'placeholder' => 'Script']) }}
                                        {!! $errors->first('script', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
@section('page-script')
    <script>
        $(document).ready(function(){
            // Function to show/hide #groups element based on select box value
            function handleTypeChange() {
                if ($('select[name="page_type"]').val() === 'GroupAdmin') {
                    $('#script').removeClass('d-none');
                } else {
                    $('#script').addClass('d-none');
                }
            }

            // Attach the event handler to the select box change event
            $('select[name="page_type"]').on('change', handleTypeChange);

            // Trigger the event on page load to handle the default value
            handleTypeChange();
        });
    </script>

@endsection
