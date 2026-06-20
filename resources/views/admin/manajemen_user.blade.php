@extends('layouts.admin_premium')

@section('title', 'Data Pelanggan')

@section('header')
    <div class="hidden md:flex items-center gap-3 text-[12px] font-black text-emerald-500 uppercase tracking-widest mb-2">
        <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors text-emerald-500">Admin</a>
        <span class="text-white/20">/</span>
        <span class="text-white">Data Pelanggan</span>
    </div>
@endsection

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
        <!-- Card 1: Total Pelanggan -->
        <div class="stat-card bg-emerald-950 p-6 rounded-[24px] border border-emerald-800/30 shadow-sm flex flex-col items-center justify-center relative hover:scale-[1.02] hover:shadow-md transition-all duration-300 group min-h-[160px] overflow-hidden">
            <div class="absolute top-6 left-6 w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
            </div>
            <div class="text-center flex flex-col items-center mt-6">
                <h3 class="text-3xl md:text-[36px] font-extrabold text-white leading-none mb-1.5">{{ $totalCustomers }}</h3>
                <p class="text-[14px] font-bold text-white/70 leading-tight">Total Pelanggan</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>
        
        <!-- Card 2: Total Admin -->
        <div class="stat-card bg-emerald-950 p-6 rounded-[24px] border border-emerald-800/30 shadow-sm flex flex-col items-center justify-center relative hover:scale-[1.02] hover:shadow-md transition-all duration-300 group min-h-[160px] overflow-hidden">
            <div class="absolute top-6 left-6 w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008H12v-.008z" /></svg>
            </div>
            <div class="text-center flex flex-col items-center mt-6">
                <h3 class="text-3xl md:text-[36px] font-extrabold text-white leading-none mb-1.5">{{ $totalAdmins }}</h3>
                <p class="text-[14px] font-bold text-white/70 leading-tight">Total Admin</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>

        <!-- Card 3: Database Users -->
        <div class="stat-card bg-emerald-950 p-6 rounded-[24px] border border-emerald-800/30 shadow-sm flex flex-col items-center justify-center relative hover:scale-[1.02] hover:shadow-md transition-all duration-300 group min-h-[160px] overflow-hidden">
            <div class="absolute top-6 left-6 w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" /></svg>
            </div>
            <div class="text-center flex flex-col items-center mt-6">
                <h3 class="text-3xl md:text-[36px] font-extrabold text-white leading-none mb-1.5">{{ $totalAdmins + $totalCustomers }}</h3>
                <p class="text-[14px] font-bold text-white/70 leading-tight">Database Users</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white p-8 rounded-[32px] shadow-sm mb-12 flex flex-col md:flex-row gap-4 backdrop-blur-md">
        <form action="{{ url('/admin/manajemen-user') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full">
            <div class="relative flex-grow group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 absolute left-5 top-1/2 -translate-y-1/2 text-emerald-900/30 group-focus-within:text-emerald-950 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau username..." class="w-full bg-emerald-50/30 border-2 border-emerald-950 rounded-2xl py-3.5 pl-14 pr-6 text-[14px] font-bold text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950 transition-all placeholder:text-emerald-900/30">
            </div>
            <button type="submit" class="bg-emerald-950 text-white px-10 py-3.5 rounded-2xl font-black text-[14px] hover:bg-emerald-900 transition-all shadow-xl shadow-emerald-950/20 active:scale-95">Cari Data</button>
        </form>
    </div>

    <!-- ADMIN TABLE SECTION -->
    <div class="mb-16">
        <div class="flex items-center justify-between mb-8 px-4">
            <div>
                <h3 class="text-[24px] font-black text-emerald-950 tracking-tighter">Daftar Admin</h3>
            </div>
        </div>
        
        <div class="bg-white rounded-[48px] border-2 border-emerald-950 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-100 bg-emerald-950">
                            <th class="px-10 py-6">Profil Admin</th>
                            <th class="px-10 py-6">ID Username</th>
                            <th class="px-10 py-6">Email</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] text-emerald-950 divide-y divide-emerald-50/50">
                        @forelse($admins as $admin)
                        <tr class="hover:bg-emerald-50/30 transition-colors group">
                            <td class="px-10 py-7">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 bg-white border-2 border-emerald-100 rounded-[22px] flex items-center justify-center font-black text-[18px] shadow-sm overflow-hidden group-hover:scale-110 transition-transform duration-500 uppercase text-emerald-950">
                                        @if($admin->profile_photo)
                                            <img src="{{ $admin->profile_photo }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($admin->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-black text-emerald-950 text-[16px] tracking-tight">{{ $admin->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-7 font-black text-emerald-950 tracking-tight">{{ $admin->username }}</td>
                            <td class="px-10 py-7 text-emerald-950 tracking-tight">{{ $admin->email ?? $admin->username . '@civad.com' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-10 py-16 text-center text-emerald-900/30 font-bold italic">Tidak ada administrator ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- CUSTOMER TABLE SECTION -->
    <div>
        <div class="mb-8 px-4">
            <h3 class="text-[24px] font-black text-emerald-950 tracking-tighter">Data Pelanggan</h3>
            </div>
        
        <div class="bg-white rounded-[48px] border-2 border-emerald-950 shadow-sm overflow-hidden mb-12">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-100 bg-emerald-950">
                            <th class="px-10 py-6">Nama Lengkap</th>
                            <th class="px-10 py-6">Username</th>
                            <th class="px-10 py-6">Email</th>
                            <th class="px-10 py-6">Wilayah</th>
                            <th class="px-10 py-6 text-center">Point</th>
                        </tr>
                    </thead>
                    <tbody class="text-[14px] text-emerald-950 divide-y divide-emerald-50/50">
                        @forelse($customers as $customer)
                        <tr class="hover:bg-emerald-50/30 transition-colors group">
                            <td class="px-10 py-7">
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 bg-white border-2 border-emerald-100 rounded-[22px] flex items-center justify-center font-black text-[18px] shadow-sm overflow-hidden group-hover:scale-110 transition-transform duration-500 uppercase text-emerald-950">
                                        @if($customer->profile_photo)
                                            <img src="{{ $customer->profile_photo }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($customer->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <p class="font-black text-emerald-950 text-[16px] tracking-tight">{{ $customer->name }}</p>
                                </div>
                            </td>
                            <td class="px-10 py-7 font-black text-emerald-950 tracking-tight">{{ $customer->username }}</td>
                            <td class="px-10 py-7 text-emerald-950 tracking-tight">{{ $customer->email }}</td>
                            <td class="px-10 py-7 text-emerald-950 tracking-tight font-black uppercase text-[12px]">{{ $customer->daerah ?? '-' }}</td>
                            <td class="px-10 py-7">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="px-5 py-2.5 bg-emerald-950 text-white text-[14px] font-black rounded-2xl shadow-xl min-w-[120px] text-center tracking-tighter border-2 border-emerald-800">
                                        {{ number_format($customer->points ?? 0, 0, ',', '.') }} <span class="text-[10px] text-white ml-1">PTS</span>
                                    </span>
                                    <form action="{{ route('admin.user.update-points') }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $customer->id }}">
                                        <input type="number" name="points" placeholder="Tambah" required class="w-24 bg-emerald-50 border-2 border-emerald-950 rounded-xl px-4 py-2 text-[12px] font-black text-emerald-950 focus:outline-none focus:ring-2 focus:ring-emerald-950/10 transition-all text-center">
                                        <button type="submit" class="w-10 h-10 flex items-center justify-center bg-emerald-950 text-white rounded-xl hover:bg-emerald-800 transition-all shadow-sm border-2 border-emerald-950">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-10 py-16 text-center text-emerald-900/30 font-bold italic">Tidak ada pelanggan ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <!-- Modal Delete User -->
    <div id="modal-delete-user" class="fixed inset-0 z-[60] flex items-center justify-center p-6 bg-rose-950/40 backdrop-blur-md hidden">
        <div class="bg-white w-full max-w-[440px] rounded-[48px] shadow-2xl p-10 text-center animate-in fade-in zoom-in duration-500 border-2 border-rose-600">
            <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6 border-2 border-rose-200 shadow-xl shadow-rose-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
            </div>
            <h3 class="text-[24px] font-black text-emerald-950 tracking-tighter mb-2 leading-none">Hapus Akun?</h3>
            <p class="text-[14px] text-emerald-700 leading-relaxed font-bold mb-8 px-4">Pengguna <span id="delete-user-name" class="font-black text-emerald-950"></span> akan dihapus permanen.</p>
            <form action="{{ url('/admin/user/delete') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="delete-user-id">
                <input type="hidden" name="role" id="delete-user-role">
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
    function openDeleteModal(id, name, role) {
        document.getElementById('delete-user-id').value = id;
        document.getElementById('delete-user-role').value = role;
        document.getElementById('delete-user-name').innerText = name;
        document.getElementById('modal-delete-user').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeDeleteModal() {
        document.getElementById('modal-delete-user').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
