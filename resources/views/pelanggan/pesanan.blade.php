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
        
        /* Hide HTML5 Up/Down Spinners */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
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
                .bindPopup('<b>Lokasi Arya Duta cabang Tangerang</b><br>Titik Pengiriman Utama').openPopup();

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
            jarakInput.readOnly = true;
            jarakInput.classList.remove('bg-white', 'border-emerald-950');
            jarakInput.classList.add('bg-emerald-50/50', 'border-emerald-950/20', 'text-emerald-950/60', 'cursor-not-allowed');
            
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
            let stock = parseInt(qtyElement.getAttribute('data-stock')) || 0;
            
            if (newQty < 1) return;
            if (newQty > stock) {
                alert('stok tidak mencukupi, tolong ubah jumlah stok');
                return;
            }
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
            
            // Calculate individual rates for UI display
            let costSameDay = 0;
            let costInstant = 0;
            
            if (jarak > 0) {
                // GoSend Same Day
                if (jarak <= 3) {
                    costSameDay = 12000;
                } else if (jarak <= 15) {
                    costSameDay = 18000;
                } else {
                    costSameDay = Math.round(jarak * 1200);
                }
                
                // GoSend Instant
                if (jarak <= 20) {
                    costInstant = Math.max(20000, Math.round(jarak * 2500));
                } else {
                    costInstant = Math.round(jarak * 3000);
                }
            }
            
            // Update individual cost display in labels
            document.getElementById('cost_shipping_gosend_same_day').innerText = 'Rp ' + costSameDay.toLocaleString('id-ID');
            document.getElementById('cost_shipping_gosend_instant').innerText = 'Rp ' + costInstant.toLocaleString('id-ID');
            
            // Handle GoSend Instant distance limitation (> 40 km)
            const radioInstant = document.querySelector('input[name="shipping_service"][value="GoSend Instant"]');
            const cardInstant = document.getElementById('label_shipping_gosend_instant');
            const cardSameDay = document.getElementById('label_shipping_gosend_same_day');
            const warningEl = document.getElementById('shipping_warning');
            
            if (jarak > 40) {
                radioInstant.disabled = true;
                cardInstant.classList.add('opacity-50', 'cursor-not-allowed', 'bg-slate-50');
                cardInstant.classList.remove('hover:bg-emerald-50/20');
                if (radioInstant.checked) {
                    document.querySelector('input[name="shipping_service"][value="GoSend Same Day"]').checked = true;
                }
                if (warningEl) {
                    warningEl.innerText = '*GoSend Instant tidak tersedia untuk jarak pengiriman di atas 40 km.';
                    warningEl.classList.remove('hidden');
                }
            } else {
                radioInstant.disabled = false;
                cardInstant.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-slate-50');
                cardInstant.classList.add('hover:bg-emerald-50/20');
                if (warningEl) {
                    warningEl.classList.add('hidden');
                }
            }
            
            // Get selected shipping service
            const selectedRadio = document.querySelector('input[name="shipping_service"]:checked');
            const shippingService = selectedRadio ? selectedRadio.value : 'GoSend Same Day';
            
            // Update card borders
            cardSameDay.classList.remove('border-emerald-950');
            cardSameDay.classList.add('border-emerald-100');
            cardInstant.classList.remove('border-emerald-950');
            cardInstant.classList.add('border-emerald-100');
            
            if (shippingService === 'GoSend Same Day') {
                cardSameDay.classList.add('border-emerald-950');
                cardSameDay.classList.remove('border-emerald-100');
            } else if (shippingService === 'GoSend Instant') {
                cardInstant.classList.add('border-emerald-950');
                cardInstant.classList.remove('border-emerald-100');
            }
            
            // Determine active biayaPengiriman
            let biayaPengiriman = 0;
            if (shippingService === 'GoSend Same Day') {
                biayaPengiriman = costSameDay;
            } else if (shippingService === 'GoSend Instant') {
                biayaPengiriman = costInstant;
            }
            
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
            
            const pointsSelect = document.getElementById('points_to_redeem');
            let pointsToRedeem = 0;
            if (pointsSelect) {
                pointsToRedeem = parseInt(pointsSelect.value) || 0;
            }
            
            let pointDiscount = 0;
            if (pointsToRedeem >= 100 && pointsToRedeem % 50 === 0) {
                pointDiscount = pointsToRedeem * 100;
            }
            
            diskon += pointDiscount;
            
            if (pointDiscount > 0) {
                const poinDiscountAmountEl = document.getElementById('poin_discount_amount');
                if (poinDiscountAmountEl) {
                    poinDiscountAmountEl.innerText = '- Rp ' + pointDiscount.toLocaleString('id-ID');
                }
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

            document.getElementById('alamat_lengkap').addEventListener('input', function() {
                if (this.value.trim() === '') {
                    if (marker) {
                        map.removeLayer(marker);
                        marker = null;
                    }
                    document.getElementById('lat_input').value = '';
                    document.getElementById('lng_input').value = '';
                    
                    let jarakInput = document.getElementById('jarak');
                    jarakInput.value = '-';
                    jarakInput.readOnly = false;
                    jarakInput.classList.remove('bg-emerald-50/50', 'border-emerald-950/20', 'text-emerald-950/60', 'cursor-not-allowed');
                    jarakInput.classList.add('bg-white', 'border-emerald-950');
                    
                    updateSummary();
                }
            });
        }
    </script>
