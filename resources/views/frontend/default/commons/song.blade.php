@foreach ($songs as $index => $song)
    @if(isset($song->title))
        <script>var song_data_{{ $song->id }} = {!! json_encode($song) !!}</script>
        @if($element == "genre" || $element == "artist" || $element == "collection")
            <div class="module module-row song tall draggable @if(isset($number)) show-number @endif" data-type="song" data-id="{{$song->id}}" data-index="{{ $index }}" data-plays="{{ $song->plays }}">
                <div class="fav-btn">
                    <a class="btn btn-icon-only favorite song-row-favorite @if(auth()->check() && $song->favorite) on @endif" data-type="song" data-id="{{$song->id}}">
                        <svg class="off" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg>
                        <svg class="on" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </a>
                    @if(isset($number))
                        <div class="flex justify-content-center align-items-center text-center">
                            {{ $loop->iteration }}
                        </div>
                    @endif
                </div>
                <div class="img-container" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}">
                    <img class="img" src="{{$song->artwork_url}}" alt="{!! $song->title !!}">
                    <div class="row-actions primary song-play-action">
                        <a class="btn play-lg play-object" data-type="song" data-id="{{ $song->id }}">
                            <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="metadata @if(isset($song->bpm) && $song->bpm) meta-bpm @endif" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}">
                    <div class="title">
                        <a href="{{ $song->permalink_url }}">{!! $song->title !!}</a>
                    </div>
                    @if(isset($song->bpm) && $song->bpm)
                        <div class="bpm desktop">{{ $song->bpm }} BPM</div>
                    @endif
                    <div class="artist">
                        @foreach($song->artists as $artist)<a href="{{ $artist->permalink_url }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach @if(isset($song->bpm) && $song->bpm)<span class="mobile">&nbsp;•&nbsp;{{ $song->bpm }}&nbsp;BPM</span>@endif
                    </div>
                    <div class="duration">{{ humanTime($song->duration) }}</div>
                </div>
                <div class="row-actions secondary">
                    <a class="btn options" data-toggle="contextmenu" data-trigger="left" data-type="song" data-id="{{ $song->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </a>
                </div>
            </div>
        @elseif($element == "snapshot")
            <div class="module module-row tall song draggable" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}" data-index="{{ $index }}" data-plays="{{ $song->plays }}">
                <div class="fav-btn">
                    <a class="btn btn-icon-only favorite song-row-favorite @if(auth()->check() && $song->favorite) on @endif" data-type="song" data-id="{{$song->id}}">
                        <svg class="off" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg>
                        <svg class="on" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </a>
                </div>
                <div class="img-container">
                    <img class="img" src="{{$song->artwork_url}}" alt="{!! $song->title !!}">
                    <div class="row-actions primary song-play-action">
                        <a class="btn play-lg play-object" data-type="song" data-id="{{ $song->id }}">
                            <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="metadata">
                    <a href="{{ $song->permalink_url }}" class="title">{!! $song->title !!}</a>
                </div>
            </div>
        @elseif($element == "trending")
            <div class="module module-row song trending tall draggable" data-type="song" data-id="{{$song->id}}" data-index="{{ $index }}" data-plays="{{ $song->plays }}">
                <div class="trending-position @if($song->last_postion && ($song->last_postion - $index > 0)) up @elseif($song->last_postion) down @endif">
                    <span class="current-position">{{ $index+1 }}</span>
                    <span class="last-position">{{ __('web.LAST') }}: {{ $song->last_postion ? $song->last_postion : '-' }}</span>
                </div>
                <div class="img-container" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}">
                    <img class="img" src="{{$song->artwork_url}}" alt="{!! $song->title !!}">
                    <div class="row-actions primary song-play-action">
                        <a class="btn play-lg play-object" data-type="song" data-id="{{ $song->id }}">
                            <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="metadata @if(isset($song->bpm)) meta-bpm @endif" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}">
                    <div class="title">
                        <a href="{{ $song->permalink_url }}">{!! $song->title !!}</a>
                    </div>
                    @if(isset($song->bpm))
                        <div class="bpm desktop">{{ $song->bpm }} BPM</div>
                    @endif
                    <div class="artist">
                        @foreach($song->artists as $artist)<a href="{{ $artist->permalink_url }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach @if(isset($song->bpm) && $song->bpm)<span class="mobile">&nbsp;•&nbsp;{{ $song->bpm }}&nbsp;BPM</span>@endif
                    </div>
                    <div class="duration">{{humanTime($song->duration)}}</div>
                </div>
                <div class="row-actions secondary">
                    <a class="btn options" data-toggle="contextmenu" data-trigger="left" data-type="song" data-id="{{ $song->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </a>
                </div>
            </div>
        @elseif($element == "playlist")
            <div class="module module-row song tall @if(isset($sortable) && $sortable)can-drag drag-handle @else draggable @endif" data-type="song" data-id="{{$song->id}}" data-index="{{ $index }}" data-plays="{{ $song->plays }}">
                @if(isset($sortable) && $sortable)
                    <div class="drag-handle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M11 18c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm-2-8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm6 4c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </div>
                @endif
                <div class="fav-btn">
                    <a class="btn btn-icon-only favorite song-row-favorite @if(auth()->check() && $song->favorite) on @endif" data-type="song" data-id="{{$song->id}}">
                        <svg class="off" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg>
                        <svg class="on" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </a>
                </div>
                <div class="img-container" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}" @if(isset($sortable) && $sortable) data-removable="true" data-removable-type="playlist" data-removable-id="{{ $playlist->id }}" @endif>
                    <img class="img" src="{{ $song->artwork_url }}" alt="{!! $song->title !!}">
                    <div class="row-actions primary song-play-action">
                        <a class="btn play-lg play-object" data-type="song" data-id="{{ $song->id }}">
                            <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                        </a>
                    </div>
                </div>
                <div class="metadata" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}" @if(isset($sortable) && $sortable) data-removable="true" data-removable-type="playlist" data-removable-id="{{ $playlist->id }}" @endif>
                    <div class="title">
                        <a href="{{ $song->permalink_url }}">{!! $song->title !!}</a>
                    </div>
                    <div class="artist">
                        @foreach($song->artists as $artist)<a href="{{$artist->permalink_url}}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach
                    </div>
                    <div class="duration">{{humanTime($song->duration)}}</div>
                </div>
                <div class="row-actions secondary">
                    <a class="btn options" data-toggle="contextmenu" data-trigger="left" data-type="song" data-id="{{ $song->id }}" @if(isset($sortable) && $sortable) data-removable="true" data-removable-type="playlist" data-removable-id="{{ $playlist->id }}" @endif>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </a>
                </div>
            </div>
        @elseif ($element == "activity")
            @if (count($songs) > 1)
                <a href="{{ $song->permalink_url }}" class="feed-item-img s---how-song-tooltip small song-link song draggable" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}">
                    <img src="{{ $song->artwork_url }}" class="row-feed-image" data-toggle="song-popover" data-placement="top" data-html="true" data-target="tmpl-song-popover" data-id="{{ $song->id }}" alt="{!! $song->title !!}">
                </a>
            @else
                <div class="feed-item " data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}">
                    <a href="{{ $song->permalink_url }}" class="feed-item-img song draggable" data-type="song" data-id="{{ $song->id }}">
                        <img class="feed-img-medium" src="{{ $song->artwork_url }}" width="80" height="80" data-id="{{ $song->id }}" data-toggle="song-popover" data-placement="top" data-html="true" data-target="tmpl-song-popover">
                    </a>
                    <div class="inner">
                        <a class="item-title song-link" href="{{ $song->permalink_url }}">{!! $song->title !!}</a>
                        @foreach($song->artists as $artist)<a class="item-subtitle artist-link" href="{{$artist->permalink_url}}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach
                        <a class="btn play play-object" data-type="song" data-id="{{ $song->id }}">
                            <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <span data-translate-text="PLAY_SONG">{{ __('web.PLAY_SONG') }}</span>
                        </a>
                    </div>
                </div>
            @endif
        @elseif ($element == "carousel")
            @if(config('settings.channel_grid_style'))
                <div class="module module-row station tall" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}" data-index="{{ $index }}" data-plays="{{ $song->plays }}">
                    <div class="img-container">
                        <img class="img" src="{{$song->artwork_url}}" alt="{!! $song->title !!}">
                        <a class="overlay-link" href="{{$song->permalink_url}}"></a>
                        <div class="row-actions primary">
                            <div class="button-process-container" data-song-id="{{ $song->id }}">
                                <div class="buttonProgressBorder button-progress-active-border">
                                    <div class="button-progress-circle"></div>
                                </div>
                            </div>
                            <a class="btn play play-lg play-object" data-type="song" data-id="{{$song->id}}">
                                <div></div>
                                <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                            </a>
                        </div>
                    </div>
                    <div class="metadata station">
                        <div class="title">
                            <a href="{{$song->permalink_url}}">{!! $song->title !!}</a>
                        </div>
                        <div class="description">
                            <span class="byline"><span data-translate-text="BY">{{ __('web.BY') }}</span> @foreach($song->artists as $artist)<a href="{{$artist->permalink_url}}" class="artist-link artist" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="module module-cell song swiper-slide draggable" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{{ $song->id }}" data-index="{{ $index }}" data-plays="{{ $song->plays }}">
                    <div class="img-container">
                        <img class="img" src="{{$song->artwork_url}}">
                        <a class="overlay-link" href="{{$song->permalink_url}}"></a>
                        <div class="actions primary">
                            <div class="button-process-container" data-song-id="{{ $song->id }}">
                                <div class="buttonProgressBorder button-progress-active-border">
                                    <div class="button-progress-circle"></div>
                                </div>
                            </div>
                            <a class="btn play play-lg play-object" data-type="song" data-id="{{$song->id}}">
                                <div></div>
                                <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                            </a>
                        </div>
                    </div>
                    <div class="module-inner">
                        <a class="song-link title" href="{{$song->permalink_url}}" title="{!! $song->title !!}">{!! $song->title !!}</a>
                        <span class="byline"><span data-translate-text="BY">{{ __('web.BY') }}</span> @foreach($song->artists as $artist)<a href="{{$artist->permalink_url}}" class="artist-link artist" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach</span>
                    </div>
                </div>
            @endif
        @endif
    @endif
@endforeach