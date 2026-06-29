@extends('layouts.customer_premium')

@section('title', 'Keranjang Belanja')

@section('scripts')
    <script>
        function updateCart() {
            let totalProduk = 0;
            let items = document.querySelectorAll('.cart-item');
            let hasStockIssue = false;
            
            items.forEach(item => {
                let id = item.dataset.id;
                let price = parseInt(item.dataset.price);
                let stock = parseInt(item.dataset.stock) || 0;
                let qty = parseInt(document.getElementById('qty-' + id).value) || 0;
                let subtotal = price * qty;
                
                document.getElementById('subtotal-' + id).innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
                totalProduk += subtotal;

                // Check if qty exceeds stock
                let stockWarning = document.getElementById('stock-warning-' + id);
                if (qty > stock) {
                    hasStockIssue = true;
                    if (stockWarning) {
                        stockWarning.classList.remove('hidden');
                    }
                } else {
                    if (stockWarning) {
                        stockWarning.classList.add('hidden');
                    }
                }
            });
            
            document.getElementById('side_total_produk').innerText = 'Rp ' + totalProduk.toLocaleString('id-ID');

            // Handle checkout button and global warning
            const checkoutBtn = document.getElementById('checkout-btn');
            const globalStockAlert = document.getElementById('global-stock-alert');
            
            if (hasStockIssue) {
                if (globalStockAlert) {
                    globalStockAlert.classList.remove('hidden');
                }
                if (checkoutBtn) {
                    checkoutBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    checkoutBtn.setAttribute('href', '#');
                    checkoutBtn.onclick = function(e) {
                        e.preventDefault();
                        alert('stok tidak mencukupi, tolong ubah jumlah stok');
                        return false;
                    };
                }
            } else {
                if (globalStockAlert) {
                    globalStockAlert.classList.add('hidden');
                }
                if (checkoutBtn) {
                    checkoutBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    checkoutBtn.setAttribute('href', '{{ url("/pelanggan/pesanan") }}');
                    checkoutBtn.onclick = null;
                }
            }
        }

        function changeQty(id, delta) {
            let input = document.getElementById('qty-' + id);
            let val = parseInt(input.value) + delta;
            if (val >= 1 && val <= 50) {
                input.value = val;
                
                // Update session via AJAX
                fetch('{{ url("/pelanggan/keranjang/update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id, qty: val })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCart();
                    }
                });
            }
        }

        window.onload = updateCart;
    </script>
@endsection

