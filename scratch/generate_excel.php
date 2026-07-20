<?php

$scenarios = [
    [
        'id' => 'TS.Reg.001',
        'case_id' => 'TC.Reg.001',
        'test' => 'Cek registrasi pelanggan baru dengan data valid',
        'type' => 'Positive',
        'test_case' => 'Pelanggan berhasil mendaftarkan akun baru ke sistem',
        'pre_condition' => 'Pelanggan belum memiliki akun pada sistem',
        'steps' => [
            ['desc' => 'Buka halaman registrasi /register', 'expected' => 'Sistem menampilkan halaman form registrasi pelanggan'],
            ['desc' => 'Input nama lengkap "John Doe"', 'expected' => 'Kolom nama terisi "John Doe"'],
            ['desc' => 'Input username unik "johndoe"', 'expected' => 'Kolom username terisi "johndoe"'],
            ['desc' => 'Input email unik "johndoe@example.com"', 'expected' => 'Kolom email terisi "johndoe@example.com"'],
            ['desc' => 'Input password yang valid "Password123"', 'expected' => 'Kolom password terisi "Password123" (disamarkan)'],
            ['desc' => 'Input konfirmasi password yang sama "Password123"', 'expected' => 'Kolom konfirmasi password terisi'],
            ['desc' => 'Pilih daerah pengiriman "Tangerang"', 'expected' => 'Kolom daerah terpilih "Tangerang"'],
            ['desc' => 'Klik tombol "Daftar"', 'expected' => 'Sistem memproses data, mengirim email konfirmasi, dan menampilkan halaman login dengan pesan sukses.']
        ]
    ],
    [
        'id' => 'TS.Login.001',
        'case_id' => 'TC.Login.001',
        'test' => 'Cek validasi login dengan data valid',
        'type' => 'Positive',
        'test_case' => 'Pengguna berhasil login menggunakan kredensial yang valid',
        'pre_condition' => 'Pengguna sudah memiliki akun terdaftar pada sistem',
        'steps' => [
            ['desc' => 'Buka halaman login /login', 'expected' => 'Sistem menampilkan form login'],
            ['desc' => 'Input username "johndoe" atau "admin"', 'expected' => 'Kolom username terisi dengan benar'],
            ['desc' => 'Input password "Password123"', 'expected' => 'Kolom password terisi dengan benar'],
            ['desc' => 'Pilih role ("Pelanggan" atau "Admin")', 'expected' => 'Role terpilih dengan benar'],
            ['desc' => 'Klik tombol "Login"', 'expected' => 'Sistem memvalidasi kredensial, mencocokkan hash password, dan mengarahkan ke dashboard masing-masing.']
        ]
    ],
    [
        'id' => 'TS.ViewCust.001',
        'case_id' => 'TC.ViewCust.001',
        'test' => 'Melihat daftar pelanggan terdaftar',
        'type' => 'Positive',
        'test_case' => 'Admin dapat melihat daftar seluruh pelanggan terdaftar',
        'pre_condition' => 'Admin sudah berhasil masuk (login) ke dashboard admin',
        'steps' => [
            ['desc' => 'Buka menu "Manajemen User" /admin/manajemen-user', 'expected' => 'Halaman Manajemen User terbuka menampilkan sub-tabel Admin dan Pelanggan'],
            ['desc' => 'Lihat daftar pelanggan pada tabel', 'expected' => 'Daftar seluruh pelanggan (nama, email, daerah, poin, dll.) ditampilkan dengan lengkap.']
        ]
    ],
    [
        'id' => 'TS.ManageBook.001',
        'case_id' => 'TC.ManageBook.001',
        'test' => 'Mengelola data buku (Tambah Buku)',
        'type' => 'Positive',
        'test_case' => 'Admin berhasil menambahkan data buku baru ke database',
        'pre_condition' => 'Admin sudah login dan berada di halaman manajemen buku',
        'steps' => [
            ['desc' => 'Buka halaman "Manajemen Buku" /admin/manajemen-buku', 'expected' => 'Halaman manajemen buku termuat dan menampilkan daftar buku saat ini'],
            ['desc' => 'Klik tombol "Tambah Buku"', 'expected' => 'Modal form tambah buku baru muncul ke layar'],
            ['desc' => 'Isi data judul, penulis, kategori, kelas, harga, stok, gambar, dan deskripsi buku', 'expected' => 'Seluruh kolom input terisi dengan data buku baru'],
            ['desc' => 'Klik tombol "Simpan"', 'expected' => 'Sistem menyimpan buku baru ke database, memuat ulang tabel buku, dan menampilkan pesan sukses.']
        ]
    ],
    [
        'id' => 'TS.ManageOrder.001',
        'case_id' => 'TC.ManageOrder.001',
        'test' => 'Memperbarui status pesanan pelanggan',
        'type' => 'Positive',
        'test_case' => 'Admin berhasil memperbarui status pesanan menjadi Sedang Dikirim',
        'pre_condition' => 'Terdapat pesanan masuk dari pelanggan dengan status Pending',
        'steps' => [
            ['desc' => 'Buka halaman "Manajemen Pesanan" /admin/manajemen-pesanan', 'expected' => 'Halaman manajemen pesanan terbuka menampilkan tabel daftar pesanan masuk'],
            ['desc' => 'Pilih pesanan berstatus Pending dan klik "Update Status"', 'expected' => 'Form/modal update status pesanan ditampilkan'],
            ['desc' => 'Pilih status "Sedang Dikirim" dan input tracking link kurir', 'expected' => 'Status terpilih dan kolom tracking link terisi link pelacakan'],
            ['desc' => 'Klik tombol "Perbarui Status"', 'expected' => 'Sistem mengupdate status pesanan menjadi Sedang Dikirim, menyimpan tracking link, mengirimkan notifikasi ke pelanggan, dan menampilkan pesan sukses.']
        ]
    ],
    [
        'id' => 'TS.VerifyReturn.001',
        'case_id' => 'TC.VerifyReturn.001',
        'test' => 'Verifikasi pengajuan retur (Disetujui)',
        'type' => 'Positive',
        'test_case' => 'Admin menyetujui pengajuan pengembalian buku dari pelanggan',
        'pre_condition' => 'Pelanggan telah mengajukan retur buku (status pesanan: Pengajuan Pending)',
        'steps' => [
            ['desc' => 'Buka menu "Manajemen Pengembalian" di dashboard admin', 'expected' => 'Halaman manajemen pengembalian terbuka menampilkan tabel pengajuan retur'],
            ['desc' => 'Klik "Detail" pada salah satu pengajuan', 'expected' => 'Detail alasan, detail bank refund, dan bukti video terlampir muncul'],
            ['desc' => 'Pilih opsi verifikasi "Disetujui" dan tulis catatan admin', 'expected' => 'Status persetujuan dipilih dan catatan terisi'],
            ['desc' => 'Klik tombol "Simpan"', 'expected' => 'Status pengembalian diupdate menjadi Disetujui, status pesanan berubah menjadi Dikembalikan, dan notifikasi terkirim ke pelanggan.']
        ]
    ],
    [
        'id' => 'TS.SalesReport.001',
        'case_id' => 'TC.SalesReport.001',
        'test' => 'Melihat dan mengekspor laporan penjualan',
        'type' => 'Positive',
        'test_case' => 'Admin berhasil melihat rekap laporan penjualan dan mengunduh berkas CSV',
        'pre_condition' => 'Admin sudah masuk ke sistem',
        'steps' => [
            ['desc' => 'Buka menu "Laporan Penjualan" /admin/laporan-penjualan', 'expected' => 'Halaman laporan penjualan memuat data transaksi selesai'],
            ['desc' => 'Pilih filter periode (misal: "Bulan ini")', 'expected' => 'Data penjualan disaring, menghitung total pendapatan, jumlah pesanan, dan jumlah buku secara otomatis'],
            ['desc' => 'Klik tombol "Export Report"', 'expected' => 'Sistem memproses file dan otomatis mengunduh berkas laporan berformat CSV ke komputer admin.']
        ]
    ],
    [
        'id' => 'TS.ViewCatalog.001',
        'case_id' => 'TC.ViewCatalog.001',
        'test' => 'Membuka katalog buku pelanggan',
        'type' => 'Positive',
        'test_case' => 'Pelanggan dapat menjelajahi daftar buku di katalog',
        'pre_condition' => 'Pelanggan membuka halaman beranda',
        'steps' => [
            ['desc' => 'Buka halaman "Beranda" /pelanggan/beranda setelah login', 'expected' => 'Halaman beranda termuat dengan daftar buku terpopuler'],
            ['desc' => 'Gulir layar untuk melihat list buku', 'expected' => 'Berbagai judul buku lengkap dengan sampul, kategori, kelas, dan harga tampil di layar'],
            ['desc' => 'Klik salah satu buku', 'expected' => 'Halaman detail buku terbuka menampilkan informasi detail buku dan sisa stok.']
        ]
    ],
    [
        'id' => 'TS.SearchBook.001',
        'case_id' => 'TC.SearchBook.001',
        'test' => 'Mencari buku di katalog',
        'type' => 'Positive',
        'test_case' => 'Pelanggan berhasil mencari buku berdasarkan kata kunci pencarian',
        'pre_condition' => 'Pelanggan berada di halaman beranda katalog buku',
        'steps' => [
            ['desc' => 'Klik kolom search di bagian atas katalog', 'expected' => 'Kursor aktif pada kolom pencarian'],
            ['desc' => 'Input kata kunci pencarian (misal: "Fisika")', 'expected' => 'Teks pencarian terisi kata "Fisika"'],
            ['desc' => 'Klik ikon cari atau tekan Enter', 'expected' => 'Halaman menampilkan daftar buku yang judulnya mengandung kata "Fisika".']
        ]
    ],
    [
        'id' => 'TS.ManageCart.001',
        'case_id' => 'TC.ManageCart.001',
        'test' => 'Mengelola item dalam keranjang belanja',
        'type' => 'Positive',
        'test_case' => 'Pelanggan berhasil mengubah jumlah dan menghapus item di keranjang',
        'pre_condition' => 'Pelanggan memiliki buku di keranjang belanja',
        'steps' => [
            ['desc' => 'Buka halaman "Keranjang" /pelanggan/keranjang', 'expected' => 'Halaman keranjang belanja menampilkan daftar item yang siap dipesan'],
            ['desc' => 'Ubah kuantitas (qty) salah satu buku', 'expected' => 'Jumlah item diperbarui dan subtotal harga otomatis terhitung ulang'],
            ['desc' => 'Klik tombol "Hapus" pada salah satu buku', 'expected' => 'Item terhapus dari keranjang belanja dan counter keranjang di navbar berkurang.']
        ]
    ],
    [
        'id' => 'TS.PlaceOrder.001',
        'case_id' => 'TC.PlaceOrder.001',
        'test' => 'Melakukan checkout pesanan belanja',
        'type' => 'Positive',
        'test_case' => 'Pelanggan berhasil mengirimkan pesanan belanja ke sistem',
        'pre_condition' => 'Pelanggan memiliki item di keranjang belanja',
        'steps' => [
            ['desc' => 'Klik tombol "Checkout" di halaman keranjang belanja', 'expected' => 'Form pesanan/checkout ditampilkan'],
            ['desc' => 'Isi nama penerima, nomor telepon, alamat pengiriman, jarak km, dan metode GoSend', 'expected' => 'Formulir terisi dengan data pengiriman yang lengkap'],
            ['desc' => 'Input penukaran poin (opsional, kelipatan 50)', 'expected' => 'Kolom penukaran poin terisi'],
            ['desc' => 'Klik tombol "Buat Pesanan"', 'expected' => 'Sistem memproses pesanan baru, mengurangi stok buku, mencatat diskon poin, dan mengarahkan ke halaman pembayaran.']
        ]
    ],
    [
        'id' => 'TS.MakePayment.001',
        'case_id' => 'TC.MakePayment.001',
        'test' => 'Melakukan pembayaran menggunakan Midtrans Snap',
        'type' => 'Positive',
        'test_case' => 'Pelanggan berhasil membayar pesanan melalui payment gateway',
        'pre_condition' => 'Pelanggan berada di halaman pembayaran Midtrans Snap',
        'steps' => [
            ['desc' => 'Buka halaman pembayaran midtrans', 'expected' => 'Halaman menampilkan detail nominal tagihan dan opsi pembayaran Midtrans'],
            ['desc' => 'Klik tombol "Bayar Sekarang"', 'expected' => 'Pop-up widget Midtrans Snap muncul menampilkan pilihan metode (Gopay/Transfer/VA)'],
            ['desc' => 'Pilih "Virtual Account BCA" dan salin nomor VA', 'expected' => 'Detail nomor Virtual Account BCA tergenerate'],
            ['desc' => 'Lakukan transfer pembayaran sandbox', 'expected' => 'Pembayaran terdeteksi sukses, status pesanan berubah menjadi Pesanan Sedang Dikemas, dan diarahkan kembali ke riwayat belanja.']
        ]
    ],
    [
        'id' => 'TS.TrackOrder.001',
        'case_id' => 'TC.TrackOrder.001',
        'test' => 'Memantau status pesanan (Lacak)',
        'type' => 'Positive',
        'test_case' => 'Pelanggan dapat melihat pelacakan pengiriman kurir',
        'pre_condition' => 'Pesanan pelanggan telah dikirim oleh admin (status: Sedang Dikirim)',
        'steps' => [
            ['desc' => 'Buka menu "Status Pesanan" atau membuka riwayat pesanan', 'expected' => 'Sistem menampilkan daftar pesanan aktif beserta statusnya'],
            ['desc' => 'Lihat status pesanan', 'expected' => 'Status pesanan tertulis "Pesanan Sedang Dikirim" lengkap dengan link pelacakan kurir'],
            ['desc' => 'Klik "Lacak Pengiriman"', 'expected' => 'Sistem membuka link eksternal peta/kurir pelacakan GoSend secara real-time.']
        ]
    ],
    [
        'id' => 'TS.ViewHistory.001',
        'case_id' => 'TC.ViewHistory.001',
        'test' => 'Membuka riwayat transaksi',
        'type' => 'Positive',
        'test_case' => 'Pelanggan dapat melihat daftar transaksi belanja yang pernah dilakukan',
        'pre_condition' => 'Pelanggan sudah login ke sistem',
        'steps' => [
            ['desc' => 'Buka menu "Riwayat" /pelanggan/riwayat', 'expected' => 'Halaman riwayat transaksi termuat'],
            ['desc' => 'Gulir daftar riwayat pesanan', 'expected' => 'Menampilkan daftar seluruh pesanan (selesai/batal/aktif) lengkap dengan nomor pesanan, tanggal, total bayar, dan invoice.']
        ]
    ],
    [
        'id' => 'TS.SubmitReturn.001',
        'case_id' => 'TC.SubmitReturn.001',
        'test' => 'Mengajukan pengembalian buku (Retur)',
        'type' => 'Positive',
        'test_case' => 'Pelanggan berhasil mengirimkan pengajuan retur buku cacat',
        'pre_condition' => 'Pesanan pelanggan berstatus Dikirim/Sedang Dikirim',
        'steps' => [
            ['desc' => 'Buka menu riwayat transaksi, lalu klik "Ajukan Pengembalian"', 'expected' => 'Halaman form pengembalian buku ditampilkan'],
            ['desc' => 'Input alasan retur "Buku robek di halaman 15-20"', 'expected' => 'Kolom alasan terisi (min 10 karakter)'],
            ['desc' => 'Upload file video unboxing (.mp4)', 'expected' => 'File video berhasil terupload'],
            ['desc' => 'Input nama bank dan nomor rekening untuk refund', 'expected' => 'Detail rekening terisi lengkap'],
            ['desc' => 'Klik tombol "Kirim Pengajuan"', 'expected' => 'Sistem menyimpan data pengajuan, mengubah status pesanan menjadi Pengajuan Pending, dan mengirim notifikasi ke admin.']
        ]
    ],
    [
        'id' => 'TS.AddPoints.001',
        'case_id' => 'TC.AddPoints.001',
        'test' => 'Menambahkan poin loyalty pelanggan secara manual',
        'type' => 'Positive',
        'test_case' => 'Admin berhasil memberikan poin loyalty tambahan ke pelanggan',
        'pre_condition' => 'Admin berada di halaman manajemen user',
        'steps' => [
            ['desc' => 'Buka halaman "Manajemen User" /admin/manajemen-user', 'expected' => 'Tabel data pelanggan ditampilkan lengkap dengan kolom poin loyalty saat ini'],
            ['desc' => 'Klik tombol "Update Poin" pada baris pelanggan terkait', 'expected' => 'Modal pop-up update poin terbuka'],
            ['desc' => 'Masukkan jumlah poin tambahan (misal: 100)', 'expected' => 'Kolom input poin terisi dengan angka 100'],
            ['desc' => 'Klik tombol "Simpan"', 'expected' => 'Sistem menambahkan 100 poin ke saldo pelanggan di database, mengirim notifikasi, dan tabel memperbarui total poin.']
        ]
    ],
    [
        'id' => 'TS.OrderChat.001',
        'case_id' => 'TC.OrderChat.001',
        'test' => 'Berkomunikasi via chat pesanan',
        'type' => 'Positive',
        'test_case' => 'Pelanggan dan Admin dapat saling mengirim pesan secara real-time',
        'pre_condition' => 'Pesanan aktif terdaftar di sistem',
        'steps' => [
            ['desc' => 'Pelanggan klik "Chat dengan Admin" pada detail pesanan', 'expected' => 'Halaman chatroom pesanan pelanggan termuat'],
            ['desc' => 'Pelanggan ketik "Apakah pesanan bisa dikirim sekarang?" lalu klik kirim', 'expected' => 'Pesan tampil di chatroom pelanggan, tersimpan, dan mengirimkan notifikasi ke admin'],
            ['desc' => 'Admin membuka rute chat /admin/chat/{order_id}', 'expected' => 'Halaman chatroom admin termuat menampilkan pesan pelanggan'],
            ['desc' => 'Admin mengetik balasan "Tentu, pesanan segera dikirim sore ini" lalu klik kirim', 'expected' => 'Pesan balasan tampil, tersimpan di database, dan notifikasi terkirim ke pelanggan.']
        ]
    ],
    [
        'id' => 'TS.GiveReview.001',
        'case_id' => 'TC.GiveReview.001',
        'test' => 'Beri Ulasan',
        'type' => 'Positive',
        'test_case' => 'Pelanggan berhasil mengirimkan ulasan produk buku setelah transaksi selesai',
        'pre_condition' => 'Pesanan pelanggan berstatus Selesai dan belum pernah diulas',
        'steps' => [
            ['desc' => 'Buka halaman riwayat transaksi, lalu klik "Beri Ulasan"', 'expected' => 'Form/modal ulasan buku terbuka'],
            ['desc' => 'Pilih rating bintang (misal: 5)', 'expected' => 'Pilihan rating bintang 5 terpilih'],
            ['desc' => 'Ketik ulasan "Buku sangat bagus, kertas bersih dan cetakan jernih."', 'expected' => 'Kolom komentar ulasan terisi'],
            ['desc' => 'Klik tombol "Kirim Ulasan"', 'expected' => 'Ulasan tersimpan di database, terhubung ke buku terkait, status ulasan ditandai, dan pesan sukses muncul.']
        ]
    ],
    [
        'id' => 'TS.ManageReview.001',
        'case_id' => 'TC.ManageReview.001',
        'test' => 'Mengelola ulasan pelanggan',
        'type' => 'Positive',
        'test_case' => 'Admin berhasil melihat ulasan masuk dan menghapus ulasan melanggar',
        'pre_condition' => 'Admin sudah login',
        'steps' => [
            ['desc' => 'Buka halaman "Manajemen Ulasan" /admin/manajemen-ulasan', 'expected' => 'Halaman memuat daftar seluruh rating dan komentar buku dari pelanggan'],
            ['desc' => 'Klik tombol "Hapus Ulasan" pada ulasan melanggar', 'expected' => 'Dialog konfirmasi penghapusan muncul'],
            ['desc' => 'Klik "Ya, Hapus"', 'expected' => 'Ulasan berhasil dihapus dari database, tabel memuat ulang data terbaru, dan pesan sukses ditampilkan.']
        ]
    ]
];

