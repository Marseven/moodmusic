<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Install Wizard</title>
    <meta name="theme-color" content="#ffffff">
    <link href="{{ Illuminate\Http\Request::capture()->getSchemeAndHttpHost() }}/backend/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="{{ Illuminate\Http\Request::capture()->getSchemeAndHttpHost() }}/backend/css/style.css" rel="stylesheet">
</head>
<body class="bg-dark">
<div class="container">
    <div class="card mx-auto mt-5 mb-5 col-12">
        <div class="card-header text-center">Install Wizard</div>
        <div class="card-body">
            @if (isset($errors) && count($errors) >0)
                @foreach($errors as $error)
                    <div class="alert alert-danger" role="alert">
                        {!! $error[0] !!}
                    </div>
                @endforeach
            @endif
            @yield('content')

        </div>
    </div>
</div>
<script src="{{ Illuminate\Http\Request::capture()->getSchemeAndHttpHost() }}/backend/vendor/jquery/jquery.min.js"></script>
</body>
</html>