@extends('index')
@section('content')
    <div class="get-verify-now">
        <div class="claim-hero">
            <div class="container claim-container">
                <div class="row">
                    <div class="col">
                        <div class="vertical-align">
                            <p class="claim-subtitle">{{ __('web.CLAIM_FOR_ARTIST') }}</p>
                            <h1 class="claim-display-title">{{ __('web.CLAIM_DISPLAY_TITLE') }}</h1>
                            <p class="claim-h3 right">{{ __('web.CLAIM_DISPLAY_DESCRIPTION') }}</p>
                            <a class="button-white orange w-button claim-artist-access">{{ __('web.CLAIM_GET_VERIFIED_NOW') }}</a>
                        </div>
                    </div>
                    <div class="claim-column-right col">
                        <img src="{{ asset('skins/default/images/artist-request-landing.png') }}" width="540" alt="Request landing" class="claim-landing-image">
                    </div>
                </div>
            </div>
        </div>
        <div class="container claim-container mt-5">
            <h2 class="claim-h2 mb-5" data-translate-text="CLAIM_JOIN_REASON_TITLE">{{ __('web.CLAIM_JOIN_REASON_TITLE') }}</h2>
            <div class="row">
                <div class="card-info w-col col-lg-4 col-12 text-center">
                    <img src="{{ asset('skins/default/images/artist-request-connect-sample.png') }}" alt="" class="card-image">
                    <h3 class="claim-feature-h3 text-center" data-translate-text="CLAIM_JOIN_REASON_1_T">{{ __('web.CLAIM_JOIN_REASON_1_T') }}</h3>
                    <p class="claim-h3-regular text-secondary" data-translate-text="CLAIM_JOIN_REASON_1_D">{{ __('web.CLAIM_JOIN_REASON_1_D') }}</p>
                </div>
                <div class="card-info w-col col-lg-4 col-12 text-center">
                    <img src="{{ asset('skins/default/images/artist-request-verified-button.png') }}" alt="" class="card-image">
                    <h3 class="claim-feature-h3 text-center" data-translate-text="CLAIM_JOIN_REASON_2_T">{{ __('web.CLAIM_JOIN_REASON_2_T') }}</h3>
                    <p class="claim-h3-regular text-secondary" data-translate-text="CLAIM_JOIN_REASON_2_D">{!! __('web.CLAIM_JOIN_REASON_2_D') !!}</p>
                </div>
                <div class="card-info w-col col-lg-4 col-12 text-center">
                    <img src="{{ asset('skins/default/images/artist-request-profile-sample.png') }}" alt="" class="card-image">
                    <h3 class="claim-feature-h3 text-center" data-translate-text="CLAIM_JOIN_REASON_3_T">{{ __('web.CLAIM_JOIN_REASON_3_T') }}</h3>
                    <p class="claim-h3-regular text-secondary" data-translate-text="CLAIM_JOIN_REASON_4_D">{{ __('web.CLAIM_JOIN_REASON_4_D') }}</p>
                </div>
            </div>

        </div>

        <div class="container claim-container mt-5 desktop">
            <h2 class="claim-h2 mb-5" data-translate-text="CLAIM_JOIN_SAMPLE">{{ __('web.CLAIM_JOIN_SAMPLE') }}</h2>
            <img src="{{ asset('skins/default/images/artist-request-verified-sample-white.png') }}" width="1140" alt="" class="verified-artists">
        </div>

        <div class="va-section-footer mt-5">
            <div class="container">
                <h2 class="claim-h2-white padding-bottom-40px" data-translate-text="CLAIM_NOW_TEXT">{{ __('web.CLAIM_NOW_TEXT') }}</h2>
                <p class="claim-h3" data-translate-text="CLAIM_NOW_DESCRIPTION">{!! __('web.CLAIM_NOW_DESCRIPTION') !!}</p>
                <div class="d-flex justify-content-center">
                    <a class="button-white w-button claim-artist-access text-primary" data-translate-text="CLAIM_NOW_BUTTON_TEXT">{{ __('web.CLAIM_NOW_BUTTON_TEXT') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection