@php
    $mainmenu = 'Admin Login';
    $submenu = 'Admin Login';
@endphp

<!DOCTYPE html>
<html lang="en">
@include('admin.header')

<body class="hold-transition login-page"
    style="background-image: url('{{ asset('images/logo/') }}') ; background-repeat: no-repeat;background-size: cover;">
    <div class="login-box">
        <div class="login-logo">

            <img style="border-radius: 8% " src="{{ asset('images/logo/logo.png') }}" height="80" alt=""><br>
            <h1 class="text text-dark">{{ config('app.name') }} Admin</h1>
        </div>
        <!-- /.login-logo -->
        <div class="card card-outline card-danger ">
            <div class="card-body login-card-body">


                <form action="{{ url('admin/login') }}" method="POST">
                    @csrf
                    <label>
                        <p class="text text-danger">
                            @error('msg')
                                {{ $message }}
                            @enderror
                        </p>
                    </label>
                    <div class="input-group mb-3">
                        <input type="email" name="email" value="superadmin@gmail.com" class="form-control"
                            value="{{ old('email') }}" placeholder="Email">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <label>
                            <p class="text text-danger">
                                {{ $message }}</p>
                        </label>
                    @enderror
                    <div class="input-group mb-3">

                        <input type="password" name="password" value="admin" class="form-control"
                            placeholder="Password">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>

                        </div>
                    </div>
                    @error('password')
                        <label>
                            <p class="text text-danger">
                                {{ $message }}</p>
                        </label>
                    @enderror
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>

                </form>
                @if (session()->has('msg'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session()->get('msg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>
