@extends('layouts.admin_premium')

@section('title', 'Laporan Penjualan')

@section('header')
    <div class="hidden md:flex items-center gap-3 text-[12px] font-black text-emerald-500 uppercase tracking-widest mb-2">
        <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors">Admin</a>
        <span class="text-white/20">/</span>
        <span class="text-white">Laporan Penjualan</span>
    </div>
@endsection

@section('content')
    <!-- Enhanced Filter & Export Card -->
    <div class="bg-white p-6 md:p-10 rounded-[32px] md:rounded-[40px] border-2 border-emerald-950 shadow-sm mb-12 backdrop-blur-md">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 md:gap-10">
            <div class="w-full lg:w-auto">
                <p class="text-[11px] md:text-[12px] font-black text-emerald-950 uppercase tracking-[0.2em] mb-4 text-center lg:text-left">Pilih Periode:</p>
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 md:gap-3">
                    @php
                        $filters = [
                            'all' => 'Semua',
                            'today' => 'Hari Ini',
                            'week' => 'Minggu Ini',
                            'month' => 'Bulan Ini',
                            'year' => 'Tahun Ini'
                        ];
                    @endphp
                    @foreach($filters as $key => $label)
                        <a href="{{ url('/admin/laporan-penjualan?filter='.$key) }}" 
                           class="px-4 md:px-6 py-2.5 md:py-3.5 rounded-xl md:rounded-2xl text-[11px] md:text-[13px] font-black transition-all border-2 {{ $filter == $key ? 'bg-emerald-950 text-white border-emerald-950 shadow-xl' : 'bg-white text-emerald-950 border-emerald-100 hover:border-emerald-950' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="w-full lg:w-auto flex flex-col items-center gap-4">
                <p class="text-[11px] md:text-[12px] font-black text-emerald-950 uppercase tracking-[0.2em] mb-2 md:mb-4 text-center">Atur Rentang Tanggal :</p>
                <form action="{{ url('/admin/laporan-penjualan') }}" method="GET" class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full">
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full sm:w-auto bg-emerald-50 border-2 border-emerald-950 rounded-xl px-5 py-3 text-[14px] font-bold text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all">
                        <span class="text-emerald-950 font-black text-[12px] uppercase">s/d</span>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full sm:w-auto bg-emerald-50 border-2 border-emerald-950 rounded-xl px-5 py-3 text-[14px] font-bold text-emerald-950 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-emerald-950 text-white px-8 py-3.5 rounded-xl font-black text-[13px] hover:bg-emerald-900 transition-all shadow-lg active:scale-95 border-2 border-emerald-950">Terapkan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-8 mb-12">
        <div class="bg-emerald-950 p-6 md:p-10 rounded-[32px] md:rounded-[40px] shadow-2xl relative overflow-hidden group flex flex-col items-center text-center">
            <div class="relative z-10">
                <p class="text-[9px] md:text-[11px] font-black text-white/60 uppercase tracking-[0.2em] mb-3">Total Pendapatan</p>
                <h3 class="text-xl md:text-[40px] font-black text-white tracking-tighter leading-none">{{ number_format($totalRevenue, 0, ',', '.') }} <span class="text-[20px] md:text-[20px] text-white/60 ml-1">Rp</span></h3>
            </div>
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700 -z-0"></div>
        </div>
        <div class="bg-emerald-950 p-6 md:p-10 rounded-[32px] md:rounded-[40px] shadow-2xl relative overflow-hidden group flex flex-col items-center text-center">
            <div class="relative z-10">
                <p class="text-[9px] md:text-[11px] font-black text-white/60 uppercase tracking-[0.2em] mb-3">Jumlah Transaksi</p>
                <h3 class="text-xl md:text-[40px] font-black text-white tracking-tighter leading-none">{{ $totalOrders }} <span class="text-[10px] md:text-[20px] text-white/60 ml-1">Order</span></h3>
            </div>
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700 -z-0"></div>
        </div>
        <div class="bg-emerald-950 p-6 md:p-10 rounded-[32px] md:rounded-[40px] shadow-2xl relative overflow-hidden group flex flex-col items-center text-center">
            <div class="relative z-10">
                <p class="text-[9px] md:text-[11px] font-black text-white/60 uppercase tracking-[0.2em] mb-3">Buku Terjual</p>
                <h3 class="text-xl md:text-[40px] font-black text-white tracking-tighter leading-none">{{ $totalBooks }} <span class="text-[10px] md:text-[20px] text-white/60 ml-1">Buku</span></h3>
            </div>
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700 -z-0"></div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8 px-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-950 border-2 border-emerald-100 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
            </div>
            <div>
                <p class="text-[11px] font-black text-emerald-950 uppercase tracking-widest leading-none mb-1.5">Periode Laporan:</p>
                <p class="text-[18px] font-black text-emerald-950 tracking-tight">
                    @if($startDate && $endDate)
                        {{ date('d M Y', strtotime($startDate)) }} — {{ date('d M Y', strtotime($endDate)) }}
                    @else
                        {{ $filters[$filter] ?? 'Semua Data Penjualan' }}
                    @endif
                </p>
            </div>
        </div>

        <a href="{{ url('/admin/laporan-penjualan/export?filter='.$filter.'&start_date='.$startDate.'&end_date='.$endDate) }}" 
           class="bg-emerald-950 text-white px-10 py-5 rounded-[24px] text-[15px] font-black hover:bg-emerald-900 transition-all shadow-2xl shadow-emerald-950/20 flex items-center gap-4 active:scale-95 group">
            <div class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
            </div>
            Eksport Excel/CSV (.csv)
        </a>
    </div>

    <div class="bg-white rounded-[48px] border-2 border-emerald-950 shadow-sm overflow-hidden mb-12">
        <div class="p-10 border-b-2 border-emerald-950 bg-emerald-50/20 flex items-center justify-between">
            <div>
                <h3 class="text-[20px] font-black text-emerald-950 tracking-tight">Detail Penjualan</h3>
                </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-100 bg-emerald-950">
                        <th class="px-10 py-6">ID Transaksi</th>
                        <th class="px-10 py-6">Waktu & Tanggal</th>
                        <th class="px-10 py-6">Pelanggan</th>
                        <th class="px-10 py-6">Total Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-emerald-950 divide-y divide-emerald-50/50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                        <td class="px-10 py-7 font-black text-emerald-950">#{{ $order->order_number }}</td>
                        <td class="px-10 py-7">
                            <p class="font-bold">{{ $order->created_at->format('d F Y') }}</p>
                            <p class="text-[11px] font-black text-emerald-950">{{ $order->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-10 py-7 font-black text-emerald-700 italic">{{ $order->user->name ?? 'Guest User' }}</td>
                        <td class="px-10 py-7">
                            <span class="px-4 py-2 bg-emerald-50 text-emerald-950 font-black rounded-xl border border-emerald-100 shadow-sm">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-10 py-24 text-center">
                            <p class="text-emerald-950 font-black text-xl italic">Data laporan tidak ditemukan untuk periode ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
