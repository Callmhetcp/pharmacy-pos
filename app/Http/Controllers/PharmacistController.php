<?php

namespace App\Http\Controllers;

use App\Models\Medicine;

class PharmacistController extends Controller
{
    public function index()
    {
        $totalMedicines = Medicine::count();

        $lowStock = Medicine::whereColumn('quantity', '<=', 'minimum_stock')
            ->count();

        $outOfStock = Medicine::where('quantity', 0)
            ->count();

        $expiringSoon = Medicine::whereDate(
                'expiry_date',
                '<=',
                now()->addDays(30)
            )
            ->count();

        $recentMedicines = Medicine::latest()
            ->take(10)
            ->get();

        return view(
            'pharmacist.dashboard',
            compact(
                'totalMedicines',
                'lowStock',
                'outOfStock',
                'expiringSoon',
                'recentMedicines'
            )
        );
    }
}
