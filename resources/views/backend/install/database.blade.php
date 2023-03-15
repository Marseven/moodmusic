@extends('backend.install.index')
@section('content')
    <form method="post" action="{{ $_SERVER['PHP_SELF'] }}?step=database">
        <div class="form-group">
            <label>Mysql host</label>
            <input class="form-control" name="host" type="text" value="{{ $request->input('host') }}">
        </div>
        <div class="form-group">
            <label>Database name</label>
            <input class="form-control" name="database" type="text" value="{{ $request->input('database') }}">
        </div>
        <div class="form-group">
            <label>Mysql username</label>
            <input class="form-control" name="username" type="text" value="{{ $request->input('username') }}">
        </div>
        <div class="form-group">
            <label>Mysql password</label>
            <input class="form-control" name="password" type="text" value="{{ $request->input('password') }}">
        </div>
        <div class="alert alert-secondary text-center">Below you should enter your database connection details. If you’re not sure about these, contact your host.</div>
        <button class="btn btn-primary btn-block" type="submit">Continue</button>
    </form>
@endsection