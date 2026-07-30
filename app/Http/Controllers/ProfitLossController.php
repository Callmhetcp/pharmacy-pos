<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        $from = $request->from
            ? Carbon::parse($request->from)->startOfDay()
            : Carbon::now()->startOfMonth();

        $to = $request->to
            ? Carbon::parse($request->to)->endOfDay()
            : Carbon::now()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Revenue
        |--------------------------------------------------------------------------
        */

        $totalSales = Sale::whereBetween(
                'sale_date',
                [$from, $to]
            )
            ->sum('total_amount');

        /*
        |--------------------------------------------------------------------------
        | Cost Of Goods Sold
        |--------------------------------------------------------------------------
        */

        $cogs = SaleItem::whereHas('sale', function ($query) use ($from, $to) {

                $query->whereBetween(
                    'sale_date',
                    [$from, $to]
                );

            })
            ->selectRaw('SUM(cost_price * quantity) as total')
            ->value('total') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Gross Profit
        |--------------------------------------------------------------------------
        */

        $grossProfit = $totalSales - $cogs;

        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        $totalExpenses = Expense::whereBetween(
                'expense_date',
                [
                    $from->toDateString(),
                    $to->toDateString()
                ]
            )
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Net Profit
        |--------------------------------------------------------------------------
        */

        $netProfit = $grossProfit - $totalExpenses;

        /*
        |--------------------------------------------------------------------------
        | Monthly Chart
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];

        $salesChart = [];

        $expenseChart = [];

        $profitChart = [];

        for ($i = 1; $i <= 12; $i++) {

            $chartLabels[] = Carbon::create()->month($i)->format('M');

            $sales = Sale::whereYear('sale_date', now()->year)
                ->whereMonth('sale_date', $i)
                ->sum('total_amount');

            $monthCogs = SaleItem::whereHas('sale', function ($query) use ($i) {

                    $query->whereYear('sale_date', now()->year)
                          ->whereMonth('sale_date', $i);

                })
                ->selectRaw('SUM(cost_price * quantity) as total')
                ->value('total') ?? 0;

            $expenses = Expense::whereYear('expense_date', now()->year)
                ->whereMonth('expense_date', $i)
                ->sum('amount');

            $profit = ($sales - $monthCogs) - $expenses;

            $salesChart[] = $sales;
            $expenseChart[] = $expenses;
            $profitChart[] = $profit;
        }

        /*
        |--------------------------------------------------------------------------
        | Top Expense Categories
        |--------------------------------------------------------------------------
        */

        $expenseCategories = Expense::select(
                'expense_category_id',
                DB::raw('SUM(amount) as total')
            )
            ->with('category')
            ->groupBy('expense_category_id')
            ->orderByDesc('total')
            ->get();

        return view(
            'reports.profit-loss',
            compact(
                'from',
                'to',
                'totalSales',
                'cogs',
                'grossProfit',
                'totalExpenses',
                'netProfit',
                'chartLabels',
                'salesChart',
                'expenseChart',
                'profitChart',
                'expenseCategories'
            )
        );
    }
}
