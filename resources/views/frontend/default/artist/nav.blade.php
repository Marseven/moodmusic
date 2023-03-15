<div id="page-nav">
    <div class="outer">
        <ul>
            <li><a href="{{ $artist->permalink_url }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.artist') active @endif" data-translate-text="OVERVIEW">Overview<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.artist.albums', ['id' => $artist->id, 'slug' => str_slug($artist->name) ? str_slug($artist->name) : str_replace(' ', '-', $artist->name)]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.artist.albums') active @endif" data-translate-text="ALBUMS">Albums<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.artist.podcasts', ['id' => $artist->id, 'slug' => str_slug($artist->name) ? str_slug($artist->name) : str_replace(' ', '-', $artist->name)]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.artist.podcasts') active @endif" data-translate-text="PODCASTS">Podcasts<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.artist.similar', ['id' => $artist->id, 'slug' => str_slug($artist->name) ? str_slug($artist->name) : str_replace(' ', '-', $artist->name)]) }}" class="page-nav-link  @if(Route::currentRouteName() == 'frontend.artist.similar') active @endif" data-translate-text="SIMILAR_ARTISTS">Related Artists<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.artist.followers', ['id' => $artist->id, 'slug' => str_slug($artist->name) ? str_slug($artist->name) : str_replace(' ', '-', $artist->name)]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.artist.followers') active @endif" data-translate-text="FOLLOWERS">Followers<div class="arrow"></div></a></li>
            <li><a href="{{ route('frontend.artist.events', ['id' => $artist->id, 'slug' => str_slug($artist->name) ? str_slug($artist->name) : str_replace(' ', '-', $artist->name)]) }}" class="page-nav-link @if(Route::currentRouteName() == 'frontend.artist.events') active @endif" data-translate-text="EVENTS">Events<div class="arrow"></div></a></li>
        </ul>
    </div>
</div>
<script>var artist_data_{{ $artist->id }} = {!! json_encode($artist->makeHidden('songs')->makeHidden('activities')) !!}</script>
