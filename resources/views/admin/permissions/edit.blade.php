@extends('layouts.admin.app')
@section('admin')
    <!-- Content Body -->
    <div class="modal fade" id="editPermission{{$i}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-md-0">
                    <div class="text-center mb-4">
                        <h3 class="mb-2 pb-1">Edit Permission</h3>
                        <p>Edit permission as per your requirements.</p>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading mb-2">Warning</h6>
                        <p class="mb-0 text-wrap">
                            By editing the permission name, you might not break the system
                            functionality. It will change only display Name.
                        </p>
                    </div>
                    <form class="row pt-2" method="POST" action="{{ route('users.edit', $permission->id )}}">
                        {{ method_field('PATCH') }}
                        @csrf
                        <div class="col-sm-9 mb-3">
                            <div>
                                <label for="display_name">Permission Name</label>
                                <input
                                    type="text"
                                    id="display_name"
                                    name="display_name"
                                    class="form-control"
                                    placeholder="Permission Name"
                                    tabindex="-1"
                                    value="{{ $permission->display_name }}"/>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary mt-1 mt-sm-0">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
