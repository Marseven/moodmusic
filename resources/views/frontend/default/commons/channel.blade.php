@if(isset($channels) && count($channels))
    @foreach ($channels as $channel)
        @if($channel->objects != null && count($channel->objects->data))
            <div class="content home-section">
                <div class="sub-header @if($channel->object_type == 'song') can-play @endif">
                    @if($channel->object_type == 'song')
                        <a class="btn btn-icon-only btn-rounded play-section play-now" data-target="#channel-{{ $channel->id }}">
                            <svg height="40" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 5v14l11-7z"/>
                                <path d="M0 0h24v24H0z" fill="none"/>
                            </svg>
                        </a>
                    @endif
                    <h2 class="section-title">
                        <span data-translate-text="">{{ $channel->title }}</span>
                    </h2>
                    @if($channel->description)
                        <span class="section-tagline">{{ $channel->description }}</span>
                    @endif
                    <div class="actions-primary">
                        <a class="btn" href="{{ $channel->permalink_url }}">
                            <span data-translate-text="SEE_ALL">{{ __('web.SEE_ALL') }}</span>
                        </a>
                    </div>
                </div>
                <div class="home-content-container">
                    @if(config('settings.channel_grid_style'))
                        <div  class="grid-view">
                            @if($channel->object_type == 'song')
                                @include('commons.song', ['songs' => $channel->objects->data, 'element' => 'carousel'])
                            @elseif($channel->object_type == 'artist')
                                @include('commons.artist', ['artists' => $channel->objects->data, 'element' => 'carousel'])
                            @elseif($channel->object_type == 'album')
                                @include('commons.album', ['albums' => $channel->objects->data, 'element' => 'carousel'])
                            @elseif($channel->object_type == 'playlist')
                                @include('commons.playlist', ['playlists' => $channel->objects->data, 'element' => 'carousel'])
                            @elseif($channel->object_type == 'station')
                                @include('commons.station', ['stations' => $channel->objects->data, 'element' => 'carousel'])
                            @elseif($channel->object_type == 'user')
                                @include('commons.user', ['users' => $channel->objects->data, 'element' => 'carousel'])
                            @elseif($channel->object_type == 'podcast')
                                @include('commons.podcast', ['podcasts' => $channel->objects->data, 'element' => 'carousel'])
                            @elseif($channel->object_type == 'video')
                                @include('commons.video', ['videos' => $channel->objects->data, 'element' => 'carousel'])
                            @endif
                        </div>
                    @else
                        <div class="swiper-container-channel">
                            <div id="channel-{{ $channel->id }}" class="swiper-wrapper">
                                @if($channel->object_type == 'song')
                                    @include('commons.song', ['songs' => $channel->objects->data, 'element' => 'carousel'])
                                @elseif($channel->object_type == 'artist')
                                    @include('commons.artist', ['artists' => $channel->objects->data, 'element' => 'carousel'])
                                @elseif($channel->object_type == 'album')
                                    @include('commons.album', ['albums' => $channel->objects->data, 'element' => 'carousel'])
                                @elseif($channel->object_type == 'playlist')
                                    @include('commons.playlist', ['playlists' => $channel->objects->data, 'element' => 'carousel'])
                                @elseif($channel->object_type == 'station')
                                    @include('commons.station', ['stations' => $channel->objects->data, 'element' => 'carousel'])
                                @elseif($channel->object_type == 'user')
                                    @include('commons.user', ['users' => $channel->objects->data, 'element' => 'carousel'])
                                @elseif($channel->object_type == 'podcast')
                                    @include('commons.podcast', ['podcasts' => $channel->objects->data, 'element' => 'carousel'])
                                @elseif($channel->object_type == 'video')
                                    @include('commons.video', ['videos' => $channel->objects->data, 'element' => 'carousel'])
                                @endif
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
        @endif
    @endforeach
@endif