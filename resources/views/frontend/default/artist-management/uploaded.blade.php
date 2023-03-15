@extends('index')
@section('pagination')
    @foreach ($songs as $index => $song)
        <tr class="module" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}">
            <td style="width: 60px">
                <div class="img-container">
                    <img class="img" src="{{$song->artwork_url}}" alt="{!! $song->title !!}">
                    <div class="row-actions primary song-play-action">
                        @if(! $song->pending)
                            <a class="btn play-lg play-object" data-type="song" data-id="{{ $song->id }}">
                                <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </td>
            <td>
                @if($song->approved)
                    <a class="song-link" href="{{ $song->permalink_url }}" data-song-id="{{ $song->id }}">{!! $song->title !!}</a>
                @else
                    <span class="text-muted">{!! $song->title !!}</span>
                @endif
            </td>
            <td class="text-center">
                @if($song->pending)
                    <svg fill="#2face1" height="18" width="18" class="basic-tooltip" tooltip="Processing..." viewBox="0 0 512.348 512.348" xmlns="http://www.w3.org/2000/svg"><path d="m496.816 211.974h-47.043c-4.453-18.407-11.45-35.821-20.616-51.837l36.922-36.922c6.065-6.066 6.065-15.9 0-21.966l-48.39-48.39c-6.066-6.066-15.9-6.066-21.966 0l-35.919 35.919c-16.048-9.805-33.592-17.395-52.208-22.368v-50.704c0-8.578-6.954-15.532-15.532-15.532h-68.434c-8.578 0-15.532 6.954-15.532 15.532v49.776c-19.226 4.746-37.349 12.28-53.923 22.152l-31.997-31.997c-6.066-6.066-15.9-6.066-21.966 0l-48.39 48.39c-6.066 6.066-6.066 15.9 0 21.966l32.211 32.211c-9.721 16.549-17.13 34.615-21.764 53.771h-46.737c-8.578-.001-15.532 6.953-15.532 15.532v68.434c0 8.578 6.954 15.532 15.532 15.532h48.134c4.976 18.284 12.479 35.526 22.127 51.318l-34.664 34.664c-6.066 6.066-6.066 15.9 0 21.966l48.39 48.39c6.066 6.066 15.9 6.066 21.966 0l35.667-35.667c15.171 8.682 31.591 15.43 48.926 19.903v44.596c0 8.578 6.954 15.532 15.532 15.532h68.434c8.578 0 15.532-6.954 15.532-15.532v-44.498c18.225-4.662 35.437-11.844 51.26-21.139l37.152 37.152c6.066 6.066 15.9 6.066 21.966 0l48.39-48.39c6.066-6.066 6.066-15.9 0-21.966l-36.939-36.939c9.091-15.265 16.199-31.845 20.973-49.39h48.439c8.578 0 15.532-6.954 15.532-15.532v-68.434c-.001-8.579-6.955-15.533-15.533-15.533zm-376.695-.722c-4.72-7.441-2.514-17.299 4.927-22.018 6.967-4.419 16.048-2.766 21.052 3.565 22.311-38.238 63.816-63.035 109.573-63.035 55.263 0 103.753 35.321 120.662 87.891 2.698 8.388-1.914 17.374-10.303 20.072-8.391 2.7-17.374-1.915-20.072-10.303-12.65-39.328-48.933-65.752-90.287-65.752-32.177 0-61.53 16.389-78.805 42.101 7.252-.142 13.913 4.698 15.766 12.033 2.158 8.543-3.017 17.218-11.56 19.375l-28.258 7.139c-6.221 1.593-13.697-.905-17.38-6.923zm267.178 111.862c-6.966 4.419-16.048 2.767-21.052-3.565-22.311 38.237-63.816 63.035-109.573 63.035-55.262 0-103.752-35.32-120.662-87.89-2.698-8.388 1.914-17.375 10.302-20.073 8.387-2.701 17.375 1.915 20.073 10.302 12.651 39.328 48.934 65.752 90.287 65.752 32.173 0 61.524-16.385 78.8-42.092-.101.002-.203.009-.304.009-7.134 0-13.63-4.818-15.457-12.05-2.158-8.543 3.017-17.218 11.56-19.375l28.258-7.138c6.197-1.557 13.917.91 17.532 7.161l15.163 23.906c4.72 7.441 2.514 17.298-4.927 22.018z"/></svg>
                @else
                    @if($song->approved)
                        <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 367 367" class="basic-tooltip" tooltip="This song has been approved by admin.">
                            <path fill="#3BB54A" d="M183.903,0.001c101.566,0,183.902,82.336,183.902,183.902s-82.336,183.902-183.902,183.902S0.001,285.469,0.001,183.903l0,0C-0.288,82.625,81.579,0.29,182.856,0.001C183.205,0,183.554,0,183.903,0.001z"/>
                            <polygon fill="#D4E1F4" points="285.78,133.225 155.168,263.837 82.025,191.217 111.805,161.96 155.168,204.801 256.001,103.968"/>
                        </svg>
                    @else
                        <svg fill="#ffc107" width="18" height="18" class="basic-tooltip" tooltip="Waiting to be approved." xmlns="http://www.w3.org/2000/svg" viewBox="0 0 299.995 299.995">
                            <path d="M149.995,0C67.156,0,0,67.158,0,149.995s67.156,150,149.995,150s150-67.163,150-150S232.834,0,149.995,0zM214.842,178.524H151.25c-0.215,0-0.415-0.052-0.628-0.06c-0.213,0.01-0.412,0.06-0.628,0.06c-5.729,0-10.374-4.645-10.374-10.374V62.249c0-5.729,4.645-10.374,10.374-10.374s10.374,4.645,10.374,10.374v95.527h54.47c5.729,0,10.374,4.645,10.374,10.374C225.212,173.879,220.571,178.524,214.842,178.524z"/>
                        </svg>
                    @endif
                @endif
            </td>
            <td class="text-center desktop">{{ $song->plays }}</td>
            <td class="text-center desktop">{{ $song->loves }}</td>
            <td class="text-center desktop">{{ __('symbol.' . config('settings.currency', 'USD')) }}{{ ($song->plays * \App\Models\Role::getValue('monetization_streaming_rate', 0)) }}</td>
            <td class="text-center desktop basic-tooltip" tooltip="Before tax">{{ __('symbol.' . config('settings.currency', 'USD')) }}{{ $song->sales }}</td>
            <td class="text-center secondary-actions-container">
                <div class="row-actions secondary align-items-stretch">
                    <a class="btn options song-row-edit" data-type="song" data-id="{{ $song->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </a>
                    <a class="btn options song-row-delete" data-type="song" data-id="{{ $song->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </a>
                    <a class="btn options" data-toggle="contextmenu" data-trigger="left" data-type="song" data-id="{{ $song->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </a>
                </div>
            </td>
        </tr>
        <script>var song_data_{{ $song->id }} = {!! json_encode($song->makeVisible(['description', 'copyright', 'released_at'])) !!}</script>
    @endforeach