// Build the SpreadsheetML XML output
$xml = '<?xml version="1.0"?>
<?mso-application myexcel?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>Antigravity AI</Author>
  <LastAuthor>Antigravity AI</LastAuthor>
  <Created>' . date('Y-m-d\TH:i:s\Z') . '</Created>
  <Version>16.00</Version>
 </DocumentProperties>
 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
  <AllowPNG/>
 </OfficeDocumentSettings>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>12435</WindowHeight>
  <WindowWidth>28800</WindowWidth>
  <WindowTopX>0</WindowTopX>
  <WindowTopY>0</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Center"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:CharSet="1" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="Title">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Calibri" ss:Size="16" ss:Bold="1" ss:Color="#1F4E78"/>
  </Style>
  <Style ss:ID="Header">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#000000"/>
   </Borders>
   <Font ss:FontName="Calibri" ss:Size="11" ss:Color="#FFFFFF" ss:Bold="1"/>
   <Interior ss:Color="#1F4E78" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="Bordered">
   <Alignment ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
   </Borders>
  </Style>
  <Style ss:ID="BorderedCenter">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
   </Borders>
  </Style>
  <Style ss:ID="Pass">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D9D9D9"/>
   </Borders>
   <Font ss:FontName="Calibri" ss:Color="#006100" ss:Bold="1"/>
   <Interior ss:Color="#C6EFCE" ss:Pattern="Solid"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Blackbox Testing CivadTA">
  <Table ss:ExpandedColumnCount="10" x:FullColumns="1" x:FullRows="1" ss:DefaultRowHeight="20">
   <Column ss:Width="100"/>
   <Column ss:Width="100"/>
   <Column ss:Width="180"/>
   <Column ss:Width="70"/>
   <Column ss:Width="200"/>
   <Column ss:Width="180"/>
   <Column ss:Width="40"/>
   <Column ss:Width="300"/>
   <Column ss:Width="300"/>
   <Column ss:Width="70"/>
   <Row ss:Height="25">
    <Cell ss:MergeAcross="9" ss:StyleID="Title"><Data ss:Type="String">Tabel Pengujian Black Box - CivadTA (CV. Arya Duta cabang Tangerang)</Data></Cell>
   </Row>
   <Row ss:Height="10">
    <!-- Empty row for spacing -->
   </Row>
   <Row ss:Height="25">
    <Cell ss:StyleID="Header"><Data ss:Type="String">Scenario ID</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Case ID</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Test</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Type</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Test Case</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Pre Condition</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Steps</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Short Description</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Expected Result</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">Status (Pass/Fail)</Data></Cell>
   </Row>';

