<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Arya Duta - Solusi Literasi Pendidikan Indonesia</title>
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
        html { scroll-behavior: smooth; }
        .hero-gradient {
            background: linear-gradient(135deg, #052e16 0%, #064e3b 100%);
        }
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-dark {
            background: rgba(5, 46, 22, 0.98);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(16, 185, 129, 0.3);
        }
        .floating { animation: floating 6s ease-in-out infinite; }
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .swiper-pagination-bullet { background: #16a34a !important; }
        .card-shadow { box-shadow: 0 20px 50px rgba(5, 46, 22, 0.05); }
    </style>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body class="bg-white font-sans text-emerald-950 overflow-x-hidden">
    
    <!-- Top Navigation -->
    <nav class="fixed w-full z-[100] transition-all duration-500 px-6 py-4" id="main-nav">
        <div class="max-w-7xl mx-auto flex items-center justify-between glass-dark rounded-[32px] px-8 py-4 shadow-2xl">
            <div class="flex items-center gap-4 group cursor-pointer">
                <div class="bg-white p-2 rounded-xl shadow-lg transform group-hover:rotate-6 transition-transform">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo AD" class="h-8 w-auto">
                </div>
                <div class="flex flex-col">
                    <span class="text-white font-black text-xl tracking-tighter leading-none">CIVAD</span>
                    <span class="text-white/60 font-medium text-[10px] tracking-normal mt-0.5">CV. Arya Duta Tangerang</span>
                </div>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#tentang" class="text-xs font-black text-white/70 hover:text-white uppercase tracking-widest transition-colors">Tentang</a>
                <a href="#katalog" class="text-xs font-black text-white/70 hover:text-white uppercase tracking-widest transition-colors">Katalog</a>
                <a href="{{ url('/login') }}" class="px-8 py-3 bg-emerald-500 text-emerald-950 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-white transition-all active:scale-95 shadow-lg shadow-emerald-500/20">Masuk</a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="md:hidden text-white p-2" id="mobile-menu-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu" class="hidden md:hidden absolute top-full left-6 right-6 mt-4 glass-dark rounded-[32px] p-8 shadow-2xl animate-in slide-in-from-top duration-300">
            <div class="flex flex-col gap-6">
                <a href="#tentang" class="text-sm font-black text-white uppercase tracking-widest mobile-link">Tentang</a>
                <a href="#katalog" class="text-sm font-black text-white uppercase tracking-widest mobile-link">Katalog</a>
                <a href="{{ url('/login') }}" class="w-full py-4 bg-emerald-500 text-emerald-950 rounded-2xl text-center text-sm font-black uppercase tracking-widest">Masuk</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-emerald-50">
        <!-- Abstract Background -->
        <div class="absolute top-0 right-0 w-1/2 h-full bg-emerald-950 rounded-l-[100px] hidden lg:block transform translate-x-20 -rotate-2"></div>
        <div class="absolute -top-20 -left-20 w-96 h-96 bg-emerald-200/50 rounded-full blur-[100px]"></div>
        
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center relative z-10 w-full">
            <div class="animate-in fade-in slide-in-from-left duration-1000 text-center lg:text-left flex flex-col items-center lg:items-start">
                <div class="inline-flex items-center gap-3 px-4 py-2 bg-emerald-100 rounded-full text-emerald-700 text-[10px] font-black uppercase tracking-[0.2em] mb-8">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Yuk Belanja Sekarang
                </div>
                <h1 class="text-3xl md:text-6xl lg:text-8xl font-black text-emerald-950 leading-[0.9] tracking-tighter mb-8">
                    Mencerdaskan Generasi Bangsa.
                </h1>

                <div class="flex flex-wrap items-center gap-6 justify-center lg:justify-start">
                        <a href="{{ url('/login') }}" class="px-10 py-5 bg-emerald-950 text-white rounded-[24px] font-black text-[16px] shadow-2xl hover:bg-emerald-800 transition-all active:scale-95 flex items-center gap-4 group">
                        Mulai Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>

                </div>
            </div>

            <div class="relative hidden lg:block animate-in fade-in slide-in-from-right duration-1000">
                <div class="relative z-10 w-full aspect-square rounded-[60px] overflow-hidden shadow-2xl floating">
                    <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=1740&auto=format&fit=crop" class="w-full h-full object-cover" alt="Arya Duta Education">
                    <div class="absolute inset-0 bg-gradient-to-t from-emerald-950/60 to-transparent"></div>
                    <div class="absolute bottom-10 left-10 right-10">
                </div>
                <!-- Decorative Circles -->
                <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-accent-amber rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
            </div>
        </div>
    </header>

    <!-- Stats Bar -->
    <section class="py-20 bg-white relative z-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 md:gap-12 border-y border-emerald-100 py-12 md:py-16">
                <div class="text-center group border-b border-emerald-50 sm:border-b-0 pb-6 sm:pb-0">
                    <p class="text-3xl md:text-5xl font-black text-emerald-950 mb-2 group-hover:text-emerald-500 transition-colors">1998</p>
                    <p class="text-[9px] md:text-[10px] font-black text-emerald-900/60 uppercase tracking-[0.2em] md:tracking-[0.3em]">Tahun Berdiri</p>
                </div>
                <div class="text-center group border-b border-emerald-50 sm:border-b-0 pb-6 sm:pb-0">
                    <p class="text-3xl md:text-5xl font-black text-emerald-950 mb-2 group-hover:text-emerald-500 transition-colors">500+</p>
                    <p class="text-[9px] md:text-[10px] font-black text-emerald-900/60 uppercase tracking-[0.2em] md:tracking-[0.3em]">Judul Terbitan</p>
                </div>
                <div class="text-center group pt-6 sm:pt-0">
                    <p class="text-3xl md:text-5xl font-black text-emerald-950 mb-2 group-hover:text-emerald-500 transition-colors">100%</p>
                    <p class="text-[9px] md:text-[10px] font-black text-emerald-900/60 uppercase tracking-[0.2em] md:tracking-[0.3em]">Terpercaya</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Catalog Section -->
    <section id="katalog" class="py-24 bg-emerald-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-6">
                <div>
                    <h2 class="text-5xl font-black text-emerald-950 tracking-tighter leading-none mb-4">Katalog Buku</h2>
                    <p class="text-emerald-600 font-bold uppercase tracking-[0.2em] text-xs">Temukan referensi belajar terbaik untuk siswa</p>
                </div>
                <a href="{{ url('/login?role=pelanggan') }}" class="group inline-flex items-center gap-3 text-sm font-black text-emerald-950 hover:text-emerald-600 transition-colors">
                    LIHAT SEMUA KOLEKSI
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4 group-hover:translate-x-1 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <!-- Search Bar -->
            <form action="{{ url('/#katalog') }}" method="GET" class="mb-12 max-w-lg">
                <div class="relative flex items-center group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 absolute left-4 text-emerald-950/40 group-focus-within:text-emerald-600 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul buku, penulis, atau jenjang..." class="w-full bg-white border-2 border-emerald-950 rounded-2xl py-3 pl-12 pr-[140px] text-[13px] font-bold text-emerald-950 focus:outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-500/5 transition-all placeholder:text-emerald-950/30">
                    
                    <div class="absolute right-2 flex items-center gap-2">
                        @if(request('search'))
                            <a href="{{ url('/#katalog') }}" class="text-emerald-950/50 hover:text-rose-500 transition-colors font-black text-[10px] uppercase tracking-widest mr-1.5">
                                ✕ Reset
                            </a>
                        @endif
                        <button type="submit" class="bg-emerald-950 hover:bg-emerald-900 text-white font-black text-[10px] uppercase tracking-widest px-4 py-2 rounded-xl transition-all duration-300">
                            Cari
                        </button>
                    </div>
                </div>
            </form>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-10">
                @forelse($dummyBooks as $book)
                <div class="bg-white border-2 border-emerald-950 rounded-[36px] p-5 shadow-2xl flex flex-col relative overflow-hidden group hover:-translate-y-3 transition-all duration-500 hover:shadow-emerald-900/20 h-full">

                    <!-- Image Wrapper -->
                    <div class="relative aspect-[3/4] rounded-[28px] overflow-hidden bg-emerald-50 mb-6 shadow-md border border-emerald-50">
                        <img src="{{ $book->image }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <!-- Category Badge Floating -->
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="px-4 py-1.5 bg-red-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">
                                {{ $book->category }}
                            </span>
                            <span class="px-4 py-1.5 bg-white text-emerald-950 text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg border border-emerald-100">
                                {{ $book->class }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-grow px-1 md:px-2">
                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mb-3">
                            <span class="text-[9px] md:text-[11px] font-bold text-emerald-600 uppercase tracking-widest break-words">{{ $book->author ?? 'Tim Arya Duta' }}</span>
                            <div class="hidden md:block w-1 h-1 bg-emerald-200 rounded-full"></div>
                            <span class="text-[9px] md:text-[11px] font-black text-red-500 uppercase tracking-widest">Stok: {{ $book->stock }}</span>
                        </div>
                        <h3 class="text-sm md:text-[18px] font-extrabold text-emerald-950 leading-tight mb-4 group-hover:text-emerald-600 transition-colors line-clamp-2 min-h-[40px] md:min-h-[44px]">
                            {{ $book->title }}
                        </h3>
                    </div>

                    <!-- Footer / Price & Action -->
                    <div class="mt-4 pt-4 md:pt-6 border-t border-emerald-100 flex flex-col gap-3">
                        <div class="flex items-center justify-between px-1 md:px-2 mb-2">
                            <span class="text-lg md:text-[24px] font-black text-emerald-950 tracking-tight">{{ $book->price }}</span>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-2 md:gap-3">
                            <a href="{{ url('/login?role=pelanggan') }}" class="w-full flex items-center justify-center gap-2 bg-emerald-950 text-white py-3 md:py-4 rounded-[16px] md:rounded-[20px] text-[12px] md:text-[14px] font-black shadow-xl shadow-emerald-950/20 hover:bg-emerald-800 transition-all active:scale-95 group/buy">
                                Pesan
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4 group-hover/buy:translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            </a>

                            <a href="{{ url('/login?role=pelanggan') }}" class="w-full flex items-center justify-center gap-2 bg-white text-emerald-950 border-2 border-emerald-950 py-3.5 rounded-[20px] text-[13px] font-black hover:bg-emerald-50 transition-all active:scale-95 group/cart">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 group-hover/cart:rotate-12 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Detail Koleksi
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center">
                    <div class="w-24 h-24 bg-white rounded-3xl border-2 border-emerald-950 flex items-center justify-center mx-auto mb-6 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-12 h-12 text-emerald-950/40"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                    </div>
                    <p class="text-emerald-950 font-black text-2xl mb-2 tracking-tight">Buku Tidak Ditemukan</p>
                    <p class="text-emerald-600/70 font-bold text-sm">Tidak ada buku yang cocok dengan pencarian "{{ request('search') }}".</p>
                    <a href="{{ url('/#katalog') }}" class="inline-block mt-6 px-6 py-3 bg-emerald-950 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-900 transition-colors">Tampilkan Semua Buku</a>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Identitas Section -->
    <section id="tentang" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Tentang Card -->
            <div class="bg-white p-6 md:p-10 lg:p-14 rounded-[40px] md:rounded-[56px] border-2 border-emerald-950 shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-40 -bottom-40 w-96 h-96 bg-emerald-50 rounded-full blur-[100px] transition-transform duration-1000 group-hover:scale-150"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-8 md:mb-12">
                        <span class="w-12 md:w-20 h-2 bg-emerald-950 rounded-full"></span>
                        <h2 class="text-2xl md:text-[32px] font-black text-emerald-950 tracking-tight uppercase tracking-widest">Identitas Arya Duta</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                        <div class="space-y-8 text-[18px] text-emerald-950/70 leading-[1.8] font-medium">
                            <p class="first-letter:text-5xl first-letter:font-black first-letter:text-emerald-950 first-letter:mr-3 first-letter:float-left">
                                CV Arya Duta adalah perusahaan yang berdiri sejak tahun 1998 dan bergerak di bidang percetakan dan penerbitan buku, terutama buku-buku pendidikan. Dengan pengalaman lebih dari dua dekade, kami berkomitmen untuk menyediakan buku-buku berkualitas yang mendukung pendidikan di Indonesia.
                            </p>
                            <p>
                               Tersedia berbagai macam buku pendidikan untuk jenjang pendidikan dasar, menengah, hingga menengah atas sederajat, dengan harga yang terjangkau dan kualitas terbaik.</p>
                            <p> 
                                Melalui website ini memudahkan pelanggan untuk memesan buku-buku pendidikan dengan praktis dan efisien.
                            </p>
                        </div>
                        <div class="bg-emerald-950 rounded-[32px] md:rounded-[48px] p-8 md:p-12 text-white relative overflow-hidden shadow-2xl transform md:rotate-1 hover:rotate-0 transition-transform duration-700">
                            <div class="absolute top-0 right-0 p-8 opacity-10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-32 h-32"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" /></svg>
                            </div>
                            <div class="relative z-10">
                                <div class="text-4xl md:text-[64px] font-black leading-none mb-4 text-emerald-400">25+</div>
                                <p class="text-[14px] md:text-[16px] font-black uppercase tracking-[0.3em] mb-6 md:mb-8 text-white/50">Tahun Pengalaman</p>
                                <p class="text-[18px] md:text-[20px] font-medium italic leading-relaxed text-emerald-100">"Setiap buku adalah gerbang menuju ilmu, wawasan, dan masa depan yang lebih baik."</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-12">
                <!-- Jam Operasional -->
                <div class="bg-white p-6 md:p-12 rounded-[40px] md:rounded-[56px] border-2 border-emerald-950 shadow-2xl relative group overflow-hidden">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-emerald-950 text-white rounded-2xl md:rounded-3xl flex items-center justify-center mb-8 md:mb-10 shadow-xl transform group-hover:rotate-12 transition-transform duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8 md:w-10 md:h-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="font-black text-emerald-950 mb-8 md:mb-10 tracking-tight uppercase tracking-[0.3em] text-[14px] md:text-[16px]">Jam Operasional</h3>

                    <div class="space-y-4 md:space-y-6">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 md:p-6 bg-emerald-50 rounded-[24px] md:rounded-[28px] border border-emerald-100 transition-colors hover:bg-emerald-100 gap-2">
                            <span class="text-[14px] md:text-[16px] font-bold text-emerald-900">Senin - Jumat</span>
                            <span class="text-[16px] md:text-[18px] font-black text-emerald-950">08.00 - 16.00</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 md:p-6 bg-emerald-50 rounded-[24px] md:rounded-[28px] border border-emerald-100 transition-colors hover:bg-emerald-100 gap-2">
                            <span class="text-[14px] md:text-[16px] font-bold text-emerald-900">Sabtu</span>
                            <span class="text-[16px] md:text-[18px] font-black text-emerald-950">08.00 - 15.00</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-4 md:p-6 bg-rose-50 rounded-[24px] md:rounded-[28px] border border-rose-100 gap-2">
                            <span class="text-[14px] md:text-[16px] font-bold text-rose-500">Minggu</span>
                            <span class="text-[16px] md:text-[18px] font-black text-rose-600 uppercase tracking-widest">Tutup</span>
                        </div>
                    </div>
                </div>

                <!-- Area Layanan -->
                <div class="bg-white p-12 rounded-[56px] border-2 border-emerald-950 shadow-2xl relative group overflow-hidden">
                    <div class="w-20 h-20 bg-emerald-950 text-white rounded-3xl flex items-center justify-center mb-10 shadow-xl transform group-hover:-rotate-12 transition-transform duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </div>
                    <h3 class="font-black text-emerald-950 mb-10 tracking-tight uppercase tracking-[0.3em] text-[16px]">Jangkauan Pengiriman</h3>

                    <p class="text-[16px] font-medium text-emerald-950/60 mb-8">Siap melayani pengiriman untuk wilayah:</p>

                    <div class="flex flex-wrap gap-4 mb-10">
                        <span class="bg-emerald-950 text-white px-8 py-3 rounded-2xl text-[14px] font-black shadow-xl">Jakarta</span>
                        <span class="bg-emerald-950 text-white px-8 py-3 rounded-2xl text-[14px] font-black shadow-xl">Tangerang</span>
                        <span class="bg-emerald-950 text-white px-8 py-3 rounded-2xl text-[14px] font-black shadow-xl">Bekasi</span>
                    </div>

                    <div class="bg-emerald-50 p-8 rounded-[36px] border-2 border-dashed border-emerald-200 group-hover:border-emerald-950 transition-colors duration-500">
                        <p class="text-[11px] font-black text-emerald-900/40 uppercase tracking-widest mb-2">Biaya Pengiriman</p>
                        <p class="text-[28px] font-black text-emerald-950 tracking-tight">Rp 2.800 <span class="text-[14px] font-bold text-emerald-950">/ KM</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Maps Section -->
    <section id="lokasi" class="py-24 bg-emerald-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-white p-8 rounded-[56px] border-2 border-emerald-950 shadow-2xl overflow-hidden relative group">
                <div class="grid lg:grid-cols-3 gap-12 items-center">
                    <div class="lg:col-span-1 p-8">
                        <h2 class="text-4xl font-black text-emerald-950 tracking-tight mb-6">Kunjungi Toko</h2>
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-emerald-950">Alamat Utama</p>
                                    <p class="text-sm text-emerald-900/60 font-medium">Jl. Karyawan 1 No.71, Karang Tengah, Banten, Kota Tangerang - 15157</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-emerald-950">Jam Operasional</p>
                                    <p class="text-sm text-emerald-900/60 font-medium">Senin - Jumat: 08.00 - 16.00</p>
                                    <p class="text-sm text-emerald-900/60 font-medium">Sabtu: 08.00 - 15.00</p>
                                    <p class="text-sm text-emerald-900/60 font-medium">Minggu & Libur Nasional: Tutup</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12">
                            <a href="https://www.google.com/maps?q=-6.216972,106.71125" target="_blank" class="inline-flex items-center gap-3 px-8 py-4 bg-emerald-950 text-white rounded-2xl font-black text-sm shadow-xl hover:bg-emerald-800 transition-all active:scale-95 group">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                Buka di Google Maps
                            </a>
                        </div>
                    </div>
                    <div class="lg:col-span-2 h-[500px] rounded-[40px] overflow-hidden border-4 border-emerald-50">
                        <iframe 
                            src="https://maps.google.com/maps?q=-6.216972,106.71125&hl=id&z=17&output=embed" 
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-emerald-950 text-white py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
                <div class="col-span-1 lg:col-span-2">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="bg-white p-2 rounded-xl">
                            <img src="{{ asset('logo.jpg') }}" alt="Logo" class="h-10 w-auto">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-black tracking-tighter leading-none">CIVAD</span>
                            <span class="text-emerald-100/60 font-medium text-xs tracking-normal mt-1">CV. Arya Duta Tangerang</span>
                        </div>
                    </div>
                    <p class="text-emerald-100/60 max-w-sm leading-relaxed font-medium">
                     Jl. Karyawan 1 No.71 Karang Tengah. Banten, Kota Tangerang - 15157  
                </p>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] mb-8 text-emerald-400">Navigasi</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-sm text-white/60 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#tentang" class="text-sm text-white/60 hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#katalog" class="text-sm text-white/60 hover:text-white transition-colors">Katalog Buku</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] mb-8 text-emerald-400">Kontak</h4>
                    <p class="text-sm text-white/60 leading-relaxed">
                        0858-1119-7530
                    </p>
                </div>
            </div>
            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[11px] font-black text-white/40 uppercase tracking-widest">© 2026 CV Arya Duta. All Rights Reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 transition-all flex items-center justify-center cursor-pointer group">
                        <svg class="w-5 h-5 text-white/60 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 transition-all flex items-center justify-center cursor-pointer group">
                        <svg class="w-5 h-5 text-white/60 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 transition-all flex items-center justify-center cursor-pointer group">
                        <svg class="w-5 h-5 text-white/60 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.324v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            effect: "fade",
            fadeEffect: { crossFade: true },
            autoplay: { delay: 5000 },
            pagination: { el: ".swiper-pagination", clickable: true },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        // Navbar scroll effect
        window.onscroll = function() {
            var nav = document.getElementById('main-nav');
            if (window.pageYOffset > 50) {
                nav.classList.add('py-2');
            } else {
                nav.classList.remove('py-2');
            }
        };

        // Mobile Menu Toggle Logic
        const toggleBtn = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        toggleBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
