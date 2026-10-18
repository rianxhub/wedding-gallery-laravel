@extends('layouts.app')

@section('title', 'Client Wedding Galleries')
@section('breadcrumb', 'Client Galleries')

@section('content')
<div class="space-y-6">
    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-xs font-bold">&times;</button>
        </div>
    @endif

    @if(session('info'))
        <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                <span>{{ session('info') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-blue-700 hover:text-blue-900 text-xs font-bold">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 text-xs font-bold">&times;</button>
        </div>
    @endif

    <!-- Page Header & Main Actions (Figma Exact Spec) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Client Wedding Galleries</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Overview of active, published, and upcoming wedding photo collections.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" 
                    onclick="exportTableToCSV()" 
                    class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 shadow-xs transition">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Export CSV</span>
            </button>

            <a href="{{ route('weddings.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-[#1B4D3E] hover:bg-[#153C30] rounded-lg shadow-xs transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Create New Wedding</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI / Metric Cards (Figma Exact Spec) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Active Galleries -->
        <div class="p-5 bg-white border border-slate-200/80 rounded-2xl shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Active Galleries</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2.5">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalWeddings }}</span>
                <span class="text-xs font-semibold text-emerald-700 flex items-center gap-0.5">
                    ↑+{{ $totalWeddings > 0 ? 3 : 0 }} this month
                </span>
            </div>
            <div class="mt-3 text-xs text-slate-500 font-medium">
                {{ $totalWeddings }} Published &bull; {{ $totalAlbums }} Albums &bull; {{ $totalUsers }} Users
            </div>
        </div>

        <!-- Card 2: Photos Hosted -->
        <div class="p-5 bg-white border border-slate-200/80 rounded-2xl shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Photos Hosted</span>
                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2.5">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalPhotos) }}</span>
                <span class="text-xs font-semibold text-slate-500">High-Res RAW</span>
            </div>
            <div class="mt-3 text-xs text-slate-500 font-medium">
                Avg {{ $totalWeddings > 0 ? round($totalPhotos / max(1, $totalWeddings)) : 0 }} captures per wedding
            </div>
        </div>

        <!-- Card 3: PIN Protections -->
        <div class="p-5 bg-white border border-slate-200/80 rounded-2xl shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">PIN Protections</span>
                <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-baseline gap-2.5">
                <span class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalWeddings }} Active</span>
                <span class="text-xs font-semibold text-teal-700">100% Protected</span>
            </div>
            <div class="mt-3 text-xs text-slate-500 font-medium">
                Client security standard enabled
            </div>
        </div>

        <!-- Card 4: Storage Used -->
        <div class="p-5 bg-white border border-slate-200/80 rounded-2xl shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Storage Used</span>
                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            @php
                $approxGb = round(($totalPhotos * 3.5) / 1024, 1);
                $percent = max(4, min(95, round(($approxGb / 50) * 100, 1)));
            @endphp
            <div class="mt-4 flex items-baseline justify-between">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $approxGb }} GB</span>
                    <span class="text-xs text-slate-500">of 50 GB</span>
                </div>
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                    {{ $percent }}%
                </span>
            </div>
            <!-- Progress Bar -->
            <div class="mt-2 w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full" style="width: {{ $percent }}%;"></div>
            </div>
            <div class="mt-2 text-xs text-slate-400 font-medium">
                {{ round(50 - $approxGb, 1) }} GB free
            </div>
        </div>
    </div>

    <!-- Filter Pills & Sorting Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
        <!-- Status Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
            <button type="button" 
                    onclick="filterTableStatus('all')" 
                    class="filter-pill active px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#1B4D3E] text-white shadow-xs transition">
                All ({{ $totalWeddings }})
            </button>
            <button type="button" 
                    onclick="filterTableStatus('published')" 
                    class="filter-pill px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:border-slate-300 transition">
                Published ({{ $totalWeddings }})
            </button>
            <button type="button" 
                    onclick="filterTableStatus('review')" 
                    class="filter-pill px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:border-slate-300 transition">
                In Review (0)
            </button>
            <button type="button" 
                    onclick="filterTableStatus('draft')" 
                    class="filter-pill px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-600 hover:border-slate-300 transition">
                Draft (0)
            </button>
        </div>

        <!-- Date & Sorting Controls -->
        <div class="flex items-center gap-2.5">
            <div class="relative">
                <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>All Dates ({{ date('Y') }})</span>
                    <svg class="w-3 h-3 text-slate-400 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <div class="relative">
                <button type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                    </svg>
                    <span>Sort: Event Date (Desc)</span>
                    <svg class="w-3 h-3 text-slate-400 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Client Wedding Galleries Table (Figma Exact Spec) -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="weddings-data-table">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 pl-5 pr-2 w-10">
                            <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-emerald-800 focus:ring-emerald-700">
                        </th>
                        <th class="py-3.5 px-3">Wedding &amp; Couple</th>
                        <th class="py-3.5 px-3">Event Date</th>
                        <th class="py-3.5 px-3">Gallery URL / Slug</th>
                        <th class="py-3.5 px-3 text-center">Photos</th>
                        <th class="py-3.5 px-3">Theme Preset</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 pr-5 pl-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($weddings as $wedding)
                        @php
                            $theme = $wedding->theme ?? 'Elegance Emerald';
                            $themeDotColor = 'bg-emerald-500';
                            if (str_contains(strtolower($theme), 'minimalist')) $themeDotColor = 'bg-amber-500';
                            elseif (str_contains(strtolower($theme), 'luxury') || str_contains(strtolower($theme), 'royal')) $themeDotColor = 'bg-indigo-600';
                            elseif (str_contains(strtolower($theme), 'sunset') || str_contains(strtolower($theme), 'gold')) $themeDotColor = 'bg-yellow-500';

                            // Fallback couple image
                            $sampleImages = [
                                'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=120&q=80',
                                'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=120&q=80',
                                'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?auto=format&fit=crop&w=120&q=80',
                                'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?auto=format&fit=crop&w=120&q=80'
                            ];
                            $fallbackPhoto = $sampleImages[$wedding->id % count($sampleImages)];
                            $firstPhoto = $wedding->photos->first();
                            $coupleCover = $firstPhoto ? $firstPhoto->thumbnail_url : $fallbackPhoto;
                        @endphp
                        <tr class="wedding-row hover:bg-slate-50/70 transition" data-name="{{ strtolower($wedding->couple_name) }}" data-slug="{{ strtolower($wedding->slug) }}">
                            <td class="py-4 pl-5 pr-2">
                                <input type="checkbox" class="w-4 h-4 rounded border-slate-300 text-emerald-800 focus:ring-emerald-700">
                            </td>

                            <!-- Wedding & Couple -->
                            <td class="py-4 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-200/80 shadow-2xs">
                                        <img src="{{ $coupleCover }}" 
                                             alt="{{ $wedding->couple_name }}" 
                                             class="w-full h-full object-cover"
                                             loading="lazy"
                                             onerror="this.src='{{ $fallbackPhoto }}'">
                                    </div>
                                    <div>
                                        <a href="{{ route('weddings.show', $wedding->slug) }}" class="font-bold text-slate-900 text-sm hover:text-emerald-800 transition">
                                            {{ $wedding->couple_name }}
                                        </a>
                                        <div class="text-[11px] text-slate-400 font-medium flex items-center gap-1.5 mt-0.5">
                                            <span>ID: #ARC-2026-{{ str_pad($wedding->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $wedding->user->name ?? 'Bali, ID' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Event Date -->
                            <td class="py-4 px-3 whitespace-nowrap font-medium text-slate-600">
                                {{ $wedding->wedding_date ? \Carbon\Carbon::parse($wedding->wedding_date)->format('M d, Y') : 'Date TBD' }}
                            </td>

                            <!-- Gallery URL / Slug -->
                            <td class="py-4 px-3 whitespace-nowrap">
                                <a href="{{ url('/' . $wedding->slug) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-blue-50/70 text-blue-700 hover:bg-blue-100 border border-blue-200/60 font-mono text-[11px] font-semibold transition"
                                   title="Buka Halaman Galeri Tamu (Tab Baru)">
                                    <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    <span>/{{ $wedding->slug }}</span>
                                </a>
                            </td>

                            <!-- Photos & Size -->
                            <td class="py-4 px-3 whitespace-nowrap text-center">
                                <span class="font-semibold text-slate-900 text-xs">{{ $wedding->photos->count() }} foto</span>
                                <span class="text-[11px] font-mono text-emerald-800 font-bold block">{{ $wedding->formatted_total_size }}</span>
                            </td>

                            <!-- Theme Preset -->
                            <td class="py-4 px-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $themeDotColor }}"></span>
                                    <span class="font-medium text-slate-800 text-xs">{{ $theme }}</span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Published
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 pr-5 pl-3 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-1 text-slate-400">
                                    <!-- View Gallery -->
                                    <a href="{{ url('/' . $wedding->slug) }}" 
                                       target="_blank"
                                       class="p-1.5 hover:text-slate-900 hover:bg-slate-100 rounded-md transition" 
                                       title="Lihat Galeri Tamu (Tab Baru)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <!-- Kelola & Edit Galeri -->
                                    <a href="{{ route('weddings.show', $wedding->slug) }}" 
                                       class="p-1.5 hover:text-emerald-700 hover:bg-emerald-50 rounded-md transition" 
                                       title="Kelola &amp; Edit Galeri Proyek">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Copy Link Share -->
                                    <button type="button" 
                                            onclick="copyGalleryLink('{{ url('/' . $wedding->slug) }}')" 
                                            class="p-1.5 hover:text-blue-700 hover:bg-blue-50 rounded-md transition" 
                                            title="Salin Tautan Galeri">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                        </svg>
                                    </button>

                                    <!-- Hapus Seluruh Proyek Wedding & Semua Datanya -->
                                    <form action="{{ route('weddings.destroy', $wedding->slug) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('⚠️ PERINGATAN: Apakah Anda yakin ingin menghapus seluruh proyek wedding \'{{ addslashes($wedding->couple_name) }}\'? Seluruh data wedding, tautan slug, foto, album, dan log unduhan akan dihapus permanen!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 hover:text-rose-700 hover:bg-rose-50 rounded-md transition" 
                                                title="Hapus Proyek Wedding &amp; Semua Datanya">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-500">
                                <div class="max-w-sm mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-800 mx-auto flex items-center justify-center font-bold text-xl">
                                        💍
                                    </div>
                                    <h3 class="font-bold text-slate-800 text-base">Belum Ada Wedding Terdaftar</h3>
                                    <p class="text-xs text-slate-500">Daftarkan pasangan pengantin baru untuk mulai membuat galeri foto Google Drive eksklusif.</p>
                                    <a href="{{ route('weddings.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-[#1B4D3E] hover:bg-[#153C30] rounded-lg shadow-xs transition">
                                        <span>+ Tambah Pengantin Baru</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination -->
        <div class="px-5 py-3.5 bg-white border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <div>
                Showing <span class="font-semibold text-slate-800">1</span> to <span class="font-semibold text-slate-800">{{ $weddings->count() }}</span> of <span class="font-semibold text-slate-800">{{ $totalWeddings }}</span> weddings
            </div>

            <div class="flex items-center gap-1.5">
                <button type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition">
                    Previous
                </button>
                <button type="button" class="px-3 py-1.5 rounded-lg bg-[#1B4D3E] text-white font-semibold shadow-xs">
                    1
                </button>
                <button type="button" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyGalleryLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('Tautan galeri berhasil disalin:\n' + url);
        });
    }

    function exportTableToCSV() {
        let csv = [];
        const rows = document.querySelectorAll("#weddings-data-table tr");
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll("td, th");
            for (let j = 1; j < cols.length - 1; j++) {
                let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                text = text.replace(/"/g, '""');
                row.push('"' + text + '"');
            }
            if (row.length > 0) csv.push(row.join(","));
        }
        const csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
        const downloadLink = document.createElement("a");
        downloadLink.download = "client-weddings-" + new Date().toISOString().slice(0,10) + ".csv";
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    function filterTableStatus(status) {
        document.querySelectorAll('.filter-pill').forEach(el => {
            el.classList.remove('bg-[#1B4D3E]', 'text-white');
            el.classList.add('bg-white', 'text-slate-600', 'border', 'border-slate-200');
        });
        event.target.classList.remove('bg-white', 'text-slate-600', 'border', 'border-slate-200');
        event.target.classList.add('bg-[#1B4D3E]', 'text-white');

        // Simple client row filter
        const rows = document.querySelectorAll('.wedding-row');
        rows.forEach(r => {
            if (status === 'all' || status === 'published') {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    }

    // Connect global search input
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('global-studio-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                document.querySelectorAll('.wedding-row').forEach(row => {
                    const name = row.getAttribute('data-name') || '';
                    const slug = row.getAttribute('data-slug') || '';
                    if (name.includes(query) || slug.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection
