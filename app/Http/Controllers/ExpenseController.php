<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Helpers\ActivityHelper;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class ExpenseController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Expense List
    |--------------------------------------------------------------------------
    */

   public function index(Request $request)
{

    $query = Expense::with([
        'category',
        'user'
    ]);


    // Search
    if($request->search){

        $search = $request->search;

        $query->where(function($q) use ($search){

            $q->where('expense_number','like',"%{$search}%")

            ->orWhere('description','like',"%{$search}%")

            ->orWhereHas('category', function($cat) use ($search){

                $cat->where('name','like',"%{$search}%");

            });

        });

    }



    // Category filter
    if($request->category){

        $query->where(
            'expense_category_id',
            $request->category
        );

    }



    // Payment method filter
    if($request->payment_method){

        $query->where(
            'payment_method',
            $request->payment_method
        );

    }



    // Date filter
    if($request->from){

        $query->whereDate(
            'expense_date',
            '>=',
            $request->from
        );

    }


    if($request->to){

        $query->whereDate(
            'expense_date',
            '<=',
            $request->to
        );

    }



    $expenses = $query
        ->latest()
        ->paginate(15)
        ->withQueryString();



    $categories = ExpenseCategory::where(
        'status',
        'Active'
    )
    ->orderBy('name')
    ->get();



    $totalExpenses = Expense::sum('amount');



    $todayExpenses = Expense::whereDate(
        'expense_date',
        today()
    )
    ->sum('amount');



    $monthExpenses = Expense::whereMonth(
        'expense_date',
        now()->month
    )
    ->whereYear(
        'expense_date',
        now()->year
    )
    ->sum('amount');



    return view('expenses.index',
    compact(
        'expenses',
        'categories',
        'totalExpenses',
        'todayExpenses',
        'monthExpenses'
    ));

}




    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {

        $categories = ExpenseCategory::where(
                'status',
                'Active'
            )
            ->orderBy('name')
            ->get();



        return view(
            'expenses.create',
            compact('categories')
        );

    }







    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        $request->validate([

            'expense_category_id'
                =>
            'required|exists:expense_categories,id',

            'amount'
                =>
            'required|numeric|min:0',

            'expense_date'
                =>
            'required|date',

            'payment_method'
                =>
            'required|string',

            'description'
                =>
            'nullable|string',

        ]);

        $receipt = null;


        if($request->hasFile('receipt')){

            $receipt = $request
                ->file('receipt')
                ->store('expense_receipts','public');

        }



        $expense = Expense::create([

            'expense_number'
                =>
            'EXP-'
            .now()->format('YmdHis')
            .'-'
            .strtoupper(substr(uniqid(),-4)),


            'expense_category_id'
                =>
            $request->expense_category_id,


            'amount'
                =>
            $request->amount,


            'expense_date'
                =>
            $request->expense_date,


            'payment_method'
                =>
            $request->payment_method,


            'description'
                =>
            $request->description,

            'receipt'
                =>
                $receipt,

            'user_id'
                =>
            Auth::id(),

        ]);





        ActivityHelper::log(

            'Created',

            'Expense',

            'Created expense: '
            .$expense->expense_number

        );





        NotificationHelper::create(

            title:'New Expense',

            message:
            'Expense of ₦'
            .number_format($expense->amount,2)
            .' recorded.',

            type:'info',

            role:'Administrator'

        );





        return redirect()

            ->route('expenses.index')

            ->with(
                'success',
                'Expense added successfully.'
            );

    }








    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Expense $expense)
    {

        $categories = ExpenseCategory::where(
                'status',
                'Active'
            )
            ->orderBy('name')
            ->get();



        return view(
            'expenses.edit',
            compact(
                'expense',
                'categories'
            )
        );

    }








    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Expense $expense
    )
    {


        $request->validate([

            'expense_category_id'
                =>
            'required|exists:expense_categories,id',

            'amount'
                =>
            'required|numeric|min:0',

            'expense_date'
                =>
            'required|date',

            'payment_method'
                =>
            'required|string',

            'description'
                =>
            'nullable|string',
            'receipt'
                =>
            'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

        ]);




        $expense->update([


            'expense_category_id'
                =>
            $request->expense_category_id,


            'amount'
                =>
            $request->amount,


            'expense_date'
                =>
            $request->expense_date,


            'payment_method'
                =>
            $request->payment_method,


            'description'
                =>
            $request->description,


        ]);






        ActivityHelper::log(

            'Updated',

            'Expense',

            'Updated expense: '
            .$expense->expense_number

        );





        NotificationHelper::create(

            title:'Expense Updated',

            message:
            'Expense '
            .$expense->expense_number
            .' updated.',

            type:'info',

            role:'Administrator'

        );





        return back()

            ->with(
                'success',
                'Expense updated successfully.'
            );

    }









    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(Expense $expense)
    {


        $expenseNumber = $expense->expense_number;



        ActivityHelper::log(

            'Deleted',

            'Expense',

            'Deleted expense: '
            .$expenseNumber

        );






        NotificationHelper::create(

            title:'Expense Deleted',

            message:
            'Expense '
            .$expenseNumber
            .' deleted.',

            type:'warning',

            role:'Administrator'

        );






        $expense->delete();





        return back()
        ->with(
            'success',
            'Expense deleted successfully.'
        );

    }

public function exportPdf(Request $request)
{

    $expenses = Expense::with([
        'category',
        'user'
    ])
    ->when($request->from, function($query) use ($request){

        $query->whereDate(
            'expense_date',
            '>=',
            $request->from
        );

    })
    ->when($request->to, function($query) use ($request){

        $query->whereDate(
            'expense_date',
            '<=',
            $request->to
        );

    })
    ->latest()
    ->get();



    $total = $expenses->sum('amount');



    $pdf = Pdf::loadView(
        'expenses.pdf',
        compact(
            'expenses',
            'total'
        )
    );



    return $pdf->download(
        'expense-report.pdf'
    );

}


}