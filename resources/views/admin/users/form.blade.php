<div class="card-body">
    <div class="row">
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('First Name') }}
            {{ Form::text('first_name', $user->first_name, ['class' => 'form-control' . ($errors->has('first_name') ? ' is-invalid' : ''), 'required', 'placeholder' => 'First Name']) }}
            {!! $errors->first('first_name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('Last Name') }}
            {{ Form::text('last_name', $user->last_name, ['class' => 'form-control' . ($errors->has('last_name') ? ' is-invalid' : ''),  'required','placeholder' => 'Last Name']) }}
            {!! $errors->first('last_name', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('UserName') }}
            {{ Form::text('username', $user->username, ['class' => 'form-control' . ($errors->has('username') ? ' is-invalid' : ''), 'required', 'placeholder' => 'User Name']) }}
            {!! $errors->first('username', '<div class="invalid-feedback">:message</div>') !!}
            <small><b>Note:</b> Username must contain min. 8 characters.</small>
        </div>
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('password') }}
            {{ Form::password('password', ['class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''), 'placeholder' => 'Password',$user->id ? '': 'required']) }}
            {!! $errors->first('password', '<p class="invalid-feedback">:message</p>') !!}
            <small><b>Note:</b> Password must contain min. 8 characters, capital letter,
                number and a special character.</small>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-6 col-sm-6">
            {{ Form::label('email') }}
            {{ Form::email('email', $user->email, ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''), 'required', 'placeholder' => 'Email','required']) }}
            {!! $errors->first('email', '<p class="invalid-feedback">:message</p>') !!}

        </div>
        <div class="col-md-6 col-sm-12">
            {{ Form::label('roles') }}
            {{ Form::select('roles[]', $roles, $user->roles, ['class' => 'form-control select2 form-select' . ($errors->has('roles') ? ' is-invalid' : ''), 'required','multiple']) }}
            {!! $errors->first('roles', '<p class="invalid-feedback">:message</p>') !!}
        </div>
    </div>
    <div class="row">
        <div class="col-4 col-md-4">
        <label>Type</label>
        <select name="type" required="required" class="form-control select2">
            <option value="">Select Option</option>
            <option value="Admin" {{ $user->type == 'Admin' ? 'selected' : '' }}>Admin
            </option>
            <option value="GroupAdmin" {{ $user->type == 'GroupAdmin' ? 'selected' : '' }}>
                GroupAdmin</option>
        </select>
        </div>
        <div class="col-4 col-md-4 {{($user->type == 'Admin') ? 'd-none' : ''}} " id="groups">
            {{ Form::label('group') }}
            {{ Form::select('group_id', ['' => 'Please select a group...'] + $groups->toArray(), $user->group_id, ['class' => 'form-control select2 form-select' . ($errors->has('groups') ? ' is-invalid' : '')]) }}
            {!! $errors->first('groups', '<p class="invalid-feedback">:message</p>') !!}
        </div>
        <div class="col-4 col-md-4">
            <label>Status</label>
            <select name="status" required="required" class="form-control select2">
                <option value="">Select Option</option>
                <option value="Active" {{ $user->status == 'Active' ? 'selected' : '' }}>Active
                </option>
                <option value="InActive" {{ $user->status == 'InActive' ? 'selected' : '' }}>
                    InActive</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-6 col-md-6">
            <label for="image">Upload Image</label>
            <input type="file" id="image" name="image" accept="image/*" class="dropify"
                   data-default-file="{{ $user->image ?? '' }}" data-height="350" />
        </div>
    </div>
</div>
@section('page-script')
<script>

    $(document).ready(function(){
        // Function to show/hide #groups element based on select box value
        function handleTypeChange() {
            if ($('select[name="type"]').val() === 'GroupAdmin') {
                $('#groups').removeClass('d-none');
            } else {
                $('#groups').addClass('d-none');
            }
        }

        // Attach the event handler to the select box change event
        $('select[name="type"]').on('change', handleTypeChange);

        // Trigger the event on page load to handle the default value
        handleTypeChange();
    });
</script>

@endsection
