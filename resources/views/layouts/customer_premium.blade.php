<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') - Arya Duta Tangerang</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">

    <!-- Tailwind -->
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
                        },
                        accent: {
                            gold: '#D4AF37',
                            amber: '#fbbf24',
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
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
            background: #ffffff;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #052e16 0%, #064e3b 100%);
        }

        .glass {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .glass-dark {
            background: rgba(5, 46, 22, 0.98);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(16, 185, 129, 0.3);
        }

        .card-shadow {
            box-shadow: 0 20px 50px rgba(5, 46, 22, 0.08);
        }

        .floating {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .page-bg::before {
            content: '';
            position: fixed;
            top: -200px;
            right: -200px;
            width: 500px;
            height: 500px;
            background: rgba(16, 185, 129, 0.15);
            filter: blur(120px);
            border-radius: 999px;
            z-index: -1;
        }

        .page-bg::after {
            content: '';
            position: fixed;
            bottom: -200px;
            left: -200px;
            width: 500px;
            height: 500px;
            background: rgba(110, 231, 183, 0.18);
            filter: blur(120px);
            border-radius: 999px;
            z-index: -1;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #ecfdf5;
        }

        ::-webkit-scrollbar-thumb {
            background: #16a34a;
            border-radius: 999px;
        }
    </style>
</head>

<body class="page-bg bg-white font-sans text-emerald-950 overflow-x-hidden">

    <!-- Navbar -->
    <nav class="fixed w-full z-[100] transition-all duration-500 px-3 md:px-6 py-3 md:py-4" id="main-nav">
        <div class="max-w-7xl mx-auto flex items-center justify-between glass-dark rounded-[24px] md:rounded-[32px] px-4 md:px-8 py-3 md:py-4 shadow-2xl">

            <!-- Logo -->
            <div class="flex items-center gap-4 group">
                <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-4">
                    <div class="bg-white p-2 rounded-xl shadow-lg transform group-hover:rotate-6 transition-transform duration-500">
                        <img src="{{ asset('logo.jpg') }}" alt="Logo AD" class="h-6 sm:h-8 w-auto">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-white font-black text-lg sm:text-xl tracking-tighter leading-none">Arya Duta Tangerang</span>
                    </div>
                </a>
            </div>

            <!-- Navigation -->
            <div class="hidden lg:flex items-center gap-3">

                @php
                    $customerNav = [
                        ['url' => '/pelanggan/beranda', 'label' => 'Beranda'],
                        ['url' => '/pelanggan/dashboard', 'label' => 'Katalog'],
                        ['url' => '/pelanggan/riwayat', 'label' => 'Riwayat Transaksi'],
                    ];
                @endphp

                @foreach($customerNav as $item)

                    @php
                        $isActive = request()->is(ltrim($item['url'], '/'));
                    @endphp

                    <a href="{{ url($item['url']) }}"
                       class="px-5 py-3 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all duration-300
                       {{ $isActive
                           ? 'bg-white text-emerald-950 shadow-xl'
                           : 'text-white/70 hover:text-white hover:bg-white/10'
                       }}">
                        {{ $item['label'] }}
                    </a>

                @endforeach
            </div>

            <!-- Right -->
            <div class="flex items-center gap-2 md:gap-4">

                <!-- Notifications -->
                @php
                    $unreadNotifications = \App\Models\Notification::where('user_id', Auth::id())
                        ->where('role', 'pelanggan')
                        ->where('is_read', false)
                        ->count();
                    $recentNotifications = \App\Models\Notification::where('user_id', Auth::id())
                        ->where('role', 'pelanggan')
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                <div class="relative group/notif">
                    <button class="relative w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-white/10 border border-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        @if($unreadNotifications > 0)
                            <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-[9px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-lg animate-bounce">
                                {{ $unreadNotifications }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div class="absolute right-[-130px] sm:right-0 top-full mt-4 w-[85vw] sm:w-[400px] bg-white rounded-[32px] md:rounded-[40px] shadow-2xl border border-emerald-100 overflow-hidden opacity-0 invisible group-hover/notif:opacity-100 group-hover/notif:visible transition-all duration-300 z-[100] translate-y-4 group-hover/notif:translate-y-0">
                        <div class="p-6 bg-emerald-950 text-white flex items-center justify-between">
                            <h4 class="font-black text-[14px] uppercase tracking-widest">Notifikasi</h4>
                            @if($unreadNotifications > 0)
                                <a href="{{ url('/pelanggan/notifications/read-all') }}" class="text-[10px] font-black text-emerald-400 hover:text-white uppercase tracking-widest">Baca Semua</a>
                            @endif
                        </div>
                        <div class="max-h-[350px] overflow-y-auto">
                            @forelse($recentNotifications as $notif)
                                <a href="{{ $notif->link ?? '#' }}" class="block p-5 md:p-8 border-b border-emerald-50 hover:bg-emerald-50 transition-colors {{ !$notif->is_read ? 'bg-emerald-50/50' : '' }}">
                                    <div class="flex gap-4">
                                        <div class="w-2 h-2 rounded-full mt-2 shrink-0 {{ $notif->type == 'success' ? 'bg-emerald-500' : ($notif->type == 'warning' ? 'bg-amber-500' : 'bg-blue-500') }}"></div>
                                        <div>
                                            <p class="font-black text-emerald-950 text-[13px] leading-tight mb-1">{{ $notif->title }}</p>
                                            <p class="text-[12px] text-emerald-700 leading-normal">{{ $notif->message }}</p>
                                            <p class="text-[10px] font-bold text-emerald-300 uppercase tracking-widest mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-12 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-12 h-12 text-emerald-100 mx-auto mb-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                                    <p class="text-emerald-900 font-bold text-[13px]">Belum ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Cart -->
                <a href="{{ url('/pelanggan/keranjang') }}"
                   class="relative z-10 w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl bg-white/10 border border-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                    </svg>

                    @if(count(session('cart', [])) > 0)
                        <span class="absolute -top-1 -right-1 bg-emerald-500 text-white text-[9px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-emerald-950 shadow-lg">
                            {{ count(session('cart', [])) }}
                        </span>
                    @endif
                </a>

                <!-- User -->
                <a href="{{ url('/pelanggan/profil') }}" class="relative z-10 hidden sm:flex items-center gap-4 glass px-4 py-2 rounded-2xl hover:bg-white/10 transition-all group">
                    <div class="text-right">
                        <p class="text-[13px] font-black text-white leading-none tracking-tight">
                            {{ Auth::user()->name ?? session('username') ?? 'Customer' }}
                        </p>
                        <p class="text-[9px] font-black text-emerald-300 uppercase tracking-[0.2em] mt-1">
                            {{ ucfirst(Auth::user()->role ?? 'Pelanggan') }}
                        </p>
                    </div>

                    <div class="w-10 h-10 bg-white text-emerald-950 rounded-xl flex items-center justify-center font-black shadow-lg uppercase overflow-hidden">
                        @if(Auth::user() && Auth::user()->profile_photo)
                            <img src="{{ Auth::user()->profile_photo }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(Auth::user()->name ?? session('username') ?? 'C', 0, 1) }}
                        @endif
                    </div>
                </a>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="group/logout shrink-0">
                    @csrf
                    <button type="submit" class="flex items-center gap-0 hover:gap-3 px-3 py-3 md:px-6 bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500 hover:text-white rounded-xl md:rounded-2xl transition-all duration-500 ease-out shadow-lg hover:shadow-rose-500/40 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]"></div>
                        
                        <span class="max-w-0 overflow-hidden whitespace-nowrap text-[11px] font-black uppercase tracking-widest transition-all duration-500 group-hover:max-w-[80px]">
                            Keluar
                        </span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 flex-shrink-0 transform group-hover:translate-x-1 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12"/>
                        </svg>
                    </button>
                </form>

                <style>
                    @keyframes shimmer {
                        100% { transform: translateX(100%); }
                    }
                </style>
            </div>
                <!-- Mobile Menu Toggle -->
                <button class="lg:hidden text-white p-2" id="mobile-menu-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu" class="hidden lg:hidden absolute top-full left-6 right-6 mt-4 glass-dark rounded-[32px] p-6 shadow-2xl animate-in slide-in-from-top duration-300 z-[110] border-2 border-emerald-500/30">
            <div class="flex flex-col gap-3">
                @foreach($customerNav as $item)
                    @php $isActive = request()->is(ltrim($item['url'], '/')); @endphp
                    <a href="{{ url($item['url']) }}" class="flex items-center justify-between p-5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all {{ $isActive ? 'bg-white text-emerald-950 shadow-xl' : 'text-white/70 hover:text-white bg-white/5' }}">
                        {{ $item['label'] }}
                        @if($isActive)
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                        @endif
                    </a>
                @endforeach
                
                <div class="h-px bg-white/10 my-2"></div>
                
                <a href="{{ url('/pelanggan/profil') }}" class="flex items-center gap-4 p-5 rounded-2xl bg-white/5 text-white border border-white/5 hover:border-white/20 transition-all">
                    <div class="w-10 h-10 bg-white text-emerald-950 rounded-xl flex items-center justify-center font-black shadow-lg">
                        {{ substr(Auth::user()->name ?? 'C', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[13px] font-black leading-none">{{ Auth::user()->name ?? 'Customer' }}</p>
                        <p class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.2em] mt-1.5">Akun Saya</p>
                    </div>
                </a>
            </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 md:px-6 pt-24 md:pt-40 pb-20 relative z-10">

        @if(session('success'))
            <div class="mb-10 bg-emerald-950 rounded-[32px] p-6 flex items-center gap-5 shadow-2xl border border-emerald-800/30">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-emerald-400 font-black mb-1">{{ session('title') ?? 'Login Berhasil' }}</p>
                    <p class="text-white font-bold text-base">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @yield('content')

    </main>

    @yield('scripts')

    <!-- Navbar Scroll & Mobile Toggle -->
    <script>
        window.onscroll = function () {
            const nav = document.getElementById('main-nav');
            if (window.pageYOffset > 50) {
                nav.classList.add('py-2');
            } else {
                nav.classList.remove('py-2');
            }
        };

        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-toggle');
        const menu = document.getElementById('mobile-menu');
        
        if(menuBtn && menu) {
            menuBtn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!menuBtn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        }
    </script>

</body>
</html>
