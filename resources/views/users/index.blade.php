@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('breadcrumb', 'Pengguna (Users)')

@section('content')
<div class="space-y-6">
    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between text-xs sm:text-sm shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-base font-bold">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 flex items-center justify-between text-xs sm:text-sm shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 text-base font-bold">&times;</button>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs sm:text-sm space-y-1 shadow-xs">
            <div class="font-bold flex items-center gap-1.5 text-rose-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Terdapat kendala saat menyimpan data pengguna:</span>
            </div>
            <ul class="list-disc list-inside text-rose-700 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Section with Add User Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Akun Pengguna</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Kelola akun administrator sistem dan pasangan pengantin (klien) yang terdaftar.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="px-3 py-1.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold">
                👥 Total {{ $users->count() }} Pengguna
            </span>

            <button type="button" 
                    onclick="openAddUserModal()"
                    class="px-4 py-2 rounded-xl bg-[#1B4D3E] hover:bg-[#153C30] text-white text-xs font-semibold flex items-center gap-2 shadow-xs transition hover:scale-[1.02] active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                <span>+ Tambah Pengguna</span>
            </button>
        </div>
    </div>

    <!-- User Grid Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($users as $user)
            <div class="p-5 bg-white border border-slate-200/90 rounded-2xl shadow-xs flex flex-col justify-between hover:shadow-md transition relative group">
                <div>
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-11 h-11 rounded-xl {{ $user->role === 'admin' ? 'bg-[#1B4D3E] text-emerald-300' : 'bg-blue-900 text-blue-200' }} flex items-center justify-center font-bold text-sm flex-shrink-0 shadow-xs">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>

                            <div class="overflow-hidden">
                                <h2 class="font-bold text-slate-900 text-sm truncate flex items-center gap-1.5">
                                    <span>{{ $user->name }}</span>
                                    @if(auth()->id() === $user->id)
                                        <span class="px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 text-[10px] font-semibold">(Anda)</span>
                                    @endif
                                </h2>
                                <div class="text-xs text-slate-400 truncate">
                                    {{ $user->email }}
                                </div>
                            </div>
                        </div>

                        <!-- Delete Button on Card -->
                        @if(auth()->id() !== $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirmDeleteUser('{{ addslashes($user->name) }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" 
                                        title="Hapus Pengguna {{ $user->name }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-semibold">
                                👑 Administrator
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200 text-[11px] font-semibold">
                                💍 Klien Pengantin
                            </span>
                        @endif

                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[11px] font-medium">
                            {{ $user->weddings_count ?? 0 }} Wedding
                        </span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 mt-4">
                    <span>Terdaftar:</span>
                    <span>{{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '-' }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-full p-8 text-center bg-white rounded-2xl border border-dashed border-slate-300 text-slate-400 text-xs">
                Belum ada pengguna terdaftar.
            </div>
        @endforelse
    </div>

    <!-- Structured Table View -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden mt-8">
        <div class="px-5 py-3.5 border-b border-slate-200 bg-slate-50/80 flex items-center justify-between">
            <span class="font-bold text-xs uppercase tracking-wider text-slate-600">Tabel Rincian Data Pengguna</span>
            <span class="text-xs text-slate-400">{{ $users->count() }} Akun</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">Nama Pengguna</th>
                        <th class="py-3 px-4">Alamat Email</th>
                        <th class="py-3 px-4">Peran (Role)</th>
                        <th class="py-3 px-4 text-center">Acara Dikelola</th>
                        <th class="py-3 px-4">Tanggal Terdaftar</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3 px-4 font-mono text-slate-400">#{{ $user->id }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900 flex items-center gap-1.5">
                                <span>{{ $user->name }}</span>
                                @if(auth()->id() === $user->id)
                                    <span class="px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 text-[10px] font-semibold">(Anda)</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-600 font-mono text-xs">{{ $user->email }}</td>
                            <td class="py-3 px-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-semibold">
                                        👑 Administrator
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 border border-blue-200 text-[11px] font-semibold">
                                        💍 Klien
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center font-semibold text-slate-800">
                                {{ $user->weddings_count ?? 0 }}
                            </td>
                            <td class="py-3 px-4 text-slate-500">
                                {{ $user->created_at ? $user->created_at->translatedFormat('d F Y, H:i') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirmDeleteUser('{{ addslashes($user->name) }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-2.5 py-1 rounded bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold transition"
                                                title="Hapus Akun Pengguna">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Akun Aktif</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: TAMBAH PENGGUNA BARU                                  -->
<!-- ============================================================ -->
<div id="modal-add-user" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                    👤
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Tambah Pengguna Baru</h3>
                    <p class="text-[11px] text-slate-500">Buat akun admin atau klien pengantin baru</p>
                </div>
            </div>
            <button type="button" onclick="closeAddUserModal()" class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
        </div>

        <!-- Modal Form -->
        <form action="{{ route('users.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       required 
                       placeholder="Contoh: Rian & Nisa / Studio Admin" 
                       value="{{ old('name') }}"
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       required 
                       placeholder="nama@domain.com" 
                       value="{{ old('email') }}"
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white">
            </div>

            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">
                    Peran Pengguna (Role) <span class="text-rose-500">*</span>
                </label>
                <select id="role" 
                        name="role" 
                        required 
                        class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white">
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>👑 Administrator (Akses Penuh Studio)</option>
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>💍 Klien Pengantin (Hanya Lihat Acara Sendiri)</option>
                </select>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                    Kata Sandi (Password) <span class="text-rose-500">*</span>
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required 
                       placeholder="Minimal 6 karakter" 
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white">
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">
                    Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required 
                       placeholder="Ketik ulang kata sandi" 
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:bg-white">
            </div>

            <!-- Actions -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" 
                        onclick="closeAddUserModal()" 
                        class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2 rounded-xl bg-[#1B4D3E] hover:bg-[#153C30] text-white text-xs font-semibold shadow-xs transition hover:scale-105 active:scale-95">
                    💾 Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddUserModal() {
        document.getElementById('modal-add-user').classList.remove('hidden');
    }

    function closeAddUserModal() {
        document.getElementById('modal-add-user').classList.add('hidden');
    }

    function confirmDeleteUser(userName) {
        return confirm(`Apakah Anda yakin ingin menghapus akun pengguna "${userName}"? Tindakan ini tidak dapat dibatalkan.`);
    }

    // Auto open modal if validation error occurred on store
    @if(isset($errors) && $errors->any())
        openAddUserModal();
    @endif
</script>
@endsection
