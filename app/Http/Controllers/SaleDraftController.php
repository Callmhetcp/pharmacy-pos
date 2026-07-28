<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Medicine;
use App\Models\SaleDraft;
use App\Models\SaleDraftItem;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class SaleDraftController extends Controller
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
        ->orderByRaw("
            CASE
                WHEN status = 'held' THEN 1
                WHEN status = 'open' THEN 2
            END
        ")
        ->latest()
        ->get();
}

   public function create()
{
    $draft = SaleDraft::create([

        'draft_number' => 'DRF-' . now()->format('YmdHis'),

        'user_id' => $this->userId(),

        'status' => 'open'

    ]);


   $drafts = $this->draftList();


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
   $drafts = $this->draftList();



    // Find current open draft
    $currentDraft = SaleDraft::with('items.medicine')
        ->where('status', 'open')
        ->where('user_id',$this->userId() )
        ->latest()
        ->first();



    // Create one if no open draft exists
    if(!$currentDraft){

        $currentDraft = SaleDraft::create([

            'draft_number' => 'DRF-' . now()->format('YmdHis'),

            'user_id' => $this->userId(),

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
    if ($draft->user_id != $this->userId()) {
        abort(403);
    }
    if ($draft->status === 'held') {
    $draft->update([
        'status' => 'open'
    ]);
}
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
   $drafts = $this->draftList();

    return response()->json([

        'success' => true,

        'items' => $draft->items,

        'total' => $draft->items->sum('subtotal'),

        'drafts' => $drafts

    ]);
}

public function removeItem(SaleDraftItem $item)
{
    if ($item->draft->user_id != $this->userId()) {
        abort(403);
    }

    $draft = $item->draft;

    if ($draft->status === 'held') {
    $draft->update([
        'status' => 'open'
    ]);
}

    $item->delete();

    $draft->load('items.medicine');

    $drafts = $this->draftList();

    return response()->json([
        'success' => true,
        'items'   => $draft->items,
        'total'   => $draft->items->sum('subtotal'),
        'drafts'  => $drafts
    ]);
}


public function show(SaleDraft $draft)
{
    if ($draft->user_id != $this->userId()) {
            abort(403);
        }

    // If the draft was held, reopen it
   


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
    if ($draft->user_id != $this->userId()) {
        abort(403);
    }
    // Delete all items
    $draft->items()->delete();

    // Delete the draft
    $draft->delete();

    // Get remaining drafts
    $drafts = $this->draftList();

    return response()->json([
        'success' => true,
        'drafts'  => $drafts
    ]);
}
public function updateCustomer(Request $request, SaleDraft $draft)
{
    if ($draft->user_id != $this->userId()) {
        abort(403);
    }

    
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
    ])
    ->where('user_id', $this->userId())
    ->findOrFail($id);

    if ($draft->status === 'held') {
    return response()->json([
        'success' => false,
        'message' => 'Resume the sale before printing.'
    ], 403);
}

    return view('sales.draft_receipt', compact('draft'));
}

public function updateQuantity(Request $request, SaleDraftItem $item)
{
    if ($item->draft->user_id != $this->userId()) {
        abort(403);
    }

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

            if ($draft->status === 'held') {
                $draft->update([
                    'status' => 'open'
                ]);
            }

            $item->delete();

            $draft->load('items.medicine');

            $drafts = $this->draftList();

            return response()->json([
                'success' => true,
                'items'   => $draft->items,
                'drafts'  => $drafts,
                'total'   => $draft->items->sum('subtotal')
            ]);
        }
    }

    $item->subtotal = $item->quantity * $item->unit_price;
    $item->save();

    $draft = $item->draft;

    
    $draft->load('items.medicine');

    $drafts = $this->draftList();

    return response()->json([
        'success' => true,
        'items'   => $draft->items,
        'drafts'  => $drafts,
        'total'   => $draft->items->sum('subtotal')
    ]);
}

public function clear(SaleDraft $draft)
{
    if ($draft->user_id != $this->userId()) {
        abort(403);
    }
    if ($draft->status === 'held') {
    $draft->update([
        'status' => 'open'
    ]);
}

    $draft->items()->delete();

    $draft->load('items.medicine');

    $drafts = $this->draftList();

    return response()->json([
        'success' => true,
        'items'   => [],
        'total'   => 0,
        'drafts'  => $drafts
    ]);
}

public function hold(SaleDraft $draft)
{
    if ($draft->user_id != $this->userId()) {
        abort(403);
    }
    $draft->update([
        'status' => 'held'
    ]);

    // Find another open draft
    $active = SaleDraft::with('items', 'customer')
         ->where('user_id', $this->userId())
        ->where('status', 'open')
        ->latest()
        ->first();

    // If none exists, create one
    if (!$active) {

        $active = SaleDraft::create([
            'draft_number' => 'DRF-' . now()->format('YmdHis'),
            'user_id' => $this->userId(),
            'status' => 'open'
        ]);

        // VERY IMPORTANT
        $active->load('items', 'customer');
    }

    $drafts = $this->draftList();

    return response()->json([
        'success' => true,
        'drafts'  => $drafts,
        'active'  => $active
    ]);
}

}
