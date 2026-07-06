# Dokumen Test Case: Registrasi Pelanggan

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Registrasi Pelanggan** pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail.

## Aturan Validasi Input (Input Validation Rules)

Sebelum data registrasi diproses, sistem menerapkan aturan validasi ketat sebagai berikut:

1. **Nama Lengkap (`name`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data *string*, **minimal 3 karakter**, maksimal 255 karakter.
   - **Pelanggaran**: Dikosongkan, atau diisi kurang dari 3 karakter (misal: 2 karakter).
   - **Dampak/Error**: Validasi menolak pendaftaran dan memunculkan pesan: `The name field is required.` atau `Nama lengkap minimal harus 3 karakter.`

2. **Username (`username`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data *string*, **minimal 3 karakter**, maksimal 255 karakter, harus **unik** (belum terdaftar di database `pelanggan`).
   - **Pelanggaran**: Dikosongkan, diisi kurang dari 3 karakter (misal: 2 karakter), atau diisi dengan username yang sudah digunakan.
   - **Dampak/Error**: Sistem memunculkan pesan: `The username has already been taken.` atau `Username minimal harus 3 karakter.`

3. **Email (`email`)**:
   - **Aturan**: Wajib diisi (*required*), format email harus valid (mengandung `@` dan domain), maksimal 255 karakter, harus **unik**.
   - **Pelanggaran**: Dikosongkan, diisi tanpa format email (misal: `johndoe`), atau email sudah terdaftar.
   - **Dampak/Error**: Sistem memunculkan pesan: `The email must be a valid email address.` atau `The email has already been taken.`

4. **Kata Sandi (`password`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data *string*, harus dicocokkan dengan konfirmasi kata sandi (*confirmed*), **minimal 8 karakter**, mengandung **setidaknya 1 huruf besar**, **setidaknya 1 huruf kecil/huruf**, dan **setidaknya 1 angka**.
   - **Pelanggaran**:
     - Panjang < 8 karakter: `Kata sandi minimal harus 8 karakter.`
     - Tanpa huruf besar: `Kata sandi harus mengandung setidaknya 1 huruf besar.`
     - Tanpa huruf kecil/huruf: `Kata sandi harus mengandung setidaknya satu huruf.`
     - Tanpa angka: `Kata sandi harus mengandung setidaknya satu angka.`
     - Konfirmasi tidak cocok: `Konfirmasi kolom kata sandi tidak sesuai.`

5. **Daerah (`daerah`)**:
   - **Aturan**: Opsional/Wajib dipilih (string).

---

## Tabel Test Case Registrasi Pelanggan

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Reg.001** | TC.Reg.001 | Cek registrasi pelanggan baru dengan data valid | Positive | Equivalence Partitioning (EP) | Pelanggan berhasil mendaftarkan akun baru ke sistem | Pelanggan belum memiliki akun pada sistem | 1. Buka halaman registrasi `/register`<br>2. Input nama lengkap "John Doe"<br>3. Input username unik "johndoe"<br>4. Input email unik "johndoe@example.com"<br>5. Input password yang valid "Password123"<br>6. Input konfirmasi password "Password123"<br>7. Pilih daerah pengiriman "Tangerang"<br>8. Klik tombol "Daftar" | 1. Membuka halaman register<br>2. Mengisi kolom nama lengkap<br>3. Mengisi kolom username<br>4. Mengisi kolom email<br>5. Mengisi kolom password<br>6. Mengisi kolom konfirmasi password<br>7. Memilih daerah Tangerang<br>8. Mengklik tombol daftar akun | 1. Halaman form registrasi ditampilkan dengan benar<br>2. Nama terisi<br>3. Username terisi<br>4. Email terisi<br>5. Password terisi (disamarkan)<br>6. Konfirmasi password terisi<br>7. Daerah terpilih<br>8. Sistem berhasil menyimpan akun baru, mengirim email konfirmasi, dan mengalihkan ke halaman login dengan pesan sukses. | Pass |
| **TS.Reg.002** | TC.Reg.002 | Registrasi dengan nama lengkap dikosongkan | Negative | Equivalence Partitioning (EP) | Registrasi ditolak karena Nama Lengkap tidak diisi | Pelanggan berada di halaman registrasi | 1. Kosongkan field "Nama Lengkap"<br>2. Isi field lainnya dengan data valid<br>3. Klik tombol "Daftar" | 1. Mengosongkan field Nama Lengkap<br>2. Mengisi field data lainnya<br>3. Mengklik tombol daftar akun | 1. Field Nama kosong<br>2. Data lain terisi<br>3. Pendaftaran ditolak, sistem menampilkan pesan error: `The name field is required.` | Pass |
| **TS.Reg.003** | TC.Reg.003 | Registrasi dengan username duplikat | Negative | Equivalence Partitioning (EP) | Registrasi ditolak karena username sudah digunakan oleh akun lain | Username "johndoe" sudah terdaftar di database | 1. Input username yang sudah ada "johndoe"<br>2. Isi field lainnya dengan data valid<br>3. Klik tombol "Daftar" | 1. Mengisi username duplikat<br>2. Mengisi field data lainnya<br>3. Mengklik tombol daftar akun | 1. Username terisi "johndoe"<br>2. Data lain terisi<br>3. Pendaftaran ditolak, sistem menampilkan pesan error: `The username has already been taken.` | Pass |
| **TS.Reg.004** | TC.Reg.004 | Registrasi dengan email format salah | Negative | Equivalence Partitioning (EP) | Registrasi ditolak karena format email tidak sesuai standar | Pelanggan berada di halaman registrasi | 1. Input email salah format "johndoegmail.com" (tanpa `@`) atau "johndoe@gmail" (tanpa `.com`)<br>2. Isi field lainnya dengan data valid<br>3. Klik tombol "Daftar" | 1. Mengisi email dengan format tidak valid<br>2. Mengisi field data lainnya<br>3. Mengklik tombol daftar akun | 1. Email terisi format salah<br>2. Data lain terisi<br>3. Pendaftaran ditolak oleh browser/sistem dengan pesan error: `The email must be a valid email address.` | Pass |
| **TS.Reg.005** | TC.Reg.005 | Registrasi dengan password kurang dari 8 karakter | Negative | Boundary Value Analysis (BVA) | Registrasi ditolak karena password kurang dari batas minimum (8 karakter) | Pelanggan berada di halaman registrasi | 1. Input password 7 karakter (di bawah batas minimum 8), misal "Passw12"<br>2. Isi field lainnya dengan data valid<br>3. Klik tombol "Daftar" | 1. Mengisi password 7 karakter<br>2. Mengisi field data lainnya<br>3. Mengklik tombol daftar akun | 1. Password terisi "Passw12"<br>2. Data lain terisi<br>3. Pendaftaran ditolak, sistem menampilkan pesan error: `Kata sandi minimal harus 8 karakter.` | Pass |
| **TS.Reg.006** | TC.Reg.006 | Registrasi dengan password tanpa huruf besar/angka | Negative | Equivalence Partitioning (EP) | Registrasi ditolak karena tingkat keamanan password tidak memenuhi syarat | Pelanggan berada di halaman registrasi | 1. Input password lemah "password" (tanpa huruf besar dan angka) atau "12345678" (tanpa huruf)<br>2. Isi field lainnya dengan data valid<br>3. Klik tombol "Daftar" | 1. Mengisi password lemah<br>2. Mengisi field data lainnya<br>3. Mengklik tombol daftar akun | 1. Password lemah terisi<br>2. Data lain terisi<br>3. Pendaftaran ditolak, sistem menampilkan pesan error: `Kata sandi harus mengandung setidaknya 1 huruf besar.` dan `Kata sandi harus mengandung setidaknya satu huruf.` | Pass |
| **TS.Reg.007** | TC.Reg.007 | Registrasi dengan konfirmasi password tidak cocok | Negative | Equivalence Partitioning (EP) | Registrasi ditolak karena konfirmasi password berbeda | Pelanggan berada di halaman registrasi | 1. Input password "Password123"<br>2. Input konfirmasi password berbeda "Password321"<br>3. Isi field lainnya dengan data valid<br>4. Klik tombol "Daftar" | 1. Mengisi password valid<br>2. Mengisi konfirmasi password berbeda<br>3. Mengisi field data lainnya<br>4. Mengklik tombol daftar akun | 1. Password terisi<br>2. Konfirmasi password terisi berbeda<br>3. Pendaftaran ditolak, sistem menampilkan pesan error: `Konfirmasi kolom kata sandi tidak sesuai.` | Pass |
| **TS.Reg.008** | TC.Reg.008 | Registrasi dengan username kurang dari 3 karakter | Negative | Boundary Value Analysis (BVA) | Registrasi ditolak karena username kurang dari batas minimum (3 karakter) | Pelanggan berada di halaman registrasi | 1. Input username 2 karakter (di bawah batas minimum 3), misal "ij"<br>2. Isi field lainnya dengan data valid<br>3. Klik tombol "Daftar" | 1. Mengisi username 2 karakter "ij"<br>2. Mengisi field data lainnya<br>3. Mengklik tombol daftar akun | 1. Username terisi "ij"<br>2. Data lain terisi<br>3. Pendaftaran ditolak, sistem menampilkan pesan error: `Username minimal harus 3 karakter.` | Pass |
| **TS.Reg.009** | TC.Reg.009 | Registrasi dengan nama lengkap kurang dari 3 karakter | Negative | Boundary Value Analysis (BVA) | Registrasi ditolak karena nama lengkap kurang dari batas minimum (3 karakter) | Pelanggan berada di halaman registrasi | 1. Input nama lengkap 2 karakter (di bawah batas minimum 3), misal "ij"<br>2. Isi field lainnya dengan data valid<br>3. Klik tombol "Daftar" | 1. Mengisi nama lengkap 2 karakter "ij"<br>2. Mengisi field data lainnya<br>3. Mengklik tombol daftar akun | 1. Nama lengkap terisi "ij"<br>2. Data lain terisi<br>3. Pendaftaran ditolak, sistem menampilkan pesan error: `Nama lengkap minimal harus 3 karakter.` | Pass |
