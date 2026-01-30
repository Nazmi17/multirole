<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Ambil semua Role beserta permission yang dimilikinya
        $roles = Role::with('permissions')->get();
        
        // Ambil semua Permission yang tersedia di database
        $permissions = Permission::all()->sortBy('name');
        
        // Hitung total user (opsional, untuk statistik)
        $totalUsers = User::count();

        if (auth()->user()->hasRole('admin')) {
        $roles = Role::all();
        } else {
            $roles = Role::where('name', '!=', 'admin')->get();
        }

        return view('admin.dashboard', compact('roles', 'permissions', 'totalUsers'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return back()->with('success', 'Role baru berhasil dibuat!');
    }

    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
        ]);

        // Sync permissions: Hapus yang tidak dicentang, tambah yang dicentang
        // Jika tidak ada yang dicentang, permissions akan dikosongkan
        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Hak akses role berhasil diperbarui!');
    }

    public function destroyRole(Role $role)
    {
        // Mencegah penghapusan role admin utama agar tidak terkunci
        if ($role->name === 'admin') {
            return back()->with('error', 'Role Admin utama tidak boleh dihapus!');
        }

        $role->delete();

        return back()->with('success', 'Role berhasil dihapus.');
    }
}