@extends('layouts.customer_premium')

@section('title', 'Katalog Buku')

@section('content')

    <!-- Header Section -->
    <div class="mb-16 bg-emerald-950 rounded-[40px] border border-emerald-800/30 py-8 px-12 shadow-2xl relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-10">
        <div class="absolute -right-10 -top-10 w-96 h-96 rounded-full bg-emerald-500/20 blur-[120px]"></div>
        <div class="absolute -left-10 -bottom-10 w-72 h-72 rounded-full bg-emerald-400/10 blur-[100px]"></div>
        
        <div class="relative z-10 max-w-2xl text-left">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-500/20 rounded-full border border-emerald-400/20 mb-6">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                <p class="text-[11px] uppercase tracking-[0.4em] font-black text-emerald-400">Katalog Buku</p>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white leading-[1.1] tracking-tighter mb-6 font-serif">Temukan buku pendidikan <br>terbaik untuk setiap jenjang.</h1>
            <p class="text-emerald-100/70 text-lg leading-relaxed max-w-xl font-medium">Jelajahi katalog lengkap kami dengan pilihan buku berkualitas untuk SD/MI, SMP/MTs, MA/SMA/SMK.</p>
        </div>

        <div class="relative z-10 hidden lg:block w-[480px] floating">
            <img src="{{ asset('bukukatalog.png') }}"
                 class="w-full h-auto rounded-[32px] shadow-2xl border-4 border-white/10 -rotate-1 group-hover:rotate-0 transition-transform duration-700" 
                 alt="Educational Books">
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="flex flex-col lg:flex-row gap-6 mb-16">
        <div class="flex-grow relative group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 absolute left-6 top-1/2 -translate-y-1/2 text-emerald-950/40 group-focus-within:text-emerald-700 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            <input type="text" id="search-input" placeholder="Cari judul buku atau penulis..." class="w-full bg-white border-2 border-emerald-950 rounded-[28px] py-5 pl-16 pr-8 text-[16px] font-bold focus:outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-xl placeholder:text-emerald-950/20 text-emerald-950">
        </div>
        <div class="lg:w-[320px] relative">
            <select id="filter-select" class="w-full bg-white border-2 border-emerald-950 rounded-[28px] py-5 px-8 text-[16px] font-extrabold text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all shadow-xl appearance-none cursor-pointer">
                <option value="all">Semua Tingkat</option>
                <option value="SD">SD / MI</option>
                <option value="SMP">SMP / MTs</option>
                <option value="SMA">SMA / SMK / MA</option>
            </select>
            <div class="absolute inset-y-0 right-8 flex items-center pointer-events-none text-emerald-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-12 px-4">
        <div>
            <h2 class="text-[32px] font-extrabold text-emerald-950 tracking-tight">Koleksi Buku Terbaru</h2>
            <p class="text-emerald-700 opacity-80 font-medium mt-1">Tersedia <span class="text-emerald-950 font-black">{{ count($dummyBooks) }}</span> referensi belajar terbaik.</p>
        </div>
    </div>

    <!-- Catalog Grid -->
    <div id="catalog-grid" class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-8">
        @foreach($dummyBooks as $book)
        <div class="book-card bg-white border-2 border-emerald-950 rounded-[32px] md:rounded-[36px] p-4 md:p-5 shadow-2xl flex flex-col relative overflow-hidden group hover:-translate-y-3 transition-all duration-500 hover:shadow-emerald-900/20" 
             data-title="{{ strtolower($book['title']) }}" 
             data-author="{{ strtolower($book['author'] ?? 'arya duta') }}" 
             data-class="{{ $book['class'] }}">

            <!-- Image Wrapper -->
            <div class="relative aspect-[4/5] rounded-[24px] md:rounded-[28px] overflow-hidden bg-emerald-50 mb-3 md:mb-4 shadow-md border border-emerald-50">
                <img src="{{ $book['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <!-- Category Badge Floating -->
                <div class="absolute top-3 left-3 md:top-4 md:left-4 flex flex-col gap-1.5">
                    <span class="px-3 md:px-4 py-1 bg-red-600 text-white text-[8px] md:text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">
                        {{ $book['category'] }}
                    </span>
                    <span class="px-3 md:px-4 py-1 bg-white text-emerald-950 text-[8px] md:text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg border border-emerald-100">
                        {{ $book['class'] }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-grow px-1 md:px-2">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mb-2 md:mb-3">
                    <span class="text-[9px] md:text-[11px] font-bold text-emerald-900 uppercase tracking-widest truncate max-w-[60px] md:max-w-none">{{ $book['author'] ?? 'Arya Duta Team' }}</span>
                    <div class="hidden md:block w-1 h-1 bg-emerald-200 rounded-full"></div>
                    <span class="text-[9px] md:text-[11px] font-black text-red-500 uppercase tracking-widest">Stok: {{ $book['stock'] }}</span>
                </div>
                <a href="{{ url('/pelanggan/buku/' . $book['id']) }}" class="block group/title">
                    <h3 class="text-sm md:text-[17px] font-extrabold text-emerald-950 leading-tight mb-1 md:mb-2 group-hover/title:text-emerald-600 transition-colors line-clamp-3">
                        {{ $book['title'] }}
                    </h3>
                </a>
            </div>

            <!-- Footer / Price & Action -->
            <div class="mt-2 pt-2 md:pt-4 border-t border-emerald-100 flex flex-col gap-3">
                <div class="flex items-center justify-between px-1 md:px-2 mb-1 md:mb-2">
                    <span class="text-lg md:text-[24px] font-black text-emerald-950 tracking-tight">{{ $book['price'] }}</span>
                </div>
                
                <div class="grid grid-cols-1 gap-2 md:gap-3">
                    <!-- Buy Now Button -->
                    <form action="{{ url('/pelanggan/beli-sekarang') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="buku_id" value="{{ $book['id'] }}">
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-emerald-950 text-white py-3 md:py-4 rounded-[16px] md:rounded-[20px] text-[12px] md:text-[14px] font-black shadow-xl shadow-emerald-950/20 hover:bg-emerald-800 transition-all active:scale-95 group/buy">
                            Pesan
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4 group-hover/buy:translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </button>
                    </form>

                    <!-- Add to Cart Button -->
                    <form action="{{ url('/pelanggan/keranjang/tambah') }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="buku_id" value="{{ $book['id'] }}">
                        <input type="hidden" name="qty" value="1">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white text-emerald-950 border-2 border-emerald-950 py-2.5 md:py-3.5 rounded-[16px] md:rounded-[20px] text-[11px] md:text-[13px] font-black hover:bg-emerald-50 transition-all active:scale-95 group/cart">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 group-hover/cart:rotate-12 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden py-24 text-center">
        <div class="w-24 h-24 bg-emerald-50 text-emerald-900 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-12 h-12"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
        </div>
        <h3 class="text-2xl font-black text-emerald-950 mb-2">Buku Tidak Ditemukan</h3>
        <p class="text-emerald-700 font-medium">Coba gunakan kata kunci lain atau pilih tingkat yang berbeda.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const filterSelect = document.getElementById('filter-select');
            const bookCards = document.querySelectorAll('.book-card');
            const emptyState = document.getElementById('empty-state');
            const catalogGrid = document.getElementById('catalog-grid');

            function filterBooks() {
                const searchTerm = searchInput.value.toLowerCase();
                const filterValue = filterSelect.value;
                let visibleCount = 0;

                bookCards.forEach(card => {
                    const title = card.dataset.title;
                    const author = card.dataset.author;
                    const bookClass = card.dataset.class; // e.g. "Kelas 7"
                    
                    // Extract class number
                    const classNum = parseInt(bookClass.match(/\d+/)?.[0]) || 0;
                    
                    let matchesFilter = false;
                    if (filterValue === 'all') {
                        matchesFilter = true;
                    } else if (filterValue === 'SD') {
                        matchesFilter = classNum >= 1 && classNum <= 6;
                    } else if (filterValue === 'SMP') {
                        matchesFilter = classNum >= 7 && classNum <= 9;
                    } else if (filterValue === 'SMA') {
                        matchesFilter = classNum >= 10 && classNum <= 12;
                    }

                    const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm);

                    if (matchesFilter && matchesSearch) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (visibleCount === 0) {
                    catalogGrid.classList.add('hidden');
                    emptyState.classList.remove('hidden');
                } else {
                    catalogGrid.classList.remove('hidden');
                    emptyState.classList.add('hidden');
                }
            }

            searchInput.addEventListener('input', filterBooks);
            filterSelect.addEventListener('change', filterBooks);
        });
    </script>
@endsection
