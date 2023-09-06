@extends('layouts.app')

@section('front_content')

<div class="hold-transition login-page" style="background: #d93c85;">
    <div class="login-box">
        <div class="login-logo">
            <img src="{{asset('/images/mmt_logo.png')}}" class="img-fluid w-75" />
        </div>

        <!-- /.login-box-body -->
        <div class="card bg-transparent shadow-none">
            <div class="card-body login-card-body bg-transparent shadow-none">
                <form method="POST" action="{{route('login')}}">
                    @csrf
                    <div class="input-group input-group-lg mb-3 border border-primary rounded">
                        <div class="input-group-prepend">
                            <span class="input-group-text rounded-0 text-mmt" style="background:#f7f1f2;"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="text" placeholder="Username" id="username" class="form-control rounded-0" name="email" required autofocus>
                    </div>
                    <div class="input-group input-group-lg mb-3 border border-primary rounded">
                        <div class="input-group-prepend">
                            <span class="input-group-text rounded-0 text-mmt" style="background:#f7f1f2;"><i class="fa fa-lock"></i></span>
                        </div>
                        <input type="password" placeholder="Password" id="password" class="form-control rounded-0" name="password" required>
                    </div>
                                        <div class="d-grid mx-auto">
                        <input type="hidden">
                        <button type="submit" class="btn btn-login btn-block btn-lg font-weight-bold">Login</button>
                    </div>
                </form>
                <div class="row mt-2">
                    <div class="col-8">
                        <p class="mb-1">
                            <a href="#" class="text-dark">I forgot my password</a>
                        </p>
                    </div>
                    <div class="col-4 text-right">
                        <p class="mb-1">
                            <a href="#" class="text-dark">Contact Us</a>
                        </p>
                    </div>
                </div>
            </div>
            <!-- /.login-card-body -->
        </div>

    </div>
</div>
    <!-- /.login-box -->
    @endsection
