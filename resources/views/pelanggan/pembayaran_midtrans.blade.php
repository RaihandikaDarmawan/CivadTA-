<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selesaikan Pembayaran - Arya Duta Tangerang</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f0fdf4; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="text-center p-10 bg-white rounded-[48px] shadow-xl border border-emerald-100 max-w-md w-full mx-6">
        <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-[24px] flex items-center justify-center mb-8 mx-auto animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75-10.5h16.5a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25H3.75a2.25 2.25 0 01-2.25-2.25V5.25a2.25 2.25 0 012.25-2.25z" />
            </svg>
        </div>
        <h2 class="text-[24px] font-black text-emerald-950 mb-3 tracking-tight">Menyiapkan Pembayaran</h2>
        <p class="text-emerald-700 font-bold text-[14px] mb-10">Pesanan #{{ $order->order_number }}</p>
        
        <button id="pay-button" class="w-full bg-emerald-950 text-white py-5 rounded-[24px] text-[16px] font-black hover:bg-emerald-800 transition-all shadow-xl shadow-emerald-950/20 mb-4">
            Bayar Sekarang
        </button>
        
        <p class="text-[11px] text-emerald-600 font-bold uppercase tracking-widest">Aman & Terenkripsi oleh Midtrans</p>
    </div>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        
        // Langsung panggil snap saat halaman dimuat
        window.onload = function() {
            triggerSnap();
        };

        payButton.onclick = function() {
            triggerSnap();
        };

        function triggerSnap() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = "{{ url('/pelanggan/riwayat') }}?status=success";
                },
                onPending: function(result) {
                    window.location.href = "{{ url('/pelanggan/riwayat') }}?status=pending";
                },
                onError: function(result) {
                    window.location.href = "{{ url('/pelanggan/riwayat') }}?status=error";
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        }
    </script>
</body>
</html>
