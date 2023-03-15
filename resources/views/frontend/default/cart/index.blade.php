@extends('index')
@section('content')
    <div id="page-content">
        <div class="container">
            @if(count($cart))
                <div class="checkout-container">
                    <div class="css-17ftu1m">{{ __('web.CHECKOUT') }}</div>
                    @foreach($cart as $item)
                        <div class="src-mainapp-pages-trackshop-___TrackTable__track___1KfAv">
                            <div class="src-mainapp-pages-trackshop-___TrackTable__trackInfo___1BkaZ">
                                <span class="src-mainapp-pages-trackshop-___TrackTable__trackInfoFirstLine___1pleZ css-1sl0tih">
                                    <span class="src-mainapp-pages-trackshop-___TrackTable__trackTitle___1wYMc">{{ $item->associatedModel->title }}</span>
                                    <span class="css-1sl0tih">{{ $item->price }} {{ config('settings.currency', 'USD') }}</span>
                                </span>
                                @if(isset($item->associatedModel->artists))
                                    <span class="src-mainapp-pages-trackshop-___TrackTable__trackInfoSecondLine___IitMi css-1a0jd54">
                                        <span>@foreach($item->associatedModel->artists as $artist){!! $artist->name !!}@if(!$loop->last), @endif @endforeach</span>
                                    </span>
                                @endif
                            </div>
                            <svg data-action="remove-from-cart" data-id="{{ $item->id }}" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="src-mainapp-pages-trackshop-___TrackTable__closeIcon___21Tg_">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5874 12.0029L6.00337 16.5899L7.41713 18.0046L12.0014 13.4173L16.5853 18.0022L17.9994 16.5878L13.4152 12.0026L18.0002 7.41467L16.5865 6L12.0012 10.5883L7.41406 6.00015L6 7.41452L10.5874 12.0029Z"></path>
                            </svg>
                        </div>
                    @endforeach
                    <div class="src-mainapp-pages-trackshop-___Trackshop__totalSection___1_bz4">
                        <div class="src-mainapp-pages-trackshop-___GrandTotalSection__opacityWrapper___1eQI8">
                            <span class="src-mainapp-pages-trackshop-___GrandTotalSection__subTotal___1_xyL css-1sl0tih">{{ __('web.SUBTOTAL') }}<span class="css-1sl0tih">{{ Cart::getSubtotal() }} {{ config('settings.currency', 'USD') }}</span></span>
                        </div>
                    </div>
                    @if(count(Cart::getConditions()))
                        <div class="src-mainapp-pages-trackshop-___Trackshop__totalSection___1_bz4">
                            <div class="src-mainapp-pages-trackshop-___GrandTotalSection__opacityWrapper___1eQI8">
                                <span class="src-mainapp-pages-trackshop-___GrandTotalSection__subTotal___1_xyL css-1sl0tih">{{ __('web.DISCOUNT') }}<span class="css-1sl0tih">-{{ number_format((Cart::getSubtotal() - Cart::geTtotal()), 2)  }} {{ config('settings.currency', 'USD') }}</span></span>
                            </div>
                        </div>
                        <div class="src-mainapp-pages-trackshop-___Trackshop__totalSection___1_bz4">
                            <div class="src-mainapp-pages-trackshop-___GrandTotalSection__opacityWrapper___1eQI8">
                                <span class="src-mainapp-pages-trackshop-___GrandTotalSection__subTotal___1_xyL css-1sl0tih">{{ __('web.total') }}<span class="css-1sl0tih">{{ number_format(Cart::geTtotal(), 2)  }} {{ config('settings.currency', 'USD') }}</span></span>
                            </div>
                        </div>
                    @endif
                    @if(! count(Cart::getConditions()))
                        <div class="d-flex mb-4">
                            <div class="accordion w-100" id="accordionCoupon">
                                <div class="card border-bottom">
                                    <div class="card-header" id="headingCoupon">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseCoupon" aria-expanded="false" aria-controls="collapseCoupon">
                                                I have a coupon
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseCoupon" class="collapse" aria-labelledby="headingCoupon" data-parent="#accordionCoupon">
                                        <div class="card-body">
                                            <form id="form-coupon-apply" class="ajax-form" method="post" action="{{ route('frontend.cart.coupon.apply') }}">
                                                <div class="alert alert-danger error hide">
                                                    <div class="message"></div>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="text" name="code" class="form-control" placeholder="Enter coupon code" aria-label="Enter coupon code" aria-describedby="basic-addon2" required>
                                                    <div class="input-group-append">
                                                        <button class="input-group-text" type="submit" id="basic-addon2">{{ __('web.APPLY') }}</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="d-block">
                        <a class="btn btn-primary btn-block float-right make-payment" data-should-hide-description="true">{{ __('web.PURCHASE') }}</a>
                    </div>
                </div>
            @else
                <div class="empty-page no-nav">
                    <div class="empty-inner">
                        <h2 data-translate-text="CART_IS_EMPTY">{{ __('web.CART_IS_EMPTY') }}</h2>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection