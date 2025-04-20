
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Détails du produit</h1>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $product->name }}</h4>
                <p class="card-text"><strong>Description:</strong> {{ $product->description }}</p>
                <p class="card-text"><strong>Prix:</strong> {{ $product->price }} €</p>
            </div>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Retour à la liste</a>
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning mt-3">Modifier</a>

        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger mt-3" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
        </form>
    </div>
@endsection
