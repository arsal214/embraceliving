@extends('layouts.admin.app')

@section('title')
    Home
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
                            <h1 class="m-0">Manage Home </h1>
                        </div>
                        <div class="col-md-5 col-sm-12 text-right">
                            @can('homes-create')
                                <a href="{{route('homes.create')}}" class="add-btn"> <i
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
                                <li class="breadcrumb-item active">Homes</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Homes List</h3>
                        </div>
                        <div class="card-body">
                            <table id="users_table"
                                   class="table table-bordered table-hover mmt-data-table table-striped table-mmt">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Region</th>
                                    <th>Code</th>
                                    <th>Identifier</th>
                                    <th>Heygo Token</th>
                                    <th>Template Link</th>
                                    <th>Status</th>
                                    <th >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($homes as $i=> $home)
                                    <tr>
                                        <td>{{ ++$i }}</td>

                                        <td>{{ $home->title }}</td>
                                        <td>{{ $home->region }}</td>
                                        <td>{{ $home->code }}</td>
                                        <td>{{ $home->identifier }}</td>
                                        <td>{{ $home->heygo_token }}</td>
                                        <td>{{ $home->template_link }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{$home->status == 'Active' ? 'badge-success' :'badge-danger'}} text-uppercase">{{$home->status}}</span>
                                        </td>

                                        <td align="center">
                                            @can('homes-list')
                                                <a class="edit_btn" href="{{route('homes.show',$home->id)}}"
                                                   title="Edit home">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('homes-edit')
                                                <a class="edit_btn" href="{{route('homes.edit',$home->id)}}"
                                                   title="Show home">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('homes-delete')
                                                <form method="post" action="{{route('homes.destroy',$home->id)}}"
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
