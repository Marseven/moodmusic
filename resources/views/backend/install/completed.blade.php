@extends('backend.install.index')
@section('content')
    <h3 class="text-center">Installation Complete</h3>
    <p>Congratulations, Music Engine has been successfully installed on your server.</p>
    <p><a target="_blank" href="{{ $siteUrl }}">Homepage of your website</a> and try the features of the engine. Or you can <a target="_blank" href="{{ $siteUrl }}/admin">enter</a> the Music Engine control panel and change other system settings.</p>
    <div class="alert alert-danger">Attention: when you install the engine, the database structure and administrator's account are created, and basic system settings are performed, so you need to delete <b>install.php</b> after the successful installation in order to avoid re-installation of the engine!</div>
    <a target="_blank" href="{{ $siteUrl }}" class="btn btn-success btn-block" type="submit">See Your Website</a>
@endsection