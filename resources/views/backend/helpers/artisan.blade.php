@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Terminal</li>
    </ol>
    <div class="card shadow mb-4 output-box">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fa fa-terminal"></i> Terminal</h6>
        </div>
        <div class="card-body">
            <div class="box-body">
                <div>
                    <pre class="output-body" id="terminal-box"></pre>
                </div>
                @foreach($commands['groups'] as $group => $command)
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ $group }}
                        </button>
                        <div class="dropdown-menu">
                            @foreach($command as $item)
                                <li><a href="#" class="dropdown-item loaded-command">{{$item}}</a></li>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <div class="btn-group dropup">
                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Other
                    </button>
                    <div class="dropdown-menu">
                        @foreach($commands['others'] as $item)
                            <li><a href="#" class="dropdown-item loaded-command">{{$item}}</a></li>
                        @endforeach
                    </div>
                </div>
                <div class="form-group input-group mt-4 clearfix">
                    <span class="input-group-addon">artisan</span>
                    <input class="form-control" id="terminal-query" placeholder="command" autocomplete="off">
                    <span class="input-group-append">
                        <button type="button" class="btn btn-warning" id="terminal-clear"><i class="fa fa-refresh"></i> clear</button>
			            <button type="submit" class="btn btn-primary" id="terminal-send"><i class="fa fa-paper-plane"></i> Send</button>
			        </span>
                </div>
            </div>
        </div>
    </div>
@endsection

