<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Medicine;
use App\Models\StockMovement;
use Illuminate\Validation\ValidationException;
class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $purchaseReturns = PurchaseReturn::with([
                'supplier',
                'user'
            ])

            ->when($request->search, function ($query) use ($request) {

                $query->where('return_number', 'like', "%{$request->search}%")

                    ->orWhereHas('supplier', function ($q) use ($request) {

                        $q->where('name', 'like', "%{$request->search}%");

                    });

            })

            ->when($request->from, function ($query) use ($request) {

                $query->whereDate('return_date', '>=', $request->from);

            })

            ->when($request->to, function ($query) use ($request) {

                $query->whereDate('return_date', '<=', $request->to);

            })

            ->when($request->status, function ($query) use ($request) {

                $query->where('status', $request->status);

            })

            ->latest()

            ->paginate(20)

            ->withQueryString();

        return view('purchase-returns.index', compact('purchaseReturns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $purchases = Purchase::with('supplier')
            ->latest()
            ->get();

        $returnNumber = 'PR-' . date('Y') . '-' . str_pad(
            PurchaseReturn::count() + 1,
            6,
            '0',
            STR_PAD_LEFT
        );

        return view('purchase-returns.create', compact(
            'purchases',
            'returnNumber'
        ));
    }

    //load purchase items
    public function getPurchase(Purchase $purchase)
{
    $purchase->load([
        'supplier',
        'items.medicine'
    ]);

    return response()->json([
        'supplier' => $purchase->supplier,
        'items' => $purchase->items
    ]);

      dd($purchase->items->first()->medicine);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([

        'purchase_id' => 'required|exists:purchases,id',

        'supplier_id' => 'required|exists:suppliers,id',

        'return_date' => 'required|date',

        'reason' => 'required|string|max:255',

        'items' => 'required|array|min:1',

    ]);

    

    DB::beginTransaction();

    try {

        $returnNumber = 'PR-' . date('Y') . '-' . str_pad(
            PurchaseReturn::count() + 1,
            6,
            '0',
            STR_PAD_LEFT
        );

        $purchaseReturn = PurchaseReturn::create([

            'return_number' => $returnNumber,

            'purchase_id' => $request->purchase_id,

            'supplier_id' => $request->supplier_id,

            'return_date' => $request->return_date,

            'reason' => $request->reason,

            'total_amount' => 0,

            'status' => PurchaseReturn::STATUS_COMPLETED,

            'user_id' => 1,

        ]);

        $totalAmount = 0;
foreach ($request->items as $item) {

    if (empty($item['quantity']) || $item['quantity'] <= 0) {
        continue;
    }

    $medicine = Medicine::findOrFail($item['medicine_id']);

    /*
    |--------------------------------------------------------------------------
    | Get the original purchased quantity for this medicine
    |--------------------------------------------------------------------------
    */

    $purchaseItem = PurchaseItem::where('purchase_id', $request->purchase_id)
        ->where('medicine_id', $medicine->id)
        ->first();

    if (!$purchaseItem) {

        throw new \Exception(
            "The medicine {$medicine->name} was not found in this purchase."
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Total quantity already returned for this purchase and medicine
    |--------------------------------------------------------------------------
    */

    $returnedQuantity = PurchaseReturnItem::where('medicine_id', $medicine->id)
        ->whereHas('purchaseReturn', function ($query) use ($request) {

            $query->where('purchase_id', $request->purchase_id)
                  ->where('status', PurchaseReturn::STATUS_COMPLETED);

        })
        ->sum('quantity');

    /*
    |--------------------------------------------------------------------------
    | Remaining quantity available for return
    |--------------------------------------------------------------------------
    */

    $availableToReturn = $purchaseItem->quantity - $returnedQuantity;

    if ($item['quantity'] > $availableToReturn) {

        throw new \Exception(

            "You can only return {$availableToReturn} unit(s) of {$medicine->name}."

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Current stock validation
    |--------------------------------------------------------------------------
    */

    if ($item['quantity'] > $medicine->quantity) {

        throw new \Exception(

            "{$medicine->name} does not have enough stock."

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Calculate subtotal
    |--------------------------------------------------------------------------
    */

    $subtotal = $item['quantity'] * $item['cost_price'];

    PurchaseReturnItem::create([

        'purchase_return_id' => $purchaseReturn->id,

        'medicine_id' => $medicine->id,

        'quantity' => $item['quantity'],

        'cost_price' => $item['cost_price'],

        'subtotal' => $subtotal,

    ]);

    $medicine->decrement('quantity', $item['quantity']);

    StockMovement::create([

        'medicine_id'       => $medicine->id,

        'reference_number'  => $purchaseReturn->return_number,

        'type'              => StockMovement::TYPE_PURCHASE_RETURN,

        'quantity_in'       => 0,

        'quantity_out'      => $item['quantity'],

        'balance'           => $medicine->fresh()->quantity,

        'user_id'           => 1,

    ]);

    $totalAmount += $subtotal;

}

        $purchaseReturn->update([

            'total_amount' => $totalAmount,

        ]);

        DB::commit();

        return redirect()
            ->route('purchase-returns.index')
            ->with('success', 'Purchase return created successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());

    }
}

    /**
     * Display the specified resource.
     */
    public function show(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->load([
            'supplier',
            'user',
            'purchase',
            'items.medicine'
        ]);

        return view('purchase-returns.show', compact('purchaseReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseReturn $purchaseReturn)
    {
        $purchaseReturn->load([
            'supplier',
            'purchase',
            'items.medicine'
        ]);

        $purchases = Purchase::with('supplier')->get();

        return view('purchase-returns.edit', [

            'purchaseReturn' => $purchaseReturn,

            'purchases' => $purchases,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, PurchaseReturn $purchaseReturn)
{
    $request->validate([

        'purchase_id' => 'required|exists:purchases,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'return_date' => 'required|date',
        'reason' => 'required|string|max:255',
        'status' => 'required|in:Completed,Pending,Cancelled',
        'items' => 'required|array|min:1',

    ]);

    DB::transaction(function () use ($request, $purchaseReturn) {

        /*
        |--------------------------------------------------------------------------
        | Restore Previous Stock
        |--------------------------------------------------------------------------
        */

        foreach ($purchaseReturn->items as $item) {

            $medicine = Medicine::findOrFail($item->medicine_id);

            $medicine->increment('quantity', $item->quantity);

        }

        /*
        |--------------------------------------------------------------------------
        | Delete Old Return Items
        |--------------------------------------------------------------------------
        */

        $purchaseReturn->items()->delete();

        /*
        |--------------------------------------------------------------------------
        | Delete Old Stock Movements
        |--------------------------------------------------------------------------
        */

        StockMovement::where('reference_number', $purchaseReturn->return_number)
            ->where('type', StockMovement::TYPE_PURCHASE_RETURN)
            ->delete();

        $totalAmount = 0;

        /*
        |--------------------------------------------------------------------------
        | Update Purchase Return
        |--------------------------------------------------------------------------
        */

        $purchaseReturn->update([

            'purchase_id' => $request->purchase_id,
            'supplier_id' => $request->supplier_id,
            'return_date' => $request->return_date,
            'reason' => $request->reason,
            'status' => $request->status,
            'total_amount' => 0,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Save New Items
        |--------------------------------------------------------------------------
        */

        foreach ($request->items as $item) {

            if (empty($item['quantity']) || $item['quantity'] <= 0) {
                continue;
            }

            $medicine = Medicine::findOrFail($item['medicine_id']);

            if ($item['quantity'] > $medicine->quantity) {

                throw ValidationException::withMessages([

                    'items' => 'The return quantity cannot be greater than the available stock.'

                ]);

            }

            $subtotal = $item['quantity'] * $item['cost_price'];

            $purchaseReturn->items()->create([

                'medicine_id' => $item['medicine_id'],
                'quantity' => $item['quantity'],
                'cost_price' => $item['cost_price'],
                'subtotal' => $subtotal,

            ]);

            $oldStock = $medicine->quantity;

            $medicine->decrement('quantity', $item['quantity']);

            $totalAmount += $subtotal;

            StockMovement::create([

                'medicine_id' => $medicine->id,
                'reference_number' => $purchaseReturn->return_number,
                'type' => StockMovement::TYPE_PURCHASE_RETURN,
                'quantity_in' => 0,
                'quantity_out' => $item['quantity'],
                'balance' => $medicine->fresh()->quantity,
                'user_id' => 1,

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Update Total Amount
        |--------------------------------------------------------------------------
        */

        $purchaseReturn->update([

            'total_amount' => $totalAmount,

        ]);

    });

    return redirect()
        ->route('purchase-returns.index')
        ->with('success', 'Purchase Return updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(PurchaseReturn $purchaseReturn)
{
    DB::transaction(function () use ($purchaseReturn) {

        $purchaseReturn->load('items');

        /*
        |--------------------------------------------------------------------------
        | Restore Stock
        |--------------------------------------------------------------------------
        */

        foreach ($purchaseReturn->items as $item) {

            $medicine = Medicine::find($item->medicine_id);

            if ($medicine) {

                $medicine->increment('quantity', $item->quantity);

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Delete Stock Movements
        |--------------------------------------------------------------------------
        */

        StockMovement::where('reference_number', $purchaseReturn->return_number)
            ->where('type', StockMovement::TYPE_PURCHASE_RETURN)
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | Delete Return Items
        |--------------------------------------------------------------------------
        */

        $purchaseReturn->items()->delete();

        /*
        |--------------------------------------------------------------------------
        | Delete Purchase Return
        |--------------------------------------------------------------------------
        */

        $purchaseReturn->delete();

    });

    return redirect()
        ->route('purchase-returns.index')
        ->with('success', 'Purchase Return deleted successfully.');
}
}
