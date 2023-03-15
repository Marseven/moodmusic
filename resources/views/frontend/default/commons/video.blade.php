@foreach ($videos as $video)
    <script>var video_data_{{ $video->id }} = {!! json_encode($video) !!}</script>
    <div class="module module-cell swiper swiper-slide">
        <div class="img-container">
            <a href="{{ $video->permalink_url }}">
                <img class="img" src="{{$video->artwork_url}}">
            </a>
            <div class="video-description justify-content-center">
                <div class="title">{{$video->title}}</div>
                <div class="description">{{$video->description}}</div>
                <a href="{{ $video->permalink_url }}" class="btn btn-hover">Play Now</a>
            </div>
        </div>
    </div>
@endforeach