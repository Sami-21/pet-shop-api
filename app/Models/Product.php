<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_uuid',
        'uuid',
        'title',
        'price',
        'metadata',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'category_uuid');
    }
}
