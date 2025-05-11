<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource with filtering and sorting.
     */
    public function index(Request $request)
    {
        $query = Product::with('reviews', 'category'); // preload relationships

        // // Category filter
        // if ($request->filled('category')) {
        //     $query->where('category_id', $request->category);
        // }

        // // Price filters
        // if ($request->filled('min_price')) {
        //     $query->where('price', '>=', $request->min_price);
        // }

        // if ($request->filled('max_price')) {
        //     $query->where('price', '<=', $request->max_price);
        // }

        // // Rating filter — handle outside the query using IDs
        // if ($request->filled('min_rating')) {
        //     $productIds = DB::table('reviews')
        //         ->select('product_id')
        //         ->groupBy('product_id')
        //         ->havingRaw('AVG(rating) >= ?', [$request->min_rating])
        //         ->pluck('product_id');

        //     $query->whereIn('id', $productIds);
        // }

        // // Sorting
        // if ($request->filled('sort')) {
        //     switch ($request->sort) {
        //         case 'price_asc':
        //             $query->orderBy('price', 'asc');
        //             break;
        //         case 'price_desc':
        //             $query->orderBy('price', 'desc');
        //             break;
        //         case 'name_asc':
        //             $query->orderBy('name', 'asc');
        //             break;
        //         case 'name_desc':
        //             $query->orderBy('name', 'desc');
        //             break;
        //         case 'popularity':
        //             $query->withCount('reviews')->orderBy('reviews_count', 'desc');
        //             break;
        //     }
        // }

        $products = $query->get();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
            'rating' => 'nullable|numeric|min:0|max:5',
            'sales_count' => 'nullable|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produit ajouté avec succès !');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
            'rating' => 'nullable|numeric|min:0|max:5',
            'sales_count' => 'nullable|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès !');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès !');
    }
}