@section('content')
    <a href="{{ url('/pelanggan/dashboard') }}" class="inline-flex items-center gap-3 text-emerald-600 hover:text-emerald-950 text-[13px] font-black mb-8 md:mb-12 transition-all group">
        <div class="w-8 h-8 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center shadow-sm group-hover:-translate-x-1 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        </div>
        Kembali Belanja
    </a>

    <div class="mb-8 md:mb-16 text-center md:text-left">
        <h1 class="text-[32px] md:text-[48px] font-black text-emerald-950 tracking-tighter leading-none mb-3">Keranjang Belanja</h1>
        <p class="text-emerald-900 font-bold text-[12px] md:text-[14px] uppercase tracking-[0.3em]">Tersimpan <span class="text-emerald-600">{{ count(session('cart', [])) }}</span> Item Pilihan</p>
    </div>

    @if(session('error') && trim(session('error')) !== 'stok tidak mencukupi, tolong ubah jumlah stok')
    <div class="mb-8 p-6 bg-red-50 border border-red-200 rounded-[24px] flex items-center gap-4 text-red-700 font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div id="global-stock-alert" class="mb-8 p-6 bg-red-50 border border-red-200 rounded-[24px] flex items-center gap-4 text-red-700 font-bold hidden">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
        <span>stok tidak mencukupi, tolong ubah jumlah stok</span>
    </div>

    <div class="flex flex-col xl:flex-row gap-8 md:gap-16">
        
        <!-- Items Column -->
        <div class="flex-grow flex flex-col gap-6">
            @forelse(session('cart', []) as $id => $item)
            @php
                $book = \App\Models\Book::find($id);
                $stock = $book ? $book->stock : 0;
            @endphp
            <div class="cart-item bg-white p-4 md:p-8 rounded-[32px] md:rounded-[48px] border border-emerald-100 shadow-xl flex flex-col sm:flex-row gap-6 md:gap-10 relative overflow-hidden hover:border-emerald-200 transition-all" data-id="{{ $id }}" data-price="{{ $item['price'] }}" data-stock="{{ $stock }}">
                <!-- Delete Button -->
                <form action="{{ url('/pelanggan/keranjang/hapus') }}" method="POST" class="absolute top-4 right-4 md:top-8 md:right-8">
                    @csrf
                    <input type="hidden" name="id" value="{{ $id }}">
                    <button type="submit" class="w-10 h-10 flex items-center justify-center text-red-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all group/delete">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 group-hover/delete:scale-110 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                    </button>
                </form>

                <div class="w-full sm:w-32 md:w-40 h-48 sm:h-auto rounded-[24px] md:rounded-[32px] overflow-hidden bg-emerald-50 flex-shrink-0 shadow-inner border border-emerald-50">
                    <img src="{{ $book ? $book->image : '' }}" class="w-full h-full object-cover">
                </div>

                <div class="flex-grow flex flex-col py-1">
                    <div class="mb-4">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="px-3 py-1 bg-emerald-950 text-white text-[9px] font-black uppercase tracking-widest rounded-full">
                                {{ $item['category'] }}
                            </span>
                            <span class="text-[10px] font-black text-emerald-700/50 uppercase tracking-[0.2em]">{{ $item['class'] }}</span>
                        </div>
                        <h3 class="font-black text-emerald-950 text-[20px] md:text-[24px] tracking-tight leading-tight mb-2 max-w-[80%]">{{ $item['title'] }}</h3>
                        <p class="text-[14px] font-bold text-emerald-950">Rp {{ number_format($item['price'], 0, ',', '.') }} <span class="text-[11px] text-emerald-900/60 font-medium">/ unit</span></p>
                        <div class="mt-2 flex items-center gap-3">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Stok: {{ $stock }}</span>
                            <span id="stock-warning-{{ $id }}" class="text-[11px] font-black text-red-500 uppercase tracking-widest hidden">(Stok tidak mencukupi!)</span>
                        </div>
                    </div>
                    
                    <div class="mt-auto flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center bg-emerald-50/50 rounded-2xl p-1 border border-emerald-100">
                            <button type="button" onclick="changeQty('{{ $id }}', -1)" class="w-10 h-10 flex items-center justify-center bg-white border border-emerald-100 rounded-xl text-emerald-600 hover:text-emerald-950 transition-all shadow-sm active:scale-90">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                            </button>
                            <input type="number" id="qty-{{ $id }}" value="{{ $item['qty'] }}" min="1" readonly class="w-12 h-10 text-center border-0 p-0 text-[16px] font-black text-emerald-950 bg-transparent">
                            <button type="button" onclick="changeQty('{{ $id }}', 1)" class="w-10 h-10 flex items-center justify-center bg-white border border-emerald-100 rounded-xl text-emerald-600 hover:text-emerald-950 transition-all shadow-sm active:scale-90">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </button>
                        </div>

                        <div class="text-right">
                             <p class="text-[10px] font-black text-emerald-950 uppercase tracking-widest mb-1">Subtotal Item</p>
                            <p id="subtotal-{{ $id }}" class="font-black text-emerald-950 text-[24px] tracking-tighter leading-none">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white p-12 md:p-24 rounded-[40px] md:rounded-[60px] border-2 border-emerald-50 text-center shadow-xl">
                 <div class="w-24 h-24 bg-emerald-50 text-emerald-950 rounded-[32px] flex items-center justify-center mx-auto mb-8 rotate-3 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-12 h-12"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                </div>
                <h2 class="text-[24px] md:text-[32px] font-black text-emerald-950 mb-4 tracking-tight">Keranjang Anda Kosong</h2>
                <p class="text-emerald-700/60 font-bold mb-10 max-w-sm mx-auto">Tingkatkan pengetahuan anda dengan membeli buku terbaik dari CV. Arya Duta.</p>
                <a href="{{ url('/pelanggan/dashboard') }}" class="inline-flex items-center justify-center px-12 py-5 bg-emerald-950 text-white rounded-2xl font-black shadow-2xl shadow-emerald-950/20 hover:bg-emerald-800 transition-all active:scale-95">Lihat Katalog Utama</a>
            </div>
            @endforelse
        </div>

        <!-- Summary Column -->
        <div class="w-full xl:w-[460px] flex-shrink-0">
            <div class="bg-emerald-950 p-8 md:p-12 rounded-[40px] md:rounded-[56px] text-white shadow-2xl sticky top-28 overflow-hidden group/summary">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-600 rounded-full blur-[100px] opacity-30 group-hover/summary:scale-110 transition-transform duration-1000"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-1.5 h-8 bg-emerald-400 rounded-full"></div>
                        <h2 class="font-black text-[24px] md:text-[28px] tracking-tight">Ringkasan</h2>
                    </div>
                    
                    <div class="space-y-6 mb-12 max-h-[250px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach(session('cart', []) as $item)
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex flex-col">
                                <span class="text-white font-black text-[14px] line-clamp-1 mb-1">{{ $item['title'] }}</span>
                                <span class="text-emerald-400/60 text-[11px] font-bold uppercase tracking-widest">{{ $item['qty'] }} Unit</span>
                            </div>
                            <span class="font-black text-white text-[14px] whitespace-nowrap">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="h-px bg-white/10 mb-10"></div>

                    <div class="flex flex-col gap-2 mb-12">
                        <span class="text-[11px] font-black text-emerald-400 uppercase tracking-[0.2em]">Total Pembelian</span>
                        <div class="flex items-baseline gap-2">
                            <span id="side_total_produk" class="text-[36px] md:text-[44px] font-black tracking-tighter leading-none">Rp 0</span>
                        </div>
                    </div>

                    <div class="bg-white/5 border border-white/10 p-6 rounded-[28px] mb-12 flex items-start gap-4">
                        <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg text-white shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125a1.125 1.125 0 001.125-1.125V3.375c0-.621-.504-1.125-1.125-1.125h-9.75a1.125 1.125 0 00-1.125 1.125v1.433M7.5 14.25L16.344 14.25M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0Zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0Z" /></svg>
                        </div>
                        <p class="text-[12px] text-emerald-200/60 leading-relaxed font-bold">Biaya pengiriman dihitung otomatis berdasarkan jarak tempuh ke lokasi Anda.</p>
                    </div>

                    <a href="{{ url('/pelanggan/pesanan') }}" id="checkout-btn" class="w-full block text-center bg-white text-emerald-950 py-5 rounded-2xl text-[16px] font-black hover:bg-emerald-50 transition-all shadow-xl active:scale-95 group">
                        Pesan
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4 inline-block ml-2 group-hover:translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                    </a>
                </div>
            </div>
        </div>

    </div>
    
    <style>
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
@endsection
