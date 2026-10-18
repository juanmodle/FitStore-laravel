<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giveaway extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'prize', 'starts_at', 'ends_at', 'status', 'winner_user_id',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'giveaway_entries')->using(GiveawayEntry::class)->withPivot('joined_at')->withTimestamps();
    }
}
