@extends('layouts.admin_premium')

@section('title', 'Manajemen Pesanan')

@section('scripts')
    <script>
        function openDetailModal(order, unreadChatCount = 0) {
            document.getElementById('modal-order-number').innerText = order.order_number;
            document.getElementById('modal-customer-name').innerText = order.recipient_name || (order.user ? order.user.name : '-');
            document.getElementById('modal-phone-number').innerText = order.phone_number || '-';
            document.getElementById('modal-order-date').innerText = new Date(order.created_at).toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + ' WIB';
            document.getElementById('modal-distance').innerText = order.distance_km ? order.distance_km + ' km' : '-';
            document.getElementById('modal-shipping-service').innerText = order.shipping_service || 'Kurir Standar';
            document.getElementById('modal-total-payment').innerText = 'Rp ' + new Number(order.total_amount).toLocaleString('id-ID');
            document.getElementById('modal-order-id-input').value = order.id;
            document.getElementById('modal-rejection-reason').value = order.rejection_reason || '';
            document.getElementById('modal-customer-address').innerText = order.address || '-';
            document.getElementById('modal-tracking-link-input').value = order.tracking_link || '';
            document.getElementById('modal-chat-btn').href = '/admin/chat/' + order.id;
            if (order.status === 'Selesai' || order.status === 'Dibatalkan' || order.status === 'Dikembalikan') {
                document.getElementById('modal-chat-btn').classList.add('hidden');
            } else {
                document.getElementById('modal-chat-btn').classList.remove('hidden');
            }

            const modalBadge = document.getElementById('modal-chat-badge');
            if (unreadChatCount > 0) {
                modalBadge.innerText = unreadChatCount;
                modalBadge.classList.remove('hidden');
            } else {
                modalBadge.classList.add('hidden');
            }

            const mapsBtn = document.getElementById('modal-maps-link');
            const noMaps = document.getElementById('no-maps-link');
            if (order.latitude && order.longitude) {
                mapsBtn.href = `https://www.google.com/maps/search/?api=1&query=${order.latitude},${order.longitude}`;
                mapsBtn.classList.remove('hidden');
                noMaps.classList.add('hidden');
            } else {
                mapsBtn.classList.add('hidden');
                noMaps.classList.remove('hidden');
            }

            document.getElementById('rejection-box').classList.add('hidden');
            document.getElementById('reject-btn').classList.remove('hidden');
            document.getElementById('confirm-reject-btn').classList.add('hidden');

            const itemsContainer = document.getElementById('modal-items-container');
            itemsContainer.innerHTML = '';
            
            order.items.forEach(item => {
                const itemHtml = `
                    <div class="flex items-center gap-5 bg-emerald-50/30 p-5 rounded-[28px] border border-emerald-100 group/item hover:border-emerald-950/20 transition-all">
                        <div class="w-20 h-20 bg-white rounded-2xl overflow-hidden border border-emerald-100 flex-shrink-0 shadow-sm group-hover/item:scale-105 transition-transform">
                            <img src="${item.book.image}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <h5 class="text-[15px] font-black text-emerald-950 leading-tight">${item.book.title}</h5>
                            <p class="text-[12px] font-bold text-emerald-600 mt-1 uppercase tracking-widest">${item.quantity} Unit x Rp ${new Number(item.price).toLocaleString('id-ID')}</p>
                        </div>
                        <div class="text-[16px] font-black text-emerald-950 tracking-tighter">Rp ${new Number(item.price * item.quantity).toLocaleString('id-ID')}</div>
                    </div>
                `;
                itemsContainer.insertAdjacentHTML('beforeend', itemHtml);
            });

            // Toggle buttons based on status
            const btnVerifikasi = document.getElementById('btn-verifikasi');
            const btnSedangDikirim = document.getElementById('btn-sedang-dikirim');
            const btnSelesai = document.getElementById('btn-selesai');
            const btnReject = document.getElementById('reject-btn');
            const btnConfirmReject = document.getElementById('confirm-reject-btn');
            const trackingInput = document.getElementById('modal-tracking-link-input');
            
            btnVerifikasi.classList.remove('hidden');
            btnSedangDikirim.classList.remove('hidden');
            btnSelesai.classList.remove('hidden');
            btnReject.classList.remove('hidden');
            
            // Enable tracking input by default
            trackingInput.disabled = false;
            trackingInput.placeholder = "Masukkan link perjalanan pelacakan (misal: https://resi.com/123)";

            if (order.status !== 'Pending') {
                btnVerifikasi.classList.add('hidden');
            }

            if (order.status === 'Pesanan Sedang Dikemas' || order.status === 'Sedang Dikemas') {
                btnSelesai.classList.add('hidden');
            }

            if (order.status === 'Sedang Dikirim' || order.status === 'Pesanan Sedang Dikirim' || order.status === 'Dikirim') {
                btnSedangDikirim.classList.add('hidden');
                btnVerifikasi.classList.add('hidden');
            }

            if (order.status === 'Selesai' || order.status === 'Dibatalkan' || order.status === 'Cancelled' || order.status === 'Dikembalikan' || order.status === 'Pengembalian Ditolak') {
                btnVerifikasi.classList.add('hidden');
                btnSedangDikirim.classList.add('hidden');
                btnSelesai.classList.add('hidden');
                btnReject.classList.add('hidden');
                btnConfirmReject.classList.add('hidden');
                
                // Disable editing of tracking link
                trackingInput.disabled = true;
                trackingInput.placeholder = "Pelacakan Selesai / Tidak Tersedia";
            }

            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function validateTrackingLink() {
            const trackingInput = document.getElementById('modal-tracking-link-input');
            if (!trackingInput.value.trim()) {
                alert('Link Perjalanan Pelacakan (Tracking Link) wajib diisi sebelum memproses status "Sedang Dikirim"!');
                trackingInput.focus();
                return false;
            }
            return true;
        }

        function showRejectionBox() {
            document.getElementById('rejection-box').classList.remove('hidden');
            document.getElementById('reject-btn').classList.add('hidden');
            document.getElementById('confirm-reject-btn').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // Real-time Search and Filter
        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('.order-row');

            rows.forEach(row => {
                const searchData = row.getAttribute('data-search').toLowerCase();
                const statusData = row.getAttribute('data-status');
                
                const matchesSearch = searchData.includes(searchTerm);
                const matchesStatus = statusFilter === 'Semua Status Pesanan' || statusFilter === 'Semua Status Transaksi' || statusData === statusFilter;

                if (matchesSearch && matchesStatus) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }
    </script>
@endsection

@section('header')
    <div class="hidden md:flex items-center gap-3 text-[12px] font-black text-emerald-500 uppercase tracking-widest mb-2">
        <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors">Admin</a>
        <span class="text-white/20">/</span>
        <span class="text-white">Manajemen Pesanan</span>
    </div>
    @endsection

@section('content')
    <!-- Search & Filter -->
    <div class="bg-white p-10 rounded-[32px] border border-emerald-100 shadow-xl mb-12 flex flex-col xl:flex-row gap-8 backdrop-blur-md">
        <div class="relative flex-grow group">
            <div class="absolute inset-y-0 left-0 pl-8 flex items-center pointer-events-none text-emerald-950/30 group-focus-within:text-emerald-950 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            </div>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari nomor pesanan atau nama pelanggan..." 
                   class="w-full bg-emerald-50/50 border-4 border-emerald-950 rounded-[18px] py-6 pl-20 pr-10 text-[16px] font-bold text-emerald-950 focus:outline-none focus:ring-8 focus:ring-emerald-500/5 transition-all placeholder:text-emerald-950/20">
        </div>
        <div class="xl:w-[320px] relative group">
            <div class="absolute inset-y-0 right-8 flex items-center pointer-events-none text-emerald-950/30 group-focus-within:text-emerald-950">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
            </div>
            <select id="statusFilter" onchange="filterTable()" 
                    class="w-full bg-emerald-50/50 border-4 border-emerald-950 rounded-[18px] py-6 px-10 text-[16px] font-black text-emerald-950 focus:outline-none focus:ring-8 focus:ring-emerald-500/5 transition-all appearance-none cursor-pointer">
                <option>Semua Status Pesanan</option>
                <option>Sedang Dikemas</option>
                <option>Sedang Dikirim</option>
                <option>Selesai</option>
                <option>Dibatalkan</option>
                <option>Pengajuan Pending</option>
                <option>Dikembalikan</option>
                <option>Pengembalian Ditolak</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[48px] border border-emerald-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-900 bg-emerald-50/50">
                        <th class="px-10 py-6">ID Pesanan</th>
                        <th class="px-10 py-6">Informasi Pelanggan</th>
                        <th class="px-10 py-6">Waktu Transaksi</th>
                        <th class="px-10 py-6 text-center">Item</th>
                        <th class="px-10 py-6">Total Pembayaran</th>
                        <th class="px-10 py-6 text-center">Status</th>
                        <th class="px-10 py-6 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-emerald-950">
                    @forelse($orders as $order)
                    @php
                        // Map database status to new display labels if needed
                        $statusMap = [
                            'Menunggu Verifikasi' => 'Pending',
                            'Pending' => 'Pending',
                            'Terverifikasi' => 'Verifikasi',
                            'Verifikasi' => 'Verifikasi',
                            'Pesanan Sedang Dikemas' => 'Sedang Dikemas',
                            'Sedang Dikemas' => 'Sedang Dikemas',
                            'Dikirim' => 'Sedang Dikirim',
                            'Sedang Dikirim' => 'Sedang Dikirim',
                            'Pesanan Sedang Dikirim' => 'Sedang Dikirim',
                            'Selesai' => 'Selesai',
                            'Ditolak' => 'Dibatalkan',
                            'Dibatalkan' => 'Dibatalkan',
                            'Cancelled' => 'Dibatalkan',
                            'Pengajuan Pending' => 'Pengajuan Pending',
                            'Dikembalikan' => 'Dikembalikan',
                            'Pengembalian Ditolak' => 'Pengembalian Ditolak'
                        ];
                        $displayStatus = $statusMap[$order->status] ?? $order->status;

                        $statusColors = [
                            'Pending' => 'amber',
                            'Verifikasi' => 'emerald',
                            'Sedang Dikemas' => 'purple',
                            'Sedang Dikirim' => 'blue',
                            'Selesai' => 'emerald',
                            'Dibatalkan' => 'rose',
                            'Pengajuan Pending' => 'orange',
                            'Dikembalikan' => 'indigo',
                            'Pengembalian Ditolak' => 'rose'
                        ];
                        $accentColor = $statusColors[$displayStatus] ?? 'emerald';
                    @endphp
                    <tr class="order-row hover:bg-emerald-50/50 transition-all duration-300 group border-b-2 border-emerald-50/80 last:border-0 relative"
                        data-search="{{ $order->order_number }} {{ $order->user->name ?? '' }} {{ $order->recipient_name ?? '' }} {{ $order->phone_number ?? '' }}"
                        data-status="{{ $displayStatus }}">
                        <td class="px-10 py-8 relative">
                            <!-- Status Indicator Bar -->
                            <div class="absolute left-0 top-6 bottom-6 w-1.5 rounded-r-full {{ 'bg-'.$accentColor.'-500' }} shadow-[0_0_15px_rgba(var(--tw-color-'.$accentColor.'-500),0.5)]"></div>
                            <span class="font-black text-emerald-950 tracking-tighter italic text-[16px]">#{{ $order->order_number }}</span>
                        </td>
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 bg-emerald-950 text-white rounded-2xl flex items-center justify-center font-black text-[16px] shadow-2xl transform group-hover:rotate-6 transition-transform">
                                    {{ substr($order->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-black text-emerald-950 text-[15px] leading-tight">{{ $order->user->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-8">
                            <div class="flex flex-col">
                                <span class="font-black text-emerald-950 text-[14px]">{{ $order->created_at->format('d F Y') }}</span>
                                <span class="text-[11px] font-bold text-emerald-900/40 uppercase tracking-widest mt-1">{{ $order->created_at->format('H:i') }} WIB</span>
                            </div>
                        </td>
                        <td class="px-10 py-8 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="px-5 py-2 bg-emerald-50 text-emerald-950 text-[12px] font-black rounded-2xl border border-emerald-100 shadow-sm">
                                    {{ $order->items->count() }} <span class="text-[10px] opacity-40 ml-1">ITEM</span>
                                </span>
                            </div>
                        </td>
                        <td class="px-10 py-8">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-emerald-400 uppercase tracking-widest mb-1 italic">Total Bill</span>
                                <span class="font-black text-emerald-950 text-[20px] tracking-tighter leading-none">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </td>
                        <td class="px-10 py-8 text-center">
                            @php
                                $colors = [
                                    'Pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'Verifikasi' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'Sedang Dikemas' => 'bg-purple-100 text-purple-700 border-purple-200',
                                    'Sedang Dikirim' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'Selesai' => 'bg-emerald-950 text-white border-emerald-950 shadow-emerald-950/20',
                                    'Dibatalkan' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    'Pengajuan Pending' => 'bg-orange-100 text-orange-700 border-orange-200',
                                    'Dikembalikan' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                    'Pengembalian Ditolak' => 'bg-rose-950 text-white border-rose-950 shadow-rose-950/20',
                                ];
                                $colorClass = $colors[$displayStatus] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                            @endphp
                            <span class="inline-block px-6 py-2.5 rounded-2xl text-[11px] font-black uppercase tracking-[0.15em] border-2 {{ $colorClass }} shadow-lg transition-transform group-hover:scale-105">
                                {{ $displayStatus }}
                            </span>
                        </td>
                        <td class="px-10 py-8 text-center">
                            @php
                                $unreadChatCount = \App\Models\OrderMessage::where('order_id', $order->id)
                                    ->where('sender_type', 'pelanggan')
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            <div class="flex flex-wrap items-center justify-center gap-3">
                                @if(strtolower($order->status) !== 'pengajuan pending')
                                    <button onclick='openDetailModal(@json($order), {{ $unreadChatCount }})' class="px-6 py-3.5 bg-emerald-950 text-white font-black text-[13px] rounded-2xl shadow-xl hover:bg-emerald-900 transition-all active:scale-95 flex items-center gap-2 uppercase tracking-tighter shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-9.75 0h9.75" /></svg>
                                        {{ in_array($order->status, ['Selesai', 'Dikembalikan', 'Pengembalian Ditolak', 'Dibatalkan']) ? 'Detail' : 'Kelola' }}
                                    </button>
                                @endif
                                
                                @if($order->status !== 'Selesai' && $order->status !== 'Dibatalkan' && $order->status !== 'Dikembalikan')
                                    <a href="{{ route('admin.chat', $order->id) }}" class="px-6 py-3.5 bg-white text-emerald-950 border-2 border-emerald-950 font-black text-[13px] rounded-2xl shadow-xl hover:bg-emerald-50 transition-all active:scale-95 flex items-center gap-2 uppercase tracking-tighter relative shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" /></svg>
                                        <span>Chat</span>
                                        @if($unreadChatCount > 0)
                                            <span class="absolute -top-2 -right-2 bg-rose-500 text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow-lg animate-bounce">
                                                {{ $unreadChatCount }}
                                            </span>
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-10 py-24 text-center">
                            <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 text-emerald-200"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                            </div>
                            <p class="text-emerald-950 font-black text-xl mb-1">Database Pesanan Kosong</p>
                            <p class="text-emerald-600 font-bold">Belum ada aktivitas belanja yang tercatat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal Detail Premium -->
    <div id="detailModal" class="fixed inset-0 z-[100] bg-emerald-950/40 backdrop-blur-md hidden items-center justify-center p-6 overflow-y-auto">
        <div class="bg-white w-full max-w-[850px] rounded-[56px] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-500 flex flex-col my-auto max-h-[95vh]">
            <!-- Header -->
            <div class="px-12 py-10 border-b border-emerald-50 bg-emerald-50/20 flex items-center justify-between">
                <div>
                    <h3 class="text-[28px] font-black text-emerald-950 tracking-tighter leading-none">Rincian Transaksi <span id="modal-order-number" class="text-emerald-600 italic"></span></h3>
                </div>
                <button onclick="closeDetailModal()" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white shadow-xl border border-emerald-100 text-emerald-950 hover:bg-rose-500 hover:text-white transition-all transform hover:rotate-90 duration-500 font-bold">✕</button>
            </div>

            <div class="flex-grow overflow-y-auto p-12 custom-scrollbar">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-12">
                    <!-- Left Side: Order Items -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-2 h-6 bg-emerald-950 rounded-full"></div>
                            <h4 class="text-[14px] font-black text-emerald-950 uppercase tracking-[0.2em]">Buku yang Dipesan</h4>
                        </div>
                        <div id="modal-items-container" class="space-y-4">
                            <!-- JS Injection -->
                        </div>

                        <div class="mt-10 p-8 bg-emerald-950 rounded-[40px] text-white shadow-2xl relative overflow-hidden group">
                            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                            <div class="relative z-10">
                                <p class="text-white font-black text-[11px] uppercase tracking-[0.3em] mb-2">Total Pelunasan</p>
                                <p id="modal-total-payment" class="text-[42px] font-black text-white tracking-tighter leading-none">Rp 0</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Logistics -->
                    <div class="space-y-8">
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-2 h-6 bg-emerald-950 rounded-full"></div>
                                <h4 class="text-[14px] font-black text-emerald-950 uppercase tracking-[0.2em]">Informasi Penerima</h4>
                            </div>
                            
                            <div class="bg-white p-8 rounded-[40px] border-2 border-emerald-100 shadow-sm space-y-6">
                                <div class="space-y-1">
                                    <span class="text-[11px] font-black text-emerald-600 uppercase tracking-widest opacity-60">Nama Penerima</span>
                                    <p id="modal-customer-name" class="text-[18px] font-black text-emerald-950">-</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[11px] font-black text-emerald-600 uppercase tracking-widest opacity-60">No. Handphone</span>
                                    <p id="modal-phone-number" class="text-[18px] font-black text-emerald-950">-</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[11px] font-black text-emerald-600 uppercase tracking-widest opacity-60">Waktu Pembelian</span>
                                    <p id="modal-order-date" class="text-[14px] font-bold text-emerald-900">-</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[11px] font-black text-emerald-600 uppercase tracking-widest opacity-60">Estimasi Jarak</span>
                                    <p id="modal-distance" class="text-[14px] font-bold text-emerald-900">-</p>
                                </div>
                                <div class="space-y-1">
                                    <span class="text-[11px] font-black text-emerald-600 uppercase tracking-widest opacity-60">Layanan Pengiriman</span>
                                    <p id="modal-shipping-service" class="text-[14px] font-black text-emerald-950">-</p>
                                </div>
                                <div class="pt-6 border-t border-emerald-50">
                                    <span class="text-[11px] font-black text-emerald-600 uppercase tracking-widest opacity-60 block mb-3">Titik Navigasi</span>
                                    <a id="modal-maps-link" href="#" target="_blank" class="w-full flex items-center justify-center gap-3 px-6 py-4 bg-emerald-50 text-emerald-950 text-[13px] font-black rounded-2xl hover:bg-emerald-950 hover:text-white transition-all border border-emerald-100 hidden">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                        Buka Google Maps
                                    </a>
                                    <p id="no-maps-link" class="text-[13px] font-bold text-rose-400 bg-rose-50 p-4 rounded-2xl border border-rose-100 text-center italic">Titik Koordinat Tidak Tersedia</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Alamat Lengkap Tujuan</label>
                            <div id="modal-customer-address" class="w-full bg-emerald-50/50 border border-emerald-100 rounded-[32px] p-6 text-[14px] font-bold text-emerald-950 leading-relaxed min-h-[120px] italic"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-12 pt-10 border-t border-emerald-100">
                    <form action="{{ url('/admin/pesanan/update-status') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="modal-order-id-input">
                        
                        <div id="rejection-box" class="mb-8 hidden animate-in slide-in-from-top-4 duration-500">
                            <label class="block text-[13px] font-black text-rose-900 uppercase tracking-widest mb-3 ml-1">Berikan Alasan Pembatalan</label>
                            <textarea name="rejection_reason" id="modal-rejection-reason" class="w-full bg-rose-50 border border-rose-100 rounded-[32px] p-8 text-[15px] font-bold text-rose-950 focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-400 transition-all resize-none" rows="4" placeholder="Tuliskan alasan mengapa pesanan ini ditolak atau dibatalkan..."></textarea>
                        </div>

                        <div class="mb-8">
                            <label for="modal-tracking-link-input" class="block text-[11px] font-black text-emerald-950 uppercase tracking-widest mb-2 ml-1">Link Perjalanan Pelacakan (Tracking Link)</label>
                            <input type="text" name="tracking_link" id="modal-tracking-link-input" class="w-full bg-emerald-50/50 border-2 border-emerald-950/10 focus:border-emerald-950 rounded-2xl py-3.5 px-5 text-[14px] font-bold text-emerald-950 focus:outline-none transition-all placeholder:text-emerald-950/20" placeholder="Masukkan link perjalanan pelacakan (misal: https://resi.com/123)">
                        </div>

                        <div class="flex flex-wrap items-center gap-4">
                            <a href="#" id="modal-chat-btn" class="px-10 py-5 bg-white text-emerald-950 border-2 border-emerald-950 text-[15px] font-black rounded-[24px] hover:bg-emerald-50 transition-all active:scale-95 flex items-center gap-2 relative">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" /></svg>
                                <span>Chat Pelanggan</span>
                                <span id="modal-chat-badge" class="absolute -top-2.5 -right-2.5 bg-rose-500 text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow-lg animate-bounce hidden">0</span>
                            </a>
                            <button type="submit" id="btn-verifikasi" name="status" value="Verifikasi" class="px-10 py-5 bg-emerald-600 text-white text-[15px] font-black rounded-[24px] hover:bg-emerald-700 transition-all shadow-2xl shadow-emerald-500/20 active:scale-95">Verifikasi</button>
                            <button type="submit" id="btn-sedang-dikirim" name="status" value="Sedang Dikirim" onclick="return validateTrackingLink()" class="px-10 py-5 bg-blue-600 text-white text-[15px] font-black rounded-[24px] hover:bg-blue-700 transition-all shadow-2xl shadow-blue-500/20 active:scale-95">Sedang Dikirim</button>
                            <button type="submit" id="btn-selesai" name="status" value="Selesai" class="px-10 py-5 bg-emerald-950 text-white text-[15px] font-black rounded-[24px] hover:bg-emerald-900 transition-all shadow-2xl shadow-emerald-950/20 active:scale-95">Selesai</button>
                            
                            <div class="flex-grow"></div>
                            
                            <button type="button" onclick="showRejectionBox()" id="reject-btn" class="px-10 py-5 bg-rose-50 text-rose-600 text-[15px] font-black rounded-[24px] hover:bg-rose-100 transition-all active:scale-95">Batalkan</button>
                            <button type="submit" name="status" value="Dibatalkan" id="confirm-reject-btn" class="px-10 py-5 bg-rose-600 text-white text-[15px] font-black rounded-[24px] hover:bg-rose-700 transition-all shadow-2xl shadow-rose-500/20 animate-pulse hidden">Konfirmasi Pembatalan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
