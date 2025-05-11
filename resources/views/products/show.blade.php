@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <div class="card">
                <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">{{ $product->name }}</h2>
                    <p class="card-text text-muted mb-4">{{ $product->description }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="card-text"><strong class="text-primary">{{ $product->price }} €</strong></p>
                        <p class="card-text"><small class="text-muted">Stock: {{ $product->stock ?? 'Non disponible' }}</small></p>
                    </div>

                    <!-- Normal User Actions -->
                    @if(Auth::check() && !Auth::user()->is_admin)
                    <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary w-100 mb-2">Ajouter au panier</a>

                    <form action="{{ route('wishlist.add', $product->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100">Ajouter à la wishlist</button>
                    </form>

                    <!-- Star Rating & Comment -->
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label for="rating">Note:</label>
                            <select name="rating" id="rating" class="form-select">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} ⭐</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comment">Commentaire:</label>
                            <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Envoyer le commentaire</button>
                    </form>
                    @endif
                </div>
                <div class="mt-4">
                    <h4 class="mb-3">Avis des utilisateurs</h4>

                    @php
                    $averageRating = $product->reviews->avg('rating');
                    @endphp

                    @if($product->reviews->count())
                    <p class="mb-4">
                        <strong>Moyenne des notes :</strong>
                        {{ number_format($averageRating, 1) }} ⭐ ({{ $product->reviews->count() }} avis)
                    </p>

                    @foreach($product->reviews as $review)
                    <div class="mb-3 p-3 border rounded bg-light">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $review->user->name ?? 'Utilisateur' }}</strong>
                            <span>{{ $review->rating }} ⭐</span>
                        </div>
                        <p class="mb-1">{{ $review->comment }}</p>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    @endforeach
                    @else
                    <p class="text-muted">Aucun avis pour ce produit pour le moment.</p>
                    @endif
                </div>


                <!-- Admin Actions -->
                @if(Auth::check() && Auth::user()->is_admin)
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Retour à la liste</a>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Modifier</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection