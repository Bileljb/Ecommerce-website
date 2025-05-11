@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Sidebar: Filters + Top Sold -->
        <div class="col-md-3">
            <div class="position-sticky" style="top: 20px;">
                <!-- Filter Form -->
                <form action="{{ route('home') }}" method="GET" class="mb-4 p-3 border rounded bg-light">
                    <h5 class="mb-3">Filtres</h5>

                    <!-- Category Filter -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Catégorie</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">Toutes les catégories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="min_price" class="form-label">Prix min</label>
                        <input type="number" step="0.01" name="min_price" id="min_price" class="form-control" value="{{ request('min_price') }}">
                    </div>
                    <div class="mb-3">
                        <label for="max_price" class="form-label">Prix max</label>
                        <input type="number" step="0.01" name="max_price" id="max_price" class="form-control" value="{{ request('max_price') }}">
                    </div>

                    <!-- Rating -->
                    <div class="mb-3">
                        <label for="min_rating" class="form-label">Note minimale</label>
                        <select name="min_rating" id="min_rating" class="form-select">
                            <option value="">Toutes</option>
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('min_rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} ★ et plus
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Sorting -->
                    <div class="mb-3">
                        <label for="sort" class="form-label">Trier par</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="">Aucun tri</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom Z-A</option>
                            <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularité</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Filtrer</button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    </div>
                </form>

                <!-- Static Card: Top Sold Product Placeholder -->
                <div class="card shadow-sm mb-4">
                    <img src="https://tuniscount.com/15149-large_default/table-a-manger-toscana-.jpg" class="card-img-top" alt="Top Sold Product">
                    <div class="card-body">
                        <h5 class="card-title">🔥 Meilleure vente</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Nom du produit</h6>
                        <p class="card-text">Description du produit ici. Modifiez ce texte comme vous le souhaitez.</p>
                        <p class="fw-bold text-success">€0.00</p>
                        <a href="#" class="btn btn-sm btn-outline-primary">Voir le produit</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Product Grid -->
        <div class="col-md-9">
            <div class="row">
                @forelse ($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($product->description, 70) }}</p>
                                <p class="fw-bold">€{{ number_format($product->price, 2) }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">Aucun produit trouvé.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
