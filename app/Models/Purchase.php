<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\PurchaseItem;

class Purchase extends Model
{
    use HasFactory;

   protected $fillable = [

    'purchase_number',

    'supplier_id',

    'invoice_number',

    'purchase_date',

    'grand_total',

    'amount_paid',

    'balance',

    'payment_status',

    'user_id',

];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseItems(){

        return $this->hasMany(PurchaseItem::class);
    }

    public function PurchaseReturns()
{
    return $this->hasMany(PurchaseReturn::class);
}
}