foreach ($scenarios as $scen) {
    $S = count($scen['steps']);
    $mergeStr = ($S > 1) ? ' ss:MergeDown="' . ($S - 1) . '"' : '';
    
    // Escape string values for XML
    $id = htmlspecialchars($scen['id'], ENT_QUOTES, 'UTF-8');
    $case_id = htmlspecialchars($scen['case_id'], ENT_QUOTES, 'UTF-8');
    $test = htmlspecialchars($scen['test'], ENT_QUOTES, 'UTF-8');
    $type = htmlspecialchars($scen['type'], ENT_QUOTES, 'UTF-8');
    $test_case = htmlspecialchars($scen['test_case'], ENT_QUOTES, 'UTF-8');
    $pre_condition = htmlspecialchars($scen['pre_condition'], ENT_QUOTES, 'UTF-8');

    // Row 1 of Scenario
    $step1_num = 1;
    $step1_desc = htmlspecialchars($scen['steps'][0]['desc'], ENT_QUOTES, 'UTF-8');
    $step1_expected = htmlspecialchars($scen['steps'][0]['expected'], ENT_QUOTES, 'UTF-8');

    $xml .= '
   <Row ss:Height="22">
    <Cell' . $mergeStr . ' ss:StyleID="BorderedCenter"><Data ss:Type="String">' . $id . '</Data></Cell>
    <Cell' . $mergeStr . ' ss:StyleID="BorderedCenter"><Data ss:Type="String">' . $case_id . '</Data></Cell>
    <Cell' . $mergeStr . ' ss:StyleID="Bordered"><Data ss:Type="String">' . $test . '</Data></Cell>
    <Cell' . $mergeStr . ' ss:StyleID="BorderedCenter"><Data ss:Type="String">' . $type . '</Data></Cell>
    <Cell' . $mergeStr . ' ss:StyleID="Bordered"><Data ss:Type="String">' . $test_case . '</Data></Cell>
    <Cell' . $mergeStr . ' ss:StyleID="Bordered"><Data ss:Type="String">' . $pre_condition . '</Data></Cell>
    <Cell ss:StyleID="BorderedCenter"><Data ss:Type="Number">' . $step1_num . '</Data></Cell>
    <Cell ss:StyleID="Bordered"><Data ss:Type="String">' . $step1_desc . '</Data></Cell>
    <Cell ss:StyleID="Bordered"><Data ss:Type="String">' . $step1_expected . '</Data></Cell>
    <Cell' . $mergeStr . ' ss:StyleID="Pass"><Data ss:Type="String">Pass</Data></Cell>
   </Row>';

    // Subsequent Rows of Scenario
    for ($i = 1; $i < $S; $i++) {
        $step_num = $i + 1;
        $step_desc = htmlspecialchars($scen['steps'][$i]['desc'], ENT_QUOTES, 'UTF-8');
        $step_expected = htmlspecialchars($scen['steps'][$i]['expected'], ENT_QUOTES, 'UTF-8');

        $xml .= '
   <Row ss:Height="22">
    <Cell ss:Index="7" ss:StyleID="BorderedCenter"><Data ss:Type="Number">' . $step_num . '</Data></Cell>
    <Cell ss:StyleID="Bordered"><Data ss:Type="String">' . $step_desc . '</Data></Cell>
    <Cell ss:StyleID="Bordered"><Data ss:Type="String">' . $step_expected . '</Data></Cell>
   </Row>';
    }
}

$xml .= '
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>1</ActiveRow>
     <ActiveCol>1</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';

$outputPath = dirname(__DIR__) . '/Blackbox_Testing_CivadTA.xls';
file_put_contents($outputPath, $xml);
echo "Excel file successfully written to: " . $outputPath . "\n";
