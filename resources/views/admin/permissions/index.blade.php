@extends('layouts.admin.app')
@section('title', 'Permission')
@section('content')
    <!-- Content Body -->
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="content-header">
                    <div class="row">
                        <div class="col-md-7 col-sm-12">
                            <h1 class="m-0">Manage Permissions </h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="page-breadcrumb breadcrumb">
                                <li class="breadcrumb-item"><i class="fas fa-home"></i> <a
                                        href="/admin/Dashboard">Home</a> <i
                                        class="fas fa-angle-right"></i></li>
                                <li class="breadcrumb-item active">Permissions</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="content-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Permission List</h3>
                        </div>
                        <div class="card-body">
                            <table id="users_table"
                                   class="table table-bordered table-hover mmt-data-table table-striped table-mmt">
                                <thead>
                                <tr>
                                    <th class="text-center" width="7%">Sr</th>
                                    <th width="15%">Name</th>
                                    <th width="15%">Display Name</th>
                                    <th class="text-center" width="12%">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($permissions as $i => $permission)
                                    <tr>
                                        <td align="center">{{++$i}}</td>
                                        <td>{{$permission->name}} </td>
                                        <td>{{$permission->display_name}}</td>

                                        <td align="center">
                                            @can('permissions-edit')
                                                <button data-bs-dismiss="modal" data-bs-toggle="modal"
                                                        data-bs-target="#editPermission{{ $i }}" class="btn">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            @endcan
                                            {{--                                                @include('admin.permissions.edit')--}}

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
    </div>
@endsection
