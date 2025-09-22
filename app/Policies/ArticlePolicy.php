<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    /**
     * Determine if the user can update the article.
     */
    public function update(User $user, Article $article): bool
    {
        // hanya pemilik artikel & status masih pending
        return $user->id === $article->user_id && $article->status === 'pending';
    }

    /**
     * Determine if the user can delete the article.
     */
    public function delete(User $user, Article $article): bool
    {
        // hanya pemilik artikel
        return $user->id === $article->user_id;
    }

    /**
     * Determine if the user can approve articles (admin/editor).
     */
    public function approve(User $user): bool
    {
        return $user->can('articles.approve');
    }

    /**
     * Determine if the user can reject articles.
     */
    public function reject(User $user): bool
    {
        return $user->can('articles.reject');
    }
}
