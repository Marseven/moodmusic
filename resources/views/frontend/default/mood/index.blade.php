@extends('index')
@section('pagination')
    @include('commons.song', ['songs' => $mood->songs, 'element' => 'genre'])
@stop
@section('content')
    {!! Advert::get('header') !!}
    <div id="page-content">
        <div class="container">
            <div class="page-header tag-header small desktop">
                <div class="inner">
                    <div class="actions-primary">
                        <a class="btn play-station" data-type="mood" data-id="{{ $mood->id }}"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><circle cx="6.18" cy="17.82" r="2.18"/><path d="M4 4.44v2.83c7.03 0 12.73 5.7 12.73 12.73h2.83c0-8.59-6.97-15.56-15.56-15.56zm0 5.66v2.83c3.9 0 7.07 3.17 7.07 7.07h2.83c0-5.47-4.43-9.9-9.9-9.9z"/></svg><span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span></a>
                        <a class="btn share hide" data-tag-id="41"><svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg><span data-translate-text="SHARE">{{ __('web.SHARE') }}</span></a>
                    </div>
                    <h1><span title="{{ $mood->name }}">{{ $mood->name }}</span></h1>
                    <div class="byline"><a href="{{ route('frontend.discover') }}"><span data-translate-text="SEE_ALL_MOODS">{{ __('web.SEE_ALL_MOODS') }}</span></a></div>
                </div>
            </div>
            <div id="column1" class="full">
                @include('commons.slideshow', ['slides' => $slides, 'style' => 'featured'])
                @include('commons.channel', ['channels' => $channels])
                @include('commons.toolbar.song', ['type' => 'mood', 'id' => $mood->id])
                <div id="songs-grid" class="infinity-load-more">
                    @yield('pagination')
                </div>
            </div>
        </div>
    </div>
    {!! Advert::get('footer') !!}
@endsection