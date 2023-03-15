@foreach ($activities as $index => $activity)
    @if(isset($activity->details->objects) && count($activity->details->objects) && $activity->user)
        @if( $type == 'full')
            <div class="module-feed-event {{ $activity->action }}">
                @if ($activity->action == 'addSong' || $activity->action == 'addEvent')
                    <a href="{{ route('frontend.artist', ['id' => $activity->details->model->id, 'slug' => $activity->details->model->name]) }}" class="feed-user-image">
                        <img class="author-image-medium" src="{{ $activity->details->model->artwork_url }}">
                    </a>
                @else
                    <a href="{{ route('frontend.user', ['username' => $activity->user->username]) }}" class="feed-user-image" data-id="{{ $activity->user->id }}" data-toggle="user-popover" data-placement="top" data-html="true" data-target="user-popover-container">
                        <img class="author-image-medium" src="{{ $activity->user->artwork_url }}" alt="{{ $activity->user->name }}">
                        <div class="feed-user-online-bubble user-online-status hide" data-username-status="{{ $activity->user->username }}"></div>
                    </a>
                @endif
                <div class="feed-icon">
                    @if ($activity->action == 'favoriteSong')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.623 21.623" xml:space="preserve"><path d="M21.402,7.896c-0.382-0.958-1.212-1.679-2.535-2.205c-0.107-0.043-0.221-0.08-0.338-0.113c-1.181-0.329-2.679-0.13-3.761,0.735c-0.479-1.3-1.657-2.244-2.837-2.574c-0.118-0.033-0.235-0.059-0.348-0.078c-1.405-0.235-2.488-0.045-3.31,0.576C7.456,4.853,6.966,5.87,6.812,7.26c-0.371,3.376,4.163,10.225,4.356,10.514c0.088,0.132,0.217,0.222,0.361,0.262c0.144,0.041,0.302,0.03,0.446-0.039c0.313-0.148,7.685-3.678,9.112-6.759C21.674,9.969,21.78,8.845,21.402,7.896z M20.095,11.501c0,0,0.735-3.53-2.217-4.849C17.878,6.651,21.724,7.141,20.095,11.501z M6.085,7.825C6.09,7.787,6.096,7.751,6.1,7.713c-0.728,0.26-1.427,0.879-1.699,1.701c-0.722-0.54-1.7-0.638-2.46-0.398c-0.075,0.023-0.149,0.051-0.217,0.08c-0.851,0.369-1.375,0.855-1.603,1.486c-0.226,0.625-0.134,1.354,0.275,2.167c0.994,1.974,5.894,4.106,6.102,4.196c0.095,0.04,0.198,0.044,0.29,0.015c0.093-0.03,0.175-0.091,0.23-0.18c0.052-0.084,0.617-1.011,1.207-2.207C7.051,12.337,5.89,9.601,6.085,7.825z M1.231,12.694c-1.237-1.237,0-2.554,0-2.554C0.37,11.646,1.231,12.694,1.231,12.694z"/></svg>
                    @elseif ($activity->action == 'collectSong')
                        <svg width="16" height="16" viewBox="0 -46 417.81333 417" xmlns="http://www.w3.org/2000/svg"><path d="m159.988281 318.582031c-3.988281 4.011719-9.429687 6.25-15.082031 6.25s-11.09375-2.238281-15.082031-6.25l-120.449219-120.46875c-12.5-12.5-12.5-32.769531 0-45.246093l15.082031-15.085938c12.503907-12.5 32.75-12.5 45.25 0l75.199219 75.203125 203.199219-203.203125c12.503906-12.5 32.769531-12.5 45.25 0l15.082031 15.085938c12.5 12.5 12.5 32.765624 0 45.246093zm0 0"/></svg>
                    @elseif ($activity->action == 'addSong')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 489.4 489.4" xml:space="preserve"><path d="M382.4,422.75h-79.1H282h-4.6v-106.1h34.7c8.8,0,14-10,8.8-17.2l-67.5-93.4c-4.3-6-13.2-6-17.5,0l-67.5,93.4c-5.2,7.2-0.1,17.2,8.8,17.2h34.7v106.1h-4.6H186H94.3c-52.5-2.9-94.3-52-94.3-105.2c0-36.7,19.9-68.7,49.4-86c-2.7-7.3-4.1-15.1-4.1-23.3c0-37.5,30.3-67.8,67.8-67.8c8.1,0,15.9,1.4,23.2,4.1c21.7-46,68.5-77.9,122.9-77.9c70.4,0.1,128.4,54,135,122.7c54.1,9.3,95.2,59.4,95.2,116.1C489.4,366.05,442.2,418.55,382.4,422.75z"/></svg>
                    @elseif ($activity->action == 'playSong')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 41.098 41.098" xml:space="preserve"><path d="M37.642,22.926c0-0.002,0-0.006,0-0.008c0-9.425-7.669-17.092-17.093-17.092c-9.426,0-17.094,7.667-17.094,17.092c0,0.004,0,0.006,0,0.008C1.519,23.141,0,24.904,0,27.066v4.021c0,2.307,1.726,4.184,3.844,4.184h5.568c0.828,0,1.5-0.672,1.5-1.5v-9.387c0-0.83-0.672-1.5-1.5-1.5H7.457C7.478,15.68,13.34,9.826,20.549,9.826c7.207,0,13.072,5.854,13.09,13.059h-1.953c-0.828,0-1.5,0.67-1.5,1.5v9.387c0,0.828,0.672,1.5,1.5,1.5h5.566c2.121,0,3.846-1.877,3.846-4.184v-4.021C41.098,24.903,39.576,23.141,37.642,22.926z"/></svg>
                    @elseif ($activity->action == 'addToPlaylist')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 16"><path class="st0" d="M17.7 6.3c-.4.5-.6.3-.5 0 .3-.8 1.2-2-1.2-2.4V13c0 1.7-1.3 3-3 3s-3-1.3-3-3 1.3-3 3-3c.4 0 .7.1 1 .2V1c0-.3.1-.6.3-.7V0H16c-.3 2.5 4.3 2.6 1.7 6.3zM13 12c-.6 0-1 .4-1 1s.4 1 1 1 1-.4 1-1-.4-1-1-1zm2.9 0s-.1 0 0 0c0 .2 0 .3.1.4l-.1-.4zM9.2 7H6v3.2c0 .5-.3.8-.8.8h-.4c-.5 0-.8-.3-.8-.7V7H.8C.3 7 0 6.7 0 6.3v-.5c0-.5.3-.8.8-.8H4V1.8c0-.5.3-.8.8-.8h.5c.4 0 .7.3.7.8V5h3.2c.5 0 .8.3.8.8v.5c0 .4-.3.7-.8.7z"></path></svg>
                    @elseif ($activity->action == 'followUser')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 88.71 88.709" xml:space="preserve"><path d="M49.044,24.514c0-8.1,6.565-14.666,14.666-14.666s14.666,6.566,14.666,14.666c0,8.1-6.565,14.666-14.666,14.666S49.044,32.613,49.044,24.514z M69.932,40.18H57.488c-10.354,0-18.777,8.424-18.777,18.777V74.18l0.039,0.236l1.05,0.328c9.88,3.086,18.466,4.117,25.531,4.117c13.801,0,21.8-3.936,22.294-4.186l0.98-0.498l0.104,0.001V58.958C88.71,48.604,80.287,40.18,69.932,40.18z M25,39.18c8.1,0,14.666-6.566,14.666-14.666c0-8.1-6.566-14.666-14.666-14.666s-14.666,6.566-14.666,14.666C10.334,32.614,16.9,39.18,25,39.18z M35.326,74.18V58.958c0-6.061,2.445-11.55,6.385-15.568c-2.997-2.025-6.607-3.209-10.488-3.209H18.778C8.424,40.18,0,48.604,0,58.958V74.18l0.039,0.236l1.051,0.328c9.879,3.086,18.465,4.117,25.531,4.117c4.493,0,8.359-0.42,11.563-0.99l-2.422-0.758L35.326,74.18z"/></svg>
                    @elseif ($activity->action == 'followArtist')
                        <svg width="16" height="16" viewBox="0 0 512.137 512.137" xmlns="http://www.w3.org/2000/svg"><g><path d="m494.788 239.457c8.89-8.65 3.97-23.8-8.31-25.58l-149.91-21.79-67.05-135.84c-5.5-11.12-21.4-11.13-26.9 0l-67.05 135.84-149.91 21.79c-12.27 1.78-17.21 16.92-8.31 25.58l108.48 105.74-25.61 149.31c-2.1 12.21 10.74 21.6 21.76 15.82l134.09-70.5 134.09 70.5c11 5.77 23.87-3.6 21.76-15.82l-25.61-149.31zm-177.34 147.9-61.38-32.27-61.38 32.27 11.72-68.33-49.66-48.42 68.63-9.96 30.69-62.18 30.69 62.18 68.63 9.96-49.66 48.42z"/><g><path d="m100.356 136.865-28.287-14.871-28.287 14.871c-10.984 5.775-23.863-3.577-21.764-15.813l5.402-31.498-22.885-22.306c-8.886-8.663-3.972-23.8 8.313-25.585l31.625-4.595 14.144-28.659c5.492-11.128 21.408-11.133 26.902 0l14.144 28.658 31.625 4.595c12.281 1.785 17.203 16.92 8.313 25.585l-22.885 22.307 5.402 31.498c2.107 12.276-10.834 21.559-21.762 15.813z"/></g><g><path d="m468.355 136.865-28.287-14.871-28.287 14.871c-10.984 5.774-23.863-3.577-21.764-15.813l5.402-31.498-22.885-22.307c-8.886-8.663-3.972-23.8 8.313-25.585l31.625-4.595 14.144-28.658c5.492-11.128 21.408-11.133 26.902 0l14.144 28.658 31.625 4.595c12.281 1.785 17.203 16.92 8.313 25.585l-22.885 22.307 5.402 31.498c2.097 12.209-10.752 21.602-21.762 15.813z"/></g></g></svg>
                    @elseif ($activity->action == 'followPlaylist')
                        <svg width="16" height="16" viewBox="-21 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m448 232.148438c-11.777344 0-21.332031-9.554688-21.332031-21.332032 0-59.839844-23.296875-116.074218-65.601563-158.402344-8.339844-8.339843-8.339844-21.820312 0-30.164062 8.339844-8.339844 21.824219-8.339844 30.164063 0 50.371093 50.367188 78.101562 117.335938 78.101562 188.566406 0 11.777344-9.554687 21.332032-21.332031 21.332032zm0 0"/><path d="m21.332031 232.148438c-11.773437 0-21.332031-9.554688-21.332031-21.332032 0-71.230468 27.734375-138.199218 78.101562-188.566406 8.339844-8.339844 21.824219-8.339844 30.164063 0 8.34375 8.34375 8.34375 21.824219 0 30.164062-42.304687 42.304688-65.597656 98.5625-65.597656 158.402344 0 11.777344-9.558594 21.332032-21.335938 21.332032zm0 0"/><path d="m434.753906 360.8125c-32.257812-27.265625-50.753906-67.117188-50.753906-109.335938v-59.476562c0-75.070312-55.765625-137.214844-128-147.625v-23.042969c0-11.796875-9.558594-21.332031-21.332031-21.332031-11.777344 0-21.335938 9.535156-21.335938 21.332031v23.042969c-72.253906 10.410156-128 72.554688-128 147.625v59.476562c0 42.21875-18.496093 82.070313-50.941406 109.503907-8.300781 7.105469-13.058594 17.429687-13.058594 28.351562 0 20.589844 16.746094 37.335938 37.335938 37.335938h352c20.585937 0 37.332031-16.746094 37.332031-37.335938 0-10.921875-4.757812-21.246093-13.246094-28.519531zm0 0"/><path d="m234.667969 512c38.632812 0 70.953125-27.542969 78.378906-64h-156.757813c7.421876 36.457031 39.742188 64 78.378907 64zm0 0"/></svg>
                    @elseif ($activity->action == 'postFeed')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 124 124" xml:space="preserve"><circle cx="20.3" cy="103.749" r="20"/><path d="M67,113.95c0,5.5,4.5,10,10,10s10-4.5,10-10c0-42.4-34.5-77-77-77c-5.5,0-10,4.5-10,10s4.5,10,10,10C41.5,56.95,67,82.55,67,113.95z"/><path d="M114,123.95c5.5,0,10-4.5,10-10c0-62.8-51.1-113.9-113.9-113.9c-5.5,0-10,4.5-10,10s4.5,10,10,10c51.8,0,93.9,42.1,93.9,93.9C104,119.45,108.4,123.95,114,123.95z"/></svg>
                    @endif
                </div>
                <div class="feed-content">
                    <p class="event-action">
                        @if ($activity->action == 'favoriteSong')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_FAVORITED' : 'web.FEED2_USER_FAVORITED_MANY', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'object' => htmlLink(trans('web.SONG_ARTICLE'), $activity->details->objects[0]->permalink_url, 'song-link'),
                                'objects' => trans('web.SONG_PLURAL')
                                ])
                            !!}
                        @elseif ($activity->action == 'collectSong')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_ADDED' : 'web.FEED2_USER_ADDED_MANY', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'objects' => trans('web.SONG_PLURAL'),
                                'object' => htmlLink(trans('web.SONG_ARTICLE'), $activity->details->objects[0]->permalink_url, 'song-link'),
                                'destination' => htmlLink(trans('web.AMBIGUOUS_POSSESSIVE') . ' ' . strtolower(trans('web.COLLECTION')), route('frontend.user.collection', ['username' => $activity->user->username]), 'user-collection-link'),
                                ])
                            !!}
                        @elseif ($activity->action == 'playSong')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_LISTENED' : 'web.FEED2_USER_LISTENED_MANY', [
                               'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                               'objectCount' => count($activity->details->objects),
                               'objects' => trans('web.SONG_PLURAL'),
                               'object' => htmlLink($activity->details->objects[0]->title, $activity->details->objects[0]->permalink_url, 'song-link'),
                               ])
                            !!}
                        @elseif ($activity->action == 'addToPlaylist' && $activity->details->model)
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_ADDED' : 'web.FEED2_USER_ADDED_MANY', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'objects' => trans('web.SONG_PLURAL'),
                                'object' => htmlLink(trans('web.SONG_ARTICLE'), $activity->details->objects[0]->permalink_url, 'song-link'),
                                'destination' => htmlLink($activity->details->model->title, $activity->details->model->permalink_url, 'user-collection-link'),
                                ])
                            !!}
                        @elseif ($activity->action == 'followUser')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_FOLLOWING' : 'web.FEED2_USER_FOLLOWING_MANY', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'object' => htmlLink(auth()->check() && auth()->user()->id == $activity->details->objects[0]->id ? trans('web.SELF_THIRD_PERSON') : $activity->details->objects[0]->name, $activity->details->objects[0]->permalink_url, 'user-link'),
                                'objects' => trans('web.USER_PLURAL')
                                ])
                            !!}
                        @elseif ($activity->action == 'followArtist')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_FOLLOWING' : 'web.FEED2_USER_FOLLOWING', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'object' => htmlLink(trans('web.ARTIST_ARTICLE'), $activity->details->objects[0]->permalink_url, 'artist-link'),
                                'objects' => trans('web.ARTIST_PLURAL')
                                ])
                            !!}
                        @elseif ($activity->action == 'followPlaylist')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_SUBSCRIBED' : 'web.FEED2_USER_SUBSCRIBED_MANY', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'objects' => trans('web.PLAYLIST_PLURAL'),
                                'object' => htmlLink($activity->details->objects[0]->title, $activity->details->objects[0]->permalink_url, 'playlist-link'),
                                ])
                            !!}
                        @elseif ($activity->action == 'addSong')
                            @if(isset($activity->details->model))
                                {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_ARTIST_UPLOADED' : 'web.FEED2_ARTIST_UPLOADED_MANY', [
                                    'artist' => htmlLink($activity->details->model->name, route('frontend.artist', ['id' => $activity->details->model->id, 'slug' => $activity->details->model->name]), 'user-link'),
                                    'objectCount' => count($activity->details->objects),
                                    'objects' => trans('web.SONG_PLURAL'),
                                    'object' => htmlLink(trans('web.SONG_ARTICLE'), $activity->details->objects[0]->permalink_url, 'song-link'),
                                    'destination' => htmlLink($activity->details->model->title, $activity->details->model->permalink_url, 'user-collection-link'),
                                    ])
                                !!}
                            @endif
                        @elseif ($activity->action == 'addEvent')
                            @if(isset($activity->details->model))
                                {!! __('web.FEED2_ARTIST_ADDED_EVENT', [
                                    'artist' => htmlLink($activity->details->model->name, route('frontend.artist', ['id' => $activity->details->model->id, 'slug' => $activity->details->model->name]), 'user-link'),
                                    'object' => htmlLink($activity->details->objects[0]->location, $activity->details->objects[0]->link, 'song-link'),
                                    ])
                                !!}
                            @endif
                        @elseif ($activity->action == 'postFeed')
                            {!! __('web.FEED2_USER_POST_FEED', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'object' => htmlLink(trans('web.SONG_ARTICLE'), $activity->details->objects[0]->permalink_url, 'song-link'),
                                'content' => hashtagToLink(mentionToLink($activity->events))
                                ])
                            !!}
                        @endif
                    </p>
                    <div class="feed-item">
                        <div class="feed-grid feed-grid-{{ $activity->id }}">
                            @if ($activity->action == 'favoriteSong' || $activity->action == 'addSong' || $activity->action == 'collectSong' || $activity->action == 'playSong' || $activity->action == 'addToPlaylist')
                                @include('commons.song', ['songs' => $activity->details->objects, 'element' => 'activity'])
                            @elseif ($activity->action == 'followUser')
                                @include('commons.user', ['users' => $activity->details->objects, 'element' => 'activity'])
                            @elseif ($activity->action == 'followArtist')
                                @include('commons.artist', ['artists' => $activity->details->objects, 'element' => 'activity'])
                            @elseif ($activity->action == 'followPlaylist')
                                @include('commons.playlist', ['playlists' => $activity->details->objects, 'element' => 'activity'])
                            @elseif ($activity->action == 'postFeed')
                                @if($activity->activityable_type == 'App\Models\Song')
                                    @include('commons.song', ['songs' => $activity->details->objects, 'element' => 'activity'])
                                @elseif($activity->activityable_type == 'App\Models\Album')
                                    @include('commons.album', ['albums' => $activity->details->objects, 'element' => 'activity'])
                                @elseif($activity->activityable_type == 'App\Models\Artist')
                                    @include('commons.artist', ['artists' => $activity->details->objects, 'element' => 'activity'])
                                @elseif($activity->activityable_type == 'App\Models\Playlist')
                                    @include('commons.playlist', ['playlists' => $activity->details->objects, 'element' => 'activity'])
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="module-actions-footer">
                        @if ($activity->action == 'favoriteSong' || $activity->action == 'playSong' || $activity->action == 'collectSong' || $activity->action == 'addToPlaylist')
                            <a class="module-footer-link feed-play play-menu" data-type="activity" data-id="{{ $activity->id }}" data-target=".feed-grid-{{ $activity->id }}">
                                @if(count($activity->details->objects) == 1)
                                    <span data-translate-text="FEED_PLAY">{{ __('web.FEED_PLAY') }}</span>
                                @else
                                    <span data-translate-text="FEED_PLAY_ALL">{{ __('web.FEED_PLAY_ALL') }}</span>
                                @endif
                                <span class="caret"></span>
                            </a>
                            <a class="module-footer-link feed-add add-menu" data-type="activity" data-id="{{ $activity->id }}" data-target=".feed-grid-{{ $activity->id }}">
                                @if(count($activity->details->objects) == 1)
                                    <span data-translate-text="FEED_ADD">{{ __('web.FEED_ADD') }}</span>
                                @else
                                    <span data-translate-text="FEED_ADD_ALL">{{ __('web.FEED_ADD_ALL') }}</span>
                                @endif
                                <span class="caret"></span>
                            </a>
                        @endif
                        @if(config('settings.activity_comments'))
                            <a class="module-footer-link feed-respond-cta load-comments" data-toggle="comments" data-target="#activity-comment-{{ $activity->id }}">
                                @if($activity->comment_count)
                                    <span>{{ __('web.FEED_COMMENTS_COUNT', ['count' => $activity->comment_count]) }}</span>
                                @else
                                    <span data-translate-text="FEED_COMMENT">{{ __('web.FEED_COMMENT') }}</span>
                                @endif
                                <span class="caret"></span>
                            </a>
                        @endif
                        <a class="module-footer-right-time">
                            <span class="time-full-string">{{ timeElapsedString($activity->created_at) }}</span>
                            <span class="time-short-string">{{ timeElapsedShortString($activity->created_at) }}</span>
                        </a>
                    </div>
                    <div id="activity-comment-{{ $activity->id }}" data-commentable-type="App\Models\Activity" data-commentable-id="{{ $activity->id }}"  class="comments-container feed-event-comments hide"></div>
                </div>
            </div>
        @elseif($type = 'small')
            <div class="module-feed-event module-small-feed-event {{ $activity->action }}">
                @if ($activity->action == 'addSong')
                    <a href="{{ route('frontend.user', ['username' => $activity->user->username]) }}" class="feed-user-image">
                        <img class="author-image-medium" src="{{ $activity->details->model->artwork_url }}">
                    </a>
                @else
                    <a href="{{ route('frontend.user', ['username' => $activity->user->username]) }}" class="feed-user-image">
                        <img class="author-image-medium" src="{{ $activity->user->artwork_url }}">
                        <div class="feed-user-online-bubble user-online-status hide" data-username-status="{{ $activity->user->username }}"></div>
                    </a>
                @endif
                <div class="feed-icon">
                    @if ($activity->action == 'favoriteSong')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 21.623 21.623" xml:space="preserve"><path d="M21.402,7.896c-0.382-0.958-1.212-1.679-2.535-2.205c-0.107-0.043-0.221-0.08-0.338-0.113c-1.181-0.329-2.679-0.13-3.761,0.735c-0.479-1.3-1.657-2.244-2.837-2.574c-0.118-0.033-0.235-0.059-0.348-0.078c-1.405-0.235-2.488-0.045-3.31,0.576C7.456,4.853,6.966,5.87,6.812,7.26c-0.371,3.376,4.163,10.225,4.356,10.514c0.088,0.132,0.217,0.222,0.361,0.262c0.144,0.041,0.302,0.03,0.446-0.039c0.313-0.148,7.685-3.678,9.112-6.759C21.674,9.969,21.78,8.845,21.402,7.896z M20.095,11.501c0,0,0.735-3.53-2.217-4.849C17.878,6.651,21.724,7.141,20.095,11.501z M6.085,7.825C6.09,7.787,6.096,7.751,6.1,7.713c-0.728,0.26-1.427,0.879-1.699,1.701c-0.722-0.54-1.7-0.638-2.46-0.398c-0.075,0.023-0.149,0.051-0.217,0.08c-0.851,0.369-1.375,0.855-1.603,1.486c-0.226,0.625-0.134,1.354,0.275,2.167c0.994,1.974,5.894,4.106,6.102,4.196c0.095,0.04,0.198,0.044,0.29,0.015c0.093-0.03,0.175-0.091,0.23-0.18c0.052-0.084,0.617-1.011,1.207-2.207C7.051,12.337,5.89,9.601,6.085,7.825z M1.231,12.694c-1.237-1.237,0-2.554,0-2.554C0.37,11.646,1.231,12.694,1.231,12.694z"/></svg>
                    @elseif ($activity->action == 'collectSong')
                        <svg width="16" height="16" viewBox="0 -46 417.81333 417" xmlns="http://www.w3.org/2000/svg"><path d="m159.988281 318.582031c-3.988281 4.011719-9.429687 6.25-15.082031 6.25s-11.09375-2.238281-15.082031-6.25l-120.449219-120.46875c-12.5-12.5-12.5-32.769531 0-45.246093l15.082031-15.085938c12.503907-12.5 32.75-12.5 45.25 0l75.199219 75.203125 203.199219-203.203125c12.503906-12.5 32.769531-12.5 45.25 0l15.082031 15.085938c12.5 12.5 12.5 32.765624 0 45.246093zm0 0"/></svg>
                    @elseif ($activity->action == 'addSong')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 489.4 489.4" xml:space="preserve"><path d="M382.4,422.75h-79.1H282h-4.6v-106.1h34.7c8.8,0,14-10,8.8-17.2l-67.5-93.4c-4.3-6-13.2-6-17.5,0l-67.5,93.4c-5.2,7.2-0.1,17.2,8.8,17.2h34.7v106.1h-4.6H186H94.3c-52.5-2.9-94.3-52-94.3-105.2c0-36.7,19.9-68.7,49.4-86c-2.7-7.3-4.1-15.1-4.1-23.3c0-37.5,30.3-67.8,67.8-67.8c8.1,0,15.9,1.4,23.2,4.1c21.7-46,68.5-77.9,122.9-77.9c70.4,0.1,128.4,54,135,122.7c54.1,9.3,95.2,59.4,95.2,116.1C489.4,366.05,442.2,418.55,382.4,422.75z"/></svg>
                    @elseif ($activity->action == 'playSong')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 41.098 41.098" xml:space="preserve"><path d="M37.642,22.926c0-0.002,0-0.006,0-0.008c0-9.425-7.669-17.092-17.093-17.092c-9.426,0-17.094,7.667-17.094,17.092c0,0.004,0,0.006,0,0.008C1.519,23.141,0,24.904,0,27.066v4.021c0,2.307,1.726,4.184,3.844,4.184h5.568c0.828,0,1.5-0.672,1.5-1.5v-9.387c0-0.83-0.672-1.5-1.5-1.5H7.457C7.478,15.68,13.34,9.826,20.549,9.826c7.207,0,13.072,5.854,13.09,13.059h-1.953c-0.828,0-1.5,0.67-1.5,1.5v9.387c0,0.828,0.672,1.5,1.5,1.5h5.566c2.121,0,3.846-1.877,3.846-4.184v-4.021C41.098,24.903,39.576,23.141,37.642,22.926z"/></svg>
                    @elseif ($activity->action == 'addToPlaylist')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 16"><path class="st0" d="M17.7 6.3c-.4.5-.6.3-.5 0 .3-.8 1.2-2-1.2-2.4V13c0 1.7-1.3 3-3 3s-3-1.3-3-3 1.3-3 3-3c.4 0 .7.1 1 .2V1c0-.3.1-.6.3-.7V0H16c-.3 2.5 4.3 2.6 1.7 6.3zM13 12c-.6 0-1 .4-1 1s.4 1 1 1 1-.4 1-1-.4-1-1-1zm2.9 0s-.1 0 0 0c0 .2 0 .3.1.4l-.1-.4zM9.2 7H6v3.2c0 .5-.3.8-.8.8h-.4c-.5 0-.8-.3-.8-.7V7H.8C.3 7 0 6.7 0 6.3v-.5c0-.5.3-.8.8-.8H4V1.8c0-.5.3-.8.8-.8h.5c.4 0 .7.3.7.8V5h3.2c.5 0 .8.3.8.8v.5c0 .4-.3.7-.8.7z"></path></svg>
                    @elseif ($activity->action == 'followUser')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 88.71 88.709" xml:space="preserve"><path d="M49.044,24.514c0-8.1,6.565-14.666,14.666-14.666s14.666,6.566,14.666,14.666c0,8.1-6.565,14.666-14.666,14.666S49.044,32.613,49.044,24.514z M69.932,40.18H57.488c-10.354,0-18.777,8.424-18.777,18.777V74.18l0.039,0.236l1.05,0.328c9.88,3.086,18.466,4.117,25.531,4.117c13.801,0,21.8-3.936,22.294-4.186l0.98-0.498l0.104,0.001V58.958C88.71,48.604,80.287,40.18,69.932,40.18z M25,39.18c8.1,0,14.666-6.566,14.666-14.666c0-8.1-6.566-14.666-14.666-14.666s-14.666,6.566-14.666,14.666C10.334,32.614,16.9,39.18,25,39.18z M35.326,74.18V58.958c0-6.061,2.445-11.55,6.385-15.568c-2.997-2.025-6.607-3.209-10.488-3.209H18.778C8.424,40.18,0,48.604,0,58.958V74.18l0.039,0.236l1.051,0.328c9.879,3.086,18.465,4.117,25.531,4.117c4.493,0,8.359-0.42,11.563-0.99l-2.422-0.758L35.326,74.18z"/></svg>
                    @elseif ($activity->action == 'followArtist')
                        <svg width="16" height="16" viewBox="0 0 512.137 512.137" xmlns="http://www.w3.org/2000/svg"><g><path d="m494.788 239.457c8.89-8.65 3.97-23.8-8.31-25.58l-149.91-21.79-67.05-135.84c-5.5-11.12-21.4-11.13-26.9 0l-67.05 135.84-149.91 21.79c-12.27 1.78-17.21 16.92-8.31 25.58l108.48 105.74-25.61 149.31c-2.1 12.21 10.74 21.6 21.76 15.82l134.09-70.5 134.09 70.5c11 5.77 23.87-3.6 21.76-15.82l-25.61-149.31zm-177.34 147.9-61.38-32.27-61.38 32.27 11.72-68.33-49.66-48.42 68.63-9.96 30.69-62.18 30.69 62.18 68.63 9.96-49.66 48.42z"/><g><path d="m100.356 136.865-28.287-14.871-28.287 14.871c-10.984 5.775-23.863-3.577-21.764-15.813l5.402-31.498-22.885-22.306c-8.886-8.663-3.972-23.8 8.313-25.585l31.625-4.595 14.144-28.659c5.492-11.128 21.408-11.133 26.902 0l14.144 28.658 31.625 4.595c12.281 1.785 17.203 16.92 8.313 25.585l-22.885 22.307 5.402 31.498c2.107 12.276-10.834 21.559-21.762 15.813z"/></g><g><path d="m468.355 136.865-28.287-14.871-28.287 14.871c-10.984 5.774-23.863-3.577-21.764-15.813l5.402-31.498-22.885-22.307c-8.886-8.663-3.972-23.8 8.313-25.585l31.625-4.595 14.144-28.658c5.492-11.128 21.408-11.133 26.902 0l14.144 28.658 31.625 4.595c12.281 1.785 17.203 16.92 8.313 25.585l-22.885 22.307 5.402 31.498c2.097 12.209-10.752 21.602-21.762 15.813z"/></g></g></svg>
                    @elseif ($activity->action == 'followPlaylist')
                        <svg width="16" height="16" viewBox="-21 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m448 232.148438c-11.777344 0-21.332031-9.554688-21.332031-21.332032 0-59.839844-23.296875-116.074218-65.601563-158.402344-8.339844-8.339843-8.339844-21.820312 0-30.164062 8.339844-8.339844 21.824219-8.339844 30.164063 0 50.371093 50.367188 78.101562 117.335938 78.101562 188.566406 0 11.777344-9.554687 21.332032-21.332031 21.332032zm0 0"/><path d="m21.332031 232.148438c-11.773437 0-21.332031-9.554688-21.332031-21.332032 0-71.230468 27.734375-138.199218 78.101562-188.566406 8.339844-8.339844 21.824219-8.339844 30.164063 0 8.34375 8.34375 8.34375 21.824219 0 30.164062-42.304687 42.304688-65.597656 98.5625-65.597656 158.402344 0 11.777344-9.558594 21.332032-21.335938 21.332032zm0 0"/><path d="m434.753906 360.8125c-32.257812-27.265625-50.753906-67.117188-50.753906-109.335938v-59.476562c0-75.070312-55.765625-137.214844-128-147.625v-23.042969c0-11.796875-9.558594-21.332031-21.332031-21.332031-11.777344 0-21.335938 9.535156-21.335938 21.332031v23.042969c-72.253906 10.410156-128 72.554688-128 147.625v59.476562c0 42.21875-18.496093 82.070313-50.941406 109.503907-8.300781 7.105469-13.058594 17.429687-13.058594 28.351562 0 20.589844 16.746094 37.335938 37.335938 37.335938h352c20.585937 0 37.332031-16.746094 37.332031-37.335938 0-10.921875-4.757812-21.246093-13.246094-28.519531zm0 0"/><path d="m234.667969 512c38.632812 0 70.953125-27.542969 78.378906-64h-156.757813c7.421876 36.457031 39.742188 64 78.378907 64zm0 0"/></svg>
                    @elseif ($activity->action == 'postFeed')
                        <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 124 124" xml:space="preserve"><circle cx="20.3" cy="103.749" r="20"/><path d="M67,113.95c0,5.5,4.5,10,10,10s10-4.5,10-10c0-42.4-34.5-77-77-77c-5.5,0-10,4.5-10,10s4.5,10,10,10C41.5,56.95,67,82.55,67,113.95z"/><path d="M114,123.95c5.5,0,10-4.5,10-10c0-62.8-51.1-113.9-113.9-113.9c-5.5,0-10,4.5-10,10s4.5,10,10,10c51.8,0,93.9,42.1,93.9,93.9C104,119.45,108.4,123.95,114,123.95z"/></svg>
                    @endif
                </div>
                <div class="feed-content">
                    <p class="event-action">
                        @if ($activity->action == 'favoriteSong')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_FAVORITED' : 'web.FEED2_USER_FAVORITED_MANY', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'object' => htmlLink(trans('web.SONG_ARTICLE'), $activity->details->objects[0]->permalink_url, 'song-link'),
                                'objects' => trans('web.SONG_PLURAL')
                                ])
                            !!}
                        @elseif ($activity->action == 'collectSong')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_ADDED' : 'web.FEED2_USER_ADDED_MANY', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'objects' => trans('web.SONG_PLURAL'),
                                'object' => htmlLink(trans('web.SONG_ARTICLE'), $activity->details->objects[0]->permalink_url, 'song-link'),
                                'destination' => htmlLink(trans('web.AMBIGUOUS_POSSESSIVE') . ' ' . strtolower(trans('web.COLLECTION')), route('frontend.user.collection', ['username' => $activity->user->username]), 'user-collection-link'),
                                ])
                            !!}
                        @elseif ($activity->action == 'playSong')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_LISTENED' : 'web.FEED2_USER_LISTENED_MANY', [
                               'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                               'objectCount' => count($activity->details->objects),
                               'objects' => trans('web.SONG_PLURAL'),
                               'object' => htmlLink($activity->details->objects[0]->title, $activity->details->objects[0]->permalink_url, 'song-link'),
                               ])
                            !!}
                        @elseif ($activity->action == 'addToPlaylist')
                            @if(isset($activity->details->model))
                                {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_ADDED' : 'web.FEED2_USER_ADDED_MANY', [
                                    'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                    'objectCount' => count($activity->details->objects),
                                    'objects' => trans('web.SONG_PLURAL'),
                                    'object' => htmlLink(trans('web.SONG_ARTICLE'), $activity->details->objects[0]->permalink_url, 'song-link'),
                                    'destination' => htmlLink($activity->details->model->title, $activity->details->model->permalink_url, 'user-collection-link'),
                                    ])
                                !!}
                            @endif
                        @elseif ($activity->action == 'followUser')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_FOLLOWING' : 'web.FEED2_USER_FOLLOWING_MANY', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'object' => htmlLink(auth()->check() && auth()->user()->id == $activity->details->objects[0]->id ? trans('web.SELF_THIRD_PERSON') : $activity->details->objects[0]->name, $activity->details->objects[0]->permalink_url, 'user-link'),
                                'objects' => trans('web.USER_PLURAL')
                                ])
                            !!}
                        @elseif ($activity->action == 'followArtist')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_FOLLOWING' : 'web.FEED2_USER_FOLLOWING', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'object' => htmlLink($activity->details->objects[0]->name, $activity->details->objects[0]->permalink_url, 'artist-link'),
                                'objects' => trans('web.ARTIST_PLURAL')
                                ])
                            !!}
                        @elseif ($activity->action == 'followPlaylist')
                            {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_USER_SUBSCRIBED' : 'web.FEED2_USER_SUBSCRIBED_MANY', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'objects' => trans('web.PLAYLIST_PLURAL'),
                                'object' => htmlLink($activity->details->objects[0]->title, $activity->details->objects[0]->permalink_url, 'playlist-link'),
                                ])
                            !!}
                        @elseif ($activity->action == 'addSong')
                            @if(isset($activity->details->model))
                                {!! __(count($activity->details->objects) == 1 ? 'web.FEED2_ARTIST_UPLOADED' : 'web.FEED2_ARTIST_UPLOADED_MANY', [
                                    'artist' => htmlLink($activity->details->model->name, route('frontend.artist', ['id' => $activity->details->model->id, 'slug' => $activity->details->model->name]), 'user-link'),
                                    'objectCount' => count($activity->details->objects),
                                    'objects' => trans('web.SONG_PLURAL'),
                                    'object' => htmlLink(trans('web.SONG_ARTICLE'), $activity->details->objects[0]->permalink_url, 'song-link'),
                                    'destination' => htmlLink($activity->details->model->title, $activity->details->model->permalink_url, 'user-collection-link'),
                                    ])
                                !!}
                            @endif
                        @elseif ($activity->action == 'postFeed')
                            {!! __('web.FEED2_USER_POST_FEED', [
                                'user' => htmlLink($activity->user->name, route('frontend.user', ['username' => $activity->user->username]), 'user-link'),
                                'objectCount' => count($activity->details->objects),
                                'object' => htmlLink(trans('web.SONG_ARTICLE'), $activity->details->objects[0]->permalink_url, 'song-link'),
                                'content' => hashtagToLink(mentionToLink($activity->events))
                                ])
                            !!}
                        @endif
                    </p>
                    @if ($activity->action == 'addSong')
                        <div class="feed-item">
                            <div class="feed-grid feed-grid-{{ $activity->id }}">
                                @include('commons.song', ['songs' => $activity->details->objects, 'element' => 'activity'])
                            </div>
                        </div>
                    @elseif ($activity->action == 'postFeed')
                        @if($activity->activityable_type == 'App\Models\Song')
                            @include('commons.song', ['songs' => $activity->details->objects, 'element' => 'activity'])
                        @elseif($activity->activityable_type == 'App\Models\Album')
                            @include('commons.album', ['albums' => $activity->details->objects, 'element' => 'activity'])
                        @elseif($activity->activityable_type == 'App\Models\Artist')
                            @include('commons.artist', ['artists' => $activity->details->objects, 'element' => 'activity'])
                        @elseif($activity->activityable_type == 'App\Models\Playlist')
                            @include('commons.playlist', ['playlists' => $activity->details->objects, 'element' => 'activity'])
                        @endif
                    @endif
                    <div class="small-event-footer">
                        @if(config('settings.activity_comments'))
                            <a class="module-footer-link feed-respond-cta load-comments" data-toggle="comments" data-target="#activity-comment-{{ $activity->id }}">
                                @if($activity->comment_count)
                                    <span>{{ __('web.FEED_COMMENTS_COUNT', ['count' => $activity->comment_count]) }}</span>
                                @else
                                    <span data-translate-text="FEED_COMMENT">{{ __('web.FEED_COMMENT') }}</span>
                                @endif
                                <span class="caret"></span>
                            </a>
                        @endif
                        <a class="module-footer-right-time">{{ timeElapsedShortString($activity->created_at) }}</a>
                        @if(auth()->check() && auth()->user()->id == $activity->user->id)
                            <a class="delete-event-cta" data-id="{{ $activity->id }}" data-translate-text="REMOVE">{{ __('web.REMOVE') }}</a>
                        @endif
                    </div>
                    <div id="activity-comment-{{ $activity->id }}" data-commentable-type="App\Models\Activity" data-commentable-id="{{ $activity->id }}"  class="comments-container feed-event-comments hide"></div>
                </div>
            </div>
        @endif
    @endif
@endforeach