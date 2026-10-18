<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag')->using(ProductTag::class)->withPivot('priority', 'is_visible')->withTimestamps();
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag')->withTimestamps();
    }
}
