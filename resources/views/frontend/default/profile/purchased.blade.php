@extends('index')
@section('content')
    @include('homepage.nav')
    <div id="page-content" class="mb-5">
        <div class="container">
            <div class="page-header no-separator desktop">
                <h1 data-translate-text="PURCHASED">{{ __('web.PURCHASED') }}</h1>
            </div>
            @if(count($profile->purchased))
                @foreach($profile->purchased AS $item)
                    @if(isset($item->object))
                        @if($item->orderable_type === 'App\Models\Album')
                            <h1 class="time-purchased-h1 text-secondary mb-2 mt-5">{{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y h:i') }}</h1>
                            <div class="purchased-album-header">
                                <img class="purchased-album-cover" src="{{ $item->object->artwork_url }}" title="{{ $item->object->title }}" alt="{{ $item->object->title }}">
                                <div class="purchased-album-info">
                                    <p class="purchased-album-artist">{{ __('web.ALBUM') }}</p>
                                    <span class="css-bsi9cv">{{ $item->object->title }}</span>
                                    <p class="purchased-album-artist">
                                        @foreach($item->object->artists as $artist)<a href="{{ $artist->permalink_url }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach
                                    </p>
                                    <!-- <button class="btn btn-primary"><span>{{ __('web.DOWNLOAD') }}</span></button> -->
                                </div>
                            </div>
                            <div class="mb-5">
                                @foreach($item->object->songs()->get() AS $song)
                                    @include('commons.purchased_song', ['song' => $song])
                                @endforeach
                            </div>
                        @else
                            <h1 class="time-purchased-h1 text-secondary mb-2 mt-5">{{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y h:i') }}</h1>
                            @include('commons.purchased_song', ['song' => $item->object])
                        @endif
                    @endif
                @endforeach
            @else
                <div class="empty-page following">
                    <div class="empty-inner">
                        <h2 data-translate-text="YOU_DIDNT_BOUGHT_ANYTHING_YET">{{ __('web.YOU_DIDNT_BOUGHT_ANYTHING_YET') }}</h2>
                        <p data-translate-text="YOU_DIDNT_BOUGHT_TIP">{{ __('web.YOU_DIDNT_BOUGHT_TIP') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection