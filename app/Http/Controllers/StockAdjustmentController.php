<?php

namespace App\Http\Controllers;
use App\Models\StockAdjustment;
use App\Models\StockMovement;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adjustments = StockAdjustment::with([
            'medicine',
            'user'
        ])
        ->latest()
        ->paginate(20);


        return view(
            'stock-adjustments.index',
            compact('adjustments')
        );
    }



    public function create()
    {
        $medicines = Medicine::orderBy('name')->get();


        return view(
            'stock-adjustments.create',
            compact('medicines')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{

    $request->validate([

        'medicine_id' => 'required|exists:medicines,id',

        'type' => 'required|in:increase,decrease',

        'quantity' => 'required|integer|min:1',

        'reason' => 'required|string|max:255',

        'notes' => 'nullable|string',

    ]);



    $medicine = Medicine::findOrFail($request->medicine_id);



    // Check decrease stock before transaction

    if(
        $request->type == StockAdjustment::DECREASE &&
        $medicine->quantity < $request->quantity
    ){

        return back()

            ->withInput()

            ->withErrors([

                'quantity' => 'The adjustment quantity cannot be greater than the current stock.'

            ]);

    }




    DB::transaction(function () use ($request, $medicine) {


        $oldQuantity = $medicine->quantity;



        if($request->type == StockAdjustment::INCREASE){


            $newQuantity = $oldQuantity + $request->quantity;


            $quantityIn = $request->quantity;

            $quantityOut = 0;


        } else {


            $newQuantity = $oldQuantity - $request->quantity;


            $quantityIn = 0;

            $quantityOut = $request->quantity;

        }




        $medicine->update([

            'quantity' => $newQuantity

        ]);




        StockAdjustment::create([

            'medicine_id' => $medicine->id,

            'type' => $request->type,

            'quantity' => $request->quantity,

            'old_quantity' => $oldQuantity,

            'new_quantity' => $newQuantity,

            'reason' => $request->reason,

            'notes' => $request->notes,

            'reference_number' => 'ADJ-'.time(),

            'user_id' => 1,

        ]);




        StockMovement::create([

            'medicine_id' => $medicine->id,

            'reference_number' => 'ADJ-'.time(),

            'type' => StockMovement::TYPE_ADJUSTMENT,

            'quantity_in' => $quantityIn,

            'quantity_out' => $quantityOut,

            'balance' => $newQuantity,

            'user_id' => 1,

        ]);


    });



    return redirect()

        ->route('stock-adjustments.index')

        ->with(
            'success',
            'Stock adjustment created successfully.'
        );

}
    /**
     * Display the specified resource.
     */
   public function show(StockAdjustment $stockAdjustment)
{
    $stockAdjustment->load('medicine', 'user');

    return view(
        'stock-adjustments.show',
        compact('stockAdjustment')
    );
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockAdjustment $stockAdjustment)
        {
            $medicines = Medicine::orderBy('name')->get();


            return view(
                'stock-adjustments.edit',
                compact(
                    'stockAdjustment',
                    'medicines'
                )
            );
        }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, StockAdjustment $stockAdjustment)
        {

            $request->validate([

                'medicine_id' => 'required|exists:medicines,id',

                'type' => 'required|in:increase,decrease',

                'quantity' => 'required|integer|min:1',

                'reason' => 'required|string|max:255',

                'notes' => 'nullable|string',

            ]);

            $currentMedicine = Medicine::findOrFail($stockAdjustment->medicine_id);


            // Calculate stock after reversing old adjustment

            $availableStock = $currentMedicine->quantity;


            if($stockAdjustment->type == StockAdjustment::INCREASE){

                $availableStock -= $stockAdjustment->quantity;

            }else{

                $availableStock += $stockAdjustment->quantity;

            }



            // Check new decrease quantity

            if(
                $request->type == StockAdjustment::DECREASE &&
                $availableStock < $request->quantity
            ){

                return back()

                    ->withInput()

                    ->withErrors([

                        'quantity' =>
                        'The adjustment quantity cannot be greater than the current stock.'

                    ]);

            }



            DB::transaction(function () use ($request, $stockAdjustment) {



                $medicine = Medicine::findOrFail(
                    $stockAdjustment->medicine_id
                );



                /*
                |--------------------------------------------------------------------------
                | Reverse Previous Adjustment
                |--------------------------------------------------------------------------
                */


                if($stockAdjustment->type == StockAdjustment::INCREASE){


                    // Remove previous increase

                    $medicine->quantity -= $stockAdjustment->quantity;


                }else{


                    // Return previous decrease

                    $medicine->quantity += $stockAdjustment->quantity;


                }



                $medicine->save();





                /*
                |--------------------------------------------------------------------------
                | Apply New Adjustment
                |--------------------------------------------------------------------------
                */


                $oldQuantity = $medicine->quantity;



                if($request->type == StockAdjustment::INCREASE){


                    $newQuantity = $oldQuantity + $request->quantity;


                }else{


                    $newQuantity = $oldQuantity - $request->quantity;


                    $newQuantity = $oldQuantity - $request->quantity;


                }





                /*
                |--------------------------------------------------------------------------
                | Update Medicine
                |--------------------------------------------------------------------------
                */


                $medicine->update([

                    'quantity' => $newQuantity

                ]);







                /*
                |--------------------------------------------------------------------------
                | Update Adjustment Record
                |--------------------------------------------------------------------------
                */


                $stockAdjustment->update([


                    'medicine_id' => $medicine->id,


                    'type' => $request->type,


                    'quantity' => $request->quantity,


                    'old_quantity' => $oldQuantity,


                    'new_quantity' => $newQuantity,


                    'reason' => $request->reason,


                    'notes' => $request->notes,


                ]);






                /*
                |--------------------------------------------------------------------------
                | Update Ledger Entry
                |--------------------------------------------------------------------------
                */


                $movement = StockMovement::where(
                    'reference_number',
                    $stockAdjustment->reference_number
                )->first();



                if($movement){


                    $movement->update([


                        'quantity_in' => 
                            $request->type == StockAdjustment::INCREASE
                            ? $request->quantity
                            : 0,


                        'quantity_out' => 
                            $request->type == StockAdjustment::DECREASE
                            ? $request->quantity
                            : 0,


                        'balance' => $newQuantity,


                    ]);

                }




            });



            return redirect()

                ->route('stock-adjustments.index')

                ->with(
                    'success',
                    'Stock adjustment updated successfully.'
                );

        }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(StockAdjustment $stockAdjustment)
{

    DB::transaction(function () use ($stockAdjustment) {


        $medicine = Medicine::findOrFail(
            $stockAdjustment->medicine_id
        );



        /*
        |--------------------------------------------------------------------------
        | Reverse Adjustment Effect
        |--------------------------------------------------------------------------
        */


        if($stockAdjustment->type == StockAdjustment::INCREASE){


            // Remove the previous increase

            $medicine->quantity -= $stockAdjustment->quantity;



        }else{


            // Return the previous decrease

            $medicine->quantity += $stockAdjustment->quantity;


        }




        $medicine->save();





        /*
        |--------------------------------------------------------------------------
        | Remove Ledger Entry
        |--------------------------------------------------------------------------
        */


        StockMovement::where(
            'reference_number',
            $stockAdjustment->reference_number
        )
        ->delete();





        /*
        |--------------------------------------------------------------------------
        | Remove Adjustment Record
        |--------------------------------------------------------------------------
        */


        $stockAdjustment->delete();



    });



    return redirect()

        ->route('stock-adjustments.index')

        ->with(
            'success',
            'Stock adjustment deleted successfully.'
        );

}
}
