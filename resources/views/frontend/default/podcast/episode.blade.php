@extends('index')
@section('content')
    <script>var episode_data_{{ $episode->id }} = {!! json_encode($episode) !!}</script>
    <div id="page-content" class="bluring-helper">
        <div class="container">
            <div class="blurimg">
                <img src="{{ $episode->artwork_url }}" alt="{{ $episode->title }}">
            </div>
            <div class="row podcast-show">
                <div class="col-lg-4 col-12">
                    <div class="img">
                        <img id="page-cover-art" src="{{ $episode->artwork_url }}" alt="{{ $episode->title }}">
                        <div class="inner mobile">
                            <h1 title="{{ $episode->title }}">{{ $episode->title }}</h1>
                            @if(!$episode->visibility)<span class="private" data-translate-text="PRIVATE">{{ __('web.PRIVATE') }}</span>@endif
                            <div class="byline">
                                @if($episode->explicit)
                                    <span class="explicit">E</span>
                                @endif
                                <a href="{{ $episode->podcast->permalink_url }}">{{ $episode->podcast->title }}</a>
                            </div>
                            <div class="actions-primary">
                                <a class="btn share desktop" data-type="episode" data-id="{{ $episode->id }}">
                                    <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                                    <span data-translate-text="SHARE">{{ __('web.SHARE') }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="podcast-artwork-caption text-secondary desktop">
                            <div class="podcast-total-episodes">
                                {{ intval($episode->duration/60) }} {{ __('web.MIN') }}
                            </div>
                            <a class="episode-report d-flex align-items-center text-secondary" data-action="report" data-type="episode" data-id="{{ $episode->id }}">
                                <span data-translate-text="REPORT">Report</span>
                            </a>
                        </div>
                    </div>
                    <div class="podcast-description">
                        {{ $episode->description }}
                    </div>
                </div>
                <div class="col-lg-8 col-12">
                    <div class="inner desktop">
                        <h1 title="{{ $episode->title }}">{{ $episode->title }}</h1>
                        @if(!$episode->visibility)<span class="private" data-translate-text="PRIVATE">{{ __('web.PRIVATE') }}</span>@endif
                        <div class="byline">
                            @if($episode->explicit)
                                <span class="explicit">E</span>
                            @endif
                            <a href="{{ $episode->podcast->permalink_url }}">{{ $episode->podcast->title }}</a>
                            @if($episode->season)
                                 • Season : {{ $episode->season }}#
                            @endif
                            @if($episode->number)
                                • Episode : {{ $episode->number }}#
                            @endif
                        </div>
                        <div class="actions-primary">
                            <div class="btn-group desktop">
                                <a class="btn play play-object" data-type="episode" data-id="{{ $episode->id }}">
                                    <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                    <span data-translate-text="PLAY">{{ __('web.PLAY') }}</span>
                                </a>
                            </div>
                            <a class="btn share desktop" data-type="episode" data-id="{{ $episode->id }}">
                                <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                                <span class="desktop" data-translate-text="SHARE">{{ __('web.SHARE') }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="podcast-comments">
                        @if($episode->allow_comments)
                            @include('comments.index', ['object' => (Object) ['id' => $episode->id, 'type' => 'App\Models\Episode', 'title' => $episode->title]])
                        @else
                            <p class="text-center mt-5">Comments are turned off.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection