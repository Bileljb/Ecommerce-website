<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get total orders
        $totalOrders = Order::count();
        
        // Get total turnover (sum of all completed orders)
        $totalTurnover = Order::where('status', 'completed')
            ->sum('total_price');
        
        // Get total users
        $totalUsers = User::count();
        
        // Get sales evolution data for the last 7 days
        $dailySales = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COALESCE(SUM(total_price), 0) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('Y-m-d'),
                    'total' => (float) $item->total
                ];
            });

        // Calculate max value for Y-axis
        $maxValue = $dailySales->max('total');
        $yAxisMax = ceil($maxValue / 100) * 100; // Round up to nearest hundred
        
        // Get top 5 best-selling products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->with('product:id,name,price')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'totalOrders',
            'totalTurnover',
            'totalUsers',
            'dailySales',
            'topProducts',
            'yAxisMax'
        ));
    }
} 