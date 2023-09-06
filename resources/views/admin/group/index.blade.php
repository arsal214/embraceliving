@extends('layouts.admin.app')

@section('title')
    Group
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="content-header">
                    <div class="row">
                        <div class="col-md-7 col-sm-12">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success">
                                    <p>{{ $message }}</p>
                                </div>
                            @endif
                            <h1 class="m-0">Manage Group </h1>
                        </div>
                        <div class="col-md-5 col-sm-12 text-right">
                            @can('groups-create')
                                <a href="{{route('groups.create')}}" class="add-btn"> <i
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
                                <li class="breadcrumb-item active">Groups</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Groups List</h3>
                        </div>
                        <div class="card-body">
                            <table id="users_table"
                                   class="table table-bordered table-hover mmt-data-table table-striped table-mmt">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Headline</th>
                                    <th>Description</th>
                                    <th>Status</th>

                                    <th >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($groups as $i=> $group)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>
                                            <img src="{{$group->logo}}" alt="{{$group->name}}" width="90px">
                                        </td>
                                        <td>{{ $group->name }}</td>
                                        <td>{{ $group->headline }}</td>
                                        <td>{{ $group->description }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{$group->status == 'Active' ? 'badge-success' :'badge-danger'}} text-uppercase">{{$group->status}}</span>
                                        </td>


                                        <td align="center">
                                            @can('groups-list')
                                                <a class="edit_btn" href="{{route('groups.show',$group->id)}}"
                                                   title="Edit home">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('groups-edit')
                                                <a class="edit_btn" href="{{route('groups.edit',$group->id)}}"
                                                   title="Show home">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('groups-delete')
                                                <form method="post" action="{{route('groups.destroy',$group->id)}}"
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
