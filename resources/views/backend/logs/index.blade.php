@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">System logs</li>
    </ol>
    <div class="row">
        <div class="col-lg-9">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">System logs</h6>
                </div>
                <div class="card-body">
                    <div class="clearfix  mb-4">
                        <div class="float-left">
                            <a href="" class="btn btn-primary btn-sm log-refresh"><i class="fa fa-sync"></i> Refresh</a>
                            <button type="button" class="btn btn-default btn-sm log-live"><i class="fa fa-play"></i> </button>
                        </div>
                        <div class="float-right">
                            <div class="btn-group">
                                @if ($prevUrl)
                                    <a href="{{ $prevUrl }}" class="btn btn-primary btn-sm pjax"><i class="fa fa-chevron-left"></i></a>
                                @endif
                                @if ($nextUrl)
                                    <a href="{{ $nextUrl }}" class="btn btn-primary btn-sm pjax"><i class="fa fa-chevron-right"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                        <table class="table datatables table-hover">
                            <thead>
                            <tr>
                                <th class="th-wide-image">Level</th>
                                <th class="th-wide-image desktop">Env</th>
                                <th>Time</th>
                                <th class="desktop">Message</th>
                                <th class="table-width140">Exception</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($logs as $index => $log)
                                <tr>
                                    <td><span class="badge badge-{{\App\Http\Controllers\Backend\Encore\LogViewer::$levelColors[$log['level']]}}">{{ $log['level'] }}</span></td>
                                    <td class="desktop"><strong>{{ $log['env'] }}</strong></td>
                                    <td class="table-width140">{{ timeElapsedString($log['time']) }}</td>
                                    <td class="desktop"><code>{{ $log['info'] }}</code></td>
                                    <td>
                                        @if(!empty($log['trace']))
                                            <a href="javascript:;" class="btn btn-link pl-0" data-toggle="collapse" data-target=".trace-{{$index}}"><i class="fa fa-info"></i>&nbsp;&nbsp;Exception</a>
                                        @endif
                                    </td>
                                </tr>
                                @if (!empty($log['trace']))
                                    <tr class="collapse trace-{{$index}}">
                                        <td colspan="5"><div class="text-secondary text-wrap">{{ $log['trace'] }}</div></td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Files</h6>
                </div>
                <div class="card-body">
                    <div class="card mb-4 py-3 border-left-info">
                        <div class="card-body card-small">
                            <p>Size: {{ $size }}</p>
                            <p class="mb-0">Updated at: {{ timeElapsedString(date('Y-m-d H:i:s', filectime($filePath))) }}</p>
                        </div>
                    </div>
                    <ul class="list-group">
                        @foreach($logFiles as $logFile)
                            <li class="list-group-item @if($logFile == $fileName) active @endif "><a class="pjax @if($logFile == $fileName) text-white @endif " href="{{ route('backend.log-viewer-file', ['file' => $logFile]) }}"><i class="fa fa-{{ ($logFile == $fileName) ? 'folder-open' : 'folder' }}"></i> {{ $logFile }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection