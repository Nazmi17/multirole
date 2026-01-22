<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules; // <--- Pastikan ini ada (Class Rules)
use Spatie\Permission\Models\Role; // <--- Ini untuk Role Spatie

class UserManagementController extends Controller
{
    public function index()
    {
       $query = User::with('roles')->latest();

        // FILTER: Jika yang login BUKAN 'admin', sembunyikan user yang punya role 'admin'
        if (!auth()->user()->hasRole('admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            });
        }

        $users = $query->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (auth()->user()->hasRole('admin')) {
            $roles = Role::where('name', '!=', 'admin')->get();
        } else {
            $roles = Role::where('name', 'user')->get();
        }

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if ($request->role === 'admin') {
            abort(403, 'Tindakan ilegal: Tidak diizinkan membuat Super Admin baru.');
        }
        
        if (in_array($request->role, ['admin', 'pengelola']) && !auth()->user()->hasRole('admin')) {
            abort(403, 'Anda hanya diizinkan membuat akun User biasa.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash'],
            'password' => [
                            'required', 
                            'confirmed', 
                            \Illuminate\Validation\Rules\Password::min(8)
                                ->letters()
                                ->mixedCase()
                                ->numbers()
                                ->symbols()
                          ],
            'email_verified_at' => now(),
            'is_active' => ['required', 'boolean'],
            
            'role' => ['required', 'exists:roles,name', 'not_in:admin'],
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
        if ($user->hasRole('admin') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses ke user ini.');
        }

        if ($user->hasRole('Pengelola') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses ke user ini.');
        }

        if (auth()->user()->hasRole('admin')) {
            $roles = Role::where('name', '!=', 'admin')->get();
        } else {
            $roles = Role::where('name', 'user')->get();
        }

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($request->role === 'admin') {
            abort(403, 'Tindakan ilegal: Tidak diizinkan menaikkan role menjadi Super Admin.');
        }

        if ($user->hasRole('admin') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit akun Admin.');
        }

        if ($user->hasRole('Pengelola') && !auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit akun Pengelola.');
        }

        if (in_array($request->role, ['admin', 'pengelola']) && !auth()->user()->hasRole('admin')) {
            abort(403, 'Anda tidak diizinkan mengubah role menjadi Admin atau Pengelola.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id), 'alpha_dash'],
            'role' => ['required', 'exists:roles,name', 'not_in:admin'],            
            'password' => [
                'nullable', 
                'confirmed', 
                \Illuminate\Validation\Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'is_active' => ['required', 'boolean'],
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
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}