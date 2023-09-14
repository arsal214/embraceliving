@extends('layouts.admin.app')
@section('title', 'Users')
@section('content')
    <!-- Content Body -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="content-header">
                    <div class="row">
                        <div class="col-md-7 col-sm-12">
                            <h1 class="m-0">Manage Users </h1>
                        </div>
                        <div class="col-md-5 col-sm-12 text-right">
                            @can('users-create')
                                <a href="{{route('users.create')}}" class="add-btn"> <i
                                        class="fa fa-user-plus"></i> <br> Add New </a>
                            @endcan
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="page-breadcrumb breadcrumb">
                                <li class="breadcrumb-item"><i class="fas fa-home"></i> <a
                                        href="/admin/Dashboard">Home</a> <i
                                        class="fas fa-angle-right"></i></li>
                                <li class="breadcrumb-item active">Users</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="content-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Users List</h3>
                        </div>
                        <div class="card-body">
                            <table id="users_table"
                                   class="table table-bordered table-hover mmt-data-table table-striped table-mmt">
                                <thead>
                                <tr>
                                    <th class="text-center" width="7%">Sr</th>
                                    <th width="15%">Name</th>
                                    <th width="15%">Username</th>
                                    <th width="20%">Contact Email</th>
                                    <th width="12%">Role</th>
                                    <th width="12%">Group</th>
                                    <th class="text-center" width="10%">Status</th>
                                    <th class="text-center" width="12%">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($users as $i => $user)
                                    <tr>
                                        <td align="center">{{++$i}}</td>
                                        <td>{{$user->first_name}}  {{$user->last_name}}</td>
                                        <td>{{$user->username}}</td>
                                        <td>{{$user->email}}</td>
                                        @foreach($user->roles as $role)
                                            <td>
                                                {{$role->name}}

                                            </td>
                                        @endforeach
                                        <td>{{$user?->group?->name}}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{$user->status == 'Active' ? 'badge-success' :'badge-danger'}} text-uppercase">{{$user->status}}</span>
                                        </td>
                                        <td align="center">
                                            @can('users-edit')
                                                <a class="edit_btn" href="{{route('users.edit',$user->id)}}"
                                                   title="Edit user">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('users-delete')
                                                <form method="post" action="{{route('users.destroy',$user->id)}}"
                                                      class="d-inline">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="del_btn" title="Delete user"><i
                                                            class="fas fa-user-minus"></i></button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
