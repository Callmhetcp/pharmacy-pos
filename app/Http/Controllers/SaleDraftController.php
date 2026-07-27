<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Medicine;
use App\Models\SaleDraft;
use App\Models\SaleDraftItem;
use App\Models\Customer;
use Illuminate\Http\Request;


class SaleDraftController extends Controller
{
   public function create()
{
    $draft = SaleDraft::create([

        'draft_number' => 'DRF-' . now()->format('YmdHis'),

        'user_id' => 1,

        'status' => 'open'

    ]);


    $drafts = SaleDraft::with('items')
        ->where('status', 'open')
        ->latest()
        ->get();


    return response()->json([

        'success' => true,

        'draft' => $draft,

        'drafts' => $drafts

    ]);
}

  public function index()
{
    $customers = Customer::all();

    $medicines = Medicine::all();

    $walkInCustomer = Customer::where('name', 'Walk-in Customer')->first();

    $cart = Session::get('cart', []);


    // Load both open and held drafts
    $drafts = SaleDraft::with([
            'customer',
            'items.medicine'
        ])
        ->whereIn('status', ['open', 'held'])
        ->orderByRaw("
            CASE
                WHEN status='held' THEN 1
                WHEN status='open' THEN 2
            END
        ")
        ->latest()
        ->get();



    // Find current open draft
    $currentDraft = SaleDraft::with('items.medicine')
        ->where('status', 'open')
        ->where('user_id', 1)
        ->latest()
        ->first();



    // Create one if no open draft exists
    if(!$currentDraft){

        $currentDraft = SaleDraft::create([

            'draft_number' => 'DRF-' . now()->format('YmdHis'),

            'user_id' => 1,

            'status' => 'open'

        ]);


        $currentDraft->load('items.medicine');

    }



    return view('sales.index', compact(

        'customers',

        'medicines',

        'cart',

        'walkInCustomer',

        'drafts',

        'currentDraft'

    ));

}
public function addItem(Request $request, SaleDraft $draft)
{
    $request->validate([
        'medicine_id' => 'required|exists:medicines,id',
        'quantity'    => 'required|integer|min:1',
    ]);

    $medicine = Medicine::findOrFail($request->medicine_id);

    if ($medicine->quantity < $request->quantity) {
        return response()->json([
            'success' => false,
            'message' => 'Insufficient stock.'
        ]);
    }

    $item = SaleDraftItem::where('draft_id', $draft->id)
        ->where('medicine_id', $medicine->id)
        ->first();

    if ($item) {

        $item->quantity += $request->quantity;
        $item->subtotal = $item->quantity * $item->unit_price;
        $item->save();

    } else {

        SaleDraftItem::create([
            'draft_id'    => $draft->id,
            'medicine_id' => $medicine->id,
            'quantity'    => $request->quantity,
            'unit_price'  => $medicine->selling_price,
            'subtotal'    => $medicine->selling_price * $request->quantity,
        ]);
    }

     // Reload current draft items with medicine details
    $draft->load('items.medicine');



    // Reload relationships so the counts are updated
    $drafts = SaleDraft::with('items.medicine')
    ->where('status','open')
    ->latest()
    ->get();


    return response()->json([

        'success' => true,

        'items' => $draft->items,

        'total' => $draft->items->sum('subtotal'),

        'drafts' => $drafts

    ]);
}

public function removeItem(SaleDraftItem $item)
{
    $draft = $item->draft;

    $item->delete();

    $draft->load('items.medicine');

    $drafts = SaleDraft::with('items')
        ->where('status', 'open')
        ->latest()
        ->get();

    return response()->json([
        'success' => true,
        'items'   => $draft->items,
        'total'   => $draft->items->sum('subtotal'),
        'drafts'  => $drafts
    ]);
}


public function show(SaleDraft $draft)
{

    // If the draft was held, reopen it
    if($draft->status === 'held'){

        $draft->update([
            'status'=>'open'
        ]);

    }


    $draft->load([
        'items.medicine',
        'customer'
    ]);



    return response()->json([

        'success'=>true,

        'draft'=>$draft,

        'items'=>$draft->items

    ]);

}
public function destroy(SaleDraft $draft)
{
    // Delete all items
    $draft->items()->delete();

    // Delete the draft
    $draft->delete();

    // Get remaining drafts
    $drafts = SaleDraft::with('items')
        ->where('status', 'open')
        ->latest()
        ->get();

    return response()->json([
        'success' => true,
        'drafts'  => $drafts
    ]);
}
public function updateCustomer(Request $request, SaleDraft $draft)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id'
    ]);


    $draft->update([

        'customer_id' => $request->customer_id

    ]);


    return response()->json([

        'success'=>true,

        'customer'=>$draft->customer

    ]);
}

public function printDraft($id)
{
    $draft = SaleDraft::with([
        'customer',
        'items.medicine'
    ])->findOrFail($id);

    return view('sales.draft_receipt', compact('draft'));
}

public function updateQuantity(Request $request, SaleDraftItem $item)
{
    $request->validate([
        'action' => 'required|in:increase,decrease',
    ]);

    if ($request->action == 'increase') {

        if ($item->medicine->quantity <= $item->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock.'
            ]);
        }

        $item->quantity++;

    } else {

        $item->quantity--;

        if ($item->quantity <= 0) {
            $draft = $item->draft;

            $item->delete();

            $draft->load('items.medicine');

            $drafts = SaleDraft::with('items')
                ->where('status', 'open')
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'items' => $draft->items,
                'drafts' => $drafts,
                'total' => $draft->items->sum('subtotal')
            ]);
        }
    }

    $item->subtotal = $item->quantity * $item->unit_price;
    $item->save();

    $draft = $item->draft;
    $draft->load('items.medicine');

    $drafts = SaleDraft::with('items')
        ->where('status', 'open')
        ->latest()
        ->get();

    return response()->json([
        'success' => true,
        'items' => $draft->items,
        'drafts' => $drafts,
        'total' => $draft->items->sum('subtotal')
    ]);
}

public function clear(SaleDraft $draft)
{
    $draft->items()->delete();

    $draft->load('items.medicine');

    $drafts = SaleDraft::with('items')
        ->where('status', 'open')
        ->latest()
        ->get();

    return response()->json([

        'success' => true,

        'items' => [],

        'total' => 0,

        'drafts' => $drafts

    ]);
}

public function hold(SaleDraft $draft)
{
    $draft->update([
        'status' => 'held'
    ]);

    // Find another open draft
    $active = SaleDraft::with('items', 'customer')
        ->where('status', 'open')
        ->latest()
        ->first();

    // If none exists, create one
    if (!$active) {

        $active = SaleDraft::create([
            'draft_number' => 'DRF-' . now()->format('YmdHis'),
            'user_id' => 1,
            'status' => 'open'
        ]);

        // VERY IMPORTANT
        $active->load('items', 'customer');
    }

    $drafts = SaleDraft::with('items', 'customer')
        ->whereIn('status', ['open','held'])
        ->latest()
        ->get();

    return response()->json([
        'success' => true,
        'drafts'  => $drafts,
        'active'  => $active
    ]);
}

}
