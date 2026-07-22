<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'coins',
        'discord_id',
        'avatar_url',
        'last_login_ip',
        'last_login_at',
        'is_banned',
        'banned_at',
        'ban_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'banned_at' => 'datetime',
        'is_banned' => 'boolean',
    ];

    /**
     * Relasi ke akun Minecraft.
     */
    public function mcAccounts()
    {
        return $this->hasMany(McAccount::class);
    }

    /**
     * Relasi ke pembelian.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Mendapatkan akun Minecraft utama.
     */
    public function primaryMcAccount()
    {
        return $this->hasOne(McAccount::class)->where('is_primary', true);
    }

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user di-ban.
     */
    public function isBanned()
    {
        return $this->is_banned;
    }

    /**
     * Total pembelanjaan user.
     */
    public function totalSpent()
    {
        return $this->purchases()
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    /**
     * Scope untuk pengguna aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_banned', false);
    }
}

