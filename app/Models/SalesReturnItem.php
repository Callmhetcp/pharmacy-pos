<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
    protected $fillable = [

        'sales_return_id',
        'medicine_id',
        'quantity',
        'selling_price',
        'subtotal',

    ];

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}