<div class="card-body">
    <div class="row">

        <div class="form-group col-md-4 col-sm-4">
            {{ Form::label('Name') }}
            {{ Form::text('name', $group->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>

        <div class="form-group col-md-4 col-sm-4">
            {{ Form::label('Admin Footer Text') }}
            {{ Form::text('footer', $group->footer, ['class' => 'form-control' . ($errors->has('footer') ? ' is-invalid' : ''), 'placeholder' => 'Footer Text']) }}
            {!! $errors->first('footer', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-4 col-sm-4">
            <label>Status</label>
            <select name="status" required="required" class="form-control select2">
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
        <div class="form-group col-md-4 col-sm-4">
            {{ Form::label('Homes') }}
            {{ Form::select('homes[]', $homes, $group->homes, ['class' => 'form-control select2 form-select' . ($errors->has('homes') ? ' is-invalid' : ''), 'required' , 'multiple']) }}
            {!! $errors->first('regions', '<p class="invalid-feedback">:message</p>') !!}
        </div>

        <div class=" form-group col-md-4 col-sm-4">
            {{ Form::label('Title Color') }}
            {{ Form::color('title_color', $group->title_color, ['class' => 'form-control' . ($errors->has('title_color') ? ' is-invalid' : ''), 'placeholder' => 'title color']) }}
            {!! $errors->first('title_color', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class=" form-group col-md-4 col-sm-4">
            {{ Form::label('Text Color') }}
            {{ Form::color('text_color', $group->text_color, ['class' => 'form-control' . ($errors->has('text_color') ? ' is-invalid' : ''), 'placeholder' => 'text color']) }}
            {!! $errors->first('text_color', '<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>

    <div class="row">
        <div class=" form-group col-md-4 col-sm-4">
            <label>Background Option</label>
            <select name="background_property" class="form-control select2">
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
                <option value="Color" {{ $group->background_property == 'Color' ? 'selected' : '' }}>
                    Color Only
                </option>
            </select>
        </div>
        <div id="background_color" class=" form-group col-md-4 col-sm-4 d-none">
            {{ Form::label('Background Color') }}
            {{ Form::color('background_color', $group->background_color, ['class' => 'form-control' . ($errors->has('background_color') ? ' is-invalid' : ''), 'placeholder' => 'background color']) }}
            {!! $errors->first('background_color', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div  class="col-4 col-md-4">
            <label for="background_image">Upload Background Image</label>
            <input type="file" id="background_image" name="background_image" accept="image/*" class="dropify"
                   data-default-file="{{ $group->background_image ?? '' }}"/>
        </div>


    </div>
    <div class="row">

        <div class="form-group col-md-4 col-sm-4">
            <label>OverLay Status</label>
            <select name="active_overlay" required="required" class="form-control select2">
                <option value="">Select Option</option>
                <option value="Active" {{ $group->active_overlay == 'Active' ? 'selected' : '' }}>Active
                </option>
                <option value="InActive" {{ $group->active_overlay == 'InActive' ? 'selected' : '' }}>
                    InActive
                </option>
            </select>
        </div>
        <div class=" form-group col-md-4 col-sm-4">
            <label for="logo">Upload Overlay</label>
            <input type="file" id="overlay" name="overlay" accept="image/*" class="dropify"
                   data-default-file="{{ $group->overlay ?? '' }}"/>
        </div>
        <div class="col-4 col-md-4">
            <label for="logo">Upload Logo</label>
            <input type="file" id="logo" name="logo" accept="image/*" class="dropify"
                   data-default-file="{{ $group->logo ?? '' }}"/>
        </div>
    </div>
    <div class="row">
        <div class="col-4 col-md-4">
            <label for="favicon">Upload Favicon</label>
            <input type="file" id="favicon" name="favicon" accept="image/*" class="dropify"
                   data-default-file="{{ $group->favicon ?? '' }}"/>
        </div>
        <div class="col-4 col-md-4">
            <label for="background">Upload Footer Logo</label>
            <input type="file" id="footer_logo" name="footer_logo" accept="image/*" class="dropify"
                   data-default-file="{{ $group->footer_logo ?? '' }}"/>
        </div>
        <div class="col-4 col-md-4">
            <label for="favicon">Upload Footer Border</label>
            <input type="file" id="footer_border" name="footer_border" accept="image/*" class="dropify"
                   data-default-file="{{ $group->footer_border ?? '' }}"/>
        </div>
    </div>

</div>
@section('page-script')
    <script>

        $(document).ready(function(){
            $('select[name="background_property"]').on('change', function(){
                if($(this).val() === 'Color'){
                    $('#background_color').removeClass('d-none');
                    $('#background_image_div').addClass('d-none');
                } else {
                    $('#background_color').addClass('d-none');
                    $('#background_image_div').removeClass('d-none');
                }
            });
        });
    </script>

@endsection
