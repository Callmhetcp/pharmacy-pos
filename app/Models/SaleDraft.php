<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDraft extends Model
{

    protected $fillable = [
        'draft_number',
        'customer_id',
        'user_id',
        'status',
    ];
    public function customer()
{
    return $this->belongsTo(Customer::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}

public function items()
{
    return $this->hasMany(SaleDraftItem::class,'draft_id');
}

public function total()
{
    return $this->items()->sum('subtotal');
}


}
