<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
     use HasFactory;


    protected $fillable = [

        'name',

        'phone_number',

        'address',

        'status',


    ];
    public function salesReturns()
{
    return $this->hasMany(SalesReturn::class);
}
 public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
