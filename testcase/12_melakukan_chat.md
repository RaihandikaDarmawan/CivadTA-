# Dokumen Test Case: Melakukan Chat (Chatting)

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Melakukan Chat (Chatting)** antara Pelanggan dan Admin pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail sesuai dengan data dan logika proyek.

## Aturan Validasi Input (Input Validation Rules)

Sebelum pesan chat dikirim ke database via POST ke `/pelanggan/chat/{order_id}/send` (atau `/admin/chat/{order_id}/send`), sistem menerapkan aturan validasi ketat sebagai berikut:

1. **Status Pesanan Aktif**:
   - **Aturan**: Chat hanya dapat diakses dan digunakan untuk pesanan yang belum diselesaikan (status selain **Selesai**).
   - **Pelanggaran**: Mencoba mengakses halaman chat atau mengirim pesan untuk pesanan yang statusnya sudah `Selesai`.
   - **Dampak/Error**: 
     - **Web view**: Sistem menolak akses dan mengalihkan pengguna kembali dengan pesan error session: `Chat tidak tersedia untuk pesanan yang telah selesai.`
     - **API endpoint**: Sistem mengembalikan respon JSON error: `{"error": "Chat tidak tersedia untuk pesanan yang telah selesai."}` dengan HTTP status 403.

2. **Kewajiban Isi Pesan atau Gambar (`required_without`)**:
   - **Aturan**: Salah satu dari kolom teks pesan (`message`) atau berkas gambar (`image`) wajib diisi. Pesan boleh kosong jika ada gambar, dan gambar boleh kosong jika ada pesan.
   - **Pelanggaran**: Mengosongkan kedua kolom saat mengirim chat.
   - **Dampak/Error**: Validasi menolak dengan pesan error: `Pesan atau gambar harus diisi.`

3. **Panjang Karakter Pesan (`message`)**:
   - **Aturan**: Tipe data *string*, panjang **minimal 2 karakter** dan maksimal 2000 karakter.
   - **Pelanggaran**: Mengisi pesan teks kurang dari 2 karakter (misal: "A").
   - **Dampak/Error**: Validasi menolak dengan pesan error: `Pesan minimal harus 2 karakter.`

4. **Kualitas Berkas Gambar (`image`)**:
   - **Aturan**: Bersifat opsional, jika diunggah harus berupa berkas gambar valid dengan format `jpeg`, `png`, `jpg`, `gif`, atau `webp`, serta **ukuran maksimal 2 MB (2048 KB)**.
   - **Pelanggaran**:
     - Berkas non-gambar (misal: .pdf, .zip): `File harus berupa gambar.` atau `Format gambar harus jpeg, png, jpg, gif, atau webp.`
     - Ukuran > 2 MB: `Ukuran gambar maksimal adalah 2 MB.`

---

