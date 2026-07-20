<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Arya Duta Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#052e16',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #ffffff;
            overflow-x: hidden;
        }

        .page-bg::before {
            content: '';
            position: fixed;
            top: -200px;
            right: -200px;
            width: 500px;
            height: 500px;
            background: rgba(16, 185, 129, 0.12);
            filter: blur(120px);
            border-radius: 999px;
            z-index: -1;
        }

        .page-bg::after {
            content: '';
            position: fixed;
            bottom: -200px;
            left: 200px;
            width: 500px;
            height: 500px;
            background: rgba(110, 231, 183, 0.15);
            filter: blur(120px);
            border-radius: 999px;
            z-index: -1;
        }

        .glass-sidebar { 
            background: #052e16; 
            backdrop-filter: blur(24px); 
            border-right: 1px solid rgba(16, 185, 129, 0.2); 
            box-shadow: 20px 0 50px rgba(5, 46, 22, 0.1);
        }

        .glass-nav { 
            background: #052e16; 
            backdrop-filter: blur(24px); 
            border-bottom: 1px solid rgba(16, 185, 129, 0.2); 
        }

        .nav-item-active {
            background: white;
            color: #052e16;
            box-shadow: 0 15px 30px -5px rgba(5, 46, 22, 0.2);
            transform: scale(1.02);
        }

        .stat-card { 
            border-radius: 32px;
            border: 1px solid rgba(16, 185, 129, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        .stat-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 30px 60px -15px rgba(5, 46, 22, 0.12); 
            border-color: rgba(16, 185, 129, 0.3);
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #16a34a; border-radius: 12px; }

        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
    </style>
</head>
<body class="page-bg bg-white font-sans text-emerald-950 overflow-x-hidden">

    <!-- Sidebar -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 w-[340px] glass-sidebar flex flex-col z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-500 ease-in-out">
        <!-- Logo -->
        <div class="h-32 flex flex-col items-center px-8 relative">
            <div class="flex items-center gap-4 px-6 py-4 rounded-[28px] border border-white/10 w-full hover:border-white/20 transition-all duration-500 group cursor-pointer bg-white/5 backdrop-blur-md">
                <div class="flex items-center justify-center bg-white p-2 rounded-xl shadow-xl transform group-hover:rotate-6 transition-transform duration-500">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo AD" class="h-8 w-auto">
                </div>
                <div class="flex flex-col min-w-0">
                    <h1 class="font-black text-lg text-white leading-tight tracking-tighter">CIVAD</h1>
                    <span class="text-white/60 font-medium text-[10px] tracking-normal mt-0.5 truncate">CV. Arya Duta cabang Tangerang</span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-6 py-6 space-y-2 overflow-y-auto custom-scrollbar">
            @php
                $navItems = [
                    ['url' => '/admin/dashboard', 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25V15.75Z" />'],
                    ['url' => '/admin/manajemen-buku', 'label' => 'Manajemen Buku', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />'],
                    ['url' => '/admin/manajemen-user', 'label' => 'Data Pelanggan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />'],
                    ['url' => '/admin/manajemen-pesanan', 'label' => 'Manajemen Pesanan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />'],
                    ['url' => '/admin/manajemen-pengembalian', 'label' => 'Manajemen Pengembalian', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />'],
                    ['url' => '/admin/manajemen-ulasan', 'label' => 'Manajemen Ulasan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c-.195-.39-.6-.628-1.036-.628s-.841.238-1.036.628L6.877 7.575l-4.475.65c-.43.063-.787.353-.923.76-.137.408-.035.856.262 1.162l3.238 3.155-.765 4.457c-.073.428.1.86.46 1.11.36.25.845.267 1.22.046l4.004-2.105 4.004 2.105c.376.221.86.204 1.22-.046.36-.25.533-.682.46-1.11l-.765-4.457 3.238-3.155c.297-.306.399-.754.262-1.162-.136-.407-.493-.697-.923-.76l-4.475-.65-2.032-4.076Z" />'],
                    ['url' => '/admin/laporan-penjualan', 'label' => 'Laporan Penjualan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0V3.75m0 12.75V21m7.5-17.25V21m-7.5-12.75h7.5m-7.5 3h7.5" />'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $isActive = request()->is(ltrim($item['url'], '/')); @endphp
                <a href="{{ url($item['url']) }}" class="flex items-center gap-4 px-6 py-4 rounded-[24px] text-[12px] font-black uppercase tracking-[0.15em] transition-all duration-500 group {{ $isActive ? 'nav-item-active text-emerald-950' : 'text-white hover:text-white hover:bg-white/10' }}">
                    <div class="flex items-center justify-center w-6 h-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-full h-full transition-all duration-500 group-hover:scale-125 {{ $isActive ? 'text-emerald-950' : 'text-white group-hover:text-white' }}">
                            {!! $item['icon'] !!}
                        </svg>
                    </div>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- Logout -->
        <div class="p-8 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500 hover:text-white rounded-[20px] font-black text-[12px] uppercase tracking-widest transition-all duration-500 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main id="main-content" class="ml-0 lg:ml-[340px] min-h-screen relative z-10 transition-all duration-500 ease-in-out">
        <!-- Topbar -->
        <header class="h-20 lg:h-28 glass-nav sticky top-0 z-40 flex items-center justify-between px-3 lg:px-12 shadow-2xl">
            <div class="flex items-center gap-1.5 md:gap-4 min-w-0">
                <button id="sidebar-toggle" class="text-white p-1 md:p-2 hover:bg-white/10 rounded-xl transition-all shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 md:w-7 md:h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="animate-in fade-in slide-in-from-left-4 duration-700 min-w-0">
                    @yield('header')
                </div>
            </div>
            
            <div class="flex items-center gap-2 md:gap-8 shrink-0">
                <!-- Admin Notifications -->
                @php
                    $unreadAdminNotifs = \App\Models\Notification::where('role', 'admin')
                        ->where('is_read', false)
                        ->count();
                    $recentAdminNotifs = \App\Models\Notification::where('role', 'admin')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                <div class="relative group/admin-notif">
                    <button class="relative w-10 h-10 md:w-14 md:h-14 rounded-xl md:rounded-2xl bg-white/5 border border-white/10 text-white flex items-center justify-center hover:bg-white/10 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 md:w-6 md:h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        @if($unreadAdminNotifs > 0)
                            <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-[8px] md:text-[10px] font-black w-4 h-4 md:w-6 md:h-6 rounded-full flex items-center justify-center border-2 md:border-4 border-white shadow-lg animate-bounce">
                                {{ $unreadAdminNotifs }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div class="absolute right-[-130px] sm:right-0 top-full mt-4 w-[85vw] sm:w-[400px] bg-white rounded-[32px] md:rounded-[40px] shadow-2xl border border-emerald-100 overflow-hidden opacity-0 invisible group-hover/admin-notif:opacity-100 group-hover/admin-notif:visible transition-all duration-300 z-[100] translate-y-4 group-hover/admin-notif:translate-y-0">
                        <div class="p-6 md:p-8 bg-emerald-950 text-white flex items-center justify-between">
                            <div>
                                <h4 class="font-black text-[16px] tracking-tight">Pusat Informasi</h4>
                                <p class="text-[10px] font-bold text-white uppercase tracking-widest mt-1">Aktivitas Sistem Terbaru</p>
                            </div>
                            @if($unreadAdminNotifs > 0)
                                <a href="{{ url('/admin/notifications/read-all') }}" class="text-[10px] font-black bg-white/10 px-3 py-1.5 rounded-lg hover:bg-white/20 transition-all uppercase tracking-widest">Baca Semua</a>
                            @endif
                        </div>
                        <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                            @forelse($recentAdminNotifs as $notif)
                                <a href="{{ $notif->link ?? '#' }}" class="block p-5 md:p-8 border-b border-emerald-50 hover:bg-emerald-50 transition-colors {{ !$notif->is_read ? 'bg-emerald-50/50' : '' }}">
                                    <div class="flex gap-5">
                                        <div class="w-3 h-3 rounded-full mt-2 shrink-0 {{ $notif->type == 'success' ? 'bg-emerald-500' : ($notif->type == 'warning' ? 'bg-amber-500' : 'bg-blue-500') }} shadow-lg shadow-current/20"></div>
                                        <div>
                                            <p class="font-black text-emerald-950 text-[15px] leading-tight mb-2">{{ $notif->title }}</p>
                                            <p class="text-[13px] text-emerald-800 leading-relaxed font-medium">{{ $notif->message }}</p>
                                            <div class="flex items-center gap-2 mt-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 text-emerald-950"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                                <p class="text-[10px] font-black text-emerald-950 uppercase tracking-widest">{{ $notif->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-16 text-center">
                                    <div class="w-20 h-20 bg-emerald-50 rounded-[32px] flex items-center justify-center mx-auto mb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 text-emerald-100"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                                    </div>
                                    <p class="text-emerald-950 font-black text-[15px]">Hening di Sini...</p>
                                    <p class="text-emerald-600 font-bold text-[12px] mt-1">Belum ada aktivitas baru.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @php
                    $adminUser = null;
                    if (session()->has('admin_id')) {
                        $adminUser = \App\Models\Admin::find(session('admin_id'));
                    } else {
                        $adminUser = \App\Models\Admin::where('name', session('username'))->first();
                    }
                @endphp
                <a href="{{ url('/admin/profil') }}" class="flex items-center gap-2 md:gap-5 bg-white/5 px-1.5 md:px-6 py-1.5 md:py-3 rounded-xl md:rounded-[28px] border border-white/10 group hover:border-white/20 transition-all hover:bg-white/10 shrink-0">
                    <div class="text-right hidden sm:block">
                        <p class="text-[12px] lg:text-[14px] font-black text-white leading-none tracking-tight">{{ session('username') ?? 'Admin' }}</p>
                        <p class="text-[9px] lg:text-[10px] font-black text-white uppercase tracking-[0.2em] mt-1.5">Admin</p>
                    </div>
                    <div class="w-10 h-10 lg:w-14 lg:h-14 bg-white text-emerald-950 rounded-xl lg:rounded-2xl flex items-center justify-center font-black shadow-xl border border-white uppercase text-[16px] lg:text-[20px] transform group-hover:scale-105 transition-transform duration-500 overflow-hidden">
                        @if($adminUser && $adminUser->profile_photo)
                            <img src="{{ $adminUser->profile_photo }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(session('username') ?? 'A', 0, 1) }}
                        @endif
                    </div>
                </a>
            </div>
            </div>
        </header>

        <!-- Body -->
        <div class="p-6 lg:p-12">
            @hasSection('topbar_actions')
            <div class="mb-10 flex justify-end">
                @yield('topbar_actions')
            </div>
            @endif
            @if(session('success'))
            <div class="mb-10 p-6 bg-emerald-950 rounded-[32px] flex items-center gap-6 shadow-2xl border border-emerald-800/30 animate-in fade-in zoom-in-95 duration-500">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-white font-black mb-1">System Notification</p>
                    <p class="text-white font-bold text-lg leading-tight">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-10 p-6 bg-rose-950 rounded-[32px] flex items-center gap-6 shadow-2xl border border-rose-800/30 animate-in fade-in zoom-in-95 duration-500">
                <div class="w-14 h-14 rounded-2xl bg-rose-500 text-white flex items-center justify-center shadow-lg shadow-rose-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-white font-black mb-1">Error Notification</p>
                    <p class="text-white font-bold text-lg leading-tight">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-10 p-6 bg-rose-950 rounded-[32px] shadow-2xl border border-rose-800/30 animate-in fade-in zoom-in-95 duration-500">
                <div class="flex items-center gap-6 mb-4">
                    <div class="w-14 h-14 rounded-2xl bg-rose-500 text-white flex items-center justify-center shadow-lg shadow-rose-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.3em] text-white font-black mb-1">Validation Errors</p>
                        <p class="text-white font-bold text-lg leading-tight">Ada kesalahan pada data yang Anda masukkan:</p>
                    </div>
                </div>
                <ul class="list-disc list-inside text-rose-200 font-medium text-[14px] space-y-1 ml-20">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="animate-in fade-in slide-in-from-bottom-4 duration-700">
                @yield('content')
            </div>
        </div>
    </main>

    @yield('modals')
    @yield('scripts')

    <script>
        const sidebar = document.getElementById('admin-sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const mainContent = document.getElementById('main-content');
        
        if(sidebarToggle && sidebar && mainContent) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                if (window.innerWidth >= 1024) {
                    sidebar.classList.toggle('lg:translate-x-0');
                    sidebar.classList.toggle('lg:-translate-x-full');
                    mainContent.classList.toggle('lg:ml-[340px]');
                    mainContent.classList.toggle('lg:ml-0');
                } else {
                    sidebar.classList.toggle('-translate-x-full');
                    sidebar.classList.toggle('translate-x-0');
                }
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth < 1024) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.add('-translate-x-full');
                        sidebar.classList.remove('translate-x-0');
                    }
                }
            });
        }
    </script>
</body>
</html>
