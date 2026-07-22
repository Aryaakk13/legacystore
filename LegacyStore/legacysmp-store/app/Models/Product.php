<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'stock',
        'image_url',
        'is_active',
        'features',
        'commands',
        'discount_percent',
        'sale_ends_at',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'features' => 'array',
        'commands' => 'array',
        'sale_ends_at' => 'datetime',
        'discount_percent' => 'integer',
    ];

    /**
     * Mendapatkan harga setelah diskon.
     */
    public function getDiscountedPriceAttribute()
    {
        if ($this->discount_percent && $this->discount_percent > 0) {
            $discount = $this->price * ($this->discount_percent / 100);
            return $this->price - $discount;
        }
        return $this->price;
    }

    /**
     * Cek apakah produk sedang diskon.
     */
    public function getIsOnSaleAttribute()
    {
        return $this->discount_percent > 0
            && $this->sale_ends_at
            && $this->sale_ends_at->isFuture();
    }

    /**
     * Cek stok tersedia.
     */
    public function getInStockAttribute()
    {
        return $this->stock === -1 || $this->stock > 0;
    }

    /**
     * Scope untuk produk aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk produk berdasarkan kategori.
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope untuk produk diskon.
     */
    public function scopeOnSale($query)
    {
        return $query->where('discount_percent', '>', 0)
            ->where('sale_ends_at', '>', now());
    }
}

