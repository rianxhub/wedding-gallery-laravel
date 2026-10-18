@extends('layouts.app')

@section('title', 'Wedding Gallery Details & Setup')
@section('breadcrumb', 'Tambah Pasangan Pengantin Baru')

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
    <form action="{{ route('weddings.store') }}" method="POST" id="wedding-setup-form">
        @csrf

        <!-- Top Header & Actions Bar (Figma Exact) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Wedding Gallery Details &amp; Setup</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        Active Gallery
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Configure client credentials, photography assets, Google Drive cloud sync, and presentation theme.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('weddings.index') }}" 
                   class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-xs transition">
                    Discard
                </a>

                <button type="submit" 
                        class="px-5 py-2 text-xs font-semibold text-white bg-[#1B4D3E] hover:bg-[#153C30] rounded-lg shadow-xs transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Publish Changes</span>
                </button>
            </div>
        </div>

        <!-- 2-Column Main Layout (Figma Exact) -->
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
                                <h3 class="font-bold text-slate-900 text-sm">Basic Information</h3>
                                <p class="text-[11px] text-slate-500">General client naming and public access identifiers.</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-semibold">
                            Core Metadata
                        </span>
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
                                   value="{{ old('couple_name', 'Andi & Sinta') }}" 
                                   required 
                                   oninput="updateCouplePreview(this.value)" 
                                   placeholder="Contoh: Andi & Sinta" 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-900 focus:bg-white focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 transition">
                            <span class="text-[10px] text-slate-400 mt-1 block">Appears prominently on the gallery invitation hero.</span>
                        </div>

                        <!-- Wedding Event Date -->
                        <div>
                            <label for="wedding_date" class="block text-xs font-semibold text-slate-700 mb-1">
                                Wedding Event Date <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" 
                                   id="wedding_date" 
                                   name="wedding_date" 
                                   value="{{ old('wedding_date', '2026-10-14') }}" 
                                   required 
                                   oninput="updateDatePreview(this.value)" 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-900 focus:bg-white focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 transition">
                            <span class="text-[10px] text-slate-400 mt-1 block">Sets chronology for digital countdown and RSVP timeline.</span>
                        </div>
                    </div>

                    <!-- Gallery Public URL Slug -->
                    <div>
                        <label for="slug" class="block text-xs font-semibold text-slate-700 mb-1">
                            Gallery Public URL Slug <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center">
                            <span class="px-3 py-2 bg-slate-100 border border-r-0 border-slate-200 rounded-l-lg text-xs font-mono text-slate-500">
                                {{ url('/') }}/
                            </span>
                            <input type="text" 
                                   id="slug" 
                                   name="slug" 
                                   value="{{ old('slug', 'andi-sinta') }}" 
                                   required 
                                   class="flex-1 px-3.5 py-2 bg-slate-50 border border-slate-200 text-xs font-mono font-semibold text-slate-900 focus:bg-white focus:ring-1 focus:ring-emerald-700 transition">
                            <button type="button" 
                                    onclick="copySlug()" 
                                    class="px-3.5 py-2 bg-white border border-l-0 border-slate-200 rounded-r-lg text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">
                                Copy
                            </button>
                        </div>
                    </div>

                    <!-- Location / Venue -->
                    <div>
                        <label for="location_venue" class="block text-xs font-semibold text-slate-700 mb-1">
                            Location / Venue
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                📍
                            </span>
                            <input type="text" 
                                   id="location_venue" 
                                   name="location_venue" 
                                   value="The Glasshouse Garden, Bali" 
                                   class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:bg-white focus:ring-1 focus:ring-emerald-700 transition">
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block">Formatted with map geolocation coordinates in the guest portal.</span>
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
                                <h3 class="font-bold text-slate-900 text-sm">Cloud Asset Integration</h3>
                                <p class="text-[11px] text-slate-500">Synchronize high-resolution source RAWs &amp; processed JPGs via Google Drive API.</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[10px] font-semibold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                            Connected to GDrive
                        </span>
                    </div>

                    <!-- Google Drive Folder ID / URI -->
                    <div>
                        <label for="drive_folder" class="block text-xs font-semibold text-slate-700 mb-1">
                            Google Drive Folder ID / URI <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <div class="relative flex-1">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    📁
                                </span>
                                <input type="text" 
                                       id="drive_folder" 
                                       name="drive_folder" 
                                       value="{{ old('drive_folder', '1Wpen5a0DPcl1tVnkBdUynv44_9GgimcP') }}" 
                                       placeholder="Tempel link Google Drive atau ID folder" 
                                       class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono font-medium text-slate-800 focus:bg-white focus:ring-1 focus:ring-emerald-700 transition">
                            </div>
                            <button type="button" 
                                    onclick="alert('Koneksi Google Drive terverifikasi dan siap disinkronkan!')" 
                                    class="px-3.5 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-emerald-800 rounded-lg text-xs font-semibold shadow-xs transition flex items-center gap-1.5 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span>Test Connection</span>
                            </button>
                        </div>
                        <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500">
                            <span>Detected 420 High-Res files (12.4 GB)</span>
                            <span class="text-slate-400">Last automated poll: just now</span>
                        </div>
                    </div>

                    <!-- Auto-sync Toggle -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-slate-900">Auto-sync new photos from folder</div>
                            <div class="text-[11px] text-slate-500">Automatically ingest and generate optimized WebP responsive variants when new files hit Drive.</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="auto_sync" checked class="sr-only peer">
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#1B4D3E]"></div>
                        </label>
                    </div>
                </div>

                <!-- Section 3: Visual Presentation & Media -->
                <div class="p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-800 flex items-center justify-center font-bold text-xs">
                                3
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Visual Presentation &amp; Media</h3>
                                <p class="text-[11px] text-slate-500">Editorial cover artwork, curation theme, and guest download permissions.</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-semibold">
                            Art Direction
                        </span>
                    </div>

                    <!-- Gallery Hero Cover Photo -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Gallery Hero Cover Photo <span class="text-rose-500">*</span>
                        </label>
                        <div class="p-4 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50 flex flex-col sm:flex-row items-center gap-4">
                            <div class="w-28 h-20 rounded-lg overflow-hidden bg-slate-800 border border-slate-200 flex-shrink-0 shadow-sm relative group">
                                <img id="preview-cover-img" 
                                     src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=400&q=80" 
                                     alt="Cover" 
                                     class="w-full h-full object-cover">
                                <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded font-mono">
                                    Current Cover
                                </span>
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <div class="text-xs font-bold text-slate-800 flex items-center justify-center sm:justify-start gap-1.5">
                                    <span>☁️</span>
                                    <span>Drag &amp; drop high-res cover photo here</span>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">Supports JPEG, PNG, or Google Drive File ID. Recommended aspect ratio 16:9.</div>
                                <div class="mt-2.5 flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                    <input type="text" 
                                           id="cover_drive_file_id" 
                                           name="cover_drive_file_id" 
                                           placeholder="ID File Google Drive untuk cover" 
                                           value="{{ old('cover_drive_file_id') }}" 
                                           class="px-2.5 py-1 text-xs bg-white border border-slate-200 rounded font-mono text-slate-800 w-52">
                                    <span class="text-[10px] text-slate-400">atau link Drive</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Theme Preset (4 Selectable Cards in Figma Spec) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-semibold text-slate-700">Visual Theme Preset</label>
                            <span class="text-[11px] text-emerald-800 font-semibold cursor-pointer">⚙️ Open Advanced Customizer</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="theme-selector-grid">
                            <!-- Theme 1: Elegance Emerald -->
                            <label class="theme-card cursor-pointer border-2 border-emerald-800 rounded-xl p-3 bg-emerald-50/40 flex flex-col justify-between space-y-2 hover:border-emerald-700 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-[#1B4D3E]"></span>
                                        <span class="w-3 h-3 rounded-full bg-[#34D399]"></span>
                                    </div>
                                    <input type="radio" name="theme" value="Elegance Emerald" {{ old('theme', 'Elegance Emerald') == 'Elegance Emerald' ? 'checked' : '' }} onchange="selectThemeCard(this)" class="text-emerald-800 focus:ring-emerald-700">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Elegance Emerald</div>
                                    <div class="text-[10px] text-slate-500">Deep botanical slate</div>
                                </div>
                            </label>

                            <!-- Theme 2: Warm Minimalist -->
                            <label class="theme-card cursor-pointer border border-slate-200 rounded-xl p-3 bg-white flex flex-col justify-between space-y-2 hover:border-amber-600 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-[#8C6D58]"></span>
                                        <span class="w-3 h-3 rounded-full bg-[#E5DFD7]"></span>
                                    </div>
                                    <input type="radio" name="theme" value="Warm Minimalist" {{ old('theme') == 'Warm Minimalist' ? 'checked' : '' }} onchange="selectThemeCard(this)" class="text-amber-800 focus:ring-amber-700">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Warm Minimalist</div>
                                    <div class="text-[10px] text-slate-500">Earth, travertine stone</div>
                                </div>
                            </label>

                            <!-- Theme 3: Modern Classic -->
                            <label class="theme-card cursor-pointer border border-slate-200 rounded-xl p-3 bg-white flex flex-col justify-between space-y-2 hover:border-zinc-800 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-slate-900"></span>
                                        <span class="w-3 h-3 rounded-full bg-slate-300"></span>
                                    </div>
                                    <input type="radio" name="theme" value="Modern Classic" {{ old('theme') == 'Modern Classic' ? 'checked' : '' }} onchange="selectThemeCard(this)" class="text-slate-900 focus:ring-slate-800">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Modern Classic</div>
                                    <div class="text-[10px] text-slate-500">Editorial monochrome</div>
                                </div>
                            </label>

                            <!-- Theme 4: Sunset Gold -->
                            <label class="theme-card cursor-pointer border border-slate-200 rounded-xl p-3 bg-white flex flex-col justify-between space-y-2 hover:border-amber-500 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                        <span class="w-3 h-3 rounded-full bg-amber-200"></span>
                                    </div>
                                    <input type="radio" name="theme" value="Sunset Gold" {{ old('theme') == 'Sunset Gold' ? 'checked' : '' }} onchange="selectThemeCard(this)" class="text-amber-600 focus:ring-amber-500">
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-xs">Sunset Gold</div>
                                    <div class="text-[10px] text-slate-500">Warm amber hour</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Client Access & Privacy Security (Figma Exact) -->
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
                                    <input type="checkbox" name="pin_enabled" value="1" checked class="w-4 h-4 rounded text-emerald-800 focus:ring-emerald-700">
                                </div>
                                <p class="text-[11px] text-slate-500">Guests must enter 4-digit code to view album.</p>
                                <div class="flex items-center gap-2 pt-1">
                                    <span class="text-[11px] text-slate-600 font-semibold">Access PIN:</span>
                                    <input type="text" 
                                           name="pin" 
                                           id="access_pin_input"
                                           value="{{ old('pin', '1234') }}" 
                                           maxlength="4" 
                                           pattern="\d{4}"
                                           placeholder="1234"
                                           oninput="updatePinPreview(this.value)"
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
                                    <input type="checkbox" name="guest_download" value="1" checked class="w-4 h-4 rounded text-emerald-800 focus:ring-emerald-700">
                                </div>
                                <p class="text-[11px] text-slate-500">Allow attendees to download original full-resolution RAW/JPG files directly.</p>
                                <div class="text-[10px] font-semibold text-emerald-800">
                                    &bull; Watermark Branding Configured
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Romantic Quote -->
                    <div>
                        <label for="quote" class="block text-xs font-semibold text-slate-700 mb-1">
                            Romantic Quote
                        </label>
                        <textarea id="quote" 
                                  name="quote" 
                                  rows="2" 
                                  class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:bg-white focus:ring-1 focus:ring-emerald-700 transition"
                                  placeholder="Two souls, one heart. Bersama melangkah dalam cinta selamanya.">{{ old('quote', 'Two souls with but a single thought, two hearts that beat as one.') }}</textarea>
                    </div>

                    <!-- User Account / Vendor -->
                    @if(isset($users) && $users->count() > 0)
                        <div>
                            <label for="user_id" class="block text-xs font-semibold text-slate-700 mb-1">
                                Akun Vendor / Admin Pengelola
                            </label>
                            <select id="user_id" 
                                    name="user_id" 
                                    class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-800 focus:bg-white focus:ring-1 focus:ring-emerald-700 transition">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $defaultUser?->id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Interactive Live Preview & Status (4 Cols) (Figma Exact) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Client Live Preview Device Frame -->
                <div class="p-6 bg-white border border-slate-200/90 rounded-2xl shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-900">Client Live Preview</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-semibold">
                            Realtime
                        </span>
                    </div>

                    <!-- Mobile Mockup Frame -->
                    <div class="w-full max-w-[280px] mx-auto bg-slate-950 p-2.5 rounded-[36px] shadow-2xl border-4 border-slate-800 relative">
                        <!-- Top Speaker / Dynamic Island -->
                        <div class="w-20 h-4 bg-slate-900 rounded-full mx-auto mb-2 flex items-center justify-center">
                            <span class="w-2 h-2 rounded-full bg-slate-800"></span>
                        </div>

                        <!-- Screen Content Canvas (Theme Reactive) -->
                        <div id="phone-screen" class="text-white rounded-[26px] overflow-hidden min-h-[460px] flex flex-col justify-between text-center relative p-4" style="background-color: #0B1727;">
                            <!-- Background overlay image -->
                            <div class="absolute inset-0 bg-cover bg-center opacity-30" 
                                 id="phone-bg"
                                 style="background-image: url('https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=400&q=80');"></div>
                            <div class="absolute inset-0 bg-gradient-to-t to-transparent" id="phone-overlay" style="background: linear-gradient(to top, #0B1727, rgba(11,23,39,0.8), transparent);"></div>

                            <!-- Screen Header -->
                            <div class="relative z-10 pt-4 space-y-2">
                                <div class="text-[9px] uppercase tracking-widest font-semibold" id="phone-accent-label" style="color: #6ee7b7;">
                                    The Wedding Of
                                </div>
                                <h4 id="phone-couple-name" class="serif-font text-2xl font-bold tracking-tight text-white leading-tight">
                                    Andi &amp; Sinta
                                </h4>
                                <div id="phone-event-date" class="text-[10px] text-slate-300">
                                    Oct 14, 2026 &bull; Bali, ID
                                </div>
                            </div>

                            <!-- Screen PIN Protection Card -->
                            <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/20 p-3.5 rounded-xl my-4 space-y-2">
                                <div class="text-[9px] uppercase tracking-wider text-slate-300 font-semibold">
                                    Protected Album
                                </div>
                                <div class="flex items-center justify-center gap-1.5 font-mono text-sm font-bold text-white">
                                    <span class="w-6 h-7 rounded bg-white/20 flex items-center justify-center pin-digit-0">1</span>
                                    <span class="w-6 h-7 rounded bg-white/20 flex items-center justify-center pin-digit-1">2</span>
                                    <span class="w-6 h-7 rounded bg-white/20 flex items-center justify-center pin-digit-2">3</span>
                                    <span class="w-6 h-7 rounded bg-white/20 flex items-center justify-center pin-digit-3">4</span>
                                </div>
                                <button type="button" id="phone-enter-btn" class="w-full py-1.5 rounded-lg text-white text-[10px] font-bold shadow" style="background-color: #1B4D3E;">
                                    Enter Gallery
                                </button>
                            </div>

                            <!-- Screen Footer Preview Thumbnails -->
                            <div class="relative z-10 text-left">
                                <div class="flex items-center justify-between text-[9px] text-slate-400 mb-1.5">
                                    <span>Recent Stream</span>
                                    <span id="phone-photo-count">420 Photos</span>
                                </div>
                                <div class="grid grid-cols-3 gap-1">
                                    <div class="h-10 rounded-lg bg-slate-800 overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=200&q=80" class="w-full h-full object-cover">
                                    </div>
                                    <div class="h-10 rounded-lg bg-slate-800 overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=200&q=80" class="w-full h-full object-cover">
                                    </div>
                                    <div class="h-10 rounded-lg bg-slate-800 overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1591604466107-ec97de577aff?auto=format&fit=crop&w=200&q=80" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                  
