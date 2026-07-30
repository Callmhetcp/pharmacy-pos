<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'is_read',
        'user_id',
        'medicine_id',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicine()
{
    return $this->belongsTo(Medicine::class);
}
}
