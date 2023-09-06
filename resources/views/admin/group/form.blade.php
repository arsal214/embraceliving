<div class="card-body">
    <div class="row">

        <div class="form-group col-md-4 col-sm-4">
            {{ Form::label('Name') }}
            {{ Form::text('name', $group->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>

        <div class="form-group col-md-4 col-sm-4">
            {{ Form::label('Footer Text') }}
            {{ Form::text('footer', $group->footer, ['class' => 'form-control' . ($errors->has('footer') ? ' is-invalid' : ''), 'placeholder' => 'Footer Text']) }}
            {!! $errors->first('footer', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="row">
        <div class=" form-group col-md-3 col-sm-3">
            {{ Form::label('Title Color') }}
            {{ Form::color('title_color', $group->title_color, ['class' => 'form-control' . ($errors->has('title_color') ? ' is-invalid' : ''), 'placeholder' => 'title color']) }}
            {!! $errors->first('title_color', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class=" form-group col-md-3 col-sm-3">
            {{ Form::label('Background Color') }}
            {{ Form::color('background_color', $group->background_color, ['class' => 'form-control' . ($errors->has('background_color') ? ' is-invalid' : ''), 'placeholder' => 'background color']) }}
            {!! $errors->first('background_color', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class=" form-group col-md-3 col-sm-3">
            {{ Form::label('Text Color') }}
            {{ Form::color('text_color', $group->text_color, ['class' => 'form-control' . ($errors->has('text_color') ? ' is-invalid' : ''), 'placeholder' => 'text color']) }}
            {!! $errors->first('text_color', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-3 col-sm-3">
            <label>Status</label>
            <select name="status" class="form-control select2">
                <option value="">Select Option</option>
                <option value="Active" {{ $group->status == 'Active' ? 'selected' : '' }}>Active
                </option>
                <option value="InActive" {{ $group->status == 'InActive' ? 'selected' : '' }}>
                    InActive
                </option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class=" form-group col-md-4 col-sm-4">
            <label>Background Option</label>
            <select name="status" class="form-control select2">
                <option value="">Select Option</option>
                <option value="RepeatX" {{ $group->background_property == 'RepeatX' ? 'selected' : '' }}>Repeat X
                </option>
                <option value="Repeat" {{ $group->background_property == 'Repeat' ? 'selected' : '' }}>
                    Repeat
                </option>
                <option value="NoRepeat" {{ $group->background_property == 'NoRepeat' ? 'selected' : '' }}>
                    No Repeat
                </option>
                <option value="Cover" {{ $group->background_property == 'Cover' ? 'selected' : '' }}>
                    Size (Cover)
                </option>
                <option value="Contain" {{ $group->background_property == 'Contain' ? 'selected' : '' }}>
                    Size (Contain)
                </option>
                <option value="100%" {{ $group->background_property == '100%' ? 'selected' : '' }}>
                    Size (100% 100%)
                </option>
                <option value="100%" {{ $group->background_property == '100%' ? 'selected' : '' }}>
                    Size (100% 100%)
                </option>
            </select>
        </div>
        <div class=" form-group col-md-4 col-sm-4">
            <label for="logo">Upload Overlay</label>
            <input type="file" id="overlay" name="overlay" accept="image/*" class="dropify"
                   data-default-file="{{ $group->overlay ?? '' }}"/>        </div>
        <div class="form-group col-md-4 col-sm-4">
            <label>OverLay Status</label>
            <select name="status" class="form-control select2">
                <option value="">Select Option</option>
                <option value="Active" {{ $group->active_overlay == 'Active' ? 'selected' : '' }}>Active
                </option>
                <option value="InActive" {{ $group->active_overlay == 'InActive' ? 'selected' : '' }}>
                    InActive
                </option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('description') }}
            {{ Form::textarea('description', $group->description, ['class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''), 'placeholder' => 'description']) }}
            {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="col-6 col-md-6">
            <label for="logo">Upload Logo</label>
            <input type="file" id="logo" name="logo" accept="image/*" class="dropify"
                   data-default-file="{{ $group->logo ?? '' }}"/>
        </div>
    </div>
    <div class="row">

        <div class="col-6 col-md-6">
            <label for="background">Upload Background</label>
            <input type="file" id="background" name="background" accept="image/*" class="dropify"
                   data-default-file="{{ $group->background ?? '' }}"/>
        </div>
        <div class="col-6 col-md-6">
            <label for="favicon">Upload Favicon</label>
            <input type="file" id="favicon" name="favicon" accept="image/*" class="dropify"
                   data-default-file="{{ $group->favicon ?? '' }}"/>
        </div>
    </div>

</div>
