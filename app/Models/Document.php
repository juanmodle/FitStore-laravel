<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'type', 'name', 'path',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}
