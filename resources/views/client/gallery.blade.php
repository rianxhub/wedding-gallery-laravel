@php
    $theme = $wedding->theme_config;
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $wedding->couple_name }} - Galeri Foto Pernikahan</title>
    <meta name="description" content="Koleksi foto pernikahan {{ $wedding->couple_name }}. Saksikan setiap momen berharga dan unduh kenangan penuh cinta.">

    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            --font-serif: 'Playfair Display', ui-serif, Georgia, serif;
        }
        body {
            font-family: var(--font-sans);
        }
        .serif-font {
            font-family: var(--font-serif);
        }
    </style>
</head>
<body class="min-h-full {{ $theme['body_bg'] }} {{ $theme['body_text'] }} flex flex-col antialiased selection:bg-amber-400 selection:text-black">

    <!-- Guest Sticky Navigation Bar (Clean: No admin controls) -->
    <header class="sticky top-0 z-40 {{ $theme['navbar_bg'] }} backdrop-blur-md transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Monogram (Clean: No outside links) -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full {{ $theme['badge'] }} flex items-center justify-center font-bold serif-font text-base shadow-sm">
                    {{ substr($wedding->couple_name, 0, 1) }}
                </div>
                <div class="text-left">
                    <span class="serif-font font-bold text-sm sm:text-base {{ $theme['heading_text'] }} tracking-tight block leading-tight">
                        {{ $wedding->couple_name }}
                    </span>
                    <span class="text-[10px] uppercase tracking-wider {{ $theme['subtext'] }}">Wedding Album</span>
                </div>
            </div>

            <!-- Right: Photo Count & Guest Pass Status -->
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $theme['badge'] }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $theme['accent_text'] }} bg-current"></span>
                    <span>{{ $photos->count() }} Foto</span>
                </span>

                @if($wedding->guest_download)
                    <span class="hidden sm:inline-flex items-center gap-1 text-[11px] font-medium {{ $theme['subtext'] }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduhan Aktif</span>
                    </span>
                @endif
            </div>
        </div>
    </header>

    <!-- ============================================================ -->
    <!-- HERO SECTION                                                 -->
    <!-- Menampilkan gambar cover_drive_file_id sebagai latar & quote di tengah -->
    <!-- ============================================================ -->
    <section class="relative w-full min-h-[520px] sm:min-h-[600px] lg:min-h-[680px] flex items-center justify-center overflow-hidden">
        <!-- Cover Photo Background -->
        <div class="absolute inset-0 bg-cover bg-center scale-105 transform duration-1000 ease-out" 
             style="background-image: url('{{ $wedding->cover_photo_url }}');"></div>
        
        <!-- Theme Overlay Gradient -->
        <div class="absolute inset-0 {{ $theme['hero_overlay'] }}"></div>
        <div class="absolute inset-0 bg-radial from-transparent via-black/20 to-black/60"></div>

        <!-- Center Hero Content -->
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 py-20 text-center space-y-6">
            
            <!-- Badge Monogram / Invitation -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full {{ $theme['badge'] }} text-xs font-semibold tracking-widest uppercase shadow-md backdrop-blur-md">
                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
                <span>The Wedding Celebration</span>
            </div>

            <!-- Couple Name Heading -->
            <h1 class="serif-font text-4xl sm:text-6xl md:text-7xl font-bold tracking-tight text-white leading-tight drop-shadow-lg">
                {{ $wedding->couple_name }}
            </h1>

            <!-- Romantic Quote Center (Required by User Prompt) -->
            @if(!empty($wedding->quote))
                <div class="max-w-2xl mx-auto py-2">
                    <p class="serif-font italic text-lg sm:text-2xl md:text-3xl text-white/95 font-light leading-relaxed drop-shadow-md">
                        &ldquo;{{ $wedding->quote }}&rdquo;
                    </p>
                </div>
            @endif

            <!-- Wedding Date -->
            @if($wedding->wedding_date)
                <div class="flex items-center justify-center gap-2 text-xs sm:text-sm text-white/80 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($wedding->wedding_date)->translatedFormat('l, d F Y') }}</span>
                </div>
            @endif

            <!-- Scroll Indicator -->
            <div class="pt-6">
                <a href="#photo-gallery" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full {{ $theme['primary_btn'] }} text-xs font-bold uppercase tracking-wider transition-all duration-300">
                    <span>Lihat Koleksi Foto</span>
                    <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </a>
            </div>

        </div>
    </section>

    <!-- ============================================================ -->
    <!-- GALLERY SECTION                                              -->
    <!-- Menampilkan foto-foto Google Drive dengan tombol unduh bersyarat -->
    <!-- ============================================================ -->
    <section id="photo-gallery" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-8 flex-1">
        
        <!-- Album Navigation & Controls -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b {{ $theme['card_border'] }}">
            <div>
                <h2 class="serif-font text-2xl sm:text-3xl font-bold {{ $theme['heading_text'] }} tracking-tight">
                    Momen Kebahagiaan
                </h2>
                <p class="text-xs sm:text-sm {{ $theme['subtext'] }} mt-1">
                    Setiap jepretan menceritakan kisah cinta yang abadi dan tak terlupakan.
                </p>
            </div>

            <!-- Action Controls: Album Filter, Selection Mode & Refresh -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Album Filter Tabs (Sesuai Folder Asli Google Drive & Jumlah Fotonya) -->
                @if(isset($albums) && $albums->count() > 0)
                    <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto pb-1 max-w-full scrollbar-none">
                        <button type="button" 
                                onclick="filterAlbum('all', this)"
                                class="album-tab active px-3.5 py-1.5 rounded-full text-xs font-semibold {{ $theme['primary_btn'] }} transition shadow-xs flex items-center gap-1.5 flex-shrink-0 cursor-pointer">
                            <span>All Photos</span>
                            <span class="album-badge px-1.5 py-0.2 rounded-full bg-white/25 text-[10px] font-bold">{{ $photos->count() }}</span>
                        </button>
                        @foreach($albums as $album)
                            <button type="button" 
                                    onclick="filterAlbum('{{ $album->id }}', this)"
                                    class="album-tab px-3.5 py-1.5 rounded-full text-xs font-semibold {{ $theme['secondary_btn'] }} transition shadow-xs flex items-center gap-1.5 flex-shrink-0 cursor-pointer">
                                <span>{{ $album->album_name }}</span>
                                <span class="album-badge px-1.5 py-0.2 rounded-full bg-black/10 dark:bg-white/20 text-[10px] font-bold">{{ $album->photos->count() }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Refresh / Sync Photos Button -->
                <button type="button"
                        onclick="retryAllImages()"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $theme['secondary_btn'] }} flex items-center gap-1.5 transition shadow-xs hover:scale-105 active:scale-95"
                        title="Segarkan / Muat ulang semua foto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Segarkan</span>
                </button>

                <!-- Checkbox Selection Mode Toggle Button -->
                @if($wedding->guest_download && $photos->count() > 0)
                    <button type="button"
                            id="selection-mode-toggle-btn"
                            onclick="toggleSelectionMode()"
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold {{ $theme['badge'] }} hover:opacity-90 flex items-center gap-1.5 transition shadow-xs border"
                            title="Aktifkan mode ceklis untuk memilih banyak foto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span id="selection-mode-text">Pilih Foto</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- PINTEREST-STYLE RESPONSIVE PHOTOS GRID (MASONRY)             -->
        <!-- 2 kolom di mobile, 3-5 kolom di tablet/desktop               -->
        <!-- Bersih tanpa watermark / card footer berat                   -->
        <!-- ============================================================ -->
        @if($photos->count() > 0)
            <div class="columns-2 sm:columns-3 md:columns-4 lg:columns-5 gap-2.5 sm:gap-4 [column-fill:_balance]" id="gallery-grid">
                @foreach($photos as $index => $photo)
                    <div class="photo-item group relative break-inside-avoid mb-2.5 sm:mb-4 rounded-xl sm:rounded-2xl overflow-hidden {{ $theme['photo_card_bg'] }} border {{ $theme['card_border'] }} shadow-xs hover:shadow-xl transition-all duration-300 cursor-pointer select-none"
                         id="photo-card-{{ $index }}"
                         data-album="{{ $photo->album_id ?? 'root' }}"
                         data-index="{{ $index }}"
                         data-title="{{ $photo->nama_file }}"
                         data-full-url="{{ $photo->thumbnail_url ?: 'https://lh3.googleusercontent.com/d/' . $photo->drive_file_id . '=w1920' }}"
                         data-file-id="{{ $photo->drive_file_id }}"
                         onclick="handleCardClick(event, {{ $index }})">
                        
                        <!-- Checkbox Overlay (Hanya muncul jika mode 'Pilih Foto' aktif) -->
                        @if($wedding->guest_download)
                            <div class="photo-checkbox-wrap absolute top-2.5 left-2.5 z-20 pointer-events-auto hidden" onclick="event.stopPropagation()">
                                <label class="cursor-pointer block p-0.5" title="Pilih foto ini untuk diunduh">
                                    <input type="checkbox" 
                                           class="photo-checkbox sr-only peer" 
                                           value="{{ $photo->drive_file_id }}"
                                           data-title="{{ $photo->nama_file }}"
                                           data-index="{{ $index }}"
                                           onchange="handleCheckboxToggle(this, {{ $index }}, event)">
                                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-black/50 backdrop-blur-md border-2 border-white/80 peer-checked:bg-emerald-500 peer-checked:border-emerald-500 flex items-center justify-center text-white transition-all shadow-md hover:scale-110 active:scale-95">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </label>
                            </div>
                        @endif

                        <!-- Quick Single Download Button (Atas Kanan saat Hover di Desktop) -->
                        @if($wedding->guest_download)
                            <button type="button" 
                                    onclick="asyncDownloadPhoto('{{ $photo->drive_file_id }}', '{{ addslashes($photo->nama_file) }}', this, event)"
                                    class="quick-dl-btn absolute top-2.5 right-2.5 z-20 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-black/50 hover:bg-black/80 backdrop-blur-md border border-white/40 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 sm:opacity-0 transition-all duration-200 shadow-md active:scale-95"
                                    title="Unduh Foto Ini">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </button>
                        @endif

                        <!-- Photo Image (Presisi pas 100% dengan frame tanpa offset dan tanpa overlay memuat) -->
                        <img src="{{ $photo->thumbnail_url ?: 'https://lh3.googleusercontent.com/d/' . $photo->drive_file_id . '=w800-rw' }}" 
                             alt="{{ $photo->nama_file }}" 
                             loading="lazy" 
                             class="gallery-img w-full h-auto block object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                             data-file-id="{{ $photo->drive_file_id }}"
                             data-retry="0"
                             onload="onImgLoaded(this)"
                             onerror="onImgError(this)">

                        <!-- Minimalist Hover Overlay: Judul Foto (Hanya saat hover) -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-2.5 sm:p-3 pointer-events-none">
                            <span class="text-white text-[11px] sm:text-xs font-medium truncate block drop-shadow-sm">
                                {{ $photo->nama_file }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty Gallery State -->
            <div class="text-center py-20 p-8 rounded-3xl border {{ $theme['card_border'] }} {{ $theme['card_bg'] }} max-w-lg mx-auto space-y-4">
                <div class="w-14 h-14 rounded-full {{ $theme['badge'] }} flex items-center justify-center mx-auto text-2xl">
                    📸
                </div>
                <h3 class="serif-font text-xl font-bold {{ $theme['heading_text'] }}">Foto Sedang Diproses</h3>
                <p class="text-xs {{ $theme['subtext'] }} leading-relaxed">
                    Koleksi foto sedang disinkronkan dari Google Drive. Silakan kunjungi kembali beberapa saat lagi.
                </p>
            </div>
        @endif

    </section>

    <!-- ============================================================ -->
    <!-- FLOATING SELECTION DOWNLOAD BAR (STICKY BOTTOM ACTION BAR)   -->
    <!-- Muncul saat tamu mencentang 1 atau lebih foto via ceklis     -->
    <!-- ============================================================ -->
    @if($wedding->guest_download)
        <div id="selection-bar" class="fixed bottom-6 inset-x-3 sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 z-50 transform translate-y-32 opacity-0 transition-all duration-300 pointer-events-none max-w-md w-full">
            <div class="bg-slate-900/95 backdrop-blur-md text-white p-3 sm:p-3.5 rounded-2xl shadow-2xl border border-slate-700/80 flex items-center justify-between gap-3">
                <!-- Counter & Select All -->
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-xs font-bold font-mono" id="selected-badge-count">
                        0
                    </span>
                    <div>
                        <div class="text-xs font-bold leading-tight" id="selected-count-text">0 Foto Dipilih</div>
                        <button type="button" onclick="toggleSelectAllVisible()" class="text-[10px] text-slate-400 hover:text-white transition underline underline-offset-2" id="toggle-all-btn">
                            Pilih Semua
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <button type="button" onclick="clearSelection()" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-medium transition active:scale-95">
                        Batal
                    </button>
                    <button type="button" id="btn-download-selected" onclick="downloadSelectedPhotos()" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold shadow-lg shadow-emerald-500/30 flex items-center gap-1.5 transition active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span id="btn-download-selected-text">Unduh Terpilih</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- LIGHTBOX MODAL (PRATINJAU FOTO & UNDUH SAAT DITEKAN)         -->
    <!-- ============================================================ -->
    <div id="lightbox-modal" class="fixed inset-0 z-50 bg-black/95 backdrop-blur-md hidden flex-col items-center justify-between p-3 sm:p-6 transition-all duration-300">
        
        <!-- Lightbox Top Controls -->
        <div class="w-full max-w-6xl flex items-center justify-between text-white py-2 px-1">
            <div class="flex items-center gap-2 sm:gap-3">
                <span id="lightbox-counter" class="text-xs font-mono text-white/70">1 / {{ $photos->count() }}</span>
                <span class="text-white/40">|</span>
                <span id="lightbox-title" class="text-xs font-medium truncate max-w-[140px] sm:max-w-md">Foto Pernikahan</span>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Checkbox toggle in Lightbox -->
                @if($wedding->guest_download)
                    <button type="button" 
                            id="lightbox-check-btn"
                            onclick="toggleLightboxCurrentSelection()" 
                            class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-xs font-medium flex items-center gap-1.5 transition">
                        <span id="lightbox-check-icon">⬜</span>
                        <span id="lightbox-check-text" class="hidden sm:inline">Pilih Foto</span>
                    </button>
                @endif

                <!-- Conditional Download in Lightbox (Tekan untuk unduh) -->
                @if($wedding->guest_download)
                    <button type="button" 
                            id="lightbox-download-btn"
                            onclick="triggerLightboxDownload(this)" 
                            class="px-3.5 py-1.5 rounded-lg {{ $theme['primary_btn'] }} text-xs font-semibold flex items-center gap-1.5 transition shadow-sm active:scale-95"
                            title="Unduh foto resolusi asli ini">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh Resolusi Asli</span>
                    </button>
                @endif

                <!-- Close Button -->
                <button type="button" onclick="closeLightbox()" class="p-2 text-white/70 hover:text-white rounded-full hover:bg-white/10 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Lightbox Center Image with Nav Buttons -->
        <div class="relative w-full max-w-6xl flex-1 flex items-center justify-center my-auto">
            <!-- Prev Button -->
            <button type="button" onclick="prevLightboxPhoto()" class="absolute left-1 sm:left-4 z-20 p-2.5 sm:p-3 rounded-full bg-black/50 text-white/80 hover:text-white hover:bg-black/80 transition active:scale-90">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Active Photo Image -->
            <div class="max-h-[75vh] sm:max-h-[82vh] max-w-full flex items-center justify-center">
                <img id="lightbox-img" 
                     src="" 
                     alt="Preview" 
                     class="max-h-[75vh] sm:max-h-[82vh] max-w-full object-contain rounded-lg shadow-2xl transition-opacity duration-200">
            </div>

            <!-- Next Button -->
            <button type="button" onclick="nextLightboxPhoto()" class="absolute right-1 sm:right-4 z-20 p-2.5 sm:p-3 rounded-full bg-black/50 text-white/80 hover:text-white hover:bg-black/80 transition active:scale-90">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        <!-- Lightbox Bottom Caption / Mobile Direct Download -->
        <div class="w-full max-w-6xl py-2 flex flex-col sm:flex-row items-center justify-between gap-2 text-center text-xs text-white/60">
            <span class="hidden sm:inline">Gunakan tombol panah (&larr; / &rarr;) untuk navigasi, atau tekan Esc untuk menutup.</span>
            @if($wedding->guest_download)
                <button type="button" 
                        onclick="triggerLightboxDownload(this)" 
                        class="sm:hidden w-full py-2.5 rounded-xl {{ $theme['primary_btn'] }} text-xs font-bold flex items-center justify-center gap-2 shadow-lg transition active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Unduh Foto Ini</span>
                </button>
            @endif
    </div>

    <!-- Floating Async Toast Notification -->
    <div id="async-toast" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none">
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-900 text-white text-xs font-semibold shadow-2xl border border-slate-700/80">
            <span id="toast-icon">⬇️</span>
            <span id="toast-message">Mengunduh foto...</span>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- FLOATING REFRESH / RETRY BUTTON                              -->
    <!-- Muncul otomatis bila ada foto yang gagal dimuat thumbnail-nya -->
    <!-- ============================================================ -->
    <div id="refresh-fab" class="fixed bottom-6 left-6 z-50 transform translate-y-24 opacity-0 transition-all duration-500 pointer-events-none">
        <div class="flex flex-col items-start gap-2">
            <!-- Main Retry Button -->
            <button type="button"
                    id="retry-photos-btn"
                    onclick="retryAllFailedImages()"
                    class="flex items-center gap-2.5 px-4 py-3 rounded-2xl {{ $theme['primary_btn'] }} shadow-2xl text-sm font-bold transition-all duration-200 active:scale-95 group"
                    title="Coba muat ulang foto yang gagal">
                <svg id="retry-spinner" class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <div class="text-left">
                    <div class="text-xs font-bold leading-tight">Muat Ulang Foto</div>
                    <div id="retry-count-label" class="text-[10px] font-medium opacity-80 leading-tight">0 foto gagal dimuat</div>
                </div>
            </button>

            <!-- Dismiss Button -->
            <button type="button"
                    onclick="dismissRefreshFab()"
                    class="text-[10px] {{ $theme['subtext'] }} hover:opacity-80 transition px-1 underline underline-offset-2">
                Abaikan
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full py-8 text-center text-xs opacity-60 border-t {{ $theme['card_border'] }} mt-auto">
        <div class="max-w-7xl mx-auto px-4 space-y-1">
            <p>&copy; {{ date('Y') }} {{ $wedding->couple_name }}. Seluruh hak cipta dilindungi.</p>
            <p class="text-[11px] opacity-75">Dipersembahkan dengan cinta melalui platform WedGallery.</p>
        </div>
    </footer>

    <!-- ============================================================ -->
    <!-- JAVASCRIPT: ASINKRON UNDUH & LIGHTBOX                         -->
    <!-- ============================================================ -->
    <script>
        // ============================================================
        // KONFIGURASI FALLBACK URL GOOGLE DRIVE
        // Urutan URL dicoba saat thumbnail gagal dimuat
        // ============================================================
        const DRIVE_FALLBACK_URLS = [
            // Format 1: lh3 w800 (default thumbnail)
            (id) => `https://lh3.googleusercontent.com/d/${id}=w800-rw`,
            // Format 2: lh3 w1200
            (id) => `https://lh3.googleusercontent.com/d/${id}=w1200`,
            // Format 3: lh3 h800
            (id) => `https://lh3.googleusercontent.com/d/${id}=h800`,
            // Format 4: Drive thumbnail API
            (id) => `https://drive.google.com/thumbnail?id=${id}&sz=w800`,
            // Format 5: Drive uc export view
            (id) => `https://drive.google.com/uc?id=${id}&export=view`,
        ];

        // Track failed image elements
        let failedImages = new Set();
        let fabDismissed = false;

        /**
         * Dipanggil saat gambar berhasil dimuat.
         */
        function onImgLoaded(img) {
            failedImages.delete(img);
            img.style.display = 'block';
            const errBox = img.parentElement?.querySelector('.img-error-box');
            if (errBox) errBox.remove();
            updateRefreshFab();
        }

        /**
         * Dipanggil saat gambar GAGAL dimuat.
         * Mencoba URL fallback berikutnya secara berurutan.
         */
        function onImgError(img) {
            const fileId = img.getAttribute('data-file-id');
            if (!fileId) return;

            const retryIndex = parseInt(img.getAttribute('data-retry') || '0');

            if (retryIndex < DRIVE_FALLBACK_URLS.length) {
                // Coba URL berikutnya
                img.setAttribute('data-retry', retryIndex + 1);
                img.src = DRIVE_FALLBACK_URLS[retryIndex](fileId);
            } else {
                // Semua URL sudah dicoba, tampilkan placeholder error
                showImageError(img);
                failedImages.add(img);
                if (!fabDismissed) updateRefreshFab();
            }
        }

        /**
         * Tampilkan placeholder saat semua URL gagal.
         */
        function showImageError(img) {
            img.style.display = 'none';
            let errBox = img.parentElement?.querySelector('.img-error-box');
            if (!errBox) {
                errBox = document.createElement('div');
                errBox.className = 'img-error-box w-full py-10 px-3 flex flex-col items-center justify-center text-center bg-black/5 dark:bg-white/5 text-slate-400 cursor-pointer transition hover:bg-black/10';
                errBox.innerHTML = `
                    <svg class="w-7 h-7 opacity-50 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    <span class="text-[10px] opacity-70">Gagal memuat<br><span class="underline font-medium">Tekan untuk coba lagi</span></span>
                `;
                errBox.onclick = (e) => {
                    e.stopPropagation();
                    retrySingleImage(img);
                };
                img.parentElement?.appendChild(errBox);
            }
        }

        /**
         * Muat ulang 1 gambar
         */
        function retrySingleImage(img) {
            const fileId = img.getAttribute('data-file-id');
            if (!fileId) return;
            img.setAttribute('data-retry', '0');
            const errBox = img.parentElement?.querySelector('.img-error-box');
            if (errBox) errBox.remove();
            img.style.display = 'block';
            img.src = `https://drive.google.com/thumbnail?id=${fileId}&sz=w800`;
        }

        /**
         * Tombol FAB: Coba muat ulang semua foto yang gagal.
         */
        function retryAllFailedImages() {
            if (failedImages.size === 0) {
                retryAllImages();
                return;
            }

            const btn = document.getElementById('retry-photos-btn');
            const spinner = document.getElementById('retry-spinner');
            if (btn) btn.disabled = true;
            if (spinner) spinner.classList.add('animate-spin');

            const toRetry = Array.from(failedImages);
            failedImages.clear();

            toRetry.forEach((img, i) => {
                setTimeout(() => {
                    retrySingleImage(img);
                }, i * 120);
            });

            setTimeout(() => {
                if (btn) btn.disabled = false;
                if (spinner) spinner.classList.remove('animate-spin');
                updateRefreshFab();
            }, toRetry.length * 120 + 1200);
        }

        /**
         * Coba muat ulang SEMUA gambar di galeri (bukan hanya yang gagal).
         */
        function retryAllImages() {
            const allImgs = document.querySelectorAll('img.gallery-img');
            const spinner = document.getElementById('retry-spinner');
            const btn = document.getElementById('retry-photos-btn');
            if (btn) btn.disabled = true;
            if (spinner) spinner.classList.add('animate-spin');

            allImgs.forEach((img, i) => {
                setTimeout(() => {
                    retrySingleImage(img);
                }, i * 60);
            });

            setTimeout(() => {
                if (btn) btn.disabled = false;
                if (spinner) spinner.classList.remove('animate-spin');
            }, allImgs.length * 60 + 1000);

            showToast('🔄 Memuat ulang semua foto...', '🔄');
        }

        /**
         * Update FAB visibility berdasarkan jumlah foto yang gagal.
         */
        function updateRefreshFab() {
            if (fabDismissed) return;
            const fab = document.getElementById('refresh-fab');
            const label = document.getElementById('retry-count-label');
            if (!fab) return;

            const count = failedImages.size;
            if (count > 0) {
                if (label) label.textContent = count + ' foto gagal dimuat';
                fab.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
                fab.classList.add('translate-y-0', 'opacity-100');
            } else {
                fab.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
                fab.classList.remove('translate-y-0', 'opacity-100');
            }
        }

        /**
         * Sembunyikan FAB (tombol Abaikan).
         */
        function dismissRefreshFab() {
            fabDismissed = true;
            const fab = document.getElementById('refresh-fab');
            if (fab) {
                fab.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
            }
        }

        // Photo data items array
        const photoItems = Array.from(document.querySelectorAll('.photo-item')).map(el => ({
            fileId: el.getAttribute('data-file-id'),
            title: el.getAttribute('data-title'),
            fullUrl: el.getAttribute('data-full-url'),
            albumId: el.getAttribute('data-album'),
        }));

        // ============================================================
        // STATE MANAJEMEN SELEKSI & CEKLIS FOTO
        // ============================================================
        let isSelectionMode = false;
        const selectedPhotos = new Map(); // key: fileId, value: { index, title, fileId }

        /**
         * Klik kartu foto:
         * - Jika dalam mode seleksi: toggle centang
         * - Jika mode normal: buka lightbox (tekan foto untuk lihat & unduh)
         */
        function handleCardClick(event, index) {
            if (event.target.closest('.photo-checkbox-wrap') || event.target.closest('.quick-dl-btn')) {
                return;
            }

            if (isSelectionMode) {
                toggleCardSelection(index);
            } else {
                openLightbox(index);
            }
        }

        /**
         * Toggle checkbox secara manual saat kartu diklik dalam mode seleksi
         */
        function toggleCardSelection(index) {
            const card = document.getElementById('photo-card-' + index);
            if (!card) return;
            const checkbox = card.querySelector('.photo-checkbox');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                handleCheckboxToggle(checkbox, index);
            }
        }

        /**
         * Dipanggil saat checkbox individual dicentang / diubah
         */
        function handleCheckboxToggle(checkbox, index, event) {
            if (event) event.stopPropagation();

            const fileId = checkbox.value;
            const title = checkbox.getAttribute('data-title') || ('foto_' + index);
            const card = document.getElementById('photo-card-' + index);

            if (checkbox.checked) {
                selectedPhotos.set(fileId, { index, title, fileId });
                if (card) {
                    card.classList.add('ring-2', 'ring-emerald-500', 'ring-offset-2', 'shadow-lg');
                }
            } else {
                selectedPhotos.delete(fileId);
                if (card) {
                    card.classList.remove('ring-2', 'ring-emerald-500', 'ring-offset-2', 'shadow-lg');
                }
            }

            updateSelectionBar();
            updateLightboxCheckButton();
        }

        /**
         * Toggle Mode Seleksi (Tombol di Header)
         * Menampilkan atau menyembunyikan lingkaran ceklis pada foto
         */
        function toggleSelectionMode() {
            isSelectionMode = !isSelectionMode;
            const btn = document.getElementById('selection-mode-toggle-btn');
            const text = document.getElementById('selection-mode-text');
            const wraps = document.querySelectorAll('.photo-checkbox-wrap');

            if (isSelectionMode) {
                if (btn) {
                    btn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600', 'shadow-md');
                }
                if (text) text.textContent = 'Selesai';
                wraps.forEach(wrap => wrap.classList.remove('hidden'));
                showToast('💡 Sentuh foto untuk memilih', '☑️');
            } else {
                if (btn) {
                    btn.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600', 'shadow-md');
                }
                if (text) text.textContent = 'Pilih Foto';
                // Jika tidak ada foto yang dicentang, sembunyikan semua lingkaran ceklis
                if (selectedPhotos.size === 0) {
                    wraps.forEach(wrap => wrap.classList.add('hidden'));
                }
            }
        }

        /**
         * Update status Floating Action Bar seleksi
         */
        function updateSelectionBar() {
            const bar = document.getElementById('selection-bar');
            if (!bar) return;

            const count = selectedPhotos.size;
            const countBadge = document.getElementById('selected-badge-count');
            const countText = document.getElementById('selected-count-text');
            const dlBtnText = document.getElementById('btn-download-selected-text');
            const toggleAllBtn = document.getElementById('toggle-all-btn');

            if (count > 0) {
                if (countBadge) countBadge.textContent = count;
                if (countText) countText.textContent = count + ' Foto Dipilih';
                if (dlBtnText) dlBtnText.textContent = 'Unduh ' + count + ' Foto';
                bar.classList.remove('translate-y-32', 'opacity-0', 'pointer-events-none');
                bar.classList.add('translate-y-0', 'opacity-100');
            } else {
                bar.classList.add('translate-y-32', 'opacity-0', 'pointer-events-none');
                bar.classList.remove('translate-y-0', 'opacity-100');
            }

            // Update label Pilih Semua / Batalkan Semua
            const visibleCheckboxes = Array.from(document.querySelectorAll('.photo-item'))
                .filter(el => el.style.display !== 'none')
                .map(el => el.querySelector('.photo-checkbox'))
                .filter(Boolean);

            const allChecked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
            if (toggleAllBtn) {
                toggleAllBtn.textContent = allChecked ? 'Batalkan Semua' : 'Pilih Semua';
            }
        }

        /**
         * Pilih Semua atau Batalkan Semua foto yang sedang terlihat
         */
        function toggleSelectAllVisible() {
            const visibleItems = Array.from(document.querySelectorAll('.photo-item'))
                .filter(el => el.style.display !== 'none');

            const visibleCheckboxes = visibleItems
                .map(el => el.querySelector('.photo-checkbox'))
                .filter(Boolean);

            const allChecked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);

            visibleItems.forEach(item => {
                const cb = item.querySelector('.photo-checkbox');
                if (!cb) return;
                const idx = parseInt(cb.getAttribute('data-index'));
                cb.checked = !allChecked;
                handleCheckboxToggle(cb, idx);
            });
        }

        /**
         * Kosongkan seluruh seleksi foto dan sembunyikan lingkaran ceklis
         */
        function clearSelection() {
            document.querySelectorAll('.photo-checkbox').forEach(cb => {
                cb.checked = false;
            });
            document.querySelectorAll('.photo-item').forEach(card => {
                card.classList.remove('ring-2', 'ring-emerald-500', 'ring-offset-2', 'shadow-lg');
            });
            selectedPhotos.clear();
            updateSelectionBar();
            updateLightboxCheckButton();

            // Matikan mode seleksi dan sembunyikan lingkaran ceklis
            if (isSelectionMode) {
                toggleSelectionMode();
            } else {
                document.querySelectorAll('.photo-checkbox-wrap').forEach(wrap => wrap.classList.add('hidden'));
            }
        }

        /**
         * Toggle seleksi untuk foto yang sedang aktif di Lightbox
         */
        function toggleLightboxCurrentSelection() {
            if (photoItems.length === 0 || !photoItems[currentLightboxIndex]) return;
            const currentItem = photoItems[currentLightboxIndex];
            const card = document.getElementById('photo-card-' + currentLightboxIndex);
            if (!card) return;
            const cb = card.querySelector('.photo-checkbox');
            if (cb) {
                cb.checked = !cb.checked;
                handleCheckboxToggle(cb, currentLightboxIndex);
            }
        }

        /**
         * Update tombol seleksi di Lightbox sesuai foto aktif
         */
        function updateLightboxCheckButton() {
            const btn = document.getElementById('lightbox-check-btn');
            const icon = document.getElementById('lightbox-check-icon');
            const text = document.getElementById('lightbox-check-text');
            if (!btn || photoItems.length === 0 || !photoItems[currentLightboxIndex]) return;

            const currentItem = photoItems[currentLightboxIndex];
            const isChecked = selectedPhotos.has(currentItem.fileId);

            if (isChecked) {
                if (icon) icon.textContent = '✅';
                if (text) text.textContent = 'Terpilih';
                btn.classList.add('bg-emerald-600', 'text-white');
                btn.classList.remove('bg-white/10');
            } else {
                if (icon) icon.textContent = '⬜';
                if (text) text.textContent = 'Pilih Foto';
                btn.classList.remove('bg-emerald-600');
                btn.classList.add('bg-white/10');
            }
        }

        /**
         * UNDUH SEMUA FOTO TERPILIH SECARA ASINKRON (SEKUENSIAL)
         * Mengunduh foto satu per satu dengan jeda waktu agar browser tidak memblokir unduhan banyak file.
         */
        let isBatchDownloading = false;
        async function downloadSelectedPhotos() {
            if (isBatchDownloading || selectedPhotos.size === 0) return;

            isBatchDownloading = true;
            const btn = document.getElementById('btn-download-selected');
            const btnText = document.getElementById('btn-download-selected-text');
            const originalHtml = btn ? btn.innerHTML : '';

            if (btn) btn.disabled = true;

            const itemsToDownload = Array.from(selectedPhotos.values());
            const total = itemsToDownload.length;

            showToast(`🚀 Memulai unduhan ${total} foto...`, '⬇️');

            for (let i = 0; i < total; i++) {
                const item = itemsToDownload[i];
                if (btnText) {
                    btnText.textContent = `Mengunduh ${i + 1}/${total}...`;
                }

                showToast(`⏳ Mengunduh (${i + 1}/${total}): ${item.title}`, '⬇️');

                try {
                    // Record download in backend log asynchronously
                    await fetch("{{ url('/gallery/' . $wedding->slug . '/download') }}/" + item.fileId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            client_time: new Date().toISOString()
                        })
                    }).catch(e => console.warn('Log error:', e));

                    // Trigger direct Google Drive download
                    const dlUrl = `https://drive.google.com/uc?export=download&id=${item.fileId}`;
                    triggerDirectDownload(dlUrl, item.title);

                    // Wait 400ms before downloading next photo so browser doesn't block popups
                    await new Promise(resolve => setTimeout(resolve, 450));
                } catch (err) {
                    console.error('Batch download error for ' + item.title, err);
                }
            }

            showToast(`🎉 Berhasil memproses unduhan ${total} foto!`, '✅');

            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
            isBatchDownloading = false;

            // Bersihkan seleksi setelah selesai
            clearSelection();
        }

        /**
         * FUNGSI UNDUH ASINKRON TUNGGAL (ASYNC AJAX DOWNLOAD)
         */
        function asyncDownloadPhoto(fileId, fileName, btnElement, event) {
            if (event) event.stopPropagation();

            showToast('⏳ Memproses unduhan ' + fileName + '...', '⏳');

            if (btnElement) {
                const originalHtml = btnElement.innerHTML;
                btnElement.disabled = true;
                btnElement.classList.add('opacity-75');

                fetch("{{ url('/gallery/' . $wedding->slug . '/download') }}/" + fileId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        client_time: new Date().toISOString()
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Gagal mencatat unduhan');
                    return res.json();
                })
                .then(data => {
                    const dlUrl = (data && data.download_url) ? data.download_url : `https://drive.google.com/uc?export=download&id=${fileId}`;
                    triggerDirectDownload(dlUrl, fileName);
                    showToast('✅ Unduhan ' + fileName + ' berhasil dimulai!', '✅');
                })
                .catch(err => {
                    console.error('Download error:', err);
                    const fallbackUrl = "https://drive.google.com/uc?export=download&id=" + fileId;
                    triggerDirectDownload(fallbackUrl, fileName);
                    showToast('✅ Unduhan dimulai!', '✅');
                })
                .finally(() => {
                    setTimeout(() => {
                        if (btnElement) {
                            btnElement.disabled = false;
                            btnElement.classList.remove('opacity-75');
                            btnElement.innerHTML = originalHtml;
                        }
                    }, 1200);
                });
            }
        }

        /**
         * Memicu download di browser via link tersembunyi
         */
        function triggerDirectDownload(url, filename) {
            const tempLink = document.createElement('a');
            tempLink.href = url;
            tempLink.setAttribute('download', filename || 'wedding_photo.jpg');
            tempLink.setAttribute('target', '_blank');
            tempLink.style.display = 'none';
            document.body.appendChild(tempLink);
            tempLink.click();
            setTimeout(() => {
                if (tempLink.parentElement) {
                    document.body.removeChild(tempLink);
                }
            }, 500);
        }

        /**
         * Trigger download dari dalam lightbox
         */
        function triggerLightboxDownload(btn) {
            if (photoItems.length > 0 && photoItems[currentLightboxIndex]) {
                const item = photoItems[currentLightboxIndex];
                asyncDownloadPhoto(item.fileId, item.title, btn);
            }
        }

        /**
         * Toast notification helper
         */
        let toastTimeout;
        function showToast(message, icon = '⬇️') {
            const toast = document.getElementById('async-toast');
            const toastMsg = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');

            if (!toast) return;

            toastMsg.textContent = message;
            toastIcon.textContent = icon;

            toast.classList.remove('translate-y-24', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');

            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.classList.add('translate-y-24', 'opacity-0');
                toast.classList.remove('translate-y-0', 'opacity-100');
            }, 3500);
        }

        /**
         * Lightbox Viewer Functions
         */
        function openLightbox(index) {
            if (!photoItems[index]) return;
            currentLightboxIndex = index;
            updateLightboxContent();
            const modal = document.getElementById('lightbox-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const modal = document.getElementById('lightbox-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function updateLightboxContent() {
            const item = photoItems[currentLightboxIndex];
            if (!item) return;

            const img = document.getElementById('lightbox-img');
            img.style.opacity = '0';
            img.src = item.fullUrl;
            img.onload = () => { img.style.opacity = '1'; };

            document.getElementById('lightbox-title').textContent = item.title;
            document.getElementById('lightbox-counter').textContent = (currentLightboxIndex + 1) + ' / ' + photoItems.length;

            updateLightboxCheckButton();
        }

        function nextLightboxPhoto() {
            if (photoItems.length === 0) return;
            currentLightboxIndex = (currentLightboxIndex + 1) % photoItems.length;
            updateLightboxContent();
        }

        function prevLightboxPhoto() {
            if (photoItems.length === 0) return;
            currentLightboxIndex = (currentLightboxIndex - 1 + photoItems.length) % photoItems.length;
            updateLightboxContent();
        }

        // Keyboard navigation for lightbox
        window.addEventListener('keydown', function(e) {
            const modal = document.getElementById('lightbox-modal');
            if (!modal || modal.classList.contains('hidden')) return;

            if (e.key === 'ArrowRight') nextLightboxPhoto();
            if (e.key === 'ArrowLeft') prevLightboxPhoto();
            if (e.key === 'Escape') closeLightbox();
        });

        /**
         * Filter Album Tabs
         */
        const themePrimaryTokens = {!! json_encode(array_values(array_filter(explode(' ', $theme['primary_btn'])))) !!};
        const themeSecondaryTokens = {!! json_encode(array_values(array_filter(explode(' ', $theme['secondary_btn'])))) !!};

        function filterAlbum(albumId, btnElement) {
            const activeBtn = btnElement || (window.event ? window.event.target.closest('.album-tab') : null);

            document.querySelectorAll('.album-tab').forEach(b => {
                if (themePrimaryTokens.length) b.classList.remove(...themePrimaryTokens);
                if (themeSecondaryTokens.length) b.classList.add(...themeSecondaryTokens);
                b.classList.remove('active');
            });

            if (activeBtn) {
                if (themeSecondaryTokens.length) activeBtn.classList.remove(...themeSecondaryTokens);
                if (themePrimaryTokens.length) activeBtn.classList.add(...themePrimaryTokens);
                activeBtn.classList.add('active');
            }

            const items = document.querySelectorAll('.photo-item');
            items.forEach(item => {
                const itemAlbum = item.getAttribute('data-album') || '';
                if (albumId === 'all' || itemAlbum === String(albumId)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });

            if (typeof updateSelectionBar === 'function') {
                updateSelectionBar();
            }
        }
    </script>
</body>
</html>
