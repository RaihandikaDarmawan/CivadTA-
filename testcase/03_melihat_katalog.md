# Dokumen Test Case: Melihat Katalog Buku Pelanggan

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Melihat Katalog Buku Pelanggan** pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail sesuai dengan data dan logika proyek.

## Aturan Validasi Input (Input Validation Rules)

Fitur ini memiliki beberapa aturan akses dan masukan yang harus dipenuhi:

1. **Autentikasi Sesi Pengguna**:
   - **Aturan**: Pengguna harus masuk terlebih dahulu dan memiliki sesi `role` bernilai `pelanggan`.
   - **Pelanggaran**: Pengguna belum login (Guest) mencoba mengakses halaman `/pelanggan/dashboard` secara langsung.
   - **Dampak/Error**: Sistem memblokir permintaan, mengalihkan pengguna ke halaman landing utama `/`, dan menampilkan pesan error di session: `Akses ditolak!`

2. **Parameter ID Buku (`id`)**:
   - **Aturan**: Parameter ID buku pada URL detail buku (`/pelanggan/buku/{id}`) harus bertipe data integer positif dan **ada di database**.
   - **Pelanggaran**: Memasukkan ID buku yang tidak terdaftar (misal: `/pelanggan/buku/9999`).
   - **Dampak/Error**: Sistem gagal menemukan model buku terkait dan menampilkan halaman error default: `404 | NOT FOUND`

---

## Tabel Test Case Katalog Buku Pelanggan

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Catalog.001** | TC.Catalog.001 | Membuka katalog dan melihat detail buku | Positive | Equivalence Partitioning (EP) | Pelanggan dapat menjelajahi daftar buku dan melihat informasi detail buku | Pelanggan sudah login dan berada di Dashboard pelanggan | 1. Buka halaman katalog `/pelanggan/dashboard`<br>2. Cari buku target di list katalog, misal "Fisika SMA Kelas 11"<br>3. Klik pada buku tersebut untuk melihat detailnya | 1. Mengakses halaman katalog `/pelanggan/dashboard`<br>2. Menemukan buku "Fisika SMA Kelas 11"<br>3. Mengklik buku target | 1. Halaman katalog menampilkan daftar buku terbaru dari database<br>2. Buku target "Fisika SMA Kelas 11" terlihat di list katalog<br>3. Sistem mengarahkan pelanggan ke halaman detail buku `/pelanggan/buku/{id}` menampilkan detail buku lengkap dengan sisa stok (40) dan deskripsi buku. | Pass |
| **TS.Catalog.002** | TC.Catalog.002 | Mengakses halaman katalog tanpa melakukan login | Negative | Use Case Testing | Sistem memblokir akses dan mengalihkan pengguna ke halaman landing `/` | Pengguna berstatus Guest (belum login) | 1. Buka URL halaman katalog secara langsung, misal `/pelanggan/dashboard` | 1. Mengakses URL katalog secara langsung tanpa sesi login | 1. Browser mengirimkan permintaan GET ke `/pelanggan/dashboard`<br>2. Sistem memblokir akses karena mendeteksi tidak ada sesi pelanggan yang aktif (`session('role') !== 'pelanggan'`), lalu mengalihkan pengguna kembali ke halaman landing `/` dengan pesan error: `Akses ditolak!` | Pass |
| **TS.Catalog.003** | TC.Catalog.003 | Membuka halaman detail buku dengan ID tidak valid | Negative | Equivalence Partitioning (EP) | Sistem menampilkan error 404 karena data buku tidak ditemukan | Pelanggan sudah login ke sistem | 1. Akses URL detail buku secara langsung dengan memasukkan ID buku yang tidak terdaftar, misal `/pelanggan/buku/9999` | 1. Mengakses URL langsung dengan ID tidak terdaftar | 1. Browser mengirimkan permintaan GET untuk ID buku 9999<br>2. Sistem gagal menemukan buku dengan ID 9999 di database (menggunakan `Book::findOrFail`) dan menampilkan halaman error 404 (Not Found). | Pass |
