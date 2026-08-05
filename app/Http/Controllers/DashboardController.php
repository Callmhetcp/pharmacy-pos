<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\PurchaseReturn;
use App\Models\SalesReturn;
use App\Models\Supplier;
use App\Models\Expense;
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

    $totalExpenses = Expense::sum('amount');

    $totalSuppliers = Supplier::count();

    $totalMedicines = Medicine::count();

    /*
    |--------------------------------------------------------------------------
    | Today's Statistics
    |--------------------------------------------------------------------------
    */

    $todaySales = Sale::whereDate('sale_date', today())
        ->sum('total_amount');

    $todayPurchases = Purchase::whereDate('purchase_date', today())
        ->sum('grand_total');
    
    $todayExpenses = Expense::whereDate('expense_date', today())
    ->sum('amount');

    $todaySalesReturns = SalesReturn::whereDate('return_date', today())
        ->sum('total_amount');

    $todayPurchaseReturns = PurchaseReturn::whereDate('return_date', today())
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

$salesChart = Sale::selectRaw('DATE(sale_date) as date')
    ->selectRaw('SUM(total_amount) as total')
    ->whereDate('sale_date', '>=', now()->subDays(6))
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

$expenseChart = Expense::selectRaw('DATE(expense_date) as date')
    ->selectRaw('SUM(amount) as total')
    ->whereDate('expense_date', '>=', now()->subDays(6))
    ->groupBy(DB::raw('DATE(expense_date)'))
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

    'expenses' => Expense::sum('amount'),

];

/*
|--------------------------------------------------------------------------
| Profit Calculation
|--------------------------------------------------------------------------
*/

$totalProfit = DB::table('sale_items')
    ->selectRaw(
        'SUM((unit_price - cost_price) * quantity) as profit'
    )
    ->value('profit');


$totalProfit = $totalProfit ?? 0;

/*
|--------------------------------------------------------------------------
| Inventory Value
|--------------------------------------------------------------------------
*/

$stockValue = Medicine::selectRaw(
    'SUM(quantity * cost_price) as value'
)->value('value');


$stockValue = $stockValue ?? 0;

/*
|--------------------------------------------------------------------------
| Sales Quantity
|--------------------------------------------------------------------------
*/

$totalItemsSold = DB::table('sale_items')
    ->sum('quantity');

/*
|--------------------------------------------------------------------------
| Returns Summary
|--------------------------------------------------------------------------
*/

$totalSalesReturns = SalesReturn::sum('total_amount');

$totalPurchaseReturns = PurchaseReturn::sum('total_amount');

$netProfit = 

    Sale::sum('total_amount')

    - Purchase::sum('grand_total')

    - Expense::sum('amount')

    + SalesReturn::sum('total_amount')

    - PurchaseReturn::sum('total_amount');

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
        
        'totalProfit',

        'stockValue',

        'totalItemsSold',

        'totalSalesReturns',

        'totalPurchaseReturns',

        'totalExpenses',

        'todayExpenses',

        'expenseChart',

        'netProfit',

    ));
}
}
