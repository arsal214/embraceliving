<div class="card-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('name') }}
            {{ Form::text('name', $region->name, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-6">
            <label>Status</label>
            <select name="status" class="form-control select2">
                <option value="">Select Option</option>
                <option value="Active" {{ $region->status == 'Active' ? 'selected' : '' }}>Active
                </option>
                <option value="InActive" {{ $region->status == 'InActive' ? 'selected' : '' }}>
                    InActive</option>
            </select>
        </div>

    </div>
</div>
