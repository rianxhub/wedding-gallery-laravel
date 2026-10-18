<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="WedGallery - Cloud Photo Albums & Guest Download Portal for Wedding Studios">
    <title>@yield('title', 'Client Weddings') - WedGallery</title>
    
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
            --studio-navy: #0B1727;
            --studio-navy-light: #112338;
            --studio-navy-dark: #070F1E;
            --emerald-deep: #1B4D3E;
            --emerald-hover: #153C30;
            --emerald-light: #EBF8F2;
        }

        body {
            font-family: var(--font-sans);
            background-color: #F8FAFC;
            color: #0F172A;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .serif-font {
            font-family: var(--font-serif);
        }

        .btn-emerald {
            background-color: var(--emerald-deep);
            color: #FFFFFF;
            transition: all 0.2s ease;
        }
        .btn-emerald:hover {
            background-color: var(--emerald-hover);
            color: #FFFFFF;
        }

        .sidebar-link {
            transition: all 0.15s ease-in-out;
        }
        .sidebar-link.active {
            background-color: #132B3E;
            color: #FFFFFF;
            font-weight: 600;
        }
        .sidebar-link.active .sidebar-icon {
            color: #34D399;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full flex flex-col antialiased text-slate-800 bg-slate-50 selection:bg-emerald-100 selection:text-emerald-900">

    <div class="flex h-screen overflow-hidden bg-slate-50">
        <!-- Sidebar Navigation (Desktop) -->
        <aside id="admin-sidebar" class="w-64 bg-[#0B1727] text-slate-300 flex flex-col flex-shrink-0 z-40 transition-transform duration-200 ease-in-out md:translate-x-0 -translate-x-full fixed md:static inset-y-0 left-0 border-r border-slate-800/60 shadow-xl md:shadow-none">
            <!-- Brand Logo Header -->
            <div class="p-5 border-b border-slate-800/80 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3 text-white no-underline">
                    <div class="w-9 h-9 rounded-xl bg-emerald-950/80 border border-emerald-700/50 flex items-center justify-center text-emerald-400 shadow-inner">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold text-sm tracking-wider text-white">WEDGALLERY</div>
                        <div class="text-[11px] text-slate-400 font-medium">Studio Platform</div>
                    </div>
                </a>

                <!-- Close Sidebar on Mobile -->
                <button type="button" onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <div class="px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                    Galeri &amp; Pengantin
                </div>

                <a href="{{ route('weddings.index') }}" 
                   class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-[#112338] hover:text-white {{ request()->routeIs('weddings.*') && !request()->routeIs('weddings.trash') ? 'active' : '' }}">
                    <svg class="w-4 h-4 sidebar-icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>Client Weddings</span>
                </a>

                <a href="{{ route('albums.index') }}" 
                   class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-[#112338] hover:text-white {{ request()->routeIs('albums.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 sidebar-icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z"/>
                    </svg>
                    <span>Storage &amp; Album</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-400 uppercase">
                    Manajemen Sistem
                </div>

                <a href="{{ route('users.index') }}" 
                   class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-[#112338] hover:text-white {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 sidebar-icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Pengguna (Users)</span>
                </a>

                <a href="{{ route('logs.index') }}" 
                   class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-[#112338] hover:text-white {{ request()->routeIs('logs.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 sidebar-icon text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Log Unduhan</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm text-rose-300 hover:bg-rose-950/40 hover:text-rose-200 transition">
                        <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar (Logout)</span>
                    </button>
                </form>
            </nav>

            <!-- Bottom Team Credits & System Status (Exact Figma Spec) -->
            <div class="p-4 border-t border-slate-800/80 bg-[#070F1E]/80 text-xs text-slate-400">
                <div class="mb-3 space-y-0.5 text-[11px] font-medium leading-relaxed">
                    <div class="text-slate-300 font-semibold mb-1">Studio Team:</div>
                    <div><span class="text-slate-400">Satria:</span> PM</div>
                    <div><span class="text-slate-400">Rian &amp; Robi Ajis:</span> Backend</div>
                    <div><span class="text-slate-400">Zahran:</span> UI/UX</div>
                    <div><span class="text-slate-400">Damar:</span> Frontend</div>
                    <div><span class="text-slate-400">Adi:</span> QA Support</div>
                </div>

                <div class="pt-2 border-t border-slate-800/60 flex items-center justify-between text-[11px] font-medium text-slate-300">
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                        </svg>
                        <span>System Version 2.4</span>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" title="MySQL Live Connected"></span>
                </div>
            </div>
        </aside>

        <!-- Main Column Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Header Bar (Desktop & Mobile) -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 flex items-center justify-between flex-shrink-0 z-30">
                <!-- Left: Hamburger & Breadcrumbs -->
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleSidebar()" class="md:hidden p-2 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="flex items-center gap-2 text-sm text-slate-500 font-medium">
                        <span class="font-bold text-slate-900">WedGallery</span>
                        <span>/</span>
                        <span class="text-slate-600">@yield('breadcrumb', 'Client Galleries')</span>
                    </div>
                </div>

                <!-- Center & Right: Search, Notifications & Profile -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Search Input -->
                    <div class="relative hidden sm:block w-64 md:w-80">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text" 
                               id="global-studio-search" 
                               placeholder="Search couples, dates, slugs..." 
                               class="w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-emerald-700 focus:border-emerald-700 transition">
                    </div>

                    <!-- Notification Bell -->
                    <div class="relative" id="notif-wrapper">
                        <button type="button"
                                id="notif-btn"
                                onclick="toggleNotifPanel()"
                                class="relative p-2 text-slate-500 hover:text-slate-700 rounded-full hover:bg-slate-100 transition"
                                title="Riwayat Aktivitas">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span id="notif-badge" class="absolute top-1 right-1 w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-white hidden"></span>
                        </button>

                        <!-- Notification Dropdown Panel -->
                        <div id="notif-panel"
                             class="hidden absolute right-0 top-full mt-2 w-80 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 overflow-hidden"
                             style="min-width:320px;">
                            <!-- Panel Header -->
                            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <span class="text-xs font-bold text-slate-900">Riwayat Aktivitas</span>
                                    <span id="notif-unread-count" class="px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold hidden">0</span>
                                </div>
                                <button type="button" onclick="markAllRead()" class="text-[10px] font-semibold text-emerald-700 hover:text-emerald-900 transition">
                                    Tandai semua dibaca
                                </button>
                            </div>

                            <!-- Tab Bar -->
                            <div class="flex border-b border-slate-100">
                                <button type="button" onclick="switchNotifTab('upload')"
                                        id="ntab-upload"
                                        class="notif-tab flex-1 flex items-center justify-center gap-1.5 py-2 text-[11px] font-semibold border-b-2 border-emerald-600 text-emerald-700 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Upload
                                    <span id="nbadge-upload" class="px-1 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[9px] font-bold">0</span>
                                </button>
                                <button type="button" onclick="switchNotifTab('edit')"
                                        id="ntab-edit"
                                        class="notif-tab flex-1 flex items-center justify-center gap-1.5 py-2 text-[11px] font-semibold border-b-2 border-transparent text-slate-500 hover:text-blue-700 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Edit
                                    <span id="nbadge-edit" class="px-1 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[9px] font-bold">0</span>
                                </button>
                                <button type="button" onclick="switchNotifTab('delete')"
                                        id="ntab-delete"
                                        class="notif-tab flex-1 flex items-center justify-center gap-1.5 py-2 text-[11px] font-semibold border-b-2 border-transparent text-slate-500 hover:text-rose-700 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                    <span id="nbadge-delete" class="px-1 py-0.5 rounded-full bg-rose-100 text-rose-700 text-[9px] font-bold">0</span>
                                </button>
                            </div>

                            <!-- Tab Content -->
                            <div class="overflow-y-auto" style="max-height: 320px;">
                                <div id="nlist-upload" class="notif-list divide-y divide-slate-50"></div>
                                <div id="nlist-edit"   class="notif-list divide-y divide-slate-50 hidden"></div>
                                <div id="nlist-delete" class="notif-list divide-y divide-slate-50 hidden"></div>
                            </div>

                            <!-- Panel Footer -->
                            <div class="px-4 py-2.5 border-t border-slate-100 bg-slate-50 text-center">
                                <span class="text-[10px] text-slate-400">Data diperbarui setiap kali panel dibuka</span>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile & Logout -->
                    <div class="flex items-center gap-3 pl-2 sm:pl-3 border-l border-slate-200">
                        <div class="w-8 h-8 rounded-full bg-emerald-900 text-emerald-100 font-bold text-xs flex items-center justify-center ring-1 ring-emerald-700/50 shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden lg:block text-left">
                            <div class="text-xs font-bold text-slate-900 leading-tight">{{ Auth::user()->name ?? 'Administrator' }}</div>
                            <div class="text-[10px] text-emerald-700 font-semibold uppercase tracking-wider">{{ Auth::user()->role ?? 'Admin' }}</div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" 
                                    title="Keluar (Logout)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content Canvas -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-30 hidden md:hidden"></div>

    <script>
        /* ──────────────────────────────────────────────
           Sidebar Toggle
        ────────────────────────────────────────────── */
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        /* ──────────────────────────────────────────────
           Notification Panel
        ────────────────────────────────────────────── */
        let notifOpen = false;
        let activeTab = 'upload';

        function toggleNotifPanel() {
            const panel = document.getElementById('notif-panel');
            notifOpen = !notifOpen;
            panel.classList.toggle('hidden', !notifOpen);
            if (notifOpen) loadNotifications();
        }

        function switchNotifTab(tab) {
            activeTab = tab;
            // Show/hide lists
            ['upload', 'edit', 'delete'].forEach(t => {
                document.getElementById('nlist-' + t).classList.toggle('hidden', t !== tab);
            });
            // Style tab buttons
            const colors = { upload: 'emerald', edit: 'blue', delete: 'rose' };
            ['upload', 'edit', 'delete'].forEach(t => {
                const btn = document.getElementById('ntab-' + t);
                if (t === tab) {
                    btn.classList.remove('border-transparent', 'text-slate-500');
                    btn.classList.add('border-' + colors[t] + '-600', 'text-' + colors[t] + '-700');
                } else {
                    btn.classList.remove('border-' + colors[t] + '-600', 'text-' + colors[t] + '-700');
                    btn.classList.add('border-transparent', 'text-slate-500');
                }
            });
        }

        function timeAgo(isoStr) {
            const diff = Math.floor((Date.now() - new Date(isoStr)) / 1000);
            if (diff < 60)   return diff + 'd lalu';
            if (diff < 3600) return Math.floor(diff/60) + 'm lalu';
            if (diff < 86400) return Math.floor(diff/3600) + 'j lalu';
            return Math.floor(diff/86400) + 'hr lalu';
        }

        function renderItems(type, items) {
            const el = document.getElementById('nlist-' + type);
            const icons = { upload: '⬆️', edit: '✏️', delete: '🗑️' };
            const colors = {
                upload: 'bg-emerald-50 text-emerald-700',
                edit:   'bg-blue-50 text-blue-700',
                delete: 'bg-rose-50 text-rose-700',
            };
            if (!items || items.length === 0) {
                el.innerHTML = `<div class="px-4 py-8 text-center text-xs text-slate-400">Belum ada riwayat ${type}</div>`;
                return;
            }
            el.innerHTML = items.map(item => `
                <div class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition ${!item.is_read ? 'bg-slate-50/80' : ''}">
                    <span class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-sm ${colors[type]}">${icons[type]}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-800 font-medium leading-snug">${item.description}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-[10px] text-slate-400">${timeAgo(item.created_at)}</span>
                            ${item.user ? `<span class="text-[10px] text-slate-400">· ${item.user.name}</span>` : ''}
                        </div>
                    </div>
                    ${!item.is_read ? '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0 mt-1.5"></span>' : ''}
                </div>
            `).join('');
        }

        function loadNotifications() {
            fetch('{{ route("notifications.data") }}')
                .then(r => r.json())
                .then(data => {
                    // Badge
                    const badge = document.getElementById('notif-badge');
                    const unreadEl = document.getElementById('notif-unread-count');
                    if (data.unread > 0) {
                        badge.classList.remove('hidden');
                        unreadEl.textContent = data.unread;
                        unreadEl.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                        unreadEl.classList.add('hidden');
                    }
                    // Tab count badges
                    document.getElementById('nbadge-upload').textContent = data.upload.length;
                    document.getElementById('nbadge-edit').textContent   = data.edit.length;
                    document.getElementById('nbadge-delete').textContent  = data.delete.length;
                    // Render lists
                    renderItems('upload', data.upload);
                    renderItems('edit',   data.edit);
                    renderItems('delete', data.delete);
                })
                .catch(() => {});
        }

        function markAllRead() {
            fetch('{{ route("notifications.mark-read") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            }).then(() => loadNotifications());
        }

        // Close panel on outside click
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notif-wrapper');
            if (wrapper && !wrapper.contains(e.target) && notifOpen) {
                document.getElementById('notif-panel').classList.add('hidden');
                notifOpen = false;
            }
        });

        // Load initial badge count on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("notifications.data") }}')
                .then(r => r.json())
                .then(data => {
                    if (data.unread > 0) {
                        document.getElementById('notif-badge').classList.remove('hidden');
                    }
                }).catch(() => {});
        });
    </script>

@stack('scripts')
</body>
</html>
