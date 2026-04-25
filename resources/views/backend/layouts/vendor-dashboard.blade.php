@extends('backend.layouts.master-layouts')
@section('title')
    {{ __('Dashboard') }}
@endsection

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .quikctech-dashboard {
            padding: 20px;
        }

        .quikctech-header h1 {
            font-size: 30px;
            font-weight: 600;
        }

        .quikctech-stats {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .quikctech-card {
            background-color: white;
            border-radius: 10px;
            padding: 9px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            padding-top: 22px;
        }

        .quikctech-cc {
            display: flex;
            gap: 20px;
            padding-bottom: 6px;
        }

        .quicktech-t-img i {
            font-size: 40px;
            padding-top: 9px;
            color: #495057b2;
        }

        .quikctech-card h5 {
            font-size: 16px;
            color: #495057;
        }

        .quikctech-card p {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
            color: black;
        }

        .quikctech-chart-container {
            margin-top: 30px;
        }

        .quikctech-chart {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-height: 410px;
            display: flex;
            justify-content: center;
        }

        .quikctech-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .quikctech-actions button {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .quikctech-btn-add {
            background-color: #007bff;
            color: white;
        }

        .quikctech-btn-view {
            background-color: #28a745;
            color: white;
        }

        .quikctech-btn-alert {
            background-color: #ffc107;
            color: white;
        }

        .quikctech-progress-bar {
            height: 12px;
            margin-top: 10px;
            border-radius: 5px;
        }

        .menu-item {
            margin-bottom: 15px;
        }

        .item-button {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: 500;
            color: #333;
            width: 100%;
            justify-content: space-between;
            transition: background-color 0.3s ease;
        }

        .item-button:hover {
            background-color: #f4f4f4;
        }

        .icon {
            margin-right: 10px;
        }

        .arrow {
            font-size: 18px;
            color: #4a90e2;
        }

        .gapp {
            row-gap: 20px;
        }
    </style>

    <section>
        <div class="container-fluid quikctech-dashboard">
            <div class="row quikctech-header">
                <div class="col-12">
                    <h1>Vendor Dashboard <br>
                        <span style="font-size:17px; font-weight:400;">DRC Room</span>
                    </h1>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div style="border-bottom:1px solid #0003; padding-bottom:30px;" class="row gapp my-4">
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('report.order.all') }}">
                        <div class="quikctech-card quikctech-cc">
                            <div class="quicktech-t-img">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>
                            <div class="quicktech-t-text">
                                <h5>Total Orders</h5>
                                <p>{{ $totalOrder ?? 0 }}</p>
                            </div>
                        </div>
                    </a>

                </div>
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('report.order.today') }}">
                        <div class="quikctech-card quikctech-cc">
                            <div class="quicktech-t-img">
                                <i class="fa-solid fa-money-bill"></i>
                            </div>
                            <div class="quicktech-t-text">
                                <h5>Today's Sales</h5>
                                <p>Tk {{ $todayslSale ?? 0.0 }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-sm-6">
                    <a href="{{ route('report.order.all') }}">
                        <div class="quikctech-card quikctech-cc">
                            <div class="quicktech-t-img">
                                <i class="fa-solid fa-money-bill"></i>
                            </div>
                            <div class="quicktech-t-text">
                                <h5>Total Sales</h5>
                                <p>Tk {{ $totalSale ?? 0.0 }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>


            <div style="border-bottom:1px solid #0003; padding-bottom:30px;" class="row quikctech-chart-container">
                <!-- Dashboard Actions -->
                <div class="col-lg-6">
                    <div class="menu-item">
                        <a href="{{ route('product.create') }}" class="item-button">
                            <span class="icon">+ Add New Product</span>
                            <span class="arrow"><i class="fa-solid fa-chevron-right"></i></span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('report.order.all') }}" class="item-button">
                            <span class="icon">ЁЯУЭ View Orders</span>
                            <span class="arrow"><i class="fa-solid fa-chevron-right"></i></span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('product.stock_warning') }}" class="item-button">
                            <span class="icon">тЪая╕П Low Stock Alert </span>
                            <span class="arrow"><i class="fa-solid fa-chevron-right"></i></span>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('expired.list') }}" class="item-button">
                            <span class="icon">тШая╕П Expire Products</span>
                            <span class="arrow"><i class="fa-solid fa-chevron-right"></i></span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row gapp">
                        <div class="col-lg-6 col-sm-6">
                            <a href="{{ route('report.order.today') }}">
                                <div class="quikctech-card quikctech-cc">
                                    <div class="quicktech-t-img">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </div>
                                    <div class="quicktech-t-text">
                                        <h5>New Orders</h5>
                                        <p>{{ $newOrder ?? 0 }}</p>
                                    </div>
                                </div>
                            </a>

                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <a href="{{ route('report.order.bytype', 'pending') }}">
                                <div class="quikctech-card quikctech-cc">
                                    <div class="quicktech-t-img">
                                        <i class="fa-solid fa-sheet-plastic"></i>
                                    </div>
                                    <div class="quicktech-t-text">
                                        <h5>Pending Order</h5>
                                        <p>{{ $pendingOrder ?? 0 }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <a href="{{ route('report.order.bytype', 'in-process') }}">
                                <div class="quikctech-card quikctech-cc">
                                    <div class="quicktech-t-img">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </div>
                                    <div class="quicktech-t-text">
                                        <h5>Processing Orders</h5>
                                        <p>{{ $processingOrder ?? 0 }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <a href="{{ route('report.order.bytype', 'delivered') }}">
                                <div class="quikctech-card quikctech-cc">
                                    <div class="quicktech-t-img">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </div>
                                    <div class="quicktech-t-text">
                                        <h5>Delivered Orders</h5>
                                        <p>{{ $deliveredOrder ?? 0 }}</p>
                                    </div>
                                </div>
                            </a>

                        </div>

                    </div>
                </div>
            </div>

            <!-- Monthly Sales Bar Chart -->
            <div class="row gapp quikctech-chart-container">
                <div class="col-12 col-lg-8">
                    <div class="quikctech-chart">
                        <canvas id="monthlySalesChart" width="400" height="200"></canvas>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="quikctech-chart">
                        <canvas id="orderStatusChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthlySalesData = @json($monthlySalesData);
            const orderStatusData = @json($orderStatusData);

            // Monthly Sales Bar Chart
            const ctxMonthly = document.getElementById('monthlySalesChart').getContext('2d');
            new Chart(ctxMonthly, {
                type: 'bar',
                data: {
                    labels: [
                        'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ],
                    datasets: [{
                        label: 'Monthly Sales',
                        data: monthlySalesData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        hoverBackgroundColor: 'rgba(75, 192, 192, 0.9)',
                        hoverBorderColor: 'rgba(75, 192, 192, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            enabled: true
                        },
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });

            // Order Status Pie Chart
            const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'pie',
                data: {
                    labels: ['Delivered', 'Pending', 'Cancelled'],
                    datasets: [{
                        data: [
                            orderStatusData.delivered || 0,
                            orderStatusData.pending || 0,
                            orderStatusData.cancelled || 0
                        ],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const label = tooltipItem.label || '';
                                    const value = tooltipItem.raw || 0;
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection

@section('script')
    <!-- Plugin Js-->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard.init.js') }}"></script>
@endsection
