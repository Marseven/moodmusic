@extends('index')
@section('content')
    <div id="page-content">
        <div class="page-header no-separator desktop">
            <h1 data-translate-text="PODCASTS">{{ __('web.PODCASTS') }}</h1>
            <div class="byline">
                <span data-translate-text="PODCASTS_TIP">{{ __('web.PODCASTS_TIP') }}</span>
            </div>
        </div>
        <div id="column1" class="full">
            @include('commons.slideshow', ['slides' => $slides, 'style' => 'featured'])
            @include('commons.channel', ['channels' => $channels])
            <div class="content home-section">
                <div class="sub-header">
                    <h2 class="section-title">
                        <span data-translate-text="CATEGORIES">{{ __('web.CATEGORIES') }}</span>
                    </h2>
                </div>
                <div class="home-content-container ml-0 mr-0">
                    <div id="grid" class="genre">
                        @foreach ($podcasts->categories as $index => $category)
                            <div class="module module-cell small-radio-cat">
                                <div class="img-container" style="background: url({{$category->artwork_url}})">
                                    <div class="module-inner">
                                        <a class="title" href="{{$category->permalink_url}}" title="{{$category->name}}">
                                            <span>{!! $category->name !!}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if(isset($podcasts->regions) && count($podcasts->regions))
                <div class="content home-section">
                    <div class="sub-header">
                        <h2 class="section-title">
                            <span data-translate-text="BY_LOCATION">{{ __('web.BY_LOCATION') }}</span>
                        </h2>
                    </div>
                    <div class="home-content-container ml-0 mr-0">
                        <ul class="tag-cloud-container">
                            @foreach ($podcasts->regions as $index => $item)
                                <a class="tag-cloud-item" href="{{ route('frontend.podcasts.browse.by.region', ['slug' => $item->alt_name]) }}" title="{{ $item->name }}">{{$item->name}}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(isset($podcasts->countries) && count($podcasts->countries))
                <div class="content home-section">
                    <div class="sub-header">
                        <h2 class="section-title">
                            <span data-translate-text="BY_COUNTRY">{{ __('web.BY_COUNTRY') }}</span>
                        </h2>
                        <div class="actions-primary">
                            <a class="btn" href="{{ route('frontend.podcasts.browse.countries') }}">
                                <span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="home-content-container ml-0 mr-0">
                        <div id="grid" class="genre">
                            @foreach ($podcasts->countries as $index => $item)
                                <div class="module module-cell small-radio-cat">
                                    <div class="img-container" style="background: url({{$item->artwork_url}})">
                                        <div class="module-inner">
                                            <a class="title" href="{{ route('frontend.podcasts.browse.by.country', ['code' => $item->code]) }}" title="{{$item->name}}">
                                                <span>{{ $item->name }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if(isset($podcasts->languages) && count($podcasts->languages))
                <div class="content home-section">
                    <div class="sub-header">
                        <h2 class="section-title">
                            <span data-translate-text="BY_LANGUAGE">{{ __('web.BY_LANGUAGE') }}</span>
                        </h2>
                        <div class="actions-primary">
                            <a class="btn" href="{{ route('frontend.podcasts.browse.languages') }}">
                                <span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="home-content-container ml-0 mr-0">
                        <ul class="tag-cloud-container">
                            @foreach ($podcasts->languages as $index => $item)
                                <a class="tag-cloud-item" href="{{ route('frontend.podcasts.browse.by.language', ['id' => $item->id]) }}" title="{{ $item->name }}">{{$item->name}}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection