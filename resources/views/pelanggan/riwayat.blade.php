@extends('layouts.customer_premium')

@section('title', 'Riwayat Transaksi')

@section('scripts')
    <script>
        function toggleDetail(id) {
            const detail = document.getElementById('detail-' + id);
            const btn = document.getElementById('btn-' + id);
            const icon = document.getElementById('btn-icon-' + id);
            
            if (detail.classList.contains('hidden')) {
                detail.classList.remove('hidden');
                detail.classList.add('animate-in', 'fade-in', 'slide-in-from-top-4', 'duration-300');
                icon.style.transform = 'rotate(180deg)';
                btn.classList.add('bg-emerald-950', 'text-white');
                btn.classList.remove('text-emerald-950', 'bg-emerald-100');
            } else {
                detail.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
                btn.classList.remove('bg-emerald-950', 'text-white');
                btn.classList.add('text-emerald-950', 'bg-emerald-100');
            }
        }
    </script>

@endsection

@section('content')
    <div class="mb-12 text-center md:text-left">
        <h1 class="text-[32px] md:text-[48px] font-black text-emerald-950 tracking-tighter mb-3 leading-none">Riwayat Transaksi</h1>
    </div>
    
    <div class="max-w-5xl mx-auto w-full flex flex-col gap-8 md:gap-16">
        @forelse($orders as $order)
        <div class="bg-white rounded-[40px] md:rounded-[60px] border-2 border-emerald-950 shadow-2xl overflow-hidden group transition-all duration-500">
            <!-- Card Top Section -->
            <div class="bg-emerald-950 p-6 md:p-12 relative overflow-hidden">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/20 rounded-full blur-[100px]"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
                    <div class="flex items-center gap-4 md:gap-6">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-white/10 backdrop-blur-xl text-emerald-400 rounded-2xl md:rounded-[24px] flex items-center justify-center border border-white/10 shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 md:w-7 md:h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                        </div>
                        <div>
                            @php
                                $statusColors = [
                                    'Pending' => 'bg-amber-500',
                                    'Verifikasi' => 'bg-emerald-500',
                                    'Sedang Dikirim' => 'bg-blue-500',
                                    'Selesai' => 'bg-emerald-500',
                                    'Dibatalkan' => 'bg-rose-500',
                                    'Dikembalikan' => 'bg-rose-600',
                                    'Pengajuan Pending' => 'bg-amber-500',
                                    'Pengembalian Ditolak' => 'bg-rose-600',
                                ];
                                $statusColor = $statusColors[$order->status] ?? 'bg-slate-500';
                            @endphp
                            <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                                <span class="px-3 py-1 {{ $statusColor }} text-white text-[9px] font-black rounded-full uppercase tracking-widest">{{ $order->status }}</span>
                                <h3 class="font-black text-white text-[18px] md:text-[24px] tracking-tighter leading-none">#{{ $order->order_number }}</h3>
                            </div>
                            <p class="text-emerald-300/50 font-bold text-[11px] uppercase tracking-widest">{{ $order->created_at->format('d M Y • H:i') }}</p>
                            @if($order->tracking_link)
                                <div class="mt-2.5 flex items-center gap-1.5 text-emerald-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>
                                    <a href="{{ $order->tracking_link }}" target="_blank" class="font-black text-[11px] tracking-tight underline hover:text-emerald-300 transition-colors truncate max-w-[200px] sm:max-w-xs block" title="{{ $order->tracking_link }}">
                                        {{ $order->tracking_link }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col lg:items-end w-full lg:w-auto pt-4 lg:pt-0 border-t lg:border-0 border-white/10">
                        <span class="text-emerald-400 font-black text-[10px] uppercase tracking-widest mb-1 opacity-60">Total Pembayaran</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-white font-black text-[28px] md:text-[36px] tracking-tighter leading-none">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="px-6 md:px-12 py-6 md:py-8 flex flex-col sm:flex-row items-center justify-between gap-6 bg-white">
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="flex -space-x-3">
                        @foreach($order->items->take(3) as $item)
                            <div class="w-10 h-10 rounded-full border-2 border-white bg-emerald-50 overflow-hidden shadow-md">
                                <img src="{{ $item->book->image }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[13px] font-black text-emerald-950 tracking-tight">
                        {{ $order->items->count() }} Produk
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-stretch gap-3 w-full sm:w-auto">
                    @if($order->status == 'Dikirim' || $order->status == 'Sedang Dikirim' || $order->status == 'Pesanan Sedang Dikirim')
                        @if($order->returnRequest)
                            @php
                                $retStatus = $order->returnRequest->status;
                                $retColor = $retStatus === 'Pending' ? 'bg-amber-500' : ($retStatus === 'Disetujui' ? 'bg-emerald-600' : 'bg-rose-600');
                            @endphp
                            <span class="px-5 py-4 {{ $retColor }} text-white text-[11px] font-black rounded-2xl uppercase tracking-widest text-center shadow-md whitespace-nowrap">
                                Retur: {{ $retStatus }}
                            </span>
                        @else
                            <form action="{{ route('pelanggan.pesanan.selesai') }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Sudah menerima pesanan ini? Pengembalian barang tidak dapat diajukan setelah pesanan diselesaikan.')">
                                @csrf
                                <input type="hidden" name="id" value="{{ $order->id }}">
                                <button type="submit" class="w-full px-6 py-4 bg-emerald-950 text-white rounded-2xl font-black text-[13px] uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-emerald-900 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" /></svg>
                                    Diterima
                                </button>
                            </form>
                            <a href="{{ route('pelanggan.pengembalian.buat', ['order_id' => $order->id]) }}" class="w-full sm:w-auto px-6 py-4 bg-amber-500 text-white rounded-2xl font-black text-[13px] uppercase tracking-widest hover:bg-amber-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" /></svg>
                                Pengajuan Pengembalian
                            </a>
                        @endif
                    @elseif($order->status == 'Selesai' || $order->status == 'Dikembalikan')
                        @if($order->returnRequest)
                            @php
                                $retStatus = $order->returnRequest->status;
                                $retColor = $retStatus === 'Pending' ? 'bg-amber-500' : ($retStatus === 'Disetujui' ? 'bg-emerald-600' : 'bg-rose-600');
                            @endphp
                            <span class="px-5 py-4 {{ $retColor }} text-white text-[11px] font-black rounded-2xl uppercase tracking-widest text-center shadow-md whitespace-nowrap">
                                Retur: {{ $retStatus }}
                            </span>
                        @endif
                    @endif
                    @if($order->status === 'Selesai')
                        <a href="{{ route('pelanggan.invoice.unduh', $order->id) }}" class="w-full sm:w-auto px-6 py-4 bg-emerald-950 text-white rounded-2xl font-black text-[13px] uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-emerald-900 transition-all shadow-lg shadow-emerald-950/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            Invoice
                        </a>
                    @endif
                    
                    <button id="btn-{{ $order->id }}" onclick="toggleDetail('{{ $order->id }}')" class="w-full sm:w-auto px-6 py-4 bg-emerald-50 text-emerald-950 rounded-2xl font-black text-[13px] uppercase tracking-widest border border-emerald-100 flex items-center justify-center gap-3 group/btn">
                        <span>Rincian</span>
                        <svg id="btn-icon-{{ $order->id }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5 transition-transform duration-300 group-hover/btn:translate-y-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                </div>
            </div>

            <!-- Expanded Detail -->
            <div id="detail-{{ $order->id }}" class="hidden px-6 md:px-12 pb-12 pt-4 bg-emerald-50/20">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 pt-8 border-t border-emerald-950/5">
                    <!-- Items List -->
                    <div>
                        <h4 class="text-[11px] font-black text-emerald-950 uppercase tracking-[0.3em] mb-6">Koleksi Buku</h4>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="bg-white p-4 rounded-[24px] border border-emerald-950/5 flex items-center gap-4">
                                <div class="w-12 h-16 rounded-xl overflow-hidden flex-shrink-0">
                                    <img src="{{ $item->book->image }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow">
                                    <h5 class="text-[14px] font-black text-emerald-950 leading-tight line-clamp-1">{{ $item->book->title }}</h5>
                                    <p class="text-[12px] font-bold text-emerald-900 mt-1">{{ $item->quantity }} Pcs • Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Logistics -->
                    <div>
                        <h4 class="text-[11px] font-black text-emerald-950 uppercase tracking-[0.3em] mb-6">Info Pengiriman</h4>
                        <div class="bg-white p-6 md:p-8 rounded-[32px] border border-emerald-950/5">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-emerald-50 text-emerald-950 rounded-xl flex items-center justify-center shrink-0 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-emerald-950 uppercase tracking-widest mb-1">Alamat Tujuan</p>
                                    <p class="text-[14px] font-bold text-emerald-950 leading-relaxed">{{ $order->address ?? 'Alamat tidak tersedia' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($order->returnRequest)
                <div class="mt-8 pt-8 border-t border-emerald-950/5">
                    <h4 class="text-[11px] font-black text-emerald-950 uppercase tracking-[0.3em] mb-4">Informasi Pengembalian Barang</h4>
                    <div class="bg-white p-6 rounded-[32px] border border-emerald-950/5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-1">Alasan Pengembalian</span>
                                <p class="text-[13px] font-bold text-emerald-950 leading-relaxed">{{ $order->returnRequest->reason }}</p>
                            </div>
                            <div>
                                <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-2">Bukti Video</span>
                                <a href="{{ $order->returnRequest->video_proof }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-950 rounded-xl text-[11px] font-black uppercase tracking-wider hover:bg-emerald-100 transition-all border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" /></svg>
                                    Putar Video Bukti
                                </a>
                            </div>
                        </div>
                        
                        @if($order->returnRequest->admin_notes)
                        <div class="pt-4 border-t border-emerald-50">
                            <span class="text-[9px] uppercase tracking-widest font-black text-rose-500 block mb-1">Catatan Admin / Alasan Penolakan</span>
                            <p class="text-[13px] font-bold text-rose-700 leading-relaxed">{{ $order->returnRequest->admin_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white p-12 md:p-24 rounded-[40px] md:rounded-[60px] border border-emerald-100 text-center shadow-xl">
            <p class="text-[20px] font-black text-emerald-950 mb-2">Belum Ada Transaksi</p>
            <p class="text-emerald-700/60 font-bold mb-8">Mulailah petualangan literasi Anda hari ini.</p>
            <a href="{{ url('/pelanggan/dashboard') }}" class="inline-flex items-center px-10 py-4 bg-emerald-950 text-white rounded-2xl font-black shadow-xl hover:bg-emerald-800 transition-all">Lihat Katalog</a>
        </div>
        @endforelse
    </div>
@endsection
