@extends('layouts.customer_premium')

@section('title', 'Checkout Pesanan')

@section('scripts')
    <!-- Leaflet Maps CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <style>
        #map { height: 400px; width: 100%; border-radius: 32px; border: 2px solid #064e3b; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .leaflet-container { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        @keyframes pulse-emerald {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); color: #059669; }
            100% { transform: scale(1); }
        }
        .animate-update { animation: pulse-emerald 0.3s ease-in-out; }
        
        .premium-shadow { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05); }
        
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }
    </style>

    <script>
        let map, marker;
        const GUDANG_COORDS = [-6.216968, 106.711253]; // Arya Duta Tangerang

        function initMap() {
            map = L.map('map').setView(GUDANG_COORDS, 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Warehouse marker
            L.marker(GUDANG_COORDS).addTo(map)
                .bindPopup('<b>Gudang Arya Duta</b><br>Titik Pengiriman Utama').openPopup();

            map.on('click', function(e) {
                setMarker(e.latlng);
            });
        }

        function setMarker(latlng) {
            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng, {draggable: true}).addTo(map);
                marker.on('dragend', function(event) {
                    setMarker(event.target.getLatLng());
                });
            }
            
            // Save to hidden inputs
            document.getElementById('lat_input').value = latlng.lat;
            document.getElementById('lng_input').value = latlng.lng;

            // Calculate distance
            let distance = map.distance(latlng, GUDANG_COORDS) / 1000;
            let roundedDist = Math.max(1, Math.round(distance));
            
            let jarakInput = document.getElementById('jarak');
            jarakInput.value = roundedDist;
            
            updateSummary();

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latlng.lat}&lon=${latlng.lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        document.getElementById('alamat_lengkap').value = data.display_name;
                    }
                });
            
            map.panTo(latlng);
        }

        function useCurrentLocation() {
            const btn = document.getElementById('btn-geo');
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" viewBox="0 0 24 24">...</svg> Mencari...';
            btn.disabled = true;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const latlng = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        setMarker(latlng);
                        btn.innerHTML = 'Lokasi Berhasil Ditemukan';
                        btn.classList.replace('bg-emerald-600', 'bg-blue-600');
                        setTimeout(() => {
                            btn.innerHTML = 'Gunakan Lokasi Saat Ini';
                            btn.classList.replace('bg-blue-600', 'bg-emerald-600');
                            btn.disabled = false;
                        }, 3000);
                    },
                    (error) => {
                        alert("Gagal mendapatkan lokasi: " + error.message);
                        btn.innerHTML = 'Gunakan Lokasi Saat Ini';
                        btn.disabled = false;
                    }
                );
            } else {
                alert("Browser Anda tidak mendukung geolokasi.");
                btn.disabled = false;
            }
        }

        function changeItemQty(id, delta) {
            let qtyElement = document.getElementById('qty-' + id);
            let currentQty = parseInt(qtyElement.innerText);
            let newQty = currentQty + delta;
            
            if (newQty < 1) return;
            if (newQty > 50) return; // Limit quantity

            // Update UI immediately
            qtyElement.innerText = newQty;
            
            // Update session via AJAX
            fetch('{{ url("/pelanggan/keranjang/update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, qty: newQty })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the item subtotal in UI
                    let subtotalElement = document.getElementById('subtotal-' + id);
                    let price = parseInt(subtotalElement.dataset.price);
                    subtotalElement.innerText = 'Rp ' + (price * newQty).toLocaleString('id-ID');
                    
                    // Visual feedback
                    subtotalElement.classList.add('animate-update');
                    setTimeout(() => subtotalElement.classList.remove('animate-update'), 300);
                    
                    // Recalculate overall summary
                    updateSummary();
                }
            });
        }

        function updateSummary() {
            let jarakInput = document.getElementById('jarak');
            let jarak = parseInt(jarakInput.value) || 0;
            let biayaPerKm = 2800;
            let biayaPengiriman = jarak * biayaPerKm;
            
            // Calculate totalProduk from UI elements
            let totalProduk = 0;
            document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
                let price = parseInt(el.dataset.price);
                let id = el.id.split('-')[1];
                let qty = parseInt(document.getElementById('qty-' + id).innerText);
                totalProduk += price * qty;
            });
            
            const displayTotal = document.getElementById('display_total_produk');
            const totalBayar = document.getElementById('total_pembayaran');
            
            displayTotal.innerText = 'Rp ' + totalProduk.toLocaleString('id-ID');
            
            let basePoin = Math.floor(totalProduk / 10000);
            document.getElementById('poin_didapat').innerText = basePoin + " Poin";
            
            let diskon = {{ session('active_discount', 0) }};
            
            const usePointsCheckbox = document.getElementById('use_points');
            if (usePointsCheckbox && usePointsCheckbox.checked) {
                diskon += 10000;
                document.getElementById('poin_discount_row').classList.remove('hidden');
            } else {
                if (document.getElementById('poin_discount_row')) {
                    document.getElementById('poin_discount_row').classList.add('hidden');
                }
            }
            
            let totalBayarNominal = Math.max(0, totalProduk + biayaPengiriman - diskon);
            
            document.getElementById('ongkir_total').innerText = 'Rp ' + biayaPengiriman.toLocaleString('id-ID');
            totalBayar.innerText = 'Rp ' + totalBayarNominal.toLocaleString('id-ID');
            
            // Visual feedback for totals
            totalBayar.classList.add('animate-update');
            setTimeout(() => totalBayar.classList.remove('animate-update'), 300);
        }

        window.onload = function() {
            initMap();
            updateSummary();
        }
    </script>
