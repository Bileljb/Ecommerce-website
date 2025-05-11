<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderConfirmation;
use App\Notifications\NewOrderNotification;
use App\Models\User;

class OrderController extends Controller
{
    public function store()
    {
        $cart = session()->get('cart', []);

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Le panier est vide');
        }

        // Calculate subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Apply coupon discount if exists
        $discount = 0;
        if (session('applied_coupon')) {
            $discount = session('applied_coupon')->calculateDiscount($subtotal);
        }

        // Calculate final total
        $total = $subtotal - $discount;

        // Create the order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $total,
            'discount_amount' => $discount,
            'status' => 'completed'
        ]);

        // Insert order items
        foreach ($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);
        }

        // Send notification to customer
        $order->user->notify(new OrderConfirmation($order));

        // Send notification to all admin users
        $adminUsers = User::where('is_admin', true)->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new NewOrderNotification($order));
        }

        // Clear cart and applied coupon
        session()->forget(['cart', 'applied_coupon']);

        return redirect()->route('cart.index')->with('success', 'Commande passée !');
    }
}
