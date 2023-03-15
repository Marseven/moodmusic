<script type="text/x-tmpl" id="tmpl-song-item">
<div class="module module-row song tall grid-item draggable" data-type="song" data-id="{%=o.id%}">
    <div class="fav-btn">
        <a class="btn btn-icon-only favorite song-row-favorite" data-type="song" data-id="{%=o.id%}">
            <svg class="off" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg>
            <svg class="on" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </a>
    </div>
    <div class="img-container" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{%=o.id%}">
        <img class="img" src="{%=o.artwork_url%}">
        <div class="row-actions primary song-play-action">
            <a class="btn play-lg play-object" data-type="song" data-id="{%=o.id%}">
                <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
            </a>
        </div>
    </div>
    <div class="metadata" data-toggle="contextmenu" data-trigger="right" data-type="song" data-id="{%=o.id%}">
        <div class="title">{%=o.title%}</div>
        <div class="artist">
            {% for (var i=0; i<o.artists.length; i++) { %}
            <a href="{%=o.artists[i].permalink_url%}">{%=o.artists[i].name%}</a>
            {% } %}
        </div>
        <div class="duration">{%=$.engineUtils.humanTime(o.duration)%}</div>
    </div>
    <div class="row-actions secondary">
        <a class="btn options" data-toggle="contextmenu" data-trigger="left" data-type="song" data-id={%=o.id%}>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
        </a>
    </div>
</div>
</script>
<script type="text/x-tmpl" id="tmpl-now-playing-card">
<div class="img">
    <img src="{%=o.artwork_url%}" alt="{%=o.title%}">
    <div class="card-actions primary">
        <div class="button-process-container" data-song-id="{%=o.id%}">
            <div class="buttonProgressBorder button-progress-active-border">
                <div class="button-progress-circle"></div>
            </div>
        </div>
        <a class="btn play-lg play-object" data-type="song" data-id="{%=o.id%}">
            <svg class="icon-play" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
            <svg class="icon-pause" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
            <svg class="icon-waiting embed_spin" width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 252.264 252.264" xml:space="preserve"><path d="M248.988,80.693c-3.423-2.335-8.089-1.452-10.422,1.97l-15.314,22.453c-9.679-44.721-49.575-78.354-97.123-78.354c-26.544,0-51.498,10.337-70.265,29.108c-2.929,2.929-2.928,7.678,0.001,10.606c2.929,2.929,7.678,2.929,10.606-0.001c15.933-15.937,37.12-24.713,59.657-24.713c41.32,0,75.815,29.921,82.98,69.228l-26.606-18.147c-3.423-2.336-8.089-1.452-10.422,1.97c-2.334,3.422-1.452,8.088,1.971,10.422l39.714,27.087c0.003,0.002,0.005,0.003,0.007,0.005c0.97,0.661,2.039,1.064,3.128,1.225c0.362,0.053,0.727,0.08,1.091,0.08c2.396,0,4.751-1.146,6.203-3.274l26.764-39.242C253.293,87.693,252.41,83.027,248.988,80.693z"></path><path d="M187.196,184.351c-16.084,16.863-37.77,26.15-61.065,26.15c-41.317-0.001-75.813-29.921-82.978-69.227l26.607,18.147c1.293,0.882,2.764,1.305,4.219,1.305c2.396,0,4.751-1.145,6.203-3.274c2.334-3.422,1.452-8.088-1.97-10.422l-39.714-27.087c-0.002-0.001-0.004-0.003-0.006-0.005c-3.424-2.335-8.088-1.452-10.422,1.97L1.304,161.149c-2.333,3.422-1.452,8.088,1.97,10.422c1.293,0.882,2.764,1.304,4.219,1.304c2.397,0,4.751-1.146,6.203-3.275l15.313-22.453c9.68,44.72,49.577,78.352,97.121,78.352c27.435,0,52.977-10.938,71.919-30.797c2.859-2.997,2.747-7.745-0.25-10.604C194.8,181.241,190.053,181.353,187.196,184.351z"></path></svg>
        </a>
    </div>
