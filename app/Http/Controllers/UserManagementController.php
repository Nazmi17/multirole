<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate; 

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        // Ganti $this->authorize dengan Gate::authorize
        Gate::authorize('viewAny', User::class);

        $query = User::with('roles')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if (!auth()->user()->hasRole('admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            });
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        Gate::authorize('create', User::class); // <--- Pakai Gate

        if (auth()->user()->hasRole('admin')) {
            $roles = Role::where('name', '!=', 'admin')->get();
        } else {
            $roles = Role::whereNotIn('name', ['admin', 'Pengelola'])->get();
        }

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', User::class); 

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash', 'lowercase'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_active' => ['required', 'boolean'],
            'role' => [
                'required', 
                'exists:roles,name', 
                function ($attribute, $value, $fail) {
                    if ($value === 'admin') {
                        $fail('Tidak diizinkan membuat Super Admin baru.');
                    }
                    if (in_array($value, ['admin', 'pengelola']) && !auth()->user()->hasRole('admin')) {
                        $fail('Anda hanya diizinkan membuat akun User biasa.');
                    }
                },
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'email_verified_at' => now(),
            'is_active' => $request->is_active,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        Gate::authorize('update', $user); // <--- Pakai Gate

        if (auth()->user()->hasRole('admin')) {
            $roles = Role::where('name', '!=', 'admin')->get();
        } else {
            $roles = Role::where('name', 'user')->get();
        }

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user); // <--- Pakai Gate

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id), 'alpha_dash', 'lowercase'],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => [
                'required', 
                'exists:roles,name',
                function ($attribute, $value, $fail) {
                    if ($value === 'admin') {
                        $fail('Tidak diizinkan menjadikan user sebagai Super Admin.');
                    }
                    if (in_array($value, ['admin', 'pengelola']) && !auth()->user()->hasRole('admin')) {
                        $fail('Anda tidak diizinkan mengubah role menjadi Admin atau Pengelola.');
                    }
                }
            ],     
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'is_active' => $request->is_active,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $user->syncRoles($request->role);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user); 

        $user->delete();

        $user->is_active = false;
        $user->save();

        return back()->with('success', 'User berhasil dihapus.');
    }

    /**
     * Menampilkan daftar user yang sudah dihapus (Soft Deleted).
     */
    public function trash()
    {
        // Gate check (opsional, sesuaikan dengan Policy Anda)
        // Gate::authorize('viewTrash', User::class);

        // onlyTrashed() hanya mengambil data yang kolom deleted_at-nya terisi
        $users = User::onlyTrashed()->latest()->paginate(10);

        return view('admin.users.trash', compact('users'));
    }

    public function restore($id)
    {
        // Cari user di tong sampah
        $user = User::withTrashed()->findOrFail($id);

        // Gate::authorize('restore', $user);

        $user->restore();

        $user->is_active = true;
        $user->save();

        return back()->with('success', 'User berhasil dipulihkan (Restore).');
    }

  
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // Gate::authorize('forceDelete', $user);

        if ($user->avatar) {
             Storage::disk('public')->delete($user->avatar);
        }

        $user->forceDelete();

        return back()->with('success', 'User berhasil dihapus permanen.');
    }
}