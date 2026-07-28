<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function index()
    {
        $todaySales = Sale::where('user_id', Auth::id())
            ->whereDate('sale_date', today())
            ->sum('total_amount');

        $todayTransactions = Sale::where('user_id', Auth::id())
            ->whereDate('sale_date', today())
            ->count();

        $recentSales = Sale::with('customer')
            ->where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        return view('cashier.dashboard', compact(
            'todaySales',
            'todayTransactions',
            'recentSales'
        ));
    }
}