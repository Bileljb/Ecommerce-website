<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function store()
    {
        $cart = session()->get('cart', []);

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Le panier est vide');
        }

        // Calcul du total correct
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Création de la commande
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $total,
        ]);

        // Insertion des articles de commande
        foreach ($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);
        }

        // Vider le panier
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Commande passée !');
    }
}
