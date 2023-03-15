@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Scheduling</li>
    </ol>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-calendar"></i> Scheduling</h6>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <tbody>
                <tr>
                    <th class="th-1action">#</th>
                    <th>Task</th>
                    <th>Run at</th>
                    <th>Next run time</th>
                    <th>Description</th>
                    <th>Run</th>
                </tr>
                @foreach($events as $index => $event)
                    <tr>
                        <td>{{ $index+1 }}.</td>
                        <td><code>{{ $event['task']['name'] }}</code></td>
                        <td><span class="label label-success">{{ $event['expression'] }}</span>&nbsp;{{ $event['readable'] }}</td>
                        <td>{{ $event['nextRunDate'] }}</td>
                        <td>{{ $event['description'] }}</td>
                        <td width="70px"><a class="btn btn-sm btn-primary run-task text-white" data-id="{{ $index+1 }}">Run</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card shadow mb-4 output-box d-none">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-terminal"></i> Output</h6>
        </div>
        <div class="card-body">
            <div class="box-body">
                <pre class="output-body"></pre>
            </div>
        </div>
    </div>
@endsection