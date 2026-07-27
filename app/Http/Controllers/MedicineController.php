<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
   public function index(Request $request)
{
    $search = $request->search;

    $medicines = Medicine::with('category')
        ->when($search, function ($query) use ($search) {

            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('quantity', 'like', "%{$search}%")
                  ->orWhere('cost_price', 'like', "%{$search}%")
                  ->orWhere('selling_price', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {

                      $q->where('name', 'like', "%{$search}%");

                  });

        })
        ->latest()
        ->paginate(20)
        ->withQueryString();

    $categories = Category::all();

    if ($request->ajax()) {

        return view('medicines.table', compact('medicines'))->render();

    }

    return view('medicines.index', compact('medicines', 'categories', 'search'));
}

    public function store(Request $request){

    $request->validate([
        'name'=>'required|string|min:3|max:100',
        'minimum_stock' => 'required|integer|min:0',
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
        $medicine->minimum_stock = $request->minimum_stock;
        $medicine->cost_price = $request->cost_price;
        $medicine->selling_price = $request->selling_price;
        $medicine->expiry_date = $request->expiry_date;
        $medicine->category_id = $request->category_id;

        $medicine->save();

        return redirect('/medicines');


    }

   public function destroy($id)
{
    $medicine = Medicine::findOrFail($id);

    if (
        $medicine->saleItems()->exists() ||
        $medicine->purchaseItems()->exists() ||
        $medicine->stockMovements()->exists() ||
        $medicine->stockAdjustments()->exists() ||
        $medicine->purchaseReturnItems()->exists() ||
        $medicine->salesReturnItems()->exists()
    ) {
        return redirect('/medicines')
            ->with('error', 'Cannot delete this medicine because it has transaction history.');
    }

    $medicine->delete();

    return redirect('/medicines')
        ->with('success', 'Medicine deleted successfully.');
}

 public function search(Request $request)
{
    $search = $request->search;

    $medicines = Medicine::where('name', 'like', "%{$search}%")
        ->where('quantity', '>', 0)
        ->select('id', 'name', 'selling_price', 'quantity')
        ->limit(10)
        ->get();

    return response()->json($medicines);
}
}
