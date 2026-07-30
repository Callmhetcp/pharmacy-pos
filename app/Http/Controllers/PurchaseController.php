<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\ActivityHelper;
use App\Models\Notification;
use App\Helpers\NotificationHelper;

class PurchaseController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Create Purchase
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $suppliers = Supplier::where('status', 'Active')
            ->orderBy('company')
            ->get();

        $medicines = Medicine::orderBy('name')->get();

        $purchaseNumber = $this->generatePurchaseNumber();

        

        return view('purchases.create', compact(
            'suppliers',
            'medicines',
            'purchaseNumber'
        ));
    }

    /*
|--------------------------------------------------------------------------
| Store Purchase
|--------------------------------------------------------------------------
*/

public function store(Request $request)
{
    $request->validate([

        'supplier_id'        => 'required|exists:suppliers,id',
        'purchase_date'      => 'required|date',
        'invoice_number'     => 'nullable|string|max:255',

        'medicine_id'        => 'required|array',
        'medicine_id.*'      => 'required|exists:medicines,id',

        'batch_number'       => 'required|array',
        'batch_number.*'     => 'required|string|max:255',

        'expiry_date'        => 'required|array',
        'expiry_date.*'      => 'required|date',

        'quantity'           => 'required|array',
        'quantity.*'         => 'required|integer|min:1',

        'cost_price'         => 'required|array',
        'cost_price.*'       => 'required|numeric|min:0',

        'selling_price'      => 'required|array',
        'selling_price.*'    => 'required|numeric|min:0',

        'amount_paid'        => 'required|numeric|min:0',

        'payment_method'     => 'required|string',

    ]);

    DB::transaction(function () use ($request) {

        // =====================================================
        // CREATE PURCHASE
        // =====================================================

        $purchase = Purchase::create([

            'purchase_number' => $this->generatePurchaseNumber(),

            'supplier_id'     => $request->supplier_id,

            'invoice_number'  => $request->invoice_number,

            'purchase_date'   => $request->purchase_date,

            'grand_total'     => 0,

            'amount_paid'     => 0,

            'balance'         => 0,

            'payment_method'  => $request->payment_method,

            'payment_status'  => 'Unpaid',

            'user_id'         => Auth::id(),

        ]);

        $grandTotal = 0;

        // =====================================================
        // SAVE PURCHASE ITEMS
        // =====================================================

        foreach ($request->medicine_id as $index => $medicineId) {

            $qty      = $request->quantity[$index];

            $cost     = $request->cost_price[$index];

            $selling  = $request->selling_price[$index];

            $subtotal = $qty * $cost;

            PurchaseItem::create([

                'purchase_id'   => $purchase->id,

                'medicine_id'   => $medicineId,

                'batch_number'  => $request->batch_number[$index],

                'expiry_date'   => $request->expiry_date[$index],

                'quantity'      => $qty,

                'cost_price'    => $cost,

                'selling_price' => $selling,

                'subtotal'      => $subtotal,

            ]);

            $medicine = Medicine::findOrFail($medicineId);

            $medicine->quantity += $qty;

            $medicine->cost_price = $cost;

            $medicine->selling_price = $selling;

            $medicine->expiry_date = $request->expiry_date[$index];

            $medicine->save();

            // Remove low stock notification

            if ($medicine->quantity > $medicine->minimum_stock) {

                Notification::where('medicine_id', $medicine->id)
                    ->where('title', 'Low Stock')
                    ->delete();

            }

            // Stock movement

            StockMovement::create([

                'medicine_id'      => $medicine->id,

                'reference_number' => $purchase->purchase_number,

                'type'             => StockMovement::TYPE_PURCHASE,

                'quantity_in'      => $qty,

                'quantity_out'     => 0,

                'balance'          => $medicine->quantity,

                'user_id'          => Auth::id(),

            ]);

            $grandTotal += $subtotal;

        }

        // =====================================================
        // PAYMENT CALCULATION
        // =====================================================

        $amountPaid = $request->amount_paid;

        $balance = $grandTotal - $amountPaid;

        if ($amountPaid <= 0) {

            $paymentStatus = 'Unpaid';

        } elseif ($balance <= 0) {

            $paymentStatus = 'Paid';

            $balance = 0;

        } else {

            $paymentStatus = 'Partial';

        }

        // =====================================================
        // UPDATE PURCHASE TOTALS
        // =====================================================

        $purchase->update([

            'grand_total'    => $grandTotal,

            'amount_paid'    => $amountPaid,

            'balance'        => $balance,

            'payment_method' => $request->payment_method,

            'payment_status' => $paymentStatus,

        ]);

        // =====================================================
        // NOTIFICATION
        // =====================================================

        NotificationHelper::create(

            title: 'Purchase Completed',

            message: 'Purchase ' . $purchase->purchase_number . ' has been recorded.',

            type: 'success',

            role: 'Storekeeper'

        );

        // =====================================================
        // ACTIVITY LOG
        // =====================================================

        ActivityHelper::log(

            'Created',

            'Purchase',

            'Created purchase: ' . $purchase->purchase_number

        );

    });

    return redirect()
        ->route('purchases.index')
        ->with('success', 'Purchase saved successfully.');
}
/*
|--------------------------------------------------------------------------
| Generate Purchase Number
|--------------------------------------------------------------------------
*/

