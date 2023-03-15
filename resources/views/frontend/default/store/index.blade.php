@extends('index')
@section('pagination')
    @if(count($songs))
        @foreach($songs as $song)
            <script>var song_data_{{ $song->id }} = {!! json_encode($song) !!}</script>
            <div class="flat-song-list">
                <div class="flat-song-list-play-container">
                    <a title="Play" class="flat-song-list-play-button play-object" data-type="song" data-id="{{ $song->id }}" style="background-image: url('{{ $song->artwork_url }}')">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#FFF">
                            <path d="M20 12L8 5V19L20 12Z"></path>
                        </svg>
                    </a>
                </div>
                <div class="flat-song-list-info">
                    <div class="flat-song-list-info-inner">
                        <div title="Curiosidad" class="css-bzqkha">
                            <a href="{{ $song->permalink_url }}">{!! $song->title !!}</a></div>
                        <div class="css-kkg4pz">
                            @foreach($song->artists as $artist)<a href="{{ $artist->permalink_url }}" class="text-secondary" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach
                        </div>
                    </div>
                </div>
                <div title="Hopeful, Restless" class="css-4ehc5e">
                    @if(count($song->genres))
                        @foreach($song->genres as $genre)<a class="text-secondary" href="{{ $genre->permalink_url }}">{{ $genre->name }}</a>@if(!$loop->last), @endif @endforeach
                    @endif
                </div>

                <div class="flat-song-list-subtitle text-secondary">{{ humanTime($song->duration) }}</div>
                <div class="flat-song-list-subtitle text-secondary">{{ __('symbol.' . config('settings.currency', 'USD')) }}{{ $song->price }}</div>

                <button class="flat-song-list-button" data-action="share" data-type="song" data-id="{{ $song->id }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#FFF">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.3559 18L21.1506 11.7329L21.9476 10.9978L21.1506 10.2627L14.3559 3.99556L12.9999 5.46569L17.9133 9.99757H7.99998H7.35011L7.08617 10.5914L3.08618 19.5914L4.91381 20.4037L8.64985 11.9976H17.9138L12.9999 16.5299L14.3559 18Z"></path>
                    </svg>
                </button>
                @if($song->purchased)
                    <button title="download" class="flat-song-list-button" data-action="purchased-download">
                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.0007 16.9155L17.5346 12L19.0048 13.356L12.7376 20.1507L12.0025 20.9476L11.2675 20.1507L5.00029 13.356L6.47042 12L11.0007 16.9116V3H13.0007V16.9155Z"></path>
                        </svg>
                    </button>
                @else
                    <button title="buy" class="flat-song-list-button" data-action="buy" data-orderable-type="App\Models\Song" data-orderable-id="{{ $song->id }}">
                        <svg width="24" height="24" fill="#FFF" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M2 4H4.80701L6.06367 7.35103L9.06367 15.3508L9.30701 15.9997H10H18H18.693L18.9363 15.3508L21.9363 7.35103L22.443 5.9999H21H7.69299L6.43633 2.64887L6.19299 2H5.5H2V4ZM10.693 13.9997L8.44301 7.9999H19.557L17.307 13.9997H10.693ZM8 20C8 18.8954 8.89543 18 10 18C11.1046 18 12 18.8954 12 20C12 21.1046 11.1046 22 10 22C8.89543 22 8 21.1046 8 20ZM16 20C16 18.8954 16.8954 18 18 18C19.1046 18 20 18.8954 20 20C20 21.1046 19.1046 22 18 22C16.8954 22 16 21.1046 16 20Z"></path>
                        </svg>
                    </button>
                @endif
            </div>
        @endforeach
    @else
        <div class="empty-page following">
            <div class="empty-inner">
                <h2 data-translate-text="NO_RESULTS_FOUND">{{ __('web.NO_RESULTS_FOUND') }}</h2>
            </div>
        </div>
    @endif
