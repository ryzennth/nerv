<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Terapkan policy ke semua resource method.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Menampilkan daftar semua user.
     */
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->filter($request->only('search', 'role')) // Menggunakan local scope untuk filter
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render("Users/Index", [
            "users" => $users,
            "roles" => Role::all(['id', 'name']),
            'filters' => $request->only(['search', 'role']),
            'can' => [
                'create_user' => $request->user()->can('create', User::class),
            ]
        ]);
    }

    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        // Logika role difilter berdasarkan policy
        $roles = Role::query()
            ->when(!auth()->user()->hasRole('Super Admin'), function ($query) {
                $query->whereNotIn('name', ['Super Admin', 'Admin']);
            })
            ->get(['id', 'name']);

        return Inertia::render("Users/Create", [
            "roles" => $roles,
        ]);
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(StoreUserRequest $request)
    {
        // Validasi dan otorisasi ditangani oleh StoreUserRequest
        $user = User::create($request->getValidatedData());
        $user->syncRoles($request->roles);

        return to_route("users.index")->with("message", "User berhasil dibuat.");
    }

    /**
     * Menampilkan detail user.
     */
    public function show(User $user)
    {
        return Inertia::render("Users/Show", [
            "user" => $user->load('roles:id,name'),
        ]);
    }

    /**
     * Menampilkan form untuk mengedit user.
     */
    public function edit(User $user)
    {
        $roles = Role::query()
            ->when(!auth()->user()->hasRole('Super Admin'), function ($query) {
                $query->whereNotIn('name', ['Super Admin', 'Admin']);
            })
            ->get(['id', 'name']);

        return Inertia::render("Users/Edit", [
            "user" => $user,
            "roles" => $roles,
            "userRoles" => $user->getRoleNames(), // Method bawaan Spatie
        ]);
    }

    /**
     * Mengupdate data user di database.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Validasi dan otorisasi ditangani oleh UpdateUserRequest
        $user->update($request->getValidatedData());
        $user->syncRoles($request->roles);

        return to_route("users.index")->with("message", "User berhasil diperbarui.");
    }

    /**
     * Menghapus user dari database.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return to_route("users.index")->with("message", "User berhasil dihapus.");
    }

    /**
     * Mengubah status aktif/nonaktif user.
     */
    public function toggleStatus(User $user)
    {
        $this->authorize('toggleStatus', $user); // Otorisasi manual karena bukan resource method

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('message', 'Status user berhasil diubah.');
    }
}