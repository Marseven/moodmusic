@extends('index')
@section('content')
    <script>var station_data_{{ $station->id }} = {!! json_encode($station) !!}</script>
    <div id="page-content" class="bluring-helper">
        <div class="container">
            <div class="blurimg">
                <img src="{{ $station->artwork_url }}" alt="{{ $station->title }}">
            </div>
            <div class="page-header song main medium ">
                <div class="img">
                    <img src="{{ $station->artwork_url }}">
                </div>
                <div class="inner">
                    <h1 title="{{ $station->title }}">{!! $station->title !!} </h1>
                    <div class="byline show-full">
                        <span>{{ $station->description }}</span>
                    </div>
                    <div class="actions-primary">
                        <a class="btn awesome-play-button play-object desktop" data-type="station" data-id="{{ $station->id }}">
                            <svg height="26" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                            <span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span>
                        </a>
                        <a class="btn play play-object mobile" data-type="station" data-id="{{ $station->id }}">
                            <span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div id="column1">
                <div class="content share-content">
                    <div class="share-box">
                        <span class="third-party-btn-head" data-translate-text="SHARE_WITH_FRIENDS">Share With Friends:</span>
                        <a class="btn share-btn third-party twitter" target="_blank" href="https://twitter.com/intent/tweet?url={{ $station->permalink_url }}">
                            <svg class="icon" width="24" height="32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 510 510" xml:space="preserve"><path d="M459,0H51C22.95,0,0,22.95,0,51v408c0,28.05,22.95,51,51,51h408c28.05,0,51-22.95,51-51V51C510,22.95,487.05,0,459,0z M400.35,186.15c-2.55,117.3-76.5,198.9-188.7,204C165.75,392.7,132.6,377.4,102,359.55c33.15,5.101,76.5-7.649,99.45-28.05c-33.15-2.55-53.55-20.4-63.75-48.45c10.2,2.55,20.4,0,28.05,0c-30.6-10.2-51-28.05-53.55-68.85c7.65,5.1,17.85,7.65,28.05,7.65c-22.95-12.75-38.25-61.2-20.4-91.8c33.15,35.7,73.95,66.3,140.25,71.4c-17.85-71.4,79.051-109.65,117.301-61.2c17.85-2.55,30.6-10.2,43.35-15.3c-5.1,17.85-15.3,28.05-28.05,38.25c12.75-2.55,25.5-5.1,35.7-10.2C425.85,165.75,413.1,175.95,400.35,186.15z"></path></svg>
                            <span class="text" data-translate-text="SHARE_ON_TWITTER">{{ __('web.SHARE_ON_TWITTER') }}</span></a>
                        <a class="btn share-btn third-party facebook" target="_blank" href="https://www.facebook.com/share.php?u={{ $station->permalink_url }}&ref=songShare">
                            <svg class="icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" xml:space="preserve"><path d="M448,0H64C28.704,0,0,28.704,0,64v384c0,35.296,28.704,64,64,64h192V336h-64v-80h64v-64c0-53.024,42.976-96,96-96h64v80h-32c-17.664,0-32-1.664-32,16v64h80l-32,80h-48v176h96c35.296,0,64-28.704,64-64V64C512,28.704,483.296,0,448,0z"></path></svg>
                            <span class="text" data-translate-text="SHARE_ON_FACEBOOK">Share on Facebook</span>
                        </a>
                        <a class="btn share-btn more-menu share" data-type="station" data-id="{{ $station->id }}">
                            <span class="btn-text" data-translate-text="MORE">{{ __('web.MORE') }}</span>
                        </a>
                    </div>
                </div>
                <div class="comments-container">
                    <div class="sub-header">
                        <h2 data-translate-text="COMMENTS">Comments</h2>
                    </div>
                    <div id="comments">
                        @include('comments.index', ['object' => (Object) ['id' => $station->id, 'type' => 'App\Models\Station', 'title' => $station->title]])
                    </div>
                </div>
                <div id="song-videos" class="content"></div>
            </div>
            <div id="column2" class="about">
                {!! Advert::get('sidebar') !!}
                <div id="artist-top-songs" class="content">
                    <div class="sub-header">
                        <h3 data-translate-text="RELATED_STATION">Related stations</h3>
                    </div>
                    <ul class="snapshot">
                        @include('commons.station', ['stations' => $station->related, 'element' => 'search'])
                    </ul>
                    <div class="divider"></div>
                </div>
            </div>
        </div>
    </div>
@endsection