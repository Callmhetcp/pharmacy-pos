<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [

        'pharmacy_name',

        'phone',

        'email',

        'address',

        'currency',

        'tax',

        'logo',

        'receipt_footer',

    ];
}