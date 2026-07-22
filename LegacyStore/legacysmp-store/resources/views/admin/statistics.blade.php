@extends('layouts.admin')

@section('title', 'Statistik Penjualan')

@section('header', 'Statistik Penjualan')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    <p>Total Revenue</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['total_orders'] }}</h3>
                    <p>Total Orders</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['completed_orders'] }}</h3>
                    <p>Completed Orders</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>Rp {{ number_format($stats['average_order_value'], 0, ',', '.') }}</h3>
                    <p>Avg Order Value</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Sales Chart -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daily Sales (Last 30 Days)</h3>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Revenue by Payment Method</h3>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top 10 Products</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $product => $count)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product }}</td>
                                <td><span class="badge bg-success">{{ $count }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No sales data yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Revenue Breakdown</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['revenue_by_method'] as $method)
                            <tr>
                                <td><span class="badge bg-info">{{ ucfirst($method->payment_method) }}</span></td>
                                <td>Rp {{ number_format($method->total, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">No data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($dailySales->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('d M'); })) !!},
        datasets: [{
            label: 'Revenue (Rp)',
            data: {!! json_encode($dailySales->pluck('total')) !!},
            borderColor: 'rgba(60, 141, 188, 1)',
            backgroundColor: 'rgba(60, 141, 188, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }, {
            label: 'Orders',
            data: {!! json_encode($dailySales->pluck('count')) !!},
            borderColor: 'rgba(0, 192, 239, 1)',
            backgroundColor: 'rgba(0, 192, 239, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            },
            y1: {
                beginAtZero: true,
                position: 'right',
                grid: {
                    drawOnChartArea: false
                },
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Payment Method Chart
const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
new Chart(paymentCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($stats['revenue_by_method']->pluck('payment_method')->map(function($m) { return ucfirst($m); })) !!},
        datasets: [{
            data: {!! json_encode($stats['revenue_by_method']->pluck('total')) !!},
            backgroundColor: ['#00c0ef', '#00a65a', '#f39c12', '#d81b60', '#39cccc'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endsection

