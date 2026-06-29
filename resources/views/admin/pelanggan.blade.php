@extends('layouts.admin_premium')

@section('title', 'Manajemen Pelanggan')

@section('header')
    <div class="flex items-center gap-3 text-[12px] font-bold text-emerald-400 uppercase tracking-widest mb-2">
        <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors text-emerald-400/60">Admin</a>
        <span>/</span>
        <span class="text-white">Data Pelanggan</span>
    </div>
    <h2 class="text-[32px] font-black text-white tracking-tighter leading-none">Database Keanggotaan CIVAD</h2>
@endsection

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <div class="bg-white p-10 rounded-[48px] border border-emerald-100 shadow-sm flex items-center justify-between group hover:border-emerald-950/20 transition-all relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <p class="text-[13px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-3 opacity-60">Total Akun Terdaftar</p>
                <h3 class="text-[48px] font-black text-emerald-950 tracking-tighter leading-none">{{ count($customers) }}</h3>
                <p class="text-[12px] font-bold text-emerald-900/40 mt-4 italic">Anggota aktif di platform CIVAD</p>
            </div>
            <div class="w-20 h-20 bg-emerald-950 text-white rounded-[32px] flex items-center justify-center shadow-2xl relative z-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
            </div>
        </div>
        
        <div class="bg-white p-10 rounded-[48px] border border-emerald-100 shadow-sm flex items-center justify-between group hover:border-emerald-950/20 transition-all relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <p class="text-[13px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-3 opacity-60">Akumulasi Transaksi</p>
                <h3 class="text-[48px] font-black text-emerald-950 tracking-tighter leading-none">{{ $totalOrders }}</h3>
                <p class="text-[12px] font-bold text-emerald-900/40 mt-4 italic">Total pesanan dari seluruh pelanggan</p>
            </div>
            <div class="w-20 h-20 bg-emerald-950 text-white rounded-[32px] flex items-center justify-center shadow-2xl relative z-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-[48px] border border-emerald-100 shadow-sm overflow-hidden mb-12">
        <div class="p-10 border-b border-emerald-50 flex flex-col md:flex-row items-center justify-between gap-6 bg-emerald-50/20">
            <div>
                <h3 class="text-[20px] font-black text-emerald-950 tracking-tight">Daftar Akun Pelanggan</h3>
                <p class="text-[12px] font-bold text-emerald-600 uppercase tracking-widest mt-1">Manajemen poin & profil anggota</p>
            </div>
            <div class="relative group w-full md:w-80">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 absolute left-5 top-1/2 -translate-y-1/2 text-emerald-900/30 group-focus-within:text-emerald-950 transition-colors"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                <input type="text" placeholder="Cari nama atau username..." class="w-full bg-white border border-emerald-100 rounded-2xl py-3.5 pl-14 pr-6 text-[14px] font-bold text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950 transition-all placeholder:text-emerald-900/30">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-900 bg-emerald-50/50">
                        <th class="px-10 py-6">Profil Pelanggan</th>
                        <th class="px-10 py-6">ID Username</th>
                        <th class="px-10 py-6">Kontak & Daerah</th>
                        <th class="px-10 py-6">Alamat Domisili</th>
                        <th class="px-10 py-6 text-center">Loyalty Points</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-emerald-950 divide-y divide-emerald-50/50">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                        <td class="px-10 py-7">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-emerald-950 text-white rounded-[22px] flex items-center justify-center font-black text-[18px] shadow-xl group-hover:rotate-6 transition-transform duration-500">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-black text-emerald-950 text-[16px] tracking-tight">{{ $customer->name }}</p>
                                    <p class="text-[11px] font-black text-emerald-600 uppercase tracking-widest mt-1 opacity-60 italic">Loyal Member</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-7 font-black text-emerald-600 tracking-tight">@ {{ $customer->username }}</td>
                        <td class="px-10 py-7">
                            <p class="font-black text-emerald-950">{{ $customer->phone ?? '0812-3456-7890' }}</p>
                            <span class="inline-block mt-2 px-4 py-1.5 bg-emerald-50 text-emerald-900 text-[11px] font-black rounded-xl border border-emerald-100 uppercase tracking-tighter">{{ $customer->daerah ?? 'DKI Jakarta' }}</span>
                        </td>
                        <td class="px-10 py-7">
                            <p class="text-[13px] font-bold text-emerald-900/60 leading-relaxed max-w-[280px] line-clamp-2 italic">
                                {{ $customer->address ?? 'Alamat pengiriman belum diatur oleh pelanggan.' }}
                            </p>
                        </td>
                        <td class="px-10 py-7">
                            <div class="flex flex-col items-center gap-4">
                                <span class="px-5 py-2.5 bg-emerald-950 text-white text-[14px] font-black rounded-2xl shadow-xl min-w-[120px] text-center tracking-tighter">
                                    {{ number_format($customer->points ?? 0, 0, ',', '.') }} <span class="text-[10px] text-emerald-400 ml-1">PTS</span>
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-24 text-center">
                            <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 text-emerald-200"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493" /></svg>
                            </div>
                            <p class="text-emerald-950 font-black text-xl mb-1">Database Pelanggan Kosong</p>
                            <p class="text-emerald-600 font-bold">Belum ada pelanggan yang bergabung.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="bg-emerald-950 rounded-[48px] p-12 text-white relative overflow-hidden shadow-2xl">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500 rounded-full blur-[120px] opacity-20"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-emerald-400 rounded-full blur-[120px] opacity-10"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
            <div class="w-20 h-20 bg-white/10 backdrop-blur-2xl rounded-[32px] flex items-center justify-center border border-white/20 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0Zm-9-3.75h.008v.008H12V8.25Z" /></svg>
            </div>
            <div>
                <h4 class="text-[24px] font-black tracking-tighter mb-3 leading-none">Pedoman Manajemen Ekosistem Pelanggan</h4>
                <p class="text-emerald-100/60 text-[15px] font-medium leading-relaxed max-w-4xl italic">
                    Data di atas dikelola sepenuhnya oleh sistem CIVAD berdasarkan pendaftaran mandiri. Anda memiliki kontrol penuh untuk memantau perilaku belanja, validasi alamat pengiriman, dan manajemen skema loyalitas. Pastikan setiap penyesuaian poin dilakukan berdasarkan kebijakan promosi yang berlaku untuk menjaga integritas ekosistem bisnis.
                </p>
            </div>
        </div>
    </div>
@endsection
