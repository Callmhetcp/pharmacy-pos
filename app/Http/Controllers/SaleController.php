<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleDraft;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SaleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Current logged in user.
     */
    private function userId()
    {
        return Auth::id();
    }

    /**
     * Get all drafts belonging to the current user.
     */
    private function draftList()
    {
        return SaleDraft::with([
                'customer',
                'items.medicine'
            ])
            ->where('user_id', $this->userId())
            ->whereIn('status', ['open', 'held'])
            ->latest()
            ->get();
    }

    /**
     * Get the user's current open draft.
     * Create one automatically if none exists.
     */
    private function currentDraft()
    {
        $draft = SaleDraft::with([
                'customer',
                'items.medicine'
            ])
            ->where('user_id', $this->userId())
            ->where('status', 'open','held')
            ->latest()
            ->first();

        if (!$draft) {

            $draft = SaleDraft::create([
                'draft_number' => 'DRF-' . now()->format('YmdHis'),
                'user_id'      => $this->userId(),
                'status'       => 'open'
            ]);

            $draft->load([
                'customer',
                'items.medicine'
            ]);
        }

        return $draft;
    }

    /*
    |--------------------------------------------------------------------------
    | New Sale Page
    |--------------------------------------------------------------------------
    */

    public function index()
    {
       $customers = Customer::where('status', 'Active')
        ->orderBy('name')
        ->get();

        $medicines = Medicine::select(
                'id',
                'name',
                'selling_price',
                'quantity'
            )
            ->orderBy('name')
            ->get();

        $walkInCustomer = Customer::where(
            'name',
            'Walk-in Customer'
        )->first();

        $drafts = $this->draftList();

        $currentDraft = $this->currentDraft();

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
            'draft_id'        => 'required|exists:sale_drafts,id',
            'customer_id'     => 'required|exists:customers,id',
            'payment_method'  => 'required',
            'amount_paid'     => 'required|numeric|min:0',
        ]);

        $draft = SaleDraft::with([
                'customer',
                'items.medicine'
            ])
            ->where('id', $request->draft_id)
            ->where('user_id', $this->userId())
            ->where('status', 'open','held')
            ->first();

        if (!$draft) {

            return back()->with(
                'error',
                'The selected draft was not found.'
            );

        }

        if ($draft->items->isEmpty()) {

            return back()->with(
                'error',
                'The selected draft is empty.'
            );

        }

       $subTotal = $draft->items->sum('subtotal');

        $setting = Setting::first();

        $vatRate = $setting?->tax ?? 0;

        $vatAmount = ($subTotal * $vatRate) / 100;

        $totalAmount = $subTotal + $vatAmount;

        if ($request->amount_paid < $totalAmount) {

            return back()->with(
                'error',
                'Amount paid is less than the sale total.'
            );

        }

        DB::beginTransaction();

        try {

           $sale = $this->createSale(
                $request,
                $vatRate,
                $vatAmount,
                $totalAmount
            );

            $this->processSaleItems(
                $sale,
                $draft
            );

            $draft->items()->delete();

            $draft->update([
                'status' => 'completed'
            ]);

            $this->currentDraft();

            DB::commit();

            return redirect()
                ->route('sales.receipt', $sale)
                ->with(
                    'success',
                    'Sale completed successfully.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Create Sale
    |--------------------------------------------------------------------------
    */

private function createSale(
    Request $request,
    $vatRate,
    $vatAmount,
    $totalAmount
)
{
    return Sale::create([

        'receipt_number' => $this->generateReceiptNumber(),

        'customer_id' => $request->customer_id,

        'user_id' => $this->userId(),

        'sale_date' => now(),

        'vat_percent' => $vatRate,

        'vat_amount' => $vatAmount,

        'total_amount' => $totalAmount,

        'amount_paid' => $request->amount_paid,

        'balance' => $request->amount_paid - $totalAmount,

        'payment_method' => $request->payment_method,

    ]);
}

    /*
    |--------------------------------------------------------------------------
    | Generate Receipt Number
    |--------------------------------------------------------------------------
    */

    private function generateReceiptNumber()
    {
        return 'PHM'
            . now()->format('YmdHis')
            . '-'
            . strtoupper(substr(uniqid(), -5));
    }

    /*
    |--------------------------------------------------------------------------
    | Process Sale Items
    |--------------------------------------------------------------------------
    */

    private function processSaleItems(Sale $sale, SaleDraft $draft)
    {
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

            $medicine->decrement(
                'quantity',
                $item->quantity
            );

            $medicine->refresh();

            StockMovement::create([

                'medicine_id' => $medicine->id,

                'reference_number' => $sale->receipt_number,

                'type' => StockMovement::TYPE_SALE,

                'quantity_in' => 0,

                'quantity_out' => $item->quantity,

                'balance' => $medicine->quantity,

                'user_id' => $this->userId(),

            ]);
        }
    }
        /*
    |--------------------------------------------------------------------------
    | Print Receipt
    |--------------------------------------------------------------------------
    */

    public function receipt(Sale $sale)
    {
        $sale->load([
            'customer',
            'user',
            'saleItems.medicine'
        ]);

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

        $sales = Sale::with([
                'customer',
                'user'
            ])
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
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