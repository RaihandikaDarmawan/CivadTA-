@extends('layouts.customer_premium')

@section('title', 'Detail Buku')

@section('scripts')
    <script>
        function updateSubtotal() {
            let qty = parseInt(document.getElementById('qty').value) || 1;
            let price = {{ $book['base_price'] }};
            let subtotal = qty * price;
            // format to Rp
            document.getElementById('subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        }
        function increaseQty() {
            let qty = parseInt(document.getElementById('qty').value) || 1;
            if(qty < {{ $book['stock'] }}) {
                document.getElementById('qty').value = qty + 1;
                updateSubtotal();
            }
        }
        function decreaseQty() {
            let qty = parseInt(document.getElementById('qty').value) || 1;
            if (qty > 1) {
                document.getElementById('qty').value = qty - 1;
                updateSubtotal();
            }
        }
    </script>

@endsection

@section('content')
    <a href="{{ url('/pelanggan/dashboard') }}" class="inline-flex items-center gap-3 text-emerald-950 hover:text-emerald-800 text-[13px] font-black mb-10 transition-all group">
        <div class="w-8 h-8 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center shadow-sm group-hover:-translate-x-1 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        </div>
        Kembali ke Katalog
    </a>

    <div class="bg-white rounded-[40px] md:rounded-[60px] border border-emerald-100 shadow-2xl overflow-hidden mb-12">
        <div class="flex flex-col lg:flex-row">
            <!-- Image Section -->
            <div class="w-full lg:w-1/2 p-6 md:p-12 bg-emerald-50/30 flex items-center justify-center">
                <div class="relative group w-full max-w-[400px]">
                    <div class="absolute inset-0 bg-emerald-950 rounded-[32px] md:rounded-[48px] rotate-3 scale-105 opacity-5 group-hover:rotate-6 transition-transform duration-700"></div>
                    <div class="relative aspect-[3/4] rounded-[32px] md:rounded-[48px] overflow-hidden shadow-2xl border-8 border-white">
                        <img src="{{ $book['image'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="w-full lg:w-1/2 p-8 md:p-16 flex flex-col">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="px-4 py-1.5 bg-emerald-950 text-white text-[10px] font-black uppercase tracking-widest rounded-full">
                        {{ $book['category'] }}
                    </span>
                    <span class="px-4 py-1.5 bg-emerald-50 text-emerald-950 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-100">
                        {{ $book['class'] }}
                    </span>
                    <div class="flex items-center gap-1.5 {{ $book['stock'] <= 0 ? 'text-red-600' : 'text-emerald-900' }} ml-auto">
                        @if($book['stock'] <= 0)
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            <span class="text-[12px] font-black">Stok Habis</span>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            <span class="text-[12px] font-black">Stok Ready</span>
                        @endif
                    </div>
                </div>

                <h1 class="text-[32px] md:text-[48px] font-black text-emerald-950 leading-[1.1] tracking-tighter mb-4">{{ $book['title'] }}</h1>
                <div class="bg-emerald-50/50 rounded-[32px] p-8 border border-emerald-100 mb-10">
                    <p class="text-[12px] font-black text-emerald-800 uppercase tracking-widest mb-2">Harga Per Buku</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-[40px] font-black text-emerald-950 tracking-tighter">{{ $book['price'] }}</span>
                    </div>
                </div>

                <form action="{{ url('/pelanggan/keranjang/tambah') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="buku_id" value="{{ $book['id'] }}">
                    
                    <div class="flex flex-col sm:flex-row items-stretch gap-4">
                        <div class="flex items-center bg-white border-2 border-emerald-950 rounded-2xl p-1.5 shadow-sm {{ $book['stock'] <= 0 ? 'opacity-50 pointer-events-none' : '' }}">
                            <button type="button" onclick="decreaseQty()" class="w-12 h-12 flex items-center justify-center text-emerald-950 hover:bg-emerald-50 rounded-xl transition-all" {{ $book['stock'] <= 0 ? 'disabled' : '' }}>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                            </button>
                            <input type="number" name="qty" id="qty" value="{{ $book['stock'] <= 0 ? 0 : 1 }}" min="{{ $book['stock'] <= 0 ? 0 : 1 }}" max="{{ $book['stock'] }}" onchange="updateSubtotal()" class="w-16 text-center border-0 focus:ring-0 text-xl font-black text-emerald-950 bg-transparent" {{ $book['stock'] <= 0 ? 'disabled' : '' }}>
                            <button type="button" onclick="increaseQty()" class="w-12 h-12 flex items-center justify-center text-emerald-950 hover:bg-emerald-50 rounded-xl transition-all" {{ $book['stock'] <= 0 ? 'disabled' : '' }}>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </button>
                        </div>
                        @if($book['stock'] <= 0)
                            <button type="button" disabled class="flex-grow bg-slate-300 text-slate-600 px-8 py-5 rounded-2xl font-black text-[16px] flex items-center justify-center gap-4 cursor-not-allowed">
                                Stok Habis
                            </button>
                        @else
                            <button type="submit" class="flex-grow bg-emerald-950 text-white px-8 py-5 rounded-2xl font-black text-[16px] hover:bg-emerald-800 transition-all shadow-xl shadow-emerald-950/20 flex items-center justify-center gap-4 group">
                                Tambah ke Keranjang
                                <div class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:translate-x-1 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                </div>
                            </button>
                        @endif
                    </div>
                    
                    <div class="mt-6 flex items-center justify-between px-2">
                        <span class="text-[12px] font-black text-emerald-800 uppercase tracking-widest">Subtotal Estimasi</span>
                        <span id="subtotal" class="text-[24px] font-black text-emerald-950 tracking-tighter">Rp {{ number_format($book['base_price'], 0, ',', '.') }}</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Details/Description -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 space-y-12">
            <section>
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-2 h-10 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-[28px] font-black text-emerald-950 tracking-tight">Deskripsi</h3>
                </div>
                <div class="prose prose-emerald max-w-none text-emerald-800/80 leading-relaxed font-medium text-[16px]">
                    {{ $book['desc'] }}
                </div>
            </section>
        </div>

        <div class="space-y-8">
            <div class="bg-emerald-50 rounded-[40px] p-8 border border-emerald-100 shadow-sm relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/50 rounded-full blur-2xl"></div>
                <h4 class="relative z-10 text-[14px] font-black text-emerald-950 mb-8 uppercase tracking-[0.2em]">Spesifikasi Produk</h4>
                <div class="space-y-6 relative z-10">
                    <div class="flex justify-between items-center pb-4 border-b border-emerald-950/5">
                        <span class="text-[13px] font-bold text-emerald-900/60 uppercase">Stok Tersedia</span>
                        <span class="text-[15px] font-black text-emerald-950">{{ $book['stock'] }} Eks</span>
                    </div>
                    <div class="flex justify-between items-center pb-4 border-b border-emerald-950/5">
                        <span class="text-[13px] font-bold text-emerald-900/60 uppercase">Kategori</span>
                        <span class="text-[15px] font-black text-emerald-950">{{ $book['category'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[13px] font-bold text-emerald-900/60 uppercase">Penerbit</span>
                        <span class="text-[15px] font-black text-emerald-950">CV. Arya Duta</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
@endsection
