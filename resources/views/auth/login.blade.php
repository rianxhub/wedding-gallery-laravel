<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="WedGallery Studio Admin - Portal Masuk Administrator">
    <title>Admin Login - WedGallery Studio</title>

    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
<body class="min-h-full bg-[#08111D] text-slate-100 flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8 relative selection:bg-emerald-500 selection:text-white">

    <!-- Atmospheric Ambient Glows -->
    <div class="fixed top-0 left-1/4 w-[500px] h-[500px] bg-emerald-600/10 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="fixed bottom-0 right-1/4 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[140px] pointer-events-none"></div>

    <!-- Main Container -->
    <div class="w-full max-w-md relative z-10 space-y-6">

        <!-- Brand Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-950 to-slate-900 border border-emerald-500/30 text-emerald-400 shadow-xl shadow-emerald-950/50 mb-1">
                <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold tracking-wider text-white">WEDGALLERY</h1>
            <p class="serif-font italic text-sm text-emerald-200/80">Studio Administrator Portal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-[#0E1A29]/80 backdrop-blur-xl border border-slate-700/60 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-black/60 space-y-6">
            
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-lg font-bold text-white tracking-tight">Masuk ke Dasbor</h2>
                <p class="text-xs text-slate-400 mt-1">Gunakan akun admin untuk mengelola galeri pernikahan klien.</p>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-950/70 border border-rose-800/80 text-rose-200 text-xs space-y-1">
                    <div class="font-bold flex items-center gap-1.5 text-rose-300">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Gagal Masuk</span>
                    </div>
                    @foreach ($errors->all() as $error)
                        <p class="text-[11px] leading-relaxed">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('error'))
                <div class="p-3.5 rounded-xl bg-rose-950/60 border border-rose-800/70 text-rose-200 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="p-3.5 rounded-xl bg-blue-950/60 border border-blue-800/70 text-blue-200 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-slate-300">
                        Alamat Email Admin
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </span>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', 'admin@weddinggallery.com') }}" 
                               required 
                               autocomplete="email" 
                               autofocus 
                               placeholder="admin@weddinggallery.com" 
                               class="w-full pl-10 pr-4 py-2.5 bg-[#08111D] border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-semibold text-slate-300">
                            Kata Sandi
                        </label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="current-password" 
                               placeholder="••••••••••••" 
                               value="password123"
                               class="w-full pl-10 pr-4 py-2.5 bg-[#08111D] border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-400 hover:text-slate-200">
                        <input type="checkbox" name="remember" value="1" checked class="w-4 h-4 rounded bg-[#08111D] border-slate-700 text-emerald-600 focus:ring-emerald-500">
                        <span>Ingat sesi saya</span>
                    </label>
                    <span class="text-[11px] text-emerald-400/80">Role: Admin Only</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full mt-2 py-3 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs tracking-wider uppercase rounded-xl shadow-lg shadow-emerald-950/60 hover:shadow-emerald-900/80 transition-all duration-200 flex items-center justify-center gap-2">
                    <span>Masuk ke Dashboard</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <!-- Demo Account Credentials Helper -->
            <div class="p-3.5 rounded-xl bg-slate-900/60 border border-slate-800 text-[11px] text-slate-400 space-y-1">
                <div class="font-bold text-slate-300 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Kredensial Default Admin:</span>
                </div>
                <div class="flex justify-between font-mono text-[10px] text-slate-300 pt-0.5">
                    <span>Email: admin@weddinggallery.com</span>
                    <span>Sandi: password123</span>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} WedGallery Cloud Platform. All rights reserved.
        </p>
    </div>

</body>
</html>
