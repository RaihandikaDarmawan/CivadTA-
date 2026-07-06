# Dokumen Test Case: Login Pengguna

Dokumen ini mendokumentasikan skenario pengujian fungsional (*blackbox testing*) untuk fitur **Login Pengguna** pada sistem CivadTA dengan menggunakan format tabel pengujian standar dan aturan validasi input yang mendetail.

## Aturan Validasi Input (Input Validation Rules)

Sebelum masuk ke dashboard, sistem menerapkan aturan validasi untuk formulir masuk:

1. **Username (`username`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data *string*.
   - **Pelanggaran**: Dikosongkan.
   - **Dampak/Error**: Validasi menolak dan menampilkan pesan error di atas form: `The username field is required.`

2. **Kata Sandi (`password`)**:
   - **Aturan**: Wajib diisi (*required*), tipe data *string*.
   - **Pelanggaran**: Dikosongkan.
   - **Dampak/Error**: Validasi menolak dan menampilkan pesan error di atas form: `The password field is required.`

3. **Pencocokan Kredensial dengan Database**:
   - **Aturan**: Kombinasi username dan password harus cocok dengan data yang terdaftar di database.
   - **Pelanggaran**: Memasukkan username tidak terdaftar, password salah, atau data masukan di luar aturan pembuatan akun (misal: username/password terlalu pendek).
   - **Dampak/Error**: Login gagal, sistem menampilkan pesan error di atas form: `Username/kata sandi salah, silahkan coba lagi`

---

## Tabel Test Case Login Pengguna

| Scenario ID | Case ID | Test Scenario | Type | Teknik | Test Case | Pre Condition | Steps | Steps Description | Expected Result | Status (Pass/Fail) |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TS.Login.001** | TC.Login.001 | Cek login dengan data valid | Positive | Equivalence Partitioning (EP) | Pengguna berhasil login menggunakan kredensial yang valid | Pengguna sudah terdaftar di sistem dengan username "johndoe" dan password "Password123" | 1. Buka halaman login `/login`<br>2. Input username "johndoe"<br>3. Input password "Password123"<br>4. Pilih role "Pelanggan" atau "Admin"<br>5. Klik tombol "Login" | 1. Membuka halaman login<br>2. Mengisi username "johndoe"<br>3. Mengisi password valid<br>4. Memilih role pengguna<br>5. Mengklik tombol login | 1. Halaman login ditampilkan<br>2. Username terisi "johndoe"<br>3. Password terisi (disamarkan)<br>4. Role terpilih<br>5. Sistem memvalidasi kredensial, mencocokkan hash password, membuat sesi login, dan mengarahkan pengguna ke halaman dashboard yang sesuai. | Pass |
| **TS.Login.002** | TC.Login.002 | Login dengan username kosong | Negative | Equivalence Partitioning (EP) | Login ditolak karena username dikosongkan | Pengguna berada di halaman login | 1. Kosongkan field "Username"<br>2. Input password "Password123"<br>3. Klik tombol "Login" | 1. Mengosongkan field username<br>2. Mengisi password valid<br>3. Mengklik tombol login | 1. Username kosong<br>2. Password terisi<br>3. Login ditolak, sistem menampilkan pesan error di atas form: `The username field is required.` | Pass |
| **TS.Login.003** | TC.Login.003 | Login dengan password kosong | Negative | Equivalence Partitioning (EP) | Login ditolak karena password dikosongkan | Pengguna berada di halaman login | 1. Input username "johndoe"<br>2. Kosongkan field "Password"<br>3. Klik tombol "Login" | 1. Mengisi username valid<br>2. Mengosongkan field password<br>3. Mengklik tombol login | 1. Username terisi<br>2. Password kosong<br>3. Login ditolak, sistem menampilkan pesan error di atas form: `The password field is required.` | Pass |
| **TS.Login.004** | TC.Login.004 | Login dengan username tidak terdaftar | Negative | Equivalence Partitioning (EP) | Login ditolak karena username tidak ditemukan | Pengguna berada di halaman login | 1. Input username yang tidak ada di database, misal "usernamesalah"<br>2. Input password "Password123"<br>3. Klik tombol "Login" | 1. Mengisi username salah<br>2. Mengisi password valid<br>3. Mengklik tombol login | 1. Username terisi "usernamesalah"<br>2. Password terisi<br>3. Login ditolak, sistem menampilkan pesan error di atas form: `Username/kata sandi salah, silahkan coba lagi` | Pass |
| **TS.Login.005** | TC.Login.005 | Login dengan password salah | Negative | Equivalence Partitioning (EP) | Login ditolak karena password tidak cocok dengan username | Pengguna terdaftar dengan username "johndoe" | 1. Input username "johndoe"<br>2. Input password salah, misal "Passwordsalah"<br>3. Klik tombol "Login" | 1. Mengisi username valid<br>2. Mengisi password salah<br>3. Mengklik tombol login | 1. Username terisi "johndoe"<br>2. Password terisi salah<br>3. Login ditolak, sistem menampilkan pesan error di atas form: `Username/kata sandi salah, silahkan coba lagi` | Pass |
| **TS.Login.006** | TC.Login.006 | Login dengan input di luar batas aturan pembuatan akun (username/password pendek) | Negative | Boundary Value Analysis (BVA) | Login ditolak karena kredensial tidak cocok dengan database | Pengguna berada di halaman login | 1. Input username 2 karakter "ij" (di luar aturan pendaftaran)<br>2. Input password 7 karakter "Pass123" (di luar aturan pendaftaran)<br>3. Klik tombol "Login" | 1. Mengisi username pendek<br>2. Mengisi password pendek<br>3. Mengklik tombol login | 1. Username terisi "ij"<br>2. Password terisi "Pass123"<br>3. Login ditolak (karena input tidak ada di database), sistem menampilkan pesan error di atas form: `Username/kata sandi salah, silahkan coba lagi` | Pass |
