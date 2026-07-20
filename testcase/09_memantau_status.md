# Dokumen Test Case: Memantau Status Pesanan

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Memantau Status Pesanan** pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail sesuai dengan data dan logika proyek.

## Aturan Validasi Input (Input Validation Rules)

Fitur ini memiliki beberapa aturan akses dan masukan yang harus dipenuhi:

1. **Autentikasi Sesi Pengguna**:
   - **Aturan**: Pengguna harus masuk terlebih dahulu dan memiliki sesi `role` bernilai `pelanggan`.
   - **Dampak/Error**: Jika diakses tanpa login, sistem memblokir akses dan mengalihkan pengguna kembali ke halaman landing `/` dengan pesan error: `Akses ditolak!`.

2. **Perubahan Status Pesanan secara Real-time**:
   - **Aturan**: Pelanggan dapat memantau transisi status pesanannya, yang terdiri dari status:
     - `Pending`: Pembayaran belum selesai.
     - `Pesanan Sedang Dikemas`: Pembayaran sukses terverifikasi, barang disiapkan oleh admin.
     - `Pesanan Sedang Dikirim`: Kurir GoSend sedang mengirimkan pesanan ke alamat pelanggan.
     - `Selesai`: Penerimaan pesanan dikonfirmasi oleh pelanggan atau sistem secara otomatis.
     - `Pengajuan Pending`: Pelanggan mengajukan retur buku.

---

## Tabel Test Case Memantau Status Pesanan

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Status.001** | TC.Status.001 | Membuka halaman status pesanan aktif | Positive | State Transition | Pelanggan berhasil memantau transisi status pesanannya secara real-time | Pelanggan sudah login dan memiliki pesanan aktif di database | 1. Buka halaman status pesanan `/pelanggan/status`<br>2. Periksa daftar pesanan dan kolom status yang tertera | 1. Mengakses halaman status pesanan `/pelanggan/status`<br>2. Memeriksa detail status pesanan | 1. Pengguna dialihkan ke halaman riwayat belanja `/pelanggan/riwayat` yang berisi daftar seluruh pesanan aktif pengguna.<br>2. Status pesanan tertera dengan tepat (misal: transisi status dari "Pending" menjadi "Pesanan Sedang Dikemas" setelah pembayaran sukses). | Pass |
| **TS.Status.002** | TC.Status.002 | Mengakses halaman status pesanan tanpa login | Negative | Use Case Testing | Sistem memblokir akses dan mengalihkan pengguna ke halaman login | Pengguna berstatus Guest (belum login) | 1. Buka URL halaman status pesanan secara langsung, misal `/pelanggan/status` | 1. Mengakses URL status pesanan secara langsung tanpa sesi login | 1. Browser mengirimkan permintaan GET ke `/pelanggan/status`<br>2. Sistem memblokir akses karena mendeteksi tidak ada sesi pelanggan yang aktif, lalu mengalihkan pengguna kembali ke halaman login `/login` dengan pesan error: `Silakan login terlebih dahulu.` | Pass |
