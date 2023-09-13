<div class="card-body">
    <div class="row">
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('Name') }}
            {{ Form::text('name', $theme->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label>Status</label>
            <select name="status" class="form-control select2">
                <option value="">Select Option</option>
                <option value="Active" {{ $theme->status == 'Active' ? 'selected' : '' }}>Active
                </option>
                <option value="InActive" {{ $theme->status == 'InActive' ? 'selected' : '' }}>
                    InActive
                </option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class=" form-group col-md-4 col-sm-4">
            <label>Background Option</label>
            <select name="background_property" class="form-control select2">
                <option value="">Select Option</option>
                <option value="RepeatX" {{ $theme->background_property == 'RepeatX' ? 'selected' : '' }}>Repeat X
                </option>
                <option value="Repeat" {{ $theme->background_property == 'Repeat' ? 'selected' : '' }}>
                    Repeat
                </option>
                <option value="NoRepeat" {{ $theme->background_property == 'NoRepeat' ? 'selected' : '' }}>
                    No Repeat
                </option>
                <option value="Cover" {{ $theme->background_property == 'Cover' ? 'selected' : '' }}>
                    Size (Cover)
                </option>
                <option value="Contain" {{ $theme->background_property == 'Contain' ? 'selected' : '' }}>
                    Size (Contain)
                </option>
                <option value="100%" {{ $theme->background_property == '100%' ? 'selected' : '' }}>
                    Size (100% 100%)
                </option>
                <option value="Color" {{ $theme->background_property == 'Color' ? 'selected' : '' }}>
                    Color Only
                </option>
            </select>
        </div>
        <div id="background_color" class=" form-group col-md-4 col-sm-4 d-none">
            {{ Form::label('Background Color') }}
            {{ Form::color('background_color', $theme->background_color, ['class' => 'form-control' . ($errors->has('background_color') ? ' is-invalid' : ''), 'placeholder' => 'background color']) }}
            {!! $errors->first('background_color', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div  class="col-4 col-md-4" id="background_image_div">
            <label for="background_image">Upload Background Image</label>
            <input type="file" id="background_image" name="background_image" accept="image/*" class="dropify"
                   data-default-file="{{ $theme->background_image ?? '' }}"/>
        </div>


    </div>
    <div class="row">

        <div class="form-group col-md-4 col-sm-4">
            <label>OverLay Status</label>
            <select name="active_overlay" class="form-control select2">
                <option value="">Select Option</option>
                <option value="Active" {{ $theme->active_overlay == 'Active' ? 'selected' : '' }}>Active
                </option>
                <option value="InActive" {{ $theme->active_overlay == 'InActive' ? 'selected' : '' }}>
                    InActive
                </option>
            </select>
        </div>
        <div class=" form-group col-md-4 col-sm-4">
            <label for="logo">Upload Overlay</label>
            <input type="file" id="overlay" name="overlay" accept="image/*" class="dropify"
                   data-default-file="{{ $theme->overlay ?? '' }}"/>
        </div>
        <div class="col-4 col-md-4">
            <label for="logo">Upload Logo</label>
            <input type="file" id="logo" name="logo" accept="image/*" class="dropify"
                   data-default-file="{{ $theme->logo ?? '' }}"/>
        </div>
    </div>
    <div class="row">
        <div class="col-4 col-md-4">
            <label for="background">Upload Footer Logo</label>
            <input type="file" id="footer_logo" name="footer_logo" accept="image/*" class="dropify"
                   data-default-file="{{ $theme->footer_logo ?? '' }}"/>
        </div>
        <div class="col-4 col-md-4">
            <label for="favicon">Upload Footer Border</label>
            <input type="file" id="footer_border" name="footer_border" accept="image/*" class="dropify"
                   data-default-file="{{ $theme->footer_border ?? '' }}"/>
        </div>
    </div>

</div>
@section('page-script')
    <script>

        $(document).ready(function(){
            var backgroundPropertySelect = $('select[name="background_property"]');
            var backgroundColorDiv = $('#background_color');
            var backgroundImageDiv = $('#background_image_div');

            if (backgroundPropertySelect.val() === 'Color') {
                backgroundColorDiv.removeClass('d-none');
                backgroundImageDiv.addClass('d-none');
            } else {
                backgroundColorDiv.addClass('d-none');
                backgroundImageDiv.removeClass('d-none');
            }

            backgroundPropertySelect.on('change', function(){
                if ($(this).val() === 'Color') {
                    backgroundColorDiv.removeClass('d-none');
                    backgroundImageDiv.addClass('d-none');
                } else {
                    backgroundColorDiv.addClass('d-none');
                    backgroundImageDiv.removeClass('d-none');
                }
            });
        });

    </script>

@endsection
