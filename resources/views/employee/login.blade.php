@extends('layouts.app')
@section('content')

<body class="fixed-left">
    <!-- Begin page -->
    <div class="accountbg"></div>
    <div class="wrapper-page">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mt-0 m-b-15">
                    <a href="#" class="logo logo-admin"><img src="{{asset('assets/images/logo.png')}}" height="24" alt="logo" /></a>
                </h3>
                <div class="p-3">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="POST"
                        class="form-horizontal m-t-20"
                        action="{{route('login')}}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-12">
                                <input
                                    class="form-control"
                                    type="text"
                                    name="email"
                                    required=""
                                    placeholder="Username" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <input
                                    class="form-control"
                                    type="password"
                                    name="password"
                                    required=""
                                    placeholder="Password" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="customCheck1" />
                                    <label class="custom-control-label" for="customCheck1">Remember me</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center row m-t-20">
                            <div class="col-12">
                                <button
                                    class="btn btn-danger btn-block waves-effect waves-light"
                                    type="submit">
                                    Log In
                                </button>
                            </div>
                        </div>
                        <div class="form-group m-t-10 mb-0 row">
                            <div class="col-sm-7 m-t-20">
                                <a href="pages-recoverpw.html" class="text-muted"><i class="mdi mdi-lock"></i>
                                    <small>Forgot your password ?</small></a>
                            </div>
                            <div class="col-sm-5 m-t-20 text-end">
                                @if ($view_type === 'employee')
                                <a href="{{ route('admin.login') }}" class="text-muted">
                                    <i class="mdi mdi-account-box"></i>
                                    <small>Admin Login</small>
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection('content')