@push('scripts')
<script>
    // -------------------------------------------------------
    // Theme configurations for live preview
    // -------------------------------------------------------
    const themeConfig = {
        'Elegance Emerald': {
            bg: '#0B1727',
            overlay: 'linear-gradient(to top, #0B1727, rgba(11,23,39,0.8), transparent)',
            accent: '#6ee7b7',
            btnBg: '#1B4D3E',
            label: 'The Wedding Of',
            cardBorder: ['border-emerald-800', 'bg-emerald-50/40'],
        },
        'Warm Minimalist': {
            bg: '#2C1810',
            overlay: 'linear-gradient(to top, #2C1810, rgba(44,24,16,0.85), transparent)',
            accent: '#F5C97A',
            btnBg: '#8C6D58',
            label: 'The Wedding Of',
            cardBorder: ['border-amber-700', 'bg-amber-50/40'],
        },
        'Modern Classic': {
            bg: '#0F0F0F',
            overlay: 'linear-gradient(to top, #0F0F0F, rgba(15,15,15,0.85), transparent)',
            accent: '#D4D4D4',
            btnBg: '#1C1C1C',
            label: 'The Wedding Of',
            cardBorder: ['border-zinc-900', 'bg-zinc-100'],
        },
        'Sunset Gold': {
            bg: '#1A0A00',
            overlay: 'linear-gradient(to top, #1A0A00, rgba(26,10,0,0.85), transparent)',
            accent: '#FCD34D',
            btnBg: '#B45309',
            label: 'The Wedding Of',
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
        // Auto slug
        const slug = (val || '')
            .toLowerCase()
            .trim()
            .replace(/&/g, 'dan')
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        const slugEl = document.getElementById('slug');
        if (slugEl) slugEl.value = slug;
    }

    function updateDatePreview(val) {
        if (!val) return;
        try {
            const d = new Date(val + 'T00:00:00');
            const el = document.getElementById('phone-event-date');
            if (el) el.textContent = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + ' • Bali, ID';
        } catch(e) {}
    }

    function updatePinPreview(pin) {
        const digits = (pin + '0000').slice(0, 4).split('');
        for (let i = 0; i < 4; i++) {
            const el = document.querySelector('.pin-digit-' + i);
            if (el) el.textContent = digits[i] || '0';
        }
    }

    function selectThemeCard(radio) {
        // Reset all cards
        document.querySelectorAll('#theme-selector-grid .theme-card').forEach(card => {
            card.className = card.className
                .replace(/border-emerald-800|bg-emerald-50\/40|border-amber-700|border-zinc-900|bg-zinc-100|border-amber-500|bg-amber-50\/40|border-2\s*/g, '')
                .trim();
            // Ensure base classes
            if (!card.classList.contains('border')) card.classList.add('border');
            if (!card.classList.contains('border-slate-200')) card.classList.add('border-slate-200');
            if (!card.classList.contains('bg-white')) card.classList.add('bg-white');
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

        // Update the live preview colors
        applyThemeToPreview(radio.value);
    }

    // Initialize preview on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial couple name from input
        const coupleInput = document.getElementById('couple_name');
        if (coupleInput && coupleInput.value) {
            updateCouplePreview(coupleInput.value);
        }
        // Set initial date
        const dateInput = document.getElementById('wedding_date');
        if (dateInput && dateInput.value) {
            updateDatePreview(dateInput.value);
        }
        // Set initial PIN
        const pinInput = document.getElementById('access_pin_input');
        if (pinInput && pinInput.value) {
            updatePinPreview(pinInput.value);
        }
        // Apply initial theme (Elegance Emerald default)
        const checkedTheme = document.querySelector('input[name="theme"]:checked');
        applyThemeToPreview(checkedTheme ? checkedTheme.value : 'Elegance Emerald');
    });
</script>
@endpush
@endsection
