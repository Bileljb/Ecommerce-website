@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title mb-0">Welcome back, {{ Auth::user()->name }}!</h2>
                    <p class="text-muted">Here's an overview of your account activity</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Orders</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>${{ number_format($order->total_price, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center my-4">No orders yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Wishlist Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Wishlist</h5>
                        <a href="{{ route('wishlist.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($wishlistItems->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($wishlistItems as $item)
                                <div class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ $item->name }}</h6>
                                            <small class="text-muted">${{ number_format($item->price, 2) }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center my-4">Your wishlist is empty</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Reviews -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recent Reviews</h5>
                </div>
                <div class="card-body">
                    @if($recentReviews->count() > 0)
                        @foreach($recentReviews as $review)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $review->product->name }}</h6>
                                        <div class="text-warning mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-0">{{ Str::limit($review->comment, 100) }}</p>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center my-4">No reviews yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recommended Products -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Recommended for You</h5>
                </div>
                <div class="card-body">
                    @if($recommendedProducts->count() > 0)
                        <div class="row g-3">
                            @foreach($recommendedProducts as $product)
                                <div class="col-6">
                                    <div class="card h-100">
                                        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ Str::limit($product->name, 20) }}</h6>
                                            <p class="card-text text-primary mb-0">${{ number_format($product->price, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center my-4">No recommendations available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Notifications -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Notifications</h5>
                </div>
                <div class="card-body">
                    @forelse(auth()->user()->notifications as $notification)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0">
                                @if($notification->type === 'App\Notifications\OrderConfirmation')
                                    <i class="fas fa-shopping-bag text-primary fa-2x"></i>
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
                                    <span class="badge bg-primary">New</span>
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

@push('styles')
<style>
.card {
    border: none;
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
.badge {
    font-weight: 500;
}
</style>
@endpush
@endsection 