@if(count($notifications))
    @foreach ($notifications as $index => $notification)
        @if((isset($notification->details->object) && isset($notification->details->object->permalink_url) && isset($notification->details->host)) || (isset($notification->details->object->object->object->permalink_url) && isset($notification->details->host)))
            <a
                @if($notification->action == 'sharedMusic')
                href="{{ route('frontend.user.posts', ['username' => $notification->details->host->username, 'id' => $notification->object_id]) }}"
                @elseif ($notification->action == 'commentMusic' ||  $notification->action == 'commentMentioned' || $notification->action == 'replyComment')
                href="{{ $notification->details->object->permalink_url }}?comment_id={{ $notification->object_id }}"
                @elseif ($notification->action == 'reactComment')
                href="{{ $notification->details->object->object->object->permalink_url }}?comment_id={{ $notification->object_id }}"
                @elseif ($notification->action == 'inviteCollaboration' || $notification->action == 'acceptedCollaboration')
                href="{{ $notification->details->object->permalink_url }}"
                @elseif ($notification->action == 'addToPlaylist')
                @elseif ($notification->action == 'followUser')
                href="{{ route('frontend.user', ['username' => $notification->details->host->username]) }}"

                @elseif ($notification->action == 'followArtist')
                @elseif ($notification->action == 'followPlaylist')
                @elseif ($notification->action == 'addEvent')
                href="{{ route('frontend.artist.events', ['id' => $notification->details->model->id, 'slug' => $notification->details->model->name]) }}"
                @endif
                data-notification-id="{{ $notification->id }}"
            >
                <div class="module-feed-event {{ $notification->action }}">
                    <div class="feed-user-image">
                        <img class="author-image-medium" src="{{ $notification->details->host->artwork_url }}">
                    </div>
                    <div class="feed-icon">
                        @if ($notification->action == 'sharedMusic')
                            <svg height="16" width="16" viewBox="0 0 36 36"><path d="M16.2785894,26.6946832 C17.4409207,27.4920577 18.9988647,26.6347303 18.9988647,25.197358 L18.9988647,21.9938712 C23.9932881,21.9938712 26.8856124,22.9656089 27.8513876,25.9083002 C28.119464,26.7256589 28.5055741,26.989452 28.9662054,26.989452 C29.5883827,26.989452 30,26.5722793 30,25.9782453 C29.9989997,18.2243286 26.1404,14.0011418 18.9988647,14.0011418 L18.9988647,10.8021516 C18.9988647,9.36527885 17.4409207,8.50795146 16.2785894,9.30532589 L5.77909704,16.5026793 C4.74030099,17.2146207 4.74030099,18.7843892 5.77909704,19.4968303 L16.2785894,26.6946832 Z"></path></svg>
                        @elseif ($notification->action == 'commentMusic' || $notification->action == 'replyComment' || $notification->action == 'commentMentioned')
                            <svg  width="16" height="16" viewBox="0 0 511.096 511.096" xmlns="http://www.w3.org/2000/svg"><g id="Speech_Bubble_48_"><g><path d="m74.414 480.548h-36.214l25.607-25.607c13.807-13.807 22.429-31.765 24.747-51.246-59.127-38.802-88.554-95.014-88.554-153.944 0-108.719 99.923-219.203 256.414-219.203 165.785 0 254.682 101.666 254.682 209.678 0 108.724-89.836 210.322-254.682 210.322-28.877 0-59.01-3.855-85.913-10.928-25.467 26.121-59.973 40.928-96.087 40.928z"/></g></g></svg>
                        @elseif ($notification->action == 'addToPlaylist')
                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 16"><path class="st0" d="M17.7 6.3c-.4.5-.6.3-.5 0 .3-.8 1.2-2-1.2-2.4V13c0 1.7-1.3 3-3 3s-3-1.3-3-3 1.3-3 3-3c.4 0 .7.1 1 .2V1c0-.3.1-.6.3-.7V0H16c-.3 2.5 4.3 2.6 1.7 6.3zM13 12c-.6 0-1 .4-1 1s.4 1 1 1 1-.4 1-1-.4-1-1-1zm2.9 0s-.1 0 0 0c0 .2 0 .3.1.4l-.1-.4zM9.2 7H6v3.2c0 .5-.3.8-.8.8h-.4c-.5 0-.8-.3-.8-.7V7H.8C.3 7 0 6.7 0 6.3v-.5c0-.5.3-.8.8-.8H4V1.8c0-.5.3-.8.8-.8h.5c.4 0 .7.3.7.8V5h3.2c.5 0 .8.3.8.8v.5c0 .4-.3.7-.8.7z"></path></svg>
                        @elseif ($notification->action == 'followUser')
                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 88.71 88.709" xml:space="preserve"><path d="M49.044,24.514c0-8.1,6.565-14.666,14.666-14.666s14.666,6.566,14.666,14.666c0,8.1-6.565,14.666-14.666,14.666S49.044,32.613,49.044,24.514z M69.932,40.18H57.488c-10.354,0-18.777,8.424-18.777,18.777V74.18l0.039,0.236l1.05,0.328c9.88,3.086,18.466,4.117,25.531,4.117c13.801,0,21.8-3.936,22.294-4.186l0.98-0.498l0.104,0.001V58.958C88.71,48.604,80.287,40.18,69.932,40.18z M25,39.18c8.1,0,14.666-6.566,14.666-14.666c0-8.1-6.566-14.666-14.666-14.666s-14.666,6.566-14.666,14.666C10.334,32.614,16.9,39.18,25,39.18z M35.326,74.18V58.958c0-6.061,2.445-11.55,6.385-15.568c-2.997-2.025-6.607-3.209-10.488-3.209H18.778C8.424,40.18,0,48.604,0,58.958V74.18l0.039,0.236l1.051,0.328c9.879,3.086,18.465,4.117,25.531,4.117c4.493,0,8.359-0.42,11.563-0.99l-2.422-0.758L35.326,74.18z"/></svg>
                        @elseif ($notification->action == 'followArtist')
                            <svg width="16" height="16" viewBox="0 0 512.137 512.137" xmlns="http://www.w3.org/2000/svg"><g><path d="m494.788 239.457c8.89-8.65 3.97-23.8-8.31-25.58l-149.91-21.79-67.05-135.84c-5.5-11.12-21.4-11.13-26.9 0l-67.05 135.84-149.91 21.79c-12.27 1.78-17.21 16.92-8.31 25.58l108.48 105.74-25.61 149.31c-2.1 12.21 10.74 21.6 21.76 15.82l134.09-70.5 134.09 70.5c11 5.77 23.87-3.6 21.76-15.82l-25.61-149.31zm-177.34 147.9-61.38-32.27-61.38 32.27 11.72-68.33-49.66-48.42 68.63-9.96 30.69-62.18 30.69 62.18 68.63 9.96-49.66 48.42z"/><g><path d="m100.356 136.865-28.287-14.871-28.287 14.871c-10.984 5.775-23.863-3.577-21.764-15.813l5.402-31.498-22.885-22.306c-8.886-8.663-3.972-23.8 8.313-25.585l31.625-4.595 14.144-28.659c5.492-11.128 21.408-11.133 26.902 0l14.144 28.658 31.625 4.595c12.281 1.785 17.203 16.92 8.313 25.585l-22.885 22.307 5.402 31.498c2.107 12.276-10.834 21.559-21.762 15.813z"/></g><g><path d="m468.355 136.865-28.287-14.871-28.287 14.871c-10.984 5.774-23.863-3.577-21.764-15.813l5.402-31.498-22.885-22.307c-8.886-8.663-3.972-23.8 8.313-25.585l31.625-4.595 14.144-28.658c5.492-11.128 21.408-11.133 26.902 0l14.144 28.658 31.625 4.595c12.281 1.785 17.203 16.92 8.313 25.585l-22.885 22.307 5.402 31.498c2.097 12.209-10.752 21.602-21.762 15.813z"/></g></g></svg>
                        @elseif ($notification->action == 'followPlaylist')
                            <svg width="16" height="16" viewBox="-21 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m448 232.148438c-11.777344 0-21.332031-9.554688-21.332031-21.332032 0-59.839844-23.296875-116.074218-65.601563-158.402344-8.339844-8.339843-8.339844-21.820312 0-30.164062 8.339844-8.339844 21.824219-8.339844 30.164063 0 50.371093 50.367188 78.101562 117.335938 78.101562 188.566406 0 11.777344-9.554687 21.332032-21.332031 21.332032zm0 0"/><path d="m21.332031 232.148438c-11.773437 0-21.332031-9.554688-21.332031-21.332032 0-71.230468 27.734375-138.199218 78.101562-188.566406 8.339844-8.339844 21.824219-8.339844 30.164063 0 8.34375 8.34375 8.34375 21.824219 0 30.164062-42.304687 42.304688-65.597656 98.5625-65.597656 158.402344 0 11.777344-9.558594 21.332032-21.335938 21.332032zm0 0"/><path d="m434.753906 360.8125c-32.257812-27.265625-50.753906-67.117188-50.753906-109.335938v-59.476562c0-75.070312-55.765625-137.214844-128-147.625v-23.042969c0-11.796875-9.558594-21.332031-21.332031-21.332031-11.777344 0-21.335938 9.535156-21.335938 21.332031v23.042969c-72.253906 10.410156-128 72.554688-128 147.625v59.476562c0 42.21875-18.496093 82.070313-50.941406 109.503907-8.300781 7.105469-13.058594 17.429687-13.058594 28.351562 0 20.589844 16.746094 37.335938 37.335938 37.335938h352c20.585937 0 37.332031-16.746094 37.332031-37.335938 0-10.921875-4.757812-21.246093-13.246094-28.519531zm0 0"/><path d="m234.667969 512c38.632812 0 70.953125-27.542969 78.378906-64h-156.757813c7.421876 36.457031 39.742188 64 78.378907 64zm0 0"/></svg>
                        @elseif ($notification->action == 'reactComment')
                            <img src="{{ asset('common/reactions/' . $notification->details->object->type . '.svg') }}" alt="{{ $notification->details->object->type }}">
                        @elseif ($notification->action == 'inviteCollaboration' || $notification->action == 'acceptedCollaboration')
                            <svg height="16" viewBox="0 -8 512.00013 512" width="16" xmlns="http://www.w3.org/2000/svg"><path d="m507.609375 132.925781-128.535156-128.53125c-5.855469-5.859375-15.355469-5.859375-21.210938 0l-63.945312 63.941407c24.421875 4.371093 46.902343 16.050781 64.8125 33.960937l100.359375 100.359375 48.515625-48.515625c5.859375-5.859375 5.859375-15.355469.003906-21.214844zm0 0"/><path d="m337.515625 123.511719c-17.476563-17.480469-40.726563-27.109375-65.449219-27.109375h-25.917968l-92.011719-92.011719c-5.859375-5.851563-15.359375-5.851563-21.210938 0l-128.539062 128.539063c-5.847657 5.851562-5.847657 15.351562 0 21.210937l75.941406 75.941406v57.621094l43.347656-43.363281c11.742188-11.738282 27.351563-18.199219 43.953125-18.199219 16.597656 0 32.207032 6.460937 43.949219 18.199219 11.199219 11.191406 17.21875 25.628906 18.058594 40.332031 7.140625 3.078125 13.710937 7.507813 19.378906 13.179687 11.203125 11.199219 17.222656 25.640626 18.0625 40.320313 7.140625 3.078125 13.710937 7.507813 19.378906 13.179687 8.308594 8.308594 13.761719 18.390626 16.371094 29.039063 10.929687 2.660156 20.949219 8.28125 29.097656 16.429687 11.730469 11.742188 18.203125 27.351563 18.203125 43.949219 0 9.371094-2.0625 18.421875-5.972656 26.640625 8.390625-1.519531 16.390625-5.550781 22.859375-12.007812 19.472656-19.472656 11.160156-42.980469 9.703125-46.671875 22.9375-9.28125 32.578125-34.789063 24.097656-56.238281 19.460938-7.871094 33.902344-31.453126 24.101563-56.230469 28.609375-11.582031 36.429687-48.921875 14.410156-70.941407zm0 0"/><path d="m265.238281 418.03125c12.558594-12.554688 12.558594-32.914062 0-45.46875-12.554687-12.558594-32.914062-12.558594-45.46875 0l8.03125-8.03125c12.558594-12.558594 12.558594-32.914062 0-45.472656-12.554687-12.554688-32.914062-12.554688-45.46875 0l8.035157-8.035156c12.554687-12.554688 12.554687-32.914063 0-45.46875-12.558594-12.554688-32.914063-12.554688-45.46875 0l-40.011719 40.007812c-12.554688 12.554688-12.554688 32.914062 0 45.46875 12.558593 12.558594 32.914062 12.558594 45.472656 0-12.558594 12.558594-12.558594 32.914062 0 45.472656 12.554687 12.554688 32.914063 12.554688 45.46875 0-12.554687 12.554688-12.554687 32.914063 0 45.46875 12.554687 12.554688 32.914063 12.554688 45.46875 0-12.554687 12.554688-12.554687 32.914063 0 45.46875 12.558594 12.558594 32.914063 12.558594 45.472656 0l23.941407-23.941406c12.554687-12.554688 12.554687-32.914062 0-45.46875-12.558594-12.554688-32.914063-12.554688-45.472657 0zm0 0"/></svg>
                        @endif
                    </div>
                    <div class="feed-content">
                        <p class="event-action">
                            @if ($notification->action == 'sharedMusic')
                                @switch($notification->notificationable_type)
                                    @case('App\Models\Song')
                                    {!! __('web.NOTIFICATION_USER_SHARE', ['user' => $notification->details->host->name, 'object' => trans('web.SONG_ARTICLE')])!!}
                                    @break
                                    @case('App\Models\Album')
                                    {!! __('web.NOTIFICATION_USER_SHARE', [
                                            'user' => $notification->details->host->name,
                                            'object' => trans('web.ALBUM_ARTICLE')
                                        ])
                                    !!}
                                    @break
                                    @case('App\Models\Playlist')
                                    {!! __('web.NOTIFICATION_USER_SHARE', [
                                            'user' => $notification->details->host->name,
                                            'object' => trans('web.PLAYLIST_ARTICLE')
                                        ])
                                    !!}
                                    @break
                                    @case('App\Models\Artist')
                                    {!! __('web.NOTIFICATION_USER_SHARE', [
                                            'user' => $notification->details->host->name,
                                            'object' => trans('web.ARTIST_ARTICLE'),
                                        ])
                                    !!}
                                    @break
                                @endswitch
                            @elseif ($notification->action == 'commentMusic')
                                @switch($notification->notificationable_type)
                                    @case('App\Models\Song')
                                    {!! __('web.NOTIFICATION_USER_COMMENT', [
                                        'user' => $notification->details->host->name,
                                        'object' => trans('web.SONG_ARTICLE'),
                                        ])
                                    !!}
                                    @break
                                    @case('App\Models\Activity')
                                    {!! __('web.NOTIFICATION_USER_COMMENT', [
                                        'user' => $notification->details->host->name,
                                        'object' => 'your activity',
                                        ])
                                    !!}
                                    @break
                                    @case('App\Models\Playlist')
                                    {!! __('web.NOTIFICATION_USER_COMMENT', [
                                        'user' => $notification->details->host->name,
                                        'object' => trans('web.PLAYLIST_ARTICLE'),
                                        ])
                                    !!}
                                    @break
                                    @case('App\Models\User')
                                    {!! __('web.NOTIFICATION_USER_COMMENT', [
                                        'user' => $notification->details->host->name,
                                        'object' => 'your profile page',
                                        ])
                                    !!}
                                    @break
                                @endswitch
                            @elseif ($notification->action == 'commentMentioned')
                                    {!! __('web.NOTIFICATION_USER_GET_COMMENT_MENTIONED', [
                                        'user' => $notification->details->host->name,
                                        'object' => trans('web.SONG_ARTICLE'),
                                        ])
                                    !!}
                            @elseif ($notification->action == 'inviteCollaboration')
                                {!! __('web.NOTIFICATION_INVITED_COLLABORATION', [
                                    'user' => $notification->details->host->name
                                    ])
                                !!}
                            @elseif ($notification->action == 'acceptedCollaboration')
                                {!! __('web.NOTIFICATION_ACCEPTED_COLLABORATION', [
                                    'user' => $notification->details->host->name,
                                    'object' => $notification->details->object->title,
                                    ])
                                !!}
                            @elseif ($notification->action == 'replyComment')
                                {!! __('web.NOTIFICATION_USER_REPLY_COMMENT', [
                                    'user' => $notification->details->host->name
                                    ])
                                !!}
                            @elseif ($notification->action == 'reactComment')
                                {!! __('web.NOTIFICATION_USER_REACT_COMMENT', [
                                    'user' => $notification->details->host->name,
                                    'content' => str_limit(strip_tags($notification->details->object->object->content), 30),
                                    ])
                                !!}
                            @elseif ($notification->action == 'followUser')
                                {!! __('web.NOTIFICATION_USER_FOLLOW', ['user' => $notification->details->host->name,])
                                !!}
                            @elseif ($notification->action == 'followArtist')
                                {!! __(count($notification->details->objects) == 1 ? 'web.FEED2_USER_FOLLOWING' : 'web.FEED2_USER_FOLLOWING', [
                                    'user' => htmlLink($notification->user->name, route('frontend.user', ['username' => $notification->user->username]), 'user-link'),
                                    'objectCount' => count($notification->details->objects),
                                    'object' => htmlLink($notification->details->objects[0]->name, $notification->details->objects[0]->permalink_url, 'artist-link'),
                                    'objects' => trans('web.ARTIST_PLURAL')
                                    ])
                                !!}
                            @elseif ($notification->action == 'followPlaylist')
                                {!! __($notification->details->object->user->id == auth()->user()->id ? 'web.NOTIFICATION_USER_SUBSCRIBED_YOUR' : 'web.NOTIFICATION_USER_SUBSCRIBED', [
                                    'user' => htmlLink($notification->details->host->name, route('frontend.user', ['username' => $notification->details->host->username]), 'user-link'),
                                    'object' => htmlLink($notification->details->object->user->id == auth()->user()->id ? trans('web.PLAYLIST') : $notification->details->object->title, $notification->details->object->permalink_url, 'playlist-link'),
                                    ])
                                !!}
                            @elseif ($notification->action == 'addSong')
                                @if(isset($notification->details->model))
                                    {!! __(count($notification->details->objects) == 1 ? 'web.FEED2_ARTIST_UPLOADED' : 'web.FEED2_ARTIST_UPLOADED_MANY', [
                                        'artist' => htmlLink($notification->details->model->name, route('frontend.artist', ['id' => $notification->details->model->id, 'slug' => $notification->details->model->name]), 'user-link'),
                                        'objectCount' => count($notification->details->objects),
                                        'objects' => trans('web.SONG_PLURAL'),
                                        'object' => htmlLink(trans('web.SONG_ARTICLE'), $notification->details->objects[0]->permalink_url, 'song-link'),
                                        'destination' => htmlLink($notification->details->model->title, $notification->details->model->permalink_url, 'user-collection-link'),
                                        ])
                                    !!}
                                @endif
                            @endif
                            <span class="notification-time text-secondary">{{ timeElapsedString($notification->created_at) }}</span>
                        </p>
                        @if ($notification->action == 'inviteCollaboration')
                            <div class="row mb-3">
                                <div class="col-6">
                                    <button class="btn btn-primary btn-block" data-toggle="collaboration" data-action="accept" data-playlist-id="{{ $notification->details->object->id }}" data-notification-id="{{ $notification->id }}">{{ __('web.ACCEPT') }}</button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-secondary btn-block" data-toggle="collaboration" data-action="cancel" data-playlist-id="{{ $notification->details->object->id }}" data-notification-id="{{ $notification->id }}">{{ __('web.CANCEL') }}</button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <span class="module-time text-secondary">{{ timeElapsedString($notification->created_at) }}</span>
                </div>
            </a>
        @endif
        @if(isset($notification->details->objects) && isset($notification->details->objects[0]) && isset($notification->details->model))
            <a
                    @if ($notification->action == 'addToPlaylist')
                    href="{{ $notification->details->model->permalink_url }}"
                    @elseif ($notification->action == 'addSong')
                    href="{{ route('frontend.artist', ['id' => $notification->details->model->id, 'slug' => $notification->details->model->name]) }}"
                    @elseif ($notification->action == 'addEvent')
                    href="{{ route('frontend.artist.events', ['id' => $notification->details->model->id, 'slug' => $notification->details->model->name]) }}"
                    @endif

            >
                <div class="module-feed-event {{ $notification->action }}">
                    @if ($notification->action == 'addSong' || $notification->action == 'addEvent')
                        <div class="feed-user-image">
                            <img class="author-image-medium" src="{{ $notification->details->model->artwork_url }}">
                        </div>
                    @else
                        <div href="{{ route('frontend.user', ['username' => $notification->user->username]) }}" class="feed-user-image">
                            <img class="author-image-medium" src="{{ $notification->user->artwork_url }}">
                            <div class="feed-user-online-bubble user-online-status hide" data-username-status="{{ $notification->user->username }}"></div>
                        </div>
                    @endif
                    <div class="feed-icon">
                        @if ($notification->action == 'addSong')
                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 489.4 489.4" xml:space="preserve"><path d="M382.4,422.75h-79.1H282h-4.6v-106.1h34.7c8.8,0,14-10,8.8-17.2l-67.5-93.4c-4.3-6-13.2-6-17.5,0l-67.5,93.4c-5.2,7.2-0.1,17.2,8.8,17.2h34.7v106.1h-4.6H186H94.3c-52.5-2.9-94.3-52-94.3-105.2c0-36.7,19.9-68.7,49.4-86c-2.7-7.3-4.1-15.1-4.1-23.3c0-37.5,30.3-67.8,67.8-67.8c8.1,0,15.9,1.4,23.2,4.1c21.7-46,68.5-77.9,122.9-77.9c70.4,0.1,128.4,54,135,122.7c54.1,9.3,95.2,59.4,95.2,116.1C489.4,366.05,442.2,418.55,382.4,422.75z"/></svg>
                        @elseif ($notification->action == 'addEvent')
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 426.667 426.667" xml:space="preserve"><path d="M362.667,42.667h-21.333V0h-42.667v42.667H128V0H85.333v42.667H64c-23.573,0-42.453,19.093-42.453,42.667L21.333,384c0,23.573,19.093,42.667,42.667,42.667h298.667c23.573,0,42.667-19.093,42.667-42.667V85.333C405.333,61.76,386.24,42.667,362.667,42.667z M362.667,384H64V149.333h298.667V384z"/><polygon points="309.973,214.613 287.36,192 183.253,296.107 138.027,250.88 115.413,273.493 183.253,341.333"/></svg>
                        @elseif ($notification->action == 'addToPlaylist')
                            <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 16"><path class="st0" d="M17.7 6.3c-.4.5-.6.3-.5 0 .3-.8 1.2-2-1.2-2.4V13c0 1.7-1.3 3-3 3s-3-1.3-3-3 1.3-3 3-3c.4 0 .7.1 1 .2V1c0-.3.1-.6.3-.7V0H16c-.3 2.5 4.3 2.6 1.7 6.3zM13 12c-.6 0-1 .4-1 1s.4 1 1 1 1-.4 1-1-.4-1-1-1zm2.9 0s-.1 0 0 0c0 .2 0 .3.1.4l-.1-.4zM9.2 7H6v3.2c0 .5-.3.8-.8.8h-.4c-.5 0-.8-.3-.8-.7V7H.8C.3 7 0 6.7 0 6.3v-.5c0-.5.3-.8.8-.8H4V1.8c0-.5.3-.8.8-.8h.5c.4 0 .7.3.7.8V5h3.2c.5 0 .8.3.8.8v.5c0 .4-.3.7-.8.7z"></path></svg>
                        @endif
                    </div>
                    <div class="feed-content">
                        <p class="event-action">
                            @if ($notification->action == 'addEvent')
                                @if(isset($notification->details->model))
                                    {!! __('web.FEED2_ARTIST_ADDED_EVENT', [
                                        'artist' => $notification->details->model->name,
                                        'object' => trans('web.EVENT_ARTICLE'),
                                        ])
                                    !!}
                                @endif
                            @elseif ($notification->action == 'addToPlaylist')
                                @if(isset($notification->details->model))
                                    {!! __(count($notification->details->objects) == 1 ? 'web.NOTIFICATION_USER_ADDED' : 'web.NOTIFICATION_USER_ADDED_MANY', [
                                        'user' => $notification->user->name,
                                        'objectCount' => count($notification->details->objects),
                                        'objects' => trans('web.SONG_PLURAL'),
                                        'object' => trans('web.SONG_ARTICLE'),
                                        'destination' => $notification->details->model->title,
                                        ])
                                    !!}
                                @endif
                            @elseif ($notification->action == 'addSong')
                                @if(isset($notification->details->model))
                                    {!! __(count($notification->details->objects) == 1 ? 'web.NOTIFICATION_ARTIST_UPLOADED' : 'web.NOTIFICATION_ARTIST_UPLOADED_MANY', [
                                        'artist' => $notification->details->model->name,
                                        'objectCount' => count($notification->details->objects),
                                        'objects' => trans('web.SONG_PLURAL'),
                                        'object' => trans('web.SONG_ARTICLE'),
                                        ])
                                    !!}
                                @endif
                            @endif
                            <span class="notification-time text-secondary">{{ timeElapsedString($notification->created_at) }}</span>
                        </p>
                    </div>
                    <span class="module-time text-secondary">{{ timeElapsedString($notification->created_at) }}</span>
                </div>
            </a>
        @endif
    @endforeach
@else
    <p class="no-notifications" data-translate-text="YOU_HAVE_NO_NOTIFS">{{ __('web.YOU_HAVE_NO_NOTIFS') }}</p>
@endif