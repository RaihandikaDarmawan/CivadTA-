<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2c3e50;
            margin: 0;
            padding: 10px 20px;
            font-size: 11px;
            line-height: 1.4;
        }
        
        /* Header styling */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .header-logo-td {
            width: 60%;
            vertical-align: middle;
        }
        .header-invoice-td {
            width: 40%;
            text-align: right;
            vertical-align: middle;
        }
        .logo-container {
            display: inline-block;
            vertical-align: middle;
            margin-right: 12px;
        }
        .company-details {
            display: inline-block;
            vertical-align: middle;
        }
        .company-name {
            font-size: 18px;
            font-weight: 800;
            color: #0a2d54;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .company-tagline {
            font-size: 9px;
            color: #5c728a;
            margin: 2px 0 0 0;
            font-weight: 500;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: 900;
            color: #0a2d54;
            letter-spacing: 1px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .invoice-number-badge {
            background-color: #0a2d54;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 6px;
        }
        .invoice-date-top {
            font-size: 9px;
            color: #5c728a;
        }

        /* Divider line */
        .divider {
            border-top: 1px solid #d1dbe5;
            margin-bottom: 20px;
        }

        /* Info columns styling */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .info-td {
            width: 47%;
            vertical-align: top;
        }
        .info-spacer {
            width: 6%;
        }
        .info-title {
            font-size: 10px;
            font-weight: 800;
            color: #0a2d54;
            border-bottom: 1px solid #0a2d54;
            padding-bottom: 4px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-row {
            margin-bottom: 6px;
            font-size: 10px;
        }
        .info-label {
            display: inline-block;
            width: 80px;
            color: #5c728a;
            font-weight: 600;
        }
        .info-value {
            display: inline-block;
            color: #2c3e50;
            font-weight: 500;
        }
        .info-address {
            margin-left: 80px;
            margin-top: -14px;
            color: #2c3e50;
            font-weight: 500;
            line-height: 1.4;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #0a2d54;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            padding: 10px;
            text-transform: uppercase;
            border: 1px solid #0a2d54;
        }
        .items-table td {
            padding: 10px;
            border: 1px solid #d1dbe5;
            vertical-align: middle;
            font-size: 10px;
        }
        .book-title {
            font-weight: bold;
            color: #0a2d54;
            margin-bottom: 2px;
        }
        .book-author {
            font-size: 8px;
            color: #8898aa;
        }
        
        /* Summary Section */
        .summary-container {
            width: 100%;
            margin-top: 15px;
        }
        .footer-note-td {
            width: 50%;
            vertical-align: bottom;
            padding-bottom: 5px;
        }
        .summary-td {
            width: 50%;
            vertical-align: top;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 6px 12px;
            border: 1px solid #d1dbe5;
            font-size: 10px;
        }
        .summary-label {
            text-align: left;
            color: #5c728a;
            font-weight: 600;
            width: 60%;
        }
        .summary-value {
            text-align: right;
            color: #2c3e50;
            font-weight: 600;
            width: 40%;
        }
        .total-row td {
            background-color: #f0f5fa;
            border-top: 2px solid #0a2d54;
            border-bottom: 2px solid #0a2d54;
        }
        .total-label {
            color: #0a2d54;
            font-weight: 800;
            font-size: 11px;
        }
        .total-value {
            color: #0a2d54;
            font-weight: 800;
            font-size: 11px;
        }

        /* Footer note styling */
        .footer-note-box {
            display: table;
            width: 100%;
        }
        .footer-icon {
            display: table-cell;
            vertical-align: top;
            width: 40px;
            padding-right: 10px;
        }
        .footer-text {
            display: table-cell;
            vertical-align: top;
        }
        .footer-thanks {
            font-weight: bold;
            color: #0a2d54;
            font-size: 10px;
            margin: 0 0 3px 0;
        }
        .footer-sub {
            color: #5c728a;
            font-size: 8px;
            margin: 0;
        }
    </style>
</head>
<body>
    @php
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $dateObj = $order->created_at;
        $tanggalIndo = $dateObj->format('d') . ' ' . $bulan[(int)$dateObj->format('m')] . ' ' . $dateObj->format('Y');
        
        $invoiceNumber = 'INV-' . $dateObj->format('Y-m-d') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
    @endphp

    <table class="header-table">
        <tr>
            <td class="header-logo-td">
                <div class="logo-container">
                    <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#0a2d54" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <div class="company-details">
                    <h1 class="company-name">Arya Duta Tangerang</h1>
                </div>
            </td>
            <td class="header-invoice-td">
                <h2 class="invoice-title">Invoice</h2>
                <div class="invoice-number-badge">{{ $invoiceNumber }}</div>
                <div class="invoice-date-top">Tanggal Invoice : {{ $tanggalIndo }}</div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td class="info-td">
                <h3 class="info-title">Informasi Pelanggan</h3>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-value">: {{ $order->recipient_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">: {{ $order->user->email ?? $order->user->username . '@email.com' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. Telepon</span>
                    <span class="info-value">: {{ $order->phone_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat</span>
                    <div class="info-address">: {{ $order->address }}</div>
                </div>
            </td>
            <td class="info-spacer"></td>
            <td class="info-td" style="border-left: 1px solid #d1dbe5; padding-left: 20px;">
                <h3 class="info-title">Informasi Transaksi</h3>
                <div class="info-row">
                    <span class="info-label">No. Invoice</span>
                    <span class="info-value">: {{ $invoiceNumber }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Invoice</span>
                    <span class="info-value">: {{ $tanggalIndo }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">: {{ $order->status }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 8%; text-align: center;">No.</th>
                <th style="width: 47%; text-align: left;">Judul Buku</th>
                <th style="width: 15%; text-align: right;">Harga</th>
                <th style="width: 12%; text-align: center;">Jumlah</th>
                <th style="width: 18%; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; $totalQty = 0; @endphp
            @foreach($order->items as $index => $item)
                @php
                    $itemTotal = $item->price * $item->quantity;
                    $subtotal += $itemTotal;
                    $totalQty += $item->quantity;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <div class="book-title">{{ $item->book->title }}</div>
                        <div class="book-author">{{ $item->book->author }}</div>
                    </td>
                    <td style="text-align: right;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">Rp {{ number_format($itemTotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary-container" cellspacing="0" cellpadding="0">
        <tr>
            <td class="footer-note-td">
                <div class="footer-note-box">
                    <div class="footer-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" fill="#0a2d54" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke="#ffffff" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="footer-text">
                        <p class="footer-thanks">Terima kasih telah berbelanja<br>Arya Duta Tangerang!</p>
                        <p class="footer-sub">Semoga buku-buku pilihanmu memberi banyak manfaat.</p>
                    </div>
                </div>
            </td>
            <td class="summary-td">
                <table class="summary-table">
                    <tr>
                        <td class="summary-label">Subtotal ({{ $totalQty }} Item)</td>
                        <td class="summary-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $discount = ($subtotal + $order->shipping_cost) - $order->total_amount;
                    @endphp
                    @if($discount > 0)
                        <tr>
                            <td class="summary-label">Diskon</td>
                            <td class="summary-value" style="color: #d9534f;">- Rp {{ number_format($discount, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($order->shipping_cost > 0)
                        <tr>
                            <td class="summary-label">Ongkos Kirim</td>
                            <td class="summary-value">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td class="summary-label total-label">TOTAL</td>
                        <td class="summary-value total-value">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
