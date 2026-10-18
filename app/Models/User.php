<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'password', 'locale'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function profile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function vipSubscriptions()
    {
        return $this->hasMany(VipSubscription::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function wonGiveaways()
    {
        return $this->hasMany(Giveaway::class, 'winner_user_id');
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class)->using(CouponUser::class)
            ->withPivot('used_at', 'times_used')->withTimestamps();
    }

    public function giveaways()
    {
        return $this->belongsToMany(Giveaway::class, 'giveaway_entries')->using(GiveawayEntry::class)
            ->withPivot('joined_at')->withTimestamps();
    }

    public function isVip(): bool
    {
        return $this->vipSubscriptions()->where('status', 'active')
            ->where('starts_at', '<=', now())->where('ends_at', '>', now())->exists();
    }
}
