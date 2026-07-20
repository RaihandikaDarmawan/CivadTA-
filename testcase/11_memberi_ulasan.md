# Dokumen Test Case: Memberi Ulasan Pelanggan

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Memberi Ulasan Pelanggan** pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail sesuai dengan data dan logika proyek.

## Aturan Validasi Input (Input Validation Rules)

Sebelum ulasan dikirim ke database melalui method POST ke `/pelanggan/ulasan/simpan` (atau `/pelanggan/ulasan/store`), sistem menerapkan aturan validasi ketat sebagai berikut:

1. **Kelayakan Status Pesanan**:
   - **Aturan**: Ulasan hanya dapat diberikan untuk pesanan yang berstatus **Selesai** (`status = 'Selesai'`).
   - **Pelanggaran**: Mencoba mengirimkan ulasan untuk pesanan yang statusnya masih `Pending`, `Pesanan Sedang Dikemas`, atau `Dikirim`.
   - **Dampak/Error**: Sistem memblokir dan mengembalikan error halaman `404 | NOT FOUND` (karena query menggunakan `where('status', 'Selesai')->firstOrFail()`).

2. **Pengajuan Tunggal (Satu Ulasan per Pesanan)**:
   - **Aturan**: Satu pesanan hanya dapat diulas sekali.
   - **Pelanggaran**: Mengirimkan ulasan lagi untuk pesanan yang sudah pernah diulas.
   - **Dampak/Error**: Sistem menolak ulasan baru dan mengalihkan kembali ke halaman sebelumnya dengan pesan error session: `Ulasan untuk pesanan ini sudah diisi.`

3. **Kualitas Rating (`rating`)**:
   - **Aturan**: Wajib diisi (*required*), bertipe data integer, nilai minimal **1** dan maksimal **5** (bintang 1 sampai 5).
   - **Pelanggaran**: Menginput nilai rating di luar rentang (misalnya: 0 atau 6).
   - **Dampak/Error**: Sistem menolak ulasan melalui validasi request Laravel.

4. **Komentar Ulasan (`comment`)**:
   - **Aturan**: Wajib diisi (*required*), minimal harus **5 karakter** dan maksimal 1000 karakter.
   - **Pelanggaran**: Mengosongkan ulasan atau mengisi ulasan hanya dengan 4 karakter atau kurang (misal: "Good").
   - **Dampak/Error**: Validasi menolak pengiriman ulasan dengan pesan error: `komentar minimal terdiri dari 5 karakter.`

---

## Tabel Test Case Memberi Ulasan Pelanggan

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Review.001** | TC.Review.001 | Mengirim ulasan dengan data lengkap dan valid | Positive | Equivalence Partitioning (EP) | Pelanggan berhasil memberikan ulasan (rating dan komentar) untuk pesanan selesai | Pelanggan sudah login, memiliki pesanan berstatus "Selesai", dan belum pernah mengulas pesanan tersebut | 1. Buka halaman riwayat belanja `/pelanggan/riwayat`<br>2. Klik tombol "Ulas" pada pesanan selesai<br>3. Pilih rating "5" (bintang 5)<br>4. Isi komentar "Buku Fisika ini sangat lengkap, cetakannya jelas dan rapi!"<br>5. Klik tombol "Kirim Ulasan" | 1. Membuka detail riwayat pesanan selesai<br>2. Mengklik tombol ulas<br>3. Memilih bintang 5<br>4. Mengisi ulasan valid (60 karakter)<br>5. Mengklik tombol kirim | 1. Kolom rating terisi 5 dan komentar valid terisi<br>2. Sistem menyimpan ulasan di database<br>3. Sistem mengirim notifikasi ulasan ke admin dan mengalihkan kembali dengan pesan sukses: `Ulasan berhasil dikirim!` | Pass |
| **TS.Review.002** | TC.Review.002 | Mengirim ulasan tanpa melakukan login | Negative | Use Case Testing | Sistem memblokir aksi dan mengalihkan pengguna ke halaman landing `/` | Pengguna berstatus Guest (belum login) | 1. Kirim request POST ke `/pelanggan/ulasan/store` secara langsung tanpa login dengan menyertakan payload data ulasan | 1. Mengirim request POST ulasan tanpa sesi login | 1. Sistem mendeteksi tidak ada sesi aktif (`session('role') !== 'pelanggan'`) dan memblokir request<br>2. Pengguna dialihkan ke halaman landing `/` dengan pesan error: `Akses ditolak!` | Pass |
| **TS.Review.003** | TC.Review.003 | Ulasan ditolak karena komentar kurang dari 5 karakter | Negative | Boundary Value Analysis (BVA) | Sistem menolak ulasan karena komentar terlalu pendek | Pelanggan berada di modal/form ulasan pesanan | 1. Pilih rating "4"<br>2. Isi komentar "Bagus" (5 karakter dikurangi 1 = 4 karakter)<br>3. Klik tombol "Kirim Ulasan" | 1. Memilih bintang 4<br>2. Mengisi komentar 4 karakter<br>3. Mengklik tombol kirim | 1. Kolom komentar terisi "Bagus"<br>2. Pengajuan ditolak, sistem kembali ke halaman sebelumnya dan memunculkan pesan error: `komentar minimal terdiri dari 5 karakter.` | Pass |
| **TS.Review.004** | TC.Review.004 | Ulasan ditolak karena rating di luar batas valid (rating 0 atau 6) | Negative | Boundary Value Analysis (BVA) | Sistem menolak ulasan karena rating tidak valid | Pelanggan berada di modal/form ulasan pesanan | 1. Manipulasi input/kirim request ulasan dengan nilai rating "6" atau "0"<br>2. Isi komentar dengan data valid<br>3. Kirim ulasan | 1. Mengirim request ulasan dengan rating di luar batas 1-5<br>2. Mengklik tombol kirim | 1. Nilai rating di luar rentang valid<br>2. Sistem mendeteksi pelanggaran validasi request (`rating.min` atau `rating.max`), menolak penyimpanan ulasan, dan kembali ke halaman sebelumnya. | Pass |
| **TS.Review.005** | TC.Review.005 | Mengulas pesanan yang statusnya belum selesai | Negative | Decision Table | Sistem memblokir aksi ulasan dan mengembalikan error 404 | Pelanggan memiliki pesanan aktif berstatus "Pesanan Sedang Dikemas" | 1. Kirim request POST ulasan ke `/pelanggan/ulasan/store` dengan menyertakan ID pesanan yang masih dikemas | 1. Mengirim request ulasan untuk pesanan belum selesai | 1. Sistem menyaring database menggunakan `firstOrFail()` dengan syarat status harus `Selesai`, dan karena syarat tidak terpenuhi, sistem mengembalikan halaman error 404 (Not Found). | Pass |
| **TS.Review.006** | TC.Review.006 | Mengirim ulasan ganda untuk pesanan yang sama | Negative | Decision Table | Sistem menolak ulasan kedua dan kembali dengan pesan ulasan sudah diisi | Pelanggan sudah pernah mengulas pesanan tersebut | 1. Kirim request POST ulasan kembali untuk ID pesanan selesai yang sama | 1. Mengirim request ulasan kedua kali untuk pesanan yang sama | 1. Sistem mendeteksi ulasan untuk pesanan tersebut sudah tercatat di database (`$order->review` bernilai true)<br>2. Sistem membatalkan penyimpanan ulasan baru dan mengalihkan kembali dengan pesan error: `Ulasan untuk pesanan ini sudah diisi.` | Pass |
