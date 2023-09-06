@extends('layouts.admin.app')

@section('title')
    Region
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
                            <h1 class="m-0">Manage Region </h1>
                        </div>
                        <div class="col-md-5 col-sm-12 text-right">
                            @can('regions-create')
                                <a href="{{route('regions.create')}}" class="add-btn"> <i
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
                                <li class="breadcrumb-item active">Region</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Region List</h3>
                        </div>
                        <div class="card-body">
                            <table id="users_table"
                                   class="table table-bordered table-hover mmt-data-table table-striped table-mmt">
                                <thead>
                                <tr>
                                    <th>No</th>

                                    <th>Name</th>
                                    <th>Status</th>

                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($regions as $i=>$region)
                                    <tr>
                                        <td>{{ ++$i }}</td>

                                        <td>{{ $region->name }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{$region->status == 'Active' ? 'badge-success' :'badge-danger'}} text-uppercase">{{$region->status}}</span>
                                        </td>

                                        <td align="center">
                                            @can('regions-list')
                                                <a class="edit_btn" href="{{route('regions.show',$region->id)}}"
                                                   title="Edit home">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('regions-edit')
                                                <a class="edit_btn" href="{{route('regions.edit',$region->id)}}"
                                                   title="Show home">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('regions-delete')
                                                <form method="post" action="{{route('regions.destroy',$region->id)}}"
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

