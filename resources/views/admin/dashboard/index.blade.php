@extends('layouts.admin.app')
@section('title')
    DashBoard
@endsection
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
            </div>
        </section>
    </div>
@endsection
