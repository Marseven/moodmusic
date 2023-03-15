@extends('index')
@section('content')
    @include('settings.nav')
    <div id="page-content">
        @if(auth()->user()->subscription)
            <div class="container">
                <div class="page-header ">
                    <h1 ><span data-translate-text="SETTINGS_TITLE">{{ __('web.SETTINGS_TITLE') }}</span> / <span data-translate-text="SETTINGS_TITLE_SUBSCRIPTION">{{ __('web.SETTINGS_TITLE_SUBSCRIPTION') }}</span></h1>
                </div>
                <div class="subscription-container anywhere selected">
                    <h2>{{ auth()->user()->subscription->service->title }}</h2>
                    <p class="price">{{ __('symbol.' . strtoupper(auth()->user()->subscription->currency)) }}{{ number_format(auth()->user()->subscription->amount) }} /
                        @if(auth()->user()->subscription->service->plan_period != 1){{ auth()->user()->subscription->service->plan_period }}@endif
                        @switch(auth()->user()->subscription->service->plan_period_format)
                            @case('D')
                            Day
                            @break
                            @case('W')
                             Week
                            @break
                            @case('M')
                             Month
                            @break
                            @case('Y')
                            Year
                            @break
                        @endswitch
                    </p>
                    <p class="description">{!! nl2br(auth()->user()->subscription->service->description) !!}</p>
                    @if(\Carbon\Carbon::parse(auth()->user()->subscription->trial_end)->gt(\Carbon\Carbon::now()))
                        <a class="btn btn-success cancel-subscription">Trial ends {{ \Carbon\Carbon::parse(auth()->user()->subscription->trial_end)->format('F j, Y') }}</a>
                    @elseif(\Carbon\Carbon::parse(auth()->user()->subscription->next_billing_date)->gt(\Carbon\Carbon::now()))
                        <a class="btn btn-success cancel-subscription">Next billing {{ \Carbon\Carbon::parse(auth()->user()->subscription->next_billing_date)->format('F j, Y') }}</a>
                    @else
                        <a class="btn btn-success cancel-subscription">{{ __('web.CANCEL') }}</a>
                    @endif
                </div>
            </div>
        @else
            <div class="linear-header">
                <h1 class="desktop">{{ __('web.SETTINGS_TIP_SUBSCRIPTION') }}</h1>
                <h1 class="mobile">{{ __('web.SETTINGS_TIP_PLAN_SUBSCRIPTION') }}</h1>
            </div>
            <div class="container subscription w-auto">
                <div class="row">
                    @foreach($plans as $index => $plan)
                        <div class="col-lg-4">
                            <div class="card shadow mb-5 mb-lg-0">
                                <div class="card-body p-5">
                                    <h5 class="card-title text-muted text-uppercase text-left">{{ $plan->title }}</h5>
                                    <h6 class="card-price text-left">
                                        <span class="current">{{ __('symbol.' . config('settings.currency', 'USD')) }}</span>
                                        <span class="price">{{ floor($plan->price) }}</span>
                                        @if(explode('.', $plan->price)[1] !== '00')
                                            <span class="sub-price text-secondary">.{{ explode('.', $plan->price)[1] }}</span>
                                        @endif
                                        @if($plan->plan_period != 1){{ $plan->plan_period }}@endif
                                        <span class="period text-secondary">/
                                            @switch($plan->plan_period_format)
                                                @case('D')
                                                Day
                                                @break
                                                @case('W')
                                                Week
                                                @break
                                                @case('M')
                                                Month
                                                @break
                                                @case('Y')
                                                Year
                                                @break
                                            @endswitch
                                        </span>
                                    </h6>
                                    <hr>
                                    <ul class="fa-ul">
                                        @foreach(explode("\n", $plan->description) as $string)
                                            <li>{{ $string }}</li>
                                        @endforeach
                                    </ul>
                                    <a class="btn btn-plan text-uppercase mt-3 make-payment"
                                       data-plan="{{ $plan->title }}"
                                       data-price="{{ __('symbol.' . config('settings.currency', 'USD')) }}{{ number_format($plan->price, 2) }}"
                                       data-description="{{ $plan->title }} {{ __('symbol.' . config('settings.currency', 'USD')) }}{{ number_format($plan->price, 2) }} / {{ $plan->plan_period }} @if($plan->plan_period_format == 'D') day @elseif($plan->plan_period_format == 'W') week @elseif($plan->plan_period_format == 'M') month @elseif($plan->plan_period_format == 'Y') year @endif"
                                       data-plan-id="{{ $plan->id }}"
                                       data-trial="{{ $plan->trial }}"
                                       data-trial-preriod="{{ $plan->trial_period }}"
                                       data-trial-preriod-format="{{ $plan->trial_period_format }}"
                                       @if($plan->trial_period_format == 'D')
                                       data-trial-end-at="{{ \Carbon\Carbon::now()->addDays($plan->trial_period)->format('m/d/Y') }}"
                                       @elseif($plan->trial_period_format == 'W')
                                       data-trial-end-at="{{ \Carbon\Carbon::now()->addWeeks($plan->trial_period)->format('m/d/Y') }}"
                                       @elseif($plan->trial_period_format == 'M')
                                       data-trial-end-at="{{ \Carbon\Carbon::now()->addMonths($plan->trial_period)->format('m/d/Y') }}"
                                       @elseif($plan->trial_period_format == 'Y')
                                       data-trial-end-at="{{ \Carbon\Carbon::now()->addYears($plan->trial_period)->format('m/d/Y') }}"
                                            @endif
                                    >@if($plan->trial) Try it @else Subscribe @endif</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection