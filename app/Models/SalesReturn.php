<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    protected $fillable = [

        'return_number',
        'sale_id',
        'customer_id',
        'return_date',
        'reason',
        'total_amount',
        'status',

    ];

    protected $casts = [

        'return_date' => 'date',

    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class);
    }
}