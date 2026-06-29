@extends('layouts.admin_premium')

@section('title', 'Manajemen Buku')

@section('header')
    <div class="hidden md:flex items-center gap-3 text-[12px] font-black text-emerald-500 uppercase tracking-widest mb-2">
        <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors">Admin</a>
        <span class="text-white/20">/</span>
        <span class="text-white">Manajemen Buku</span>
    </div>
    @endsection

@section('topbar_actions')
    <button onclick="openAddModal()" class="bg-emerald-950 text-white px-8 py-4 rounded-[22px] text-[14px] font-black hover:bg-emerald-900 transition-all shadow-xl shadow-emerald-950/20 flex items-center gap-3 active:scale-95 group">
        <div class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        </div>
        Tambah Buku
    </button>
@endsection

@section('content')
    <!-- Search & Filter -->
    <form action="{{ url('/admin/manajemen-buku') }}" method="GET" class="bg-white p-6 rounded-[32px] shadow-sm mb-10 flex flex-col md:flex-row gap-4 backdrop-blur-md">
        <div class="relative flex-grow group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 absolute left-6 top-1/2 -translate-y-1/2 text-emerald-900/50 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan judul atau penulis..." class="w-full bg-emerald-50/30 border-2 border-emerald-950 rounded-2xl py-4 pl-16 pr-8 text-[15px] font-bold text-emerald-950 focus:outline-none focus:border-emerald-500 transition-all placeholder:text-emerald-950/20">
        </div>
        <div class="relative min-w-[240px]">
            <select name="category" onchange="this.form.submit()" class="w-full bg-emerald-50/30 border-2 border-emerald-950 rounded-2xl py-4 pl-6 pr-12 text-[15px] font-bold text-emerald-950 focus:outline-none focus:border-emerald-500 appearance-none cursor-pointer transition-all">
                <option value="">Semua Jenjang</option>
                <option value="SD/MI" {{ request('category') == 'SD/MI' ? 'selected' : '' }}>SD/MI</option>
                <option value="SMP/MTs" {{ request('category') == 'SMP/MTs' ? 'selected' : '' }}>SMP/MTs</option>
                <option value="SMA/SMK/MA" {{ request('category') == 'SMA/SMK/MA' ? 'selected' : '' }}>SMA/SMK/MA</option>
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 absolute right-6 top-1/2 -translate-y-1/2 text-emerald-900/50 pointer-events-none"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
        </div>
        <button type="submit" class="bg-emerald-950 text-white px-8 py-4 rounded-2xl font-black text-[15px] hover:bg-emerald-900 shadow-xl shadow-emerald-950/20 active:scale-95 transition-all">Cari</button>
    </form>

    <!-- Table Container -->
    <div class="bg-white rounded-[32px] border-2 border-emerald-950 shadow-xl shadow-emerald-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[12px] font-black uppercase tracking-[0.2em] text-white bg-emerald-950">
                        <th class="px-8 py-6 w-32 text-center">Gambar</th>
                        <th class="px-8 py-6">Judul Buku</th>
                        <th class="px-8 py-6">Jenjang / Kelas</th>
                        <th class="px-8 py-6 text-center">Harga</th>
                        <th class="px-8 py-6 text-center">Stok</th>
                        <th class="px-8 py-6 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-emerald-950 divide-y divide-emerald-50">
                    @forelse($dummyBooks as $book)
                    <tr class="hover:bg-emerald-50/50 transition-all group">
                        <td class="px-8 py-6">
                            <div class="w-20 h-28 bg-emerald-50 rounded-[16px] overflow-hidden border-2 border-white shadow-lg group-hover:scale-105 transition-transform duration-500 mx-auto">
                                <img src="{{ $book->image }}" class="w-full h-full object-cover">
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <p class="font-black text-emerald-950 text-[18px] mb-1.5 leading-tight tracking-tight">{{ $book->title }}</p>
                            <div class="flex items-center gap-2 text-[13px] text-emerald-600 font-bold uppercase tracking-wider">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                {{ $book->author }}
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col items-start gap-2">
                                <span class="inline-flex items-center px-4 py-1.5 bg-emerald-100/50 text-emerald-900 text-[11px] font-black rounded-full border border-emerald-200 uppercase tracking-widest">{{ $book->category }}</span>
                                <span class="text-[13px] font-black text-emerald-950 italic px-2">{{ $book->class }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <p class="font-black text-emerald-900 text-[20px] tracking-tighter">Rp {{ number_format($book->base_price, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="inline-block px-5 py-2.5 bg-white text-[15px] font-black rounded-2xl border-2 shadow-sm @if($book->stock == 0) text-rose-600 border-rose-200 bg-rose-50/30 @elseif($book->stock <= 10) text-amber-600 border-amber-200 bg-amber-50/30 @else text-emerald-950 border-emerald-100 @endif">
                                {{ $book->stock }} <span class="text-[10px] opacity-50 ml-1 uppercase">Unit</span>
                            </span>
                            @if($book->stock == 0)
                                <span class="block mt-1.5 text-[11px] font-black text-rose-500 uppercase tracking-wider">stok sudah habis terjual</span>
                            @elseif($book->stock <= 10)
                                <span class="block mt-1.5 text-[11px] font-black text-amber-500 uppercase tracking-wider">stok sudah mulai menipis</span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal('{{ $book->id }}', '{{ addslashes($book->title) }}', '{{ addslashes($book->author) }}', '{{ $book->category }}', '{{ str_replace('Kelas ', '', $book->class) }}', '{{ $book->base_price }}', '{{ $book->stock }}', '{{ addslashes($book->desc ?? '') }}', '{{ $book->image }}')" class="w-11 h-11 flex items-center justify-center text-emerald-600 bg-white border border-emerald-100 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 rounded-xl transition-all duration-300 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1-2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                </button>
                                <button onclick="openDeleteModal('{{ $book->id }}', '{{ addslashes($book->title) }}')" class="w-11 h-11 flex items-center justify-center text-rose-500 bg-white border border-rose-100 hover:bg-rose-500 hover:text-white hover:border-rose-500 rounded-xl transition-all duration-300 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-10 py-24 text-center">
                            <div class="w-24 h-24 bg-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-12 h-12 text-emerald-200"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                            </div>
                            <p class="text-emerald-950 font-black text-2xl mb-2 tracking-tight">Katalog Tidak Ditemukan</p>
                            <p class="text-emerald-600 font-bold">Coba ubah kata kunci pencarian atau filter kategori Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal Add/Edit -->
    <div id="modal-container" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-emerald-950/40 backdrop-blur-md hidden overflow-y-auto">
        <div id="modal-card" class="bg-white w-full max-w-[800px] rounded-[48px] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-500 my-auto">
            <div class="flex items-center justify-between px-12 py-10 border-b border-emerald-50 bg-emerald-50/20">
                <div>
                    <h3 id="modal-title" class="text-[28px] font-black text-emerald-950 tracking-tighter leading-none">Tambah Buku</h3>
                    <p class="text-[12px] font-black text-emerald-600 uppercase tracking-[0.2em] mt-2">Lengkapi informasi katalog buku</p>
                </div>
                <button onclick="closeModal()" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white shadow-xl border border-emerald-100 text-emerald-950 hover:bg-rose-500 hover:text-white transition-all transform hover:rotate-90 duration-500 font-bold">✕</button>
            </div>
            
            <form id="book-form" action="" method="POST" class="p-12">
                @csrf
                <input type="hidden" name="id" id="input-id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Image Area -->
                    <div class="space-y-4">
                        <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1 block">Gambar Buku</label>
                        <div class="relative group h-[340px] bg-emerald-50/50 rounded-[40px] border-3 border-dashed border-emerald-100 overflow-hidden flex flex-col items-center justify-center gap-5 transition-all hover:border-emerald-950/20 hover:bg-emerald-50">
                            <img id="preview-image" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                            <div id="upload-placeholder" class="flex flex-col items-center gap-4 text-center px-6">
                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-xl text-emerald-950 group-hover:scale-110 transition-transform duration-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 9 9m-9-9v18" /></svg>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[14px] font-black text-emerald-950">Upload Sampul</p>
                                    <p class="text-[11px] font-bold text-emerald-600/60 uppercase tracking-widest leading-relaxed">Pilih file gambar berkualitas tinggi</p>
                                </div>
                            </div>
                            <input type="file" id="file-input" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="handleImage(this)">
                            <input type="hidden" name="image" id="input-image">
                        </div>
                    </div>

                    <!-- Inputs Area -->
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Judul Buku</label>
                            <input type="text" name="title" id="input-title" required class="w-full px-6 py-4.5 bg-emerald-50/50 border border-emerald-100 rounded-[24px] text-[15px] font-black text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950 transition-all placeholder:text-emerald-900/30">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Nama Penulis</label>
                            <input type="text" name="author" id="input-author" required class="w-full px-6 py-4.5 bg-emerald-50/50 border border-emerald-100 rounded-[24px] text-[15px] font-black text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950 transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Jenjang</label>
                                <select name="category" id="input-category" class="w-full px-6 py-4.5 bg-emerald-50/50 border border-emerald-100 rounded-[24px] text-[15px] font-black text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950 appearance-none cursor-pointer">
                                    <option value="SD/MI">SD/MI</option>
                                    <option value="SMP/MTs">SMP/MTs</option>
                                    <option value="SMA/SMK/MA">SMA/SMK/MA</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Kelas</label>
                                <input type="text" name="class_level" id="input-class" required class="w-full px-6 py-4.5 bg-emerald-50/50 border border-emerald-100 rounded-[24px] text-[15px] font-black text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950" placeholder="10">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Harga (Rp)</label>
                                <input type="text" name="price" id="input-price" required class="w-full px-6 py-4.5 bg-emerald-50/50 border border-emerald-100 rounded-[24px] text-[16px] font-black text-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950" placeholder="Contoh: 22.800">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Stok</label>
                                <input type="number" name="stock" id="input-stock" required class="w-full px-6 py-4.5 bg-emerald-50/50 border border-emerald-100 rounded-[24px] text-[16px] font-black text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950">
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Deskripsi </label>
                        <textarea name="desc" id="input-desc" rows="4" class="w-full px-8 py-6 bg-emerald-50/50 border border-emerald-100 rounded-[32px] text-[15px] font-medium text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950 resize-none transition-all" placeholder="Tuliskan ringkasan buku..."></textarea>
                    </div>
                </div>

                <div class="flex items-center gap-6 mt-12">
                    <button type="button" onclick="closeModal()" class="flex-1 py-5 rounded-[24px] border-2 border-emerald-100 text-[15px] font-black text-emerald-950 hover:bg-emerald-50 transition-all">Batal</button>
                    <button type="submit" class="flex-2 py-5 px-10 rounded-[24px] bg-emerald-950 text-white text-[15px] font-black hover:bg-emerald-900 transition-all shadow-2xl shadow-emerald-950/20 active:scale-95">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete -->
    <div id="modal-delete" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-rose-950/40 backdrop-blur-md hidden">
        <div class="bg-white w-full max-w-[480px] rounded-[48px] shadow-2xl p-12 text-center animate-in fade-in zoom-in duration-500">
            <div class="w-24 h-24 bg-rose-50 text-rose-500 rounded-[32px] flex items-center justify-center mx-auto mb-8 border border-rose-100 shadow-xl shadow-rose-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
            </div>
            <h3 class="text-[28px] font-black text-emerald-950 tracking-tighter mb-3 leading-none">Hapus Katalog?</h3>
            <p class="text-[15px] text-emerald-700 leading-relaxed font-bold mb-10 px-4">Buku <span id="delete-name" class="font-black text-emerald-950 italic"></span> akan dihapus permanen dari sistem.</p>
            <form action="{{ url('/admin/buku/delete') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="delete-id">
                <div class="flex items-center gap-4">
                    <button type="button" onclick="closeDelete()" class="flex-1 py-5 rounded-[24px] border-2 border-emerald-100 text-[15px] font-black text-emerald-950 hover:bg-emerald-50 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-5 rounded-[24px] bg-rose-600 text-white text-[15px] font-black hover:bg-rose-700 transition-all shadow-xl shadow-rose-500/20 active:scale-95">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const priceInput = document.getElementById('input-price');

        function formatNumberInput(value) {
            let numberString = value.replace(/\D/g, '');
            return numberString.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        priceInput.addEventListener('input', function(e) {
            this.value = formatNumberInput(this.value);
        });

        document.getElementById('book-form').addEventListener('submit', function(e) {
            priceInput.value = priceInput.value.replace(/\./g, '');
        });

        function openAddModal() {
            document.getElementById('modal-title').innerText = "Tambah Buku Baru";
            document.getElementById('book-form').action = "{{ url('/admin/buku/store') }}";
            document.getElementById('input-id').value = "";
            document.getElementById('input-title').value = "";
            document.getElementById('input-author').value = "";
            document.getElementById('input-class').value = "";
            document.getElementById('input-price').value = "";
            document.getElementById('input-stock').value = "";
            document.getElementById('input-desc').value = "";
            document.getElementById('input-image').value = "";
            document.getElementById('preview-image').classList.add('hidden');
            document.getElementById('upload-placeholder').classList.remove('hidden');
            document.getElementById('modal-container').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(id, title, author, category, classLevel, price, stock, desc, image) {
            document.getElementById('modal-title').innerText = "Edit Katalog Buku";
            document.getElementById('book-form').action = "{{ url('/admin/buku/update') }}";
            document.getElementById('input-id').value = id;
            document.getElementById('input-title').value = title;
            document.getElementById('input-author').value = author;
            document.getElementById('input-category').value = category;
            document.getElementById('input-class').value = classLevel;
            document.getElementById('input-price').value = formatNumberInput(price.toString());
            document.getElementById('input-stock').value = stock;
            document.getElementById('input-desc').value = desc;
            document.getElementById('input-image').value = image;
            
            const preview = document.getElementById('preview-image');
            preview.src = image;
            preview.classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
            
            document.getElementById('modal-container').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modal-container').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function handleImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview-image');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                    document.getElementById('input-image').value = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openDeleteModal(id, name) {
            document.getElementById('delete-id').value = id;
            document.getElementById('delete-name').innerText = name;
            document.getElementById('modal-delete').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDelete() {
            document.getElementById('modal-delete').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
