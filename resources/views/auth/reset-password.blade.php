<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Procul Discipulus</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ URL::to('assets/css/app.css') }}">
    <!-- Costum CSS-->
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">
    <style>
        .orline{
    overflow: hidden;
    text-align: center;
}
.orline::before,
.orline::after {
    background-color: #000;
    content: "";
    display: inline-block;
    height: 1px;
    position: relative;
    vertical-align: middle;
    width: 50%;
}
.orline::before {
    right: 0.5em;
    margin-left: -50%;
}
.orline::after {
    left: 0.5em;
    margin-right: -50%;
}
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <span class="text-uppercase" style="font-size: 2rem"><span class="text-danger"><b><b>Procul</b></b></span><span class="text-dark"><b><b>Discipulus</b></b></span></span>
        <!-- <a href="#" class="text-uppercase"><span style="color: red;"><b><b>Procul</b></b></span><span><b><b>Discipulus</b></b></span></a> -->
    </div>
    <!-- /.login-logo -->

    <!-- /.login-box-body -->
    <div class="card">
        <div class="card-body login-card-body">
            <form method="post" action="{{ route('password.store') }}">
                @csrf
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="input-group mb-3">
                    <input type="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        placeholder="Email"
                        class="form-control @error('email') is-invalid @enderror" 
                    >
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                    @error('email')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                        name="password"
                        placeholder="Senha"
                        class="form-control @error('password') is-invalid @enderror"
                        autocomplete="off"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror

                </div>

                <div class="input-group mb-3">
                    <input type="password"
                        name="password_confirmation"
                        placeholder="Confirmar Senha"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        autocomplete="off"
                    >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password_confirmation')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror

                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark btn-block" id="btnLogin" style="background-color: #000d13;">Redefinir senha</button>
                    </div>
                </div>

                <p class="orline mb-2">OU</p>
                
                <div class="form-group mb-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-dark w-100">Iniciar sessão</a>
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>

</div>
<!-- /.login-box -->

<script src="{{ URL::to('assets/js/app.js') }}"></script>

</body>
</html>
