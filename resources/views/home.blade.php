@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Produits disponibles</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="card-text"><strong>{{ $product->price }} €</strong></p>
                            <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary">Ajouter au panier</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
