<x-mail::message>
# Selamat Datang di CIVAD Bookstore, {{ $user->name }}!

Terima kasih telah bergabung dengan kami. Akun Anda telah berhasil dibuat.

Sekarang Anda dapat menjelajahi koleksi buku kami dan menikmati berbagai fitur menarik seperti:
- Koleksi buku lengkap untuk berbagai tingkatan sekolah.
- Sistem poin loyalty untuk setiap pembelian.
- Kemudahan pembayaran dengan Midtrans.
- Pemantauan Status Pemesanan

<x-mail::button :url="config('app.url') . '/login'">
Mulai Belanja Sekarang
</x-mail::button>

Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi tim dukungan kami.

Salam hangat,<br>
Tim {{ config('app.name') }}
</x-mail::message>
