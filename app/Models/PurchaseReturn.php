<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'Pending';

    const STATUS_COMPLETED = 'Completed';

    const STATUS_CANCELLED = 'Cancelled';

    protected $fillable = [

        'return_number',
        'purchase_id',
        'supplier_id',
        'return_date',
        'reason',
        'total_amount',
        'status',
        'user_id',

    ];

    protected $casts = [
    'return_date' => 'date',
];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}