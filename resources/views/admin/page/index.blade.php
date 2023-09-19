@extends('layouts.admin.app')

@section('title')
    Page
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
                            <h1 class="m-0">Manage Pages </h1>
                        </div>
                        <div class="col-md-5 col-sm-12 text-right">
                            @can('regions-create')
                                <a href="{{route('pages.create')}}" class="add-btn"> <i
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
                                <li class="breadcrumb-item active">Page</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="content-body">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Page List</h3>
                        </div>
                        <div class="card-body">
                            <table id="users_table"
                                   class="table table-bordered table-hover mmt-data-table table-striped table-mmt">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Page Icon</th>
                                    <th>Group</th>
                                    <th>Theme</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Stauts</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($pages as $i=> $page)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td><img width="100px" height="70px" src="{{ $page->page_icon }}" alt="{{$page->title}}"></td>
                                        <td>{{ $page?->group?->name }}</td>
                                        <td>{{ $page?->theme?->name}}</td>
                                        <td>{{ $page->title }}</td>
                                        <td>{{ $page->slug }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{$page->status == 'Active' ? 'badge-success' :'badge-danger'}} text-uppercase">{{$page->status}}</span>
                                        </td>

                                        <td align="center">
{{--                                            @can('pages-list')--}}
                                                <a class="edit_btn" href="{{route('pages.show',$page->id)}}"
                                                   title="Edit home">
                                                    <i class="fas fa-eye"></i>
                                                </a>
{{--                                            @endcan--}}
{{--                                            @can('pages-edit')--}}
                                                <a class="edit_btn" href="{{route('pages.edit',$page->id)}}"
                                                   title="Show home">
                                                    <i class="fas fa-edit"></i>
                                                </a>
{{--                                            @endcan--}}
{{--                                            @can('pages-delete')--}}
                                                <form method="post" action="{{route('pages.destroy',$page->id)}}"
                                                      class="d-inline">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="del_btn" title="Delete user"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
{{--                                            @endcan--}}
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
