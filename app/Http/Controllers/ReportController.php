<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Medicine;
use App\Models\PurchaseItem;
use App\Models\Purchase;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

  public function sales(Request $request)
{
    switch ($request->period) {

        case 'today':
            $from = now()->toDateString();
            $to = now()->toDateString();
            break;

        case 'yesterday':
            $from = now()->subDay()->toDateString();
            $to = now()->subDay()->toDateString();
            break;

        case 'week':
            $from = now()->startOfWeek()->toDateString();
            $to = now()->endOfWeek()->toDateString();
            break;

        case 'month':
            $from = now()->startOfMonth()->toDateString();
            $to = now()->endOfMonth()->toDateString();
            break;

        case 'year':
            $from = now()->startOfYear()->toDateString();
            $to = now()->endOfYear()->toDateString();
            break;

        default:
            $from = $request->from ?? now()->startOfMonth()->toDateString();
            $to = $request->to ?? now()->toDateString();
            break;
    }

    $query = Sale::with(['customer', 'user'])
        ->whereBetween('sale_date', [
            Carbon::parse($from)->startOfDay(),
            Carbon::parse($to)->endOfDay(),
        ]);

    $sales = (clone $query)
        ->latest('sale_date')
        ->paginate(20)
        ->withQueryString();

    $totalSales = (clone $query)->sum('total_amount');
    $totalPaid = (clone $query)->sum('amount_paid');
    $totalBalance = (clone $query)->sum('balance');
    $transactions = (clone $query)->count();
    $paymentMethods = (clone $query)
    ->selectRaw('payment_method, SUM(total_amount) as total')
    ->groupBy('payment_method')
    ->orderByDesc('total')
    ->get();
    $cashierSales = (clone $query)
    ->selectRaw('user_id, COUNT(*) as transactions, SUM(total_amount) as total_sales')
    ->with('user')
    ->groupBy('user_id')
    ->orderByDesc('total_sales')
    ->get();
   $topMedicines = SaleItem::selectRaw(
    'medicine_id,
     SUM(quantity) as quantity_sold,
     SUM(subtotal) as revenue'
    )
    ->with('medicine')
    ->groupBy('medicine_id')
    ->orderByDesc('quantity_sold')
    ->take(10)
    ->get();

$dailySales = (clone $query)
    ->selectRaw('DATE(sale_date) as sale_day, SUM(total_amount) as total_sales, COUNT(*) as transactions')
    ->groupByRaw('DATE(sale_date)')
    ->orderBy('sale_day')
    ->get();

    return view('reports.sales', compact(
        'sales',
        'from',
        'to',
        'totalSales',
        'totalPaid',
        'totalBalance',
        'transactions',
        'paymentMethods',
        'cashierSales',
        'topMedicines',
        'dailySales'

    ));
}
   public function purchases(Request $request)
{
    switch ($request->period) {

        case 'today':
            $from = now()->toDateString();
            $to = now()->toDateString();
            break;

        case 'yesterday':
            $from = now()->subDay()->toDateString();
            $to = now()->subDay()->toDateString();
            break;

        case 'week':
            $from = now()->startOfWeek()->toDateString();
            $to = now()->endOfWeek()->toDateString();
            break;

        case 'month':
            $from = now()->startOfMonth()->toDateString();
            $to = now()->endOfMonth()->toDateString();
            break;

        case 'year':
            $from = now()->startOfYear()->toDateString();
            $to = now()->endOfYear()->toDateString();
            break;

        default:
            $from = $request->from ?? now()->startOfMonth()->toDateString();
            $to = $request->to ?? now()->toDateString();
            break;
    }

    $query = Purchase::with(['supplier', 'user'])
        ->whereBetween('purchase_date', [
            Carbon::parse($from)->startOfDay(),
            Carbon::parse($to)->endOfDay(),
        ]);

    $purchases = (clone $query)
        ->latest('purchase_date')
        ->paginate(20)
        ->withQueryString();

    $totalPurchases = (clone $query)->count();

    $totalAmount = (clone $query)->sum('grand_total');

    $averagePurchase = $totalPurchases > 0
        ? $totalAmount / $totalPurchases
        : 0;

    $supplierSummary = (clone $query)
        ->selectRaw('supplier_id, COUNT(*) as purchases, SUM(grand_total) as amount')
        ->with('supplier')
        ->groupBy('supplier_id')
        ->orderByDesc('amount')
        ->get();

    return view('reports.purchases', compact(
        'purchases',
        'from',
        'to',
        'totalPurchases',
        'totalAmount',
        'averagePurchase',
        'supplierSummary'
    ));
}
    public function inventory()
{
    $medicines = Medicine::with('category')
        ->orderBy('name')
        ->paginate(20);

    $totalMedicines = Medicine::count();

    $totalStock = Medicine::sum('quantity');

    $outOfStock = Medicine::where('quantity', 0)->count();

    $lowStock = Medicine::whereColumn('quantity', '<=', 'minimum_stock')->count();

    $inventoryCost = Medicine::selectRaw('SUM(quantity * cost_price) as total')
        ->value('total');

    $inventoryValue = Medicine::selectRaw('SUM(quantity * selling_price) as total')
        ->value('total');

    return view('reports.inventory', compact(
        'medicines',
        'totalMedicines',
        'totalStock',
        'outOfStock',
        'lowStock',
        'inventoryCost',
        'inventoryValue'
    ));
}

   public function profit(Request $request)
{
    switch ($request->period) {

        case 'today':
            $from = now()->toDateString();
            $to = now()->toDateString();
            break;

        case 'week':
            $from = now()->startOfWeek()->toDateString();
            $to = now()->endOfWeek()->toDateString();
            break;

        case 'month':
            $from = now()->startOfMonth()->toDateString();
            $to = now()->endOfMonth()->toDateString();
            break;

        case 'year':
            $from = now()->startOfYear()->toDateString();
            $to = now()->endOfYear()->toDateString();
            break;

        default:
            $from = $request->from ?? now()->startOfMonth()->toDateString();
            $to = $request->to ?? now()->toDateString();
    }

    $items = SaleItem::with('medicine', 'sale')
        ->whereHas('sale', function ($q) use ($from, $to) {
            $q->whereBetween('sale_date', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);
        })
        ->get();

    $revenue = $items->sum('subtotal');

    $cost = $items->sum(function ($item) {
        return $item->quantity * ($item->medicine->cost_price ?? 0);
    });

    $profit = $revenue - $cost;

    $margin = $revenue > 0
        ? ($profit / $revenue) * 100
        : 0;

    return view('reports.profit', compact(
        'items',
        'from',
        'to',
        'revenue',
        'cost',
        'profit',
        'margin'
    ));
}

   public function medicines()
{
    $medicines = Medicine::with('category')
        ->orderBy('name')
        ->paginate(20);

    $topSelling = SaleItem::selectRaw('
            medicine_id,
            SUM(quantity) as total_quantity,
            SUM(subtotal) as total_sales
        ')
        ->with('medicine')
        ->groupBy('medicine_id')
        ->orderByDesc('total_quantity')
        ->take(10)
        ->get();

    $slowMoving = Medicine::with('category')
        ->whereDoesntHave('saleItems')
        ->orWhereHas('saleItems', function ($query) {
            $query->selectRaw('medicine_id, SUM(quantity) as qty')
                ->groupBy('medicine_id')
                ->havingRaw('SUM(quantity) <= 10');
        })
        ->take(10)
        ->get();

    return view('reports.medicines', compact(
        'medicines',
        'topSelling',
        'slowMoving'
    ));
}

    public function customers()
{
    $customers = Customer::withCount('sales')
        ->withSum('sales', 'total_amount')
        ->withSum('sales', 'amount_paid')
        ->withSum('sales', 'balance')
        ->paginate(20);

    $totalCustomers = Customer::count();

    $activeCustomers = Customer::has('sales')->count();

    $topCustomers = Customer::withSum('sales', 'total_amount')
        ->orderByDesc('sales_sum_total_amount')
        ->take(10)
        ->get();

    $outstandingBalance = Sale::sum('balance');

    return view('reports.customers', compact(
        'customers',
        'totalCustomers',
        'activeCustomers',
        'topCustomers',
        'outstandingBalance'
    ));
}

    public function lowStock()
{
    $medicines = Medicine::whereColumn('quantity', '<=', 'minimum_stock')
        ->orderBy('quantity')
        ->paginate(20);

    $totalLowStock = $medicines->total();

    return view('reports.low-stock', compact(
        'medicines',
        'totalLowStock'
    ));
}

public function expiry()
{
    $today = now()->startOfDay();
    $thirtyDays = now()->addDays(30)->endOfDay();
    $ninetyDays = now()->addDays(90)->endOfDay();

    $items = PurchaseItem::with('medicine')
        ->whereNotNull('expiry_date')
        ->whereDate('expiry_date', '<=', $ninetyDays)
        ->orderBy('expiry_date')
        ->get();

    return view('reports.expiry', compact(
        'items',
        'today',
        'thirtyDays',
        'ninetyDays'
    ));
}

private function downloadPdf($view, $data, $filename)
{
    $pdf = Pdf::loadView($view, $data);

    return $pdf->download($filename);
}
public function salesPdf()
{
    $sales = Sale::with(['customer','user'])->latest()->get();

    return $this->downloadPdf(

        'reports.pdf.sales',

        [

            'title'=>'Sales Report',

            'sales'=>$sales,

            'totalSales'=>$sales->sum('total_amount'),

            'totalPaid'=>$sales->sum('amount_paid'),

            'totalBalance'=>$sales->sum('balance'),

        ],

        'sales-report.pdf'

    );
}

public function purchasesPdf()
{
    $purchases = Purchase::with(['supplier','user'])
        ->latest('purchase_date')
        ->get();

    $totalPurchases = $purchases->count();

    $totalAmount = $purchases->sum('grand_total');

    $averagePurchase = $totalPurchases > 0
        ? $totalAmount / $totalPurchases
        : 0;

    return $this->downloadPdf(

        'reports.pdf.purchases',

        [
            'title' => 'Purchase Report',
            'purchases' => $purchases,
            'totalPurchases' => $totalPurchases,
            'totalAmount' => $totalAmount,
            'averagePurchase' => $averagePurchase,
        ],

        'purchase-report.pdf'

    );
}
public function inventoryPdf()
{
    $medicines = Medicine::with('category')
        ->orderBy('name')
        ->get();

    return $this->downloadPdf(

        'reports.pdf.inventory',

        [
            'title' => 'Inventory Report',
            'medicines' => $medicines,
            'totalMedicines' => Medicine::count(),
            'totalStock' => Medicine::sum('quantity'),
            'lowStock' => Medicine::whereColumn(
                'quantity',
                '<=',
                'minimum_stock'
            )->count(),
            'outOfStock' => Medicine::where('quantity',0)->count(),
            'inventoryCost' => Medicine::selectRaw(
                'SUM(quantity * cost_price) as total'
            )->value('total'),
            'inventoryValue' => Medicine::selectRaw(
                'SUM(quantity * selling_price) as total'
            )->value('total'),
        ],

        'inventory-report.pdf'

    );
}
public function medicinesPdf()
{
    $medicines = Medicine::with('category')
        ->orderBy('name')
        ->get();

    return $this->downloadPdf(

        'reports.pdf.medicines',

        [
            'title' => 'Medicine Report',
            'medicines' => $medicines,
        ],

        'medicine-report.pdf'

    );
}
public function customersPdf()
{
    $customers = Customer::withCount('sales')
        ->withSum('sales','total_amount')
        ->withSum('sales','amount_paid')
        ->withSum('sales','balance')
        ->get();

    return $this->downloadPdf(

        'reports.pdf.customers',

        [
            'title' => 'Customer Report',
            'customers' => $customers,
            'totalCustomers' => Customer::count(),
            'activeCustomers' => Customer::has('sales')->count(),
            'outstandingBalance' => Sale::sum('balance'),
        ],

        'customer-report.pdf'

    );
}
}
