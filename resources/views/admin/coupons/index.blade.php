@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Manage Coupons</h4>
                </div>
                <div class="card-body">
                    <!-- Create Coupon Form -->
                    <form action="{{ route('coupons.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount_percentage">Discount Percentage</label>
                                    <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" min="0" max="100" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="expires_at">Expiry Date (Optional)</label>
                                    <input type="date" class="form-control" id="expires_at" name="expires_at">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block">Generate Coupon</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Coupons List -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Status</th>
                                    <th>Expires At</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ $coupon->discount_percentage }}%</td>
                                    <td>
                                        <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : 'Never' }}</td>
                                    <td>{{ $coupon->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <form action="{{ route('coupons.toggle', $coupon) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $coupon->is_active ? 'btn-warning' : 'btn-success' }}">
                                                {{ $coupon->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this coupon?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 