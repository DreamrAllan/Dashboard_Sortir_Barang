<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Category;

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

        return view('dashboard', compact(
            'totalItems',
            'totalStock',
            'totalValue',
            'totalCategories',
            'todayIn',
            'todayOut',
            'recentTransactions',
            'lowStockItems'
        ));
    }
}