@stop
@section('content')
    <div class="store-filter-container">
        <svg class="store-filter-icon" data-action="show-filter" height="22" viewBox="0 0 512 512" width="22" xmlns="http://www.w3.org/2000/svg"><g><path d="m420.404 0h-328.808c-50.506 0-91.596 41.09-91.596 91.596v328.809c0 50.505 41.09 91.595 91.596 91.595h328.809c50.505 0 91.595-41.09 91.595-91.596v-328.808c0-50.506-41.09-91.596-91.596-91.596zm61.596 420.404c0 33.964-27.632 61.596-61.596 61.596h-328.808c-33.964 0-61.596-27.632-61.596-61.596v-328.808c0-33.964 27.632-61.596 61.596-61.596h328.809c33.963 0 61.595 27.632 61.595 61.596z"/><path d="m432.733 112.467h-228.461c-6.281-18.655-23.926-32.133-44.672-32.133s-38.391 13.478-44.672 32.133h-35.661c-8.284 0-15 6.716-15 15s6.716 15 15 15h35.662c6.281 18.655 23.926 32.133 44.672 32.133s38.391-13.478 44.672-32.133h228.461c8.284 0 15-6.716 15-15s-6.716-15-15.001-15zm-273.133 32.133c-9.447 0-17.133-7.686-17.133-17.133s7.686-17.133 17.133-17.133 17.133 7.686 17.133 17.133-7.686 17.133-17.133 17.133z"/><path d="m432.733 241h-35.662c-6.281-18.655-23.927-32.133-44.672-32.133s-38.39 13.478-44.671 32.133h-228.461c-8.284 0-15 6.716-15 15s6.716 15 15 15h228.461c6.281 18.655 23.927 32.133 44.672 32.133s38.391-13.478 44.672-32.133h35.662c8.284 0 15-6.716 15-15s-6.716-15-15.001-15zm-80.333 32.133c-9.447 0-17.133-7.686-17.133-17.133s7.686-17.133 17.133-17.133 17.133 7.686 17.133 17.133-7.686 17.133-17.133 17.133z"/><path d="m432.733 369.533h-164.194c-6.281-18.655-23.926-32.133-44.672-32.133s-38.391 13.478-44.672 32.133h-99.928c-8.284 0-15 6.716-15 15s6.716 15 15 15h99.928c6.281 18.655 23.926 32.133 44.672 32.133s38.391-13.478 44.672-32.133h164.195c8.284 0 15-6.716 15-15s-6.716-15-15.001-15zm-208.866 32.134c-9.447 0-17.133-7.686-17.133-17.133s7.686-17.133 17.133-17.133 17.133 7.685 17.133 17.132-7.686 17.134-17.133 17.134z"/></g></svg>
        <div class="store-filter-search store-filter-search-mobile">
            <div class="store-filter-search-input-container">
                <input id="header-filter-search-input" placeholder="Search Filters" type="text" class="store-filter-search-input" autocomplete="off">
                <div class="store-filter-search-clear filter-masks">
                    <div class="store-filter-search-clearall">Clear All</div>
                </div>
                <div class="store-filter-search-visible store-filter-label hide" data-action="clear-filter"><span class="total-filter">0</span> Filters</div>
            </div>
            <div class="store-filter-m-clear">
                <div class="store-filter-m-clear-button align-items-center hide" data-action="clear-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                    <div>Clear All</div>
                </div>
            </div>
        </div>
    </div>
    <div class="store-container">
        <div class="store-filter-section">
            <div class="store-filter-main-section">
                <div class="store-filter-menu">
                    <div class="store-filter-menu-label" data-action="load-filter" data-type="genre">
                        <div class="store-filter-menu-text">{{ __('web.GENRE') }}</div>
                    </div>
                </div>
                <div class="store-filter-menu">
                    <div class="store-filter-menu-label" data-action="load-filter" data-type="mood">
                        <div class="store-filter-menu-text">{{ __('web.MOOD') }}</div>
                    </div>
                </div>
                <div class="store-filter-menu">
                    <div class="store-filter-menu-label" data-action="load-filter" data-type="artist">
                        <div class="store-filter-menu-text">{{ __('web.ARTIST') }}</div>
                    </div>
                </div>
            </div>
            <div class="filter-items-container">
                <div data-testid="filter-options-container" class="store-filter-distrack">
                    <div class="filter-items"></div>
                </div>
            </div>
            <div class="store-filter-mobile store-filter-mobile-2 justify-content-between align-items-center">
                <div class="store-filter-mobile-count"><span class="total-filter">0</span> Filters Selected</div>
                <div class="store-filter-mobile-apply store-filter-mobile-apply-flex-direction d-flex align-items-center">
                    <button class="btn btn-link mr-3" data-action="clear-filter">Cancel</button>
                    <button class="btn btn-sm btn-primary" data-action="apply-filter">Apply Filters</button>
                </div>
            </div>
        </div>
        <div class="store-item-list infinity-load-more" data-total-page="1">
            @yield('pagination')
        </div>
    </div>
@endsection