@extends('index')
@section('content')
    {!! Advert::get('header') !!}
    <div id="page-content" class="home">
        <div class="page-header no-separator desktop">
            <h1 data-translate-text="RADIO">{{ __('web.RADIO') }}</h1>
            <div class="byline">
                <span>Listen to live radio stations worldwide.</span>
            </div>
        </div>
        <div id="column1" class="full">
            @include('commons.slideshow', ['slides' => $slides, 'style' => 'featured'])
            @include('commons.channel', ['channels' => $channels])
            <div class="content home-section">
                <div class="sub-header">
                    <h2 class="section-title">
                        <span data-translate-text="">Categories</span>
                    </h2>
                </div>
                <div class="home-content-container ml-0 mr-0">
                    <div id="grid" class="genre">
                        @foreach ($radio as $index => $genre)
                            <div class="module module-cell small-radio-cat">
                                <div class="img-container" style="background: url({{$genre->artwork_url}})">
                                    <div class="module-inner">
                                        <a class="title" href="{{$genre->permalink_url}}" title="{{$genre->name}}">
                                            <span>{{$genre->name}}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if(isset($radio->regions) && count($radio->regions))
                <div class="content home-section">
                    <div class="sub-header">
                        <h2 class="section-title">
                            <span data-translate-text="">By location</span>
                        </h2>
                    </div>
                    <div class="home-content-container ml-0 mr-0">
                        <ul class="tag-cloud-container">
                            @foreach ($radio->regions as $index => $item)
                                <a class="tag-cloud-item" href="{{ route('frontend.radio.browse.by.region', ['slug' => $item->alt_name]) }}" title="{{ $item->name }}">{{$item->name}}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(isset($radio->countries) && count($radio->countries))
                <div class="content home-section">
                    <div class="sub-header">
                        <h2 class="section-title">
                            <span data-translate-text="">By country</span>
                        </h2>
                        <div class="actions-primary">
                            <a class="btn" href="{{ route('frontend.radio.browse.countries') }}">
                                <span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="home-content-container ml-0 mr-0">
                        <div id="grid" class="genre">
                            @foreach ($radio->countries as $index => $item)
                                <div class="module module-cell small-radio-cat">
                                    <div class="img-container" style="background: url({{$item->artwork_url}})">
                                        <div class="module-inner">
                                            <a class="title" href="{{ route('frontend.radio.browse.by.country', ['code' => $item->code]) }}" title="{{$item->name}}">
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

            @if(isset($radio->cities) && count($radio->cities))
                <div class="content home-section">
                    <div class="sub-header">
                        <h2 class="section-title">
                            <span data-translate-text="">By city</span>
                        </h2>
                    </div>
                    <div class="home-content-container ml-0 mr-0">
                        <ul class="tag-cloud-container">
                            @foreach ($radio->cities as $index => $item)
                                <a class="tag-cloud-item" href="{{ route('frontend.radio.browse.by.city', ['id' => $item->id]) }}" title="{{ $item->name }}">{{$item->name}}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif


            @if(isset($radio->languages) && count($radio->languages))
                <div class="content home-section">
                    <div class="sub-header">
                        <h2 class="section-title">
                            <span data-translate-text="">By languages</span>
                        </h2>
                        <div class="actions-primary">
                            <a class="btn" href="{{ route('frontend.radio.browse.languages') }}">
                                <span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="home-content-container ml-0 mr-0">
                        <ul class="tag-cloud-container">
                            @foreach ($radio->languages as $index => $item)
                                <a class="tag-cloud-item" href="{{ route('frontend.radio.browse.by.language', ['id' => $item->id]) }}" title="{{ $item->name }}">{{$item->name}}</a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
    {!! Advert::get('footer') !!}
@endsection