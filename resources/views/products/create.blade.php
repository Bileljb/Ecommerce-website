@extends('layouts.app')

@section('content')
<h1>Ajouter un produit</h1>

<form action="{{ route('products.store') }}" method="POST">
    @csrf
    <label>Nom</label>
    <input type="text" name="name" class="form-control" required>

    <label>Description</label>
    <textarea name="description" class="form-control" required></textarea>

    <label>Prix</label>
    <input type="number" name="price" class="form-control" required>
    <div class="mb-3">
        <label for="product-image-url" class="form-label">Image URL</label>
        <input type="url" class="form-control" id="product-image-url" name="image_url" placeholder="Paste image URL here" required>
    </div>
    <div class="form-group">
        <label for="category_id">Category</label>
        <select name="category_id" id="category_id" class="form-control">
            <option value="">-- Select Category --</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-success">Ajouter</button>
</form>
@endsection