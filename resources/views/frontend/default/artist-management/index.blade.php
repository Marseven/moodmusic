@extends('index')
@section('content')
    @include('artist-management.nav', ['artist' => $artist])
    <div id="page-content">
        <div class="container">
            <div class="page-header artist-management main">
                <div class="img">
                    <img src="{{ $artist->artwork_url }}" alt="{{ $artist->name}}">
                </div>
                <div class="inner">
                    <h1 title="{!! $artist->name !!}">{!! $artist->name !!}</h1>
                    <div class="actions-primary">
                        <a class="btn edit-profile" href="{{ route('frontend.auth.user.artist.manager.profile') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="26" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                            <span data-translate-text="EDIT">{{ __('web.EDIT') }}</span>
                        </a>
                        <a class="btn create-album">
                            <svg height="26" width="14" viewBox="0 0 511.334 511.334" xmlns="http://www.w3.org/2000/svg"><path d="m436.667 21c0-11.598-9.402-21-21-21h-394.667c-11.598 0-21 9.402-21 21v394.667c0 11.598 9.402 21 21 21s21-9.402 21-21v-373.667h373.667c11.598 0 21-9.402 21-21z"/><path d="m490.333 74.667h-394.666c-11.598 0-21 9.402-21 21v394.667c0 11.598 9.402 21 21 21h394.667c11.598 0 21-9.402 21-21v-394.667c-.001-11.598-9.402-21-21.001-21zm-21 394.667h-352.666v-352.667h352.667v352.667z"/><path d="m255.667 404.667c35.106 0 63.667-28.561 63.667-63.667 0-10.433 0-84.548 0-94.021l33.608 16.805c10.373 5.184 22.987.981 28.175-9.392 5.187-10.374.982-22.988-9.392-28.175l-64-32c-13.939-6.967-30.392 3.176-30.392 18.783v64.334h-21.667c-35.105 0-63.666 28.561-63.666 63.666 0 35.106 28.561 63.667 63.667 63.667zm0-85.333h21.667v21.666c0 11.947-9.72 21.667-21.667 21.667s-21.667-9.72-21.667-21.667c0-11.946 9.72-21.666 21.667-21.666z"/></svg>
                            <span data-translate-text="CREATE_ALBUM">{{ __('web.CREATE_ALBUM') }}</span>
                        </a>
                        <a class="btn share" data-type="artist" data-id="{{ $artist->id }}">
                            <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"/><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                            <span data-translate-text="SHARE">{{ __('web.SHARE') }}</span>
                        </a>
                        <a class="btn" data-action="withdraw" data-min="{{ \App\Models\Role::getUserValue('monetization_paypal_min_withdraw', auth()->user()->id) }}" data-max="{{ number_format(auth()->user()->balance, 2) }}">
                            <svg height="26" width="14" viewBox="0 0 511.854 511.854" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m480.927 190.854c16.542 0 30-13.458 30-30v-38.844c0-12.317-7.377-23.234-18.8-27.831l-224.952-91.98c-7.325-2.951-15.252-2.899-22.391-.042-.166.067 3.765-1.54-225.058 92.023-11.423 4.596-18.8 15.514-18.8 27.831v38.845c0 16.542 13.458 30 30 30h18v226h-18c-16.542 0-30 13.458-30 30v35c0 16.542 13.458 30 30 30h450c16.542 0 30-13.458 30-30v-35c0-16.542-13.458-30-30-30h-18v-226h18.001zm0 256c.019 35.801.1 35 0 35h-450v-35zm-402-30v-226h34v226zm64 0v-226h66v226zm96 0v-226h34v226zm64 0v-226h66v226zm96 0v-226h34v226zm-368-256c0-41.843-.045-38.826.105-38.887l224.895-91.957 224.895 91.957c.155.062.105-2.857.105 38.887-4.986 0-444.075 0-450 0z"/></g><g><path d="m255.927 64.854c-8.284 0-15 6.716-15 15v32c0 8.284 6.716 15 15 15s15-6.716 15-15v-32c0-8.284-6.716-15-15-15z"/></g></g></svg>
                            <span data-translate-text="WITHDRAW">{{ __('web.WITHDRAW') }}</span>
                        </a>
                    </div>
                    <div class="description">
                        <p id="user-bio"></p>
                    </div>
                    <ul class="stat-summary">
                        <li><a class="basic-tooltip" tooltip="For both sales and streaming"><span class="num">{{ __('symbol.' . config('settings.currency', 'USD')) }}{{ number_format(auth()->user()->balance, 2) }}</span><span class="label" data-translate-text="BALANCE">{{ __('web.BALANCE') }}</span></a></li>
                        <li><a href="{{ route('frontend.auth.user.artist.manager.uploaded') }}"><span id="song-count" class="num">{{ $artist->song_count }}</span><span class="label" data-translate-text="UPLOADED">Uploaded</span></a></li>
                        <li><a href="{{ route('frontend.auth.user.artist.manager.albums') }}"><span id="album-count" class="num">{{ $artist->album_count }}</span><span class="label" data-translate-text="ALBUMS">Albums</span></a></li>
                        <li><a href="{{ route('frontend.artist.followers', ['id' => $artist->id, 'slug' => str_slug($artist->name)]) }}"><span id="follower-count" class="num">{{ $artist->follower_count }}</span><span class="label" data-translate-text="">{{ __('web.FOLLOWERS') }}</span></a></li>
                    </ul>
                </div>
            </div>
            <div id="column1" class="full">
                <div class="row mb-3">
                    @if(intval(\App\Models\Role::getValue('artist_max_songs')) > 0)
                        <div class="col-xl col-lg col-sm-6 mb-3">
                            <div class="card text-white bg-secondary o-hidden h-100">
                                <div class="card-body">
                                    <h2 class="float-left h1 mb-0">{{ $artist->song_count }}/{{ intval(\App\Models\Role::getValue('artist_max_songs')) }} songs</h2>
                                    <div class="float-right"><a class="btn btn-primary" href="{{ route('frontend.settings.subscription') }}">Increase</a></div>
                                </div>
                                <div class="card-footer text-white clearfix small z-1 border-0">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: {{ intval(intval($artist->song_count)*100)/intval(\App\Models\Role::getValue('artist_max_songs')) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(config('settings.monetization'))
                        <div class="col-xl col-lg col-sm-6 mb-3">
                            <div class="card text-white bg-dark o-hidden h-100">
                                <div class="card-body">
                                    <h2 class="float-left h1 mb-0">{{ __('symbol.' . config('settings.currency', 'USD')) }}{{ $songs_revenue->total ? $songs_revenue->total : 0}}</h2>
                                    <div class="float-right">{{ thousandsCurrencyFormat(intval($songs_revenue->count)) }} streams</div>
                                </div>
                                <div class="card-footer text-white clearfix small z-1">
                                    <span class="float-left">{{ __('web.ARTIST_SONG_REVENUE') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl col-lg col-sm-6 mb-3">
                            <div class="card text-white bg-danger o-hidden h-100">
                                <div class="card-body">
                                    <h2 class="float-left h1 mb-0">{{ __('symbol.' . config('settings.currency', 'USD')) }}{{ $episodes_revenue->total ? $episodes_revenue->total : 0 }}</h2>
                                    <div class="float-right">{{ thousandsCurrencyFormat(intval($episodes_revenue->count)) }} streams</div>
                                </div>
                                <div class="card-footer text-white clearfix small z-1">
                                    <span class="float-left">{{ __('web.ARTIST_PODCAST_REVENUE') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row mb-2">
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-6 mb-4">
                        <div class="card o-hidden h-100 shadow artist">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <svg height="512pt" viewBox="0 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg"><path d="m456.828125 0h-118.472656c-26.007813 0-60.789063 14.40625-79.175781 32.796875l-243.070313 243.066406c-10.390625 10.386719-16.109375 24.238281-16.109375 39.007813 0 14.769531 5.722656 28.621094 16.109375 39.007812l142.007813 142.011719c10.386718 10.386719 24.238281 16.109375 39.007812 16.109375 14.765625 0 28.621094-5.722656 39.011719-16.109375l243.066406-243.070313c18.390625-18.386718 32.796875-53.167968 32.796875-79.175781v-118.480469c-.003906-30.417968-24.753906-55.164062-55.171875-55.164062zm25.171875 173.644531c0 18.089844-11.21875 45.175781-24.007812 57.964844l-243.070313 243.066406c-4.722656 4.722657-11.042969 7.324219-17.796875 7.324219s-13.070312-2.601562-17.792969-7.320312l-142.007812-142.015626c-4.722657-4.722656-7.324219-11.042968-7.324219-17.792968 0-6.753906 2.597656-13.074219 7.320312-17.792969l243.070313-243.070313c12.789063-12.789062 39.875-24.007812 57.964844-24.007812h118.472656c13.878906 0 25.167969 11.289062 25.171875 25.164062zm0 0"/><path d="m139.308594 331.492188c-5.453125-5.449219-4.089844-13.402344 4.085937-21.585938 9.546875-9.542969 16.476563-7.839844 20-11.359375 4.089844-4.089844.679688-11.136719-1.933593-13.746094-7.5-7.5-23.972657 5.792969-30.675782 12.496094-13.066406 13.066406-21.8125 33.402344-4.886718 50.332031 26.925781 26.925782 58.171874-14.769531 75.214843 2.273438 6.816407 6.816406 3.976563 15.789062-2.839843 22.609375-11.589844 11.585937-23.40625 5.90625-28.0625 10.5625-3.523438 3.523437-1.703126 11.25 1.476562 14.433593 6.589844 6.589844 25.449219 2.726563 40.332031-12.160156 15.679688-15.675781 18.632813-35.449218 2.386719-51.695312-29.425781-29.421875-61.011719 11.933594-75.097656-2.160156zm0 0"/><path d="m298.136719 282.644531-93.394531-49.535156c-4.429688-2.386719-10.226563.226563-14.542969 4.546875-4.203125 4.199219-6.816407 9.996094-4.429688 14.429688l49.535157 93.390624c.339843.566407.683593 1.132813.910156 1.363282 3.636718 3.632812 11.476562.792968 15.449218-3.183594 2.5-2.496094 3.640626-5.226562 2.046876-7.953125l-10.792969-19.425781 26.019531-26.019532 19.425781 10.796876c2.730469 1.585937 5.453125.453124 7.953125-2.046876 3.976563-3.976562 6.703125-11.933593 3.179688-15.453124-.335938-.339844-.792969-.570313-1.359375-.910157zm-63.738281 18.976563-24.996094-44.769532 44.765625 25zm0 0"/><path d="m343.007812 226.410156-27.722656 27.722656-65.554687-65.558593c-3.183594-3.179688-8.292969-.792969-11.929688 2.839843-3.746093 3.75-6.023437 8.75-2.839843 11.933594l73.847656 73.851563c3.070312 3.066406 7.839844 1.476562 11.136718-1.820313l36.015626-36.015625c2.953124-2.953125 1.136718-7.726562-2.046876-10.90625-3.292968-3.296875-7.949218-5.003906-10.90625-2.046875zm0 0"/><path d="m402.425781 166.992188-32.152343 32.152343-22.835938-22.835937 17.269531-17.269532c3.179688-3.179687 1.589844-7.726562-1.023437-10.339843-3.066406-3.066407-7.613282-4.199219-10.566406-1.246094l-17.269532 17.265625-22.722656-22.722656 32.152344-32.148438c2.953125-2.957031 1.589844-7.726562-1.929688-11.25-3.070312-3.066406-7.839844-4.886718-11.023437-1.707031l-40.445313 40.449219c-3.292968 3.292968-4.886718 8.066406-1.816406 11.132812l73.738281 73.734375c3.066407 3.070313 7.835938 1.480469 11.132813-1.816406l40.445312-40.445313c3.183594-3.179687 1.363282-7.953124-1.703125-11.019531-3.523437-3.523437-8.296875-4.886719-11.25-1.933593zm0 0"/><path d="m417.46875 80.398438c-3.949219 0-7.820312 1.601562-10.609375 4.390624-2.789063 2.789063-4.390625 6.660157-4.390625 10.609376 0 3.953124 1.601562 7.8125 4.390625 10.601562 2.789063 2.800781 6.660156 4.398438 10.609375 4.398438s7.808594-1.597657 10.601562-4.398438c2.796876-2.789062 4.398438-6.660156 4.398438-10.601562 0-3.949219-1.601562-7.820313-4.398438-10.609376-2.792968-2.789062-6.652343-4.390624-10.601562-4.390624zm0 0"/></svg>
                                </div>
                                <p class="d-block text-center mt-2 mb-2 counter">{{ thousandsCurrencyFormat(\App\Models\Order::where('user_id', auth()->user()->id)->count()) }}</p>
                                <p class="d-block text-center font-weight-bold mt-0">{{ __('web.SALES') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-6 mb-4">
                        <div class="card o-hidden h-100 shadow artist">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <svg width="24" height="24" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><path d="m437.02 74.98c-48.353-48.351-112.64-74.98-181.02-74.98s-132.667 26.629-181.02 74.98c-48.351 48.353-74.98 112.64-74.98 181.02s26.629 132.667 74.98 181.02c48.353 48.351 112.64 74.98 181.02 74.98s132.667-26.629 181.02-74.98c48.351-48.353 74.98-112.64 74.98-181.02s-26.629-132.667-74.98-181.02zm-181.02 407.02c-124.617 0-226-101.383-226-226s101.383-226 226-226 226 101.383 226 226-101.383 226-226 226z"/><path d="m374.782 243.84-180-130c-4.566-3.298-10.596-3.759-15.611-1.195s-8.171 7.722-8.171 13.355v260c0 5.633 3.156 10.791 8.171 13.355 2.154 1.102 4.495 1.645 6.827 1.645 3.097 0 6.179-.958 8.784-2.84l180-130c3.904-2.82 6.218-7.344 6.218-12.16s-2.312-9.34-6.218-12.16zm-173.782 112.824v-201.328l139.381 100.664z"/></g></svg>
                                </div>
                                <p class="d-block text-center mt-2 mb-2 counter">{{ thousandsCurrencyFormat(intval($counts->playSong)) }}</p>
                                <p class="d-block text-center font-weight-bold mt-0">{{ __('web.PLAYS') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-6 mb-4">
                        <div class="card o-hidden h-100 shadow artist">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <svg width="24" height="24" viewBox="0 -28 512.001 512" xmlns="http://www.w3.org/2000/svg"><path d="m256 455.515625c-7.289062 0-14.316406-2.640625-19.792969-7.4375-20.683593-18.085937-40.625-35.082031-58.21875-50.074219l-.089843-.078125c-51.582032-43.957031-96.125-81.917969-127.117188-119.3125-34.644531-41.804687-50.78125-81.441406-50.78125-124.742187 0-42.070313 14.425781-80.882813 40.617188-109.292969 26.503906-28.746094 62.871093-44.578125 102.414062-44.578125 29.554688 0 56.621094 9.34375 80.445312 27.769531 12.023438 9.300781 22.921876 20.683594 32.523438 33.960938 9.605469-13.277344 20.5-24.660157 32.527344-33.960938 23.824218-18.425781 50.890625-27.769531 80.445312-27.769531 39.539063 0 75.910156 15.832031 102.414063 44.578125 26.191406 28.410156 40.613281 67.222656 40.613281 109.292969 0 43.300781-16.132812 82.9375-50.777344 124.738281-30.992187 37.398437-75.53125 75.355469-127.105468 119.308594-17.625 15.015625-37.597657 32.039062-58.328126 50.167969-5.472656 4.789062-12.503906 7.429687-19.789062 7.429687zm-112.96875-425.523437c-31.066406 0-59.605469 12.398437-80.367188 34.914062-21.070312 22.855469-32.675781 54.449219-32.675781 88.964844 0 36.417968 13.535157 68.988281 43.882813 105.605468 29.332031 35.394532 72.960937 72.574219 123.476562 115.625l.09375.078126c17.660156 15.050781 37.679688 32.113281 58.515625 50.332031 20.960938-18.253907 41.011719-35.34375 58.707031-50.417969 50.511719-43.050781 94.136719-80.222656 123.46875-115.617188 30.34375-36.617187 43.878907-69.1875 43.878907-105.605468 0-34.515625-11.605469-66.109375-32.675781-88.964844-20.757813-22.515625-49.300782-34.914062-80.363282-34.914062-22.757812 0-43.652344 7.234374-62.101562 21.5-16.441406 12.71875-27.894532 28.796874-34.609375 40.046874-3.453125 5.785157-9.53125 9.238282-16.261719 9.238282s-12.808594-3.453125-16.261719-9.238282c-6.710937-11.25-18.164062-27.328124-34.609375-40.046874-18.449218-14.265626-39.34375-21.5-62.097656-21.5zm0 0"/></svg>
                                </div>
                                <p class="d-block text-center mt-2 mb-2 counter">{{ thousandsCurrencyFormat(intval($counts->favoriteSong)) }}</p>
                                <p class="d-block text-center font-weight-bold mt-0">{{ __('web.FAVORITES') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 col-6 mb-4">
                        <div class="card o-hidden h-100 shadow artist">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                         viewBox="0 0 512 512" xml:space="preserve">
                                    <path d="M437.019,74.98C388.667,26.629,324.38,0,256,0C187.619,0,123.331,26.629,74.98,74.98C26.628,123.332,0,187.62,0,256
                                            s26.628,132.667,74.98,181.019C123.332,485.371,187.619,512,256,512c68.38,0,132.667-26.629,181.019-74.981
                                            C485.371,388.667,512,324.38,512,256S485.371,123.333,437.019,74.98z M256,482C131.383,482,30,380.617,30,256S131.383,30,256,30
                                            s226,101.383,226,226S380.617,482,256,482z"/>
                                        <path d="M378.305,173.859c-5.857-5.856-15.355-5.856-21.212,0.001L224.634,306.319l-69.727-69.727
                                            c-5.857-5.857-15.355-5.857-21.213,0c-5.858,5.857-5.858,15.355,0,21.213l80.333,80.333c2.929,2.929,6.768,4.393,10.606,4.393
                                            c3.838,0,7.678-1.465,10.606-4.393l143.066-143.066C384.163,189.215,384.163,179.717,378.305,173.859z"/>
                                </svg>
                                </div>
                                <p class="d-block text-center mt-2 mb-2 counter">{{ thousandsCurrencyFormat(intval($counts->collectSong)) }}</p>
                                <p class="d-block text-center font-weight-bold mt-0">{{ __('web.COLLECTORS') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mb-5">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h2 class="m-0 font-weight-bold">15-day statics chart</h2>
                    </div>
                    <div class="card-body">
                        <div class="artist-management-chart-block">
                            <canvas id="artistManagerChart" class="artist-management-chart"></canvas>
                        </div>
                    </div>
                </div>
                @if(count($songs))
                    <div class="card shadow">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h2 class="m-0 font-weight-bold" data-translate-text="TOP_PERFORMING_CONTENT">{{ __('web.TOP_PERFORMING_CONTENT') }}</h2>
                        </div>
                        <div class="card-body">
                            <div id="recent-songs-grid">
                                <div class="grid-canvas">
                                    @foreach($songs as $song)
                                        <script>var song_data_{{ $song->id }} = {!! json_encode($song->makeVisible(['description', 'copyright', 'released_at'])) !!}</script>
                                        <div class="module module-row song tall artist-management" data-type="song" data-id="{{$song->id}}">
                                            <div class="img-container">
                                                <img class="img" src="{{$song->artwork_url}}" alt="{!! $song->title !!}">
                                                <div class="row-actions primary song-play-action">
                                                    <a class="btn play-lg play-object" data-type="song" data-id="{{ $song->id }}">
                                                        <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                                        <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                                        <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="metadata">
                                                <div class="title">
                                                    <a href="{{ $song->permalink_url }}">{!! $song->title !!}</a>
                                                </div>
                                                <div class="artist">
                                                    @foreach($song->artists as $artist)<a href="{{$artist->permalink_url}}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach
                                                </div>
                                                <div class="duration">{{humanTime($song->duration)}}</div>
                                            </div>
                                            <div class="row-actions secondary">
                                                <a class="btn options song-row-edit" data-type="song" data-id="{{ $song->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                                </a>
                                                <a class="btn options song-row-delete" data-type="song" data-id="{{ $song->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                                                </a>
                                                <a class="btn options" data-toggle="contextmenu" data-trigger="left" data-type="song" data-id="{{ $song->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
