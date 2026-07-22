<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'username',
        'uuid',
        'is_verified',
        'is_primary',
        'verified_at',
        'last_synced_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'is_primary' => 'boolean',
        'verified_at' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Relasi ke user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke pembelian.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Cek apakah akun terverifikasi.
     */
    public function isVerified()
    {
        return $this->is_verified;
    }

    /**
     * Mendapatkan URL skin Minecraft dari Crafatar.
     */
    public function getSkinUrlAttribute()
    {
        if ($this->uuid) {
            return "https://crafatar.com/avatars/{$this->uuid}?size=64&overlay";
        }
        return "https://crafatar.com/avatars/{$this->username}?size=64&overlay";
    }

    /**
     * Mendapatkan URL body Minecraft.
     */
    public function getBodyUrlAttribute()
    {
        if ($this->uuid) {
            return "https://crafatar.com/renders/body/{$this->uuid}?size=128&overlay";
        }
        return "https://crafatar.com/renders/body/{$this->username}?size=128&overlay";
    }

    /**
     * Scope untuk akun terverifikasi.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope untuk akun utama.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}

