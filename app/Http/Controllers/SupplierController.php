<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

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

        return redirect('/suppliers')
        ->with('success', 'Supplier has been edited successfully.');

    }

    public function destroy(Supplier $supplier)
{
     
    $supplier->update([
        'status' => 'Inactive'
    ]);

    return redirect()->back()
        ->with('success', 'Supplier marked as inactive successfully.');
}

public function activate(Supplier $supplier)
{
    $supplier->update([
        'status' => 'Active'
    ]);

    return redirect()->back()
        ->with('success', 'Supplier activated successfully.');
}
}
