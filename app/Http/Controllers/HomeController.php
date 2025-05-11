<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category; // Add this import
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('reviews', 'category'); // preload relationships

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Price filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Rating filter — handle outside the query using IDs
        if ($request->filled('min_rating')) {
            $productIds = DB::table('reviews')
                ->select('product_id')
                ->groupBy('product_id')
                ->havingRaw('AVG(rating) >= ?', [$request->min_rating])
                ->pluck('product_id');

            $query->whereIn('id', $productIds);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'popularity':
                    $query->withCount('reviews')->orderBy('reviews_count', 'desc');
                    break;
            }
        }

        $products = $query->get();
        $categories = Category::all();
        return view('home', compact('products', 'categories')); // Pass both variables to the view
    }
}