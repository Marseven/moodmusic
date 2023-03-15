@extends('index')
@section('pagination')
    @if(count($profile->notifications))
        @include('commons.notification', ['notifications' => $profile->notifications, 'type' => 'full'])
    @else
        <p class="no-notifications" data-translate-text="YOU_HAVE_NO_NOTIFS">{{ __('web.YOU_HAVE_NO_NOTIFS') }}</p>
    @endif
@stop
@section('content')
    @include('profile.nav')
    <div id="page-content">
        <div class="container">
            <div class="page-header user main  small">
                <div class="img"><img src="{{ $profile->artwork_url }}" alt="{{ $profile->name}}" width="40"></div>
                <div class="inner">
                    <h1 title="{{ $profile->username }}" class="">{{ $profile->name }}<span class="subpage-header"> / Notifications</span></h1>
                    @if(isset($profile->group->role_id))
                        <div class="byline"><span>{!! $profile->group->role->name !!}</span></div>
                    @endif
                </div>
            </div>
            <div id="column1" class="full">
                <div class="content">
                    <div id="user-profile-grid" class="notifications notifications-grid">
                        <div id="user-page-notifications" class="infinity-load-more" data-type="notifications" data-element="notification" data-total-page="5">
                            @yield('pagination')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
