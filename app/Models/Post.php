<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'post_category_id', 'title', 'slug', 'content', 'status', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag')->withTimestamps();
    }

    public function translations()
    {
        return $this->hasMany(PostTranslation::class);
    }
}
