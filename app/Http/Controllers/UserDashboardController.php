<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get wishlist items
        $wishlistItems = $user->wishlist
            ->take(4);
            
        // Get recent reviews
        $recentReviews = Review::where('user_id', $user->id)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        // Get recommended products based on purchase history
        $recommendedProducts = Product::whereHas('orders', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereNotIn('id', $user->wishlist->pluck('id'))
            ->take(4)
            ->get();
            
        return view('user.dashboard', compact(
            'recentOrders',
            'wishlistItems',
            'recentReviews',
            'recommendedProducts'
        ));
    }
} 