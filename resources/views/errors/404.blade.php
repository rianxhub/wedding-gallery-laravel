<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oops, Album Not Found (404) - WedGallery</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #F8FAFC;
        }
        .serif-font {
            font-family: 'Playfair Display', Georgia, serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between text-slate-800 antialiased bg-slate-50">
    <!-- Top Header Bar (Figma Exact) -->
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 text-slate-900 group">
                    <div class="w-8 h-8 rounded-lg bg-emerald-900 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        📷
                    </div>
                    <span class="font-bold text-base tracking-tight text-slate-900">WedGallery</span>
                </a>
                <span class="text-xs text-slate-400 font-medium">Gallery</span>
            </div>

            <div class="flex items-center gap-4 text-xs font-semibold text-slate-600">
                <a href="mailto:support@wedgallery.studio" class="hidden sm:flex items-center gap-1.5 hover:text-slate-900 transition">
                    <span>Help / Contact Studio</span>
                </a>
                <a href="{{ route('weddings.index') }}" class="px-3.5 py-1.5 rounded-lg bg-[#1B4D3E] text-white hover:bg-[#153C30] transition shadow-xs">
                    Guest Login
                </a>
            </div>
        </div>
    </header>

    <!-- Center Stage Card (Figma Exact: Friendly 404 Error Page) -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 my-6">
        <div class="w-full max-w-2xl bg-white border border-slate-200/90 rounded-3xl p-8 sm:p-12 text-center shadow-lg space-y-6">
            <!-- Camera Aperture Illustration -->
            <div class="w-20 h-20 mx-auto rounded-full bg-emerald-50/70 border border-emerald-200/60 flex items-center justify-center relative">
                <div class="w-12 h-12 rounded-full bg-white shadow-inner border border-emerald-200 flex items-center justify-center text-emerald-800 text-xl font-bold">
                    🌸
                </div>
            </div>

            <!-- 404 Pill -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                <span>&bull;</span>
                <span>404 &bull; PAGE NOT FOUND</span>
            </div>

            <!-- Title & Description -->
            <div>
                <h1 class="serif-font text-3xl sm:text-4xl font-bold tracking-tight text-slate-900 leading-tight">
                    Oops, Album Not Found
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mt-3 leading-relaxed">
                    The wedding gallery or photo you are looking for might have been moved, set to private, or the link may have a typo. Don't worry—all published memories are safe and sound.
                </p>
            </div>

            <!-- Actions Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <a href="{{ url('/') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[#1B4D3E] hover:bg-[#153C30] text-white text-xs font-semibold shadow-xs transition flex items-center justify-center gap-2">
                    <span>&larr;</span>
                    <span>Return to Home</span>
                </a>

                <button type="button" 
                        onclick="promptPin()" 
                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition flex items-center justify-center gap-2">
                    <span>🔑</span>
                    <span>Enter Access PIN</span>
                </button>
            </div>

            <div class="pt-4 border-t border-slate-100 text-xs text-slate-500">
                Are you looking for an active wedding gallery? 
                <a href="{{ route('weddings.index') }}" class="font-bold text-emerald-800 hover:underline">
                    Lihat Daftar Wedding &rarr;
                </a>
            </div>
        </div>
    </main>

    <!-- Bottom Help Notification Bar (Figma Exact) -->
    <div class="max-w-2xl mx-auto px-4 pb-8 w-full">
        <div class="p-4 rounded-2xl bg-white border border-slate-200 text-xs text-slate-600 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5 text-center sm:text-left">
                <span class="text-base text-emerald-700">ℹ️</span>
                <span>Invited via digital invitation card? Direct access links typically include your 4-digit security token.</span>
            </div>
            <button type="button" onclick="alert('Silakan hubungi pasangan pengantin untuk link baru.')" class="font-bold text-emerald-800 hover:underline whitespace-nowrap">
                Resend My Link
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-xs text-slate-400">
        <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-slate-600 font-medium">
                <span class="font-bold text-slate-800">WedGallery</span>
                <span>|</span>
                <span>&copy; {{ date('Y') }} WedGallery Studio Platform. All rights reserved.</span>
            </div>
            <div class="flex items-center gap-4 text-slate-500">
                <a href="#" class="hover:text-slate-800">Privacy Policy</a>
                <a href="#" class="hover:text-slate-800">Download All (PIN Required)</a>
                <a href="#" class="hover:text-slate-800">Studio Contact</a>
                <a href="#" class="hover:text-slate-800">Terms</a>
            </div>
        </div>
    </footer>

    <script>
        function promptPin() {
            const pin = prompt('Masukkan 4-digit PIN akses galeri pengantin:');
            if (pin) {
                alert('Mencoba validasi PIN ' + pin + '... Jika valid, Anda akan dialihkan ke galeri pengantin.');
            }
        }
    </script>
</body>
</html>
