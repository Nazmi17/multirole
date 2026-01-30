<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Tentukan siapa yang boleh melihat daftar user (Index).
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view users');
    }

    /**
     * Tentukan siapa yang boleh membuka form create.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create users');
    }

    /**
     * Tentukan siapa yang boleh melihat detail user (jika ada show).
     */
    public function view(User $user, User $model): bool
    {
        return $user->checkPermissionTo('view users');
    }

    /**
     * Tentukan siapa yang boleh mengedit user tertentu.
     */
    public function update(User $user, User $model): bool
    {
        // 1. Cek permission dasar
        if (! $user->checkPermissionTo('edit users')) {
            return false;
        }

        // 2. Aturan Hierarki (Logika dari Controllermu sebelumnya)
        // Jika target user adalah Admin atau Pengelola, dan yang mau edit BUKAN Admin, maka tolak.
        if ($model->hasRole(['admin', 'Pengelola']) && ! $user->hasRole('admin')) {
            return false;
        }

        return true;
    }

    /**
     * Tentukan siapa yang boleh menghapus user.
     */
    public function delete(User $user, User $model): bool
    {
        // 1. Cek permission dasar
        if (! $user->checkPermissionTo('delete users')) {
            return false;
        }

        // 2. Tidak boleh menghapus diri sendiri
        if ($user->id === $model->id) {
            return false;
        }

        // 3. Aturan Hierarki (Sama seperti update)
        if ($model->hasRole(['admin', 'Pengelola']) && ! $user->hasRole('admin')) {
            return false;
        }

        return true;
    }
}