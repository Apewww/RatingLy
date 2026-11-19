<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RatingLy Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6 bg-slate-100">
    <div class="max-w-md w-full space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex w-12 h-12 rounded-2xl bg-indigo-600 items-center justify-center text-white font-bold text-2xl shadow-md">
                R
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">RatingLy</h1>
            <p class="text-xs text-slate-500">Platform Aggregator Ulasan Tempat Kuliner Cimahi &amp; Bandung</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div>
                <h2 class="text-base font-bold text-slate-900">Masuk ke Sistem</h2>
                <p class="text-xs text-slate-500">Masukkan kredensial akun Anda untuk melanjutkan.</p>
            </div>

            <!-- Flash Error -->
            @if(session('error'))
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="p-3.5 rounded-xl bg-sky-50 border border-sky-200 text-sky-700 text-xs font-medium">
                    {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                    @foreach($errors->all() as $error)
                        <p>&bull; {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', 'admin@ratingly.com') }}" required autofocus
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" value="password" required
                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition-colors">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Quick Login Shortcuts for Testing -->
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider text-center">
                    Tombol Uji Coba Cepat (Testing Helper)
                </span>
                <div class="grid grid-cols-2 gap-2">
                    <form method="POST" action="{{ route('quick-login') }}">
                        @csrf
                        <input type="hidden" name="role" value="admin">
                        <button type="submit" class="w-full py-2 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold transition-colors">
                            Login Admin
                        </button>
                    </form>

                    <form method="POST" action="{{ route('quick-login') }}">
                        @csrf
                        <input type="hidden" name="role" value="member">
                        <button type="submit" class="w-full py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold transition-colors">
                            Login Member
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400">
            RatingLy &bull; Panel Moderasi Admin Modul 5
        </p>
    </div>
</body>
</html>
