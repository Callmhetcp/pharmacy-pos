<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
{
    $search = $request->search;

    $customers = Customer::when($search, function ($query) use ($search) {

        $query->where('name', 'like', "%{$search}%")
              ->orWhere('phone_number', 'like', "%{$search}%")
              ->orWhere('address', 'like', "%{$search}%");

    })
    ->latest()
    ->paginate(20)
    ->withQueryString();

    if ($request->ajax()) {

        return view('customers.table', compact('customers'))->render();

    }

    return view('customers.index', compact('customers', 'search'));
}

    public function store(Request $request){

    $request->validate([
            'name' => 'required|string|min:3|max:100',
            'address' => 'required|string|min:5',
            'phone_number' => 'required|string|min:10|max:15',
        ]);

    $customer = new Customer();

    $customer->name = $request->name;
    $customer->phone_number = $request->phone_number;
    $customer->address = $request->address;

    $customer->save();

    return "Customer Saved Successfully!";
    

    }

    public function edit($id){

        $customer = Customer::find($id);

        return view('customers.edit', compact('customer'));

    }

    public function update(Request $request,$id){

        $customer = Customer::find($id);

        $customer->name = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->address = $request->address;

        $customer->save();

        return redirect('/customers');

    }

    public function destroy($id){

        $customer = Customer::find($id);

        $customer->delete();

        return redirect ('/customers')
            ->with('sucess','Suppliers have been deleted successfully.');

    }
}
