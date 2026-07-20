# Dokumen Test Case: Melihat Riwayat Transaksi

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Melihat Riwayat Transaksi** pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail sesuai dengan data dan logika proyek.

## Aturan Validasi Input (Input Validation Rules)

Fitur ini memiliki beberapa aturan akses dan masukan yang harus dipenuhi:

1. **Autentikasi Sesi Pengguna**:
   - **Aturan**: Pengguna harus masuk terlebih dahulu dan memiliki sesi `role` bernilai `pelanggan`.
   - **Dampak/Error**: Jika diakses tanpa login, sistem memblokir akses dan mengalihkan pengguna ke halaman login (`/login`) dengan pesan error: `Silakan login terlebih dahulu.`

2. **Syarat Unduh Invoice (`/pelanggan/invoice/{id}/unduh`)**:
   - **Aturan**: File PDF invoice hanya dapat diunduh jika pesanan tersebut berstatus **Selesai** (`status = 'Selesai'`) dan pesanan tersebut milik pengguna yang bersangkutan.
   - **Pelanggaran**: Mencoba mengunduh invoice dari pesanan yang masih berstatus `Pending`, `Pesanan Sedang Dikemas`, atau `Dikirim`.
   - **Dampak/Error**: Sistem menolak permintaan unduhan dan menampilkan halaman error default Laravel `404 | NOT FOUND` (karena query menggunakan `where('status', 'Selesai')->firstOrFail()`).

---

## Tabel Test Case Melihat Riwayat Transaksi

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.History.001** | TC.History.001 | Membuka halaman riwayat transaksi dan mengunduh invoice valid | Positive | Equivalence Partitioning (EP) | Pelanggan berhasil melihat daftar riwayat belanja dan mengunduh invoice PDF untuk pesanan selesai | Pelanggan sudah login dan memiliki setidaknya satu pesanan berstatus "Selesai" | 1. Buka halaman riwayat `/pelanggan/riwayat`<br>2. Cari pesanan berstatus "Selesai"<br>3. Klik tombol "Unduh Invoice" | 1. Mengakses halaman `/pelanggan/riwayat`<br>2. Mencari pesanan selesai<br>3. Mengklik tombol unduh invoice | 1. Halaman riwayat menampilkan daftar pesanan terdahulu lengkap dengan status, total harga, dan tombol aksi.<br>2. Tombol "Unduh Invoice" aktif pada pesanan selesai.<br>3. Sistem memproses file PDF invoice dan mendownloadnya secara otomatis dengan nama file `invoice-[order_number].pdf`. | Pass |
| **TS.History.002** | TC.History.002 | Mengakses halaman riwayat tanpa login | Negative | Use Case Testing | Sistem memblokir akses dan mengalihkan pengguna ke halaman login | Pengguna berstatus Guest (belum login) | 1. Buka URL halaman riwayat secara langsung, misal `/pelanggan/riwayat` | 1. Mengakses URL riwayat secara langsung tanpa sesi login | 1. Browser mengirimkan permintaan GET ke `/pelanggan/riwayat`<br>2. Sistem memblokir akses karena mendeteksi tidak ada sesi pelanggan yang aktif, lalu mengalihkan pengguna kembali ke halaman login `/login` dengan pesan error: `Silakan login terlebih dahulu.` | Pass |
| **TS.History.003** | TC.History.003 | Mengunduh invoice untuk pesanan yang belum selesai | Negative | Decision Table | Sistem menolak unduhan invoice dan mengembalikan error 404 | Pelanggan memiliki pesanan dengan status "Pesanan Sedang Dikemas" | 1. Coba akses URL unduh invoice secara langsung dengan ID pesanan yang belum selesai, misal `/pelanggan/invoice/25/unduh` | 1. Mengakses URL unduh invoice pesanan belum selesai secara langsung | 1. Browser mengirimkan permintaan GET ke `/pelanggan/invoice/25/unduh`<br>2. Sistem menyaring database menggunakan `firstOrFail()` dengan syarat status harus `Selesai`, dan karena syarat tidak terpenuhi, sistem mengembalikan halaman error 404 (Not Found). | Pass |
