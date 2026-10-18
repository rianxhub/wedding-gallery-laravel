@extends('layouts.app')

@section('title', 'Riwayat Hapus Foto - ' . $wedding->couple_name)
@section('breadcrumb', 'Riwayat Hapus: ' . $wedding->couple_name)

@section('content')
<div class="space-y-6">
    <!-- Back Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('weddings.show', $wedding->slug) }}" 
           class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
            <span>&larr;</span>
            <span>Kembali ke Galeri {{ $wedding->couple_name }}</span>
        </a>

        @if($trashedPhotos->count() > 0)
            <form action="{{ route('weddings.trash.force-delete-all', $wedding->slug) }}" 
                  method="POST" 
                  onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SELURUH foto di riwayat ini secara permanen dari database MySQL? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 text-xs font-bold transition shadow-2xs flex items-center gap-1.5">
                    <span>🔥</span>
                    <span>Hapus Semua Permanen dari Database</span>
                </button>
            </form>
        @endif
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-xs font-bold">&times;</button>
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

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
                    💍 {{ $wedding->couple_name }}
                </span>
                <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-800 border border-rose-200 text-xs font-semibold">
                    🗑️ {{ $trashedPhotos->count() }} Foto Terhapus
                </span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Riwayat Hapus Foto Google Drive</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                Foto yang dihapus dari Google Drive otomatis disembunyikan dari galeri publik tamu, namun data metadatanya masih tersimpan di database MySQL. Anda dapat memulihkannya kembali atau menghapusnya secara permanen.
            </p>
        </div>
    </div>

    <!-- Info Box -->
    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/90 text-amber-900 text-xs space-y-1.5 shadow-2xs">
        <div class="font-bold flex items-center gap-1.5">
            <span>💡</span>
            <span>Cara Kerja Sistem Pengamanan Foto:</span>
        </div>
        <ul class="list-disc pl-5 space-y-1 text-amber-800 leading-relaxed">
            <li><strong>Otomatis Tersembunyi:</strong> Begitu foto dihapus dari Google Drive dan Anda melakukan sinkronisasi, foto langsung tidak tampil di galeri tamu.</li>
            <li><strong>Aman di Database:</strong> Datanya tetap aman tersimpan di sini agar riwayat unduhan atau metadata foto tidak hilang tanpa sengaja.</li>
            <li><strong>Pulihkan:</strong> Jika foto tidak sengaja terhapus dan ingin dikembalikan ke galeri publik, klik tombol <em>Pulihkan</em>.</li>
            <li><strong>Hapus Permanen:</strong> Menghapus record foto seutuhnya dari tabel database MySQL.</li>
        </ul>
    </div>

    @if($trashedPhotos->count() > 0)
        <!-- Photo Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($trashedPhotos as $photo)
                <div class="bg-white border border-rose-200 rounded-2xl overflow-hidden shadow-xs flex flex-col justify-between">
                    <div class="relative aspect-4/3 bg-slate-900 overflow-hidden">
                        <img src="{{ $photo->thumbnail_url }}" 
                             alt="{{ $photo->nama_file }}" 
                             class="w-full h-full object-cover filter grayscale opacity-75"
                             loading="lazy"
                             onerror="this.src='https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=400&q=80'">
                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded bg-black/70 text-white text-[10px] font-mono backdrop-blur-xs">
                            🗑️ Deleted
                        </span>
                    </div>

                    <div class="p-4 space-y-3 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-xs truncate" title="{{ $photo->nama_file }}">
                                {{ $photo->nama_file }}
                            </div>
                            <div class="text-[11px] font-mono text-slate-400 mt-0.5">
                                ID: {{ Str::limit($photo->drive_file_id, 16) }}
                            </div>
                            <div class="text-[11px] text-slate-400 mt-2">
                                Dihapus: {{ $photo->deleted_at ? $photo->deleted_at->translatedFormat('d M Y, H:i') : '-' }}
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 space-y-1.5">
                            <form action="{{ route('weddings.trash.restore', ['slug' => $wedding->slug, 'photoId' => $photo->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-1.5 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 text-xs font-bold transition">
                                    ♻️ Pulihkan ke Galeri
                                </button>
                            </form>

                            <form action="{{ route('weddings.trash.force-delete', ['slug' => $wedding->slug, 'photoId' => $photo->id]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto \'{{ $photo->nama_file }}\' secara PERMANEN dari database?');">
                                @csrf
                                <button type="submit" class="w-full py-1.5 rounded-lg bg-rose-50 text-rose-800 hover:bg-rose-100 border border-rose-200 text-xs font-bold transition">
                                    ❌ Hapus Permanen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Table View -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden mt-6">
            <div class="px-5 py-3.5 border-b border-slate-200 bg-slate-50/80 font-bold text-xs uppercase tracking-wider text-slate-600">
                Tabel Rincian Riwayat Hapus
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Nama File</th>
                            <th class="py-3 px-4">Drive File ID</th>
                            <th class="py-3 px-4">Waktu Dihapus</th>
                            <th class="py-3 px-4 text-right">Aksi Database</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($trashedPhotos as $index => $photo)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3 px-4 font-mono text-slate-400">#{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-bold text-slate-900">{{ $photo->nama_file }}</td>
                                <td class="py-3 px-4 font-mono text-slate-500">{{ $photo->drive_file_id }}</td>
                                <td class="py-3 px-4 text-slate-500">
                                    {{ $photo->deleted_at ? $photo->deleted_at->translatedFormat('d M Y, H:i:s') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <form action="{{ route('weddings.trash.restore', ['slug' => $wedding->slug, 'photoId' => $photo->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold hover:bg-emerald-100 transition">
                                                ♻️ Pulihkan
                                            </button>
                                        </form>

                                        <form action="{{ route('weddings.trash.force-delete', ['slug' => $wedding->slug, 'photoId' => $photo->id]) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Hapus permanen foto ini dari database?');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 rounded bg-rose-50 text-rose-800 border border-rose-200 text-xs font-semibold hover:bg-rose-100 transition">
                                                ❌ Hapus Permanen
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="p-12 text-center bg-white rounded-2xl border border-dashed border-slate-300">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center text-xl mx-auto mb-3">
                ✨
            </div>
            <h3 class="font-bold text-slate-900 text-base">Riwayat Hapus Bersih</h3>
            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-4">
                Tidak ada foto yang terdeteksi terhapus dari Google Drive. Seluruh foto yang tersimpan di database sinkron secara sempurna dengan file Google Drive Anda.
            </p>
            <a href="{{ route('weddings.show', $wedding->slug) }}" class="btn-emerald text-xs font-semibold px-4 py-2 rounded-lg inline-flex items-center gap-2">
                <span>&larr; Kembali ke Galeri Foto</span>
            </a>
        </div>
    @endif
</div>
@endsection
