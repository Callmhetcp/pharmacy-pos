<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
  public function index(Request $request)
{
    $medicines = Medicine::with('category')
        ->when($request->search, function ($query) use ($request) {

            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhereHas('category', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%');
                });

        })
        ->latest()
        ->paginate(20);

    $totalMedicines = Medicine::count();
    $totalStock = Medicine::sum('quantity');
    $lowStock = Medicine::whereColumn('quantity', '<=', 'minimum_stock')->count();
    $expired = Medicine::whereDate('expiry_date', '<', now())->count();
    $expiringSoon = Medicine::whereBetween('expiry_date', [now(), now()->addDays(30)])->count();

    if ($request->ajax()) {
        return view('inventory.table', compact('medicines'))->render();
    }

    return view('inventory.index', compact(
        'medicines',
        'totalMedicines',
        'totalStock',
        'lowStock',
        'expired',
        'expiringSoon'
    ));
}

//ledger method
public function ledger(Request $request)
{
    $ledger = StockMovement::with([
            'medicine',
            'user'
        ])

        // Search medicine, reference number, user
        ->when($request->search, function ($query) use ($request) {

            $query->where(function ($q) use ($request) {

                $q->where('reference_number', 'like', "%{$request->search}%")

                  ->orWhere('type', 'like', "%{$request->search}%")

                  ->orWhereHas('medicine', function ($medicine) use ($request) {

                      $medicine->where('name', 'like', "%{$request->search}%");

                  })

                  ->orWhereHas('user', function ($user) use ($request) {

                      $user->where('name', 'like', "%{$request->search}%");

                  });

            });

        })


        // From date
        ->when($request->from, function ($query) use ($request) {

            $query->whereDate('created_at', '>=', $request->from);

        })


        // To date
        ->when($request->to, function ($query) use ($request) {

            $query->whereDate('created_at', '<=', $request->to);

        })


        // Movement type
        ->when($request->type, function ($query) use ($request) {

            $query->where('type', $request->type);

        })


        ->latest()

        ->paginate(20)

        ->withQueryString();



    if ($request->ajax()) {

        return view('inventory.ledger_table', compact('ledger'));

    }


    return view('inventory.ledger', compact('ledger'));
}

}