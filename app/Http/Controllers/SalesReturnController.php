<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Medicine;
use App\Models\SalesReturn;
use App\Models\StockMovement;

class SalesReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
{
    $salesReturns = SalesReturn::with([
        'customer',
        'sale'
    ])

    ->when($request->search, function ($query) use ($request) {

        $query->where('return_number', 'like', '%' . $request->search . '%')

            ->orWhereHas('customer', function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%');

            })

            ->orWhereHas('sale', function ($q) use ($request) {

                $q->where('receipt_number', 'like', '%' . $request->search . '%');

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

    ->paginate(10)

    ->withQueryString();

    return view('sales-returns.index', compact('salesReturns'));
}
    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    $sales = Sale::with('customer')
        ->latest()
        ->get();

    $lastReturn = SalesReturn::latest()->first();

    $nextNumber = $lastReturn
        ? ((int) substr($lastReturn->return_number, -6)) + 1
        : 1;

    $returnNumber = 'SR-' . date('Y') . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

    return view('sales-returns.create', compact(
        'sales',
        'returnNumber'
    ));
}

  public function getSale(Sale $sale)
{
    $sale->load([
        'customer',
        'items.medicine'
    ]);

    return response()->json([
        'customer' => $sale->customer,
        'items'    => $sale->items
    ]);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([

        'sale_id' => 'required|exists:sales,id',

        'customer_id' => 'required|exists:customers,id',

        'return_date' => 'required|date',

        'reason' => 'required|string|max:255',

        'items' => 'required|array|min:1',

    ]);

    DB::transaction(function () use ($request) {

        $lastReturn = SalesReturn::latest()->first();

        $nextNumber = $lastReturn
            ? ((int) substr($lastReturn->return_number, -6)) + 1
            : 1;

        $returnNumber = 'SR-' . date('Y') . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        $salesReturn = SalesReturn::create([

            'return_number' => $returnNumber,

            'sale_id' => $request->sale_id,

            'customer_id' => $request->customer_id,

            'return_date' => $request->return_date,

            'reason' => $request->reason,

            'status' => 'Completed',

            'total_amount' => 0,

        ]);

        $totalAmount = 0;

        foreach ($request->items as $item) {

            if (empty($item['quantity']) || $item['quantity'] <= 0) {
                continue;
            }

            $medicine = Medicine::findOrFail($item['medicine_id']);

            $subtotal = $item['quantity'] * $item['unit_price'];

            $salesReturn->items()->create([

                'medicine_id' => $item['medicine_id'],

                'quantity' => $item['quantity'],

                'selling_price' => $item['unit_price'],

                'subtotal' => $subtotal,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Increase Stock
            |--------------------------------------------------------------------------
            */

            $medicine->increment('quantity', $item['quantity']);

            /*
            |--------------------------------------------------------------------------
            | Stock Movement
            |--------------------------------------------------------------------------
            */

            StockMovement::create([

                'medicine_id' => $medicine->id,

                'reference_number' => $returnNumber,

                'type' => StockMovement::TYPE_SALES_RETURN,

                'quantity_in' => $item['quantity'],

                'quantity_out' => 0,

                'balance' => $medicine->fresh()->quantity,

                'user_id' => 1,

            ]);

            $totalAmount += $subtotal;

        }

        $salesReturn->update([

            'total_amount' => $totalAmount,

        ]);

    });

    return redirect()
        ->route('sales-returns.index')
        ->with('success', 'Sales Return created successfully.');

}

    /**
     * Display the specified resource.
     */
    public function show(SalesReturn $salesReturn)
        {
            $salesReturn->load([
                'customer',
                'sale',
                'items.medicine'
            ]);

            return view('sales-returns.show', compact('salesReturn'));
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesReturn $salesReturn)
        {
            $salesReturn->load([
                'customer',
                'sale',
                'items.medicine'
            ]);

            return view('sales-returns.edit', compact('salesReturn'));
        }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, SalesReturn $salesReturn)
{
    $request->validate([

        'return_date' => 'required|date',

        'reason' => 'required|string|max:255',

        'items' => 'required|array|min:1',

    ]);

    DB::transaction(function () use ($request, $salesReturn) {

        /*
        |--------------------------------------------------------------------------
        | Restore Previous Stock
        |--------------------------------------------------------------------------
        */

        $salesReturn->load('items');

        foreach ($salesReturn->items as $oldItem) {

            $medicine = Medicine::find($oldItem->medicine_id);

            if ($medicine) {

                $medicine->decrement('quantity', $oldItem->quantity);

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Delete Old Return Items
        |--------------------------------------------------------------------------
        */

        $salesReturn->items()->delete();

        /*
        |--------------------------------------------------------------------------
        | Delete Old Stock Movements
        |--------------------------------------------------------------------------
        */

        StockMovement::where('reference_number', $salesReturn->return_number)
            ->where('type', StockMovement::TYPE_SALES_RETURN)
            ->delete();

        /*
        |--------------------------------------------------------------------------
        | Update Sales Return
        |--------------------------------------------------------------------------
        */

        $salesReturn->update([

            'return_date' => $request->return_date,

            'reason' => $request->reason,

            'status' => 'Completed',

            'total_amount' => 0,

        ]);

        $totalAmount = 0;

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

            // Read unit_price from Sale Item
            $sellingPrice = $item['selling_price'];

            $subtotal = $item['quantity'] * $sellingPrice;

            $salesReturn->items()->create([

                'medicine_id'   => $item['medicine_id'],

                'quantity'      => $item['quantity'],

                // Save as selling_price
                'selling_price' => $sellingPrice,

                'subtotal'      => $subtotal,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Increase Stock
            |--------------------------------------------------------------------------
            */

            $medicine->increment('quantity', $item['quantity']);

            /*
            |--------------------------------------------------------------------------
            | Stock Movement
            |--------------------------------------------------------------------------
            */

            StockMovement::create([

                'medicine_id'      => $medicine->id,

                'reference_number' => $salesReturn->return_number,

                'type'             => StockMovement::TYPE_SALES_RETURN,

                'quantity_in'      => $item['quantity'],

                'quantity_out'     => 0,

                'balance'          => $medicine->fresh()->quantity,

                'user_id'          => 1,

            ]);

            $totalAmount += $subtotal;

        }

        /*
        |--------------------------------------------------------------------------
        | Update Total
        |--------------------------------------------------------------------------
        */

        $salesReturn->update([

            'total_amount' => $totalAmount,

        ]);

    });

    return redirect()
        ->route('sales-returns.index')
        ->with('success', 'Sales Return updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(SalesReturn $salesReturn)
{
    DB::transaction(function () use ($salesReturn) {

        $salesReturn->load('items');

        /*
        |--------------------------------------------------------------------------
        | Reverse Stock
        |--------------------------------------------------------------------------
        */

        foreach ($salesReturn->items as $item) {

            $medicine = Medicine::find($item->medicine_id);

            if ($medicine) {

                $medicine->decrement('quantity', $item->quantity);

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Delete Stock Movement
        |--------------------------------------------------------------------------
        */

        StockMovement::where(
            'reference_number',
            $salesReturn->return_number
        )->where(
            'type',
            StockMovement::TYPE_SALES_RETURN
        )->delete();

        /*
        |--------------------------------------------------------------------------
        | Delete Return Items
        |--------------------------------------------------------------------------
        */

        $salesReturn->items()->delete();

        /*
        |--------------------------------------------------------------------------
        | Delete Sales Return
        |--------------------------------------------------------------------------
        */

        $salesReturn->delete();

    });

    return redirect()
        ->route('sales-returns.index')
        ->with('success', 'Sales Return deleted successfully.');
}
}
