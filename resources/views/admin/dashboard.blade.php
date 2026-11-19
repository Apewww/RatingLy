@extends('layouts.admin')

@section('title', 'Dashboard Moderasi')
@section('page_title', 'Ringkasan & Dashboard Moderasi')

@section('content')
<div class="space-y-8">
    <!-- 5 Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- 1. Total Tempat -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tempat Kuliner</span>
                <span class="p-2 rounded-xl bg-slate-100 text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold text-slate-900">{{ $stats['total_places'] }}</div>
                <p class="text-xs text-slate-500 mt-1">Cimahi &amp; Bandung</p>
            </div>
        </div>

        <!-- 2. Pending Reviews -->
        <a href="{{ route('admin.moderation.index', ['status' => 'pending']) }}" class="group bg-white p-5 rounded-2xl border border-amber-200 shadow-sm hover:border-amber-400 hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-700">Validasi Pending</span>
                <span class="p-2 rounded-xl bg-amber-50 text-amber-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold text-amber-900">{{ $stats['pending_reviews'] }}</div>
                <p class="text-xs text-amber-700 font-medium mt-1">Memerlukan verifikasi struk &rarr;</p>
            </div>
        </a>

        <!-- 3. Active Reports -->
        <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" class="group bg-white p-5 rounded-2xl border border-rose-200 shadow-sm hover:border-rose-400 hover:shadow-md transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-rose-700">Laporan Aktif</span>
                <span class="p-2 rounded-xl bg-rose-50 text-rose-600 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold text-rose-900">{{ $stats['active_reports'] }}</div>
                <p class="text-xs text-rose-700 font-medium mt-1">Keluhan pelanggaran &rarr;</p>
            </div>
        </a>

        <!-- 4. Verified Reviews -->
        <a href="{{ route('admin.moderation.index', ['status' => 'verified']) }}" class="group bg-white p-5 rounded-2xl border border-emerald-200 shadow-sm hover:border-emerald-400 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Ulasan Verified</span>
                <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold text-emerald-900">{{ $stats['verified_reviews'] }}</div>
                <p class="text-xs text-emerald-700 font-medium mt-1">Bobot 2x dalam agregat</p>
            </div>
        </a>

        <!-- 5. Unverified Reviews -->
        <a href="{{ route('admin.moderation.index', ['status' => 'unverified']) }}" class="group bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-slate-300 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Ulasan Unverified</span>
                <span class="p-2 rounded-xl bg-slate-100 text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-bold text-slate-900">{{ $stats['unverified_reviews'] }}</div>
                <p class="text-xs text-slate-500 font-medium mt-1">Bobot 1x standar</p>
            </div>
        </a>
    </div>

    <!-- Rating Weighting Algorithm Explainer Card -->
    <div class="p-5 bg-gradient-to-r from-indigo-900 to-slate-900 rounded-2xl text-white shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30">
                    Kaidah Algoritma RatingLy
                </span>
                <span class="text-xs text-slate-300">Modul 4 &bull; Engine Pembobotan Struk</span>
            </div>
            <h3 class="text-base font-semibold">Rumus Agregat Rating: Verified Review Bernilai 2x Lebih Tinggi</h3>
            <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                Rating total dihitung otomatis oleh <code class="text-indigo-300 bg-slate-800/80 px-1 py-0.5 rounded">RatingCalculationService</code> setiap kali Admin menyetujui, menolak, atau men-take down ulasan dengan rumus: 
                <span class="font-mono text-indigo-200 font-medium">Rating = &sum;(Skor &times; Bobot) / &sum;Bobot</span>. Ulasan berstruk terverifikasi mencegah manipulasi ulasan palsu (anti-spam).
            </p>
        </div>
        <a href="{{ route('admin.moderation.index') }}" class="shrink-0 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors">
            Validasi Antrean Sekarang &rarr;
        </a>
    </div>

    <!-- Tables Section: 2 Columns on Desktop -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Antrean Validasi Struk Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <h2 class="text-sm font-bold text-slate-900">Antrean Validasi Struk Terbaru</h2>
                </div>
                <a href="{{ route('admin.moderation.index', ['status' => 'pending']) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">Pengguna</th>
                            <th class="px-4 py-3">Tempat</th>
                            <th class="px-4 py-3">Struk</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stats['recent_pending'] as $review)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">{{ $review->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-800">{{ $review->place->name }}</div>
                                    <div class="flex items-center gap-1 text-xs text-amber-600 font-semibold">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        {{ number_format($review->overall_rating, 1) }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($review->receipt)
                                        <img src="{{ $review->receipt->url }}" alt="Struk" class="w-10 h-10 object-cover rounded-lg border border-slate-200">
                                    @else
                                        <span class="text-xs text-slate-400">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.moderation.show', $review) }}" class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors">
                                        Periksa
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-400 text-xs">
                                    Tidak ada ulasan pending yang menunggu validasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Laporan Ulasan Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <h2 class="text-sm font-bold text-slate-900">Laporan Pelanggaran Terbaru</h2>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-medium text-rose-600 hover:text-rose-800">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">Pelapor</th>
                            <th class="px-4 py-3">Ulasan Dilaporkan</th>
                            <th class="px-4 py-3">Alasan</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($stats['recent_reports'] as $report)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">{{ $report->reporter->name ?? 'User' }}</div>
                                    <div class="text-xs text-slate-400">{{ $report->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-800">{{ $report->review->place->name ?? 'Tempat' }}</div>
                                    <div class="text-xs text-slate-500 line-clamp-1 italic">"{{ $report->review->content ?? '-' }}"</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200 max-w-[180px] truncate">
                                        {{ $report->reason }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 transition-colors">
                                        Moderasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-400 text-xs">
                                    Tidak ada laporan pelanggaran yang aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
