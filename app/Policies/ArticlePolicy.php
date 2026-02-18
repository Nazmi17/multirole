<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view articles');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Article $article): bool
    {
        if ($user->id == $article->user_id) {
            return true;
        }

        if ($user->hasRole('editor') && !$article->status == 'draft') {
            return true;
        }

        if ($user->hasRole('admin') && !$article->status == 'draft') {
            return true;
        }

        return $article->status == 'published';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create articles');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Article $article): bool
    {
        if ($user->id !== $article->user_id) {
            return false;
        }

        // Hanya boleh edit jika status Draft atau Rejected (untuk revisi)
        // Jika sudah Pending/Published, harus dibatalkan dulu (opsional logic)
        return in_array($article->status, ['draft', 'rejected']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->checkPermissionTo('delete articles');
    }

    public function approve(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->checkPermissionTo('approve articles');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Article $article): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Article $article): bool
    {
        return false;
    }
}
