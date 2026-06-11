@extends('layouts.admin_premium')

@section('title', 'Tambah Admin')

@section('header')
    <div class="flex items-center gap-3 text-[12px] font-bold text-emerald-400 uppercase tracking-widest mb-2">
        <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors text-emerald-400/60">Admin</a>
        <span>/</span>
        <span class="text-white">Hak Akses</span>
    </div>
    <h2 class="text-[32px] font-black text-white tracking-tighter leading-none">Registrasi Administrator Baru</h2>
@endsection

@section('content')
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-12">
        <div class="xl:col-span-2">
            <div class="bg-white rounded-[48px] border border-emerald-100 shadow-sm overflow-hidden backdrop-blur-md">
                <div class="p-12 border-b border-emerald-50 bg-emerald-50/20">
                    <h3 class="text-[24px] font-black text-emerald-950 tracking-tight">Formulir Kredensial</h3>
                    <p class="text-[12px] font-black text-emerald-600 uppercase tracking-[0.2em] mt-2">Daftarkan akun otoritas sistem baru</p>
                </div>

                <div class="p-12">
                    @if($errors->any())
                    <div class="mb-10 p-6 bg-rose-50 border border-rose-100 text-rose-600 text-[14px] rounded-[32px] font-bold animate-in fade-in slide-in-from-top-2">
                        <div class="flex items-center gap-3 mb-3 text-rose-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                            <span class="uppercase tracking-widest text-[11px] font-black">Terjadi Kesalahan Input</span>
                        </div>
                        <ul class="list-disc pl-8 space-y-1 opacity-80">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.tambah.submit') }}" method="POST" class="space-y-10">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-3">
                                <label class="text-[13px] font-black text-emerald-900 uppercase tracking-widest ml-1 opacity-60">Nama Lengkap Admin</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-emerald-900/30 group-focus-within:text-emerald-950 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                    </div>
                                    <input type="text" name="name" placeholder="Contoh: Raihan Ahmad" required class="w-full pl-15 pr-8 py-5 bg-emerald-50/50 border border-emerald-100 rounded-[28px] text-[15px] font-black text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950 transition-all placeholder:text-emerald-900/20">
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[13px] font-black text-emerald-900 uppercase tracking-widest ml-1 opacity-60">ID Username Login</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-emerald-900/30 group-focus-within:text-emerald-950 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                    </div>
                                    <input type="text" name="username" placeholder="raihan_admin" required class="w-full pl-15 pr-8 py-5 bg-emerald-50/50 border border-emerald-100 rounded-[28px] text-[15px] font-black text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950 transition-all placeholder:text-emerald-900/20">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-3">
                                <label class="text-[13px] font-black text-emerald-900 uppercase tracking-widest ml-1 opacity-60">Kata Sandi Rahasia</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-emerald-900/30 group-focus-within:text-emerald-950 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                                    </div>
                                    <input type="password" name="password" placeholder="••••••••••••" required class="w-full pl-15 pr-8 py-5 bg-emerald-50/50 border border-emerald-100 rounded-[28px] text-[15px] font-black text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950 transition-all placeholder:text-emerald-900/20">
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[13px] font-black text-emerald-900 uppercase tracking-widest ml-1 opacity-60">Alamat Email Admin</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none text-emerald-900/30 group-focus-within:text-emerald-950 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                                    </div>
                                    <input type="email" name="email" placeholder="raihan@civad.com" required class="w-full pl-15 pr-8 py-5 bg-emerald-50/50 border border-emerald-100 rounded-[28px] text-[15px] font-black text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-950 transition-all placeholder:text-emerald-900/20">
                                </div>
                            </div>
                        </div>
                            
                        <div class="flex items-center gap-6 pt-10">
                            <button type="submit" class="flex-2 bg-emerald-950 text-white py-5 px-10 rounded-[28px] text-[16px] font-black hover:bg-emerald-900 transition-all shadow-2xl shadow-emerald-950/20 active:scale-95 flex items-center justify-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                Aktifkan Akses Admin
                            </button>
                            <a href="{{ url('/admin/dashboard') }}" class="flex-1 py-5 rounded-[28px] border-2 border-emerald-100 text-[15px] font-black text-emerald-950 hover:bg-emerald-50 transition-all text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security Guide -->
        <div class="space-y-8">
            <div class="bg-emerald-950 rounded-[48px] p-10 text-white relative overflow-hidden shadow-2xl">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 bg-white/10 backdrop-blur-xl rounded-2xl flex items-center justify-center border border-white/20 mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7 text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008H12v-.008z" /></svg>
                    </div>
                    <h4 class="text-[20px] font-black tracking-tighter mb-4">Protokol Keamanan Otoritas</h4>
                    <p class="text-emerald-100/60 text-[14px] font-medium leading-relaxed italic">
                        Akun administrator memiliki hak akses penuh terhadap katalog literasi, data pelanggan, dan validasi transaksi keuangan. 
                        Pastikan pendaftaran hanya dilakukan untuk staf internal yang telah menandatangani pakta integritas data.
                    </p>
                    <div class="mt-10 space-y-4">
                        <div class="flex items-center gap-4 text-[13px] font-bold text-emerald-400">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full shadow-[0_0_10px_rgba(52,211,153,1)]"></div>
                            Akses Langsung Aktif
                        </div>
                        <div class="flex items-center gap-4 text-[13px] font-bold text-emerald-400">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full shadow-[0_0_10px_rgba(52,211,153,1)]"></div>
                            Enkripsi Kata Sandi 256-bit
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[40px] p-10 border border-emerald-100 shadow-sm">
                <h4 class="text-[16px] font-black text-emerald-950 mb-4 tracking-tight">Butuh Bantuan?</h4>
                <p class="text-[13px] font-bold text-emerald-900/50 leading-relaxed mb-6">Jika terjadi kendala pada saat registrasi atau admin lupa kata sandi, hubungi IT Support Central.</p>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-900">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25z" /></svg>
                    </div>
                    <span class="text-[14px] font-black text-emerald-950">Internal Hotline 202</span>
                </div>
            </div>
        </div>
    </div>
@endsection
