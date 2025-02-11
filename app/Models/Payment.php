<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'type',
        'details',
    ];

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
