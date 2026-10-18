@php
    $theme = $wedding->theme_config;
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Galeri Pernikahan - {{ $wedding->couple_name }}</title>
    <meta name="description" content="Album foto pernikahan eksklusif {{ $wedding->couple_name }}. Masukkan 4-digit PIN untuk melihat kenangan indah.">

    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
<body class="min-h-full {{ $theme['body_bg'] }} {{ $theme['body_text'] }} flex flex-col justify-between antialiased selection:bg-amber-400 selection:text-black relative overflow-x-hidden">

    <!-- Background Cover Art with Atmospheric Blur -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute inset-0 bg-cover bg-center opacity-20 filter blur-xl scale-110"
             style="background-image: url('{{ $wedding->cover_photo_url }}');"></div>
        <div class="absolute inset-0 {{ $theme['hero_overlay'] }}"></div>
    </div>

    <!-- Header (Minimalist Guest Branding, No Admin Tools) -->
    <header class="relative z-10 w-full py-6 px-4 sm:px-8 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full {{ $theme['accent_text'] }} bg-current animate-pulse"></span>
            <span class="text-xs uppercase tracking-widest font-bold opacity-80">Private Wedding Gallery</span>
        </div>
        <div class="text-xs opacity-60 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <span>PIN Protected</span>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="relative z-10 flex-1 flex flex-col items-center justify-center p-4 sm:p-6 my-auto">
        <div class="w-full max-w-md mx-auto space-y-6 text-center">

            <!-- Couple Monogram & Title -->
            <div class="space-y-3">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full {{ $theme['badge'] }} shadow-md mx-auto mb-1">
                    <span class="serif-font italic text-2xl font-bold">
                        {{ substr($wedding->couple_name, 0, 1) }}
                    </span>
                </div>

                <div class="text-[11px] uppercase tracking-[0.25em] font-semibold opacity-75">
                    The Wedding Celebration Of
                </div>

                <h1 class="serif-font text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight {{ $theme['heading_text'] }} leading-tight">
                    {{ $wedding->couple_name }}
                </h1>

                @if($wedding->wedding_date)
                    <p class="text-xs sm:text-sm opacity-70">
                        {{ \Carbon\Carbon::parse($wedding->wedding_date)->translatedFormat('l, d F Y') }}
                    </p>
                @endif
            </div>

            <!-- PIN Form Card -->
            <div class="{{ $theme['card_bg'] }} {{ $theme['card_border'] }} {{ $theme['card_shadow'] }} border rounded-3xl p-6 sm:p-8 backdrop-blur-xl relative">
                
                <div class="space-y-1 mb-6">
                    <h2 class="text-base font-bold {{ $theme['heading_text'] }}">Masukkan PIN Akses</h2>
                    <p class="text-xs {{ $theme['subtext'] }}">
                        Silakan masukkan 4 digit PIN yang telah dibagikan oleh pengantin untuk membuka galeri foto.
                    </p>
                </div>

                <!-- Error Alert -->
                <div id="pin-error-box" class="{{ $errors->has('pin') ? 'block' : 'hidden' }} mb-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-500 text-xs">
                    <span id="pin-error-text">{{ $errors->first('pin') ?? 'PIN yang Anda masukkan salah. Silakan coba lagi.' }}</span>
                </div>

                <!-- Form -->
                <form id="pin-form" action="{{ route('client.verify-pin', $wedding->slug) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- 4-Box PIN Display & Hidden Real Input -->
                    <div class="relative flex flex-col items-center">
                        <div class="flex items-center justify-center gap-3 sm:gap-4 my-2">
                            @for ($i = 0; $i < 4; $i++)
                                <div id="box-digit-{{ $i }}" 
                                     class="w-12 h-14 sm:w-14 sm:h-16 rounded-2xl border-2 {{ $theme['pin_box'] }} flex items-center justify-center text-xl sm:text-2xl font-mono font-bold transition-all duration-200 shadow-inner">
                                    <span class="digit-char text-current">&bull;</span>
                                </div>
                            @endfor
                        </div>

                        <!-- Real Input (Invisible / Overlayed) -->
                        <input type="password" 
                               id="pin-input" 
                               name="pin" 
                               maxlength="4" 
                               inputmode="numeric" 
                               pattern="\d{4}" 
                               autocomplete="off" 
                               autofocus 
                               required 
                               class="absolute inset-0 opacity-0 cursor-pointer w-full h-full text-center">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            id="btn-submit-pin"
                            class="w-full py-3.5 px-6 rounded-xl {{ $theme['primary_btn'] }} font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition-all duration-200">
                        <span id="btn-text">Buka Galeri Foto</span>
                        <svg id="btn-arrow" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

                <!-- Virtual Numeric Keypad for Mobile Comfort -->
                <div class="mt-6 pt-5 border-t {{ $theme['card_border'] }} grid grid-cols-3 gap-2 max-w-[260px] mx-auto sm:hidden">
                    @for ($n = 1; $n <= 9; $n++)
                        <button type="button" onclick="keypadPress('{{ $n }}')" class="py-2.5 rounded-xl {{ $theme['secondary_btn'] }} font-mono font-bold text-base transition active:scale-95">
                            {{ $n }}
                        </button>
                    @endfor
                    <button type="button" onclick="keypadClear()" class="py-2.5 rounded-xl {{ $theme['secondary_btn'] }} text-xs font-semibold transition active:scale-95">
                        Hapus
                    </button>
                    <button type="button" onclick="keypadPress('0')" class="py-2.5 rounded-xl {{ $theme['secondary_btn'] }} font-mono font-bold text-base transition active:scale-95">
                        0
                    </button>
                    <button type="button" onclick="keypadBackspace()" class="py-2.5 rounded-xl {{ $theme['secondary_btn'] }} text-sm transition active:scale-95">
                        &larr;
                    </button>
                </div>
            </div>

            <!-- Quote Preview at bottom of card -->
            @if(!empty($wedding->quote))
                <div class="max-w-sm mx-auto px-4">
                    <p class="serif-font italic text-xs sm:text-sm {{ $theme['subtext'] }} leading-relaxed opacity-85">
                        &ldquo;{{ $wedding->quote }}&rdquo;
                    </p>
                </div>
            @endif

        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 py-6 text-center text-xs opacity-60">
        &copy; {{ date('Y') }} {{ $wedding->couple_name }}. All photos powered by WedGallery.
    </footer>

    <!-- Interactive PIN Handler Script -->
    <script>
        const pinInput = document.getElementById('pin-input');
        const form = document.getElementById('pin-form');
        const errorBox = document.getElementById('pin-error-box');
        const errorText = document.getElementById('pin-error-text');
        const btnSubmit = document.getElementById('btn-submit-pin');
        const btnText = document.getElementById('btn-text');

        function updateBoxes() {
            const val = pinInput.value.replace(/\D/g, '').slice(0, 4);
            pinInput.value = val;

            for (let i = 0; i < 4; i++) {
                const box = document.getElementById('box-digit-' + i);
                const charSpan = box.querySelector('.digit-char');
                if (i < val.length) {
                    charSpan.textContent = val[i];
                    box.classList.add('ring-2', 'ring-current');
                } else {
                    charSpan.textContent = '•';
                    box.classList.remove('ring-2', 'ring-current');
                }
            }

            // Auto submit when 4 digits are completed
            if (val.length === 4) {
                submitPinAjax();
            }
        }

        pinInput.addEventListener('input', updateBoxes);

        function keypadPress(digit) {
            if (pinInput.value.length < 4) {
                pinInput.value += digit;
                updateBoxes();
            }
        }

        function keypadBackspace() {
            pinInput.value = pinInput.value.slice(0, -1);
            updateBoxes();
        }

        function keypadClear() {
            pinInput.value = '';
            updateBoxes();
        }

        function submitPinAjax() {
            const pinVal = pinInput.value;
            if (pinVal.length !== 4) return;

            btnSubmit.disabled = true;
            btnText.textContent = 'Memverifikasi...';
            errorBox.classList.add('hidden');

            fetch("{{ route('client.verify-pin', $wedding->slug) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ pin: pinVal })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    btnText.textContent = 'PIN Benar! Membuka Galeri...';
                    window.location.href = data.redirect;
                } else {
                    showError(data.message || 'PIN yang Anda masukkan salah. Silakan coba lagi.');
                }
            })
            .catch(() => {
                // If anything fails with AJAX, submit natively
                form.submit();
            })
            .finally(() => {
                btnSubmit.disabled = false;
            });
        }

        function showError(msg) {
            errorText.textContent = msg;
            errorBox.classList.remove('hidden');
            pinInput.value = '';
            updateBoxes();
            btnText.textContent = 'Buka Galeri Foto';
            pinInput.focus();
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitPinAjax();
        });

        // Focus input on load
        window.addEventListener('DOMContentLoaded', () => {
            pinInput.focus();
        });
    </script>
</body>
</html>
