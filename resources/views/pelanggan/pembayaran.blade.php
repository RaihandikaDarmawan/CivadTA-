@extends('layouts.customer_premium')

@section('title', 'Konfirmasi Pembayaran')

@section('scripts')
    <script>
        function enableButton() {
            const btn = document.getElementById('btnSubmit');
            btn.classList.remove('bg-slate-200', 'text-emerald-600', 'cursor-not-allowed');
            btn.classList.add('bg-emerald-600', 'text-white', 'hover:bg-emerald-700', 'shadow-xl', 'shadow-emerald-100');
            btn.removeAttribute('disabled');
        }
        window.onload = enableButton;
    </script>

@endsection

@section('content')


    <main class="max-w-[1280px] mx-auto px-6 py-12">
        <header class="mb-12">
            <h1 class="text-[36px] font-black text-emerald-950 tracking-tighter mb-3 leading-none">Pembayaran</h1>
            </header>

        @if(session('error'))
        <div class="mb-8 p-6 bg-red-50 border border-red-100 rounded-[24px] flex items-center gap-4 text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <p class="font-bold text-[14px]">{{ session('error') }}</p>
        </div>
        @endif

        <form action="{{ url('/pelanggan/konfirmasi-pembayaran') }}" method="POST">
            @csrf
            <!-- Hidden Checkout Data -->
            <input type="hidden" name="recipient_name" value="{{ $request->recipient_name }}">
            <input type="hidden" name="phone_number" value="{{ $request->phone_number }}">
            <input type="hidden" name="address" value="{{ $request->address }}">
            <input type="hidden" name="latitude" value="{{ $request->latitude }}">
            <input type="hidden" name="longitude" value="{{ $request->longitude }}">
            <input type="hidden" name="distance_km" value="{{ $request->distance_km ?? 3 }}">
            <input type="hidden" name="shipping_service" value="{{ $request->shipping_service ?? 'GoSend Same Day' }}">
            <input type="hidden" name="points_to_redeem" value="{{ $request->points_to_redeem ?? 0 }}">

            <div class="flex flex-col xl:flex-row gap-12">
                <div class="flex-grow space-y-8">
                    <div class="bg-white p-10 rounded-[48px] border border-emerald-100 shadow-sm overflow-hidden relative">
                        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-50 rounded-full blur-[100px] opacity-40"></div>
                        
                        <div class="relative z-10">
                            <h3 class="text-[20px] font-black text-emerald-950 mb-8 tracking-tight">Metode Pembayaran Otomatis</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                                <div class="payment-card p-8 rounded-[32px] border-2 border-emerald-600 bg-emerald-50/30 relative overflow-hidden group">
                                    <div class="absolute top-4 right-6 text-emerald-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <p class="text-[11px] font-black text-emerald-900 uppercase tracking-[0.2em] mb-4">Midtrans Payment</p>
                                    <p class="text-[20px] font-black text-emerald-950 tracking-tight leading-none mb-1">Multi Payment Gateway</p>
                                    <p class="text-[13px] font-bold text-emerald-900 mt-2">Dukung VA, GoPay, OVO, & Kartu Kredit</p>
                                </div>
                            </div>

                            <div class="space-y-6 mb-12">
                                <h4 class="text-[14px] font-black text-emerald-950 uppercase tracking-widest flex items-center gap-3">
                                    <span class="w-1.5 h-6 bg-emerald-600 rounded-full"></span>
                                    Cara Pembayaran
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex items-start gap-4 p-5 rounded-3xl bg-emerald-50 border border-emerald-100">
                                        <div class="step-number bg-emerald-600 w-8 h-8 rounded-xl text-white flex items-center justify-center font-black text-[14px] shrink-0 shadow-lg shadow-emerald-100">1</div>
                                        <p class="text-[13px] font-bold text-emerald-800 leading-relaxed">Klik tombol bayar di sebelah kanan.</p>
                                    </div>
                                    <div class="flex items-start gap-4 p-5 rounded-3xl bg-emerald-50 border border-emerald-100">
                                        <div class="step-number bg-emerald-600 w-8 h-8 rounded-xl text-white flex items-center justify-center font-black text-[14px] shrink-0 shadow-lg shadow-emerald-100">2</div>
                                        <p class="text-[13px] font-bold text-emerald-800 leading-relaxed">Pilih metode pembayaran favorit Anda.</p>
                                    </div>
                                    <div class="flex items-start gap-4 p-5 rounded-3xl bg-emerald-50 border border-emerald-100">
                                        <div class="step-number bg-emerald-600 w-8 h-8 rounded-xl text-white flex items-center justify-center font-black text-[14px] shrink-0 shadow-lg shadow-emerald-100">3</div>
                                        <p class="text-[13px] font-bold text-emerald-800 leading-relaxed">Selesaikan transaksi di aplikasi bank/e-wallet.</p>
                                    </div>
                                    <div class="flex items-start gap-4 p-5 rounded-3xl bg-emerald-50 border border-emerald-100">
                                        <div class="step-number bg-emerald-600 w-8 h-8 rounded-xl text-white flex items-center justify-center font-black text-[14px] shrink-0 shadow-lg shadow-emerald-100">4</div>
                                        <p class="text-[13px] font-bold text-emerald-800 leading-relaxed">Status pesanan akan otomatis terupdate.</p>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary -->
                <div class="w-full xl:w-[420px] flex-shrink-0">
                    <div class="bg-white p-10 rounded-[48px] border border-emerald-100 shadow-sm sticky top-28">
                        <h3 class="text-[22px] font-black text-emerald-950 tracking-tight mb-8">Detail Tagihan</h3>
                        
                        <div class="space-y-4 mb-10">
                            @foreach(session('cart', []) as $item)
                            <div class="flex items-center gap-4 p-4 bg-emerald-50 rounded-2xl border border-emerald-50">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-white shadow-sm border border-emerald-100 flex-shrink-0">
                                    <img src="{{ $item['image'] ?? (\App\Models\Book::find($item['id'])->image ?? asset('logo.jpg')) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow">
                                    <p class="text-[13px] font-black text-emerald-950 leading-tight line-clamp-1">{{ $item['title'] }}</p>
                                    <p class="text-[11px] font-bold text-emerald-900 mt-1 uppercase">{{ $item['qty'] }} Unit</p>
                                </div>
                                <span class="text-[13px] font-black text-emerald-950">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="space-y-4 mb-10 border-t border-emerald-50 pt-8">
                            <div class="flex justify-between items-center text-[14px]">
                                <span class="font-bold text-emerald-900">Total Produk</span>
                                <span class="font-black text-emerald-950">Rp {{ number_format(collect(session('cart', []))->sum(fn($i) => $i['price'] * $i['qty']), 0, ',', '.') }}</span>
                            </div>
                            @php
                                $distance = (int) ($request->distance_km ?? 3);
                                $shippingService = $request->shipping_service ?? 'GoSend Same Day';
                                $shippingCost = 0;
                                if ($distance > 0) {
                                    if ($shippingService === 'GoSend Same Day') {
                                        if ($distance <= 3) {
                                            $shippingCost = 12000;
                                        } elseif ($distance <= 15) {
                                            $shippingCost = 18000;
                                        } else {
                                            $shippingCost = (int) ($distance * 1200);
                                        }
                                    } elseif ($shippingService === 'GoSend Instant') {
                                        if ($distance <= 20) {
                                            $shippingCost = (int) max(20000, $distance * 2500);
                                        } else {
                                            $shippingCost = (int) ($distance * 3000);
                                        }
                                    } else {
                                        $shippingCost = $distance <= 4 ? 11800 : 11800 + ($distance - 4) * 2800;
                                    }
                                }
                            @endphp
                            <div class="flex justify-between items-center text-[14px]">
                                <span class="font-bold text-emerald-900">Biaya Pengiriman ({{ $shippingService }} - {{ $distance }} km)</span>
                                <span class="font-black text-emerald-500">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            @php
                                $pointsToRedeem = (int) ($request->points_to_redeem ?? 0);
                                $pointsDiscount = 0;
                                if ($pointsToRedeem >= 100 && $pointsToRedeem <= Auth::user()->points && $pointsToRedeem % 50 === 0) {
                                    $pointsDiscount = $pointsToRedeem * 100;
                                }
                            @endphp
                            @if($pointsDiscount > 0)
                            <div class="flex justify-between items-center text-[14px]">
                                <span class="font-bold text-rose-600 uppercase tracking-widest text-[11px]">Potongan Poin Loyalty ({{ $pointsToRedeem }} Poin)</span>
                                <span class="font-black text-rose-600">- Rp {{ number_format($pointsDiscount, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="bg-emerald-950 p-8 rounded-[32px] text-white mb-10 shadow-xl relative overflow-hidden">
                            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-600 rounded-full blur-[60px] opacity-30"></div>
                            <p class="text-[11px] font-black text-emerald-300 uppercase tracking-[0.2em] mb-2 text-center relative z-10">Total Harus Dibayar</p>
                            <p id="total_pembayaran" class="text-[32px] font-black tracking-tighter text-center leading-none relative z-10">
                                @php
                                    $subtotal = collect(session('cart', []))->sum(fn($i) => $i['price'] * $i['qty']);
                                    $distance = (int) ($request->distance_km ?? 3);
                                    $shippingService = $request->shipping_service ?? 'GoSend Same Day';
                                    $shipping = 0;
                                    if ($distance > 0) {
                                        if ($shippingService === 'GoSend Same Day') {
                                            if ($distance <= 3) {
                                                $shipping = 12000;
                                            } elseif ($distance <= 15) {
                                                $shipping = 18000;
                                            } else {
                                                $shipping = (int) ($distance * 1200);
                                            }
                                        } elseif ($shippingService === 'GoSend Instant') {
                                            if ($distance <= 20) {
                                                $shipping = (int) max(20000, $distance * 2500);
                                            } else {
                                                $shipping = (int) ($distance * 3000);
                                            }
                                        } else {
                                            $shipping = $distance <= 4 ? 11800 : 11800 + ($distance - 4) * 2800;
                                        }
                                    }
                                    $discount = session('active_discount', 0);
                                    $pointsToRedeem = (int) ($request->points_to_redeem ?? 0);
                                    $pointsDiscount = 0;
                                    if ($pointsToRedeem >= 100 && $pointsToRedeem <= Auth::user()->points && $pointsToRedeem % 50 === 0) {
                                        $pointsDiscount = $pointsToRedeem * 100;
                                    }
                                    $discount += $pointsDiscount;
                                    $grandTotal = max(0, $subtotal + $shipping - $discount);
                                @endphp
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </p>
                        </div>

                        <button type="submit" id="btnSubmit" class="w-full flex items-center justify-center gap-3 bg-emerald-600 text-white py-5 rounded-[24px] text-[16px] font-black hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 group">
                            Bayar Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </button>
                        <p class="text-[11px] text-center text-emerald-900 font-bold mt-6 italic">Anda akan diarahkan ke halaman pembayaran aman Midtrans.</p>
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection
