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

        {{-- Formulaire de commande --}}
        <form action="{{ route('order.store') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Passer la commande</button>
        </form>
    @else
        <p>Votre panier est vide.</p>
    @endif
@endsection
