<?php

namespace App\Models;

use App\Traits\Likable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;

class Article extends Model
{
    use HasFactory, SoftDeletes;
    use Likable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'user_id',
        'cover',
        'category_id', // <-- DITAMBAHKAN
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($article) {
            $article->slug = Str::slug($article->title);

            // kalau ada slug duplikat → tambahkan angka
            $originalSlug = $article->slug;
            $count = 1;
            while (static::where('slug', $article->slug)->exists()) {
                $article->slug = "{$originalSlug}-{$count}";
                $count++;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the user that owns the article.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that the article belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * The tags that belong to the article.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}