<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the parent likeable model (article or photo).
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the like.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}