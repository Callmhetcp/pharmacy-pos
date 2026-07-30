<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Helpers\ActivityHelper;
use App\Helpers\NotificationHelper;
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

        ActivityHelper::log(
            'Created',
            'Customer',
            'Added customer: ' . $customer->name
        );

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

        ActivityHelper::log(
            'Updated',
            'Customer',
            'Updated customer: ' . $customer->name
        );

        return redirect('/customers');

    }

   public function destroy(Customer $customer)
        {
            
            $customer->update([
                'status' => 'Inactive'
            ]);

             ActivityHelper::log(
                'Updated',
                'Customer',
                'Deactivated customer: ' . $customer->name
            );


            return redirect()
                ->back()
                ->with('success','Customer marked as inactive.');
        }
    
  public function toggleStatus(Customer $customer)
{
    $newStatus = $customer->status == 'Active'
        ? 'Inactive'
        : 'Active';


    $customer->update([
        'status' => $newStatus
    ]);


    ActivityHelper::log(
        'Updated',
        'Customer',
        'Changed customer status: ' . 
        $customer->name . 
        ' to ' . 
        $newStatus
    );
    NotificationHelper::create(
    title: 'New Customer',
    message: $customer->name . ' was registered.',
    type: 'info',
    role: 'Cashier'
);


    return redirect()
        ->back()
        ->with('success','Customer status updated successfully.');
}
}
