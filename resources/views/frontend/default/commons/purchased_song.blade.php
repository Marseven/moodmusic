<script>var song_data_{{ $song->id }} = {!! json_encode($song) !!}</script>
<div class="flat-song-list">
    <div class="flat-song-list-play-container">
        <a title="Play" class="flat-song-list-play-button play-object" data-type="song" data-id="{{ $song->id }}" style="background-image: url('{{ $song->artwork_url }}')">
            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#FFF">
                <path d="M20 12L8 5V19L20 12Z"></path>
            </svg>
        </a>
    </div>
    <div class="flat-song-list-info">
        <div class="flat-song-list-info-inner">
            <div title="{!! $song->title !!}" class="css-bzqkha">
                <a href="{{ $song->permalink_url }}">{!! $song->title !!}</a>
            </div>
            <div class="css-kkg4pz">
                @foreach($song->artists as $artist)<a href="{{ $artist->permalink_url }}" title="{!! $artist->name !!}">{!! $artist->name !!}</a>@if(!$loop->last), @endif @endforeach
            </div>
        </div>
    </div>
    <div class="flat-song-list-subtitle text-secondary">{{ humanTime($song->duration) }}</div>

    @if($song->flac)
        <button class="text-secondary flat-song-list-button btn-file-format">
            <a href="{{ route('frontend.purchased.download.song', ['id' => $song->id, 'format' => 'flac']) }}" target="_blank">
                <span>FLAC</span>
            </a>
        </button>
    @else
        <button class="basic-tooltip text-secondary flat-song-list-button btn-file-format disabled" tooltip="{{ __('web.NOT_AVAILABLE') }}">
            <span>FLAC</span>
        </button>
    @endif
    @if($song->wav)
        <button class="text-secondary flat-song-list-button btn-file-format">
            <a href="{{ route('frontend.purchased.download.song', ['id' => $song->id, 'format' => 'wav']) }}" target="_blank">
                <span>WAV</span>
            </a>
        </button>
    @else
        <button class="basic-tooltip text-secondary flat-song-list-button btn-file-format disabled" tooltip="{{ __('web.NOT_AVAILABLE') }}">
            <span>WAV</span>
        </button>
    @endif

    @if($song->mp3)
        <button class="text-secondary flat-song-list-button btn-file-format">
            <a href="{{ route('frontend.purchased.download.song', ['id' => $song->id, 'format' => 'mp3']) }}" target="_blank">
                <span>MP3</span>
            </a>
        </button>
    @else
        <button class="basic-tooltip text-secondary flat-song-list-button btn-file-format disabled" tooltip="{{ __('web.NOT_AVAILABLE') }}">
            <span>MP3</span>
        </button>
    @endif
    @if($song->getFirstMedia('attachment'))
        <button class="text-secondary flat-song-list-button btn-file-format">
            <a href="{{ route('frontend.purchased.download.song', ['id' => $song->id, 'format' => 'attachment']) }}" target="_blank">
                <span>ZIP</span>
            </a>
        </button>
    @endif
</div>
