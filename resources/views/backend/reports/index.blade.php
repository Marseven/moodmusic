@extends('backend.index')
@section('content')
    <script>
        var RevenueSourcesChartDataLabel = [
            @foreach($plans as $plan)
                "{{ $plan->title }}",
            @endforeach
        ]
        var RevenueSourcesChartData = [
            @foreach($plans as $plan)
                "{{ DB::table('subscriptions')->where('service_id', $plan->id)->where('payment_status', 1)->count() }}",
            @endforeach
        ]
        var PaymentMethodChartData = [{{ DB::table('subscriptions')->where('gate', 'paypal')->count() }}, {{ DB::table('subscriptions')->where('gate', 'stripe')->count() }}]
        var EarningsReportsLabel = @json($day->period);
        var EarningsReportsData = @json($day->earnings);
        var currencyLabel = '{{ __('symbol.' . config('settings.currency', 'USD')) }}';
        var MonthlyEarningsChartLabel = @json($month->period);
        var MonthlyEarningsChartEarningData = @json($month->earnings);
        var MonthlyEarningsChartOrdersData = @json($month->orders);
    </script>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active">Reports</li>
    </ol>
    <div class="col-lg-12 mb-5">
        <form role="form" method="post" action="">
            @csrf
            <div class="form-group row mb-2 p-0">
                <label class="col-sm-3 col-3 col-form-label text-right">From</label>
                <div class="col-xl-3 col-sm-6 col-9">
                    <input class="form-control datetimepicker-no-mask" type="text" name="from" value="{{ isset($fromDate) ? $fromDate : \Carbon\Carbon::now()->subMonth(1)->format('Y/m/d H:i') }}" required>
                </div>
            </div>
            <div class="form-group row mb-2 p-0">
                <label class="col-sm-3 col-3 col-form-label text-right">To</label>
                <div class="col-xl-3 col-sm-6 col-9">
                    <input class="form-control datetimepicker-no-mask" type="text" name="to" value="{{ isset($toDate) ? $toDate : \Carbon\Carbon::now()->format('Y/m/d H:i') }}" required>
                </div>
            </div>
            <div class="form-group row mb-2 mt-4 p-0">
                <label class="col-sm-3 col-3 col-form-label text-right"></label>
                <div class="col-xl-3 col-sm-6 col-9">
                    <button type="submit" class="btn btn-primary">Get Report</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row universal-report">
        <div class="col-xl-6 col-lg-6 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fa fa-money-bill"></i>
                    </div>
                    <div id="increase-number-1" class="mr-5 h2">{{ $data->total->earnings }}{{ __('symbol.' . config('settings.currency', 'USD')) }} Total Earnings</div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-sm-6 mb-3">
            <div class="card text-white bg-secondary o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fa fa-money-bill"></i>
                    </div>
                    <div id="increase-number-2" class="mr-5 h2">{{ $data->orders }} Subscription</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
            <div class="card text-white bg-success o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fa fa-check"></i>
                    </div>
                    <div id="increase-number-3" class="mr-5 h2">{{ $data->success }} Active</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
            <div class="card text-white bg-danger o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fa fa-fw fa-info"></i>
                    </div>
                    <div id="increase-number-4" class="mr-5 h2">{{ $data->failed }} In-Active</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
            <div class="card text-white bg-info o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fa fa-fw fa-money-bill-alt"></i>
                    </div>
                    <div id="increase-number-5" class="mr-5 h2">{{ $data->paypal }} via Paypal</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
            <div class="card text-white bg-dark o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fa fa-fw fa-credit-card"></i>
                    </div>
                    <div id="increase-number-6" class="mr-5 h2">{{ $data->stripe }} via Stripe</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-money-check-alt"></i> Earnings Reports</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="EarningsReportsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-money-check-alt"></i> Monthly Earnings Reports</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="MonthlyEarningsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="RevenueSourcesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Method</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="PaymentMethodChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection