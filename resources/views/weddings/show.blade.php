@extends('layouts.app')

@section('title', $wedding->couple_name . ' - Wedding Gallery')
@section('footer_couple_name', $wedding->couple_name)
@section('is_public_gallery', true)

@section('content')
<!-- ============================================================ -->
<!-- GALLERY TAB CONTENT                                          -->
<!-- ============================================================ -->
<div id="tab-gallery" class="space-y-12">
    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    <span class="font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-xs font-bold">&times;</button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    <span>{{ session('info') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-blue-700 hover:text-blue-900 text-xs font-bold">&times;</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="p-5 rounded-2xl bg-rose-50 border border-rose-200 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                        ⚠️
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-rose-900 text-sm">Folder Google Drive Membutuhkan Izin Akses</h3>
                        <p class="text-xs text-rose-700 mt-1 leading-relaxed">{{ session('error') }}</p>

                        <div class="mt-4 p-4 bg-white border border-rose-200/80 rounded-xl space-y-3 text-xs">
                            <div class="font-bold text-slate-800">💡 Cara Membuka Akses Folder Google Drive:</div>
                            <div class="p-3 bg-emerald-50/70 border border-emerald-200/80 rounded-lg text-emerald-900">
                                <strong>🟢 Opsi Paling Mudah (Link Publik):</strong> Buka folder di Google Drive &rarr; Klik <em>Bagikan (Share)</em> &rarr; Ubah Akses umum menjadi <strong>Siapa saja yang memiliki link</strong> (sebagai <strong>Pelihat / Viewer</strong>).
                            </div>
                            <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 flex flex-wrap items-center justify-between gap-2">
                                <span><strong>🔒 Opsi Service Account:</strong> Tambahkan email Service Account sebagai Pelihat:</span>
                                <div class="flex items-center gap-2">
                                    <code class="px-2 py-1 bg-white border border-slate-200 rounded font-mono text-[11px] text-slate-800">
                                        {{ $serviceAccountEmail ?? 'gallery-wedding@gallery-wedding-508713.iam.gserviceaccount.com' }}
                                    </code>
                                    <button type="button" 
                                            onclick="navigator.clipboard.writeText('{{ $serviceAccountEmail ?? 'gallery-wedding@gallery-wedding-508713.iam.gserviceaccount.com' }}'); alert('Email Service Account disalin!');" 
                                            class="px-2.5 py-1 bg-slate-200 hover:bg-slate-300 text-slate-800 rounded font-semibold text-[11px]">
                                        Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Folder Updater -->
                        <form action="{{ route('weddings.update-folder', $wedding->slug) }}" method="POST" class="mt-4 flex flex-wrap gap-2">
                            @csrf
                            <input type="text" 
                                   name="drive_folder" 
                                   value="{{ $wedding->drive_folder_id }}" 
                                   placeholder="Tempel Link / ID Folder Google Drive" 
                                   required 
                                   class="flex-1 min-w-[280px] px-3.5 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono text-slate-900 focus:ring-1 focus:ring-emerald-700">
                            <button type="submit" class="px-4 py-2 bg-[#1B4D3E] hover:bg-[#153C30] text-white rounded-lg text-xs font-semibold shadow-xs">
                                💾 Simpan &amp; Sinkronkan Ulang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Hero Section (Figma Exact: Home & Gallery View - Andi & Sinta) -->
    @php
        $heroBackground = 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=1920&q=80';
        if ($wedding->photos->count() > 0) {
            $heroBackground = $wedding->photos->first()->thumbnail_url;
        }
    @endphp
    <section class="relative w-full min-h-[520px] lg:min-h-[580px] flex items-center justify-center overflow-hidden bg-slate-950">
        <!-- Background Image with Dark Atmospheric Vignette -->
        <div class="absolute inset-0 bg-cover bg-center opacity-35 scale-105 transform duration-1000 ease-out" 
             style="background-image: url('{{ $heroBackground }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#0B1727] via-[#0B1727]/60 to-transparent"></div>
        <div class="absolute inset-0 bg-radial from-transparent via-[#0B1727]/40 to-[#0B1727]/80"></div>

        <!-- Hero Content Center -->
        <div class="relative z-10 max-w-4xl mx-auto px-4 py-16 text-center text-white space-y-4">
            <!-- Badge: The Wedding Celebration -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-semibold tracking-wider uppercase text-emerald-200">
                <svg class="w-3.5 h-3.5 text-emerald-300 fill-current" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span>The Wedding Celebration</span>
            </div>

            <!-- Couple Name Title -->
            <h1 class="serif-font text-5xl sm:text-6xl md:text-7xl font-bold tracking-tight text-white leading-tight">
                {{ $wedding->couple_name }}
            </h1>

            <!-- Subtitle -->
            <div class="serif-font italic text-lg sm:text-xl text-slate-300 font-light">
                A Celebration of Love
            </div>

            <!-- Event Date & Venue -->
            <div class="flex flex-wrap items-center justify-center gap-3 text-xs sm:text-sm text-slate-300 pt-2 font-medium">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $wedding->wedding_date ? \Carbon\Carbon::parse($wedding->wedding_date)->translatedFormat('F d, Y') : 'October 14, 2026' }}
                </span>
                <span class="text-slate-500">&bull;</span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    The Glasshouse Garden, Bali
                </span>
            </div>

            <!-- Romantic Quote -->
            @if($wedding->quote)
                <div class="pt-3 max-w-xl mx-auto">
                    <p class="italic text-slate-300 text-xs sm:text-sm font-light leading-relaxed">
                        &ldquo;{{ $wedding->quote }}&rdquo;
                    </p>
                </div>
            @endif
        </div>
    </section>

    <!-- Filter Pills & Search Bar (Figma Exact) -->
    <section class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 py-2 border-b border-slate-200">
            <!-- Left: Album Filter Pills (Sesuai Folder Asli Google Drive) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0 scrollbar-none">
                <button type="button" 
                        onclick="filterByAlbum('all', this)" 
                        class="album-tab active px-4 py-1.5 rounded-full text-xs font-semibold bg-[#1B4D3E] text-white shadow-xs transition flex items-center gap-1.5 flex-shrink-0">
                    <span>All Photos</span>
                    <span class="album-badge px-1.5 py-0.5 rounded-full bg-white/20 text-[10px] text-white font-bold">{{ $wedding->photos->count() }}</span>
                </button>

                @foreach($wedding->albums as $album)
                    <button type="button" 
                            onclick="filterByAlbum('{{ $album->id }}', this)" 
                            class="album-tab px-4 py-1.5 rounded-full text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition flex items-center gap-1.5 flex-shrink-0">
                        <span>{{ $album->album_name }}</span>
                        <span class="album-badge px-1.5 py-0.5 rounded-full bg-slate-200 text-slate-700 text-[10px] font-bold">{{ $album->photos->count() }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Right: Search & View Modes -->
            <div class="flex items-center gap-3">
                <div class="relative flex-1 sm:w-72">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" 
                           id="gallery-search" 
                           placeholder="Search moments, tags, guest names... ⌘K" 
                           class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-emerald-700">
                </div>

                <div class="hidden sm:flex items-center gap-1 text-slate-400 border border-slate-200 rounded-lg p-1 bg-white">
                    <button type="button" class="p-1 rounded bg-slate-100 text-slate-800" title="Grid 4 Kolom">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button type="button" class="p-1 rounded text-slate-400 hover:text-slate-800" title="Masonry List">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- The Curated Collection Header & Sort -->
    <section class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">The Curated Collection</h2>
                <p class="text-xs text-slate-500 mt-0.5">Captured with natural illumination &amp; medium format film optics</p>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-medium">Sort by:</span>
                <select class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:ring-1 focus:ring-emerald-700">
                    <option>Chronological (Ceremony First)</option>
                    <option>Highlights First</option>
                    <option>Recently Added</option>
                </select>
            </div>
        </div>

        <!-- Trashed Photos Notification Bar (Preserved Feature) -->
        @if(($trashedPhotosCount ?? 0) > 0)
            <div class="mb-6 p-3.5 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs flex items-center justify-between gap-3 shadow-2xs">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>Terdapat <strong>{{ $trashedPhotosCount }} foto</strong> yang terhapus dari Google Drive dan otomatis disembunyikan dari galeri publik tamu ini.</span>
                </div>
                <a href="{{ route('weddings.trash', $wedding->slug) }}" class="font-semibold text-amber-800 underline hover:text-amber-950 whitespace-nowrap">
                    Buka Riwayat Hapus &rarr;
                </a>
            </div>
        @endif

        <!-- Photo Grid (Figma Exact: 4-Column Editorial Cards) -->
        @if($wedding->photos->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5" id="photo-cards-container">
                @foreach($wedding->photos as $index => $photo)
                    @php
                        $photoLabel = $photo->nama_file;
                        // Clean file extension for editorial display
                        $cleanLabel = preg_replace('/\.[^.]+$/', '', $photoLabel);
                        $cleanLabel = str_replace(['_', '-'], ' ', $cleanLabel);
                        $formattedIndex = '#' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                        $isVideo = str_starts_with($photo->mime_type ?? '', 'video/');
                    @endphp
                    <div class="gallery-photo-card group bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between cursor-pointer"
                         data-label="{{ strtolower($cleanLabel) }} {{ strtolower($photo->album?->album_name ?? '') }}"
                         data-album-id="{{ $photo->album_id ?? 'root' }}"
                         data-index="{{ $index }}"
                         data-src="{{ $photo->thumbnail_url }}"
                         data-title="{{ $cleanLabel }}"
                         data-num="{{ $formattedIndex }}"
                         data-file-id="{{ $photo->drive_file_id }}"
                         data-is-video="{{ $isVideo ? '1' : '0' }}"
                         data-download-url="{{ route('weddings.download', ['slug' => $wedding->slug, 'fileId' => $photo->drive_file_id]) }}">
                        
                        <!-- Image Container with Hover Zoom & Action Overlay -->
                        <div class="relative aspect-4/3 sm:aspect-square bg-slate-900 overflow-hidden">
                            <img src="{{ $photo->thumbnail_url }}" 
                                 alt="{{ $photo->nama_file }}" 
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                 onerror="this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80'">

                            @if($isVideo)
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <div class="w-11 h-11 rounded-full bg-black/60 backdrop-blur-sm flex items-center justify-center shadow-lg border-2 border-white/70 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <span class="absolute top-2 left-2 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-black/70 text-white backdrop-blur-sm pointer-events-none">VIDEO</span>
                            @endif

                            <!-- Hover Overlay Action Buttons -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-between p-3">
                                <span class="px-2 py-1 rounded bg-black/50 text-[10px] text-white font-mono backdrop-blur-xs">
                                    {{ $formattedIndex }}
                                </span>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" 
                                            onclick="openLightbox({{ $index }})" 
                                            class="w-8 h-8 rounded-full bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center shadow transition" 
                                            title="Buka Layar Penuh">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                        </svg>
                                    </button>
                                    <a href="{{ route('weddings.download', ['slug' => $wedding->slug, 'fileId' => $photo->drive_file_id]) }}" 
                                       onclick="handleDownloadWithDeviceTime(event, this)" 
                                       class="w-8 h-8 rounded-full bg-emerald-800 hover:bg-emerald-900 text-white flex items-center justify-center shadow transition" 
                                       title="Unduh Foto High-Res">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Card Editorial Footer (Figma Exact) -->
                        <div class="p-3 bg-white border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-800 truncate pr-2" title="{{ $cleanLabel }}">
                                {{ Str::limit($cleanLabel, 20) }}
                            </span>
                            <span class="font-mono text-slate-400 font-bold text-[11px] flex-shrink-0">
                                {{ $formattedIndex }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Gallery Bottom Action Bar (Figma Exact) -->
            <div class="mt-10 flex flex-wrap items-center justify-center gap-3">
                <button type="button" 
                        onclick="alert('Seluruh foto galeri telah dimuat sempurna!')" 
                        class="px-5 py-2.5 rounded-full border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold shadow-2xs transition flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Load More Photos ({{ $wedding->photos->count() }} Loaded)</span>
                </button>

                <a href="https://drive.google.com/drive/folders/{{ $wedding->drive_folder_id }}" 
                   target="_blank" 
                   class="px-5 py-2.5 rounded-full bg-blue-50 text-blue-800 hover:bg-blue-100 border border-blue-200 text-xs font-semibold shadow-2xs transition flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Download High-Res ZIP</span>
                </a>
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl mx-auto mb-3">
                    📸
                </div>
                <h3 class="font-bold text-slate-900 text-base">Belum Ada Foto Tersinkron di Galeri Ini</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-4">
                    Folder Google Drive terhubung: <code>{{ $wedding->drive_folder_id }}</code>.
                    Klik tombol sinkronisasi di bawah untuk mengambil foto-foto dari Google Drive.
                </p>
                <a href="{{ route('weddings.sync', $wedding->slug) }}" class="btn-emerald text-xs font-semibold px-4 py-2 rounded-lg inline-flex items-center gap-2">
                    <span>🔄 Sinkronkan Foto Sekarang</span>
                </a>
            </div>
        @endif
    </section>

    <!-- Administrative Management & Audit Logs Section (Rule #2 & #3) -->
    <section class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="p-6 bg-white border border-slate-200 rounded-2xl shadow-xs space-y-6">
            <!-- Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                        <span>⚙️ Studio Cloud Storage &amp; Guest Activity</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Pengaturan Google Drive, sinkronisasi otomatis, dan pencatatan realtime unduhan tamu ke database MySQL.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('weddings.edit', $wedding->slug) }}" class="px-3.5 py-2 text-xs font-semibold text-slate-800 bg-amber-100 hover:bg-amber-200 border border-amber-300 rounded-lg flex items-center gap-1.5 shadow-xs transition">
                        <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>✏️ Edit Proyek</span>
                    </a>
                    <a href="{{ url('/' . $wedding->slug) }}" target="_blank" class="px-3.5 py-2 text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 rounded-lg flex items-center gap-1.5 shadow-xs transition">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>👁️ Buka Tampilan Tamu</span>
                    </a>
                    <a href="{{ route('weddings.sync', $wedding->slug) }}" class="btn-emerald text-xs font-semibold px-3.5 py-2 rounded-lg flex items-center gap-1.5 shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>🔄 Sinkronkan Foto Drive</span>
                    </a>
                    <a href="https://drive.google.com/drive/folders/{{ $wedding->drive_folder_id }}" target="_blank" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-xs transition">
                        Buka Google Drive &rarr;
                    </a>
                </div>
            </div>

            <!-- Ganti Folder Form -->
            <form action="{{ route('weddings.update-folder', $wedding->slug) }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                @csrf
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Link atau ID Folder Google Drive:</label>
                    <input type="text" 
                           name="drive_folder" 
                           value="{{ $wedding->drive_folder_id }}" 
                           required 
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono text-slate-800 focus:bg-white focus:ring-1 focus:ring-emerald-700 transition">
                </div>
                <div class="sm:self-end">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold shadow-xs">
                        💾 Perbarui Folder &amp; Sinkronkan
                    </button>
                </div>
            </form>

            <!-- Realtime Download Logs Table -->
            <div class="pt-2">
                <div class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 flex items-center justify-between">
                    <span>📥 15 Log Unduhan Terakhir (Database MySQL Tracking)</span>
                    <a href="{{ route('logs.index') }}" class="text-emerald-800 hover:underline font-semibold text-xs lowercase">
                        lihat seluruh riwayat &rarr;
                    </a>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3">Log ID</th>
                                <th class="py-2.5 px-3">Nama File</th>
                                <th class="py-2.5 px-3">Drive File ID</th>
                                <th class="py-2.5 px-3">IP Address Tamu</th>
                                <th class="py-2.5 px-3">Waktu Unduh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($wedding->downloadLogs as $log)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-2.5 px-3 font-mono text-slate-400">#{{ $log->id }}</td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $log->file_name }}</td>
                                    <td class="py-2.5 px-3 font-mono text-[11px] text-slate-500">{{ Str::limit($log->drive_file_id, 16) }}</td>
                                    <td class="py-2.5 px-3">
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-mono text-[10px]">
                                            {{ $log->ip_address ?? '127.0.0.1' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <div class="device-time-display font-medium text-slate-800" data-iso="{{ $log->downloaded_at ? $log->downloaded_at->toISOString() : '' }}">
                                            {{ $log->downloaded_at_formatted }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-slate-400">
                                        Belum ada data unduhan untuk galeri pernikahan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ============================================================ -->
<!-- CLIENT TAB CONTENT                                           -->
<!-- ============================================================ -->
<div id="tab-client" class="hidden">
    <!-- Client Tab Hero Banner -->
    <section class="relative w-full bg-gradient-to-br from-[#0B1727] via-[#112338] to-[#0B1727] py-14 px-4 text-center overflow-hidden">
        <!-- Decorative circles -->
        <div class="absolute -top-16 -left-16 w-64 h-64 rounded-full bg-emerald-900/20 blur-3xl"></div>
        <div class="absolute -bottom-16 -right-16 w-72 h-72 rounded-full bg-emerald-800/20 blur-3xl"></div>
        <div class="relative z-10 max-w-2xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 mb-5 rounded-full bg-white/10 backdrop-blur border border-white/20 text-emerald-200 text-xs font-semibold tracking-widest uppercase">
                <svg class="w-3.5 h-3.5 text-emerald-300 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                Client Gallery Portal
            </div>
            <h1 class="serif-font text-4xl sm:text-5xl font-bold text-white mb-3 leading-tight">{{ $wedding->couple_name }}</h1>
            <p class="text-slate-300 text-sm sm:text-base font-light mb-2">
                Your complete wedding gallery, organized by album and ready to download.
            </p>
            @if($wedding->wedding_date)
            <div class="flex items-center justify-center gap-2 text-emerald-300 text-xs font-medium mt-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $wedding->wedding_date->translatedFormat('F d, Y') }}
                @if($wedding->theme)
                <span class="text-slate-500">•</span>
                <span>{{ $wedding->theme }}</span>
                @endif
            </div>
            @endif
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-6 py-4 text-sm">
                <div class="flex items-center gap-2 text-slate-600">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="font-bold text-slate-900">{{ $wedding->photos->count() }}</span>
                    <span class="text-slate-500 text-xs">Total Photos</span>
                </div>
                <div class="flex items-center gap-2 text-slate-600">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span class="font-bold text-slate-900">{{ $wedding->albums->count() ?: 1 }}</span>
                    <span class="text-slate-500 text-xs">Albums / Folders</span>
                </div>
                <div class="flex items-center gap-2 text-slate-600">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span class="font-bold text-slate-900">High-Res</span>
                    <span class="text-slate-500 text-xs">Download Ready</span>
                </div>
                <!-- Drive Folder Link -->
                <div class="ml-auto">
                    <a href="https://drive.google.com/drive/folders/{{ $wedding->drive_folder_id }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#1B4D3E] hover:bg-[#153C30] text-white text-xs font-semibold shadow transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download All (Google Drive)
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Albums / Folders Grid -->
    <section class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-10">

        @if($wedding->albums->count() > 0)
            <!-- Albums are defined: show each album as a folder card with its photos -->
            <div class="space-y-12">
                @foreach($wedding->albums as $albumIndex => $album)
                    @php
                        // Get photos matching this album name from wedding photos
                        $albumPhotos = $wedding->photos->filter(function($p) use ($album) {
                            return stripos($p->nama_file, $album->album_name) !== false;
                        });
                        if ($albumPhotos->isEmpty()) {
                            // fallback: split photos evenly per album
                            $perAlbum = max(1, (int)ceil($wedding->photos->count() / max(1, $wedding->albums->count())));
                            $albumPhotos = $wedding->photos->slice($albumIndex * $perAlbum, $perAlbum);
                        }
                        $albumThemeColors = [
                            ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'badge' => 'bg-emerald-100 text-emerald-800', 'icon' => 'text-emerald-700'],
                            ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'badge' => 'bg-blue-100 text-blue-800', 'icon' => 'text-blue-700'],
                            ['bg' => 'bg-violet-50', 'border' => 'border-violet-200', 'badge' => 'bg-violet-100 text-violet-800', 'icon' => 'text-violet-700'],
                            ['bg' => 'bg-amber-50', 'border' => 'border-amber-200', 'badge' => 'bg-amber-100 text-amber-800', 'icon' => 'text-amber-700'],
                            ['bg' => 'bg-rose-50', 'border' => 'border-rose-200', 'badge' => 'bg-rose-100 text-rose-800', 'icon' => 'text-rose-700'],
                        ];
                        $color = $albumThemeColors[$albumIndex % count($albumThemeColors)];
                    @endphp

                    <div class="group">
                        <!-- Album Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl {{ $color['bg'] }} {{ $color['border'] }} border flex items-center justify-center {{ $color['icon'] }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">{{ $album->album_name }}</h2>
                                    <p class="text-xs text-slate-500">{{ $albumPhotos->count() }} photos in this album</p>
                                </div>
                                <span class="hidden sm:inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $color['badge'] }}">
                                    Album {{ $albumIndex + 1 }}
                                </span>
                            </div>
                            <a href="https://drive.google.com/drive/folders/{{ $wedding->drive_folder_id }}" 
                               target="_blank"
                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 text-xs font-semibold shadow-xs transition">
                                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download Folder
                            </a>
                        </div>

                        <!-- Photo Grid for this Album -->
                        @if($albumPhotos->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                                @foreach($albumPhotos->take(12) as $idx => $photo)
                                    @php
                                        $cleanName = preg_replace('/\.[^.]+$/', '', $photo->nama_file);
                                        $cleanName = str_replace(['_', '-'], ' ', $cleanName);
                                    @endphp
                                    <a href="{{ route('weddings.download', ['slug' => $wedding->slug, 'fileId' => $photo->drive_file_id]) }}"
                                       onclick="handleDownloadWithDeviceTime(event, this)"
                                       title="Download: {{ $cleanName }}"
                                       class="group/card relative block rounded-xl overflow-hidden border border-slate-200 bg-white shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                                        <!-- Thumbnail -->
                                        <div class="aspect-square bg-slate-100 overflow-hidden">
                                            <img src="{{ $photo->thumbnail_url }}" 
                                                 alt="{{ $cleanName }}"
                                                 loading="lazy"
                                                 class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-300"
                                                 onerror="this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=300&q=60'">
                                        </div>
                                        <!-- Hover Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity flex items-end p-2">
                                            <div class="flex items-center gap-1.5 w-full">
                                                <svg class="w-3.5 h-3.5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                <span class="text-[10px] text-white font-semibold truncate">{{ Str::limit($cleanName, 16) }}</span>
                                            </div>
                                        </div>
                                        <!-- Index badge -->
                                        <div class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-black/50 backdrop-blur-xs text-[9px] text-white font-mono">
                                            #{{ str_pad($idx + 1, 3, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </a>
                                @endforeach

                                <!-- "View More" card if there are more than 12 photos -->
                                @if($albumPhotos->count() > 12)
                                    <a href="https://drive.google.com/drive/folders/{{ $wedding->drive_folder_id }}"
                                       target="_blank"
                                       class="relative block rounded-xl overflow-hidden border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-slate-100 transition-colors flex items-center justify-center min-h-[100px]">
                                        <div class="text-center p-3">
                                            <div class="text-2xl font-bold text-slate-600">+{{ $albumPhotos->count() - 12 }}</div>
                                            <div class="text-[10px] text-slate-500 font-medium mt-0.5">more photos</div>
                                            <div class="text-[9px] text-emerald-700 font-semibold mt-1">View All →</div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="p-6 rounded-xl border border-dashed border-slate-200 text-center text-slate-400 text-xs">
                                No photos synced for this album yet.
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        @elseif($wedding->photos->count() > 0)
            <!-- No albums defined: show all photos in one "All Photos" folder view -->
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">All Photos — {{ $wedding->couple_name }}</h2>
                            <p class="text-xs text-slate-500">{{ $wedding->photos->count() }} photos • {{ $wedding->theme ?? 'Wedding Gallery' }}</p>
                        </div>
                    </div>
                    <a href="https://drive.google.com/drive/folders/{{ $wedding->drive_folder_id }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#1B4D3E] hover:bg-[#153C30] text-white text-xs font-semibold shadow transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download All from Drive
                    </a>
                </div>

                <!-- Responsive Photo Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3" id="client-photo-grid">
                    @foreach($wedding->photos as $idx => $photo)
                        @php
                            $cleanName = preg_replace('/\.[^.]+$/', '', $photo->nama_file);
                            $cleanName = str_replace(['_', '-'], ' ', $cleanName);
                        @endphp
                        <a href="{{ route('weddings.download', ['slug' => $wedding->slug, 'fileId' => $photo->drive_file_id]) }}"
                           onclick="handleDownloadWithDeviceTime(event, this)"
                           title="Download: {{ $cleanName }}"
                           class="group/card relative block rounded-xl overflow-hidden border border-slate-200 bg-white shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <div class="aspect-square bg-slate-100 overflow-hidden">
                                <img src="{{ $photo->thumbnail_url }}" 
                                     alt="{{ $cleanName }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-300"
                                     onerror="this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=300&q=60'">
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity flex items-end p-2">
                                <div class="flex items-center gap-1.5 w-full">
                                    <svg class="w-3.5 h-3.5 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span class="text-[10px] text-white font-semibold truncate">{{ Str::limit($cleanName, 16) }}</span>
                                </div>
                            </div>
                            <div class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-black/50 backdrop-blur-xs text-[9px] text-white font-mono">
                                #{{ str_pad($idx + 1, 3, '0', STR_PAD_LEFT) }}
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Bottom actions -->
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="https://drive.google.com/drive/folders/{{ $wedding->drive_folder_id }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#1B4D3E] hover:bg-[#153C30] text-white text-xs font-semibold shadow transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Open Google Drive Folder
                    </a>
                    <button type="button"
                            onclick="window.switchGalleryMainTab && window.switchGalleryMainTab('gallery')"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 text-xs font-semibold shadow-xs transition">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        View Full Gallery
                    </button>
                </div>
            </div>

        @else
            <!-- Empty state -->
            <div class="py-20 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-3xl mx-auto mb-4">📂</div>
                <h3 class="font-bold text-slate-800 text-lg mb-1">No Photos Yet</h3>
                <p class="text-sm text-slate-500 max-w-sm mx-auto mb-6">
                    Photos haven't been synced from Google Drive yet. Ask the studio to sync your gallery.
                </p>
                <a href="{{ route('weddings.sync', $wedding->slug) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#1B4D3E] text-white text-xs font-semibold shadow transition hover:bg-[#153C30]">
                    🔄 Sync Photos Now
                </a>
            </div>
        @endif
    </section>
</div>

<!-- ======================================================== -->
<!-- LIGHTBOX MODAL (Figma Exact: Fullscreen Photo Viewer)    -->
<!-- ======================================================== -->
<div id="lightbox-modal" class="fixed inset-0 z-50 bg-[#0B1727]/95 backdrop-blur-md hidden flex-col justify-between text-white p-4 sm:p-6 transition-all duration-300">
    <!-- Top Bar -->
    <div class="flex items-center justify-between border-b border-white/10 pb-3">
        <div class="flex items-center gap-3">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            <div>
                <span id="lb-counter" class="text-xs font-bold text-white tracking-wide">Photo 1 of 1</span>
                <span class="text-xs text-slate-400 ml-1">&bull; {{ $wedding->couple_name }}</span>
            </div>
        </div>

        <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 text-emerald-300 text-xs font-semibold border border-white/10">
            <span>✨ Resepsi / The Curated Collection</span>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="closeLightbox()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-sm transition">
                &times;
            </button>
        </div>
    </div>

    <!-- Center Stage -->
    <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden">
        <!-- Prev Button -->
        <button type="button" 
                onclick="prevPhoto()" 
                class="absolute left-2 sm:left-6 z-20 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition shadow-lg backdrop-blur-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <!-- Main Photo Frame -->
        <div class="max-w-4xl max-h-[70vh] bg-white p-2 sm:p-3 rounded-2xl shadow-2xl flex flex-col items-center">
            <img id="lb-image" 
                 src="" 
                 alt="" 
                 class="max-h-[62vh] max-w-full object-contain rounded-xl">
            <div id="lb-title" class="text-xs font-bold text-slate-800 text-center pt-2">
                Foto
            </div>
        </div>

        <!-- Next Button -->
        <button type="button" 
                onclick="nextPhoto()" 
                class="absolute right-2 sm:right-6 z-20 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition shadow-lg backdrop-blur-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    <!-- Floating Bottom Controls Bar (Figma Exact) -->
    <div class="flex flex-col items-center gap-3">
        <div class="flex items-center gap-3 px-4 py-2 bg-slate-900/90 border border-white/15 rounded-full shadow-2xl backdrop-blur-md">
            <a id="lb-download-btn" 
               href="#" 
               onclick="handleDownloadWithDeviceTime(event, this)" 
               class="px-4 py-1.5 rounded-full bg-[#1B4D3E] hover:bg-[#153C30] text-white text-xs font-semibold flex items-center gap-1.5 shadow transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Download High-Res Photo</span>
            </a>

            <button type="button" 
                    onclick="toggleFavorite(this)" 
                    class="px-3 py-1.5 text-xs text-slate-300 hover:text-white flex items-center gap-1 transition">
                <span class="fav-icon">♡</span>
                <span>Add to Favorites</span>
            </button>

            <span class="hidden md:inline-block text-[11px] font-mono text-slate-400 border-l border-white/10 pl-3">
                50mm &bull; f/1.4 &bull; 1/800s &bull; ISO 100
            </span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ============================================================
    // TAB SWITCHING: Gallery ↔ Client
    // ============================================================
    window.switchGalleryMainTab = function(tab) {
        const galleryTab = document.getElementById('tab-gallery');
        const clientTab  = document.getElementById('tab-client');
        const navGallery = document.getElementById('nav-tab-gallery');
        const navClient  = document.getElementById('nav-tab-client');

        if (tab === 'gallery') {
            galleryTab && galleryTab.classList.remove('hidden');
            clientTab  && clientTab.classList.add('hidden');
            navGallery && navGallery.classList.add('active', 'text-emerald-900', 'font-bold', 'border-b-2', 'border-emerald-800');
            navGallery && navGallery.classList.remove('text-slate-500', 'border-transparent');
            navClient  && navClient.classList.remove('active', 'text-emerald-900', 'font-bold', 'border-b-2', 'border-emerald-800');
            navClient  && navClient.classList.add('text-slate-500', 'border-transparent');
        } else {
            clientTab  && clientTab.classList.remove('hidden');
            galleryTab && galleryTab.classList.add('hidden');
            navClient  && navClient.classList.add('active', 'text-emerald-900', 'font-bold', 'border-b-2', 'border-emerald-800');
            navClient  && navClient.classList.remove('text-slate-500', 'border-transparent');
            navGallery && navGallery.classList.remove('active', 'text-emerald-900', 'font-bold', 'border-b-2', 'border-emerald-800');
            navGallery && navGallery.classList.add('text-slate-500', 'border-transparent');
        }

        // Save tab state in URL hash
        window.location.hash = tab;
    };

    // On page load: restore tab from URL hash
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.replace('#', '');
        if (hash === 'client') {
            window.switchGalleryMainTab('client');
        }
    });

    // Realtime device download handler
    function handleDownloadWithDeviceTime(event, el) {
        const originalHref = el.getAttribute('href');
        if (!originalHref || originalHref.includes('client_time=')) return;
        event.preventDefault();
        const nowIso = new Date().toISOString();
        const sep = originalHref.includes('?') ? '&' : '?';
        window.location.href = originalHref + sep + 'client_time=' + encodeURIComponent(nowIso);
    }

    // Format download logs timestamps to device local time
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.device-time-display').forEach(function(el) {
            const iso = el.getAttribute('data-iso');
            if (iso) {
                try {
                    const date = new Date(iso);
                    if (!isNaN(date.getTime())) {
                        el.textContent = '🕒 ' + date.toLocaleString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        });
                    }
                } catch (e) {}
            }
        });
    });

    // Lightbox Modal Logic
    let currentPhotoIndex = 0;
    const photoCards = Array.from(document.querySelectorAll('.gallery-photo-card'));

    function openLightbox(index) {
        if (!photoCards[index]) return;
        currentPhotoIndex = index;
        updateLightbox();
        document.getElementById('lightbox-modal').classList.remove('hidden');
        document.getElementById('lightbox-modal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox-modal').classList.add('hidden');
        document.getElementById('lightbox-modal').classList.remove('flex');
        document.body.style.overflow = '';
    }

    function updateLightbox() {
        const card = photoCards[currentPhotoIndex];
        if (!card) return;
        const src = card.getAttribute('data-src');
        const title = card.getAttribute('data-title');
        const num = card.getAttribute('data-num');
        const downloadUrl = card.getAttribute('data-download-url');

        document.getElementById('lb-image').src = src;
        document.getElementById('lb-title').textContent = title + ' (' + num + ')';
        document.getElementById('lb-counter').textContent = 'Photo ' + (currentPhotoIndex + 1) + ' of ' + photoCards.length;
        document.getElementById('lb-download-btn').setAttribute('href', downloadUrl);
    }

    function prevPhoto() {
        if (photoCards.length === 0) return;
        currentPhotoIndex = (currentPhotoIndex - 1 + photoCards.length) % photoCards.length;
        updateLightbox();
    }

    function nextPhoto() {
        if (photoCards.length === 0) return;
        currentPhotoIndex = (currentPhotoIndex + 1) % photoCards.length;
        updateLightbox();
    }

    function toggleFavorite(btn) {
        const icon = btn.querySelector('.fav-icon');
        if (icon.textContent === '♡') {
            icon.textContent = '❤️';
            btn.classList.add('text-rose-400');
        } else {
            icon.textContent = '♡';
            btn.classList.remove('text-rose-400');
        }
    }

    // Keyboard navigation in Lightbox
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('lightbox-modal');
        if (!modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') prevPhoto();
            if (e.key === 'ArrowRight') nextPhoto();
        }
    });

    // Gallery Search Filter
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('gallery-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                photoCards.forEach(card => {
                    const label = card.getAttribute('data-label') || '';
                    if (label.includes(query)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });

    function filterByAlbum(albumId, btnElement) {
        document.querySelectorAll('.album-tab').forEach(el => {
            el.classList.remove('bg-[#1B4D3E]', 'text-white');
            el.classList.add('bg-slate-100', 'text-slate-700');
            const badge = el.querySelector('.album-badge');
            if (badge) {
                badge.classList.remove('bg-white/20', 'text-white');
                badge.classList.add('bg-slate-200', 'text-slate-700');
            }
        });

        const activeBtn = btnElement || (event ? event.target.closest('.album-tab') : null);
        if (activeBtn) {
            activeBtn.classList.remove('bg-slate-100', 'text-slate-700');
            activeBtn.classList.add('bg-[#1B4D3E]', 'text-white');
            const activeBadge = activeBtn.querySelector('.album-badge');
            if (activeBadge) {
                activeBadge.classList.remove('bg-slate-200', 'text-slate-700');
                activeBadge.classList.add('bg-white/20', 'text-white');
            }
        }

        const cards = document.querySelectorAll('.gallery-photo-card');
        cards.forEach(card => {
            if (albumId === 'all') {
                card.style.display = '';
            } else {
                const cardAlbumId = card.getAttribute('data-album-id') || '';
                card.style.display = (cardAlbumId === String(albumId)) ? '' : 'none';
            }
        });
    }
</script>
@endpush
@endsection
