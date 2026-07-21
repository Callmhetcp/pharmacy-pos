<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index(){

        $suppliers = Supplier::all();

    return view('suppliers.index', compact('suppliers'));

    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'address' => 'required|string|min:5',
            'phone_number' => 'required|string|min:10|max:15',
        ]);
        $supplier = new Supplier();

        $supplier->name = $request->name;
        $supplier->address = $request->address;
        $supplier->phone_number = $request->phone_number;

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

        $supplier->name = $request->name;
        $supplier->address = $request->address;
        $supplier->phone_number = $request->phone_number;

        $supplier->save();

        return redirect('/suppliers')
        ->with('success', 'Supplier has been edited successfully.');

    }

    public function destroy($id){

    $supplier = Supplier::find($id);

    $supplier->delete();

    return redirect('/suppliers')
        ->with('success','Supplier has been deleted successfully.');

    }
}
