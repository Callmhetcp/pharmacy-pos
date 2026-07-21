<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(){

    $customers = Customer::all();

    return view ('customers.index', compact ('customers'));

        

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