@stop
@section('content')
    @include('artist-management.nav', ['artist' => $artist])
    <div id="page-content">
        <div class="container">
            <div class="page-header artist main small desktop"> <a class="img "> <img src="{{ $artist->artwork_url }}" alt="{{ $artist->name}}">  </a>
                <div class="inner">
                    <h1 title="{!! $artist->name !!}">{!! $artist->name !!}<span class="subpage-header"> / Uploaded</span></h1>
                    <div class="byline">Manager to upload your's songs from your computer to the website's library.</div>
                    <div class="actions-primary">
                        @include('artist-management.actions')
                    </div>
                </div>
            </div>
            <div id="column1" class="full">
                @if(count($songs))
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h2 class="m-0 font-weight-bold" data-translate-text="RECENT_UPLOADS">Recent Uploads</h2>
                        </div>
                        <div class="card-body">
                            <table class="table artist-management">
                                <thead>
                                <tr>
                                    <th class="th-image"></th>
                                    <th class="text-left">Title</th>
                                    <th>Status</th>
                                    <th class="desktop">Plays</th>
                                    <th class="desktop">Favs</th>
                                    <th class="desktop basic-tooltip" tooltip="Impression RPM is the amount of revenue paid per 1,000 impressions.">Impression</th>
                                    <th class="desktop">Sales</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class="infinity-load-more">
                                    @yield('pagination')
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection