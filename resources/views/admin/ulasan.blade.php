@extends('layouts.admin_premium')

@section('title', 'Manajemen Ulasan')

@section('header')
    <div class="hidden md:flex items-center gap-3 text-[12px] font-black text-emerald-500 uppercase tracking-widest mb-2">
        <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors">Admin</a>
        <span class="text-white/20">/</span>
        <span class="text-white">Manajemen Ulasan</span>
    </div>
@endsection

@section('content')
    <div class="mb-12 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
            <h1 class="text-[32px] md:text-[40px] font-black text-emerald-950 tracking-tighter leading-none mb-2">Manajemen Ulasan</h1>
            <p class="text-emerald-700/60 font-bold text-[14px]">Daftar feedback dan ulasan bintang dari pelanggan CIVAD</p>
        </div>
    </div>

    <!-- Stats summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Average Rating -->
        <div class="stat-card bg-emerald-950 p-6 rounded-[24px] border border-emerald-800/30 shadow-sm flex flex-col justify-center min-h-[140px] relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <span class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5 block">Rata-Rata Rating</span>
            @php
                $avgRating = $reviews->avg('rating') ?: 0;
            @endphp
            <div class="flex items-center gap-3">
                <span class="text-3xl md:text-[38px] font-extrabold text-white leading-none">{{ number_format($avgRating, 1, '.', ',') }}</span>
                <div class="flex items-center text-amber-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="{{ $i <= round($avgRating) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c-.195-.39-.6-.628-1.036-.628s-.841.238-1.036.628L6.877 7.575l-4.475.65c-.43.063-.787.353-.923.76-.137.408-.035.856.262 1.162l3.238 3.155-.765 4.457c-.073.428.1.86.46 1.11.36.25.845.267 1.22.046l4.004-2.105 4.004 2.105c.376.221.86.204 1.22-.046.36-.25.533-.682.46-1.11l-.765-4.457 3.238-3.155c.297-.306.399-.754.262-1.162-.136-.407-.493-.697-.923-.76l-4.475-.65-2.032-4.076Z" />
                        </svg>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Total Reviews -->
        <div class="stat-card bg-emerald-950 p-6 rounded-[24px] border border-emerald-800/30 shadow-sm flex flex-col justify-center min-h-[140px] relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <span class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5 block">Total Ulasan</span>
            <span class="text-3xl md:text-[38px] font-extrabold text-white leading-none">{{ $reviews->count() }}</span>
        </div>

        <!-- Satisfied customers (rating >= 4) -->
        <div class="stat-card bg-emerald-950 p-6 rounded-[24px] border border-emerald-800/30 shadow-sm flex flex-col justify-center min-h-[140px] relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <span class="text-[10px] font-black text-white/50 uppercase tracking-widest mb-1.5 block">Ulasan Positif (★4 - ★5)</span>
            <span class="text-3xl md:text-[38px] font-extrabold text-white leading-none">{{ $reviews->where('rating', '>=', 4)->count() }}</span>
        </div>
    </div>

    <!-- Table of Reviews -->
    <div class="bg-white rounded-[48px] border-2 border-emerald-950 shadow-sm overflow-hidden mb-12">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-100 bg-emerald-950">
                        <th class="px-10 py-6">ORD & Tanggal</th>
                        <th class="px-10 py-6">Pelanggan</th>
                        <th class="px-10 py-6">Rating</th>
                        <th class="px-10 py-6">Komentar / Feedback</th>
                        <th class="px-10 py-6 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-emerald-950 divide-y divide-emerald-50">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-emerald-50/20 transition-all duration-300">
                            <!-- Order details -->
                            <td class="px-10 py-8">
                                <div class="flex flex-col">
                                    <span class="font-black text-emerald-950 text-[15px] italic">#{{ $review->order->order_number ?? 'ORD-DELETED' }}</span>
                                    <span class="text-[11px] font-bold text-emerald-900/40 uppercase tracking-widest mt-1">{{ $review->created_at->format('d M Y') }}</span>
                                </div>
                            </td>

                            <!-- Customer Profile -->
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-emerald-950 text-white rounded-full flex items-center justify-center font-black text-[14px] uppercase overflow-hidden shrink-0 shadow-inner">
                                        @if($review->user->profile_photo)
                                            <img src="{{ $review->user->profile_photo }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($review->user->name ?? 'U', 0, 1) }}
                                        @endif
                                    </div>
                                    <span class="font-black text-emerald-950 tracking-tight">{{ $review->user->name ?? 'User Terhapus' }}</span>
                                </div>
                            </td>

                            <!-- Star Rating -->
                            <td class="px-10 py-8">
                                <div class="flex items-center text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-4 h-4 shrink-0">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c-.195-.39-.6-.628-1.036-.628s-.841.238-1.036.628L6.877 7.575l-4.475.65c-.43.063-.787.353-.923.76-.137.408-.035.856.262 1.162l3.238 3.155-.765 4.457c-.073.428.1.86.46 1.11.36.25.845.267 1.22.046l4.004-2.105 4.004 2.105c.376.221.86.204 1.22-.046.36-.25.533-.682.46-1.11l-.765-4.457 3.238-3.155c.297-.306.399-.754.262-1.162-.136-.407-.493-.697-.923-.76l-4.475-.65-2.032-4.076Z" />
                                        </svg>
                                    @endfor
                                    <span class="text-[12px] font-black text-emerald-950 ml-1.5">{{ $review->rating }} / 5</span>
                                </div>
                            </td>

                            <!-- Comment -->
                            <td class="px-10 py-8 max-w-sm">
                                @if($review->comment)
                                    <p class="font-medium text-emerald-950 leading-relaxed italic">"{{ $review->comment }}"</p>
                                @else
                                    <span class="text-[12px] font-bold text-emerald-900/30 italic">Tidak ada komentar tambahan.</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-10 py-8 text-center">
                                <button onclick="openDeleteModal('{{ $review->id }}', '{{ $review->user->name ?? 'User' }}')" class="px-6 py-3 bg-rose-50 text-rose-600 font-black text-[12px] rounded-xl hover:bg-rose-600 hover:text-white transition-all active:scale-95 uppercase tracking-wider shadow-sm flex items-center justify-center gap-1.5 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-10 py-20 text-center">
                                <div class="w-16 h-16 bg-emerald-50 text-emerald-200 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c-.195-.39-.6-.628-1.036-.628s-.841.238-1.036.628L6.877 7.575l-4.475.65c-.43.063-.787.353-.923.76-.137.408-.035.856.262 1.162l3.238 3.155-.765 4.457c-.073.428.1.86.46 1.11.36.25.845.267 1.22.046l4.004-2.105 4.004 2.105c.376.221.86.204 1.22-.046.36-.25.533-.682.46-1.11l-.765-4.457 3.238-3.155c.297-.306.399-.754.262-1.162-.136-.407-.493-.697-.923-.76l-4.475-.65-2.032-4.076Z" /></svg>
                                </div>
                                <p class="text-emerald-950 font-black text-[18px] mb-1">Belum Ada Ulasan</p>
                                <p class="text-emerald-600/60 font-bold text-[13px]">Belum ada feedback ulasan dari pelanggan yang masuk.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal Delete Review -->
    <div id="modal-delete-review" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-rose-950/40 backdrop-blur-md hidden">
        <div class="bg-white w-full max-w-[440px] rounded-[48px] shadow-2xl p-10 text-center animate-in fade-in zoom-in duration-500 border-2 border-rose-600">
            <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6 border-2 border-rose-200 shadow-xl shadow-rose-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
            </div>
            <h3 class="text-[24px] font-black text-emerald-950 tracking-tighter mb-2 leading-none">Hapus Ulasan?</h3>
            <p class="text-[14px] text-emerald-700 leading-relaxed font-bold mb-8 px-4">Ulasan dari pelanggan <span id="delete-review-name" class="font-black text-emerald-950"></span> akan dihapus dari sistem secara permanen.</p>
            <form action="{{ route('admin.ulasan.delete') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="delete-review-id">
                <div class="flex items-center gap-4">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 py-4 rounded-[20px] border-2 border-emerald-100 text-[14px] font-black text-emerald-950 hover:bg-emerald-50 transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-4 rounded-[20px] bg-rose-600 text-white text-[14px] font-black hover:bg-rose-700 transition-all shadow-xl shadow-rose-500/20 active:scale-95">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openDeleteModal(id, name) {
            document.getElementById('delete-review-id').value = id;
            document.getElementById('delete-review-name').innerText = name;
            document.getElementById('modal-delete-review').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeDeleteModal() {
            document.getElementById('modal-delete-review').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection
