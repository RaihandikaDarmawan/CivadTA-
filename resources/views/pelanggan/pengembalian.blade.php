@extends('layouts.customer_premium')

@section('title', 'Ajukan Pengembalian')

@section('content')
<div class="mb-12 text-center md:text-left">
    <a href="{{ url('/pelanggan/riwayat') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 text-emerald-950 rounded-full text-[11px] font-black uppercase tracking-widest hover:bg-emerald-100 transition-all mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke Riwayat
    </a>
    <h1 class="text-[32px] md:text-[48px] font-black text-emerald-950 tracking-tighter leading-none mb-3">Ajukan Pengembalian</h1>
    <p class="text-emerald-700/60 font-bold text-sm md:text-base">Silakan isi formulir di bawah ini untuk mengajukan pengembalian barang beserta bukti video.</p>
</div>

<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Order Details Side Card -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-[32px] border-2 border-emerald-950 shadow-xl overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-100 rounded-full blur-2xl opacity-50"></div>
                
                <h3 class="font-black text-emerald-950 text-lg tracking-tight mb-4 pb-3 border-b border-emerald-50">Detail Pesanan</h3>
                
                <div class="space-y-4">
                    <div>
                        <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-1">Nomor Pesanan</span>
                        <p class="font-black text-emerald-950 text-base">#{{ $order->order_number }}</p>
                    </div>
                    
                    <div>
                        <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-1">Tanggal Transaksi</span>
                        <p class="font-bold text-emerald-950 text-[13px]">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    
                    <div>
                        <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-1">Total Pembayaran</span>
                        <p class="font-black text-emerald-950 text-base">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="pt-4 border-t border-emerald-50">
                        <span class="text-[9px] uppercase tracking-widest font-black text-emerald-400 block mb-2">Daftar Produk</span>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-10 rounded bg-emerald-50 overflow-hidden flex-shrink-0">
                                    <img src="{{ $item->book->image }}" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[12px] font-black text-emerald-950 truncate leading-none mb-1">{{ $item->book->title }}</p>
                                    <p class="text-[10px] font-bold text-emerald-700">{{ $item->quantity }} Pcs • Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Return Form -->
        <div class="md:col-span-2">
            <div class="bg-white p-8 md:p-12 rounded-[40px] border-2 border-emerald-950 shadow-2xl">
                
                @if ($errors->any())
                <div class="mb-8 p-6 bg-rose-50 border border-rose-200 rounded-2xl">
                    <ul class="list-disc list-inside text-[13px] font-bold text-rose-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form action="{{ route('pelanggan.pengembalian.simpan') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    
                    <!-- Shipping Destination Text -->
                    <div class="p-6 bg-emerald-50 border border-emerald-100 rounded-3xl mb-4">
                        <p class="text-[13px] font-bold text-emerald-950 leading-relaxed">
                            Silahkan mengirimkan ke alamat ini: <strong>Jl. Karyawan 1 No.71, Karang Tengah, Banten, Kota Tangerang - 15157</strong>
                        </p>
                    </div>

                    <!-- Reason -->
                    <div class="flex flex-col gap-2">
                        <label for="reason" class="text-[11px] font-black text-emerald-950 uppercase tracking-[0.2em]">Alasan Pengembalian <span class="text-rose-500">*</span></label>
                        <textarea name="reason" id="reason" rows="5" required
                            class="w-full px-6 py-4 rounded-2xl border-2 border-emerald-950/10 focus:border-emerald-950 focus:ring-0 outline-none text-[14px] font-medium leading-relaxed transition-all placeholder:text-emerald-900/35"
                            placeholder="Tuliskan alasan lengkap Anda, seperti cacat cetak, salah buku, halaman rusak/hilang... (Minimal 10 karakter)">{{ old('reason') }}</textarea>
                    </div>
                    
                    <!-- Video Proof -->
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col">
                            <label class="text-[11px] font-black text-emerald-950 uppercase tracking-[0.2em]">Unggah Bukti Video <span class="text-rose-500">*</span></label>
                            <p class="text-[11px] font-bold text-emerald-700/60 mt-1">pastikan rekam terus-menerus & menyorot seluruh permukaan paket sebelum dibuka</p>
                        </div>
                        
                        <div class="relative group/upload">
                            <input type="file" name="video_proof" id="video_proof" accept="video/*" required class="hidden" onchange="updateFileName(this)">
                            
                            <label for="video_proof" class="flex flex-col items-center justify-center border-2 border-dashed border-emerald-950/20 hover:border-emerald-950 rounded-2xl py-6 px-4 cursor-pointer transition-all duration-300 bg-emerald-50/10 hover:bg-emerald-50/30 text-center">
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-950 flex items-center justify-center mb-2.5 border border-emerald-100 group-hover/upload:scale-110 transition-transform duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                                </div>
                                <span class="font-black text-emerald-950 text-[13px] leading-tight block mb-0.5" id="file-label-text">Pilih atau Tarik Berkas Video</span>
                                <span class="text-[10px] font-bold text-emerald-700/60 block" id="file-sub-text">Mendukung MP4, MOV, AVI, atau WEBM (Maks. 50MB)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-emerald-950/5">
                        <div class="flex flex-col gap-2">
                            <label for="bank_name" class="text-[11px] font-black text-emerald-950 uppercase tracking-[0.2em]">Nama Bank <span class="text-rose-500">*</span></label>
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" required placeholder="Contoh: BCA, Mandiri, BRI..."
                                   class="w-full px-6 py-4 rounded-2xl border-2 border-emerald-950/10 focus:border-emerald-950 focus:ring-0 outline-none text-[14px] font-bold text-emerald-950 transition-all placeholder:text-emerald-950/20">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="bank_account_number" class="text-[11px] font-black text-emerald-950 uppercase tracking-[0.2em]">Nomor Rekening <span class="text-rose-500">*</span></label>
                            <input type="text" name="bank_account_number" id="bank_account_number" value="{{ old('bank_account_number') }}" required placeholder="Contoh: 1234567890"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric"
                                   class="w-full px-6 py-4 rounded-2xl border-2 border-emerald-950/10 focus:border-emerald-950 focus:ring-0 outline-none text-[14px] font-bold text-emerald-950 transition-all placeholder:text-emerald-950/20">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-5 bg-emerald-950 text-white rounded-[24px] font-black text-[13px] uppercase tracking-[0.2em] shadow-xl hover:bg-emerald-900 transition-all flex items-center justify-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                        Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateFileName(input) {
        const fileLabel = document.getElementById('file-label-text');
        const fileSub = document.getElementById('file-sub-text');
        
        // Reset dynamic classes
        fileLabel.classList.remove('text-rose-500');
        fileSub.classList.remove('text-rose-500/80');
        
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            const sizeInMB = file.size / (1024 * 1024);
            
            if (sizeInMB > 50) {
                alert('Video gagal diunggah karena ukuran file melebihi 50 MB');
                input.value = ''; // Reset input
                fileLabel.innerText = "Video gagal diunggah karena ukuran file melebihi 50 MB";
                fileLabel.classList.add('text-rose-500');
                fileSub.innerText = "Silakan pilih berkas video lain (Maks. 50MB)";
                fileSub.classList.remove('text-emerald-500');
                fileSub.classList.add('text-rose-500/80');
                return;
            }
            
            fileLabel.innerText = file.name;
            fileSub.innerText = `Ukuran berkas: ${sizeInMB.toFixed(2)} MB`;
            fileSub.classList.add('text-emerald-500');
            fileSub.classList.remove('text-emerald-700/60');
        } else {
            fileLabel.innerText = "Pilih atau Tarik Berkas Video";
            fileSub.innerText = "Mendukung MP4, MOV, AVI, atau WEBM (Maks. 50MB)";
            fileSub.classList.remove('text-emerald-500');
            fileSub.classList.add('text-emerald-700/60');
        }
    }
</script>
@endsection
