@extends('index')
@section('content')
    @include('homepage.nav')
    <div id="page-content">
        <div class="container">
            <div class="page-header no-separator desktop">
                <div id="primary-actions">
                    <a class="btn play-station" data-type="trending"> <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg> <span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span> </a>
                    <a class="btn share"> <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg> <span>{{ __('web.SHARE') }}</span> </a>
                </div>
                <h1 data-translate-text="POPULAR">{{ __('web.POPULAR') }}</h1>
            </div>
            <div id="column1" class="full">
                @include('commons.slideshow', ['slides' => $trending->slides])
                @include('commons.channel', ['channels' => $trending->channels])
                @include('commons.toolbar.song', ['trending' => true, 'type' => 'trending' , 'id' => null])
                <div class="mobile-toolbar mobile">
                    <a class="btn today @if(Request::route()->getName() == 'frontend.trending') active @endif" href="{{ route('frontend.trending') }}">
                        <span data-translate-text="POPULAR_TODAY">{{ __('web.POPULAR_TODAY') }}</span>
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/></svg>
                    </a>
                    <a class="btn week @if(Request::route()->getName() == 'frontend.trending.week') active @endif" href="{{ route('frontend.trending.week') }}">
                        <span data-translate-text="POPULAR_WEEK">{{ __('web.POPULAR_WEEK') }}</span>
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 5H3c-.55 0-1 .45-1 1v12c0 .55.45 1 1 1h3c.55 0 1-.45 1-1V6c0-.55-.45-1-1-1zm14 0h-3c-.55 0-1 .45-1 1v12c0 .55.45 1 1 1h3c.55 0 1-.45 1-1V6c0-.55-.45-1-1-1zm-7 0h-3c-.55 0-1 .45-1 1v12c0 .55.45 1 1 1h3c.55 0 1-.45 1-1V6c0-.55-.45-1-1-1z"/></svg>
                    </a>
                    <a class="btn month @if(Request::route()->getName() == 'frontend.trending.month') active @endif" href="{{ route('frontend.trending.month') }}">
                        <span data-translate-text="POPULAR_MONTH">{{ __('web.POPULAR_MONTH') }}</span>
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" viewBox="0 0 24 24"><defs><path id="a" d="M0 0h24v24H0V0z"/></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"/></clipPath><path clip-path="url(#b)" d="M23 8c0 1.1-.9 2-2 2-.18 0-.35-.02-.51-.07l-3.56 3.55c.05.16.07.34.07.52 0 1.1-.9 2-2 2s-2-.9-2-2c0-.18.02-.36.07-.52l-2.55-2.55c-.16.05-.34.07-.52.07s-.36-.02-.52-.07l-4.55 4.56c.05.16.07.33.07.51 0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2c.18 0 .35.02.51.07l4.56-4.55C8.02 9.36 8 9.18 8 9c0-1.1.9-2 2-2s2 .9 2 2c0 .18-.02.36-.07.52l2.55 2.55c.16-.05.34-.07.52-.07s.36.02.52.07l3.55-3.56C19.02 8.35 19 8.18 19 8c0-1.1.9-2 2-2s2 .9 2 2z"/></svg>
                    </a>
                </div>
                <div id="songs-grid" class="popular has-row-numbers">
                    @include('commons.song', ['songs' => $trending->songs, 'element' => 'trending'])
                </div>
            </div>
        </div>
    </div>
@endsection