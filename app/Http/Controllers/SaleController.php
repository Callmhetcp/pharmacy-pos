<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;

class SaleController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $medicines = Medicine::all();
        $walkInCustomer = Customer::where('name', 'Walk-in Customer')->first();
        $cart = Session::get('cart', []);

        return view('sales.index', compact(
            'customers',
            'medicines',
            'cart',
            'walkInCustomer'
        ));
    }

    public function addToCart(Request $request)
    {
        $medicine = Medicine::find($request->medicine_id);

        if (!$medicine) {
            return redirect('/sales');
        }

        // Save selected customer
        if ($request->filled('customer_id')) {
            Session::put('customer_id', $request->customer_id);
        }

        $cart = Session::get('cart', []);

        $cart[] = [
            'id' => $medicine->id,
            'name' => $medicine->name,
            'price' => $medicine->selling_price,
            'quantity' => $request->quantity,
            'subtotal' => $medicine->selling_price * $request->quantity,
        ];
        Session::put('cart', $cart);

        if ($request->ajax()) {

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'grandTotal' => collect($cart)->sum('subtotal')
            ]);

        }

        return redirect('/sales');
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);

        if (count($cart) == 0) {
            return redirect('/sales');
        }

        $total = collect($cart)->sum('subtotal');

        if ($request->amount_paid < $total) {
            return back()
                ->with('error', 'Amount received is less than the total sale amount.')
                ->withInput();
        }

        $customerId = $request->customer_id;

        if (!$customerId) {
            return redirect('/sales')
                ->with('error', 'Please select a customer first.');
        }

        $receiptNumber = 'PHM' . now()->format('Ymd') . '-' . str_pad(
            Sale::count() + 1,
            5,
            '0',
            STR_PAD_LEFT
        );

        $sale = Sale::create([
            'receipt_number' => $receiptNumber,
            'customer_id' => $customerId,
            'user_id' => 1,
            'total_amount' => $total,
            'sale_date' => now(),
            'amount_paid' => $request->amount_paid,
            'balance' => $request->amount_paid - $total,
            'payment_method' => $request->payment_method,
        ]);

        foreach ($cart as $item) {

            $medicine = Medicine::find($item['id']);

            if ($medicine->quantity < $item['quantity']) {
                return back()->with(
                    'error',
                    "{$medicine->name} does not have enough stock."
                );
            }

            SaleItem::create([
                'sale_id' => $sale->id,
                'medicine_id' => $item['id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);

            // Reduce stock
            $medicine->decrement('quantity', $item['quantity']);
        }

        Session::forget('cart');
        Session::forget('customer_id');

        return redirect()->route('sales.receipt', $sale->id);
    }

    public function clearCart()
    {
        Session::forget('cart');

        return redirect('/sales');
    }

    public function receipt($id)
    {
        $sale = Sale::with([
            'customer',
            'user',
            'saleItems.medicine'
        ])->findOrFail($id);

        return view('sales.receipt', compact('sale'));
    }

    public function history(Request $request)
    {
        $search = $request->search;

        $sales = Sale::when($search, function ($query) use ($search) {

            $query->where('receipt_number', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%");

        })
        ->latest()
        ->get();

        return view('sales.history', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(
            'saleItems.medicine',
            'customer',
            'user'
        );

        return view('sales.show', compact('sale'));
    }

   public function removeCart(Request $request, $id)
{
    $cart = Session::get('cart', []);

    foreach ($cart as $key => $item) {

        if ($item['id'] == $id) {
            unset($cart[$key]);
            break;
        }

    }

    $cart = array_values($cart);

    Session::put('cart', $cart);

    if ($request->ajax()) {

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'grandTotal' => collect($cart)->sum('subtotal')
        ]);

    }

    return back()->with('success', 'Item removed from cart.');
}

    public function customerType(Request $request)
    {
        if ($request->has('walkin')) {

            $walkInCustomer = Customer::where('name', 'Walk-in Customer')->first();

            if ($walkInCustomer) {
                Session::put('customer_id', $walkInCustomer->id);
            }

        } else {

            Session::forget('customer_id');

        }

        return back();
    }
}