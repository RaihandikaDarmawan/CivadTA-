@extends('layouts.admin_premium')

@section('title', 'Manajemen Banner')

@section('header')
    <div class="hidden md:flex items-center gap-3 text-[12px] font-black text-emerald-500 uppercase tracking-widest mb-2">
        <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors text-emerald-500">Admin</a>
        <span class="text-white/20">/</span>
        <span class="text-white">Manajemen Banner</span>
    </div>
@endsection

@section('topbar_actions')
    <button onclick="openAddModal()" class="bg-emerald-950 text-white px-8 py-4 rounded-[22px] text-[14px] font-black hover:bg-emerald-900 transition-all shadow-xl shadow-emerald-950/20 flex items-center gap-3 active:scale-95 group">
        <div class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        </div>
        Tambah Banner Baru
    </button>
@endsection

@section('content')
    <!-- Table Container -->
    <div class="bg-white rounded-[32px] border border-emerald-100 shadow-xl shadow-emerald-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[12px] font-black uppercase tracking-[0.2em] text-white bg-emerald-950">
                        <th class="px-8 py-6 w-[400px]">Preview Visual</th>
                        <th class="px-8 py-6 text-center">Prioritas</th>
                        <th class="px-8 py-6 text-center">Status Publikasi</th>
                        <th class="px-8 py-6 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-emerald-950 divide-y divide-emerald-50">
                    @forelse($banners as $banner)
                    <tr class="hover:bg-emerald-50/50 transition-all group">
                        <td class="px-8 py-6">
                            <div class="relative w-full max-w-[320px] aspect-[21/9] bg-emerald-50 rounded-[20px] overflow-hidden border-4 border-white shadow-lg group-hover:scale-[1.02] transition-transform duration-500">
                                <img src="{{ $banner->image }}" class="absolute inset-0 w-full h-full object-cover">
                                @if(!$banner->is_active)
                                <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] flex items-center justify-center">
                                    <span class="bg-white/80 text-emerald-900 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm">Hidden</span>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="text-[28px] font-black text-emerald-950">
                                {{ $banner->order_priority }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($banner->is_active)
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-950 text-[11px] font-black rounded-full uppercase tracking-widest border border-emerald-950">
                                <span class="w-2 h-2 rounded-full bg-emerald-950 animate-pulse"></span>
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-400 text-[11px] font-black rounded-full uppercase tracking-widest border border-slate-200">
                                <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                Non-aktif
                            </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="openEditModal('{{ $banner->id }}', '{{ $banner->image }}', '{{ $banner->order_priority }}', {{ $banner->is_active }})" class="w-11 h-11 flex items-center justify-center text-emerald-950 bg-white border border-emerald-950 hover:bg-emerald-950 hover:text-white rounded-xl transition-all duration-300 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1-2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                </button>
                                <button onclick="openDeleteModal('{{ $banner->id }}', 'Banner Urutan {{ $banner->order_priority }}')" class="w-11 h-11 flex items-center justify-center text-rose-500 bg-white border border-rose-100 hover:bg-rose-500 hover:text-white hover:border-rose-500 rounded-xl transition-all duration-300 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-24 text-center">
                            <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 text-emerald-200"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            </div>
                            <p class="text-emerald-950 font-black text-xl mb-1">Visual Promo Kosong</p>
                            <p class="text-emerald-600 font-bold">Belum ada banner yang dikonfigurasi.</p>
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
        <div id="modal-card" class="bg-emerald-950 w-full max-w-[700px] rounded-[48px] border border-emerald-800 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-500 my-auto">
            <div class="flex items-center justify-between px-12 py-10 border-b border-emerald-800 bg-white/5">
                <div>
                    <h3 id="modal-title" class="text-[28px] font-black text-white tracking-tighter leading-none">Tambah Banner Baru</h3>
                    <p class="text-[12px] font-black text-white uppercase tracking-[0.2em] mt-2">Konfigurasi visual promosi beranda</p>
                </div>
                <button type="button" onclick="closeModal()" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white/10 shadow-xl border border-emerald-800 text-white hover:bg-rose-600 hover:text-white transition-all transform hover:rotate-90 duration-500 font-bold">✕</button>
            </div>
            
            <form id="banner-form" action="" method="POST" class="p-12">
                @csrf
                <input type="hidden" name="id" id="input-id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Image Area -->
                    <div class="space-y-4">
                        <label class="text-[13px] font-black text-white uppercase tracking-widest ml-1 block">Visual Banner</label>
                        <div class="relative group h-[260px] bg-emerald-900 rounded-[40px] border-3 border-dashed border-emerald-800 overflow-hidden flex flex-col items-center justify-center gap-5 transition-all hover:border-white/20 hover:bg-emerald-800">
                            <img id="preview-image" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                            <div id="upload-placeholder" class="flex flex-col items-center gap-4 text-center px-6">
                                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center shadow-xl text-white group-hover:scale-110 transition-transform duration-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 9 9m-9-9v18" /></svg>
                                </div>
                                <span class="text-[14px] font-black text-white">Upload File</span>
                            </div>
                            <input type="file" id="file-input" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="handleImage(this)">
                            <input type="hidden" name="image" id="input-image" required>
                        </div>
                        <p class="text-[11px] text-white font-black uppercase tracking-widest text-center">Rasio 16:9 atau 1920x600px</p>
                    </div>

                    <!-- Inputs Area -->
                    <div class="space-y-8">
                        <div class="space-y-2">
                            <label class="text-[13px] font-black text-white uppercase tracking-widest ml-1">Urutan Prioritas</label>
                            <input type="number" name="order_priority" id="input-order" required class="w-full px-6 py-4.5 bg-emerald-900 border border-emerald-800 rounded-[24px] text-[18px] font-black text-white focus:outline-none focus:ring-4 focus:ring-emerald-400/20 focus:border-emerald-400" value="0">
                        </div>
                        
                        <div class="pt-4">
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="is_active" id="input-active" value="1" checked class="sr-only peer">
                                <div class="w-16 h-9 bg-emerald-900 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4.5px] after:left-[4.5px] after:bg-white after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                                <span class="ml-4 text-[14px] font-black text-white uppercase tracking-widest">Publikasikan</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6 mt-12">
                    <button type="button" onclick="closeModal()" class="flex-1 py-5 rounded-[24px] border-2 border-emerald-800 text-[15px] font-black text-white hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit" class="flex-2 py-5 px-10 rounded-[24px] bg-white text-emerald-950 text-[15px] font-black hover:bg-emerald-50 transition-all shadow-2xl shadow-white/20 active:scale-95">Simpan Konfigurasi</button>
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
            <h3 class="text-[28px] font-black text-emerald-950 tracking-tighter mb-3 leading-none">Hapus Visual?</h3>
            <p class="text-[15px] text-emerald-700 leading-relaxed font-bold mb-10 px-4"><span id="delete-name" class="font-black text-emerald-950 italic"></span> akan dihapus permanen dari sistem.</p>
            <form action="{{ url('/admin/banner/delete') }}" method="POST">
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
        function openAddModal() {
            document.getElementById('modal-title').innerText = "Tambah Banner Baru";
            document.getElementById('banner-form').action = "{{ url('/admin/banner/store') }}";
            document.getElementById('input-id').value = "";
            document.getElementById('input-order').value = "0";
            document.getElementById('input-active').checked = true;
            document.getElementById('input-image').value = "";
            document.getElementById('preview-image').classList.add('hidden');
            document.getElementById('upload-placeholder').classList.remove('hidden');
            document.getElementById('modal-container').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(id, image, order, active) {
            document.getElementById('modal-title').innerText = "Edit Konfigurasi Banner";
            document.getElementById('banner-form').action = "{{ url('/admin/banner/update') }}";
            document.getElementById('input-id').value = id;
            document.getElementById('input-order').value = order;
            document.getElementById('input-active').checked = active == 1;
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
            document.getElementById('delete-name').innerText = name || "Banner ini";
            document.getElementById('modal-delete').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDelete() {
            document.getElementById('modal-delete').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection



