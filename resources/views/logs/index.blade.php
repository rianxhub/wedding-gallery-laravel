@extends('layouts.app')

@section('title', 'Log Unduhan')
@section('breadcrumb', 'Log Unduhan')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Riwayat Log Unduhan File</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pencatatan real-time saat tamu mengunduh file foto atau arsip zip dari Google Drive ke database MySQL.
            </p>
        </div>

        <div>
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
                📥 Realtime Activity Tracking
            </span>
        </div>
    </div>

    <!-- Structured Table View -->
    <div class="bg-white border border-slate-200/90 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Log ID</th>
                        <th class="py-3 px-4">Nama File</th>
                        <th class="py-3 px-4">Pernikahan (Wedding)</th>
                        <th class="py-3 px-4">Drive File ID</th>
                        <th class="py-3 px-4">IP Address Tamu</th>
                        <th class="py-3 px-4 text-right">Waktu Pengunduhan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/60">
                            <td class="py-3 px-4 font-mono font-semibold text-slate-400">#{{ $log->id }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">
                                📥 {{ $log->file_name }}
                            </td>
                            <td class="py-3 px-4">
                                @if($log->wedding)
                                    <a href="{{ route('weddings.show', $log->wedding->slug) }}" class="text-emerald-800 hover:underline font-semibold">
                                        💍 {{ $log->wedding->couple_name }}
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <code class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-mono text-[11px]">
                                    {{ $log->drive_file_id }}
                                </code>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-mono text-[10px]">
                                    🌐 {{ $log->ip_address ?? '127.0.0.1' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="device-time-display font-medium text-slate-900" data-iso="{{ $log->downloaded_at ? $log->downloaded_at->toISOString() : '' }}">
                                    🕒 {{ $log->downloaded_at_formatted }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $log->downloaded_at_human }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Belum ada aktivitas pengunduhan yang tercatat di database MySQL.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-200 bg-white flex justify-center">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection
