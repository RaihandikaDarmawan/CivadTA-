@extends('layouts.admin_premium')

@section('title', 'Dashboard')

@section('header')
    <div class="hidden md:flex items-center gap-3 text-[12px] font-black text-emerald-500 uppercase tracking-widest mb-2">
        <a href="{{ url('/admin/dashboard') }}" class="hover:text-white transition-colors">Admin</a>
        <span class="text-white/20">/</span>
        <span class="text-white">Dashboard</span>
    </div>
    @endsection

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Card 1: Total Buku -->
        <div class="stat-card bg-emerald-950 p-6 rounded-[24px] border border-emerald-800/30 shadow-sm flex flex-col items-start gap-4 hover:scale-[1.02] hover:shadow-md transition-all duration-300 group relative overflow-hidden">
            <div class="w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
            </div>
            <div class="space-y-0.5">
                <h3 class="text-2xl md:text-[28px] font-extrabold text-white leading-none mb-1.5">{{ $totalJenisBuku }}</h3>
                <p class="text-[14px] font-bold text-white/70 leading-tight">Total Buku</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>

        <!-- Card 2: Total Buku Terjual -->
        <div class="stat-card bg-emerald-950 p-6 rounded-[24px] border border-emerald-800/30 shadow-sm flex flex-col items-start gap-4 hover:scale-[1.02] hover:shadow-md transition-all duration-300 group relative overflow-hidden">
            <div class="w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
            </div>
            <div class="space-y-0.5">
                <h3 class="text-2xl md:text-[28px] font-extrabold text-white leading-none mb-1.5">{{ $totalBukuTerjual }}</h3>
                <p class="text-[14px] font-bold text-white/70 leading-tight">Total Buku Terjual</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>

        <!-- Card 3: Total Pendapatan -->
        <div class="stat-card bg-emerald-950 p-6 rounded-[24px] border border-emerald-800/30 shadow-sm flex flex-col items-start gap-4 hover:scale-[1.02] hover:shadow-md transition-all duration-300 group relative overflow-hidden">
            <div class="w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center">
                <span class="text-lg font-black text-white">$</span>
            </div>
            <div class="space-y-0.5">
                <h3 class="text-2xl md:text-[28px] font-extrabold text-white leading-none mb-1.5">{{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <p class="text-[14px] font-bold text-white/70 leading-tight">Total Pendapatan (Rp)</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>

        <!-- Card 4: Pembayaran Menunggu -->
        <div class="stat-card bg-emerald-950 p-6 rounded-[24px] border border-emerald-800/30 shadow-sm flex flex-col items-start gap-4 hover:scale-[1.02] hover:shadow-md transition-all duration-300 group relative overflow-hidden">
            <div class="w-12 h-12 bg-white/10 text-white rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
            </div>
            <div class="space-y-0.5">
                <h3 class="text-2xl md:text-[28px] font-extrabold text-white leading-none mb-1.5">{{ $menungguVerifikasi }}</h3>
                <p class="text-[14px] font-bold text-white/70 leading-tight">Pembayaran Menunggu</p>
            </div>
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
        </div>
    </div>

    <!-- Charts Section -->
    @php
        // Data for Book Sales Bar Chart
        $bookSalesData = \App\Models\OrderItem::join('books', 'order_items.book_id', '=', 'books.id')
            ->selectRaw('books.category, SUM(order_items.quantity) as total_sold')
            ->groupBy('books.category')
            ->orderByDesc('total_sold')
            ->pluck('total_sold', 'books.category')
            ->toArray();
            
        $bookLabels = json_encode(array_keys($bookSalesData));
        $bookSalesCounts = json_encode(array_values($bookSalesData));

        // Data for Revenue Chart (Last 6 Months)
        $revenueData = \App\Models\Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->whereIn('status', ['Selesai', 'Dikirim', 'Sedang Dikirim', 'Terverifikasi', 'Verifikasi'])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        
        $revenueLabels = [];
        $revenueValues = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthNum = $date->month;
            $revenueLabels[] = $months[$monthNum];
            $revenueValues[] = $revenueData[$monthNum] ?? 0;
        }
        
        // Data for Best Sellers by Category
        $bestSellersQuery = \App\Models\OrderItem::join('books', 'order_items.book_id', '=', 'books.id')
            ->selectRaw('books.title, books.category, SUM(order_items.quantity) as total_sold')
            ->groupBy('books.id', 'books.title', 'books.category')
            ->orderByDesc('total_sold')
            ->get();

        $bestSellersData = [
            'all' => [],
            'SD/MI' => [],
            'SMP/MTs' => [],
            'SMA/SMK/MA' => []
        ];

        foreach ($bestSellersQuery as $item) {
            $bookTitle = $item->title;
            if (strlen($bookTitle) > 30) {
                $bookTitle = substr($bookTitle, 0, 27) . '...';
            }
            $bookInfo = [
                'title' => $bookTitle,
                'sold' => (int) $item->total_sold
            ];
            
            $bestSellersData['all'][] = $bookInfo;
            if (isset($bestSellersData[$item->category])) {
                $bestSellersData[$item->category][] = $bookInfo;
            }
        }

        // Limit each list to top 7 items
        foreach ($bestSellersData as $cat => $list) {
            $bestSellersData[$cat] = array_slice($list, 0, 7);
        }

        $bestSellersJson = json_encode($bestSellersData);
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <!-- Revenue Line Chart -->
        <div class="lg:col-span-2 bg-white p-8 rounded-[40px] border border-emerald-100 shadow-sm flex flex-col">
            <div class="mb-6">
                <h3 class="text-[20px] font-black text-emerald-950 tracking-tight">Tren Pendapatan</h3>
                <p class="text-[12px] font-bold text-emerald-900 uppercase tracking-widest mt-1">6 Bulan Terakhir</p>
            </div>
            <div class="flex-1 relative min-h-[300px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Book Sales Bar Chart -->
        <div class="bg-white p-8 rounded-[40px] border border-emerald-100 shadow-sm flex flex-col">
            <div class="mb-6">
                <h3 class="text-[20px] font-black text-emerald-950 tracking-tight">Penjualan Buku</h3>
                <p class="text-[12px] font-bold text-emerald-900 uppercase tracking-widest mt-1">Berdasarkan Tingkat Pendidikan</p>
            </div>
            <div class="flex-1 relative min-h-[300px] mt-4">
                @if(empty($bookSalesData))
                    <div class="text-center mt-12">
                        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-emerald-200"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" /></svg>
                        </div>
                        <p class="text-[12px] font-bold text-emerald-600 uppercase tracking-widest">Belum ada data</p>
                    </div>
                @else
                    <canvas id="bookChart"></canvas>
                @endif
            </div>
        </div>
    </div>

    <!-- Best Sellers Section -->
    <div class="bg-white p-8 rounded-[40px] border border-emerald-100 shadow-sm flex flex-col mb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-[20px] font-black text-emerald-950 tracking-tight">Penjualan Buku Terlaris</h3>
                <p class="text-[12px] font-bold text-emerald-900 uppercase tracking-widest mt-1">Grafik Berdasarkan Kategori Pendidikan</p>
            </div>
            
            <!-- Category Tabs Selector -->
            <div class="flex flex-wrap gap-2">
                <button onclick="updateBestSellerChart('all')" id="btn-best-all" class="best-seller-tab px-4 py-2 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-300">Semua</button>
                <button onclick="updateBestSellerChart('SD/MI')" id="btn-best-sd" class="best-seller-tab px-4 py-2 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-300">SD/MI</button>
                <button onclick="updateBestSellerChart('SMP/MTs')" id="btn-best-smp" class="best-seller-tab px-4 py-2 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-300">SMP/MTs</button>
                <button onclick="updateBestSellerChart('SMA/SMK/MA')" id="btn-best-sma" class="best-seller-tab px-4 py-2 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-300">SMA/SMK/MA</button>
            </div>
        </div>
        
        <div class="flex-1 relative min-h-[350px]">
            <canvas id="bestSellerChart"></canvas>
            
            <!-- Empty State -->
            <div id="bestSellerEmptyState" class="hidden absolute inset-0 flex flex-col items-center justify-center text-center">
                <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-emerald-200"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                </div>
                <p class="text-[12px] font-bold text-emerald-600 uppercase tracking-widest">Belum ada data penjualan untuk kategori ini</p>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-[48px] border border-emerald-100 shadow-sm overflow-hidden">
        <div class="p-10 border-b border-emerald-50 flex items-center justify-between bg-emerald-50/20">
            <div>
                <h3 class="text-[20px] font-black text-emerald-950 tracking-tight">Pesanan Pelanggan Terbaru</h3>
            </div>
            <a href="{{ url('/admin/manajemen-pesanan') }}" class="px-6 py-3 bg-white border border-emerald-100 rounded-2xl text-[13px] font-black text-emerald-900 hover:bg-emerald-950 hover:text-white transition-all shadow-sm">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-[11px] font-black uppercase tracking-[0.2em] text-emerald-900 bg-emerald-50/50">
                        <th class="px-10 py-6">ID Pesanan</th>
                        <th class="px-10 py-6">Informasi Pelanggan</th>
                        <th class="px-10 py-6">Detail Produk</th>
                        <th class="px-10 py-6">Total Bayar</th>
                        <th class="px-10 py-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-[14px] text-emerald-950 divide-y divide-emerald-50/50">
                    @php
                        $recentOrders = \App\Models\Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
                    @endphp
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                        <td class="px-10 py-7 font-black text-emerald-950 tracking-tighter">#{{ $order->order_number }}</td>
                        <td class="px-10 py-7">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-emerald-950 text-white rounded-xl flex items-center justify-center font-black text-[14px] shadow-lg">
                                    {{ substr($order->user->name ?? 'U', 0, 1) }}
                                </div>
                                <span class="font-black text-emerald-950">{{ $order->user->name ?? 'Unknown User' }}</span>
                            </div>
                        </td>
                        <td class="px-10 py-7">
                            <span class="px-4 py-1.5 bg-emerald-50 text-emerald-900 text-[12px] font-black rounded-full border border-emerald-100">
                                {{ $order->items->count() }} Jenis Buku
                            </span>
                        </td>
                        <td class="px-10 py-7 font-black text-emerald-950 text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-10 py-7 text-center">
                            @php
                                $statusClasses = [
                                    'Menunggu Verifikasi' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'Terverifikasi' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'Verifikasi' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'Dikirim' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'Sedang Dikirim' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'Selesai' => 'bg-emerald-950 text-white border-emerald-950',
                                    'Dibatalkan' => 'bg-rose-50 text-rose-600 border-rose-100',
                                ];
                                $currentClass = $statusClasses[$order->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                            @endphp
                            <span class="inline-block px-5 py-2 rounded-full text-[11px] font-black uppercase tracking-widest border {{ $currentClass }} shadow-sm">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-24 text-center">
                            <div class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-10 h-10 text-emerald-200"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                            </div>
                            <p class="text-emerald-950 font-black text-xl mb-1">Belum Ada Transaksi</p>
                            <p class="text-emerald-900 font-bold">Data aktivitas pesanan akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Shared Chart Configuration
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#0f766e'; // emerald-700
        
        // 1. Revenue Line Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: {!! $revenueLabelsJson ?? '[]' !!},
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: {!! $revenueValuesJson ?? '[]' !!},
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 4,
                        pointBackgroundColor: '#052e16', // emerald-950
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#052e16',
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 14, weight: 'bold' },
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { weight: 'bold' } }
                        },
                        y: {
                            border: { display: false },
                            grid: { color: 'rgba(16, 185, 129, 0.1)' },
                            ticks: {
                                font: { weight: 'bold' },
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }

        // 2. Book Sales Bar Chart
        const bookCtx = document.getElementById('bookChart');
        if (bookCtx) {
            new Chart(bookCtx, {
                type: 'bar',
                data: {
                    labels: {!! $bookLabels ?? '[]' !!},
                    datasets: [{
                        label: 'Buku Terjual',
                        data: {!! $bookSalesCounts ?? '[]' !!},
                        backgroundColor: '#10b981', // emerald-500
                        borderRadius: 8,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#052e16',
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 14, weight: 'bold' },
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.raw + ' Buku';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { 
                                font: { weight: 'bold', size: 10 }
                            }
                        },
                        y: {
                            border: { display: false },
                            grid: { color: 'rgba(16, 185, 129, 0.1)' },
                            ticks: {
                                font: { weight: 'bold' },
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // 3. Best Seller Horizontal Bar Chart
        const bestSellerCtx = document.getElementById('bestSellerChart');
        let bestSellerChartInstance = null;
        const bestSellerData = {!! $bestSellersJson ?? '{}' !!};
        
        window.updateBestSellerChart = function(category) {
            // Update active button styling
            document.querySelectorAll('.best-seller-tab').forEach(btn => {
                btn.classList.remove('bg-teal-950', 'text-white', 'shadow-md');
                btn.classList.add('bg-teal-50', 'text-teal-950', 'border', 'border-teal-100');
            });
            
            let btnId = 'btn-best-all';
            if (category === 'SD/MI') btnId = 'btn-best-sd';
            else if (category === 'SMP/MTs') btnId = 'btn-best-smp';
            else if (category === 'SMA/SMK/MA') btnId = 'btn-best-sma';
            
            const activeBtn = document.getElementById(btnId);
            if (activeBtn) {
                activeBtn.classList.remove('bg-teal-50', 'text-teal-950', 'border', 'border-teal-100');
                activeBtn.classList.add('bg-teal-950', 'text-white', 'shadow-md');
            }
            
            const categoryData = bestSellerData[category] || [];
            
            const emptyState = document.getElementById('bestSellerEmptyState');
            const canvas = document.getElementById('bestSellerChart');
            
            if (categoryData.length === 0) {
                if (emptyState) emptyState.classList.remove('hidden');
                if (canvas) canvas.classList.add('opacity-0');
                return;
            } else {
                if (emptyState) emptyState.classList.add('hidden');
                if (canvas) canvas.classList.remove('opacity-0');
            }
            
            const labels = categoryData.map(item => item.title);
            const counts = categoryData.map(item => item.sold);
            
            if (bestSellerChartInstance) {
                bestSellerChartInstance.data.labels = labels;
                bestSellerChartInstance.data.datasets[0].data = counts;
                bestSellerChartInstance.update();
            } else if (bestSellerCtx) {
                bestSellerChartInstance = new Chart(bestSellerCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Terjual',
                            data: counts,
                            backgroundColor: '#0f766e', // tosca / dark teal
                            borderRadius: 8,
                            barPercentage: 0.5
                        }]
                    },
                    options: {
                        indexAxis: 'y', // horizontal bar chart
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#052e16',
                                titleFont: { size: 12, weight: 'bold' },
                                bodyFont: { size: 14, weight: 'bold' },
                                padding: 12,
                                cornerRadius: 12,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.raw + ' Buku Terjual';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                border: { display: false },
                                grid: { color: 'rgba(15, 118, 110, 0.1)' },
                                ticks: { 
                                    font: { weight: 'bold' },
                                    stepSize: 1
                                }
                            },
                            y: {
                                grid: { display: false, drawBorder: false },
                                ticks: { 
                                    font: { weight: 'bold', size: 11 },
                                    color: '#052e16'
                                }
                            }
                        }
                    }
                });
            }
        };
        
        // Initialize best seller chart
        updateBestSellerChart('all');
    });
</script>
@endsection
