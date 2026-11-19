@extends('layouts.admin')

@section('title', 'Detail Validasi Ulasan #' . $review->id)
@section('page_title', 'Pemeriksaan & Validasi Bukti Struk')

@section('content')
<div x-data="{ 
    zoomModal: false, 
    approveModal: false, 
    rejectModal: false, 
    takeDownModal: false,
    selectedImage: '{{ $review->receipt ? $review->receipt->url : '' }}'
}" class="space-y-6">

    <!-- Top Navigation Bar & Status -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.moderation.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-slate-900">Ulasan #{{ $review->id }} &bull; {{ $review->place->name }}</h2>
                    @if($review->status === 'verified')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Verified (Bobot 2x)
                        </span>
                    @elseif($review->status === 'pending')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            Pending Validation
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                            Unverified (Bobot 1x)
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-400">Diajukan pada {{ $review->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        <!-- Moderation Quick Actions -->
        <div class="flex items-center gap-2">
            @if($review->isPending())
                <button type="button" @click="rejectModal = true" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak Struk (Reject)
                </button>
                <button type="button" @click="approveModal = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Setujui Struk (Approve)
                </button>
            @else
                <div class="text-xs text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
                    Dimoderasi oleh <strong class="text-slate-700">{{ $review->verifier->name ?? 'Admin' }}</strong>
                    pada {{ $review->verified_at ? $review->verified_at->format('d M Y, H:i') : '-' }}
                </div>
            @endif

            <button type="button" @click="takeDownModal = true" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors" title="Take Down Ulasan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>

    <!-- 2 Columns Grid: Left (Evidence & Media) vs Right (Review & Place Detail) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Receipt & Media Inspection (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Receipt Evidence Card -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h3 class="text-sm font-bold text-slate-900">Bukti Struk Pembayaran</h3>
                    </div>
                    <span class="text-xs text-slate-400 font-medium">Klik untuk memperbesar</span>
                </div>

                @if($review->receipt)
                    <div class="relative group rounded-xl overflow-hidden border border-slate-200 bg-slate-50 cursor-pointer"
                         @click="selectedImage = '{{ $review->receipt->url }}'; zoomModal = true">
                        <img src="{{ $review->receipt->url }}" alt="Bukti Struk" class="w-full h-96 object-contain bg-slate-100 p-2 group-hover:scale-102 transition-transform">
                        <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="px-3 py-1.5 bg-white/90 text-slate-900 rounded-lg text-xs font-semibold shadow-md flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Periksa Struk Resolusi Penuh
                            </span>
                        </div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs text-slate-600 space-y-1">
                        <p class="font-semibold text-slate-800">Panduan Verifikasi Struk:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-slate-500">
                            <li>Pastikan nama resto/kafe sesuai dengan: <strong class="text-slate-700">{{ $review->place->name }}</strong>.</li>
                            <li>Periksa tanggal transaksi dan status lunas / QRIS / Tunai.</li>
                            <li>Pastikan bukan struk rekayasa, duplikat, atau dari tempat lain.</li>
                        </ul>
                    </div>
                @else
                    <div class="p-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200 text-slate-400 text-xs">
                        Tidak ada bukti struk pembayaran terlampir pada ulasan ini.
                    </div>
                @endif
            </div>

            <!-- Uploaded Place Photos -->
            @php
                $placePhotos = $review->media->where('media_type', 'place_photo');
            @endphp
            @if($placePhotos->isNotEmpty())
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                    <h3 class="text-sm font-bold text-slate-900">Foto Suasana / Menu yang Diunggah</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($placePhotos as $photo)
                            <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-100 cursor-pointer hover:opacity-90 transition-opacity"
                                 @click="selectedImage = '{{ $photo->url }}'; zoomModal = true">
                                <img src="{{ $photo->url }}" alt="Foto Tempat" class="w-full h-32 object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: User Info, 4-Criteria Scores, & Narrative (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- User & Place Info Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- User Card -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Penulis Ulasan</span>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-sm">
                            {{ substr($review->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 text-sm">{{ $review->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $review->user->email }}</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Total Ulasan User</span>
                        <strong class="text-slate-800">{{ $review->user->reviews()->count() }} ulasan</strong>
                    </div>
                </div>

                <!-- Place Card -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Tempat Kuliner</span>
                    <div>
                        <p class="font-bold text-slate-900 text-sm">{{ $review->place->name }}</p>
                        <p class="text-xs text-slate-500">{{ $review->place->category->name ?? 'Kuliner' }} &bull; {{ $review->place->city }}</p>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Rating Agregat Tempat</span>
                        <div class="flex items-center gap-1 font-bold text-amber-600">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ number_format($review->place->avg_rating, 2) }} ({{ $review->place->total_reviews }} ulasan)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Narrative Content -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Narasi Ulasan Pelanggan</h3>
                    <div class="flex items-center gap-1 bg-amber-50 px-2.5 py-1 rounded-lg text-amber-800 font-bold text-xs border border-amber-200">
                        <svg class="w-3.5 h-3.5 fill-current text-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Skor Total: {{ number_format($review->overall_rating, 1) }} / 5.0
                    </div>
                </div>
                <p class="text-sm text-slate-700 leading-relaxed italic bg-slate-50/70 p-4 rounded-xl border border-slate-100">
                    "{{ $review->content }}"
                </p>
                @if($review->rejection_reason)
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-800">
                        <strong>Catatan Penolakan Sebelumnya:</strong> {{ $review->rejection_reason }}
                    </div>
                @endif
            </div>

            <!-- 4-Criteria Score Grid (Taste, Ambience, Wi-Fi, Price) -->
            @php
                $scores = $review->criteriaScore;
            @endphp
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Rincian Multi-Criteria Rating (1.0 – 5.0)</h3>
                    <p class="text-xs text-slate-500">4 Parameter terpisah sesuai spesifikasi SDD RatingLy</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- 1. Taste Score -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-slate-700 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                1. Rasa Makanan / Minuman
                            </span>
                            <span class="text-slate-900 font-bold">{{ number_format($scores->taste_score ?? 1.0, 1) }}</span>
                        </div>
                        <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full" style="width: {{ (($scores->taste_score ?? 1.0) / 5) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- 2. Ambience Score -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-slate-700 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                2. Suasana &amp; Kenyamanan
                            </span>
                            <span class="text-slate-900 font-bold">{{ number_format($scores->ambience_score ?? 1.0, 1) }}</span>
                        </div>
                        <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-indigo-500 h-full rounded-full" style="width: {{ (($scores->ambience_score ?? 1.0) / 5) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- 3. Wi-Fi & Socket Score -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-slate-700 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                3. Wi-Fi &amp; Colokan Listrik
                            </span>
                            <span class="text-slate-900 font-bold">{{ number_format($scores->wifi_socket_score ?? 1.0, 1) }}</span>
                        </div>
                        <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-sky-500 h-full rounded-full" style="width: {{ (($scores->wifi_socket_score ?? 1.0) / 5) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- 4. Price-to-Value Score -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 space-y-2">
                        <div class="flex items-center justify-between text-xs font-semibold">
                            <span class="text-slate-700 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                4. Rasio Nilai Harga
                            </span>
                            <span class="text-slate-900 font-bold">{{ number_format($scores->price_value_score ?? 1.0, 1) }}</span>
                        </div>
                        <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: {{ (($scores->price_value_score ?? 1.0) / 5) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            @if($review->isPending())
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-500">
                        <strong class="text-slate-800">Status Saat Ini:</strong> Menunggu Verifikasi Struk.
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <button type="button" @click="rejectModal = true" class="flex-1 sm:flex-initial px-5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-colors">
                            Tolak Bukti Struk
                        </button>
                        <button type="button" @click="approveModal = true" class="flex-1 sm:flex-initial px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition-colors">
                            Setujui &amp; Verifikasi (Approve)
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- MODAL 1: ZOOM IMAGE MODAL -->
    <div x-show="zoomModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4"
         @click.self="zoomModal = false">
        <div class="relative bg-white rounded-2xl max-w-4xl w-full p-4 shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                <h4 class="text-sm font-bold text-slate-900">Preview Resolusi Penuh</h4>
                <button type="button" @click="zoomModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="max-h-[80vh] overflow-auto flex items-center justify-center bg-slate-50 rounded-xl p-2">
                <img :src="selectedImage" alt="Zoomed Struk" class="max-w-full h-auto object-contain">
            </div>
        </div>
    </div>

    <!-- MODAL 2: APPROVE CONFIRMATION MODAL -->
    <div x-show="approveModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4"
         @click.self="approveModal = false">
        <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl text-center space-y-4">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto border border-emerald-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">Setujui Bukti Struk Pembayaran?</h3>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    Ulasan ini akan mendapatkan lencana <strong>Verified (Bobot 2x)</strong>. Rating agregat tempat <strong>{{ $review->place->name }}</strong> akan otomatis dihitung ulang.
                </p>
            </div>
            <form method="POST" action="{{ route('admin.moderation.approve', $review) }}" class="flex items-center gap-3 pt-2">
                @csrf
                <button type="button" @click="approveModal = false" class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-sm">
                    Ya, Setujui Struk
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 3: REJECT CONFIRMATION MODAL -->
    <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4"
         @click.self="rejectModal = false">
        <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Tolak Bukti Struk Pembayaran</h3>
                    <p class="text-xs text-slate-500">Status ulasan akan menjadi <strong>Unverified (Bobot 1x)</strong>.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.moderation.reject', $review) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Penolakan (Opsional)</label>
                    <textarea name="reason" rows="3" placeholder="Contoh: Foto struk buram, tanggal struk kedaluwarsa, atau tempat berbeda..."
                              class="w-full text-xs p-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 bg-slate-50"></textarea>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="rejectModal = false" class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-xl shadow-sm">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: TAKE DOWN MODAL -->
    <div x-show="takeDownModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4"
         @click.self="takeDownModal = false">
        <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Take Down Ulasan Ini?</h3>
                    <p class="text-xs text-slate-500">Ulasan akan disembunyikan (soft-delete) dari halaman publik.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.reviews.takedown', $review) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tindakan Moderasi</label>
                    <textarea name="reason" rows="3" placeholder="Masukkan alasan ulasan di-takedown (misal: spam judi, ujaran kebencian, dll)..."
                              class="w-full text-xs p-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 bg-slate-50" required></textarea>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="button" @click="takeDownModal = false" class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl shadow-sm">
                        Ya, Sembunyikan Ulasan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
