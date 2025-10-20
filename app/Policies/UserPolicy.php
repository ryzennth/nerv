<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        // Super Admin bisa melakukan segalanya
        if ($user->hasRole('Super Admin')) {
            return true;
        }
        return null; // Lanjutkan ke pengecekan ability lain
    }

    public function viewAny(User $user): bool
    {
        return $user->can('users.index');
    }

    public function view(User $user, User $model): bool
    {
        // 1. Izinkan user untuk melihat profilnya sendiri.
        if ($user->id === $model->id) {
            return true;
        }

        // 2. Izinkan user lain jika mereka punya permission.
        return $user->can('users.view');
    }

    public function create(User $user): bool
    {
        return $user->can('users.create');
    }

    public function update(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true;
        }
        // Admin tidak bisa edit Super Admin
        if ($model->hasRole('Super Admin')) {
            return false;
        }
        // Admin tidak bisa edit sesama Admin
        if ($model->hasRole('Admin') && $user->hasRole('Admin')) {
            return false;
        }
        return $user->can('users.edit');
    }

    public function delete(User $user, User $model): bool
    {
        // Admin tidak bisa hapus Super Admin atau sesama Admin
        if ($model->hasRole(['Super Admin', 'Admin'])) {
            return false;
        }
        return $user->can('users.delete');
    }
    
    public function toggleStatus(User $user, User $model): bool
    {
        // Admin tidak bisa mengubah status Super Admin atau sesama Admin
        if ($model->hasRole(['Super Admin', 'Admin'])) {
            return false;
        }
        return $user->can('users.toggleStatus');
    }
}