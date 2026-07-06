# Dokumen Test Case: Melakukan Pencarian Buku

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Melakukan Pencarian Buku** pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail sesuai dengan data dan logika proyek.

## Aturan Validasi Input (Input Validation Rules)

Operasi pencarian buku di katalog memiliki aturan masukan sebagai berikut:

1. **Parameter Pencarian (`search`)**:
   - **Aturan**: Bersifat opsional (*nullable*), bertipe data *string*, diproses secara dinamis menggunakan JavaScript di client-side. Dapat mencakup kata kunci judul buku atau nama penulis.
   - **Pelanggaran**: Menginput kata kunci yang tidak memiliki kecocokan di database (misal: "Sejarah Kuno Dunia"), atau menginput karakter kosong/spasi saja.
   - **Dampak/Error**: Pencarian kosong akan memuat seluruh buku (default), sedangkan pencarian tanpa kecocokan akan memuat halaman katalog kosong dengan pesan: "Buku Tidak Ditemukan."

---

## Tabel Test Case Melakukan Pencarian Buku

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Search.001** | TC.Search.001 | Pencarian buku dengan kata kunci valid dan cocok | Positive | Equivalence Partitioning (EP) | Sistem menampilkan daftar buku yang sesuai dengan kata kunci secara dinamis | Pelanggan sudah login dan berada di halaman katalog `/pelanggan/dashboard` | 1. Buka kolom pencarian buku<br>2. Ketik kata kunci valid, misal "Matematika" | 1. Masuk ke input pencarian<br>2. Mengisi kata kunci "Matematika" | 1. Kolom pencarian terisi "Matematika"<br>2. Sistem secara real-time (client-side JS) menyaring daftar buku dan menampilkan buku yang judul atau penulisnya mengandung kata "Matematika" (misal: "Matematika SMA Kelas 10"). | Pass |
| **TS.Search.002** | TC.Search.002 | Pencarian buku dengan kata kunci tidak cocok/tidak terdaftar | Negative | Equivalence Partitioning (EP) | Sistem menampilkan halaman kosong karena tidak ada data yang cocok | Pelanggan berada di halaman katalog `/pelanggan/dashboard` | 1. Buka kolom pencarian buku<br>2. Ketik kata kunci yang tidak ada, misal "Sejarah Kuno Dunia" | 1. Masuk to input pencarian<br>2. Mengisi kata kunci tidak terdaftar | 1. Kolom pencarian terisi "Sejarah Kuno Dunia"<br>2. Sistem secara real-time menyaring daftar buku dan karena tidak ada kecocokan, menyembunyikan grid katalog serta menampilkan elemen empty state: "Buku Tidak Ditemukan". | Pass |
| **TS.Search.003** | TC.Search.003 | Pencarian buku dengan kata kunci kosong/spasi saja | Negative | Equivalence Partitioning (EP) | Sistem mengabaikan filter pencarian dan menampilkan seluruh daftar buku secara default | Pelanggan berada di halaman katalog `/pelanggan/dashboard` | 1. Klik kolom pencarian buku<br>2. Tekan tombol spasi beberapa kali (karakter kosong) | 1. Masuk ke input pencarian<br>2. Mengisi karakter spasi kosong | 1. Kolom pencarian terisi spasi kosong<br>2. Sistem tetap menampilkan seluruh buku yang terdaftar di katalog secara default karena spasi kosong tidak memengaruhi filter pencarian utama. | Pass |
