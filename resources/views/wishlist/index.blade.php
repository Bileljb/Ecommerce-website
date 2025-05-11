@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Wishlist</h1>

    @if ($wishlist->isEmpty())
    <p>Your wishlist is empty.</p>
    @else
    <div class="list-group">
        @foreach ($wishlist as $product)
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <h5>{{ $product->name }}</h5>
                <p>{{ $product->description }}</p>
                <p>{{ $product->price }} €</p>
            </div>
            <div>
                <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary  mb-2">Ajouter au panier</a>
                <form action="{{ route('wishlist.remove', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection