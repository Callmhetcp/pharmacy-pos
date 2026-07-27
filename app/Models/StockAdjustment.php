<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;


    const INCREASE = 'increase';

    const DECREASE = 'decrease';


    protected $fillable = [

        'medicine_id',
        'type',
        'quantity',
        'old_quantity',
        'new_quantity',
        'reason',
        'notes',
        'reference_number',
        'user_id'

    ];


    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}