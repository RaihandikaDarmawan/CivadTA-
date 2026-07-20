# Dokumen Test Case: Melakukan Pemesanan (Checkout)

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Melakukan Pemesanan (Checkout)** pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail sesuai dengan data dan logika proyek.

## Aturan Validasi Input (Input Validation Rules)

Sebelum pesanan dapat dikonfirmasi untuk pembayaran, sistem menerapkan aturan validasi pada form checkout (diarahkan melalui POST ke `/pelanggan/konfirmasi-pembayaran` atau `/pelanggan/pembayaran`):

1. **Nama Penerima (`recipient_name`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data *string*, **minimal 3 karakter**, maksimal 255 karakter.
   - **Pelanggaran**: Dikosongkan, atau diisi kurang dari 3 karakter (misal: 2 karakter).
   - **Dampak/Error**: Validasi menolak proses checkout dengan pesan error: `Nama penerima wajib diisi.` atau `Nama penerima minimal harus 3 karakter.`

2. **Nomor Handphone (`phone_number`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data numerik (digit saja), **panjang antara 10 hingga 13 digit**.
   - **Pelanggaran**: Dikosongkan, diisi kurang dari 10 digit (misal: 9 digit), atau diisi lebih dari 13 digit (misal: 14 digit).
   - **Dampak/Error**: Validasi menolak dengan pesan error: `Nomor handphone wajib diisi.` atau `pastikan nomor anda minimal 10-13 digit`

3. **Alamat Lengkap (`address`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data *string*, **minimal 5 karakter**.
   - **Pelanggaran**: Dikosongkan, atau diisi kurang dari 5 karakter.
   - **Dampak/Error**: Validasi menolak dengan pesan error: `Alamat lengkap wajib diisi.` atau `Alamat minimal harus 5 karakter.`

4. **Jarak Pengiriman (`distance_km`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data integer positif, **minimal 1 km**.
   - **Pelanggaran**: Mengisi jarak 0 km atau kurang.
   - **Dampak/Error**: Validasi menolak dengan pesan error default dari Laravel.

5. **Layanan Pengiriman (`shipping_service`)**:
   - **Aturan**: Wajib dipilih (*required*), tipe data *string*, bernilai salah satu dari: `GoSend Same Day` atau `GoSend Instant`.
   - **Pelanggaran**: Tidak memilih opsi pengiriman atau memilih di luar opsi valid.
   - **Dampak/Error**: Validasi menolak dengan pesan error: `Opsi pengiriman wajib dipilih.` atau `Opsi pengiriman tidak valid.`

---

## Tabel Test Case Melakukan Pemesanan (Checkout)

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Order.001** | TC.Order.001 | Proses checkout dengan seluruh data valid | Positive | Equivalence Partitioning (EP) | Pelanggan berhasil mengisi data pengiriman dan diarahkan ke proses pembayaran | Pelanggan memiliki item di keranjang dan berada di form checkout `/pelanggan/pesanan` | 1. Input nama penerima "John Doe"<br>2. Input nomor telepon "081234567890"<br>3. Input alamat "Jl. Raya Tangerang No. 45"<br>4. Masukkan jarak "5" km<br>5. Pilih layanan "GoSend Same Day"<br>6. Klik tombol "Proses Pembayaran" | 1. Mengisi nama penerima valid<br>2. Mengisi nomor telepon valid (12 digit)<br>3. Mengisi alamat valid (24 karakter)<br>4. Memasukkan jarak 5 km<br>5. Memilih GoSend Same Day<br>6. Mengklik tombol bayar | 1. Seluruh kolom formulir terisi valid<br>2. Sistem memproses ongkos kirim GoSend Same Day untuk jarak 5 km (tarif Rp 18.000)<br>3. Pemesanan dicatat di database dengan status "Pending"<br>4. Sistem berhasil memproses Snap Token Midtrans dan mengalihkan pelanggan ke halaman pembayaran dengan widget Snap. | Pass |
| **TS.Order.002** | TC.Order.002 | Checkout dengan nama penerima kurang dari 3 karakter | Negative | Boundary Value Analysis (BVA) | Checkout ditolak karena nama penerima kurang dari batas minimum (3 karakter) | Pelanggan berada di form checkout | 1. Input nama penerima 2 karakter, misal "ij"<br>2. Isi kolom lainnya dengan data valid<br>3. Klik tombol "Proses Pembayaran" | 1. Mengisi nama penerima 2 karakter<br>2. Mengisi kolom data lainnya<br>3. Mengklik tombol bayar | 1. Kolom nama berisi "ij"<br>2. Kolom lain valid<br>3. Checkout ditolak, sistem kembali ke halaman checkout dan menampilkan pesan error: `Nama penerima minimal harus 3 karakter.` | Pass |
| **TS.Order.003** | TC.Order.003 | Checkout dengan alamat kurang dari 5 karakter | Negative | Boundary Value Analysis (BVA) | Checkout ditolak karena alamat kurang dari batas minimum (5 karakter) | Pelanggan berada di form checkout | 1. Input alamat 4 karakter, misal "Home"<br>2. Isi kolom lainnya dengan data valid<br>3. Klik tombol "Proses Pembayaran" | 1. Mengisi alamat 4 karakter<br>2. Mengisi kolom data lainnya<br>3. Mengklik tombol bayar | 1. Kolom alamat berisi "Home"<br>2. Kolom lain valid<br>3. Checkout ditolak, sistem kembali ke halaman checkout dan menampilkan pesan error: `Alamat minimal harus 5 karakter.` | Pass |
| **TS.Order.004** | TC.Order.004 | Checkout dengan nomor telepon kurang dari 10 digit | Negative | Boundary Value Analysis (BVA) | Checkout ditolak karena nomor telepon kurang dari batas minimum (10 digit) | Pelanggan berada di form checkout | 1. Input nomor telepon 9 digit, misal "081234567"<br>2. Isi kolom lainnya dengan data valid<br>3. Klik tombol "Proses Pembayaran" | 1. Mengisi nomor telepon 9 digit<br>2. Mengisi kolom data lainnya<br>3. Mengklik tombol bayar | 1. Kolom telepon berisi "081234567"<br>2. Kolom lain valid<br>3. Checkout ditolak, sistem kembali ke halaman checkout dan menampilkan pesan error: `nomor telepon harus terdiri dari 10-13 digit` | Pass |
| **TS.Order.005** | TC.Order.005 | Checkout dengan nomor telepon lebih dari 13 digit | Negative | Boundary Value Analysis (BVA) | Checkout ditolak karena nomor telepon melebihi batas maksimum (13 digit) | Pelanggan berada di form checkout | 1. Input nomor telepon 14 digit, misal "08123456789012"<br>2. Isi kolom lainnya dengan data valid<br>3. Klik tombol "Proses Pembayaran" | 1. Mengisi nomor telepon 14 digit<br>2. Mengisi kolom data lainnya<br>3. Mengklik tombol bayar | 1. Kolom telepon berisi "08123456789012"<br>2. Kolom lain valid<br>3. Checkout ditolak, sistem kembali ke halaman checkout dan menampilkan pesan error: `nomor telepon harus terdiri dari 10-13 digit` | Pass |
| **TS.Order.006** | TC.Order.006 | Checkout dengan jarak pengiriman kurang dari 1 km (0 km) | Negative | Boundary Value Analysis (BVA) | Checkout ditolak karena jarak pengiriman berada di bawah batas minimum (1 km) | Pelanggan berada di form checkout | 1. Input jarak pengiriman "0" km<br>2. Isi kolom lainnya dengan data valid<br>3. Klik tombol "Proses Pembayaran" | 1. Mengisi jarak 0 km<br>2. Mengisi kolom data lainnya<br>3. Mengklik tombol bayar | 1. Kolom jarak terisi 0<br>2. Kolom lain valid<br>3. Checkout ditolak oleh browser/sistem karena jarak berada di bawah batas minimum (1 km). | Pass |
