# Dokumen Test Case: Melakukan Pembayaran

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Melakukan Pembayaran** menggunakan Midtrans Snap API pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail.

## Aturan Validasi Input (Input Validation Rules)

Proses pembayaran dilakukan melalui integrasi widget Midtrans Snap dengan ketentuan:

1. **Sesi Snap Token (`snap_token`)**:
   - **Aturan**: Harus berupa token transaksi Midtrans Snap yang valid yang diterbitkan oleh server Midtrans.
   - **Dampak/Error**: Jika API Midtrans gagal dihubungi atau parameter checkout tidak valid, sistem menampilkan pesan error: `Gagal terhubung ke Midtrans: [Pesan Kesalahan]` dan membatalkan pengalihan ke widget Snap.

2. **Status Transaksi Midtrans**:
   - **Aturan**: Status akhir pembayaran dari Midtrans (settlement, capture, pending, deny, expire, cancel) menentukan kelanjutan status pemesanan di CivadTA.
   - **Hasil Transaksi**:
     - `settlement` / `capture` (tanpa fraud challenge): Status pesanan diubah dari "Pending" menjadi "Pesanan Sedang Dikemas".
     - `pending`: Status pesanan tetap "Pending".
     - `deny` / `expire` / `cancel`: Status pesanan batal atau tetap pending hingga diverifikasi ulang.

---

## Tabel Test Case Melakukan Pembayaran

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Payment.001** | TC.Payment.001 | Pembayaran berhasil diselesaikan di Midtrans Snap | Positive | State Transition | Pelanggan berhasil melakukan pembayaran dan status pesanan berubah menjadi "Pesanan Sedang Dikemas" | Pelanggan berada di halaman pembayaran Midtrans Snap dengan widget Snap terbuka | 1. Pilih metode pembayaran "Virtual Account (VA)" atau "GoPay"<br>2. Selesaikan pembayaran sesuai petunjuk simulator Midtrans (input detail pembayaran dan konfirmasi)<br>3. Tunggu hingga Midtrans mengonfirmasi pembayaran sukses | 1. Memilih metode pembayaran di Snap<br>2. Menyelesaikan pembayaran di simulator<br>3. Mengklik tombol konfirmasi/selesai | 1. Widget Snap menampilkan pemberitahuan transaksi berhasil<br>2. Server Midtrans mengirim callback notification sukses ke backend CivadTA<br>3. Sistem mendeteksi status settlement, memotong stok buku (jika belum dipotong), memberikan poin loyalty, dan memperbarui status pesanan menjadi "Pesanan Sedang Dikemas". | Pass |
| **TS.Payment.002** | TC.Payment.002 | Pelanggan menutup widget Midtrans Snap sebelum membayar | Negative | State Transition | Transaksi tetap berstatus "Pending" dan pelanggan dapat mencoba membayar lagi dari riwayat | Pelanggan berada di halaman pembayaran Midtrans Snap dengan widget Snap terbuka | 1. Klik ikon silang (x) atau tombol "Batal/Kembali" pada widget Midtrans Snap | 1. Mengklik tombol batalkan/tutup di widget Snap | 1. Widget Snap tertutup<br>2. Sistem menerima status cancel/pending, mengalihkan kembali ke riwayat belanja pelanggan, dan status pesanan tetap "Pending" (belum lunas). | Pass |
| **TS.Payment.003** | TC.Payment.003 | Transaksi ditolak atau kedaluwarsa oleh sistem Midtrans | Negative | State Transition | Transaksi ditolak oleh Midtrans dan status pesanan tetap pending / dibatalkan | Pelanggan berada di halaman pembayaran Midtrans Snap dengan widget Snap terbuka | 1. Masukkan data kartu kredit fiktif/tidak valid atau biarkan transaksi melewati batas waktu pembayaran (expire) | 1. Memasukkan data transaksi tidak valid / mendiamkan transaksi hingga expire | 1. Widget Snap menampilkan pesan transaksi gagal/kedaluwarsa<br>2. Sistem mencatat status transaksi gagal, stok buku dikembalikan (jika status dibatalkan), dan status pesanan di riwayat tidak berubah menjadi lunas. | Pass |
