<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\PurchaseReturn;
use App\Models\SalesReturn;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    /*
    |--------------------------------------------------------------------------
    | Overall Statistics
    |--------------------------------------------------------------------------
    */

    $totalSales = Sale::sum('total_amount');

    $totalPurchases = Purchase::sum('grand_total');

    $totalCustomers = Customer::count();

    $totalSuppliers = Supplier::count();

    $totalMedicines = Medicine::count();

    /*
    |--------------------------------------------------------------------------
    | Today's Statistics
    |--------------------------------------------------------------------------
    */

    $todaySales = Sale::whereDate('created_at', today())
        ->sum('total_amount');

    $todayPurchases = Purchase::whereDate('created_at', today())
        ->sum('grand_total');

    $todaySalesReturns = SalesReturn::whereDate('created_at', today())
        ->sum('total_amount');

    $todayPurchaseReturns = PurchaseReturn::whereDate('created_at', today())
        ->sum('total_amount');

    /*
|--------------------------------------------------------------------------
| Inventory Overview
|--------------------------------------------------------------------------
*/

$lowStockCount = Medicine::whereColumn('quantity', '<=', 'minimum_stock')
    ->count();

$outOfStockCount = Medicine::where('quantity', 0)
    ->count();

$expiredCount = Medicine::whereDate('expiry_date', '<', today())
    ->count();

$expiringSoonCount = Medicine::whereBetween(
    'expiry_date',
    [
        today(),
        today()->addDays(30)
    ]
)->count();
/*
|--------------------------------------------------------------------------
| Recent Activities
|--------------------------------------------------------------------------
*/

$recentSales = Sale::with('customer')
    ->latest()
    ->take(5)
    ->get();

$recentPurchases = Purchase::with('supplier')
    ->latest()
    ->take(5)
    ->get();

$recentSalesReturns = SalesReturn::with('customer')
    ->latest()
    ->take(5)
    ->get();

$recentPurchaseReturns = PurchaseReturn::with('supplier')
    ->latest()
    ->take(5)
    ->get();

/*
|--------------------------------------------------------------------------
| Sales Chart (Last 7 Days)
|--------------------------------------------------------------------------
*/

$salesChart = Sale::selectRaw('DATE(created_at) as date')
    ->selectRaw('SUM(total_amount) as total')
    ->whereDate('created_at', '>=', now()->subDays(6))
    ->groupBy('date')
    ->orderBy('date')
    ->get();

    /*
|--------------------------------------------------------------------------
| Purchases Chart (Last 7 Days)
|--------------------------------------------------------------------------
*/

$purchaseChart = Purchase::selectRaw('DATE(purchase_date) as date')
    ->selectRaw('SUM(grand_total) as total')
    ->whereDate('purchase_date', '>=', now()->subDays(6))
    ->groupBy(DB::raw('DATE(purchase_date)'))
    ->orderBy('date')
    ->get();

/*
|--------------------------------------------------------------------------
| Revenue Breakdown
|--------------------------------------------------------------------------
*/

$revenueBreakdown = [

    'sales' => Sale::sum('total_amount'),

    'purchases' => Purchase::sum('grand_total'),

    'salesReturns' => SalesReturn::sum('total_amount'),

    'purchaseReturns' => PurchaseReturn::sum('total_amount'),

];

    return view('dashboard.index', compact(

        'totalSales',

        'totalPurchases',

        'totalCustomers',

        'totalSuppliers',

        'totalMedicines',

        'todaySales',

        'todayPurchases',

        'todaySalesReturns',

        'todayPurchaseReturns',

        'lowStockCount',

        'outOfStockCount',

        'expiredCount',

        'expiringSoonCount',

        'recentSales',

        'recentPurchases',

        'recentSalesReturns',

        'recentPurchaseReturns',

        'salesChart',

        'purchaseChart',

        'revenueBreakdown',

    ));
}
}
