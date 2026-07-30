<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;


    protected $fillable = [

        'expense_number',

        'expense_category_id',

        'amount',

        'expense_date',

        'payment_method',

        'description',

        'user_id',
        'receipt',

    ];



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    public function category()
    {
        return $this->belongsTo(
            ExpenseCategory::class,
            'expense_category_id'
        );
    }




    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }



    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */


    public function getFormattedAmountAttribute()
    {
        return '₦' . number_format($this->amount, 2);
    }



}