<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('weddings.index');
            }
            Auth::logout();
        }

        // Pastikan akun admin default tersedia jika database masih kosong
        if (User::where('role', 'admin')->count() === 0) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@weddinggallery.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]);
        }

        return view('auth.login');
    }

    /**
     * Handle the admin login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Akses ditolak. Portal ini khusus untuk akun Administrator Studio.']);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('weddings.index'))
                ->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Kombinasi email dan kata sandi yang Anda masukkan tidak sesuai.']);
    }

    /**
     * Log the admin out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'Anda telah berhasil keluar dari sistem.');
    }
}
