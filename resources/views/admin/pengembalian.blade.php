@extends('layouts.admin_premium')

@section('title', 'Manajemen Pengembalian')

@section('header')
    <h2 class="text-white font-black text-xl lg:text-3xl tracking-tighter leading-none">Manajemen Pengembalian</h2>
@endsection

@section('content')
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-emerald-950 tracking-tight">Daftar Pengajuan Pengembalian</h1>
            <p class="text-emerald-700/60 font-bold text-sm">Kelola dan tinjau pengajuan pengembalian barang dari pelanggan beserta bukti video.</p>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($returns as $ret)
            <div class="bg-white rounded-[40px] border-2 border-emerald-950 shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
                <div class="p-6 md:p-10">
                    <div class="flex flex-col lg:flex-row gap-8 justify-between">
                        
                        <!-- Details and Info -->
                        <div class="flex-grow space-y-6">
                            <div class="flex flex-wrap items-center gap-3">
                                @php
                                    $statusColors = [
                                        'Pending' => 'bg-amber-500',
                                        'Disetujui' => 'bg-emerald-600',
                                        'Ditolak' => 'bg-rose-600',
                                    ];
                                    $statusColor = $statusColors[$ret->status] ?? 'bg-slate-500';
                                @endphp
                                <span class="px-3 py-1 {{ $statusColor }} text-white text-[9px] font-black rounded-full uppercase tracking-widest">{{ $ret->status }}</span>
                                <h3 class="font-black text-emerald-950 text-xl tracking-tight">Pesanan #{{ $ret->order->order_number }}</h3>
                                <span class="text-emerald-400 font-bold text-xs uppercase tracking-wider">• {{ $ret->created_at->format('d M Y, H:i') }}</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-1">Pelanggan</span>
                                    <p class="font-black text-emerald-950 text-[15px]">{{ $ret->user->name }}</p>
                                    <p class="text-emerald-700/60 font-bold text-[12px]">{{ $ret->user->email }}</p>
                                </div>
                                <div>
                                    <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-1">Total Pembayaran</span>
                                    <p class="font-black text-emerald-950 text-[15px]">Rp {{ number_format($ret->order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-1">Rekening Pengembalian</span>
                                    <p class="font-black text-emerald-950 text-[15px]">{{ $ret->bank_name ?? '-' }}</p>
                                    <p class="text-emerald-700/60 font-bold text-[12px]">{{ $ret->bank_account_number ?? '-' }}</p>
                                </div>
                            </div>

                            <div>
                                <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-1">Alasan Pengembalian</span>
                                <p class="text-emerald-950 font-bold text-[14px] leading-relaxed bg-emerald-50/30 p-4 rounded-2xl border border-emerald-50">{{ $ret->reason }}</p>
                            </div>

                            @if($ret->admin_notes)
                            <div>
                                <span class="text-[9px] uppercase tracking-widest font-black text-rose-500 block mb-1">Catatan Admin / Alasan Penolakan</span>
                                <p class="text-rose-900 font-bold text-[14px] leading-relaxed bg-rose-50/50 p-4 rounded-2xl border border-rose-100">{{ $ret->admin_notes }}</p>
                            </div>
                            @endif

                            @if($ret->status === 'Pending')
                            <div class="flex items-center gap-4 pt-4">
                                <form action="{{ route('admin.pengembalian.update-status') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengembalian ini?')">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $ret->id }}">
                                    <input type="hidden" name="status" value="Disetujui">
                                    <button type="submit" class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all flex items-center gap-2 shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                        Setujui
                                    </button>
                                </form>

                                <button onclick="openRejectModal('{{ $ret->id }}')" class="px-6 py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all flex items-center gap-2 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                    Tolak
                                </button>
                            </div>
                            @endif
                        </div>

                        <!-- Video Proof Container -->
                        <div class="w-full lg:w-[320px] shrink-0">
                            <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-3">Bukti Video Pengembalian</span>
                            <div class="relative rounded-3xl overflow-hidden bg-black border-2 border-emerald-950/10 shadow-lg">
                                <video width="100%" height="180" controls class="block aspect-video">
                                    <source src="{{ $ret->video_proof }}" type="video/mp4">
                                    Peramban Anda tidak mendukung pemutar video HTML5.
                                </video>
                            </div>
                            <div class="mt-2 text-center">
                                <a href="{{ $ret->video_proof }}" target="_blank" class="inline-flex items-center gap-1.5 text-[10px] font-black text-emerald-600 hover:underline uppercase tracking-wider">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                    Buka di Tab Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-16 rounded-[40px] border border-emerald-100 text-center shadow-xl">
                <div class="w-20 h-20 bg-emerald-50 text-emerald-950 rounded-[32px] flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 text-emerald-100"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" /></svg>
                </div>
                <h3 class="text-xl font-black text-emerald-950 mb-1">Tidak Ada Pengajuan</h3>
                <p class="text-emerald-700/60 font-bold text-sm">Saat ini tidak ada pengajuan pengembalian barang dari pelanggan.</p>
            </div>
        @endforelse
    </div>
@endsection

@section('modals')
    <!-- Rejection Modal -->
    <div id="reject-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-emerald-950/80 backdrop-blur-sm hidden animate-in fade-in duration-300">
        <div class="bg-white w-full max-w-lg rounded-[36px] border-2 border-emerald-950 shadow-2xl p-8 md:p-10 transform scale-95 transition-transform duration-300" id="modal-container">
            <h3 class="font-black text-2xl text-emerald-950 tracking-tight mb-2">Tolak Pengembalian</h3>
            <p class="text-emerald-700/60 font-bold text-xs mb-6">Tuliskan alasan penolakan pengajuan pengembalian ini. Informasi ini akan dikirimkan langsung ke pelanggan.</p>
            
            <form action="{{ route('admin.pengembalian.update-status') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" id="reject-id" value="">
                <input type="hidden" name="status" value="Ditolak">
                
                <div class="flex flex-col gap-2">
                    <label for="admin_notes" class="text-[10px] font-black text-emerald-950 uppercase tracking-[0.2em]">Alasan Penolakan <span class="text-rose-500">*</span></label>
                    <textarea name="admin_notes" id="admin_notes" rows="4" required class="w-full px-5 py-4 rounded-2xl border-2 border-emerald-950/10 focus:border-emerald-950 focus:ring-0 outline-none text-[13px] font-medium leading-relaxed transition-all placeholder:text-emerald-900/35" placeholder="Contoh: Bukti video tidak jelas, barang tidak dibeli dari toko kami, atau bukti tidak kuat..."></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeRejectModal()" class="px-5 py-3.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-950 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all">Batal</button>
                    <button type="submit" class="px-5 py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all shadow-md shadow-rose-600/10">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openRejectModal(id) {
            document.getElementById('reject-id').value = id;
            const modal = document.getElementById('reject-modal');
            const container = document.getElementById('modal-container');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95');
                container.classList.add('scale-100');
            }, 10);
        }

        function closeRejectModal() {
            const modal = document.getElementById('reject-modal');
            const container = document.getElementById('modal-container');
            
            container.classList.remove('scale-100');
            container.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
        
        // Close modal clicking outside
        document.getElementById('reject-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
@endsection
