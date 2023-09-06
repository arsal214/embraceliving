<div class="card-body">
    <div class="row">
        <div class="form-group col-md-6 col-sm-6">
            {{ Form::label('Name') }}
            {{ Form::text('role', $role->name, ['class' => 'form-control' . ($errors->has('role') ? ' is-invalid' : ''), 'placeholder' => 'Name', 'required']) }}
            {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="mt-4 form-repeater">
            <div class="col-12">
                <h2>Role Permissions</h2>
                <div class="row">
                    <div class="col-md-12 text-nowrap fw-semibold">
                        <div class="form-check">
                            <input class="form-check-input selectAll" type="checkbox" id="selectAll" />
                            <label class="form-check-label" for="selectAll">
                                Select All
                            </label>
                        </div>
                    </div>
                    @php
                        $rolePermissions = rolePermissions($role->id);
                    @endphp
                    @foreach (adminAllPermissions() as $title => $permissions)
                        <div class="col-md-12">
                            <h5 class="text-capitalize mt-2 mb-1">{{ $title }}</h5>
                            <div class="row px-3">
                                @foreach ($permissions as $permission)
                                    <div class="form-check col-3">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                               id="edit{{ $role->id . $permission['id'] }}" value="{{ $permission['id'] }}"
                                            {{ in_array($permission['id'], $rolePermissions) ? 'checked' : '' }} />
                                        <label class="form-check-label text-capitalize"
                                               for="edit{{ $role->id . $permission['id'] }}">
                                            {{ $permission['name'] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>


