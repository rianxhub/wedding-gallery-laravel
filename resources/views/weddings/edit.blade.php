@extends('layouts.app')

@section('title', 'Edit Proyek: ' . $wedding->couple_name)
@section('breadcrumb', 'Edit Proyek Pernikahan')

@section('content')
<div class="space-y-6">
    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-xs shadow-xs">
            <div class="font-bold flex items-center gap-2 mb-2">
                <span>⚠️</span> Mohon periksa kembali formulir berikut:
            </div>
            <ul class="list-disc pl-5 space-y-1 text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <form action="{{ route('weddings.update', $wedding->slug) }}" method="POST" id="wedding-edit-form">
        @csrf

        <!-- Top Header & Actions Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Proyek: {{ $wedding->couple_name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        {{ $wedding->theme ?? 'Elegance Emerald' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Sesuaikan konfigurasi cover hero, visual theme preset, keamanan PIN, izin unduh tamu, dan kutipan romantis.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('weddings.show', $wedding->slug) }}" 
                   class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-xs transition">
                    Batal
                </a>

                <button type="submit" 
                        class="px-5 py-2 text-xs font-semibold text-white bg-[#1B4D3E] hover:bg-[#153C30] rounded-lg shadow-xs transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </div>

        <!-- 2-Column Main Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-6">
            <!-- Left Column: Form Sections (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Section 1: Basic Information -->
                <div class="p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-800 flex items-center justify-center font-bold text-xs">
                                1
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Informasi Dasar Klien</h3>
                                <p class="text-[11px] text-slate-500">Nama pasangan, tanggal acara pernikahan, dan slug URL publik.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Couple Name -->
                        <div>
                            <label for="couple_name" class="block text-xs font-semibold text-slate-700 mb-1">
                                Couple Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="couple_name" 
                                   name="couple_name" 
                                   value="{{ old('couple_name', $wedding->couple_name) }}" 
                                   required 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-900 focus:bg-white focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 transition">
                        </div>

                        <!-- Wedding Event Date -->
                        <div>
                            <label for="wedding_date" class="block text-xs font-semibold text-slate-700 mb-1">
                                Wedding Event Date
                            </label>
                            <input type="date" 
                                   id="wedding_date" 
                                   name="wedding_date" 
                                   value="{{ old('wedding_date', $wedding->wedding_date ? $wedding->wedding_date->format('Y-m-d') : '') }}" 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-900 focus:bg-white focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 transition">
                        </div>
                    </div>

                    <!-- Gallery Public URL Slug -->
                    <div>
                        <label for="slug" class="block text-xs font-semibold text-slate-700 mb-1">
                            Gallery Public URL Slug <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center">
                            <span class="px-3 py-2 bg-slate-100 border border-r-0 border-slate-200 rounded-l-lg text-xs font-mono text-slate-500">
                                {{ url('/gallery') }}/
                            </span>
                            <input type="text" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug', $wedding->slug) }}" 
                                   required 
                                   class="flex-1 px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-r-lg text-xs font-mono font-semibold text-slate-900 focus:bg-white focus:ring-1 focus:ring-emerald-700 transition">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Cloud Asset Integration -->
                <div class="p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-800 flex items-center justify-center font-bold text-xs">
                                2
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Integrasi Google Drive</h3>
                                <p class="text-[11px] text-slate-500">Folder ID Google Drive tempat foto galeri tersimpan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Google Drive Folder ID / URI -->
                    <div>
                        <label for="drive_folder" class="block text-xs font-semibold text-slate-700 mb-1">
                            Google Drive Folder ID / URI
                        </label>
                        <input type="text" 
                               id="drive_folder" 
                               name="drive_folder" 
                               value="{{ old('drive_folder', $wedding->drive_folder_id) }}" 
                               placeholder="Tempel link Google Drive atau ID folder" 
                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono font-medium text-slate-800 focus:bg-white focus:ring-1 focus:ring-emerald-700 transition">
                    </div>
                </div>

                <!-- Section 3: Visual Presentation & Security (Figma Required Fields) -->
                <div class="p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-800 flex items-center justify-center font-bold text-xs">
                                3
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Visual Presentation &amp; Privacy Security</h3>
                                <p class="text-[11px] text-slate-500">Cover hero, visual theme preset, proteksi PIN, dan izin unduh tamu.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Hero Cover Photo: Input untuk ID file Google Drive (cover_drive_file_id) -->
                    <div>
                        <label for="cover_drive_file_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Gallery Hero Cover Photo (Google Drive File ID)
                        </label>
                        <div class="p-4 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 flex flex-col sm:flex-row items-center gap-4">
                            <div class="w-28 h-20 rounded-lg overflow-hidden bg-slate-800 border border-slate-200 flex-shrink-0 shadow-sm relative">
                                <img src="{{ $wedding->cover_photo_url }}" 
                                     alt="Preview Cover" 
                                     class="w-full h-full object-cover">
                                <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded font-mono">
                                    Current
                                </span>
                            </div>
                            <div class="flex-1 space-y-1 w-full">
                                <span class="text-xs font-bold text-slate-800 block">Google Drive File ID / Link</span>
                                <input type="text" 
                                       id="cover_drive_file_id" 
                                       name="cover_drive_file_id" 
                                       placeholder="Contoh: 1AbC2dEfG3hIjKlMnOpQrStUv" 
                                       value="{{ old('cover_drive_file_id', $wedding->cover_drive_file_id) }}" 
                                       class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg font-mono text-slate-800 focus:ring-1 focus:ring-emerald-700">
                                <span class="text-[10px] text-slate-400 block">ID file gambar dari Google Drive yang akan dijadikan background hero publik.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Theme Preset (Radio button untuk memilih tema) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Visual Theme Preset</label>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="theme-selector-grid">
                            @php
                                $currentTheme = old('theme', $wedding->theme ?? 'Elegance Emerald');
                            @endphp

                            <!-- Theme 1: Elegance Emerald -->
                            <label class="theme-card cursor-pointer border-2 {{ $currentTheme == 'Elegance Emerald' ? 'border-emerald-800 bg-emerald-50/40' : 'border-slate-200 bg-white' }} rounded-xl p-3 flex flex-col justify-between space-y-2 hover:border-emerald-700 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-[#1B4D3E]"></span>
                                        <span class="w-3 h-3 rounded-full bg-[#34D399]"></span>
                                    </div>
                                    <input type="radio" name="theme" value="Elegance Emerald" {{ $currentTheme == 'Elegance Emerald' ? 'checked' : '' }} onchange="selectThemeCard(this)" class="text-emerald-800 focus:ring-emerald-700">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Elegance Emerald</div>
                                    <div class="text-[10px] text-slate-500">Deep botanical slate</div>
                                </div>
                            </label>

                            <!-- Theme 2: Warm Minimalist -->
                            <label class="theme-card cursor-pointer border-2 {{ $currentTheme == 'Warm Minimalist' ? 'border-amber-700 bg-amber-50/40' : 'border-slate-200 bg-white' }} rounded-xl p-3 flex flex-col justify-between space-y-2 hover:border-amber-600 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-[#8C6D58]"></span>
                                        <span class="w-3 h-3 rounded-full bg-[#E5DFD7]"></span>
                                    </div>
                                    <input type="radio" name="theme" value="Warm Minimalist" {{ $currentTheme == 'Warm Minimalist' ? 'checked' : '' }} onchange="selectThemeCard(this)" class="text-amber-800 focus:ring-amber-700">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Warm Minimalist</div>
                                    <div class="text-[10px] text-slate-500">Earth, travertine stone</div>
                                </div>
                            </label>

                            <!-- Theme 3: Modern Classic -->
                            <label class="theme-card cursor-pointer border-2 {{ $currentTheme == 'Modern Classic' ? 'border-zinc-900 bg-zinc-100' : 'border-slate-200 bg-white' }} rounded-xl p-3 flex flex-col justify-between space-y-2 hover:border-zinc-800 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-slate-900"></span>
                                        <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                                    </div>
                                    <input type="radio" name="theme" value="Modern Classic" {{ $currentTheme == 'Modern Classic' ? 'checked' : '' }} onchange="selectThemeCard(this)" class="text-slate-900 focus:ring-slate-800">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Modern Classic</div>
                                    <div class="text-[10px] text-slate-500">Editorial monochrome</div>
                                </div>
                            </label>

                            <!-- Theme 4: Sunset Gold -->
                            <label class="theme-card cursor-pointer border-2 {{ $currentTheme == 'Sunset Gold' ? 'border-amber-500 bg-amber-50/40' : 'border-slate-200 bg-white' }} rounded-xl p-3 flex flex-col justify-between space-y-2 hover:border-amber-500 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                        <span class="w-3 h-3 rounded-full bg-amber-200"></span>
                                    </div>
                                    <input type="radio" name="theme" value="Sunset Gold" {{ $currentTheme == 'Sunset Gold' ? 'checked' : '' }} onchange="selectThemeCard(this)" class="text-amber-600 focus:ring-amber-500">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Sunset Gold</div>
                                    <div class="text-[10px] text-slate-500">Warm amber hour</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Client Access & Privacy Security: Checkbox PIN Protection, Input PIN, Checkbox Guest Download -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Client Access &amp; Privacy Security</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- PIN Protection Card -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-between space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <span class="text-xs font-bold text-slate-900">PIN Protection</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="pin_enabled" 
                                           value="1" 
                                           {{ old('pin_enabled', $wedding->pin_enabled) ? 'checked' : '' }} 
                                           class="w-4 h-4 rounded text-emerald-800 focus:ring-emerald-700">
                                </div>
                                <p class="text-[11px] text-slate-500">Tamu wajib memasukkan kode 4 digit untuk membuka galeri.</p>
                                <div class="flex items-center gap-2 pt-1">
                                    <span class="text-[11px] text-slate-600 font-semibold">Access PIN:</span>
                                    <input type="text" 
                                           name="pin" 
                                           id="access_pin_input"
                                           value="{{ old('pin', $wedding->pin ?? '1234') }}" 
                                           maxlength="4" 
                                           pattern="\d{4}"
                                           placeholder="1234"
                                           class="w-20 px-2 py-1 bg-white border border-slate-300 rounded font-mono text-center text-xs font-bold text-slate-900 focus:ring-1 focus:ring-emerald-700">
                                </div>
                            </div>

                            <!-- Guest Download Card -->
                            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 flex flex-col justify-between space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span class="text-xs font-bold text-slate-900">Guest Download</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="guest_download" 
                                           value="1" 
                                           {{ old('guest_download', $wedding->guest_download) ? 'checked' : '' }} 
                                           class="w-4 h-4 rounded text-emerald-800 focus:ring-emerald-700">
                                </div>
                                <p class="text-[11px] text-slate-500">Izinkan tamu mengunduh file foto resolusi tinggi secara asinkron tanpa reload.</p>
                                <div class="text-[10px] font-semibold text-emerald-800">
                                    &bull; Tombol unduh bersyarat aktif jika dicentang
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Romantic Quote: Input teks untuk kutipan romantis (quote) -->
                    <div>
                        <label for="quote" class="block text-xs font-semibold text-slate-700 mb-1">
                            Romantic Quote
                        </label>
                        <textarea id="quote" 
                                  name="quote" 
                                  rows="2" 
                                  class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:bg-white focus:ring-1 focus:ring-emerald-700 transition"
                                  placeholder="Two souls, one heart. Bersama melangkah dalam cinta selamanya.">{{ old('quote', $wedding->quote) }}</textarea>
                        <span class="text-[10px] text-slate-400 mt-1 block">Teks ini akan ditampilkan di tengah bagian Hero pada galeri tamu.</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Quick Info -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Status Card -->
                <div class="p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs space-y-4">
                    <h4 class="text-xs font-bold text-slate-900">Status Galeri</h4>
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Tautan Tamu:</span>
                            <a href="{{ route('client.pin', $wedding->slug) }}" target="_blank" class="text-emerald-700 font-semibold hover:underline flex items-center gap-1">
                                <span>Buka Galeri Tamu</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Total Foto Aktif:</span>
                            <span class="font-bold text-slate-900">{{ $wedding->photos()->whereNull('deleted_at')->count() }} Foto</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Total Album:</span>
                            <span class="font-bold text-slate-900">{{ $wedding->albums()->count() }} Album</span>
                        </div>
                    </div>
                </div>

                <!-- Client Live Preview -->
                <div class="p-5 bg-white border border-slate-200/90 rounded-2xl shadow-xs space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-900">Client Live Preview</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-semibold">Realtime</span>
                    </div>

                    <!-- Compact phone mockup -->
                    <div class="w-full max-w-[220px] mx-auto bg-slate-950 p-2 rounded-[28px] shadow-xl border-4 border-slate-800">
                        <div class="w-16 h-3 bg-slate-900 rounded-full mx-auto mb-1.5 flex items-center justify-center">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-800"></span>
                        </div>
                        <div id="phone-screen" class="text-white rounded-[20px] overflow-hidden min-h-[340px] flex flex-col justify-between text-center relative p-3" style="background-color: #0B1727;">
                            <div class="absolute inset-0 bg-cover bg-center opacity-25" style="background-image: url('{{ $wedding->cover_photo_url ?? 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=400&q=80' }}');"></div>
                            <div class="absolute inset-0 bg-gradient-to-t to-transparent" id="phone-overlay" style="background: linear-gradient(to top, #0B1727, rgba(11,23,39,0.8), transparent);"></div>

                            <div class="relative z-10 pt-3 space-y-1">
                                <div class="text-[8px] uppercase tracking-widest font-semibold" id="phone-accent-label" style="color: #6ee7b7;">The Wedding Of</div>
                                <h4 id="phone-couple-name" class="serif-font text-xl font-bold tracking-tight text-white leading-tight">{{ $wedding->couple_name }}</h4>
                                <div id="phone-event-date" class="text-[9px] text-slate-300">
                                    {{ $wedding->wedding_date ? \Carbon\Carbon::parse($wedding->wedding_date)->format('M d, Y') : 'Oct 14, 2026' }} &bull; Bali, ID
                                </div>
                            </div>

                            <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/20 p-2.5 rounded-lg my-3 space-y-1.5">
                                <div class="text-[8px] uppercase tracking-wider text-slate-300 font-semibold">Protected Album</div>
                                <div class="flex items-center justify-center gap-1 font-mono text-xs font-bold text-white">
                                    <span class="w-5 h-6 rounded bg-white/20 flex items-center justify-center pin-digit-0">{{ substr($wedding->pin ?? '1234', 0, 1) }}</span>
                                    <span class="w-5 h-6 rounded bg-white/20 flex items-center justify-center pin-digit-1">{{ substr($wedding->pin ?? '1234', 1, 1) }}</span>
                                    <span class="w-5 h-6 rounded bg-white/20 flex items-center justify-center pin-digit-2">{{ substr($wedding->pin ?? '1234', 2, 1) }}</span>
                                    <span class="w-5 h-6 rounded bg-white/20 flex items-center justify-center pin-digit-3">{{ substr($wedding->pin ?? '1234', 3, 1) }}</span>
                                </div>
                                <button type="button" id="phone-enter-btn" class="w-full py-1 rounded-md text-white text-[9px] font-bold shadow" style="background-color: #1B4D3E;">Enter Gallery</button>
                            </div>

                            <div class="relative z-10 text-left">
                                <div class="flex items-center justify-between text-[8px] text-slate-400 mb-1">
                                    <span>Recent Moments</span>
                                    <span>{{ $wedding->photos()->count() }} Photos</span>
                                </div>
                                <div class="grid grid-cols-3 gap-1">
                                    <div class="h-8 rounded bg-slate-800 overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=120&q=70" class="w-full h-full object-cover">
                                    </div>
                                    <div class="h-8 rounded bg-slate-800 overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=120&q=70" class="w-full h-full object-cover">
                                    </div>
                                    <div class="h-8 rounded bg-slate-800 overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1591604466107-ec97de577aff?auto=format&fit=crop&w=120&q=70" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const themeConfig = {
        'Elegance Emerald': {
            bg: '#0B1727',
            overlay: 'linear-gradient(to top, #0B1727, rgba(11,23,39,0.8), transparent)',
            accent: '#6ee7b7',
            btnBg: '#1B4D3E',
            cardBorder: ['border-emerald-800', 'bg-emerald-50/40'],
        },
        'Warm Minimalist': {
            bg: '#2C1810',
            overlay: 'linear-gradient(to top, #2C1810, rgba(44,24,16,0.85), transparent)',
            accent: '#F5C97A',
            btnBg: '#8C6D58',
            cardBorder: ['border-amber-700', 'bg-amber-50/40'],
        },
        'Modern Classic': {
            bg: '#0F0F0F',
            overlay: 'linear-gradient(to top, #0F0F0F, rgba(15,15,15,0.85), transparent)',
            accent: '#D4D4D4',
            btnBg: '#1C1C1C',
            cardBorder: ['border-zinc-900', 'bg-zinc-100'],
        },
        'Sunset Gold': {
            bg: '#1A0A00',
            overlay: 'linear-gradient(to top, #1A0A00, rgba(26,10,0,0.85), transparent)',
            accent: '#FCD34D',
            btnBg: '#B45309',
            cardBorder: ['border-amber-500', 'bg-amber-50/40'],
        },
    };

    function applyThemeToPreview(themeName) {
        const cfg = themeConfig[themeName] || themeConfig['Elegance Emerald'];
        const screen = document.getElementById('phone-screen');
        const overlay = document.getElementById('phone-overlay');
        const accentLabel = document.getElementById('phone-accent-label');
        const enterBtn = document.getElementById('phone-enter-btn');
        if (screen) screen.style.backgroundColor = cfg.bg;
        if (overlay) overlay.style.background = cfg.overlay;
        if (accentLabel) accentLabel.style.color = cfg.accent;
        if (enterBtn) enterBtn.style.backgroundColor = cfg.btnBg;
    }

    function updateCouplePreview(val) {
        const el = document.getElementById('phone-couple-name');
        if (el) el.textContent = val || 'Nama Pasangan';
    }

    function updateDatePreview(val) {
        if (!val) return;
        try {
            const d = new Date(val + 'T00:00:00');
            const el = document.getElementById('phone-event-date');
            if (el) el.textContent = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' • Bali, ID';
        } catch(e) {}
    }

    function selectThemeCard(radio) {
        document.querySelectorAll('#theme-selector-grid .theme-card').forEach(card => {
            card.classList.remove('border-2', 'border-emerald-800', 'bg-emerald-50/40', 'border-amber-700', 'bg-amber-50/40', 'border-zinc-900', 'bg-zinc-100', 'border-amber-500');
            card.classList.add('border', 'border-slate-200', 'bg-white');
        });

        const activeCard = radio.closest('.theme-card');
        if (activeCard) {
            activeCard.classList.remove('border', 'border-slate-200', 'bg-white');
            activeCard.classList.add('border-2');
            const cfg = themeConfig[radio.value];
            if (cfg && cfg.cardBorder) {
                cfg.cardBorder.forEach(cls => activeCard.classList.add(cls));
            }
        }

        // Update live preview
        applyThemeToPreview(radio.value);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Sync couple name input to preview
        const coupleInput = document.getElementById('couple_name');
        if (coupleInput) {
            coupleInput.addEventListener('input', e => updateCouplePreview(e.target.value));
        }
        // Sync date input to preview
        const dateInput = document.getElementById('wedding_date');
        if (dateInput) {
            dateInput.addEventListener('input', e => updateDatePreview(e.target.value));
        }
        // Apply current saved theme
        const checkedTheme = document.querySelector('input[name="theme"]:checked');
        if (checkedTheme) applyThemeToPreview(checkedTheme.value);
    });
</script>
@endpush
@endsection
