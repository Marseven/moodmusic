<div class="content home-section">
    <div class="sub-header can-play">
        <a class="btn btn-icon-only btn-rounded play-section play-now" data-target="#recommended-{{ $type }}">
            <svg height="40" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 5v14l11-7z"/>
                <path d="M0 0h24v24H0z" fill="none"/>
            </svg>
        </a>
        <h2 class="section-title">
            {!! $title !!}
        </h2>
        {!! $description !!}
        <div class="actions-primary">
            <a class="btn play-station desktop" data-type="{{ $type }}" data-id="{{ auth()->user()->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><circle cx="6.18" cy="17.82" r="2.18"/><path d="M4 4.44v2.83c7.03 0 12.73 5.7 12.73 12.73h2.83c0-8.59-6.97-15.56-15.56-15.56zm0 5.66v2.83c3.9 0 7.07 3.17 7.07 7.07h2.83c0-5.47-4.43-9.9-9.9-9.9z"/></svg>
                <span data-translate-text="PLAY_STATION">{{ __('web.PLAY_STATION') }}</span>
            </a>
            <a class="btn" href="{{ $more_link }}">
                <span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span>
            </a>
        </div>
    </div>
    <div class="home-content-container">
        @if(config('settings.channel_grid_style'))
            <div  class="grid-view">
                @include('commons.song', ['songs' => $songs, 'element' => 'carousel'])
            </div>
        @else
            <div class="swiper-container swiper-container-channel">
                <div id="recommended-{{ $type }}" class="swiper-wrapper">
                    @include('commons.song', ['songs' => $songs, 'element' => 'carousel'])
                </div>
            </div>
            <a class="home-pageable-nav previous-pageable-nav swiper-arrow-left">
                <div class="icon pagable-icon">
                    <svg height="16" viewBox="0 0 501.5 501.5" width="16" xmlns="http://www.w3.org/2000/svg"><g><path d="M302.67 90.877l55.77 55.508L254.575 250.75 358.44 355.116l-55.77 55.506L143.56 250.75z"></path></g></svg>
                </div>
            </a>
            <a class="home-pageable-nav next-pageable-nav swiper-arrow-right">
                <div class="icon pagable-icon">
                    <svg height="16" viewBox="0 0 501.5 501.5" width="16" xmlns="http://www.w3.org/2000/svg"><g><path d="M302.67 90.877l55.77 55.508L254.575 250.75 358.44 355.116l-55.77 55.506L143.56 250.75z"></path></g></svg>
                </div>
            </a>
        @endif
    </div>
</div>