@endsection

@section('content')
    <div class="mb-12 text-center md:text-left">
        <h1 class="text-[32px] md:text-[48px] font-black text-emerald-950 tracking-tighter leading-none mb-3">Informasi Pesanan</h1>
    </div>

    @if($errors->any())
    <div class="mb-8 p-6 bg-red-50 border border-red-100 text-red-600 text-[13px] rounded-3xl font-bold animate-in fade-in slide-in-from-top-2">
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ url('/pelanggan/pembayaran') }}" method="POST">
        @csrf
        <div class="flex flex-col xl:flex-row gap-8">
            <!-- Left Side: Forms -->
            <div class="flex-grow flex flex-col gap-6">
                
                <!-- Identitas Penerima -->
                <div class="bg-emerald-50/30 p-5 md:p-8 rounded-3xl border border-emerald-100 shadow-sm backdrop-blur-sm">
                    <div class="flex items-center gap-4 mb-6">
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
                            <input type="text" name="recipient_name" value="{{ old('recipient_name', Auth::user()->name ?? '') }}" placeholder="Nama penerima..." class="w-full px-5 py-3 rounded-xl bg-white border-2 border-emerald-950 focus:outline-none text-[15px] font-bold text-emerald-950 transition-all placeholder:text-emerald-950/20" required>
                        </div>
                        <div class="space-y-2.5">
                            <label class="text-[12px] font-black text-emerald-900 uppercase tracking-widest ml-1">No. Handphone</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number', Auth::user()->phone ?? '') }}" placeholder="Contoh: 0812..." 
                                   minlength="10" maxlength="30" pattern="[0-9]{10,13}" inputmode="numeric"
                                   oninvalid="this.setCustomValidity('nomor telepon harus terdiri dari 10-13 digit')"
                                   oninput="this.setCustomValidity(''); this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="w-full px-5 py-3 rounded-xl bg-white border-2 border-emerald-950 focus:outline-none text-[15px] font-bold text-emerald-950 transition-all placeholder:text-emerald-950/20" required>
                        </div>
                    </div>
                </div>

                <!-- Lokasi Pengiriman -->
                <div class="bg-white p-5 md:p-8 rounded-3xl border border-emerald-100 shadow-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-emerald-50">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 115 0z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-[18px] font-black text-emerald-950 tracking-tight">Detail Lokasi</h3>
                                <p class="text-[11px] font-bold text-emerald-900 uppercase tracking-widest mt-0.5">Alamat & Jarak</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2 max-w-md bg-emerald-50/50 hover:bg-emerald-50 p-3 rounded-2xl border border-emerald-100/50 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-emerald-700 mt-0.5 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 115 0z" />
                            </svg>
                            <div class="flex flex-col">
                                <span class="text-[9px] font-bold text-emerald-800 uppercase tracking-wider mb-0.5">Alamat Lokasi Penjualan</span>
                                <a href="https://maps.app.goo.gl/HBUQsD1jCrT7zENd7" target="_blank" rel="noopener noreferrer" class="text-[12px] font-bold text-emerald-950 hover:text-emerald-700 transition-colors duration-200 leading-tight decoration-emerald-700/30 hover:underline">
                                    Jl. Karyawan 1 No.71, Karang Tengah, Banten, Kota Tangerang - 15157
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-3">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-1">
                                <label class="text-[12px] font-black text-emerald-900 uppercase tracking-widest ml-1">Tandai Lokasi di Peta</label>
                                <button type="button" id="btn-geo" onclick="useCurrentLocation()" class="flex items-center gap-2 px-4 py-2 bg-emerald-950 text-white text-[10px] font-black rounded-lg hover:bg-emerald-800 transition-all shadow-md active:scale-95 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 115 0z" /></svg>
                                    Lokasi Saat Ini
                                </button>
                            </div>
                            <div id="map" class="h-[200px] md:h-[300px] rounded-2xl overflow-hidden border-4 border-emerald-50"></div>
                            
                            <input type="hidden" name="latitude" id="lat_input">
                            <input type="hidden" name="longitude" id="lng_input">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[12px] font-black text-emerald-900 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea id="alamat_lengkap" name="address" class="w-full px-5 py-3 rounded-2xl bg-white border-2 border-emerald-950 focus:outline-none text-[14px] font-bold text-emerald-950 h-24 resize-none transition-all placeholder:text-emerald-950/20" placeholder="Masukkan alamat pengiriman selengkap mungkin..." required>{{ old('address', Auth::user()->address ?? '') }}</textarea>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-[12px] font-black text-emerald-900 uppercase tracking-widest ml-1">Estimasi Jarak</label>
                            <div class="relative">
                                <input type="number" id="jarak" name="distance_km" min="1" value="-" oninput="updateSummary()" onchange="updateSummary()" class="w-full px-5 py-3 rounded-xl bg-white border-2 border-emerald-950 focus:outline-none text-[15px] font-black text-emerald-950 pr-16 transition-all" required>
                                <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-emerald-950/40">
                                    <span class="text-[14px] font-black">km</span>
                                </div>
                            </div>
                        </div>

                        <!-- Opsi Pengiriman -->
                        <div class="space-y-2.5 mt-4">
                            <label class="text-[12px] font-black text-emerald-900 uppercase tracking-widest ml-1">Opsi Pengiriman</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Option 1: GoSend Same Day -->
                                <label class="relative flex flex-col p-4 bg-white border-2 border-emerald-950 rounded-xl cursor-pointer hover:bg-emerald-50/20 transition-all shadow-sm select-none" id="label_shipping_gosend_same_day">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[13px] font-black text-emerald-950">GoSend Same Day</span>
                                        <input type="radio" name="shipping_service" value="GoSend Same Day" checked onchange="updateSummary()" class="w-4 h-4 text-emerald-950 border-emerald-950 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                                    </div>
                                    <span class="text-[14px] font-black text-emerald-600" id="cost_shipping_gosend_same_day">Rp 0</span>
                                    <span class="text-[10px] text-emerald-900/60 font-bold mt-1">Estimasi 6-8 Jam</span>
                                </label>

                                <!-- Option 2: GoSend Instant -->
                                <label class="relative flex flex-col p-4 bg-white border-2 border-emerald-100 rounded-xl cursor-pointer hover:bg-emerald-50/20 transition-all shadow-sm select-none" id="label_shipping_gosend_instant">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-[13px] font-black text-emerald-950">GoSend Instant</span>
                                        <input type="radio" name="shipping_service" value="GoSend Instant" onchange="updateSummary()" class="w-4 h-4 text-emerald-950 border-emerald-950 focus:ring-0 focus:ring-offset-0 cursor-pointer">
                                    </div>
                                    <span class="text-[14px] font-black text-emerald-600" id="cost_shipping_gosend_instant">Rp 0</span>
                                    <span class="text-[10px] text-emerald-900/60 font-bold mt-1">Estimasi 1-2 Jam</span>
                                </label>
                            </div>
                            <p class="text-[11px] font-bold text-rose-500 mt-2 ml-1 hidden" id="shipping_warning"></p>
                        </div>
                    </div>
                </div>

                <!-- Reward Poin -->
                <div class="bg-emerald-950 p-4 md:p-6 rounded-3xl text-white flex items-center justify-between shadow-2xl relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 010 .656l-5.603 3.113a.375.375 0 01-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-[16px] md:text-[18px] font-black tracking-tight">Reward Poin</h3>
                            <p class="text-emerald-400/50 text-[10px] md:text-[11px] font-black uppercase tracking-widest mt-0.5">Bonus Pembelian Ini</p>
                        </div>
                    </div>
                    <div id="poin_didapat" class="text-[16px] md:text-[20px] font-black text-white bg-white/10 px-5 py-2.5 rounded-xl border border-white/10 shadow-inner relative z-10">
                        0 Poin
                    </div>
                </div>

                <!-- Gunakan Poin Loyalty -->
                <div class="bg-white p-5 md:p-8 rounded-3xl border border-emerald-100 shadow-xl">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-950 rounded-2xl flex items-center justify-center border-2 border-emerald-100 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-emerald-950"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0-1.268-.63-2.39-1.593-3.068a3.745 3.745 0 00-1.043-3.296 3.745 3.745 0 00-3.296-1.043A3.745 3.745 0 0012 3c-1.268 0-2.39.63-3.068 1.593a3.746 3.746 0 00-3.296 1.043 3.745 3.745 0 00-1.043 3.296A3.745 3.745 0 003 12c0 1.268.63 2.39 1.593 3.068a3.745 3.745 0 001.043 3.296 3.746 3.746 0 003.296 1.043A3.746 3.746 0 0012 21c1.268 0 2.39-.63 3.068-1.593a3.746 3.746 0 003.296-1.043 3.745 3.745 0 001.043-3.296A3.745 3.745 0 0021 12z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-[18px] font-black text-emerald-950 tracking-tight">Gunakan Poin</h3>
                                <p class="text-[11px] font-bold text-emerald-900 uppercase tracking-widest mt-0.5 font-sans">Poin Anda: {{ Auth::user()->points ?? 0 }} Poin</p>
                            </div>
                        </div>
                        <div class="w-full sm:w-auto">
                            @if((Auth::user()->points ?? 0) >= 100)
                                <div class="flex flex-col gap-1 w-full">
                                    <select id="points_to_redeem" name="points_to_redeem" onchange="updateSummary()" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-emerald-50 border-2 border-emerald-950 text-[13px] font-black text-emerald-950 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-0 cursor-pointer select-none">
                                        <option value="0">Jangan gunakan poin</option>
                                        @for ($p = 100; $p <= Auth::user()->points; $p += 50)
                                            <option value="{{ $p }}">Gunakan {{ $p }} Poin (Potongan Rp {{ number_format($p * 100, 0, ',', '.') }})</option>
                                        @endfor
                                    </select>
                                </div>
                            @else
                                <span class="text-[11px] font-bold text-emerald-900/50 italic bg-emerald-50 px-4 py-2.5 rounded-xl border border-emerald-50/50">Butuh min. 100 Poin</span>
                            @endif
                        </div>
                    </div>
                    @if((Auth::user()->points ?? 0) >= 100)
                        <p class="text-[11px] font-bold text-emerald-900 mt-2 ml-1 italic">*Minimal penukaran 100 poin, berlaku kelipatan 50 poin. Setiap 1 poin bernilai Rp 100 potongan harga.</p>
                    @endif
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="w-full xl:w-[350px] flex-shrink-0">
                <div class="bg-emerald-950 p-5 md:p-6 rounded-[24px] md:rounded-[30px] border border-white/10 shadow-2xl sticky top-28 overflow-hidden group/summary">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-600 rounded-full blur-[100px] opacity-20"></div>
                    
                    <div class="relative z-10">
                        <h2 class="text-[18px] md:text-[22px] font-black text-white tracking-tight mb-4 flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                            Konfirmasi Pesanan
                        </h2>
                        
                        <div class="space-y-2.5 mb-4 max-h-[180px] overflow-y-auto pr-1 custom-scrollbar">
                            @foreach(session('checkout_items', []) as $item)
                            @php
                                $book = \App\Models\Book::find($item['id']);
                                $stock = $book ? $book->stock : 0;
                            @endphp
                            <div class="flex items-center gap-3 bg-white/5 p-3 rounded-[20px] border border-white/10 hover:bg-white/10 transition-all group">
                                <div class="w-12 h-12 bg-white/10 rounded-xl overflow-hidden border border-white/10 flex-shrink-0 shadow-sm">
                                    <img src="{{ $item['image'] ?? ($book->image ?? '') }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="text-[12px] font-black text-emerald-50 truncate leading-tight mb-1">{{ $item['title'] }}</p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5 bg-white/10 px-1.5 py-0.5 rounded-md">
                                            <button type="button" onclick="changeItemQty('{{ $item['id'] }}', -1)" class="w-5 h-5 flex items-center justify-center text-emerald-400 hover:text-white rounded-md transition-all active:scale-75">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-2.5 h-2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                                            </button>
                                            <span id="qty-{{ $item['id'] }}" class="text-[10px] font-black text-white w-3 text-center" data-stock="{{ $stock }}">{{ $item['qty'] }}</span>
                                            <button type="button" onclick="changeItemQty('{{ $item['id'] }}', 1)" class="w-5 h-5 flex items-center justify-center text-emerald-400 hover:text-white rounded-md transition-all active:scale-75">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-2.5 h-2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                            </button>
                                        </div>
                                        <span class="text-[12px] font-black text-emerald-400" id="subtotal-{{ $item['id'] }}" data-price="{{ $item['price'] }}">
                                            Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="space-y-2 pt-4 border-t border-white/10 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-emerald-400/60 uppercase tracking-widest text-[10px]">Subtotal Produk</span>
                                <span id="display_total_produk" class="font-black text-white text-[14px]">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-emerald-400/60 uppercase tracking-widest text-[10px]">Biaya Pengiriman</span>
                                <span id="ongkir_total" class="font-black text-emerald-400 text-[14px]">Rp 0</span>
                            </div>
                            @if(session('active_discount', 0) > 0)
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-rose-400 uppercase tracking-widest text-[10px]">Potongan Rewards</span>
                                <span class="font-black text-rose-400 text-[14px]">- Rp {{ number_format(session('active_discount'), 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div id="poin_discount_row" class="flex justify-between items-center hidden">
                                <span class="font-bold text-rose-400 uppercase tracking-widest text-[10px]">Potongan Poin</span>
                                <span id="poin_discount_amount" class="font-black text-rose-400 text-[14px]">- Rp 0</span>
                            </div>
                        </div>

                        <div class="bg-white/5 backdrop-blur-md p-4 rounded-[18px] mb-4 border border-white/10 shadow-inner">
                            <p class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-1.5 text-center">Total Pembayaran</p>
                            <p id="total_pembayaran" class="text-[26px] md:text-[30px] font-black text-white tracking-tighter text-center leading-none">Rp 0</p>
                        </div>

                        <button type="submit" class="w-full flex items-center justify-center bg-white text-emerald-950 py-3 rounded-xl text-[14px] font-black shadow-xl hover:bg-emerald-50 hover:scale-[1.02] transition-all active:scale-95">
                            Buat Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
