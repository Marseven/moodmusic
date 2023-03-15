@extends('index')
@section('content')
    @include('artist-management.nav', ['artist' => $artist])
    <div id="page-content">
        <div class="container">
            <div class="page-header artist main small desktop">
                <a class="img ">
                    <img src="{{ $artist->artwork_url }}" alt="{{ $artist->name}}">
                </a>
                <div class="inner">
                    <h1 title="{!! $artist->name !!}">{!! $artist->name !!}<span class="subpage-header"> / Events</span></h1>
                    <div class="byline">Events let you organize and respond to gatherings in the real world your fans.</div>
                    <div class="actions-primary">
                        <a class="btn create-event">
                            <svg height="26" width="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.667 426.667" xml:space="preserve"><path d="M362.667,42.667h-21.333V0h-42.667v42.667H128V0H85.333v42.667H64c-23.573,0-42.453,19.093-42.453,42.667L21.333,384c0,23.573,19.093,42.667,42.667,42.667h298.667c23.573,0,42.667-19.093,42.667-42.667V85.333C405.333,61.76,386.24,42.667,362.667,42.667z M362.667,384H64V149.333h298.667V384z"/><polygon points="309.973,214.613 287.36,192 183.253,296.107 138.027,250.88 115.413,273.493 183.253,341.333 			"/></svg>
                            <span class="desktop" data-translate-text="CREATE_EVENT">{{ __('web.CREATE_EVENT') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div id="column1" class="full">
                <ul class="snapshot">
                    @foreach($artist->events as $event)
                        <script>var event_data_{{ $event->id }} = {!! json_encode($event) !!}</script>
                        <li class="module module-row event tall">
                            <div class="date">
                                <span class="month">{{ \Carbon\Carbon::parse($event->started_at)->format('M') }}</span>
                                <span class="day">{{ \Carbon\Carbon::parse($event->started_at)->format('d') }}</span>
                            </div>
                            <div class="metadata">
                                <a href="{{ $event->link }}" target="_blank" class="title event-link">{{ $event->title }}</a>
                                <div class="meta-inner">{{ $event->location }}</div>
                            </div>
                            <div class="row-actions secondary">
                                <a class="btn options event-row-edit" data-type="event" data-id="{{ $event->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                                </a>
                                <a class="btn options event-row-delete" data-type="event" data-id="{{ $event->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection