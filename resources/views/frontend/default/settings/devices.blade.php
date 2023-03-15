@extends('index')
@section('content')
    @include('settings.nav')
    <div id="page-content">
        <div class="container">
            <div class="page-header ">
                <h1 ><span data-translate-text="SETTINGS_TITLE">{{ __('web.SETTINGS_TITLE') }}</span> / <span data-translate-text="SETTINGS_TITLE_DEVICES">{{ __('web.SETTINGS_TITLE_DEVICES') }}</span></h1>
            </div>
            <div id="column1" class="full settings">
                <table class="table artist-management">
                    <thead>
                    <tr>
                        <th>Device</th>
                        <th>IP</th>
                        <th class="desktop">Last Activity</th>
                        <th class="desktop">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="infinity-load-more">
                    @foreach($devices as $device)
                        <tr class="module">
                            <td>{{ $device->user_agent }}</td>
                            <td>{{ $device->ip_address }}</td>
                            <td class="text-center">
                                @if(\Carbon\Carbon::parse($device->last_activity)->gt(\Carbon\Carbon::now()->subSeconds(50)))
                                    <span class="badge badge-pill badge-success">online</span>
                                @else
                                    {{ timeElapsedString(\Carbon\Carbon::parse($device->last_activity)->format('Y-m-d H:i:s')) }}
                                @endif
                            </td>
                            <td class="text-center secondary-actions-container">
                                @if(\Session::getId() == $device->id)
                                    <span class="badge badge-success badge-pill">this device</span>
                                @else
                                    <a class="badge badge-danger badge-pill pt-2 pb-2 pr-3 pl-3" data-action="remove-session" data-id="{{  $device->id }}">remove</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection