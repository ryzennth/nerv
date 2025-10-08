<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ArticlePolicy
{
    public function create(User $user): bool
    {
        // Izinkan semua user yang sudah login untuk mengakses halaman create article
        return Auth::check();
    }

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
        public function viewAny(User $user): bool
    {
        // User bisa melihat daftar artikel jika mereka punya permission 'articles.view'
        return $user->can('articles.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Article $article): bool
    {
        // Izinkan jika user punya permission, atau jika dia adalah pemilik artikel
        if ($user->can('articles.view')) {
            return true;
        }
        return $user->id === $article->user_id;
    }
}
