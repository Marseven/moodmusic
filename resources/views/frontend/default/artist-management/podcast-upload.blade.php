@extends('index')
@section('content')
    @include('artist-management.nav', ['artist' => $artist])
    <div id="page-content" class="artist">
        <div class="page-header artist main small desktop">
            <a class="img ">
                <img src="{{ $artist->artwork_url }}" alt="{{ $artist->name}}">
            </a>
            <div class="inner">
                <h1 title="{!! $artist->name !!}">{!! $artist->name !!}<span class="subpage-header"> / {{ $podcast->title }} / {{ __('web.UPLOADS') }}</span></h1>
                <div class="byline"> <span class="label">{!! __('web.UPLOAD') !!}</span></div>
                <div class="actions-primary">
                    <a class="btn" href="{{ route('frontend.auth.user.artist.manager.albums.show', ['id' => $podcast->id]) }}">
                        <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                        <span class="desktop" data-translate-text="BACK">Back</span>
                    </a>
                </div>
            </div>
        </div>
        <div id="column1" class="content full">
            <form id="fileupload" data-template="template-episode-upload" action="{{ route('frontend.auth.user.artist.manager.podcasts.upload.post', ['id' => $podcast->id]) }}" method="POST" enctype="multipart/form-data">
                <div class="upload-container">
                    <h1>{{ $podcast->title }}</h1>
                    @if(config('settings.ffmpeg'))
                        <p data-translate-text="UPLOAD_ALL_FORMAT_TIP">{{ __('web.UPLOAD_ALL_FORMAT_TIP') }}</p>
                    @else
                        <p data-translate-text="UPLOAD_MP3_TIP">{{ __('web.UPLOAD_MP3_TIP') }}</p>
                    @endif
                    <div id="upload-file-button" class="btn btn-primary">
                        <span data-translate-text="CHOOSE_A_FILE">{{ __('web.CHOOSE_A_FILE') }}</span>
                        <input id="upload-file-input" type="file" accept="audio/*" name="file" multiple>
                    </div>
                </div>
            </form>
            <div class="uploaded-files card-columns card-2-columns"></div>
            <p class="text-center desktop">{!! __('web.LB_UPLOAD_NOTICE') !!}</p>
        </div>
    </div>
    @include('commons.upload-episode-item')
@endsection