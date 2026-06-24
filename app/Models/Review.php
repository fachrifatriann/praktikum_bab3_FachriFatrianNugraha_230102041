<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    // Sudah dipastikan menggunakan tanda sama dengan (=) yang benar
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    /**
     * Relasi ke model User (Inverse Relationship).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Product (Inverse Relationship).
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
