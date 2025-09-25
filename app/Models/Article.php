<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'slug', 'content', 'status', 'user_id', 'cover'];

        protected static function booted()
    {
        static::creating(function ($article) {
            $article->slug = Str::slug($article->title);

            // kalau ada slug duplikat → tambahkan angka
            $originalSlug = $article->slug;
            $count = 1;
            while (Article::where('slug', $article->slug)->exists()) {
                $article->slug = "{$originalSlug}-{$count}";
                $count++;
            }
        });
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}