private function generatePurchaseNumber()
{
    $year = date('Y');

    $lastPurchase = Purchase::where('purchase_number', 'like', "HP-PUR{$year}-%")
        ->orderByDesc('purchase_number')
        ->first();

    if ($lastPurchase) {

        $lastNumber = (int) substr($lastPurchase->purchase_number, -6);

        $nextNumber = $lastNumber + 1;

    } else {

        $nextNumber = 1;

    }

    return 'HP-PUR'
        . $year
        . '-'
        . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
}


/*
|--------------------------------------------------------------------------
| Purchase List
|--------------------------------------------------------------------------
*/

public function index(Request $request)
{
    $search = $request->search;

    $purchases = Purchase::with([
            'supplier',
            'user'
        ])
        ->withCount('purchaseItems')
        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($supplier) use ($search) {

                      $supplier->where('company', 'like', "%{$search}%");

                  });

            });

        })
        ->latest()
        ->paginate(20)
        ->withQueryString();

    if ($request->ajax()) {

        return view('purchases.table', compact('purchases'))->render();

    }

    return view('purchases.index', compact(
        'purchases',
        'search'
    ));
}

/*
|--------------------------------------------------------------------------
| Show Purchase Details
|--------------------------------------------------------------------------
*/

public function show(Purchase $purchase)
{
    $purchase->load([
        'supplier',
        'user',
        'items.medicine'
    ]);

    return view('purchases.show', compact('purchase'));
}


/*
|--------------------------------------------------------------------------
| Print Purchase Receipt
|--------------------------------------------------------------------------
*/

public function receipt(Purchase $purchase)
{
    $purchase->load([
        'supplier',
        'user',
        'items.medicine'
    ]);

    return view('purchases.receipt', compact('purchase'));
}


/*
|--------------------------------------------------------------------------
| Edit Purchase
|--------------------------------------------------------------------------
*/

public function edit(Purchase $purchase)
{
    $purchase->load([
        'items.medicine'
    ]);

    $suppliers = Supplier::where('status', 'Active')
        ->orderBy('name')
        ->get();

    $medicines = Medicine::orderBy('name')->get();

    return view('purchases.edit', compact(
        'purchase',
        'suppliers',
        'medicines'
    ));
}
/*
|--------------------------------------------------------------------------
| Update Purchase
|--------------------------------------------------------------------------
*/

