<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Helpers\ActivityHelper;
use App\Helpers\NotificationHelper;

class SupplierController extends Controller
{
   public function index(Request $request)
{
    $search = $request->search;

    $suppliers = Supplier::when($search, function ($query) use ($search) {

        $query->where('company', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->orWhere('phone_number', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");

    })
    ->latest()
    ->paginate(20)
    ->withQueryString();

    // AJAX request
    if ($request->ajax()) {

        return view('suppliers.table', compact('suppliers'))->render();

    }

    return view('suppliers.index', compact('suppliers', 'search'));
}
    public function store(Request $request){

        $request->validate([
            'company' => 'required|string|max:255',
            'name' => 'required|string|min:3|max:100',
            'phone_number' => 'required|string|min:10|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|min:5',
            'status'=> 'required|in:Active,Inactive',
            'notes' => 'nullable|string',

        ]);
        $supplier = new Supplier();
        $supplier->company = $request->company;
        $supplier->name = $request->name;
        $supplier->phone_number = $request->phone_number;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->status = $request->status;
        $supplier->notes = $request->notes;

        $supplier->save();
        ActivityHelper::log(
            'Created',
            'Supplier',
            'Added supplier: ' . $supplier->name
        );
        NotificationHelper::create(
            title: 'New Supplier',
            message: $supplier->name . ' has been added.',
            type: 'success',
            role: 'admin'
        );



       return redirect('/suppliers')
    ->with('success', 'Supplier has been added successfully.');
    }

    public function edit($id){

        $supplier = Supplier::find($id);

        return view('suppliers.edit', compact('supplier'));

    }

    public function update(Request $request, $id){

        $supplier = Supplier::find($id);

        
        $request->validate([
            'company' => 'required|string|max:255',
            'name' => 'required|string|min:3|max:100',
            'phone_number' => 'required|string|min:10|max:15',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|min:5',
            'status'=> 'required|in:Active,Inactive',
            'notes' => 'nullable|string',

        ]);

        $supplier->company = $request->company;
        $supplier->name = $request->name;
        $supplier->phone_number = $request->phone_number;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->status = $request->status;
        $supplier->notes = $request->notes;

        $supplier->save();

        ActivityHelper::log(
            'Updated',
            'Supplier',
            'Updated supplier: ' . $supplier->name
        );

        return redirect('/suppliers')
        ->with('success', 'Supplier has been edited successfully.');

    }

  public function destroy(Supplier $supplier)
{
    $supplier->update([
        'status' => 'Inactive'
    ]);


    ActivityHelper::log(
        'Updated',
        'Supplier',
        'Deactivated supplier: ' . $supplier->name
    );


    return redirect()->back()
        ->with('success', 'Supplier marked as inactive successfully.');
}

public function activate(Supplier $supplier)
{
    $supplier->update([
        'status' => 'Active'
    ]);


    ActivityHelper::log(
        'Updated',
        'Supplier',
        'Activated supplier: ' . $supplier->name
    );


    return redirect()->back()
        ->with('success', 'Supplier activated successfully.');
}
}
