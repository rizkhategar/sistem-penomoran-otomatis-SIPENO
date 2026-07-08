<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MasterBidang;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private function bidangOptions(): array
    {
        $bidangs = MasterBidang::where('is_active', true)
            ->orderBy('name')
            ->pluck('name')
            ->values()
            ->all();

        return $bidangs ?: [
            'PELAYANAN PENDAFTARAN PENDUDUK',
            'PELAYANAN PENCATATAN SIPIL',
            'PIAK',
            'SEKRETARIATAN',
        ];
    }

    public function create(): View
    {
        $bidangs = $this->bidangOptions();
        return view('auth.register', compact('bidangs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'bidang' => ['required', Rule::in($this->bidangOptions())],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'bidang' => $request->bidang,
            'role' => 'user',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }
}
