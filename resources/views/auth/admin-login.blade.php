@extends('layouts._login')

@section('login_content')
    <div id="login-box-body">
        <form action="{{route('admin.login.submit')}}" method="post" role="form">
            {{ csrf_field() }}
            <div class="form-group has-feedback" {{ $errors->has('name') ? ' has-error' : '' }}>
                <input type="text" class="form-control" placeholder="Benutzername" name="name" value="{{old('name')}}" required autofocus>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @if ($errors->has('name'))
                    <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group has-feedback" {{ $errors->has('password') ? ' has-error' : '' }}>
                <input type="password" class="form-control" placeholder="Password" name="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <a href="{{route('password.request')}}">I forgot my password</a><br>
        <a href="{{route('register')}}" class="text-center">Register a new membership</a>
    </div>
@endsection
