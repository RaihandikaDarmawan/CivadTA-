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

        function openReviewModal(orderId, orderNumber) {
            document.getElementById('modal-review-order-id').value = orderId;
            document.getElementById('modal-review-order-number').innerText = '#' + orderNumber;
            setRating(5); // Default to 5 stars
            document.getElementById('comment-input').value = '';
            
            // Clear any error states
            const commentInput = document.getElementById('comment-input');
            const commentError = document.getElementById('comment-error');
            if (commentError) {
                commentError.classList.add('hidden');
                commentInput.classList.remove('border-rose-500', 'focus:border-rose-500');
                commentInput.classList.add('border-emerald-50/50', 'border-emerald-950/10', 'focus:border-emerald-950');
            }
            
            document.getElementById('reviewModal').classList.remove('hidden');
            document.getElementById('reviewModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.getElementById('reviewModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function setRating(rating) {
            document.getElementById('modal-review-rating-value').value = rating;
            for (let i = 1; i <= 5; i++) {
                const star = document.getElementById('star-' + i);
                if (i <= rating) {
                    star.classList.remove('text-emerald-200');
                    star.classList.add('text-amber-400');
                } else {
                    star.classList.remove('text-amber-400');
                    star.classList.add('text-emerald-200');
                }
            }
        }

        function validateReviewForm() {
            const commentInput = document.getElementById('comment-input');
            const commentError = document.getElementById('comment-error');
            const comment = commentInput.value.trim();
            
            if (comment.length < 5) {
                commentError.classList.remove('hidden');
                commentInput.classList.add('border-rose-500', 'focus:border-rose-500');
                commentInput.classList.remove('border-emerald-50/50', 'border-emerald-950/10', 'focus:border-emerald-950');
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const commentInput = document.getElementById('comment-input');
            if (commentInput) {
                commentInput.addEventListener('input', () => {
                    const commentError = document.getElementById('comment-error');
                    const comment = commentInput.value.trim();
                    if (comment.length >= 5) {
                        commentError.classList.add('hidden');
                        commentInput.classList.remove('border-rose-500', 'focus:border-rose-500');
                        commentInput.classList.add('border-emerald-50/50', 'border-emerald-950/10', 'focus:border-emerald-950');
                    }
                });
            }
        });
    </script>
@endsection

@section('content')
    @if(session('error') || $errors->any())
    <div class="mb-8 p-6 bg-red-50 border border-red-100 text-red-600 text-[13px] rounded-3xl font-bold animate-in fade-in slide-in-from-top-2">
        <ul class="list-disc pl-5 space-y-1">
            @if(session('error'))
                <li>{{ session('error') }}</li>
            @endif
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

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
                                @if(in_array($order->status, ['Dikirim', 'Sedang Dikirim', 'Pesanan Sedang Dikirim']))
                                    <p class="text-[11px] text-emerald-300/80 font-bold mt-2 max-w-md">Mohon lakukan konfirmasi penerimaan pesanan setelah barang diterima. Apabila dalam 2 hari tidak ada konfirmasi, status pesanan akan otomatis diperbarui menjadi selesai</p>
                                @endif
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
                
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full sm:w-auto">
                    @if($order->status == 'Dikirim' || $order->status == 'Sedang Dikirim' || $order->status == 'Pesanan Sedang Dikirim')
                        @if($order->returnRequest)
                            @php
                                $retStatus = $order->returnRequest->status;
                                $retColor = $retStatus === 'Pending' ? 'bg-amber-500' : ($retStatus === 'Disetujui' ? 'bg-emerald-600' : 'bg-rose-600');
                            @endphp
                            <span class="px-4 py-3.5 {{ $retColor }} text-white text-[11px] font-black rounded-2xl uppercase tracking-widest text-center shadow-md whitespace-nowrap shrink-0">
                                Retur: {{ $retStatus }}
                            </span>
                        @else
                            <form action="{{ route('pelanggan.pesanan.selesai') }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Sudah menerima pesanan ini? Pengembalian barang tidak dapat diajukan setelah pesanan diselesaikan.')">
                                @csrf
                                <input type="hidden" name="id" value="{{ $order->id }}">
                                <button type="submit" class="w-full px-4 md:px-5 py-3.5 bg-emerald-950 text-white rounded-2xl font-black text-[11px] md:text-[12px] uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-emerald-900 transition-all whitespace-nowrap shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" /></svg>
                                    Diterima
                                </button>
                            </form>
                            <a href="{{ route('pelanggan.pengembalian.buat', ['order_id' => $order->id]) }}" class="w-full sm:w-auto px-4 md:px-5 py-3.5 bg-amber-500 text-white rounded-2xl font-black text-[11px] md:text-[12px] uppercase tracking-widest hover:bg-amber-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20 whitespace-nowrap shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" /></svg>
                                Pengajuan Pengembalian
                            </a>
                        @endif
                    @elseif($order->status == 'Selesai' || $order->status == 'Dikembalikan')
                        @if($order->returnRequest)
                            @php
                                $retStatus = $order->returnRequest->status;
                                $retColor = $retStatus === 'Pending' ? 'bg-amber-500' : ($retStatus === 'Disetujui' ? 'bg-emerald-600' : 'bg-rose-600');
                            @endphp
                            <span class="px-4 py-3.5 {{ $retColor }} text-white text-[11px] font-black rounded-2xl uppercase tracking-widest text-center shadow-md whitespace-nowrap shrink-0">
                                Retur: {{ $retStatus }}
                            </span>
                        @endif
                    @endif
                    @if($order->status === 'Selesai')
                        @if(!$order->review)
                            <button onclick="openReviewModal('{{ $order->id }}', '{{ $order->order_number }}')" class="w-full sm:w-auto px-4 md:px-5 py-3.5 bg-amber-500 text-white rounded-2xl font-black text-[11px] md:text-[12px] uppercase tracking-widest hover:bg-amber-600 transition-all shadow-md shadow-amber-500/15 active:scale-95 text-center whitespace-nowrap shrink-0">
                                Beri Ulasan
                            </button>
                        @endif
                        <a href="{{ route('pelanggan.invoice.unduh', $order->id) }}" class="w-full sm:w-auto px-4 md:px-5 py-3.5 bg-emerald-950 text-white rounded-2xl font-black text-[11px] md:text-[12px] uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-emerald-900 transition-all shadow-lg shadow-emerald-950/20 whitespace-nowrap shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            Invoice
                        </a>
                    @endif

                    @if($order->status !== 'Selesai' && $order->status !== 'Dibatalkan' && $order->status !== 'Dikembalikan')
                        @php
                            $unreadChatCount = \App\Models\OrderMessage::where('order_id', $order->id)
                                ->where('sender_type', 'admin')
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        <a href="{{ route('pelanggan.chat', $order->id) }}" class="w-full sm:w-auto px-4 md:px-5 py-3.5 bg-white text-emerald-950 border-2 border-emerald-950 rounded-2xl font-black text-[11px] md:text-[12px] uppercase tracking-widest hover:bg-emerald-50 transition-all flex items-center justify-center gap-2 relative shadow-md whitespace-nowrap shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" /></svg>
                            <span>Chat Admin</span>
                            @if($unreadChatCount > 0)
                                <span class="absolute -top-2.5 -right-2.5 bg-rose-500 text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow-lg animate-bounce">
                                    {{ $unreadChatCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                    
                    <button id="btn-{{ $order->id }}" onclick="toggleDetail('{{ $order->id }}')" class="w-full sm:w-auto px-4 md:px-5 py-3.5 bg-emerald-50 text-emerald-950 rounded-2xl font-black text-[11px] md:text-[12px] uppercase tracking-widest border border-emerald-100 flex items-center justify-center gap-3 group/btn whitespace-nowrap shrink-0">
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
                        <div class="bg-white p-6 md:p-8 rounded-[32px] border border-emerald-950/5 space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-emerald-50 text-emerald-950 rounded-xl flex items-center justify-center shrink-0 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-[10px] font-black text-emerald-950 uppercase tracking-widest mb-1">Alamat Tujuan</p>
                                    <p class="text-[14px] font-bold text-emerald-950 leading-relaxed">{{ $order->address ?? 'Alamat tidak tersedia' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 pt-4 border-t border-emerald-50">
                                <div class="w-10 h-10 bg-emerald-50 text-emerald-950 rounded-xl flex items-center justify-center shrink-0 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.12-1.014L1.5 6.75h18.5l-.75 10.986a1.125 1.125 0 0 1-1.12 1.014H18.75m0 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h-1.5M16.5 12h.008v.008H16.5V12Zm-.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375-3h.008v.008H15.75V9Zm-.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-emerald-950 uppercase tracking-widest mb-1">Layanan Pengiriman</p>
                                    <p class="text-[14px] font-bold text-emerald-950 leading-relaxed">{{ $order->shipping_service ?? 'Kurir Standar' }} ({{ $order->distance_km ?? 0 }} km)</p>
                                    <p class="text-[12px] font-black text-emerald-600 mt-0.5">Tarif: Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($order->returnRequest)
                <div class="mt-8 pt-8 border-t border-emerald-950/5">
                    <h4 class="text-[11px] font-black text-emerald-950 uppercase tracking-[0.3em] mb-4">Informasi Pengembalian Barang</h4>
                    <div class="bg-white p-6 rounded-[32px] border border-emerald-950/5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-1">Alasan Pengembalian</span>
                                <p class="text-[13px] font-bold text-emerald-950 leading-relaxed">{{ $order->returnRequest->reason }}</p>
                            </div>
                            <div>
                                <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-1">Rekening Pengembalian</span>
                                <p class="text-[13px] font-bold text-emerald-950 leading-relaxed">{{ $order->returnRequest->bank_name ?? '-' }} ({{ $order->returnRequest->bank_account_number ?? '-' }})</p>
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
                
                @if($order->review)
                <div class="mt-8 pt-8 border-t border-emerald-950/5">
                    <h4 class="text-[11px] font-black text-emerald-950 uppercase tracking-[0.3em] mb-4">Ulasan Anda</h4>
                    <div class="bg-white p-6 rounded-[32px] border border-emerald-950/5 space-y-3">
                        <div class="flex items-center gap-1 text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="{{ $i <= $order->review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c-.195-.39-.6-.628-1.036-.628s-.841.238-1.036.628L6.877 7.575l-4.475.65c-.43.063-.787.353-.923.76-.137.408-.035.856.262 1.162l3.238 3.155-.765 4.457c-.073.428.1.86.46 1.11.36.25.845.267 1.22.046l4.004-2.105 4.004 2.105c.376.221.86.204 1.22-.046.36-.25.533-.682.46-1.11l-.765-4.457 3.238-3.155c.297-.306.399-.754.262-1.162-.136-.407-.493-.697-.923-.76l-4.475-.65-2.032-4.076Z" />
                                </svg>
                            @endfor
                            <span class="text-[12px] text-emerald-900/40 font-bold uppercase tracking-widest ml-2">{{ $order->review->created_at->format('d M Y') }}</span>
                        </div>
                        @if($order->review->comment)
                            <p class="text-[14px] font-bold text-emerald-950 leading-relaxed italic">"{{ $order->review->comment }}"</p>
                        @else
                            <p class="text-[14px] text-emerald-900/40 font-bold italic">Tidak ada komentar.</p>
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

    <!-- Modal Ulasan Premium -->
    <div id="reviewModal" class="fixed inset-0 z-[110] bg-emerald-950/40 backdrop-blur-md hidden items-center justify-center p-6 overflow-y-auto">
        <div class="bg-white w-full max-w-[500px] rounded-[48px] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-500 border-2 border-emerald-950 my-auto">
            <!-- Header -->
            <div class="px-10 py-8 border-b border-emerald-50 bg-emerald-50/20 flex items-center justify-between">
                <div>
                    <h3 class="text-[22px] font-black text-emerald-950 tracking-tighter leading-none">Beri Ulasan</h3>
                    <p class="text-[11px] font-bold text-emerald-600 mt-1 uppercase tracking-wider">Order <span id="modal-review-order-number" class="italic"></span></p>
                </div>
                <button onclick="closeReviewModal()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white shadow-md border border-emerald-100 text-emerald-950 hover:bg-rose-500 hover:text-white transition-all font-bold">✕</button>
            </div>

            <!-- Body -->
            <form action="{{ route('pelanggan.ulasan.simpan') }}" method="POST" class="p-10 space-y-6" onsubmit="return validateReviewForm()">
                @csrf
                <input type="hidden" name="order_id" id="modal-review-order-id">

                <!-- Star Rating selector -->
                <div class="space-y-2 text-center">
                    <label class="block text-[12px] font-black text-emerald-950 uppercase tracking-widest">Rating Kepuasan</label>
                    <div class="flex items-center justify-center gap-2 text-emerald-200">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" id="star-{{ $i }}" class="star-btn w-12 h-12 flex items-center justify-center text-emerald-200 hover:text-amber-400 hover:scale-110 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10">
                                    <path fill-rule="evenodd" d="M10.788 2.903a.75.75 0 0 1 1.424 0l2.032 4.076 4.475.65a.75.75 0 0 1 .416 1.28l-3.238 3.155.765 4.457a.75.75 0 0 1-1.088.791L12 15.002l-4.004 2.105a.75.75 0 0 1-1.088-.791l.765-4.457-3.238-3.155a.75.75 0 0 1 .416-1.28l4.475-.65 2.032-4.076Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="modal-review-rating-value" value="5">
                </div>

                <!-- Text Comment -->
                <div class="space-y-2">
                    <label for="comment-input" class="block text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Komentar / Ulasan</label>
                    <textarea name="comment" id="comment-input" rows="4" class="w-full bg-emerald-50/50 border border-emerald-950/10 focus:border-emerald-950 rounded-2xl p-4 text-[14px] font-bold text-emerald-950 focus:outline-none transition-all placeholder:text-emerald-950/25 resize-none" placeholder="Ceritakan pengalaman belanja Anda..."></textarea>
                    <p id="comment-error" class="text-rose-500 text-[11px] font-bold ml-1 hidden">komentar minimal terdiri dari 5 karakter.</p>
                </div>

                <!-- Action Button -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-4 bg-emerald-950 hover:bg-emerald-900 text-white rounded-[20px] font-black text-[13px] uppercase tracking-widest shadow-xl shadow-emerald-950/20 active:scale-95 transition-all">
                        Kirim Ulasan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
