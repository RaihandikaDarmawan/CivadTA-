# Dokumen Test Case: Mengelola Keranjang Belanja Pelanggan

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Mengelola Keranjang Belanja Pelanggan** pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail sesuai dengan data dan logika proyek.

## Aturan Validasi Input (Input Validation Rules)

Operasi keranjang belanja memiliki aturan validasi masukan dan batasan sistem sebagai berikut:

1. **Autentikasi Sesi Pengguna**:
   - **Aturan**: Pengguna harus login dan memiliki sesi `role` bernilai `pelanggan`.
   - **Dampak/Error**: Jika diakses tanpa login, halaman dapat memicu error/redirection karena ketiadaan data pengguna.

2. **Validasi Ketersediaan Stok Buku**:
   - **Aturan**: Jumlah buku yang dipesan (`qty`) tidak boleh melebihi sisa `stock` buku di database.
   - **Pelanggaran**: Memasukkan `qty` lebih besar dari stok buku yang tersedia (misal: stok 50, input qty 51).
   - **Dampak/Error**: 
     - **Client-side**: Memunculkan teks peringatan `(Stok tidak mencukupi!)` dan tombol checkout dinonaktifkan. Jika diklik, memunculkan alert browser `stok tidak mencukupi, tolong ubah jumlah stok`.
     - **Backend-side**: Saat pemrosesan pesanan/checkout, sistem membatalkan pesanan, mengalihkan kembali ke halaman keranjang belanja, dan menampilkan pesan session error: `stok tidak mencukupi, tolong ubah jumlah stok`.

3. **Batasan Jumlah Kuantitas (`qty`)**:
   - **Aturan**: Nilai `qty` yang dimasukkan atau diperbarui wajib berupa bilangan bulat positif (minimal 1).
   - **Pelanggaran**: Menginput nilai `qty` sama dengan 0, angka negatif, atau data non-numerik.
   - **Dampak/Error**: Browser memblokir pengiriman formulir lewat validasi input HTML `min="1"`. Di sisi backend, jika controller menerima nilai `qty <= 0`, sistem akan mengabaikan perubahan data kuantitas tersebut agar tidak merusak perhitungan keranjang belanja.

---

## Tabel Test Case Keranjang Belanja Pelanggan

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Cart.001** | TC.Cart.001 | CRUD item keranjang belanja dengan data valid | Positive | Equivalence Partitioning (EP) | Pelanggan berhasil menambah, mengubah kuantitas, dan menghapus item keranjang belanja | Pelanggan sudah login dan memiliki buku "Matematika SMA Kelas 10" dengan stok 50 di database | 1. Buka halaman detail buku<br>2. Input kuantitas "2" dan klik tombol "Tambah ke Keranjang"<br>3. Buka halaman keranjang `/pelanggan/keranjang`<br>4. Ubah kuantitas di keranjang belanja menjadi "5"<br>5. Klik tombol "Hapus" pada buku di keranjang belanja | 1. Membuka detail buku<br>2. Memasukkan qty 2 dan menambah ke keranjang<br>3. Membuka halaman keranjang belanja<br>4. Mengubah qty menjadi 5 menggunakan tombol +<br>5. Menghapus item dari keranjang | 1. Halaman detail buku ditampilkan<br>2. Buku berhasil ditambahkan, muncul pesan sukses: `Matematika SMA Kelas 10 berhasil ditambahkan ke keranjang!` dan counter keranjang di navbar bertambah menjadi 1<br>3. Buku Matematika tampil di daftar keranjang belanja dengan qty 2<br>4. Kuantitas berhasil diubah menjadi 5 via AJAX request ke `/pelanggan/keranjang/update` dan total biaya belanja otomatis terhitung ulang secara dinamis<br>5. Item buku berhasil dihapus dari keranjang, tabel keranjang kosong, dan counter di navbar kembali menjadi 0. | Pass |
| **TS.Cart.002** | TC.Cart.002 | Tambah/ubah qty keranjang melebihi stok yang tersedia | Negative | Boundary Value Analysis (BVA) | Sistem memblokir proses checkout karena kuantitas melebihi stok | Buku "Matematika SMA Kelas 10" memiliki sisa stok 50 di database | 1. Buka halaman keranjang `/pelanggan/keranjang` yang berisi buku Matematika SMA Kelas 10<br>2. Ubah kolom input kuantitas buku menjadi "51" (melebihi batas stok 50) | 1. Membuka keranjang belanja<br>2. Mengisi input qty dengan 51 (di atas stok)<br>3. Memeriksa status tombol checkout | 1. Teks warning merah `(Stok tidak mencukupi!)` muncul di samping stok buku<br>2. Banner merah `stok tidak mencukupi, tolong ubah jumlah stok` muncul di atas keranjang<br>3. Tombol checkout berubah pudar dan jika diklik akan muncul alert peringatan serta memblokir pengalihan halaman. | Pass |
| **TS.Cart.003** | TC.Cart.003 | Tambah ke keranjang dengan kuantitas nol atau negatif | Negative | Equivalence Partitioning (EP) | Sistem mengabaikan atau menolak penambahan buku ke keranjang | Pelanggan berada di halaman detail buku | 1. Input kuantitas "0" atau "-3"<br>2. Klik tombol "Tambah ke Keranjang" | 1. Mengisi qty 0 atau negatif<br>2. Mengklik tombol tambah ke keranjang | 1. Kolom kuantitas terisi nilai non-positif<br>2. Sistem memblokir aksi penambahan (baik divalidasi oleh input HTML `min="1"` atau ditolak oleh backend controller) dan nilai qty tidak bertambah ke keranjang. | Pass |
| **TS.Cart.004** | TC.Cart.004 | Mengubah kuantitas di keranjang belanja menjadi nol atau negatif | Negative | Equivalence Partitioning (EP) | Sistem menolak perubahan kuantitas dan total biaya belanja tidak berubah | Pelanggan memiliki 1 buku di keranjang belanja | 1. Masuk ke halaman keranjang `/pelanggan/keranjang`<br>2. Ubah kolom input kuantitas buku menjadi "0" atau "-1" | 1. Membuka keranjang belanja<br>2. Mengisi input qty dengan 0 atau negatif | 1. Halaman keranjang belanja termuat<br>2. Kolom input kuantitas terisi nilai non-positif. Sistem memblokir pembaruan kuantitas (mengabaikan update atau memvalidasi input) dan tidak mengubah total biaya belanja. | Pass |
| **TS.Cart.005** | TC.Cart.005 | Mengakses keranjang belanja tanpa login | Negative | Use Case Testing | Sistem memblokir akses / memicu kegagalan karena tidak ada sesi login | Pengguna berstatus Guest (belum login) | 1. Buka URL halaman keranjang belanja secara langsung, misal `/pelanggan/keranjang` | 1. Mengakses URL keranjang secara langsung tanpa sesi login | 1. Browser mengirimkan permintaan GET ke `/pelanggan/keranjang`<br>2. Sistem gagal merender halaman dengan benar karena ketiadaan data pengguna terautentikasi (memicu kegagalan render/redirect login). | Pass |
