@extends('layouts.admin')

@section('title', 'Antrean Validasi Bukti Struk')
@section('page_title', 'Antrean Validasi Bukti Struk Pembayaran')

@section('content')
<div class="space-y-6">
    <!-- Header Filter & Search Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0">
            <a href="{{ route('admin.moderation.index', ['status' => 'pending', 'q' => $search]) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Menunggu Validasi (Pending)
            </a>
            <a href="{{ route('admin.moderation.index', ['status' => 'verified', 'q' => $search]) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'verified' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Terverifikasi (Verified)
            </a>
            <a href="{{ route('admin.moderation.index', ['status' => 'unverified', 'q' => $search]) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'unverified' ? 'bg-slate-700 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Ditolak (Unverified)
            </a>
            <a href="{{ route('admin.moderation.index', ['status' => 'all', 'q' => $search]) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Semua Ulasan
            </a>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('admin.moderation.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="relative w-full md:w-64">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari user / tempat..." 
                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-colors">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.moderation.index', ['status' => $status]) }}" class="px-2.5 py-2 text-xs text-slate-500 hover:text-slate-800">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table of Moderation Items -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Tempat Kuliner</th>
                        <th class="px-6 py-4">Tanggal Ulasan</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Bukti Struk</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $review->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $review->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $review->place->name }}</div>
                                <div class="text-xs text-slate-500">{{ $review->place->city }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-600 whitespace-nowrap">
                                <div>{{ $review->created_at->format('d M Y') }}</div>
                                <div class="text-slate-400">{{ $review->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 font-bold text-xs border border-amber-200">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ number_format($review->overall_rating, 1) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($review->receipt)
                                    <a href="{{ route('admin.moderation.show', $review) }}" class="group block relative w-12 h-12 rounded-lg overflow-hidden border border-slate-200 shadow-sm bg-slate-100 hover:border-indigo-400">
                                        <img src="{{ $review->receipt->url }}" alt="Struk #{{ $review->id }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Tanpa struk</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($review->status === 'verified')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Verified (2x)
                                    </span>
                                @elseif($review->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                        Pending Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Unverified (1x)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.moderation.show', $review) }}" 
                                   class="inline-flex items-center gap-1 px-3.5 py-2 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition-colors">
                                    Periksa Detail
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="font-medium text-slate-600">Tidak ada ulasan ditemukan</p>
                                    <p class="text-xs text-slate-400">Semua ulasan dengan filter status saat ini telah selesai diproses.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
