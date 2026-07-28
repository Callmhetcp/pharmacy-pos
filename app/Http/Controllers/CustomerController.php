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

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|min:3|max:100',
        'address' => 'required|string|min:5',
        'phone_number' => 'required|string|min:10|max:15',
    ]);

    try {

        $customer = new Customer();

        $customer->name = $request->name;
        $customer->phone_number = $request->phone_number;
        $customer->address = $request->address;

        $customer->save();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer added successfully.');

    } catch (\Exception $e) {

        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Failed to add customer.');

    }
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

   public function destroy(Customer $customer)
        {
            $customer->update([
                'status' => 'Inactive'
            ]);

            return redirect()
                ->back()
                ->with('success','Customer marked as inactive.');
        }
    
   public function toggleStatus(Customer $customer)
        {
            $customer->update([
                'status' => $customer->status == 'Active'
                    ? 'Inactive'
                    : 'Active'
            ]);

            return redirect()->back()
                ->with('success','Customer status updated successfully.');
        }
}
