<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserViewController extends Controller
{
    /**
     * Menampilkan daftar pengguna (admin & client).
     */
    public function index()
    {
        $users = User::withCount('weddings')->latest()->get();

        return view('users.index', compact('users'));
    }

    /**
     * Menyimpan data pengguna baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:admin,user',
        ], [
            'name.required'     => 'Nama pengguna wajib diisi.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email ini sudah terdaftar oleh pengguna lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
            'password.confirmed'=> 'Konfirmasi kata sandi tidak cocok.',
            'role.required'     => 'Peran pengguna wajib dipilih.',
            'role.in'           => 'Peran pengguna harus Administrator atau Klien.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna '{$user->name}' berhasil ditambahkan ke sistem!");
    }

    /**
     * Menghapus data pengguna dari sistem.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Jangan izinkan admin menghapus akunnya sendiri yang sedang aktif digunakan
        if (auth()->id() == $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif masuk.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna '{$userName}' berhasil dihapus dari sistem.");
    }
}
