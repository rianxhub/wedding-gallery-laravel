@extends('layouts.app')

@section('title', 'Pusat Penyimpanan Google Drive & Album')
@section('breadcrumb', 'Penyimpanan & Album')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pusat Penyimpanan Google Drive &amp; Album</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pantau kuota penyimpanan cloud Google Drive, penggunaan kapasitas (size) setiap acara pernikahan, dan struktur subfolder album.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Google Drive Terhubung</span>
            </span>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- GOOGLE DRIVE STORAGE HERO OVERVIEW CARD                      -->
    <!-- Menampilkan kapasitas total, terpakai, dan sisa storage      -->
    <!-- ============================================================ -->
    <div class="bg-gradient-to-br from-[#0B1727] via-[#0E2028] to-[#122E26] rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-emerald-900/30 relative overflow-hidden">
        <!-- Ambient Glow -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-6">
            <!-- Top Row: Title & Account Email -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-white/10">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-2xl shadow-inner">
                        ☁️
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-white tracking-tight">Kapasitas Cloud Storage Google Drive</h2>
                        <div class="text-xs text-emerald-300/80 font-mono flex items-center gap-1.5 mt-0.5">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>{{ $storage['account_email'] ?: 'Koneksi Service Account & Google API Key' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="https://drive.google.com" 
                       target="_blank" 
                       class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-semibold backdrop-blur-md border border-white/15 transition flex items-center gap-1.5 shadow-sm">
                        <span>Buka Google Drive Console</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Visual Progress Bar -->
            <div class="space-y-2">
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-slate-200">Penggunaan Storage:</span>
                        <span class="font-mono font-bold text-emerald-400">{{ $storage['formatted_usage'] }}</span>
                        <span class="text-slate-400">dari</span>
                        <span class="font-mono font-semibold text-white">{{ $storage['formatted_limit'] }}</span>
                    </div>
                    <div class="font-mono font-bold text-xs px-2.5 py-0.5 rounded-full bg-white/10 text-emerald-300 border border-white/10">
                        {{ $storage['usage_percentage'] }}% Terpakai
                    </div>
                </div>

                <!-- Tailwind Progress Bar -->
                <div class="w-full h-3.5 bg-black/40 rounded-full overflow-hidden p-0.5 border border-white/10">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 via-teal-400 to-amber-400 transition-all duration-1000 ease-out shadow-sm"
                         style="width: {{ max(1, min(100, $storage['usage_percentage'])) }}%;"></div>
                </div>

                <div class="flex items-center justify-between text-[11px] text-slate-400 pt-0.5">
                    <span>🟢 Terpakai: <strong class="text-slate-200">{{ $storage['formatted_usage'] }}</strong></span>
                    <span>✨ Sisa Ruang Bebas: <strong class="text-emerald-300">{{ $storage['formatted_free'] }}</strong></span>
                </div>
            </div>

            <!-- 4 Summary Stat Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 pt-2">
                <!-- Stat 1: Total Kapasitas -->
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md space-y-1">
                    <div class="text-[11px] uppercase tracking-wider text-slate-400 font-medium">Total Kuota Drive</div>
                    <div class="text-xl sm:text-2xl font-bold font-mono text-white">{{ $storage['formatted_limit'] }}</div>
                    <div class="text-[10px] text-emerald-300/70">Kapasitas penyimpanan Google</div>
                </div>

                <!-- Stat 2: Terpakai -->
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md space-y-1">
                    <div class="text-[11px] uppercase tracking-wider text-slate-400 font-medium">Storage Terpakai</div>
                    <div class="text-xl sm:text-2xl font-bold font-mono text-amber-300">{{ $storage['formatted_usage'] }}</div>
                    <div class="text-[10px] text-slate-400">Total data foto aktif</div>
                </div>

                <!-- Stat 3: Sisa Kuota -->
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md space-y-1">
                    <div class="text-[11px] uppercase tracking-wider text-slate-400 font-medium">Sisa Kuota Tersedia</div>
                    <div class="text-xl sm:text-2xl font-bold font-mono text-emerald-400">{{ $storage['formatted_free'] }}</div>
                    <div class="text-[10px] text-emerald-300/70">Ruang kosong siap diisi foto</div>
                </div>

                <!-- Stat 4: Total Foto -->
                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md space-y-1">
                    <div class="text-[11px] uppercase tracking-wider text-slate-400 font-medium">Total Foto Tersimpan</div>
                    <div class="text-xl sm:text-2xl font-bold font-mono text-white">{{ $storage['total_photos_count'] }} <span class="text-xs font-normal text-slate-400">Foto</span></div>
                    <div class="text-[10px] text-slate-400">Dari {{ $weddings->count() }} acara pernikahan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SECTION: PENGGUNAAN SIZE SETIAP WEDDING                      -->
    <!-- Menampilkan ukuran file foto untuk setiap wedding project    -->
    <!-- ============================================================ -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Rincian Ukuran Penyimpanan (Size) Setiap Wedding</h2>
                <p class="text-xs text-slate-500">Ukuran kapasitas nyata dari setiap proyek pernikahan di cloud Google Drive.</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                💍 {{ $weddings->count() }} Acara Pernikahan
            </span>
        </div>

        <!-- Wedding Storage Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($weddings as $wedding)
                <div class="bg-white border border-slate-200/90 rounded-2xl p-5 shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between space-y-4">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base leading-snug">
                                    {{ $wedding->couple_name }}
                                </h3>
                                <div class="text-xs text-slate-400 flex items-center gap-1.5 mt-0.5 font-medium">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ $wedding->wedding_date ? \Carbon\Carbon::parse($wedding->wedding_date)->translatedFormat('d M Y') : 'Tanggal Belum Diatur' }}</span>
                                </div>
                            </div>

                            <!-- Size Badge -->
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-mono text-xs font-bold shadow-2xs">
                                    💾 {{ $wedding->formatted_total_size }}
                                </span>
                            </div>
                        </div>

                        <!-- Theme & Stats Strip -->
                        <div class="p-3 bg-slate-50 border border-slate-200/70 rounded-xl space-y-2 text-xs">
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Jumlah Foto Aktif:</span>
                                <span class="font-bold text-slate-900 font-mono">{{ $wedding->photos->count() }} Foto</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Subfolder Album:</span>
                                <span class="font-bold text-slate-900 font-mono">{{ $wedding->albums->count() }} Folder</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span>Tema Tampilan:</span>
                                <span class="font-semibold text-slate-800">{{ $wedding->theme ?: 'Elegance Emerald' }}</span>
                            </div>
                        </div>

                        <!-- Subfolders tags if available -->
                        @if($wedding->albums->count() > 0)
                            <div class="mt-3">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Subfolder Terdaftar:</div>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($wedding->albums as $alb)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-medium border border-slate-200">
                                            📁 {{ $alb->album_name }}
                                            <span class="text-[10px] text-slate-500 font-bold">({{ $alb->photos->count() }})</span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <a href="{{ route('weddings.show', $wedding->slug) }}" 
                           class="flex-1 py-1.5 px-3 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold text-center transition">
                            👁️ Galeri Admin
                        </a>

                        <a href="{{ route('client.pin', $wedding->slug) }}" 
                           target="_blank" 
                           class="flex-1 py-1.5 px-3 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-semibold text-center border border-emerald-200 transition">
                            🔗 Link Tamu
                        </a>

                        <a href="https://drive.google.com/drive/folders/{{ $wedding->drive_folder_id }}" 
                           target="_blank" 
                           class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 transition" 
                           title="Buka Folder Google Drive">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-8 text-center bg-white rounded-2xl border border-dashed border-slate-300 text-slate-400 text-xs">
                    Belum ada proyek pernikahan yang dibuat.
                </div>
            @endforelse
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- STRUCTURED TABLE: RINCIAN LENGKAP PENYIMPANAN PER WEDDING    -->
    <!-- ============================================================ -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden mt-6">
        <div class="px-5 py-3.5 border-b border-slate-200 bg-slate-50/80 flex items-center justify-between">
            <span class="font-bold text-xs uppercase tracking-wider text-slate-600">Tabel Kapasitas Storage &amp; Folder Wedding</span>
            <span class="text-xs text-slate-400">{{ $weddings->count() }} Data Acara</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Pasangan</th>
                        <th class="py-3 px-4">Folder ID Drive</th>
                        <th class="py-3 px-4 text-center">Jumlah Foto</th>
                        <th class="py-3 px-4 text-center">Subfolder Album</th>
                        <th class="py-3 px-4 text-right">Ukuran Size</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($weddings as $w)
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3 px-4 font-bold text-slate-900">
                                <a href="{{ route('weddings.show', $w->slug) }}" class="hover:text-emerald-700 transition">
                                    💍 {{ $w->couple_name }}
                                </a>
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-500 text-[11px]">
                                {{ Str::limit($w->drive_folder_id, 24) }}
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-800">
                                {{ $w->photos->count() }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-semibold text-[11px]">
                                    {{ $w->albums->count() }} Folder
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-emerald-800">
                                {{ $w->formatted_total_size }}
                            </td>
                            <td class="py-3 px-4 text-right space-x-1">
                                <a href="{{ route('weddings.show', $w->slug) }}" class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition">
                                    Detail
                                </a>
                                <a href="https://drive.google.com/drive/folders/{{ $w->drive_folder_id }}" target="_blank" class="px-2.5 py-1 rounded bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold text-[11px] transition">
                                    📂 Drive
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
