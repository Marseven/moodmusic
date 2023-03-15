@extends('backend.index')
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('backend.dashboard') }}">Control Panel</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{ route('backend.banners') }}">Banners</a></li>
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

    <!--
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fa fa-money-bill"></i>
                    </div>
                    <div class="mr-5 h2">{{ $data->total_clicks }} Total Clicks</div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-sm-6 mb-3">
            <div class="card text-white bg-warning o-hidden h-100">
                <div class="card-body">
                    <div class="card-body-icon">
                        <i class="fa fa-money-bill"></i>
                    </div>
                    <div class="mr-5 h2">{{ $data->total_clicks }} Total Clicks</div>
                </div>
            </div>
        </div>
    </div>
    -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-money-check-alt"></i> Click Reports</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="clicks-report"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-money-check-alt"></i> By Age</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="age-area"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-money-check-alt"></i> By Gender</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="gender-area"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-money-check-alt"></i> By Country Reports</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="country-area"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function getRandomColor() {
            var letters = '0123456789ABCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        var randomScalingFactor = function() {
            return Math.round(Math.random() * 100);
        };

        window.onload = function() {

            var ctxClick = document.getElementById("clicks-report");
            var myLineChart = new Chart(ctxClick, {
                type: 'line',
                data: {
                    labels: @json($day->period),
                    datasets: [{
                        label: "Clicks",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: @json($day->earnings),
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 25,
                            top: 25,
                            bottom: 0
                        }
                    },
                    scales: {
                        xAxes: [{
                            time: {
                                unit: 'date'
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 7
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    return value;
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                    },
                    legend: {
                        display: false
                    },
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        titleMarginBottom: 10,
                        titleFontColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                        callbacks: {
                            label: function (tooltipItem, chart) {
                                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                return datasetLabel + tooltipItem.yLabel;
                            }
                        }
                    }
                }
            });


            var ctxgender = document.getElementById('gender-area').getContext('2d');
            window.myDoughnut = new Chart(ctxgender, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [
                            @foreach($data->gender as $sex)
                            {{ $sex->count }},
                            @endforeach
                        ],
                        backgroundColor: [
                            @foreach($data->gender as $sex)
                                getRandomColor(),
                            @endforeach
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: [
                        @foreach($data->gender as $sex)
                            @if($sex->gender == 'F')
                                "Female",
                            @elseif($sex->gender == 'M')
                                "Male",
                            @else
                                "Other",
                            @endif
                        @endforeach
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            var ctx = document.getElementById('country-area').getContext('2d');
            window.myDoughnut = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [
                            @foreach($data->countries as $country)
                            {{ $country->count }},
                            @endforeach
                        ],
                        backgroundColor: [
                            @foreach($data->countries as $country)
                            getRandomColor(),
                            @endforeach
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: [
                        @foreach($data->countries as $country)
                            "{{ $country->country_code }}",
                        @endforeach
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            var ctxage = document.getElementById('age-area').getContext('2d');
            window.myHorizontalBar = new Chart(ctxage, {
                type: 'horizontalBar',
                data: {
                    labels: [
                        @foreach($data->age as $value => $key)
                            @if(! $loop->first)
                                "{{ $value }}",
                            @endif
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Click by age',
                        backgroundColor: '#fd7e14',
                        borderWidth: 1,
                        data: [
                            @foreach($data->age as $value => $key)
                                @if(! $loop->first)
                                    "{{ $key }}",
                                @endif
                            @endforeach
                        ]
                    }]

                },
                options: {
                    // Elements options apply to all of the options unless overridden in a dataset
                    // In this case, we are setting the border of each horizontal bar to be 2px wide
                    elements: {
                        rectangle: {
                            borderWidth: 2,
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'top',
                    },
                }
            });

        };

    </script>
@endsection