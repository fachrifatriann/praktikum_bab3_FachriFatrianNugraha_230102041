<?php
// app/Models/Product.php
namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Product extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'stock', 'image', 'status', 'is_featured',
    ];


    protected $casts = [
    'price'       => 'decimal:2',
    'stock'       => 'integer',
    'is_featured' => 'boolean',
    'status'      => ProductStatus::class, // ← Cast ke Enum!
];

    // ── Relationship ──────────────────────────────────────────
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    // ── Scopes ────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }


    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }


    // ── Accessor ──────────────────────────────────────────────
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->price, 0, ',', '.')
        );
    }


    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image
                ? asset('storage/' . $this->image)
                : asset('images/placeholder.png')
        );
    }
}
