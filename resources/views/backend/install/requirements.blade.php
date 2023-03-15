@extends('backend.install.index')
@section('content')
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Minimum requirements of the script</th>
            <th>Current value</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Version of PHP 7.4.0 and higher</td>
            <td>{!! $requirements->phpversion !!}</td>
        </tr>
        <tr>
            <td>Safe mode</td>
            <td>{!! $requirements->safe_mode !!}</td>
        </tr>
        <tr>
            <td>File uploads</td>
            <td>{!! $requirements->file_uploads !!}</td>
        </tr>
        <tr>
            <td>Openssl</td>
            <td>{!! $requirements->openssl !!}</td>
        </tr>
        <tr>
            <td>PDO PHP Extension</td>
            <td>{!! $requirements->pdo !!}</td>
        </tr>
        <tr>
            <td>Mbstring PHP Extension</td>
            <td>{!! $requirements->mbstring !!}</td>
        </tr>
        <tr>
            <td>XML PHP Extension</td>
            <td>{!! $requirements->xml !!}</td>
        </tr>
        <tr>
            <td>iconv Extension</td>
            <td>{!! $requirements->iconv !!}</td>
        </tr>
        <tr>
            <td>CURL Extension</td>
            <td>{!! $requirements->curl !!}</td>
        </tr>
        <tr>
            <td>EXIF Extension</td>
            <td>{!! $requirements->exif !!}</td>
        </tr>
        </tbody>
    </table>
    <div class="alert alert-info">
        The app framework has a few system requirements. Before we will install the app please make sure you got have all system requirements.
    </div>
    <a href="{{ $_SERVER['PHP_SELF'] }}?step=chmod" class="btn btn-primary btn-block">Continue</a>
@endsection