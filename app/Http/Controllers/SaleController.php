<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleDraft;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SaleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | New Sale Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $customers = Customer::orderBy('name')->get();

        $medicines = Medicine::orderBy('name')->get();

        $walkInCustomer = Customer::where(
            'name',
            'Walk-in Customer'
        )->first();

        $drafts = SaleDraft::with([
                'customer',
                'items'
            ])
            ->where('status', 'open')
            ->latest()
            ->get();

        $currentDraft = SaleDraft::firstOrCreate(

            [
                'status' => 'open',
                'user_id' => 1
            ],

            [
                'draft_number' => 'DRF-' . now()->format('YmdHis')
            ]

        );

        return view('sales.index', compact(

            'customers',

            'medicines',

            'walkInCustomer',

            'drafts',

            'currentDraft'

        ));
    }

    /*
|--------------------------------------------------------------------------
| Store Sale
|--------------------------------------------------------------------------
*/

public function store(Request $request)
{
    $request->validate([

        'draft_id' => 'required|exists:sale_drafts,id',

        'customer_id' => 'required|exists:customers,id',

        'payment_method' => 'required',

        'amount_paid' => 'required|numeric|min:0',

    ]);

    $draft = SaleDraft::with([
            'customer',
            'items.medicine'
        ])
        ->where('id', $request->draft_id)
        ->where('user_id', 1)
        ->where('status', 'open')
        ->first();

    if (!$draft) {

        return back()->with(
            'error',
            'Selected draft was not found.'
        );

    }

    if ($draft->items->isEmpty()) {

        return back()->with(
            'error',
            'The selected draft is empty.'
        );

    }

    $totalAmount = $draft->items->sum('subtotal');

    if ($request->amount_paid < $totalAmount) {

        return back()->with(
            'error',
            'Amount paid is less than the total sale amount.'
        );

    }

    $sale = null;

    DB::transaction(function () use (
        $request,
        $draft,
        $totalAmount,
        &$sale
    ) {

        /*
        |--------------------------------------------------------------------------
        | Generate Receipt Number
        |--------------------------------------------------------------------------
        */

        $receiptNumber = 'PHM'
            . now()->format('Ymd')
            . '-'
            . str_pad(
                Sale::count() + 1,
                5,
                '0',
                STR_PAD_LEFT
            );

        /*
        |--------------------------------------------------------------------------
        | Create Sale
        |--------------------------------------------------------------------------
        */

        $sale = Sale::create([

            'receipt_number' => $receiptNumber,

            'customer_id' => $request->customer_id,

            'user_id' => 1,

            'sale_date' => now(),

            'total_amount' => $totalAmount,

            'amount_paid' => $request->amount_paid,

            'balance' => $request->amount_paid - $totalAmount,

            'payment_method' => $request->payment_method,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Save Sale Items
        |--------------------------------------------------------------------------
        */

        foreach ($draft->items as $item) {

            $medicine = $item->medicine;

            if ($medicine->quantity < $item->quantity) {

                throw new \Exception(
                    "{$medicine->name} does not have enough stock."
                );

            }

            SaleItem::create([

                'sale_id' => $sale->id,

                'medicine_id' => $medicine->id,

                'quantity' => $item->quantity,

                'unit_price' => $item->unit_price,

                'subtotal' => $item->subtotal,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Reduce Stock
            |--------------------------------------------------------------------------
            */

            $medicine->decrement(
                'quantity',
                $item->quantity
            );

            $medicine->refresh();

            /*
            |--------------------------------------------------------------------------
            | Record Stock Movement
            |--------------------------------------------------------------------------
            */

            StockMovement::create([

                'medicine_id' => $medicine->id,

                'reference_number' => $receiptNumber,

                'type' => StockMovement::TYPE_SALE,

                'quantity_in' => 0,

                'quantity_out' => $item->quantity,

                'balance' => $medicine->quantity,

                'user_id' => 1,

            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Complete Draft
        |--------------------------------------------------------------------------
        */

        $draft->items()->delete();

        $draft->update([

            'status' => 'completed',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Create New Draft
        |--------------------------------------------------------------------------
        */

        SaleDraft::create([

            'draft_number' => 'DRF-'
                . now()->format('YmdHis'),

            'user_id' => 1,

            'status' => 'open',

        ]);

    });

    if (!$sale) {
    return back()->with('error', 'Sale could not be completed.');
}

return redirect()
    ->route('sales.receipt', $sale->id)
    ->with('success', 'Sale completed successfully.');

    
}

/*
|--------------------------------------------------------------------------
| Print Receipt
|--------------------------------------------------------------------------
*/

public function receipt($id)
{
    $sale = Sale::with([

        'customer',

        'user',

        'saleItems.medicine'

    ])->findOrFail($id);

    return view(

        'sales.receipt',

        compact('sale')

    );
}
/*
|--------------------------------------------------------------------------
| Sales History
|--------------------------------------------------------------------------
*/

public function history(Request $request)
{
    $search = $request->search;

    $sales = Sale::when($search, function ($query) use ($search) {

            $query->where(
                    'receipt_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'payment_method',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas('customer', function ($customer) use ($search) {

                    $customer->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );

                });

        })

        ->latest()

        ->paginate(20);

    return view(

        'sales.history',

        compact('sales')

    );
}

/*
|--------------------------------------------------------------------------
| Sale Details
|--------------------------------------------------------------------------
*/

public function show(Sale $sale)
{
    $sale->load([

        'customer',

        'user',

        'saleItems.medicine'

    ]);

    return view(

        'sales.show',

        compact('sale')

    );
}

/*
|--------------------------------------------------------------------------
| Walk-in Customer
|--------------------------------------------------------------------------
*/

public function customerType(Request $request)
{
    if ($request->has('walkin')) {

        $walkInCustomer = Customer::where(

            'name',

            'Walk-in Customer'

        )->first();

        if ($walkInCustomer) {

            Session::put(

                'customer_id',

                $walkInCustomer->id

            );

        }

    } else {

        Session::forget('customer_id');

    }

    return back();
}
}