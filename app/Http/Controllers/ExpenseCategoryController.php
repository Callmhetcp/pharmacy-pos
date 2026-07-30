<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Helpers\ActivityHelper;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Expense Categories
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        $categories = ExpenseCategory::when($search, function ($query) use ($search) {

                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");

            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'expense_categories.index',
            compact('categories', 'search')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('expense_categories.create');
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required|max:255|unique:expense_categories',

            'description' => 'nullable|max:500',

        ]);

        $category = ExpenseCategory::create([

            'name' => $request->name,

            'description' => $request->description,

            'status' => 'Active',

        ]);

        ActivityHelper::log(

            'Created',

            'Expense Category',

            'Created expense category: ' . $category->name

        );

        NotificationHelper::create(

            title: 'Expense Category',

            message: $category->name . ' category has been created.',

            type: 'success',

            role: 'Administrator'

        );

        return redirect()
            ->route('expense-categories.index')
            ->with(
                'success',
                'Expense category created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(ExpenseCategory $expenseCategory)
    {
        return view(
            'expense_categories.edit',
            compact('expenseCategory')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        ExpenseCategory $expenseCategory
    )
    {
        $request->validate([

            'name' => 'required|max:255|unique:expense_categories,name,' . $expenseCategory->id,

            'description' => 'nullable|max:500',

        ]);

        $expenseCategory->update([

            'name' => $request->name,

            'description' => $request->description,

        ]);

        ActivityHelper::log(

            'Updated',

            'Expense Category',

            'Updated expense category: ' . $expenseCategory->name

        );

        return redirect()
            ->route('expense-categories.index')
            ->with(
                'success',
                'Expense category updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Toggle Status
    |--------------------------------------------------------------------------
    */

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $expenseCategory->status =
            $expenseCategory->status == 'Active'
                ? 'Inactive'
                : 'Active';

        $expenseCategory->save();

        ActivityHelper::log(

            'Status Changed',

            'Expense Category',

            'Changed status of ' . $expenseCategory->name

        );

        return back()->with(
            'success',
            'Expense category status updated successfully.'
        );
    }
}