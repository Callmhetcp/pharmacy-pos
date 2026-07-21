<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session; 
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;

use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(){

        $customers = Customer::all();
        $medicines = Medicine::all();
        $cart = Session::get('cart',[]);


        return view ('sales.index', compact('customers', 'medicines', 'cart'));

    }

    public function addToCart(Request $request){

        

         $medicine = Medicine::find($request->medicine_id);

        if(!$medicine){

            return redirect('/sales');

         }
                    Session::put('customer_id', $request->customer_id);
                   $cart = Session::get('cart', []);
        
                
        
                  $cart[] = [
        
                       'id'=>$medicine->id,
                        'name'=>$medicine->name,
                       'price'=>$medicine->selling_price,
                        'quantity'=>$request->quantity,
                       'subtotal'=>$medicine->selling_price * $request->quantity,
        
                    ];
        
                  Session::put('cart', $cart);
        
                 return redirect('/sales');
    }

    public function store(Request $request){

        $cart = Session::get ('cart', []);

        if(count($cart) == 0 ){

            return redirect ('/sales');
        }

        $total = collect($cart)->sum('subtotal');

        $customerId = Session::get('customer_id');

    

        if(!$customerId){
            return redirect('/sales')->with('error', 'Please select a customer first');
        }

        $sale = Sale::create([
            'customer_id' => $customerId,
            'user_id'=> 1,
            'total_amount'=> $total,
            'sale_date'=>now(),
        ]);

        foreach ($cart as $item) {

            SaleItem::create([
                'sale_id'=>$sale->id,
                'medicine_id' => $item['id'],
                'quantity' => $item ['price'],
                'unit_price' => $item['price'],
                'subtotal' => $item['subtotal'],

                ]);

                $medicine = Medicine::find($item['id']);
                $medicine->quantity -= $item ['quantity'];
                $medicine->save();
        }
         Session::forget('cart');

         return redirect ('sales');
    }

    public function clearCart(){

        Session::forget('cart');

        return redirect ('sales');
    }
}
