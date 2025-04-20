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

        <button type="submit" class="btn btn-success">Ajouter</button>
    </form>
@endsection