public function update(Request $request, Purchase $purchase)
{
    $request->validate([

        'purchase_number' => 'required',

        'purchase_date' => 'required|date',

        'supplier_id' => 'required|exists:suppliers,id',

        'invoice_number' => 'nullable|string|max:255',

        'medicine_id' => 'required|array',
        'medicine_id.*' => 'required|exists:medicines,id',

        'batch_number' => 'required|array',
        'batch_number.*' => 'required|string|max:255',

        'expiry_date' => 'required|array',
        'expiry_date.*' => 'required|date',

        'quantity' => 'required|array',
        'quantity.*' => 'required|integer|min:1',

        'cost_price' => 'required|array',
        'cost_price.*' => 'required|numeric|min:0',

        'selling_price' => 'required|array',
        'selling_price.*' => 'required|numeric|min:0',

        'grand_total' => 'required|numeric|min:0',

    ]);

    DB::transaction(function () use ($request, $purchase) {

        /*
        |--------------------------------------------------------------------------
        | Restore Previous Stock
        |--------------------------------------------------------------------------
        */

        foreach ($purchase->purchaseItems as $oldItem) {

            $medicine = Medicine::find($oldItem->medicine_id);

            if ($medicine) {

                $medicine->quantity -= $oldItem->quantity;

                if ($medicine->quantity < 0) {
                    $medicine->quantity = 0;
                }

                $medicine->save();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Old Purchase Items
        |--------------------------------------------------------------------------
        */

        $purchase->purchaseItems()->delete();

        /*
        |--------------------------------------------------------------------------
        | Update Purchase
        |--------------------------------------------------------------------------
        */

        $purchase->update([

            'purchase_number' => $request->purchase_number,

            'purchase_date' => $request->purchase_date,

            'supplier_id' => $request->supplier_id,

            'invoice_number' => $request->invoice_number,

            'grand_total' => $request->grand_total,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Save New Purchase Items
        |--------------------------------------------------------------------------
        */

        foreach ($request->medicine_id as $index => $medicineId) {

            $qty = $request->quantity[$index];

            $cost = $request->cost_price[$index];

            $selling = $request->selling_price[$index];

            $subtotal = $qty * $cost;

            PurchaseItem::create([

                'purchase_id' => $purchase->id,

                'medicine_id' => $medicineId,

                'batch_number' => $request->batch_number[$index],

                'expiry_date' => $request->expiry_date[$index],

                'quantity' => $qty,

                'cost_price' => $cost,

                'selling_price' => $selling,

                'subtotal' => $subtotal,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Medicine Stock
            |--------------------------------------------------------------------------
            */

            $medicine = Medicine::findOrFail($medicineId);

            $medicine->quantity += $qty;

            $medicine->cost_price = $cost;

            $medicine->selling_price = $selling;

            $medicine->expiry_date = $request->expiry_date[$index];

            $medicine->save();

            /*
            |--------------------------------------------------------------------------
            | Record Stock Movement
            |--------------------------------------------------------------------------
            */

            StockMovement::create([

                'medicine_id' => $medicine->id,

                'reference_number' => $purchase->purchase_number,

                'type' => StockMovement::TYPE_PURCHASE,

                'quantity_in' => $qty,

                'quantity_out' => 0,

                'balance' => $medicine->quantity,

               'user_id' => Auth::id(),

            ]);
        }

    });

        ActivityHelper::log(
        'Updated',
        'Purchase',
        'Updated purchase: ' . $purchase->purchase_number
    );
    NotificationHelper::create(
    title: 'Purchase Completed',
    message: 'Purchase ' . $purchase->purchase_number . ' has been recorded.',
    type: 'success',
    role: 'Storekeeper'
);

    return redirect()
        ->route('purchases.index')
        ->with('success', 'Purchase updated successfully.');
}

/*
|--------------------------------------------------------------------------
| Delete Purchase
|--------------------------------------------------------------------------
*/

public function destroy(Purchase $purchase)
{
    DB::transaction(function () use ($purchase) {

        /*
        |--------------------------------------------------------------------------
        | Restore Medicine Stock
        |--------------------------------------------------------------------------
        */

        foreach ($purchase->purchaseItems as $item) {

            $medicine = Medicine::find($item->medicine_id);

            if ($medicine) {

                $medicine->quantity -= $item->quantity;

                if ($medicine->quantity < 0) {
                    $medicine->quantity = 0;
                }

                $medicine->save();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Stock Movements
        |--------------------------------------------------------------------------
        */

        StockMovement::where(
            'reference_number',
            $purchase->purchase_number
        )->delete();


       /*
|--------------------------------------------------------------------------
| Delete Purchase Items
|--------------------------------------------------------------------------
*/

$purchase->purchaseItems()->delete();


// Activity Log

ActivityHelper::log(
    'Deleted',
    'Purchase',
    'Deleted purchase: ' . $purchase->purchase_number
);


$purchase->delete();

    });

    return redirect()
        ->route('purchase.index')
        ->with('success', 'Purchase deleted successfully.');
}
}
