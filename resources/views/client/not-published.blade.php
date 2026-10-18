@php
    $theme = $wedding->theme_config;
@endphp
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Belum Dipublikasikan - {{ $wedding->couple_name }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Styles and Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            --font-serif: 'Playfair Display', ui-serif, Georgia, serif;
        }
        body { font-family: var(--font-sans); }
        .serif-font { font-family: var(--font-serif); }
    </style>
</head>
<body class="min-h-full {{ $theme['body_bg'] }} {{ $theme['body_text'] }} flex flex-col justify-center items-center p-6 text-center">
    <div class="max-w-md mx-auto space-y-6 {{ $theme['card_bg'] }} {{ $theme['card_border'] }} border rounded-3xl p-8 backdrop-blur-xl shadow-xl">
        <div class="w-16 h-16 rounded-full {{ $theme['badge'] }} flex items-center justify-center mx-auto text-2xl">
            🔒
        </div>
        <div class="space-y-2">
            <h1 class="serif-font text-2xl sm:text-3xl font-bold {{ $theme['heading_text'] }}">
                {{ $wedding->couple_name }}
            </h1>
            <p class="text-xs uppercase tracking-widest {{ $theme['subtext'] }}">Galeri Belum Aktif</p>
        </div>
        <p class="text-xs {{ $theme['subtext'] }} leading-relaxed">
            Galeri pernikahan ini sedang dalam tahap kurasi dan persiapan oleh pihak studio. Silakan hubungi pengantin atau administrator untuk informasi lebih lanjut.
        </p>
        <div class="pt-4 border-t {{ $theme['card_border'] }}">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl {{ $theme['primary_btn'] }} text-xs font-bold uppercase tracking-wider">
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>
</body>
</html>
