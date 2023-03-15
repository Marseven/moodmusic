@extends('backend.install.index')
@section('content')
    <h3 class="text-center">Let create your Admin account</h3>
    <form method="post" action="{{ $_SERVER['PHP_SELF'] }}?step=admin">
        <div class="form-group">
            <label>Your name</label>
            <input class="form-control" name="name" type="text" value="{{ $request->input('name') }}">
        </div>
        <div class="form-group">
            <label>Username</label>
            <input class="form-control" name="username" type="text" value="{{ $request->input('username') }}">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input class="form-control" name="email" type="text" value="{{ $request->input('email') }}">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input class="form-control" name="password" type="password" value="{{ $request->input('password') }}">
        </div>
        <div class="form-group">
            <label>Retype password</label>
            <input class="form-control" name="password_confirmation" type="password" value="{{ $request->input('password_confirmation') }}">
        </div>
        <button class="btn btn-primary btn-block" type="submit">Continue</button>
    </form>
@endsection