@endsection

@section('content')
    <div class="mb-12 text-center md:text-left">
        <h1 class="text-[32px] md:text-[48px] font-black text-emerald-950 tracking-tighter leading-none mb-3">Informasi Pesanan</h1>
    </div>

    <form action="{{ url('/pelanggan/pembayaran') }}" method="POST">
        @csrf
        <div class="flex flex-col xl:flex-row gap-8 md:gap-16">
            <!-- Left Side: Forms -->
            <div class="flex-grow flex flex-col gap-8">
                
                <!-- Identitas Penerima -->
                <div class="bg-emerald-50/30 p-6 md:p-10 rounded-[40px] border border-emerald-100 shadow-sm backdrop-blur-sm">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-[18px] font-black text-emerald-950 tracking-tight">Identitas Penerima</h3>
                            </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2.5">
                            <label class="text-[12px] font-black text-emerald-900 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="recipient_name" placeholder="Nama penerima..." class="w-full px-6 py-4 rounded-2xl bg-white border-2 border-emerald-950 focus:outline-none text-[15px] font-bold text-emerald-950 transition-all placeholder:text-emerald-950/20" required>
                        </div>
                        <div class="space-y-2.5">
                            <label class="text-[12px] font-black text-emerald-900 uppercase tracking-widest ml-1">No. Handphone</label>
                            <input type="text" name="phone_number" placeholder="Contoh: 0812..." class="w-full px-6 py-4 rounded-2xl bg-white border-2 border-emerald-950 focus:outline-none text-[15px] font-bold text-emerald-950 transition-all placeholder:text-emerald-950/20" required>
                        </div>
                    </div>
                </div>

                <!-- Lokasi Pengiriman -->
                <div class="bg-white p-6 md:p-10 rounded-[40px] border border-emerald-100 shadow-xl">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-[18px] font-black text-emerald-950 tracking-tight">Detail Lokasi</h3>
                            <p class="text-[11px] font-bold text-emerald-900 uppercase tracking-widest mt-0.5">Alamat & Jarak</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="space-y-4">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
                                <label class="text-[12px] font-black text-emerald-900 uppercase tracking-widest ml-1">Tandai Lokasi di Peta</label>
                                <button type="button" id="btn-geo" onclick="useCurrentLocation()" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-950 text-white text-[10px] font-black rounded-xl hover:bg-emerald-800 transition-all shadow-lg active:scale-95 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    Lokasi Saat Ini
                                </button>
                            </div>
                            <div id="map" class="h-[250px] md:h-[400px] rounded-[32px] overflow-hidden border-4 border-emerald-50"></div>
                            
                            <input type="hidden" name="latitude" id="lat_input">
                            <input type="hidden" name="longitude" id="lng_input">
                        </div>

                        <div class="space-y-3">
                            <label class="text-[12px] font-black text-emerald-900 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea id="alamat_lengkap" name="address" class="w-full px-6 py-4 rounded-[28px] bg-white border-2 border-emerald-950 focus:outline-none text-[15px] font-bold text-emerald-950 h-32 resize-none transition-all placeholder:text-emerald-950/20" placeholder="Masukkan alamat pengiriman selengkap mungkin..." required></textarea>
                        </div>
                        
                        <div class="space-y-3">
                            <label class="text-[12px] font-black text-emerald-900 uppercase tracking-widest ml-1">Estimasi Jarak</label>
                            <div class="relative">
                                <input type="number" id="jarak" name="distance_km" min="1" value="3" oninput="updateSummary()" onchange="updateSummary()" class="w-full px-6 py-4 rounded-2xl bg-white border-2 border-emerald-950 focus:outline-none text-[15px] font-black text-emerald-950 pr-16 transition-all" required>
                                <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-emerald-950/40">
                                    <span class="text-[14px] font-black">km</span>
                                </div>
                            </div>
                            <p class="text-[11px] font-bold text-emerald-900 mt-2 ml-1 italic">*Biaya kirim: Rp 2.800 / KM</p>
                        </div>
                    </div>
                </div>

                <!-- Reward Poin -->
                <div class="bg-emerald-950 p-6 md:p-8 rounded-[40px] text-white flex items-center justify-between shadow-2xl relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="flex items-center gap-4 md:gap-6 relative z-10">
                        <div class="w-12 h-12 md:w-14 md:h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 md:w-7 md:h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-[16px] md:text-[18px] font-black tracking-tight">Reward Poin</h3>
                            <p class="text-emerald-400/50 text-[10px] md:text-[11px] font-black uppercase tracking-widest mt-0.5">Bonus Pembelian Ini</p>
                        </div>
                    </div>
                    <div id="poin_didapat" class="text-[18px] md:text-[24px] font-black text-white bg-white/10 px-6 py-3 rounded-2xl border border-white/10 shadow-inner relative z-10">
                        0 Poin
                    </div>
                </div>

                <!-- Gunakan Poin Loyalty -->
                <div class="bg-white p-6 md:p-10 rounded-[40px] border border-emerald-100 shadow-xl">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-950 rounded-2xl flex items-center justify-center border-2 border-emerald-100 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-emerald-950"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0-1.268-.63-2.39-1.593-3.068a3.745 3.745 0 00-1.043-3.296 3.745 3.745 0 00-3.296-1.043A3.745 3.745 0 0012 3c-1.268 0-2.39.63-3.068 1.593a3.746 3.746 0 00-3.296 1.043 3.745 3.745 0 00-1.043 3.296A3.745 3.745 0 003 12c0 1.268.63 2.39 1.593 3.068a3.745 3.745 0 001.043 3.296 3.746 3.746 0 003.296 1.043A3.746 3.746 0 0012 21c1.268 0 2.39-.63 3.068-1.593a3.746 3.746 0 003.296-1.043 3.745 3.745 0 001.043-3.296A3.745 3.745 0 0021 12z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-[18px] font-black text-emerald-950 tracking-tight">Gunakan Poin</h3>
                                <p class="text-[11px] font-bold text-emerald-900 uppercase tracking-widest mt-0.5">Poin Anda: {{ Auth::user()->points ?? 0 }} Poin</p>
                            </div>
                        </div>
                        <div>
                            @if((Auth::user()->points ?? 0) >= 100)
                                <label class="inline-flex items-center cursor-pointer gap-3 bg-emerald-50 px-5 py-3 rounded-2xl border border-emerald-100 hover:bg-emerald-100/50 transition-all select-none">
                                    <input type="checkbox" id="use_points" name="use_points" value="1" onchange="updateSummary()" class="w-5 h-5 rounded-lg border-2 border-emerald-950 text-emerald-950 focus:ring-emerald-500 focus:ring-2 focus:ring-offset-0 cursor-pointer">
                                    <span class="text-[12px] font-black text-emerald-950 uppercase tracking-wider">Gunakan 100 Poin</span>
                                </label>
                            @else
                                <span class="text-[11px] font-bold text-emerald-900/50 italic bg-emerald-50 px-4 py-2.5 rounded-xl border border-emerald-50/50">Butuh min. 100 Poin</span>
                            @endif
                        </div>
                    </div>
                    @if((Auth::user()->points ?? 0) >= 100)
                        <p class="text-[11px] font-bold text-emerald-900 mt-3 ml-1 italic">*Menggunakan 100 poin akan memotong total belanja Anda sebesar Rp 10.000</p>
                    @endif
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="w-full xl:w-[460px] flex-shrink-0">
                <div class="bg-emerald-950 p-8 md:p-12 rounded-[40px] md:rounded-[56px] border border-white/10 shadow-2xl sticky top-28 overflow-hidden group/summary">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-600 rounded-full blur-[100px] opacity-20"></div>
                    
                    <div class="relative z-10">
                        <h2 class="text-[22px] md:text-[26px] font-black text-white tracking-tight mb-8 flex items-center gap-4">
                            <div class="w-1.5 h-8 bg-emerald-500 rounded-full"></div>
                            Konfirmasi Pesanan
                        </h2>
                        
                        <div class="space-y-4 mb-10 max-h-[250px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach(session('cart', []) as $item)
                            <div class="flex items-center gap-4 bg-white/5 p-4 rounded-[28px] border border-white/10 hover:bg-white/10 transition-all group">
                                <div class="w-14 h-14 bg-white/10 rounded-2xl overflow-hidden border border-white/10 flex-shrink-0 shadow-sm">
                                    <img src="{{ $item['image'] ?? (\App\Models\Book::find($item['id'])->image ?? '') }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="text-[13px] font-black text-emerald-50 truncate leading-tight mb-2">{{ $item['title'] }}</p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2 bg-white/10 px-2 py-1 rounded-lg">
                                            <button type="button" onclick="changeItemQty('{{ $item['id'] }}', -1)" class="w-6 h-6 flex items-center justify-center text-emerald-400 hover:text-white rounded-md transition-all active:scale-75">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                                            </button>
                                            <span id="qty-{{ $item['id'] }}" class="text-[11px] font-black text-white w-4 text-center">{{ $item['qty'] }}</span>
                                            <button type="button" onclick="changeItemQty('{{ $item['id'] }}', 1)" class="w-6 h-6 flex items-center justify-center text-emerald-400 hover:text-white rounded-md transition-all active:scale-75">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                            </button>
                                        </div>
                                        <span class="text-[13px] font-black text-emerald-400" id="subtotal-{{ $item['id'] }}" data-price="{{ $item['price'] }}">
                                            Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="space-y-4 pt-8 border-t border-white/10 mb-10">
                            <div class="flex justify-between items-center text-[14px]">
                                <span class="font-bold text-emerald-400/60 uppercase tracking-widest text-[11px]">Subtotal Produk</span>
                                <span id="display_total_produk" class="font-black text-white text-[16px]">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-[14px]">
                                <span class="font-bold text-emerald-400/60 uppercase tracking-widest text-[11px]">Biaya Pengiriman</span>
                                <span id="ongkir_total" class="font-black text-emerald-400 text-[16px]">Rp 0</span>
                            </div>
                            @if(session('active_discount', 0) > 0)
                            <div class="flex justify-between items-center text-[14px]">
                                <span class="font-bold text-rose-400 uppercase tracking-widest text-[11px]">Potongan Rewards</span>
                                <span class="font-black text-rose-400 text-[16px]">- Rp {{ number_format(session('active_discount'), 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div id="poin_discount_row" class="flex justify-between items-center text-[14px] hidden">
                                <span class="font-bold text-rose-400 uppercase tracking-widest text-[11px]">Potongan Poin</span>
                                <span class="font-black text-rose-400 text-[16px]">- Rp 10.000</span>
                            </div>
                        </div>

                        <div class="bg-white/5 backdrop-blur-md p-8 rounded-[40px] mb-10 border border-white/10 shadow-inner">
                            <p class="text-[11px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-3 text-center">Total Pembayaran</p>
                            <p id="total_pembayaran" class="text-[36px] md:text-[44px] font-black text-white tracking-tighter text-center leading-none">Rp 0</p>
                        </div>

                        <button type="submit" class="w-full flex items-center justify-center gap-4 bg-white text-emerald-950 py-5 rounded-2xl text-[16px] font-black shadow-xl hover:bg-emerald-50 hover:scale-[1.02] transition-all active:scale-95 group">
                            Buat Pesanan
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
