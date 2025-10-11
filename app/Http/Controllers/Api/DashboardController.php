<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalItems = Item::count();
        $totalStock = Item::sum('stock');
        $totalValue = Item::selectRaw('SUM(stock * price) as total')->first()->total ?? 0;
        $totalCategories = Category::count();
        
        $todayIn = Transaction::where('type', 'masuk')
            ->whereDate('transaction_date', today())
            ->sum('quantity');
        
        $todayOut = Transaction::where('type', 'keluar')
            ->whereDate('transaction_date', today())
            ->sum('quantity');
        
        $recentTransactions = Transaction::with('item.category')
            ->latest()
            ->take(10)
            ->get();
        
        $lowStockItems = Item::with('category')
            ->where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_items' => $totalItems,
                    'total_stock' => $totalStock,
                    'total_value' => $totalValue,
                    'total_categories' => $totalCategories,
                    'today_in' => $todayIn,
                    'today_out' => $todayOut,
                ],
                'recent_transactions' => $recentTransactions,
                'low_stock_items' => $lowStockItems,
            ]
        ]);
    }
}