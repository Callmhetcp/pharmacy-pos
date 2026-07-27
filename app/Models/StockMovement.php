<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{

    protected $fillable = [

        'medicine_id',
        'reference_number',
        'type',
        'quantity_in',
        'quantity_out',
        'balance',
        'user_id',
    ];

    public const TYPE_PURCHASE = 'Purchase';
    public const TYPE_SALE = 'Sale';

    public const TYPE_PURCHASE_RETURN = 'Purchase Return';

    public const TYPE_SALES_RETURN = 'Sales Return';

    public const TYPE_ADJUSTMENT = 'Adjustment';

    public function medicine(){

        return $this->belongsTo(Medicine::class);
    }

     public function user(){

        return $this->belongsTo(User::class);
    }



    
}
