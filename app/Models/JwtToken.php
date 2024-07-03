<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JwtToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token_title',
        'restrictions',
        'permissions',
        'expired_at',
        'last_used_at',
        'refreshed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
