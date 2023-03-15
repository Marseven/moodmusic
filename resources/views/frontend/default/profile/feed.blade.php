@extends('index')
@section('content')
    @include('profile.nav')
    <div id="page-content">
        <div class="container">
            <div class="page-header user main  small">
                <div class="img"><img src="{{ $profile->artwork_url }}" alt="{{ $profile->name}}" width="40"></div>
                <div class="inner">
                    <div class="actions-primary">
                        <a class="btn play-station" data-type="user" data-id="{{ $profile->id }}">
                            <i class="icon icon-station-gray"></i>
                            <span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span>
                        </a>
                    </div>
                    <h1 title="{{ $profile->username }}" class="">{{ $profile->name }}<span class="subpage-header"> / News Feed</span></h1>
                    @if(isset($profile->group->role_id))
                        <div class="byline"><span>{!! $profile->group->role->name !!}</span></div>
                    @endif
                </div>
            </div>
            <div id="column1" class="full">
                <div id="news-feed" class="content">
                    <div id="community" class="content">
                        @include('commons.activity', ['activities' => $profile->feed, 'type' => 'full'])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection