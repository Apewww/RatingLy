@extends('layouts.admin')

@section('title', 'Laporan Ulasan Pengguna')
@section('page_title', 'Moderasi Laporan Pelanggaran Ulasan')

@section('content')
<div x-data="{ 
    takeDownModal: false, 
    dismissModal: false, 
    activeReviewId: null, 
    activeReportId: null,
    targetPlace: '',
    targetReviewContent: ''
}" class="space-y-6">

    <!-- Header Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
            <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'pending' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Menunggu Tindakan (Pending)
            </a>
            <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'resolved' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Telah Di-Takedown (Resolved)
            </a>
            <a href="{{ route('admin.reports.index', ['status' => 'dismissed']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'dismissed' ? 'bg-slate-700 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Laporan Ditolak (Dismissed)
            </a>
            <a href="{{ route('admin.reports.index', ['status' => 'all']) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors {{ $status === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                Semua Laporan
            </a>
        </div>

        <div class="text-xs text-slate-500">
            Menampilkan <strong>{{ $reports->total() }}</strong> total laporan
        </div>
    </div>

    <!-- Table of Reported Reviews -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Pelapor</th>
                        <th class="px-6 py-4">Tempat &amp; Pembuat Ulasan</th>
                        <th class="px-6 py-4">Isi Ulasan yang Dilaporkan</th>
                        <th class="px-6 py-4">Alasan Pelanggaran</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Tindakan Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $report->reporter->name ?? 'User' }}</div>
                                <div class="text-xs text-slate-500">{{ $report->reporter->email ?? '-' }}</div>
                                <div class="text-xs text-slate-400 mt-1">{{ $report->created_at->format('d M Y, H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $report->review->place->name ?? 'Tempat' }}</div>
                                <div class="text-xs text-slate-500">Ulasan oleh: <strong class="text-slate-700">{{ $report->review->user->name ?? 'User' }}</strong></div>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs text-slate-700 italic">
                                    "{{ $report->review->content ?? '[Ulasan telah dihapus]' }}"
                                </div>
                                @if($report->review && $report->review->trashed())
                                    <span class="inline-block mt-1 text-[11px] font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">
                                        Status: Ulasan Telah Di-Take Down
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-rose-50 text-rose-800 border border-rose-200">
                                    {{ $report->reason }}
                                </span>
                                @if($report->action_taken)
                                    <p class="text-xs text-slate-500 mt-1.5"><strong class="text-slate-700">Tindakan:</strong> {{ $report->action_taken }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($report->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
                                        Pending Action
                                    </span>
                                @elseif($report->status === 'resolved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Resolved (Takedown)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        Dismissed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($report->isPending())
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" 
                                                @click="activeReportId = {{ $report->id }}; dismissModal = true" 
                                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                            Abaikan
                                        </button>
                                        @if($report->review && ! $report->review->trashed())
                                            <button type="button" 
                                                    @click="activeReviewId = {{ $report->review->id }}; targetPlace = '{{ addslashes($report->review->place->name ?? '') }}'; targetReviewContent = '{{ addslashes($report->review->content ?? '') }}'; takeDownModal = true" 
                                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition-colors">
                                                Take Down
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">Selesai dimoderasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="font-medium text-slate-600">Tidak ada laporan pelanggaran</p>
                                    <p class="text-xs text-slate-400">Semua laporan dari pengguna dengan status ini telah ditangani.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL 1: TAKE DOWN FROM REPORT -->
    <div x-show="takeDownModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4"
         @click.self="takeDownModal = false">
        <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Take Down Ulasan Melanggar?</h3>
                    <p class="text-xs text-slate-500">Tempat: <span class="font-semibold text-slate-700" x-text="targetPlace"></span></p>
                </div>
            </div>

            <p class="text-xs text-slate-600 p-3 bg-slate-50 rounded-xl border border-slate-100 italic" x-text="targetReviewContent"></p>

            <form :action="'/admin/reviews/' + activeReviewId + '/takedown'" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Take Down &amp; Catatan Moderasi</label>
                    <textarea name="reason" rows="3" placeholder="Masukkan alasan take down (contoh: terbukti melanggar aturan komunitas)..."
                              class="w-full text-xs p-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 bg-slate-50" required></textarea>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="takeDownModal = false" class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-sm">
                        Ya, Take Down Ulasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: DISMISS REPORT -->
    <div x-show="dismissModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4"
         @click.self="dismissModal = false">
        <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Abaikan Laporan Ini?</h3>
                    <p class="text-xs text-slate-500">Laporan akan ditandai sebagai <strong>Dismissed</strong> tanpa mengubah ulasan.</p>
                </div>
            </div>

            <form :action="'/admin/reports/' + activeReportId + '/dismiss'" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Penolakan Laporan (Opsional)</label>
                    <input type="text" name="reason" placeholder="Contoh: Ulasan tidak terbukti melanggar aturan..."
                           class="w-full text-xs p-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50">
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="dismissModal = false" class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl shadow-sm">
                        Ya, Abaikan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
