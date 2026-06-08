@extends('layouts.customer_premium')

@section('title', 'Beranda')

@section('content')

    <!-- Premium Hero Section -->
    <div class="relative py-14 mb-20 overflow-hidden rounded-[60px] bg-emerald-950 text-white p-12 lg:px-24 shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-12">
        <div class="absolute -right-20 -top-20 w-96 h-96 bg-emerald-500/20 blur-[120px] animate-pulse"></div>
        <div class="absolute -left-20 -bottom-20 w-72 h-72 bg-white/5 blur-[100px]"></div>
        
        <div class="relative z-10 max-w-2xl flex flex-col items-center lg:items-start text-center lg:text-left">
            <div class="inline-flex items-center gap-3 bg-white/10 text-emerald-400 px-6 py-2.5 rounded-full text-[11px] font-black uppercase tracking-[0.3em] mb-10 border border-white/10 backdrop-blur-md">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                </span>
                Selamat Datang, {{ Auth::user()->name }}
            </div>

            <h1 class="text-4xl md:text-[56px] lg:text-[72px] font-black text-white tracking-tighter mb-8 leading-[0.9] font-serif">
                Membuka Jendela <br><span class="italic text-emerald-400">Dunia</span>.
            </h1>
            <p class="text-base md:text-[20px] text-white/60 mb-12 font-medium max-w-2xl leading-relaxed">
                Mitra literasi terpercaya Anda sejak 1998. Temukan koleksi buku pendidikan terlengkap dan layanan terbaik dalam satu platform premium.
            </p>

            <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 md:gap-6">
                <a href="{{ url('/pelanggan/dashboard') }}" class="inline-flex items-center justify-center bg-white text-emerald-950 px-8 md:px-12 py-4 md:py-6 rounded-[20px] md:rounded-[28px] text-[16px] md:text-[18px] font-black shadow-2xl hover:bg-emerald-400 transition-all active:scale-95 group">
                    Eksplorasi Katalog
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 md:w-6 md:h-6 ml-4 group-hover:translate-x-2 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </a>
                <a href="#tentang" class="px-8 md:px-12 py-4 md:py-6 rounded-[20px] md:rounded-[28px] bg-white/5 border-2 border-white/10 text-[16px] md:text-[18px] font-black text-white hover:bg-white/10 transition-all backdrop-blur-md">Tentang</a>
            </div>
        </div>

        <div class="relative z-10 hidden lg:block w-[520px] floating">
            <img src="{{ asset('promosi.jpg') }}" 
                 class="w-full h-auto rounded-[32px] shadow-2xl border-4 border-white/10 -rotate-2 group-hover:rotate-0 transition-transform duration-700" 
                 alt="Books Catalog">
        </div>
    </div>

    <!-- Content Area -->
    <div id="tentang" class="w-full max-w-7xl mx-auto flex flex-col gap-12 px-4">
        <!-- Tentang Card -->
        <div class="bg-white p-10 lg:p-14 rounded-[56px] border-2 border-emerald-950 shadow-2xl relative overflow-hidden group">
            <div class="absolute -right-40 -bottom-40 w-96 h-96 bg-emerald-50 rounded-full blur-[100px] transition-transform duration-1000 group-hover:scale-150"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-12">
                    <span class="w-20 h-2 bg-emerald-950 rounded-full"></span>
                    <h2 class="text-[32px] font-black text-emerald-950 tracking-tight uppercase tracking-widest">Identitas Arya Duta</h2>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="space-y-8 text-[18px] text-emerald-950/70 leading-[1.8] font-medium">
                        <p class="first-letter:text-5xl first-letter:font-black first-letter:text-emerald-950 first-letter:mr-3 first-letter:float-left">
                            CV Arya Duta telah mendedikasikan diri selama lebih dari seperempat abad dalam membangun ekosistem literasi di Indonesia. Berdiri sejak 1998, kami menjadi pilar utama dalam penyediaan buku berkualitas.
                        </p>
                        <p>
                            Komitmen kami tidak hanya mencetak buku, namun memastikan setiap halaman yang kami terbitkan mampu mencerdaskan kehidupan bangsa melalui standar kurikulum yang presisi.
                        </p>
                    </div>
                    <div class="bg-emerald-950 rounded-[48px] p-12 text-white relative overflow-hidden shadow-2xl transform rotate-1 hover:rotate-0 transition-transform duration-700">
                        <div class="absolute top-0 right-0 p-8 opacity-10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-32 h-32"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" /></svg>
                        </div>
                        <div class="relative z-10">
                            <div class="text-[64px] font-black leading-none mb-4 text-emerald-400">25+</div>
                            <p class="text-[16px] font-black uppercase tracking-[0.3em] mb-8 text-white/50">Tahun Pengalaman</p>
                            <p class="text-[20px] font-medium italic leading-relaxed text-emerald-100">"Literasi adalah pondasi terkuat bagi peradaban yang besar."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
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
                        <span class="text-[16px] md:text-[18px] font-black text-rose-600 uppercase tracking-widest">Closed</span>
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

                <p class="text-[16px] font-medium text-emerald-950/60 mb-8">Kurir internal kami siap melayani pengiriman kilat untuk wilayah:</p>

                <div class="flex flex-wrap gap-4 mb-10">
                    <span class="bg-emerald-950 text-white px-8 py-3 rounded-2xl text-[14px] font-black shadow-xl">Jakarta</span>
                    <span class="bg-emerald-950 text-white px-8 py-3 rounded-2xl text-[14px] font-black shadow-xl">Tangerang</span>
                    <span class="bg-emerald-950 text-white px-8 py-3 rounded-2xl text-[14px] font-black shadow-xl">Bekasi</span>
                </div>

                <div class="bg-emerald-50 p-8 rounded-[36px] border-2 border-dashed border-emerald-200 group-hover:border-emerald-950 transition-colors duration-500">
                    <p class="text-[11px] font-black text-emerald-900/40 uppercase tracking-widest mb-2">Flat Rate Delivery</p>
                    <p class="text-[28px] font-black text-emerald-950 tracking-tight">Rp 2.800 <span class="text-[14px] font-bold text-emerald-950">/ KM</span></p>
                </div>
            </div>
        </div>

        <!-- Alert Footer -->
        <div class="bg-emerald-950 text-white p-8 rounded-[40px] flex items-center justify-center gap-6 shadow-2xl border-4 border-emerald-800">
            <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center shadow-inner text-emerald-400 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008H12v-.008z" /></svg>
            </div>
            <p class="text-[15px] font-black tracking-tight italic opacity-80 leading-relaxed text-center lg:text-left">Pastikan lokasi pengiriman Anda berada di area jangkauan kami untuk menikmati layanan pengiriman kilat internal.</p>
        </div>
    </div>
    </div>

@endsection
