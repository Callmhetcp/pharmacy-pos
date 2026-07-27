<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{

protected $fillable = [

    'purchase_return_id',

    'medicine_id',

    'quantity',

    'cost_price',

    'subtotal',

];
   public function purchaseReturn()
{
    return $this->belongsTo(PurchaseReturn::class);
}



public function medicine()
{
    return $this->belongsTo(Medicine::class);
}
}
