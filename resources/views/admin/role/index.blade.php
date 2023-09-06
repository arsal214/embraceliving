@extends('layouts.admin.app')

@section('title')
    Role
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="content-header">
                    <div class="row">
                        <div class="col-md-7 col-sm-12">
                            <h1 class="m-0">Manage Roles </h1>
                        </div>
                        <div class="col-md-5 col-sm-12 text-right">
                            @can('roles-create')
                                <a href="{{route('roles.create')}}" class="add-btn"> <i
                                        class="fa fa-plus"></i> <br> Add New </a>
                            @endcan
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="page-breadcrumb breadcrumb">
                                <li class="breadcrumb-item"><i class="fas fa-home"></i> <a
                                        href="/admin/Dashboard">Home</a> <i
                                        class="fas fa-angle-right"></i></li>
                                <li class="breadcrumb-item active">Roles</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="content-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Role List</h3>
                        </div>
                        <div class="card-body">
                            <table id="users_table"
                                   class="table table-bordered table-hover mmt-data-table table-striped table-mmt">
                                <thead>
                                <tr>
                                    <th class="text-center" width="7%">Sr</th>
                                    <th width="15%">Name</th>
                                    <th class="text-center" width="12%">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($roles as $i => $role)
                                    <tr>
                                        <td align="center">{{++$i}}</td>
                                        <td>{{$role->name}} </td>

                                        <td align="center">
                                            @can('roles-edit')
                                                <a class="edit_btn" href="{{route('roles.edit',$role->id)}}"
                                                   title="Edit Role">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('roles-delete')
                                                <form method="post" action="{{route('roles.destroy',$role->id)}}"
                                                      class="d-inline">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="del_btn" title="Delete user"><i
                                                            class="fas fa-trash"></i></button>
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
