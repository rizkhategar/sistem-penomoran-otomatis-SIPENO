<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    private function bidangOptions(): array
    {
        return [
            'PELAYANAN PENDAFTARAN PENDUDUK',
            'PELAYANAN PENCATATAN SIPIL',
            'PIAK',
            'SEKRETARIATAN',
        ];
    }

    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $bidangs = $this->bidangOptions();
        return view('admin.users.create', compact('bidangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:user,admin',
            'bidang' => ['required', Rule::in($this->bidangOptions())],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'bidang' => $request->bidang,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $bidangs = $this->bidangOptions();
        return view('admin.users.edit', compact('user', 'bidangs'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',id,'.$user->id],
            'role' => 'required|in:user,admin',
            'bidang' => ['required', Rule::in($this->bidangOptions())],
        ]);

        if ($user->id === auth()->id() && $request->role !== $user->role) {
            return back()->with('error', 'Tidak bisa mengubah role diri sendiri.');
        }

        $data = $request->only(['name', 'email', 'role', 'bidang']);
        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil diupdate.');
    }
}
