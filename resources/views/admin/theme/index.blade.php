@extends('layouts.admin.app')

@section('title')
    Theme
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
                            <h1 class="m-0">Manage theme </h1>
                        </div>
                        <div class="col-md-5 col-sm-12 text-right">
                            @can('groups-create')
                                <a href="{{route('themes.create')}}" class="add-btn"> <i
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
                                <li class="breadcrumb-item active">themes</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">themes List</h3>
                        </div>
                        <div class="card-body">
                            <table id="users_table"
                                   class="table table-bordered table-hover mmt-data-table table-striped table-mmt">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>BackGround Image</th>
                                    <th>Status</th>
                                    <th >Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($themes as $i=> $theme)
{{--                                    @dd($themes)--}}
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>
                                            <img src="{{$theme->logo}}" alt="{{$theme->name}}" width="90px">
                                        </td>
                                        <td>{{ $theme->name }}</td>
                                        <td>
                                            <img src="{{ $theme->background_image }}" alt="{{ $theme->background_image }}" width="90px">
                                        </td>

                                        <td class="text-center">
                                            <span
                                                class="badge {{$theme->pivot?->status == 'Active' ? 'badge-success' :'badge-danger'}} text-uppercase">{{isset($theme->pivot?->status) ? $theme->pivot?->status : $theme->status}}</span>
                                        </td>

                                        <td align="center">
                                            @can('themes-list')
                                                <a class="edit_btn" href="{{route('themes.show',$theme->id)}}"
                                                   title="Edit home">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            @can('themes-edit')
                                                <a class="edit_btn" href="{{route('themes.edit',$theme->id)}}"
                                                   title="Show home">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('themes-delete')
                                                <form method="post" action="{{route('themes.destroy',$theme->id)}}"
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