## Tabel Test Case Melakukan Chat (Chatting)

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Chat.001** | TC.Chat.001 | Mengirim pesan teks valid | Positive | Equivalence Partitioning (EP) | Pelanggan berhasil mengirimkan pesan teks untuk pesanan aktif | Pelanggan sudah login dan berada di halaman chat pesanan aktif | 1. Ketik pesan "Tolong kirimkan invoice fisik di dalam paket ya"<br>2. Klik tombol "Kirim" | 1. Mengisi kolom pesan teks (52 karakter)<br>2. Mengklik tombol kirim | 1. Pesan teks terkirim sukses via AJAX<br>2. Pesan baru langsung muncul di area obrolan secara real-time<br>3. Admin menerima notifikasi pesan baru pelanggan. | Pass |
| **TS.Chat.002** | TC.Chat.002 | Mengirim gambar ulasan/bukti tanpa teks | Positive | Equivalence Partitioning (EP) | Pelanggan berhasil mengirimkan gambar tanpa isi pesan teks | Pelanggan sudah login dan berada di halaman chat pesanan aktif | 1. Kosongkan kolom pesan teks<br>2. Unggah gambar valid berukuran 1MB (.png)<br>3. Klik tombol "Kirim" | 1. Mengosongkan teks pesan<br>2. Mengunggah berkas gambar valid<br>3. Mengklik tombol kirim | 1. Gambar berhasil terunggah dan disimpan ke folder `public/storage/chats`<br>2. Gambar langsung dirender di area obrolan pelanggan dan admin. | Pass |
| **TS.Chat.003** | TC.Chat.003 | Mengirim pesan kosong (tanpa teks dan gambar) | Negative | Use Case Testing | Sistem menolak pengiriman pesan karena tidak ada data yang dikirim | Pelanggan berada di halaman chat | 1. Kosongkan pesan teks dan jangan pilih gambar<br>2. Klik tombol "Kirim" | 1. Mengosongkan input teks dan gambar<br>2. Mengklik tombol kirim | 1. Pengiriman dibatalkan oleh validasi sistem<br>2. Menampilkan pesan error: `Pesan atau gambar harus diisi.` | Pass |
| **TS.Chat.004** | TC.Chat.004 | Mengirim pesan teks kurang dari 2 karakter | Negative | Boundary Value Analysis (BVA) | Sistem menolak pesan karena terlalu pendek | Pelanggan berada di halaman chat | 1. Ketik pesan teks "A" (di bawah batas minimum 2 karakter)<br>2. Klik tombol "Kirim" | 1. Mengisi pesan teks 1 karakter<br>2. Mengklik tombol kirim | 1. Pesan teks terisi "A"<br>2. Pengiriman ditolak oleh validasi sistem dengan pesan error: `Pesan minimal harus 2 karakter.` | Pass |
| **TS.Chat.005** | TC.Chat.005 | Mengirim gambar dengan ukuran melebihi 2 MB | Negative | Boundary Value Analysis (BVA) | Sistem menolak pengiriman gambar karena ukuran melebihi batas maksimum (2 MB) | Pelanggan berada di halaman chat | 1. Unggah gambar berukuran 2.1 MB (.jpg)<br>2. Klik tombol "Kirim" | 1. Mengunggah gambar 2.1 MB<br>2. Mengklik tombol kirim | 1. Berkas gambar berukuran besar dipilih<br>2. Pengiriman ditolak oleh validasi Laravel dengan pesan error: `Ukuran gambar maksimal adalah 2 MB.` | Pass |
| **TS.Chat.006** | TC.Chat.006 | Mengirim berkas dengan format tidak valid (non-gambar) | Negative | Equivalence Partitioning (EP) | Sistem menolak pengiriman berkas karena bukan format gambar yang diizinkan | Pelanggan berada di halaman chat | 1. Unggah berkas dokumen "bukti.pdf"<br>2. Klik tombol "Kirim" | 1. Mengunggah berkas PDF<br>2. Mengklik tombol kirim | 1. Berkas non-gambar dipilih<br>2. Pengiriman ditolak oleh validasi Laravel dengan pesan error: `File harus berupa gambar.` atau `Format gambar harus jpeg, png, jpg, gif, atau webp.` | Pass |
| **TS.Chat.007** | TC.Chat.007 | Mengakses halaman chat untuk pesanan yang sudah selesai | Negative | Decision Table | Sistem memblokir akses dan mengalihkan pengguna kembali ke riwayat | Pelanggan memiliki pesanan dengan status "Selesai" | 1. Buka halaman chat secara langsung menggunakan URL pesanan selesai, misal `/pelanggan/chat/10` | 1. Mengakses URL chat pesanan selesai secara langsung | 1. Browser mengirimkan permintaan GET ke `/pelanggan/chat/10`<br>2. Sistem mendeteksi status pesanan terkait adalah selesai, memblokir akses, dan mengalihkan kembali ke riwayat dengan pesan error: `Chat tidak tersedia untuk pesanan yang telah selesai.` | Pass |
| **TS.Chat.008** | TC.Chat.008 | Mengakses halaman chat tanpa login | Negative | Use Case Testing | Sistem memblokir akses dan mengalihkan pengguna ke halaman landing `/` | Pengguna berstatus Guest (belum login) | 1. Buka URL halaman chat pesanan secara langsung, misal `/pelanggan/chat/5` | 1. Mengakses URL chat secara langsung tanpa sesi login | 1. Browser mengirimkan permintaan GET ke `/pelanggan/chat/5`<br>2. Sistem memblokir akses karena mendeteksi tidak ada sesi pelanggan yang aktif (`session('role') !== 'pelanggan'`), lalu mengalihkan pengguna kembali ke halaman landing `/` dengan pesan error: `Akses ditolak!` | Pass |
