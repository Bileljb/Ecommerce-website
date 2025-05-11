<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Add product to wishlist
    public function add(Product $product)
    {
        $user = Auth::user();

        // Prevent duplicates
        if (!$user->wishlist->contains($product->id)) {
            $user->wishlist()->attach($product->id);
            return back()->with('success', 'Product added to wishlist!');
        }

        return back()->with('info', 'Product is already in your wishlist.');
    }

    // Remove product from wishlist
    public function remove(Product $product)
    {
        $user = Auth::user();

        // Detach the product from the user's wishlist
        $user->wishlist()->detach($product->id);

        return back()->with('success', 'Product removed from wishlist!');
    }

    // View wishlist
    public function index()
    {
        $wishlist = Auth::user()->wishlist()->paginate(10);

        return view('wishlist.index', compact('wishlist'));
    }
}
