@extends('backend.layouts.app', ['isBanner' => false])

@section('title') {{ 'Dashboard' }} @endsection

@section('content')
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="card-icon mb-4 display-6">
                                    <i class="ph ph-user"></i>
                                </div>
                                <div class="card-data">
                                    <h1>{{ $totalusers }}</h1>
                                    <p class="mb-0 fs-3">{{ __('dashboard.lbl_total_users') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="card-icon mb-4 display-6">
                                    <i class="ph ph-user-gear"></i>
                                </div>
                                <div class="card-data">
                                    <h1>{{ $activeusers }}</h1>
                                    <p class="mb-0 fs-3">{{ __('dashboard.lbl_active_users') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="card-icon mb-4 display-6">
                                    <i class="ph ph-currency-circle-dollar"></i>
                                </div>
                                <div class="card-data">
                                    <h1>{{ $activeusers }}</h1>
                                    <p class="mb-0 fs-3">{{ __('dashboard.lbl_total_subscribers') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="card-icon mb-4 display-6">
                                    <i class="ph ph-film-strip"></i>
                                </div>
                                <div class="card-data">
                                    <h1>{{ $totalmovies }}</h1>
                                    <p class="mb-0 fs-3">{{ __('dashboard.lbl_total_movies') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="card-icon mb-4 display-6">
                                    <i class="ph ph-television-simple"></i>
                                </div>
                                <div class="card-data">
                                    <h1>{{ $totaltvshow }}</h1>
                                    <p class="mb-0 fs-3">{{ __('dashboard.lbl_total_shows') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-body">
                                <div class="card-icon mb-4 display-6">
                                    <i class="ph ph-video"></i>
                                </div>
                                <div class="card-data">
                                    <h1>{{ $totalvideo }}</h1>
                                    <p class="mb-0 fs-3">{{ __('dashboard.lbl_total_videos') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-stats">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dashboard.lbl_top_genres') }}</h3>
                    </div>
                    <div class="card-body">
                        <div id="chart-top-genres"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-stats">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <h3 class="card-title">{{ __('dashboard.total_revenue_subscribers') }}</h3>
                        <div class="dropdown">
                            <button class="btn btn-dark dropdown-toggle total_revenue" type="button" id="dropdownTotalRevenue" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('dashboard.year') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-soft-primary sub-dropdown" aria-labelledby="dropdownTotalRevenue">
                                <li><a class="revenue-dropdown-item dropdown-item" data-type="Year">{{ __('dashboard.year') }}</a></li>
                                <li><a class="revenue-dropdown-item dropdown-item" data-type="Month">{{ __('dashboard.month') }}</a></li>
                                <li><a class="revenue-dropdown-item dropdown-item" data-type="Week">{{ __('dashboard.week') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="chart-top-revenue"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-stats">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <h3 class="card-title">{{ __('dashboard.new_subscribers') }}</h3>
                        <div class="dropdown">
                            <button class="btn btn-dark dropdown-toggle total_subscribers" type="button" id="dropdownNewSubscribers" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('dashboard.year') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-soft-primary sub-dropdown" aria-labelledby="dropdownNewSubscribers">
                                <li><a class="subscribers-dropdown-item dropdown-item" data-type="Year">{{ __('dashboard.year') }}</a></li>
                                <li><a class="subscribers-dropdown-item dropdown-item" data-type="Month">{{ __('dashboard.month') }}</a></li>
                                <li><a class="subscribers-dropdown-item dropdown-item" data-type="Week">{{ __('dashboard.week') }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="chart-new-subscription"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6">
                <div class="row">
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h3 class="card-title">{{ __('dashboard.lbl_top_rated') }}</h3>
                            </div>
                            <div class="card-body">
                                <div id="chart-top-rated"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <h3 class="card-title">{{ __('dashboard.lbl_most_watched') }}</h3>
                                <div class="dropdown">
                                    <button class="btn btn-dark dropdown-toggle most_watch" type="button" id="dropdownMostWatch" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ __('dashboard.year') }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-soft-primary sub-dropdown" aria-labelledby="dropdownMostWatch">
                                        <li><a class="mostwatch-dropdown-item dropdown-item" data-type="Year">{{ __('dashboard.year') }}</a></li>
                                        <li><a class="mostwatch-dropdown-item dropdown-item" data-type="Month">{{ __('dashboard.month') }}</a></li>
                                        <li><a class="mostwatch-dropdown-item dropdown-item" data-type="Week">{{ __('dashboard.week') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="chart-most-watch"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6">
                <div class="card card-stats">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <h3 class="card-title">{{ __('dashboard.user_rating_reviews') }}</h3>
                        <a href="#">{{ __('dashboard.view_all') }}</a>
                    </div>
                    <div class="card-body">
                    <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <th>{{ __('dashboard.date') }}</th>
                                    <th>{{ __('dashboard.user_name') }}</th>
                                    <th>{{ __('dashboard.category') }}</th>
                                    <th>{{ __('dashboard.rating') }}</th>
                                </thead>
                                <tbody>
                                    @if($reviewData)
                                        @foreach($reviewData as $review)
                                            <tr>
                                                <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                                <td>{{ optional($review->user)->first_name . ' ' . optional($review->user)->last_name }}</td>
                                                <td>{{ ucfirst($review->entertainment->type) }}</td>
                                                <td>{{ $review->rating }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="5">No data available</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <h3 class="card-title">{{ __('dashboard.transaction_history') }}</h3>
                        <a href="{{ route('backend.subscriptions.index') }}">{{ __('dashboard.view_all') }}</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <th>{{ __('dashboard.user_name') }}</th>
                                    <th>{{ __('dashboard.date') }}</th>
                                    <th>{{ __('dashboard.plan') }}</th>
                                    <th>{{ __('dashboard.amount') }}</th>
                                    <th>{{ __('dashboard.duration') }}</th>
                                    <th>{{ __('dashboard.payment_method') }}</th>
                                </thead>
                                <tbody>
                                    @foreach($subscriptionData as $subscription)
                                        <tr>
                                            <td>{{ optional($subscription->user)->first_name . ' ' . optional($subscription->user)->last_name }}</td>
                                            <td>{{ $subscription->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $subscription->name }}</td>
                                            <td>{{ $subscription->amount }}</td>
                                            <td>{{ $subscription->duration. ' ' . optional($subscription->plan)->duration }}</td>
                                            <td>{{ optional($subscription->subscription_transaction)->payment_type }}</td>
                                        </tr>
                                    @endforeach
                                    @if($subscriptionData->isEmpty())
                                    <tr>
                                        <td colspan="5">No data available</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
@endsection

@push('after-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">

@endpush

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var Base_url = "{{ url('/') }}";
            var url = Base_url + "/app/get_genre_chart_data";

            $.ajax({
                url: url,
                method: "GET",
                data: {},
                success: function(response) {
                    if (document.querySelectorAll('#chart-top-genres').length) {
                        const chartData = response.data.chartData;
                        const category = response.data.category;
                        const options = {
                            series: chartData,
                            chart: {
                                height: 390,
                                type: 'radialBar',
                            },
                            plotOptions: {
                                radialBar: {
                                    offsetY: 0,
                                    startAngle: 0,
                                    endAngle: 270,
                                    hollow: {
                                        margin: 5,
                                        size: '30%',
                                        background: 'transparent',
                                        image: undefined,
                                    },
                                    dataLabels: {
                                        name: {
                                            show: false,
                                        },
                                        value: {
                                            show: false,
                                        }
                                    },
                                    barLabels: {
                                        enabled: true,
                                        useSeriesColors: true,
                                        margin: 8,
                                        fontSize: '16px',
                                        formatter: function(seriesName, opts) {
                                            return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
                                        },
                                    },
                                }
                            },
                            colors: ['#E50914', '#C51F28', '#A31B22', '#93151B', '#70070C', '#5A0206'],
                            labels: category,
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    legend: {
                                        show: false
                                    }
                                }
                            }]
                           
                        };

                        var chart = new ApexCharts(document.querySelector("#chart-top-genres"), options);
                        chart.render();
                    }
                }
            });
        });


        revanue_chart('Year')

        var chart = null;
        let revenueInstance;

        function revanue_chart(type) {
            var Base_url = "{{ url('/') }}";
            var url = Base_url + "/app/get_revnue_chart_data/" + type;

            $("#revenue_loader").show();

            $.ajax({
                url: url,
                method: "GET",
                data: {},
                success: function(response) {
                    $("#revenue_loader").hide();
                    $(".total_revenue").text(type);
                    if (document.querySelectorAll('#chart-top-revenue').length) {
                        const monthlyTotals = response.data.chartData;
                        const category = response.data.category;
                        const options = {
                            series: [{
                                name: "Total Revenue",
                                data: monthlyTotals
                            }],
                            chart: {
                                height: 350,
                                type: 'line',
                                zoom: {
                                    enabled: false
                                }
                            },
                            colors:['#E50914'],
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                curve: 'smooth',
                            },
                            // title: {
                            //     text: 'Product Trends by Month',
                            //     align: 'left'
                            // },
                            grid: {
                                row: {
                                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                    opacity: 0
                                },
                            },
                            xaxis: {
                                categories: category
                            },
                        };

                        if (revenueInstance) {
                            revenueInstance.updateOptions(options);
                        } else {
                            revenueInstance = new ApexCharts(document.querySelector("#chart-top-revenue"), options);
                            revenueInstance.render();
                        }
                    }
                }
            })
        };

        $(document).on('click', '.revenue-dropdown-item', function() {
            var type = $(this).data('type');
            revanue_chart(type);
        });


        subscriber_chart('Year')        
        let subscriberInstance;

        function subscriber_chart(type) {
            var Base_url = "{{ url('/') }}";
            var url = Base_url + "/app/get_subscriber_chart_data/" + type;

            $("#subscriber_loader").show();

            $.ajax({
                url: url,
                method: "GET",
                data: {},
                success: function(response) {
                    $("#subscriber_loader").hide();
                    $(".total_subscribers").text(type);
                    if (document.querySelectorAll('#chart-new-subscription').length) {
                        const chartData = response.data.chartData;
                        const category = response.data.category;
                        const options = {
                            series: chartData,
                            chart: {
                                type: 'bar',
                                height: 350,
                                stacked: true,
                                toolbar: {
                                    show: true
                                },
                                zoom: {
                                    enabled: true
                                }
                            },
                            colors:['#E50914', '#A31B22', '#70070C', '#5A0206'],
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    legend: {
                                    position: 'bottom',
                                    offsetX: -10,
                                    offsetY: 0
                                    }
                                }
                            }],
                            grid: {
                                borderColor: '#404A51',                    
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '25%',
                                    borderRadius: 3,
                                    borderRadiusApplication: 'end', // 'around', 'end'
                                    borderRadiusWhenStacked: 'last', // 'all', 'last'
                                    dataLabels: {
                                    total: {
                                        enabled: true,
                                        style: {
                                        fontSize: '13px',
                                        fontWeight: 900
                                        }
                                    }
                                    }
                                },
                            },
                            xaxis: {
                                // type: 'datetime',
                                categories: category
                            },
                            legend: {
                                position: 'bottom',
                                horizontalAlign: 'center', 
                                
                            },
                            fill: {
                                opacity: 1
                            }
                        };

                        if (subscriberInstance) {
                            subscriberInstance.updateOptions(options);
                        } else {
                            subscriberInstance = new ApexCharts(document.querySelector("#chart-new-subscription"), options);
                            subscriberInstance.render();
                        }
                    }
                }
            })
        };

        $(document).on('click', '.subscribers-dropdown-item', function() {
            var type = $(this).data('type');
            subscriber_chart(type);
        });

        if (document.querySelectorAll("#chart-top-rated").length) {
            var options = {
            series: [44, 55, 67, 83],
            chart: {
            height: 350,
            type: 'radialBar',
            },
            plotOptions: {
            radialBar: {
                dataLabels: {
                name: {
                    fontSize: '22px',
                },
                value: {
                    fontSize: '16px',
                },
                total: {
                    show: true,
                    label: 'Total',
                    formatter: function (w) {
                    // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                    return 249
                    }
                }
                }
            }
            },
            labels: ['Apples', 'Oranges', 'Bananas', 'Berries'],
            };

            var chart = new ApexCharts(document.querySelector("#chart-top-rated"), options);
            chart.render();
        }


        mostwatch_chart('Year')        
        let mostwatchInstance;

        function mostwatch_chart(type) {
            var Base_url = "{{ url('/') }}";
            var url = Base_url + "/app/get_mostwatch_chart_data/" + type;

            $("#mostwatch_loader").show();

            $.ajax({
                url: url,
                method: "GET",
                data: {},
                success: function(response) {
                    $("#mostwatch_loader").hide();
                    $(".most_watch").text(type);
                    if (document.querySelectorAll('#chart-most-watch').length) {
                        const chartData = response.data.chartData;
                        const category = response.data.category;
                        const options = {
                            series: chartData,
                            chart: {
                                type: 'bar',
                                height: 350,
                                stacked: true,
                                toolbar: {
                                    show: true
                                },
                                zoom: {
                                    enabled: true
                                }
                            },
                            colors:['#E50914', '#A31B22', '#70070C', '#5A0206'],
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    legend: {
                                    position: 'bottom',
                                    offsetX: -10,
                                    offsetY: 0
                                    }
                                }
                            }],
                            grid: {
                                borderColor: '#404A51',                    
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '25%',
                                    borderRadius: 3,
                                    borderRadiusApplication: 'end', // 'around', 'end'
                                    borderRadiusWhenStacked: 'last', // 'all', 'last'
                                    dataLabels: {
                                    total: {
                                        enabled: true,
                                        style: {
                                        fontSize: '13px',
                                        fontWeight: 900
                                        }
                                    }
                                    }
                                },
                            },
                            xaxis: {
                                // type: 'datetime',
                                categories: category
                            },
                            legend: {
                                position: 'bottom',
                                horizontalAlign: 'center', 
                                
                            },
                            fill: {
                                opacity: 1
                            }
                        };

                        if (mostwatchInstance) {
                            mostwatchInstance.updateOptions(options);
                        } else {
                            mostwatchInstance = new ApexCharts(document.querySelector("#chart-most-watch"), options);
                            mostwatchInstance.render();
                        }
                    }
                }
            })
        };

        $(document).on('click', '.mostwatch-dropdown-item', function() {
            var type = $(this).data('type');
            mostwatch_chart(type);
        });

        

        // if (document.querySelectorAll("#chart-most-watch").length) {
        //     var options = {
        //         series: [{
        //             name: 'PRODUCT A',
        //             data: [44, 55, 41, 67, 22, 43]
        //         }, {
        //             name: 'PRODUCT B',
        //             data: [13, 23, 20, 8, 13, 27]
        //         }],
                
        //         chart: {
        //             type: 'bar',
        //             height: 350,
        //             stacked: true,
        //             toolbar: {
        //                 show: true
        //             },
        //             zoom: {
        //                 enabled: true
        //             }
        //         },
        //         colors:['#E50914', '#A31B22'],
        //         grid: {
        //             borderColor: '#404A51',                    
        //         },
        //         responsive: [{
        //             breakpoint: 480,
        //             options: {
        //                 legend: {
        //                 position: 'bottom',
        //                 offsetX: -10,
        //                 offsetY: 0
        //                 }
        //             }
        //         }],
        //         plotOptions: {
        //             bar: {
        //                 horizontal: false,
        //                 columnWidth: '25%',
        //                 borderRadius: 3,
        //                 borderRadiusApplication: 'end', // 'around', 'end'
        //                 borderRadiusWhenStacked: 'last', // 'all', 'last'
        //                 dataLabels: {
        //                 total: {
        //                     enabled: true,
        //                     style: {
        //                     fontSize: '13px',
        //                     fontWeight: 900
        //                     }
        //                 }
        //                 }
        //             },
        //         },
        //         xaxis: {
        //             type: 'datetime',
        //             categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
        //                 '01/05/2011 GMT', '01/06/2011 GMT'
        //             ],
        //         },
        //         legend: {
        //             position: 'bottom',
        //             horizontalAlign: 'center', 
        //         },
        //         fill: {
        //             opacity: 1
        //         }
        //     };

        //     var chart = new ApexCharts(document.querySelector("#chart-most-watch"), options);
        //     chart.render(); 
        // }
    </script>
    
@endpush
