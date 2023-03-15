@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Backup</li>
    </ol>
    <div class="alert alert-info">
        You can create a backup of your site by click to "Create a backup" button, keep in mind that the system will just backup your database and main php files (which are not included folder "storage", the folder containing all your conversion data such as songs, artwork etc...).
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3 border-0">
            <button class="btn btn-link p-0 m-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <h6 class="m-0 font-weight-bold text-primary">Music Engine Update Wizard</h6>
            </button>
            <button class="float-right font-weight-bold backup-run btn btn-sm btn-success" href="{{ route('backend.backup-run') }}">Create a backup</button>
        </div>
        <div class="card-body">
            <table class="table table-striped datatables table-hover">
                <thead>
                <tr>
                    <th class="th-priority">#</th>
                    <th>Name</th>
                    <th>Disk</th>
                    <th>Reachable</th>
                    <th>Healthy</th>
                    <th># of backups</th>
                    <th>Newest backup</th>
                    <th>Used storage</th>
                </tr>
                </thead>
                <tbody>
                @foreach($backups as $index => $backup)
                    <tr data-toggle="collapse" data-target="#trace-{{$index+1}}">
                        <td>{{ $index+1 }}.</td>
                        <td>{{ $backup[0] }}</td>
                        <td>{{ $backup['disk'] }}</td>
                        <td>{{ $backup[2] }}</td>
                        <td>{{ isset($backup[3]) ? $backup[3] : '' }}</td>
                        <td>{{ $backup['amount'] }}</td>
                        <td>{{ strip_tags($backup['newest']) }}</td>
                        <td>{{ $backup['usedStorage'] }}</td>
                    </tr>
                    <tr class="collapse" id="trace-{{$index+1}}">
                        <td colspan="8">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Backup name</th>
                                    <th class="th-2action">Action</th>
                                </tr>
                                <tbody>
                                @foreach($backup['files'] as $file)
                                    <tr>
                                        <td>{{ $file }}</td>
                                        <td>
                                            <a target="_blank" href="{{ route('backend.backup-download', ['disk' => $backup['disk'], 'file' => $backup[0].'/'.$file]) }}"><i class="fa fa-download"></i></a>
                                            <a href="{{ route('backend.backup-delete', ['disk' => $backup['disk'], 'file' => $backup[0].'/'.$file]) }}" class="backup-delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5 loading-box container d-none">
        <p class="text-center">Please wait, this could take 5 minutes or more...</p>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
    </div>

    <div class="card shadow output-box d-none mt-5">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Output</h6>
        </div>
        <div class="card-body">
            <pre class="output-body"></pre>
        </div>
    </div>
@endsection