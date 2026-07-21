<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
    public function index(){

        $medicines = Medicine::all();
        $categories = Category::all();

        return view('medicines.index', compact('medicines','categories'));

        
    }

    public function store(Request $request){

    $request->validate([
        'name'=>'required|string|min:3|max:100',
        'quantity'=>'required|integer|min:0',
        'cost_price'=>'required|numeric|min:0',
        'selling_price'=>'required|numeric|min:0',
        
        'expiry_date'=>'required|date'
    ]);

    $medicine = new Medicine();

    $medicine->name = $request->name;
    $medicine->quantity = $request->quantity;
    $medicine->cost_price = $request->cost_price;
    $medicine->selling_price = $request->selling_price;
    $medicine->expiry_date = $request->expiry_date;
    $medicine->category_id = $request->category_id;

    $medicine->save();

    return redirect('/medicines')
        ->with('success', 'Medicine saved successfully');

    }

    public function edit($id){

        $medicine = Medicine::find($id);
        $categories = Category::all();

        return view('medicines.edit', compact('medicine', 'categories'));

    }

    public function update(Request $request, $id){

        $medicine = Medicine::find($id);

        $medicine->name = $request->name;
        $medicine->quantity = $request->quantity;
        $medicine->cost_price = $request->cost_price;
        $medicine->selling_price = $request->selling_price;
        $medicine->expiry_date = $request->expiry_date;
        $medicine->category_id = $request->category_id;

        $medicine->save();

        return redirect('/medicines');


    }

    public function destroy($id){

    $medicine = Medicine::find($id);

    $medicine->delete();

    return redirect('/medicines')
        ->with('success','Medicine has been deleted successfully.');

    }
}
