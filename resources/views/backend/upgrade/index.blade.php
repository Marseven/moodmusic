@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Updating the engine to the latest version</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 border-0">
                    <h6 class="m-0 font-weight-bold text-primary">Music Engine Update Wizard</h6>
                </div>
                <div class="card-body">
                    <p>Welcome to the Music Engine automatic update system. Before continuing with the update, be sure to <a href="{{ route('backend.backup-list') }}" class="text-warning" target="_blank">backup</a> your files on the server, otherwise in case of problems your data will not be restored.</p>
                    <p>To continue updating the engine you must enter your license key. In order to automatically update the engine, you need an Internet connection between your server and https://codecanyon.com site.</p>
                    <form method="post" action="{{ route('backend.upgrade.process') }}">
                        @csrf
                        <div class="form-row align-items-center">
                            <div class="col-sm-3 my-1">
                                <input name="license" type="text" class="form-control" id="inlineFormInputName" placeholder="Enter a key to activate the license">
                            </div>
                            <div class="col-auto my-1">
                                <button type="submit" class="btn btn-primary">Next</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection