<div class="card-body">
    <div class="row">

        <div class="form-group col-md-4 col-sm-4">
            {{ Form::label('title') }}
            {{ Form::text('title', $home->title, ['class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Title']) }}
            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-4 col-sm-4">
                {{ Form::label('regions') }}
                {{ Form::select('region_id', $regions, $home->region_id, ['class' => 'form-control select2 form-select' . ($errors->has('regions') ? ' is-invalid' : ''), 'required']) }}
                {!! $errors->first('regions', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="form-group col-md-4 col-sm-4">
            {{ Form::label('code') }}
            {{ Form::text('code', $home->code, ['class' => 'form-control' . ($errors->has('code') ? ' is-invalid' : ''), 'placeholder' => 'Code']) }}
            {!! $errors->first('code', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4 col-sm-4">
            {{ Form::label('identifier') }}
            {{ Form::text('identifier', $home->identifier, ['class' => 'form-control' . ($errors->has('identifier') ? ' is-invalid' : ''), 'required','placeholder' => 'Identifier']) }}
            {!! $errors->first('identifier', '<div class="invalid-feedback">:message</div>') !!}
        </div>

        <div class=" form-group col-md-4 col-sm-4">
            <label>Status</label>
            <select name="status" required="required" class="form-control select2">
                <option value="">Select Option</option>
                <option value="Active" {{ $home->status == 'Active' ? 'selected' : '' }}>Active
                </option>
                <option value="InActive" {{ $home->status == 'InActive' ? 'selected' : '' }}>
                    InActive</option>
            </select>
        </div>
        <div class="form-group col-md-4 col-sm-4">
            {{ Form::label('template_link') }}
            {{ Form::text('template_link', $home->template_link, ['class' => 'form-control' . ($errors->has('template_link') ? ' is-invalid' : ''), 'placeholder' => 'Template Link']) }}
            {!! $errors->first('template_link', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('heygo_token') }}
            {{ Form::text('heygo_token', $home->heygo_token, ['class' => 'form-control' . ($errors->has('heygo_token') ? ' is-invalid' : ''), 'placeholder' => 'Heygo Token']) }}
            {!! $errors->first('heygo_token', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>

</div>
</div>
