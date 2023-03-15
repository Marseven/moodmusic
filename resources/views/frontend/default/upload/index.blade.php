@extends('index')
@section('content')
    @if(intval(\App\Models\Role::getValue('artist_max_songs')) != 0 && $artist->song_count > intval(\App\Models\Role::getValue('artist_max_songs')))
        <div id="page-content">
            <h1 class="text-center mt-5">{{ __('web.UPLOAD_LIMITED_TITLE') }} ({{ $artist->song_count }}/{{ intval(\App\Models\Role::getValue('artist_max_songs')) }})</h1>
            <h2 class="text-center mb-5 text-secondary">{{ __('web.UPLOAD_LIMITED_DESC') }}</h2>
            <div class="d-flex justify-content-center align-content-center">
                <a href="{{ route('frontend.settings.subscription') }}">
                    <svg height="250px" viewBox="0 0 464 464" width="250px" xmlns="http://www.w3.org/2000/svg"><path d="m360 288h88c8.835938 0 16-7.164062 16-16v-224c0-8.835938-7.164062-16-16-16h-88" fill="#ffc24f"/><path d="m360 258.015625v29.984375h88c8.835938 0 16-7.164062 16-16v-138.703125c-26.632812 47.664063-61.898438 89.957031-104 124.71875zm0 0" fill="#fab839"/><path d="m144 0h176c8.835938 0 16 7.164062 16 16v288c0 8.835938-7.164062 16-16 16h-176c-8.835938 0-16-7.164062-16-16v-288c0-8.835938 7.164062-16 16-16zm0 0" fill="#4398d1"/><path d="m144.800781 320h175.199219c8.835938 0 16-7.164062 16-16v-231.367188c-37.550781 134.605469-132.574219 211.878907-191.199219 247.367188zm0 0" fill="#3e8cc7"/><path d="m16 32c-8.835938 0-16 7.164062-16 16v224c0 8.835938 7.164062 16 16 16h88v-256zm0 0" fill="#88b337"/><path d="m104 288v-44.222656c-19.207031 16.144531-39.523438 30.921875-60.800781 44.222656zm0 0" fill="#80ab30"/><path d="m200 232h64c8.835938 0 16 7.164062 16 16s-7.164062 16-16 16h-64c-8.835938 0-16-7.164062-16-16s7.164062-16 16-16zm0 0" fill="#87ced9"/><path d="m56 216c-8.835938 0-16 7.164062-16 16s7.164062 16 16 16h48v-32zm0 0" fill="#a7d64f"/><path d="m408 216h-48v32h48c8.835938 0 16-7.164062 16-16s-7.164062-16-16-16zm0 0" fill="#ffe7ba"/><path d="m330.6875 360.214844c-4.648438-.792969-9.410156.507812-13.011719 3.550781-3.601562 3.042969-5.679687 7.519531-5.675781 12.234375 0-8.835938-7.164062-16-16-16s-16 7.164062-16 16c0-8.835938-7.164062-16-16-16s-16 7.164062-16 16v-63.144531c.179688-8.039063-5.429688-15.046875-13.3125-16.640625-4.648438-.792969-9.410156.507812-13.011719 3.550781-3.601562 3.042969-5.679687 7.519531-5.675781 12.234375v112l-33.351562-20c-4.144532-2.496094-9.277344-2.695312-13.601563-.527344l-1.253906.632813c-4.777344 2.394531-7.792969 7.277343-7.792969 12.621093-.003906 4.5625 2.199219 8.847657 5.910156 11.496094l50.089844 35.777344h128v-87.144531c.179688-8.039063-5.429688-15.046875-13.3125-16.640625zm0 0" fill="#fec9a3"/><g fill="#126099"><path d="m184 32h16v16h-16zm0 0"/><path d="m216 32h64v16h-64zm0 0"/><path d="m160 72h16v32h-16zm0 0"/><path d="m192 72h16v32h-16zm0 0"/><path d="m224 72h16v32h-16zm0 0"/><path d="m256 72h16v32h-16zm0 0"/><path d="m288 72h16v32h-16zm0 0"/><path d="m224 128h56v16h-56zm0 0"/><path d="m184 128h24v16h-24zm0 0"/><path d="m184 160h96v16h-96zm0 0"/><path d="m200 192h64v16h-64zm0 0"/></g><path d="m80 56h24v16h-24zm0 0" fill="#70942d"/><path d="m24 96h16v24h-16zm0 0" fill="#70942d"/><path d="m56 96h16v24h-16zm0 0" fill="#70942d"/><path d="m88 96h16v24h-16zm0 0" fill="#70942d"/><path d="m48 56h16v16h-16zm0 0" fill="#70942d"/><path d="m56 144h48v16h-48zm0 0" fill="#70942d"/><path d="m72 176h32v16h-32zm0 0" fill="#70942d"/><path d="m360 56h56v16h-56zm0 0" fill="#ed9624"/><path d="m424 96h16v24h-16zm0 0" fill="#ed9624"/><path d="m392 96h16v24h-16zm0 0" fill="#ed9624"/><path d="m360 96h16v24h-16zm0 0" fill="#ed9624"/><path d="m360 144h40v16h-40zm0 0" fill="#ed9624"/><path d="m360 176h24v16h-24zm0 0" fill="#ed9624"/><path d="m216 464h128v-63.464844c-39.496094 32.710938-92.929688 51.601563-130.550781 61.601563zm0 0" fill="#f7bb8f"/></svg>
                </a>
            </div>
        </div>
    @else
        <div id="page-content">
            <div class="linear-header upload">
                <div class="container">
                    <h1>{{ __('web.UPLOAD_TITLE') }}</h1>
                    <h2>{{ __('web.UPLOAD_DESC') }}</h2>
                </div>
                <div class="container upload-helper">
                    <form id="fileupload" data-template="template-upload" method="POST" enctype="multipart/form-data">
                        <div class="upload-container">
                            <h1>Lets get started</h1>
                            @if(config('settings.ffmpeg'))
                                <p class="text-secondary" data-translate-text="UPLOAD_ALL_FORMAT_TIP">{{ __('web.UPLOAD_ALL_FORMAT_TIP') }}</p>
                            @else
                                <p class="text-secondary"  data-translate-text="UPLOAD_MP3_TIP">{{ __('web.UPLOAD_MP3_TIP') }}</p>
                            @endif
                            <div id="upload-file-button" class="btn btn-primary">
                                <span data-translate-text="CHOOSE_A_FILE">{{ __('web.CHOOSE_A_FILE') }}</span>
                                <input id="upload-file-input" type="file" accept="audio/*" name="file" multiple>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="uploaded-files card-columns card-2-columns"></div>
        </div>
        @include('commons.upload-item', ['genres' => $allowGenres, 'moods' => $allowMoods])
    @endif
@endsection
