<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::all(); // This retrieves all products
        return view('home', compact('products')); // Pass to the view
    }
}
