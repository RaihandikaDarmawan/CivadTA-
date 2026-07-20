# Dokumen Test Case: Mengajukan Pengembalian Buku (Return)

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Mengajukan Pengembalian Buku (Return)** pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail sesuai dengan data dan logika proyek.

## Aturan Validasi Input (Input Validation Rules)

Sebelum data pengajuan pengembalian diproses oleh backend (`/pelanggan/pengembalian/simpan`), sistem menerapkan aturan validasi ketat sebagai berikut:

1. **Kelayakan Status Pesanan**:
   - **Aturan**: Pengembalian hanya dapat diajukan untuk pesanan dengan status **Dikirim**, **Sedang Dikirim**, atau **Pesanan Sedang Dikirim**.
   - **Pelanggaran**: Mencoba mengakses form pengembalian untuk pesanan berstatus `Pending` atau `Pesanan Sedang Dikemas`.
   - **Dampak/Error**: Sistem memblokir dan mengembalikan error `404 | NOT FOUND`.

2. **Pengajuan Tunggal**:
   - **Aturan**: Satu pesanan hanya dapat diajukan pengembalian sekali.
   - **Pelanggaran**: Mengakses form atau mengirimkan data pengembalian untuk pesanan yang sudah memiliki pengajuan retur aktif.
   - **Dampak/Error**: Sistem mengalihkan kembali ke riwayat dengan pesan error: `Pengajuan pengembalian untuk pesanan ini sudah dibuat.`

