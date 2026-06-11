@extends('layouts.admin_premium')

@section('title', 'Manajemen Rewards')

@section('header')
    <div class="hidden md:flex items-center gap-3 text-[12px] font-black text-emerald-500 uppercase tracking-widest mb-2">
        <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors">Admin</a>
        <span class="text-white/20">/</span>
        <span class="text-white">Loyalty Rewards</span>
    </div>
@endsection

@section('topbar_actions')
    <button onclick="openAddModal()" class="bg-emerald-950 text-white px-8 py-4 rounded-[22px] text-[14px] font-black hover:bg-emerald-900 transition-all shadow-xl shadow-emerald-950/20 flex items-center gap-3 active:scale-95 group">
        <div class="w-6 h-6 bg-white/10 rounded-lg flex items-center justify-center group-hover:rotate-90 transition-transform duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        </div>
        Tambah Penawaran
    </button>
@endsection

@section('content')
    <!-- Table Container -->
    <div class="bg-white rounded-[48px] border border-emerald-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-900 bg-emerald-50/50">
                        <th class="px-10 py-6">Deskripsi Penawaran</th>
                        <th class="px-10 py-6 text-center">Poin Dibutuhkan</th>
                        <th class="px-10 py-6 text-center">Nilai Diskon</th>
                        <th class="px-10 py-6 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-emerald-950 divide-y divide-emerald-50/50">
                    @foreach($rewards as $reward)
                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                        <td class="px-10 py-7">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" /></svg>
                                </div>
                                <div>
                                    <p class="font-black text-emerald-950 text-[16px] leading-tight">{{ $reward->description ?? 'Diskon Belanja' }}</p>
                                    <p class="text-[11px] font-bold text-emerald-500 uppercase tracking-widest mt-1">Reward Membership</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-7 text-center">
                            <span class="inline-block px-5 py-2 bg-amber-50 text-amber-700 text-[13px] font-black rounded-xl border border-amber-100">
                                {{ $reward->points_required }} POIN
                            </span>
                        </td>
                        <td class="px-10 py-7 text-center">
                            <p class="font-black text-emerald-950 text-lg tracking-tighter">Rp {{ number_format($reward->discount_amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-10 py-7">
                            <div class="flex items-center justify-center gap-3">
                                <button onclick="openEditModal('{{ $reward->id }}', '{{ $reward->points_required }}', '{{ $reward->discount_amount }}', '{{ addslashes($reward->description) }}')" class="w-12 h-12 flex items-center justify-center text-emerald-900 bg-emerald-50 hover:bg-emerald-950 hover:text-white rounded-2xl transition-all duration-500 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1-2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                </button>
                                <button onclick="openDeleteModal('{{ $reward->id }}', '{{ addslashes($reward->description) }}')" class="w-12 h-12 flex items-center justify-center text-rose-500 bg-rose-50 hover:bg-rose-500 hover:text-white rounded-2xl transition-all duration-500 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal Add/Edit -->
    <div id="modal-container" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-emerald-950/40 backdrop-blur-md hidden overflow-y-auto">
        <div id="modal-card" class="bg-white w-full max-w-[500px] rounded-[48px] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-500 my-auto">
            <div class="flex items-center justify-between px-10 py-8 border-b border-emerald-50 bg-emerald-50/20">
                <div>
                    <h3 id="modal-title" class="text-[24px] font-black text-emerald-950 tracking-tighter leading-none">Tambah Penawaran</h3>
                    <p class="text-[11px] font-black text-emerald-600 uppercase tracking-[0.2em] mt-2">Setting poin dan nilai diskon</p>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white shadow-xl border border-emerald-100 text-emerald-950 hover:bg-rose-500 hover:text-white transition-all transform hover:rotate-90 duration-500 font-bold">✕</button>
            </div>
            
            <form id="reward-form" action="" method="POST" class="p-10 space-y-6">
                @csrf
                <input type="hidden" name="id" id="input-id">
                
                <div class="space-y-2">
                    <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Deskripsi</label>
                    <input type="text" name="description" id="input-description" required class="w-full px-6 py-4 bg-emerald-50/50 border border-emerald-100 rounded-[20px] text-[15px] font-black text-emerald-950 focus:outline-none focus:border-emerald-950 transition-all" placeholder="Contoh: Diskon Member Spesial">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Poin Butuh</label>
                        <input type="number" name="points_required" id="input-points" required class="w-full px-6 py-4 bg-emerald-50/50 border border-emerald-100 rounded-[20px] text-[16px] font-black text-amber-600 focus:outline-none focus:border-emerald-950 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[13px] font-black text-emerald-950 uppercase tracking-widest ml-1">Diskon (Rp)</label>
                        <input type="number" name="discount_amount" id="input-discount" required class="w-full px-6 py-4 bg-emerald-50/50 border border-emerald-100 rounded-[20px] text-[16px] font-black text-emerald-600 focus:outline-none focus:border-emerald-950 transition-all">
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-10">
                    <button type="button" onclick="closeModal()" class="flex-1 py-4 rounded-[20px] border-2 border-emerald-50 text-[14px] font-black text-emerald-950 hover:bg-emerald-50 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-4 rounded-[20px] bg-emerald-950 text-white text-[14px] font-black hover:bg-emerald-900 transition-all shadow-2xl shadow-emerald-950/20">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Delete -->
    <div id="modal-delete" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-rose-950/40 backdrop-blur-md hidden">
        <div class="bg-white w-full max-w-[400px] rounded-[40px] shadow-2xl p-10 text-center animate-in fade-in zoom-in duration-500">
            <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-[24px] flex items-center justify-center mx-auto mb-6 border border-rose-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
            </div>
            <h3 class="text-[24px] font-black text-emerald-950 tracking-tighter mb-2">Hapus Penawaran?</h3>
            <p class="text-[14px] text-emerald-700 font-bold mb-8">Penawaran <span id="delete-name" class="text-emerald-950 italic"></span> akan dihapus.</p>
            <form action="{{ url('/admin/rewards/delete') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="delete-id">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeDelete()" class="flex-1 py-4 rounded-[18px] border-2 border-emerald-50 text-[14px] font-black text-emerald-950">Batal</button>
                    <button type="submit" class="flex-1 py-4 rounded-[18px] bg-rose-600 text-white text-[14px] font-black">Hapus</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openAddModal() {
            document.getElementById('modal-title').innerText = "Tambah Penawaran";
            document.getElementById('reward-form').action = "{{ url('/admin/rewards/store') }}";
            document.getElementById('input-id').value = "";
            document.getElementById('input-points').value = "";
            document.getElementById('input-discount').value = "";
            document.getElementById('input-description').value = "";
            document.getElementById('modal-container').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(id, points, discount, description) {
            document.getElementById('modal-title').innerText = "Edit Penawaran";
            document.getElementById('reward-form').action = "{{ url('/admin/rewards/update') }}";
            document.getElementById('input-id').value = id;
            document.getElementById('input-points').value = points;
            document.getElementById('input-discount').value = discount;
            document.getElementById('input-description').value = description;
            document.getElementById('modal-container').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('modal-container').classList.add('hidden');
            document.body.style.overflow = 'auto';
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
