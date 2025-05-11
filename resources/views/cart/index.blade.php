@extends('layouts.app')

@section('content')
    <h1>Panier</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(!empty($cart))
        <table class="table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['price'] }} €</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>
                            <a href="{{ route('cart.remove', $id) }}" class="btn btn-danger">Retirer</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $subtotal = 0;
            foreach($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $discount = 0;
            if(session('applied_coupon')) {
                $discount = session('applied_coupon')->calculateDiscount($subtotal);
            }
            $total = $subtotal - $discount;
        @endphp

        <div class="row mt-4">
            <div class="col-md-6">
                <!-- Coupon Form -->
                @if(!session('applied_coupon'))
                    <form action="{{ route('coupons.apply') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="code" class="form-control" placeholder="Enter coupon code" required>
                            <button type="submit" class="btn btn-primary">Apply Coupon</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-success">
                        Coupon applied: {{ session('applied_coupon')->code }} 
                        ({{ session('applied_coupon')->discount_percentage }}% off)
                        <form action="{{ route('coupons.remove') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <!-- Order Summary -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>{{ number_format($subtotal, 2) }} €</span>
                        </div>
                        @if($discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Discount:</span>
                                <span>-{{ number_format($discount, 2) }} €</span>
                            </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong>{{ number_format($total, 2) }} €</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Formulaire de commande --}}
        <form action="{{ route('order.store') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-success">Passer la commande</button>
        </form>
    @else
        <p>Votre panier est vide.</p>
    @endif
@endsection
