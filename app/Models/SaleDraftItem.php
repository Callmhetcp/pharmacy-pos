<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDraftItem extends Model
{
      protected $fillable = [
        'draft_id',
        'medicine_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];
    public function draft()
{
    return $this->belongsTo(SaleDraft::class);
}

public function medicine()
{
    return $this->belongsTo(Medicine::class);
}
}
