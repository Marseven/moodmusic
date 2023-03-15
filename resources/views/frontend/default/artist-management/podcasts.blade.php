@extends('index')
@section('content')
    @include('artist-management.nav', ['artist' => $artist])
    <div id="page-content" class="artist">
        <div class="container">
            <div class="page-header artist main small desktop">
                <a class="img">
                    <img src="{{ $artist->artwork_url }}" alt="{!! $artist->name !!}">
                </a>
                <div class="inner">
                    <h1 title="{!! $artist->name !!}">{!! $artist->name !!}<span class="subpage-header"> / Podcast / Shows</span></h1>
                    <div class="byline">Create and manage your album on the website.</div>
                    <div class="actions-primary">
                        <a class="btn import-podcast-rss">
                            <svg height="26" width="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" xml:space="preserve"><path d="M26,0C11.664,0,0,11.663,0,26s11.664,26,26,26s26-11.663,26-26S40.336,0,26,0z M26,50C12.767,50,2,39.233,2,26S12.767,2,26,2s24,10.767,24,24S39.233,50,26,50z"/><path d="M38.5,25H27V14c0-0.553-0.448-1-1-1s-1,0.447-1,1v11H13.5c-0.552,0-1,0.447-1,1s0.448,1,1,1H25v12c0,0.553,0.448,1,1,1s1-0.447,1-1V27h11.5c0.552,0,1-0.447,1-1S39.052,25,38.5,25z"/></svg>
                            <span data-translate-text="IMPORT_PODCAST_RSS">{{ __('web.IMPORT_PODCAST_RSS') }}</span>
                        </a>
                        <a class="btn create-show">
                            <svg height="26" width="14"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 368 368" xml:space="preserve">
                                <path d="M184,0C82.544,0,0,82.544,0,184c0,77.264,48.024,145.736,120,172.392V360c0,4.416,3.584,8,8,8h112c4.424,0,8-3.584,8-8
                                    v-3.608C319.976,329.736,368,261.256,368,184C368,82.544,285.464,0,184,0z M232,299.12v51.76V352h-96v-1.12v-51.76V296
                                    c0-26.472,21.528-48,48-48s48,21.528,48,48V299.12z M136.008,184c0-26.464,21.528-48,48-48s48,21.528,48,48s-21.528,48-48,48
                                    S136.008,210.464,136.008,184z M247.224,286.04c-0.088-0.552-0.232-1.08-0.328-1.624c-0.152-0.84-0.32-1.68-0.512-2.512
                                    c-0.264-1.16-0.56-2.312-0.888-3.448c-0.232-0.8-0.456-1.6-0.72-2.392c-0.376-1.144-0.8-2.256-1.24-3.376
                                    c-0.296-0.744-0.568-1.496-0.888-2.224c-0.504-1.152-1.064-2.264-1.632-3.384c-0.328-0.648-0.632-1.312-0.984-1.944
                                    c-0.704-1.28-1.48-2.512-2.272-3.728c-0.28-0.432-0.52-0.88-0.808-1.304c-1.104-1.624-2.28-3.192-3.52-4.704
                                    c-0.288-0.352-0.608-0.672-0.904-1.016c-0.976-1.136-1.976-2.264-3.032-3.328c-0.512-0.512-1.056-0.992-1.584-1.488
                                    c-0.896-0.848-1.792-1.688-2.736-2.488c-0.616-0.52-1.256-1-1.888-1.496c-0.912-0.712-1.832-1.416-2.776-2.08
                                    c-0.688-0.48-1.392-0.928-2.096-1.384c-0.952-0.608-1.92-1.208-2.904-1.768c-0.208-0.12-0.4-0.256-0.6-0.368
                                    C234.616,229.08,248,208.08,248,184c0-35.288-28.712-64-64-64c-35.288,0-64,28.704-64,64c0,24.08,13.376,45.08,33.08,56
                                    c-0.208,0.112-0.4,0.248-0.6,0.368c-0.992,0.56-1.96,1.16-2.912,1.776c-0.704,0.448-1.4,0.896-2.088,1.376
                                    c-0.952,0.664-1.872,1.368-2.784,2.088c-0.632,0.496-1.272,0.976-1.888,1.488c-0.944,0.792-1.84,1.64-2.736,2.488
                                    c-0.528,0.496-1.072,0.976-1.584,1.488c-1.048,1.064-2.032,2.176-3.008,3.304c-0.304,0.352-0.632,0.68-0.936,1.04
                                    c-1.24,1.512-2.408,3.072-3.512,4.696c-0.304,0.448-0.568,0.936-0.864,1.392c-0.768,1.192-1.52,2.392-2.208,3.632
                                    c-0.36,0.656-0.68,1.336-1.016,2.008c-0.56,1.096-1.104,2.192-1.6,3.32c-0.328,0.744-0.608,1.504-0.904,2.264
                                    c-0.432,1.096-0.848,2.2-1.224,3.328c-0.264,0.8-0.496,1.616-0.728,2.424c-0.32,1.128-0.616,2.264-0.88,3.416
                                    c-0.192,0.84-0.36,1.68-0.512,2.528c-0.104,0.544-0.248,1.072-0.328,1.616C85.472,264.304,64,226.216,64,184
                                    c0-66.168,53.832-120,120-120s120,53.832,120,120C304,226.216,282.528,264.304,247.224,286.04z M248,339.368v-35.44
                                    C292.512,280.256,320,234.8,320,184c0-74.992-61.008-136-136-136S48,109.008,48,184c0,50.8,27.488,96.256,72,119.928v35.44
                                    C57.352,313.648,16,252.56,16,184C16,91.36,91.36,16,184,16c92.632,0,168,75.36,168,168C352,252.56,310.648,313.648,248,339.368z"
                                />
                            </svg>
                            <span data-translate-text="CREATE_SHOW">{{ __('web.CREATE_SHOW') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div id="column1" class="content full">
                <h2 class="text-center">{{ __('web.LB_UPLOAD_SHOW_SELECT_DESC') }}</h2>
                @if(count($artist->podcasts))
                    <div id="grid-toolbar-container">
                        <div class="grid-toolbar">
                            <div class="grid-toolbar-inner">
                                <ul class="actions primary"> </ul>
                                <ul class="actions secondary">
                                    <li>
                                        <form class="search-bar"> <i class="icon search icon-search-m-gray-flat"></i> <input autocomplete="off" value="" name="q" class="filter" id="filter-search" type="text" placeholder="Filter"> <a class="icon ex icon-circle-ex-l-gray-flat clear-filter"></a> <a class="remove hide"></a>
                                            <ul id="page_search_results"></ul>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="grid" class="row">
                        @foreach($artist->podcasts as $podcast)
                            <div class="module module-cell small grid-item">
                                <div class="img-container">
                                    <img class="img" src="{{ $podcast->artwork_url }}" alt="{{ $podcast->title }}">
                                    <a class="overlay-link" href="{{ route('frontend.auth.user.artist.manager.podcasts.show', ['id' => $podcast->id]) }}"></a>
                                    <div class="actions primary">
                                        <a class="btn btn-secondary btn-icon-only btn-options" data-toggle="contextmenu" data-trigger="left" data-type="podcast" data-id="{{ $podcast->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                        </a>
                                        <a class="btn btn-secondary btn-icon-only btn-rounded btn-play play-or-add play-object" data-type="podcast" data-id="{{ $podcast->id }}">
                                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" height="26" width="20"><path d="M8 5v14l11-7z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                                        </a>
                                    </div>
                                    @if(!$podcast->approved)
                                        <div class="status" data-translate-text="WAITING_FOR_APPROVAL">{{ __('web.WAITING_FOR_APPROVAL') }}</div>
                                    @endif
                                </div>
                                <div class="module-inner">
                                    <a href="{{ route('frontend.auth.user.artist.manager.podcasts.show', ['id' => $podcast->id]) }}" class="headline">{{ $podcast->title }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-page followers">
                        <div class="empty-inner">
                            <h2>{!! $artist->name !!} didn't create any show yet.</h2>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection