@extends('index')
@section('content')
    @include('profile.nav')
    <div id="page-content">
        <div class="container">
            <div class="page-header user main  small">
                <div class="img"><img src="{{ $profile->artwork_url }}" alt="{{ $profile->name}}" width="40"></div>
                <div class="inner">
                    <h1 title="{{ $profile->username }}" class="">{{ $profile->name }}<span class="subpage-header"> / Followers</span></h1>
                    @if(isset($profile->group->role_id))
                        <div class="byline"><span>{!! $profile->group->role->name !!}</span></div>
                    @endif
                </div>
            </div>
            <div id="column1" class="full">
                @if (count($profile->followers))
                    <div class="content">
                        <div class="row">
                            @include('commons.user', ['users' => $profile->followers, 'element' => 'profile'])
                        </div>
                    </div>
                @else
                    <div class="empty-page following">
                        <div class="empty-inner">
                            @if (auth()->check() && auth()->user()->username == $profile->username)
                                <h2 data-translate-text="EMPTY_FOLLOWERS_OWNER">{{ __('web.EMPTY_FOLLOWERS_OWNER') }}</h2>
                                <p data-translate-text="EMPTY_FOLLOWERS_DESC_OWNER">{{ __('web.EMPTY_FOLLOWERS_DESC_OWNER') }}</p>
                            @else
                                <h2 data-translate-text="EMPTY_FOLLOWERS">{{ __('web.EMPTY_FOLLOWERS', ['name' => $profile->name]) }}</h2>
                                <p data-translate-text="EMPTY_FOLLOWERS_DESC">{{ __('web.EMPTY_COLLECTION_DESC') }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection