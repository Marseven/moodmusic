@extends('index')
@section('pagination')
    @if(isset($browse->podcasts))
        @include('commons.podcast', ['podcasts' => $browse->podcasts, 'element' => 'genre'])
    @endif
@stop
@section('content')
    <div id="page-content">
        <div class="container">
            <div class="page-header tag-header small desktop">
                <div class="inner">
                    @if(isset($browse->category))
                        <h1>
                            <span title="{{ $browse->category->name }}">{{ $browse->category->name }}</span>
                        </h1>
                        <div class="byline"><a href="{{ route('frontend.podcasts') }}"><span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span></a></div>
                    @endif
                    @if(Route::currentRouteName() == 'frontend.podcasts.browse.by.region')
                        <h1>
                            <span title="{{ $browse->region->name }}">{{ $browse->region->name }}</span>
                        </h1>
                        <div class="byline">
                            <a href="{{ route('frontend.podcasts.browse.regions') }}"><span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span></a>
                        </div>
                        @elseif(Route::currentRouteName() == 'frontend.podcasts.browse.regions')
                            <h1>
                                <span title="By location">By location</span>
                            </h1>
                            <div class="byline">
                                <a href="{{ route('frontend.podcasts') }}"><span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span></a>
                            </div>
                        @elseif(Route::currentRouteName() == 'frontend.podcasts.browse.countries')
                            <h1>
                                <span title="By countries">By countries</span>
                            </h1>
                            <div class="byline">
                                <a href="{{ route('frontend.podcasts') }}"><span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span></a>
                            </div>
                    @elseif(Route::currentRouteName() == 'frontend.podcasts.browse.by.country')
                        <h1>
                            <span title="{{ $browse->country->name }}">{{ $browse->country->name }}</span>
                        </h1>
                        <div class="byline">
                            <a href="{{ route('frontend.podcasts.browse.countries') }}"><span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span></a>
                        </div>
                    @elseif(Route::currentRouteName() == 'frontend.podcasts.browse.by.language')
                        <h1>
                            <span title="{{ $browse->language->name }}">{{ $browse->language->name }}</span>
                        </h1>
                        <div class="byline">
                            <a href="{{ route('frontend.podcasts') }}"><span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span></a>
                        </div>
                    @elseif(Route::currentRouteName() == 'frontend.podcasts.browse.by.city')
                        <h1>
                            <span title="{{ $browse->city->name }}">{{ $browse->city->name }}</span>
                        </h1>
                        <div class="byline">
                            <a href="{{ route('frontend.podcasts.browse.by.country', ['code' => $browse->city->country->code]) }}"><span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span></a>
                        </div>
                    @endif
                </div>
            </div>
            @if(isset($browse->slides))
                @include('commons.slideshow', ['slides' => $browse->slides])
            @endif
            @if(isset($browse->channels))
                @include('commons.channel', ['channels' => $browse->channels])
            @endif
            @if(isset($browse->regions) && count($browse->regions))
                <div class="content home-section">
                    <div class="sub-header">
                        <h2 class="section-title">
                            <span data-translate-text="">By regions</span>
                        </h2>
                    </div>
                    <div class="home-content-container ml-0 mr-0">
                        <ul class="tag-cloud-container">
                            @foreach ($browse->regions as $index => $item)
                                <a class="tag-cloud-item" href="{{ route('frontend.podcasts.browse.by.region', ['slug' => $item->alt_name]) }}" title="{{ $item->name }}">{{$item->name}}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(isset($browse->countries) && count($browse->countries))
                <div class="content home-section">
                    <div class="sub-header">
                        <h2 class="section-title">
                            <span data-translate-text="">By country</span>
                        </h2>
                    </div>
                    <div class="home-content-container ml-0 mr-0">
                        <ul class="tag-cloud-container">
                            @foreach ($browse->countries as $index => $country)
                                <a class="tag-cloud-item" href="{{ route('frontend.podcasts.browse.by.country', ['code' => $country->code]) }}" title="{{ $country->name }}">{{$country->name}}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(isset($browse->languages) && count($browse->languages))
                <div class="content home-section">
                    <div class="sub-header">
                        <h2 class="section-title">
                            <span data-translate-text="">By languages</span>
                        </h2>
                    </div>
                    <div class="home-content-container ml-0 mr-0">
                        <ul class="tag-cloud-container">
                            @foreach ($browse->languages as $index => $language)
                                <a class="tag-cloud-item" href="{{ route('frontend.podcasts.browse.by.language', ['id' => $language->id]) }}" title="{{ $language->name }}">{{$language->name}}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(isset($browse->podcasts))
                @include('commons.toolbar.podcast')
                <div id="shows-grid" class="grid-view items-filter-able infinity-load-more">
                    @yield('pagination')
                </div>
            @endif
        </div>
    </div>
@endsection