3. **Alasan Pengembalian (`reason`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data *string*, **minimal 10 karakter**.
   - **Pelanggaran**: Dikosongkan, atau diisi kurang dari 10 karakter (misal: "Rusak").
   - **Dampak/Error**: Validasi menolak pengajuan dengan pesan: `Alasan pengembalian wajib diisi.` atau `Alasan pengembalian minimal harus 10 karakter.`

4. **Bukti Video (`video_proof`)**:
   - **Aturan**: Wajib diunggah (*required*), berkas video berformat `mp4`, `mov`, `avi`, atau `webm`, **ukuran maksimal 50 MB (51200 KB)**.
   - **Pelanggaran**:
     - Tidak mengunggah video: `Bukti video wajib diunggah.`
     - Format salah (misal: gambar/PDF): `Format video harus berupa mp4, mov, avi, atau webm.`
     - Ukuran > 50 MB: `Video gagal diunggah karena ukuran file melebihi 50 MB`

5. **Nama Bank (`bank_name`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data *string*, **minimal 3 karakter**.
   - **Pelanggaran**: Dikosongkan, atau diisi kurang dari 3 karakter.
   - **Dampak/Error**: Validasi menolak dengan pesan: `Nama bank wajib diisi.` atau `Nama bank minimal harus 3 karakter.`

6. **Nomor Rekening (`bank_account_number`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data **numerik (angka saja)**, panjang **antara 10 hingga 16 digit**.
   - **Pelanggaran**: Dikosongkan, berisi huruf/simbol, atau panjang digit di luar rentang 10-16.
   - **Dampak/Error**: Validasi menolak dengan pesan: `Nomor rekening wajib diisi.` atau `Nomor rekening harus berupa angka dengan jumlah digit 10-16.`

---

## Tabel Test Case Mengajukan Pengembalian Buku

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Return.001** | TC.Return.001 | Mengajukan pengembalian dengan seluruh data valid | Positive | Equivalence Partitioning (EP) | Pelanggan berhasil mengajukan pengembalian buku dan status pesanan berubah menjadi "Pengajuan Pending" | Pelanggan memiliki pesanan berstatus "Dikirim" dan berada di halaman form pengembalian `/pelanggan/pengembalian/buat?order_id={id}` | 1. Input alasan "Buku robek di bagian sampul belakang"<br>2. Unggah video bukti valid berukuran 10MB (.mp4)<br>3. Input nama bank "BCA"<br>4. Input nomor rekening "1234567890"<br>5. Klik tombol "Kirim Pengajuan" | 1. Mengisi alasan valid (34 karakter)<br>2. Mengunggah video mp4 10MB<br>3. Mengisi nama bank valid (3 karakter)<br>4. Mengisi rekening valid (10 digit)<br>5. Mengklik tombol kirim | 1. Seluruh kolom formulir terisi valid<br>2. Sistem menyimpan pengajuan dengan status "Pending"<br>3. Status pesanan di database berubah menjadi "Pengajuan Pending"<br>4. Sistem mengirim notifikasi ke admin dan mengalihkan pelanggan ke halaman riwayat dengan pesan sukses: `Pengajuan pengembalian berhasil dikirim! Menunggu konfirmasi admin.` | Pass |
| **TS.Return.002** | TC.Return.002 | Mengajukan pengembalian untuk pesanan yang belum dikirim | Negative | Decision Table | Sistem memblokir akses ke form pengembalian dan mengembalikan error 404 | Pelanggan memiliki pesanan berstatus "Pesanan Sedang Dikemas" | 1. Buka URL form pengembalian secara langsung dengan ID pesanan terkait, misal `/pelanggan/pengembalian/buat?order_id=15` | 1. Mengakses URL form pengembalian dengan ID pesanan belum dikirim | 1. Browser mengirimkan permintaan GET ke `/pelanggan/pengembalian/buat?order_id=15`<br>2. Sistem menyaring database menggunakan `firstOrFail()` dengan syarat status harus `Dikirim`, `Sedang Dikirim`, atau `Pesanan Sedang Dikirim`, dan karena syarat tidak terpenuhi, sistem mengembalikan halaman error 404 (Not Found). | Pass |
| **TS.Return.003** | TC.Return.003 | Pengembalian ditolak karena ukuran video melebihi 50 MB | Negative | Boundary Value Analysis (BVA) | Sistem menolak pengajuan karena ukuran berkas video melebihi batas maksimum (50 MB) | Pelanggan berada di halaman form pengembalian | 1. Input alasan "Halaman buku kosong di bab 3"<br>2. Unggah video bukti berukuran 51 MB (.mp4)<br>3. Isi kolom bank dan rekening dengan data valid<br>4. Klik tombol "Kirim Pengajuan" | 1. Mengisi alasan valid<br>2. Mengunggah video berukuran 51 MB (melebihi batas)<br>3. Mengisi data bank valid<br>4. Mengklik tombol kirim | 1. Alasan dan data bank terisi valid<br>2. Berkas video terunggah melebihi batas<br>3. Pengajuan ditolak, sistem kembali ke halaman form pengembalian dan menampilkan pesan error: `Video gagal diunggah karena ukuran file melebihi 50 MB` | Pass |
| **TS.Return.004** | TC.Return.004 | Pengembalian ditolak karena format video tidak valid | Negative | Equivalence Partitioning (EP) | Sistem menolak pengajuan karena format berkas bukan video | Pelanggan berada di halaman form pengembalian | 1. Input alasan "Buku basah terkena air"<br>2. Unggah berkas gambar fiktif, misal "bukti.jpg" atau "bukti.pdf" sebagai bukti video<br>3. Isi kolom bank dan rekening dengan data valid<br>4. Klik tombol "Kirim Pengajuan" | 1. Mengisi alasan valid<br>2. Mengunggah berkas format jpg/pdf<br>3. Mengisi data bank valid<br>4. Mengklik tombol kirim | 1. Berkas non-video terunggah<br>2. Pengajuan ditolak, sistem kembali ke halaman form pengembalian dan menampilkan pesan error: `Format video harus berupa mp4, mov, avi, atau webm.` | Pass |
| **TS.Return.005** | TC.Return.005 | Pengembalian ditolak karena alasan kurang dari 10 karakter | Negative | Boundary Value Analysis (BVA) | Sistem menolak pengajuan karena alasan terlalu pendek | Pelanggan berada di halaman form pengembalian | 1. Input alasan pendek 9 karakter, misal "Buku rusak"<br>2. Unggah video bukti valid (.mp4)<br>3. Isi kolom bank dan rekening dengan data valid<br>4. Klik tombol "Kirim Pengajuan" | 1. Mengisi alasan kurang dari 10 karakter<br>2. Mengunggah video valid<br>3. Mengisi data bank valid<br>4. Mengklik tombol kirim | 1. Kolom alasan berisi "Buku rusak"<br>2. Kolom lain valid<br>3. Pengajuan ditolak, sistem kembali ke halaman form pengembalian dan menampilkan pesan error: `Alasan pengembalian minimal harus 10 karakter.` | Pass |
| **TS.Return.006** | TC.Return.006 | Pengembalian ditolak karena nomor rekening mengandung karakter non-angka atau di luar 10-16 digit | Negative | Boundary Value Analysis (BVA) | Sistem menolak pengajuan karena format atau panjang digit nomor rekening salah | Pelanggan berada di halaman form pengembalian | 1. Input alasan valid<br>2. Unggah video bukti valid (.mp4)<br>3. Input nama bank "BCA"<br>4. Input nomor rekening yang salah, misal "1234a567" (mengandung huruf) atau "123456789" (9 digit)<br>5. Klik tombol "Kirim Pengajuan" | 1. Mengisi data alasan dan video valid<br>2. Mengisi nama bank valid<br>3. Mengisi nomor rekening tidak valid<br>4. Mengklik tombol kirim | 1. Kolom rekening terisi tidak valid<br>2. Pengajuan ditolak, sistem kembali ke halaman form pengembalian dan menampilkan pesan error: `Nomor rekening harus berupa angka dengan jumlah digit 10-16.` | Pass |
| **TS.Return.007** | TC.Return.007 | Mengajukan pengembalian ganda untuk pesanan yang sama | Negative | Decision Table | Sistem menolak pengajuan kedua kali dan mengalihkan kembali ke riwayat dengan pesan error | Pelanggan sudah memiliki pengajuan pengembalian aktif (status pesanan sudah "Pengajuan Pending") | 1. Coba kirim data pengembalian POST ke `/pelanggan/pengembalian/simpan` untuk pesanan yang sama | 1. Mengirim request pengajuan kembali untuk pesanan yang sudah berstatus retur | 1. Sistem mendeteksi bahwa pengajuan untuk pesanan ini sudah ada (`$order->returnRequest` bernilai true)<br>2. Sistem menolak pemrosesan dan mengalihkan kembali ke `/pelanggan/riwayat` dengan pesan error: `Pengajuan pengembalian untuk pesanan ini sudah dibuat.` | Pass |
