<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\Supplier;

class StorekeeperController extends Controller
{
    public function index()
    {
        $totalMedicines = Medicine::count();

        $totalSuppliers = Supplier::count();

        $todayPurchases = Purchase::whereDate('purchase_date', today())
            ->count();

        $lowStock = Medicine::whereColumn(
            'quantity',
            '<=',
            'minimum_stock'
        )->count();

        $recentPurchases = Purchase::with('supplier')
            ->latest()
            ->take(10)
            ->get();

        return view('storekeeper.dashboard', compact(
            'totalMedicines',
            'totalSuppliers',
            'todayPurchases',
            'lowStock',
            'recentPurchases'
        ));
    }
}