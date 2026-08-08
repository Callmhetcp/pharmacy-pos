<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Helpers\ActivityHelper;
use Illuminate\Support\Str;
use App\Helpers\NotificationHelper;


class MedicineController extends Controller
{
  public function index(Request $request)
{
    $search = trim($request->input('search', ''));

    $medicines = Medicine::with('category')
        ->when($search, function ($query) use ($search) {

            $search = strtolower($search);

            $query->where(function ($q) use ($search) {

                $q->whereRaw(
                    'LOWER(name) LIKE ?',
                    ["%{$search}%"]
                )

                ->orWhereRaw(
                    'CAST(quantity AS CHAR) LIKE ?',
                    ["%{$search}%"]
                )

                ->orWhereRaw(
                    'CAST(cost_price AS CHAR) LIKE ?',
                    ["%{$search}%"]
                )

                ->orWhereRaw(
                    'CAST(selling_price AS CHAR) LIKE ?',
                    ["%{$search}%"]
                )

                ->orWhereHas('category', function ($categoryQuery) use ($search) {

                    $categoryQuery->whereRaw(
                        'LOWER(name) LIKE ?',
                        ["%{$search}%"]
                    );

                });

            });

        })
        ->latest()
        ->paginate(20)
        ->withQueryString();

    $categories = Category::all();

    if ($request->ajax()) {

        return view(
            'medicines.table',
            compact('medicines')
        )->render();

    }

    return view(
        'medicines.index',
        compact(
            'medicines',
            'categories',
            'search'
        )
    );
}

    public function store(Request $request){

    $request->validate([
        'name'=>'required|string|min:3|max:100',
        'minimum_stock' => 'required|integer|min:0',
        'quantity'=>'required|integer|min:0',
        'cost_price'=>'required|numeric|min:0',
        'selling_price'=>'required|numeric|min:0',
        'barcode' => 'nullable|unique:medicines,barcode',
        
        'expiry_date'=>'required|date'
    ]);

    $medicine = new Medicine();

    $medicine->name = $request->name;
    $medicine->quantity = $request->quantity;
    $medicine->cost_price = $request->cost_price;
    $medicine->selling_price = $request->selling_price;
    $medicine->barcode = $request->barcode;
    $medicine->expiry_date = $request->expiry_date;
    $medicine->category_id = $request->category_id;
    $medicine->minimum_stock = $request->minimum_stock;

    if (empty($request->barcode)) {

        do {

            $barcode = mt_rand(100000000000, 999999999999);

        } while (Medicine::where('barcode', $barcode)->exists());

        $medicine->barcode = $barcode;

    } else {

        $medicine->barcode = $request->barcode;

}

    $medicine->save();

      // Activity Log
    ActivityHelper::log(
        'Created',
        'Medicine',
        'Added medicine: ' . $medicine->name
    );
    NotificationHelper::create(
    title: 'Medicine Added',
    message: $medicine->name . ' has been added.',
    type: 'success',
    role: 'admin',
    medicineId: $medicine->id
);

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

        ActivityHelper::log(
            'Updated',
            'Medicine',
            
            'Updated medicine: ' . $medicine->name
        );
        NotificationHelper::create(
        title: 'Medicine Updated',
        message: $medicine->name . ' information was updated.',
        type: 'info',
        role: 'admin',
        medicineId: $medicine->id
    );


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

    ActivityHelper::log(
    'Deleted',
    'Medicine',
    'Deleted medicine: ' . $medicine->name
);

    return redirect('/medicines')
        ->with('success', 'Medicine deleted successfully.');
}

 public function search(Request $request)
{
    $search = strtolower(trim($request->input('search', '')));

    $medicines = Medicine::whereRaw(
            'LOWER(name) LIKE ?',
            ["%{$search}%"]
        )
        ->where('quantity', '>', 0)
        ->select(
            'id',
            'name',
            'selling_price',
            'quantity'
        )
        ->limit(10)
        ->get();

    return response()->json($medicines);
}

public function barcode($barcode)
{
    $medicine = Medicine::where('barcode', $barcode)
        ->where('quantity', '>', 0)
        ->first();

    if (!$medicine) {
        return response()->json([
            'success' => false
        ]);
    }

    return response()->json([
        'success' => true,
        'medicine' => $medicine
    ]);
}
}
