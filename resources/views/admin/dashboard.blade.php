@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Admin Actions Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('products.index') }}" class="btn btn-info mb-3">
                        <i class="fas fa-box me-2"></i>Manage Products
                    </a>
                    <a href="{{ route('coupons.index') }}" class="btn btn-success mb-3">
                        <i class="fas fa-ticket-alt me-2"></i>Manage Coupons
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-primary mb-3">
                        <i class="fas fa-tags me-2"></i>Manage Categories
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Orders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Turnover</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalTurnover, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Sales Evolution Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sales Evolution</h6>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 Best-Selling Products</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach($topProducts as $product)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $product->product->name }}</h6>
                                <small>{{ $product->total_quantity }} sold</small>
                            </div>
                            <small class="text-muted">${{ number_format($product->product->price, 2) }} each</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recent Notifications</h5>
                </div>
                <div class="card-body">
                    @forelse(auth()->user()->notifications as $notification)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0">
                                @if($notification->type === 'App\Notifications\NewOrderNotification')
                                    <i class="fas fa-shopping-cart text-success fa-2x"></i>
                                @else
                                    <i class="fas fa-bell text-primary fa-2x"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-0">{{ $notification->data['message'] }}</p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            @if(!$notification->read_at)
                                <div class="flex-shrink-0">
                                    <span class="badge bg-success">New</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted text-center my-4">No notifications yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var salesCtx = document.getElementById('salesChart').getContext('2d');
    var salesData = JSON.parse('{!! json_encode($dailySales) !!}');
    var yAxisMax = parseInt('{!! $yAxisMax !!}');
    
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: salesData.map(function(item) { 
                return new Date(item.date).toLocaleDateString();
            }),
            datasets: [{
                label: 'Daily Sales',
                data: salesData.map(function(item) { 
                    return parseFloat(item.total);
                }),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: yAxisMax,
                    ticks: {
                        stepSize: Math.ceil(yAxisMax / 10), // Dynamic step size
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Sales: $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-info {
    border-left: 4px solid #36b9cc !important;
}
.card {
    border-radius: 0.35rem;
}
</style>
@endpush
@endsection 