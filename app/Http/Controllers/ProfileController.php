<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule; // <-- PASTIKAN INI DI-IMPORT
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form (for the logged-in user).
     */
    public function edit(Request $request): Response
    {
        // REFACTOR 1: Langsung passing seluruh objek user.
        // Inertia akan secara otomatis mengubahnya menjadi array/JSON di frontend.
        // Ini lebih bersih dan praktis.
        return Inertia::render('Profile/Edit', [
            'user' => $request->user(),
            'mustVerifyEmail' => $request->user()->hasVerifiedEmail(),
            'status' => session('status'),
        ]);
    }

    /**
     * Update the logged-in user's profile.
     */
    public function update(Request $request)
    {
        dd($request->all());
        $user = $request->user();

        // REFACTOR 2: Menggunakan `Rule::unique()` untuk validasi yang lebih mudah dibaca.
        $validated = $request->validate([
            'name' => ['filled', 'string', 'max:255'],
            'username' => ['filled', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_]+$/', Rule::unique('users')->ignore($user->id)],
            'email' => ['filled', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);
        
        // Cek apakah ada perubahan email untuk verifikasi ulang
        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();
        
        return redirect()->route('profile.edit')->with('message', 'Profile updated successfully.');
    }

    /**
     * Delete the logged-in user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'Your account has been deleted.');
    }
}