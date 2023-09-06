@extends('layouts.admin.app')
@section('title', 'Create Users')

@section('content')
    <!-- Content Body -->
    <div class="content-wrapper">
        <section class="content">
            <form method="post" action="{{route('users.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="container-fluid">
                    <div class="content-header">
                        <div class="row">
                            <div class="col-md-7 col-sm-12">
                                <h1 class="m-0">Create User </h1>
                            </div>
                            <div class="col-md-5 col-sm-12 text-right">
                                <input type="hidden"/>
                                <input type="hidden">
                                <input type="hidden">
                                <button type="submit" class="save-btn" id="user"><i
                                            class="fas fa-external-link-square-alt"> </i> <br>
                                    Submit
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="page-breadcrumb breadcrumb">
                                    <li class="breadcrumb-item"><i class="fas fa-home"></i> <a href="/admin/Dashboard">Home</a>
                                        <i
                                                class="fas fa-angle-right"></i></li>
                                    <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a> <i
                                                class="fas fa-angle-right"></i></li>
                                    <li class="breadcrumb-item active">Create User</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="content-body">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Create User</h3>
                            </div>
                            @include('admin.users.form')
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection

