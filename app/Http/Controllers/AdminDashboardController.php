<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::with('permissions')->latest();
        $query->where('name', '!=', 'admin');

        if ($request->has('search' && $request->search == '')) {
            $query->where('name', 'like', "%" . $request->search . "%");
        }

        $roles = $query->paginate(4)->withQueryString();

        $roles = Role::with('permissions')->paginate(4)->withQueryString();
        
        $permissions = Permission::all()->sortBy('name');
        
        $totalUsers = User::count();

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

    public function backToDashboard()
    {
        return redirect()->route('dashboard');
    }
}