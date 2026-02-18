<?php

namespace App\Policies;

use App\Models\Ebook;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EbookPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view ebooks');
    }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, Ebook $ebook): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create ebooks');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ebook $ebook): bool
    {
        return $user->hasPermissionTo('update ebooks');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ebook $ebook): bool
    {
        return $user->hasPermissionTo('delete ebooks');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ebook $ebook): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ebook $ebook): bool
    {
        return false;
    }
}