</div>
<div class="inner">
    <div class="actions-primary desktop">
        <a class="btn add-song" data-type="song" data-id="{%=o.id%}">
            <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
            <span data-translate-text="ADD_SONG">Add Song</span>
            <span class="caret"></span>
        </a>
        <a class="btn share" data-type="song" data-id="{%=o.id%}">
            <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"></path><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"></path></svg>
            <span data-translate-text="SHARE">{{ __('web.SHARE') }}</span>
        </a>
    </div>
    <h1><a href="{%=o.permalink_url%}">{%=o.title%}</a></h1>
    <div class="byline">
        <span>Song by</span>
        <span class="artist-link">{% for (var i=0; i<o.artists.length; i++) { %}<a href="{%=o.artists[i].permalink_url%}">{%=o.artists[i].name%}</a>{% } %}</span>
        {% if (o.album) { %}
        <span class="has-album" data-translate-text="ON">{{ __('web.ON') }}</span>
        <a class="album-link" href="{%=o.album.permalink_url%}">{%=o.album.title%}</a>
        {% } %}
    </div>
    <ul class="stat-summary">
        <li><span id="fans-count" class="num">-</span><span class="label" data-translate-text="FANS">Fans</span></li>
    </ul>
</div>
</script>

<script type="text/x-tmpl" id="tmpl-song-popover">
<div class="tooltip song-popover-container">
    <div class="single-song">
        <div class="content">
            <a class="image song-link" href="{%=o.permalink_url%}">
                <img class="song-artwork" src="{%=o.artwork_url%}" width="90" height="90" alt="{%=o.title%}">
            </a>
            <div class="info">
                <h2>
                    <a class="song-link" href="{%=o.permalink_url%}">{%=o.title%}</a>
                </h2>
                <ul class="byline">
                    <li class="artist-link">{% for (var i=0; i<o.artists.length; i++) { %}<a href="{%=o.artists[i].permalink_url%}">{%=o.artists[i].name%}</a>{% } %}</li>
                    {% if (o.album) { %}
                    <li class="album-link">
                        <span class="on-album" data-translate-text="ON">on</span> <a class="album-link" href="{%=o.album.permalink_url%}">{%=o.album.title%}</a>
                    </li>
                    {% } %}
                </ul>
                <ul class="metadata">
                    <li><span class="collectors-num">{%=o.loves%}</span> <span class="meta-label" data-translate-text="COLLECTORS">Collectors</span></li>
                </ul>
                <div class="tags-wrapper"></div>
            </div>
        </div>
        <div class="actions">
            <div class="actions-right">
                <a class="btn play play-object" data-type="song" data-id="{%=o.id%}">
                    <svg height="26" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M8 5v14l11-7z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    <span data-translate-text="PLAY_SONG">Play Song</span>
                </a>
                <a class="btn" data-toggle="collection" data-type="song" data-id="{%=o.id%}" {% if(o.library) { %} data-init="true" {% } %}>
                    <svg class="collection-on" height="26" width="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                    <svg class="collection-off" height="26" width="18" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"></path><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path></svg>
                    <span class="collection-on" data-translate-text="SONG_ADD_LIBRARY">{{ __('web.SONG_ADD_LIBRARY') }}</span>
                    <span class="collection-off" data-translate-text="CONTEXT_REMOVE_FROM_LIBRARY">{{ __('web.CONTEXT_REMOVE_FROM_LIBRARY') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
</script>

<script type="text/x-tmpl" id="tmpl-user-popover">
<div id="user-popover-container" class="tooltip user-tooltip">
    <div class="user">
        <div class="content">
            <a class="image"><img src="" width="90" height="90"></a>
            <div class="info">
                <h2><a></a></h2>
                <ul class="metadata">
                    <li><a><span id="song-count" class="num"></span> <span class="label" data-translate-text="SONGS">{{ __('web.SONGS') }}</span></a></li>
                    <li><a><span id="playlist-count" class="num"></span> <span class="label" data-translate-text="PLAYLISTS">{{ __('web.PLAYLISTS') }}</span></a></li>
                    <li><a><span id="follower-count" class="num"></span> <span class="label" data-translate-text="FOLLOWERS">{{ __('web.FOLLOWERS') }}</span></a></li>
                </ul>
            </div>
        </div>
        <div class="actions">
            <a class="btn play-station" data-type="user">
                <i class="icon icon-station-gray"></i>
                <span data-translate-text="START_STATION">{{ __('web.START_STATION') }}</span>
            </a>
            <a class="btn suggest-music" data-user-id="25">
                <svg height="26" viewBox="0 0 24 24" width="14" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0z" fill="none"></path><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"></path></svg>
                <span data-translate-text="SUGGEST_MUSIC">{{ __('web.SUGGEST_MUSIC') }}</span>
            </a>
        </div>
    </div>
</div>
</script>

<script type="text/x-tmpl" id="tmpl-sidebar-friend">
<div class="module module-cell draggable" data-id="{%=o.id%}" id="friend-id-{%=o.id%}" data-friend-id="{%=o.id%}" data-username-status="{%=o.username%}" data-type="user">
    <a class="img-container" href="{%=o.permalink_url%}" data-toggle="contextmenu" data-trigger="right" data-type="user" data-id="{%=o.id%}">
        <img class="img" src="{%=o.artwork_url%}" alt="{%=o.name%}">
    </a>
    <div class="module-inner" data-toggle="contextmenu" data-trigger="right" data-type="user" data-id="{%=o.id%}">
        <a class="headline" href="{%=o.permalink_url%}">{%=o.name%}</a>
        <a class="subtitle"></a>
    </div>
    <span class="offline hide"></span>
    <span class="online hide"></span>
</div>
</script>

<script type="text/x-tmpl" id="tmpl-sidebar-playlist">
<div class="sidebar-playlist draggable" data-toggle="contextmenu" data-trigger="right" data-type="playlist" data-id="{%=o.id%}">
    <div class="inner">
        <div class="icon playlist">
            <div class="img-container">
                <img src="{%=o.artwork_url%}" alt="{%=o.title%}">
            </div>
        </div>
        <a class="btn play play-object" data-type="playlist" data-id="{%=o.id%}">
            <div class="icon play">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                    <path d="M0 0h24v24H0z" fill="none"/>
                </svg>
            </div>
        </a>
        <a class="playlist-link" href="{%=o.permalink_url%}">
            <span class="name">{%=o.title%}</span>
        </a>
    </div>
</div>
</script>

<script type="text/x-tmpl" id="tmpl-share-autocomplete-item">
    <ul class="autocomplete-list autocomplete-list-songs">
    {% for (var i=0; i<o.items.length; i++) { %}
        <li class="autocomplete-list-item community-share-object" data-type="{%=o.type%}" data-id="{%=o.items[i].id%}">
            <a class="autocomplete-item-link">
                <span class="image">
                    <img src="{%=o.items[i].artwork_url%}">
                </span>
                {% if(o.type === 'artist') { %}
                    <h3 class="autocomplete-item-name">{%=o.items[i].name%}</h3>
                {% } else { %}
                    <h3 class="autocomplete-item-name">{%=o.items[i].title%}</h3>
                {% } %}
                {% if(o.type === 'song' || o.type === 'album') { %}
                    <span class="autocomplete-item-subtext">{%=o.items[i].artists.map(function (artist) {return artist.name}).join(", ")%}</span>
                {% } %}
            </a>
        </li>
    {% } %}
    </ul>
</script>

<script type="text/x-tmpl" id="tmpl-suggest-item">
    <div class="autocomplete-section-title">
        <span data-translate-text="{%=o.type.toUpperCase()%}">{%=Language.text[o.type.toUpperCase()]%}</span>
    </div>
    <ul class="autocomplete-list autocomplete-list-songs">
    {% for (var i=0; i<o.items.length; i++) { %}
        <li class="autocomplete-list-item community-share-object" data-type="{%=o.type%}" data-id="{%=o.items[i].id%}">
            <a class="autocomplete-item-link" href="{%=o.items[i].permalink_url%}">
                <span class="image">
                    <img src="{%=o.items[i].artwork_url%}">
                </span>
                {% if(o.type === 'artist') { %}
                    <h3 class="autocomplete-item-name">{%=o.items[i].name%}</h3>
                {% } else { %}
                    <h3 class="autocomplete-item-name">{%=o.items[i].title%}</h3>
                {% } %}
                {% if(o.type === 'song' || o.type === 'album') { %}
                    <span class="autocomplete-item-subtext">{%=o.items[i].artists.map(function (artist) {return artist.name}).join(", ")%}</span>
                {% } %}
            </a>
        </li>
    {% } %}
    </ul>
</script>

<script type="text/x-tmpl" id="tmpl-cart-item">
    <li class="cart__item">
        <div class="cart__item__image pull-left">
            <a href="{%=o.associatedModel.permalink_url%}">
                <img src="{%=o.associatedModel.artwork_url%}" alt="" title="{%=o.associatedModel.title%}">
            </a>
        </div>
        <div class="cart__item__control">
            <div class="cart__item__delete" data-action="remove-from-cart" data-id="{%=o.id%}">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
            </div>
        </div>
        <div class="cart__item__info">
            {% if(o.associatedModel.artists !== undefined) { %}
                <div class="cart__item__info__title">
                    <h2><a href="{%=o.associatedModel.permalink_url%}">{%=o.associatedModel.title%}</a></h2>
                    <p>
                        <span class="text-secondary">by</span>
                        <span>{%=o.associatedModel.artists.map(function (artist) {return artist.name}).join(", ")%}</span>
                    </p>
                </div>
                <div class="cart__item__info__price"><span class="info-label">Price:</span><span>{{ __('symbol.' . config('settings.currency', 'USD')) }}{%=o.price%}</span></div>
                <div class="cart__item__info__qty badge badge-secondary">{%= o.attributes.orderable_type === 'App\\Models\\Song' ? '{{ __('web.SONG') }}' : '{{ __('web.ALBUM') }}' %}</div>
            {% } else { %}
                <div class="cart__item__info__title">
                    <h2><a href="{%=o.associatedModel.permalink_url%}">{%=o.associatedModel.title%}</a></h2>
                    <p>
                        <span>{{ __('web.TIME') }}: {%=o.associatedModel.started_at_human_time%}</span>
                    </p>
                </div>
                <div class="cart__item__info__price"><span class="info-label">Price:</span><span>{{ __('symbol.' . config('settings.currency', 'USD')) }}{%=o.price%}</span></div>
                <div class="cart__item__info__qty badge badge-secondary">{{ __('web.TICKET') }}</div>
            {% } %}
        </div>
    </li>
</script>

<script type="text/x-tmpl" id="tmpl-filter-item">
    <div class="css-ongm40 e1bcbke2">
        <div class="filterOption" data-action="filter-query" data-term="{%=o.type%}" data-value="{%=o.id%}" data-mask="{%=o.name%}" {%if( $.enginStore.browse.params[o.type + 's'].includes(o.id) ) { %}  data-init="true"   {% } %}>{%=o.name%}</div>
        <div class="css-98z92p">
            <svg fill='white' xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24'><path d='M0 0h24v24H0z' fill='none'/><path d='M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z'/></svg>
        </div>
    </div>
</script>

<script type="text/x-tmpl" id="tmpl-mask-item">
    <div class="css-1uswwka eb8kde96" data-term="{%=o.term%}" data-value="{%=o.value%}"> {%=o.mask%}<span class="pillButtonComma css-zj6i25 eb8kde97">,</span>
        <div class="clearPillTrigger css-1gexovb eb8kde98" data-action="clear-filter-fill" data-term="{%=o.term%}" data-value="{%=o.value%}"></div>
    </div>
</script>

<script type="text/x-tmpl" id="tmpl-snapshot-item">
    <div class="module module-row tall {%=o.type%}">
        <div class="img-container">
            <img class="img" src="{%=o.item.artwork_url%}" alt="{% if(o.type === 'artist' || o.type === 'user') { %}{%=o.item.name%}{% } else { %}{%=o.item.title%}{% } %}">
        </div>
        <div class="metadata {%=o.type%}">
            <a href="{%=o.item.permalink_url%}" class="title {%=o.type%}-link">{% if(o.type === 'artist' || o.type === 'user') { %}{%=o.item.name%}{% } else { %}{%=o.item.title%}{% } %}</a>
            {% if(o.type === 'song' || o.type === 'album') { %}
                 <div class="meta-inner"><span data-translate-text="BY">by</span> {%=o.item.artists.map(function (artist) {return artist.name}).join(", ")%}</div>
            {% } %}
        </div>
    </div>
</script>