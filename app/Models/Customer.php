<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function salesReturns()
{
    return $this->hasMany(SalesReturn::class);
}
}
