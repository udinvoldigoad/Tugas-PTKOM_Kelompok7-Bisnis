# Kasir Kafe - Coffe Ridho

Aplikasi kasir berbasis Laravel untuk mengelola menu, keranjang, transaksi, dan profil pengguna Kafe Ridho. Halaman menu publik dapat dibuka tanpa login, sedangkan operasi kasir dan pengelolaan menu memerlukan akun internal.

## Fitur yang tersedia

- Menu publik responsif dengan foto, kategori, harga, dan status ketersediaan.
- Login khusus akun internal; registrasi publik, verifikasi email, dan reset password melalui email dinonaktifkan.
- Kelola menu: tambah, edit, unggah atau hapus foto, ubah status tersedia/habis, soft delete, dan pemulihan menu.
- Keranjang berbasis session dengan harga yang selalu dihitung dari database.
- Catatan pesanan sementara selama transaksi.
- Checkout dengan PPN 10% dan metode pembayaran Cash atau QRIS.
- Penyimpanan transaksi dan detail transaksi secara atomik menggunakan database transaction.
- Profil pengguna, perubahan password, avatar, status akun, serta pencatatan waktu login terakhir.
- Validasi terhadap keranjang kosong, jumlah tidak valid, menu habis, manipulasi harga dari browser, klik checkout berulang, dan kegagalan database.

## Teknologi

- PHP 8.3 atau lebih baru
- Laravel 13
- MySQL atau SQLite
- Blade, Tailwind CSS, Alpine.js, dan Vite
- PHPUnit 12

## Instalasi

```powershell
git clone https://github.com/udinvoldigoad/Tugas-PTKOM_Kelompok7-Bisnis.git
cd Tugas-PTKOM_Kelompok7-Bisnis
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

Untuk MySQL Laragon, buat database `kasir_kafe`, lalu sesuaikan bagian berikut pada `.env`:

```env
APP_NAME="Coffe Ridho"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kasir_kafe
DB_USERNAME=root
DB_PASSWORD=
```

Isi kredensial akun awal pada `.env` sebelum menjalankan seeder:

```env
INITIAL_USER_NAME="Kasir Kafe"
INITIAL_USER_EMAIL=alamat-email-internal
INITIAL_USER_PASSWORD=password-kuat-minimal-12-karakter
```

Jalankan migrasi, seeder, dan buat symbolic link untuk foto menu serta avatar:

```powershell
php artisan migrate --seed
php artisan storage:link
npm run build
```

Jalankan aplikasi:

```powershell
php artisan serve
```

Buka `http://127.0.0.1:8000` untuk menu publik atau `http://127.0.0.1:8000/login` untuk login kasir.

## Akun awal

Seeder membuat akun internal berdasarkan `INITIAL_USER_NAME`, `INITIAL_USER_EMAIL`, dan `INITIAL_USER_PASSWORD`. Password tidak mempunyai nilai bawaan dan wajib memiliki minimal 12 karakter.

## Route utama

| Halaman | URL | Akses |
| --- | --- | --- |
| Menu publik | `/` | Publik |
| Login kasir | `/login` | Publik |
| Profil | `/dashboard` atau `/profile` | Pengguna login |
| Kasir dan kelola menu | `/kelola-menu` | Pengguna login |
| Keranjang server | `/kasir/keranjang` | Pengguna login |

## Pengujian dan format kode

```powershell
php artisan test
vendor\bin\pint --format agent
```

Jika ekstensi SQLite CLI belum aktif di Windows, pengujian dapat dijalankan dengan:

```powershell
php -d extension=pdo_sqlite vendor/bin/phpunit
```

## Struktur data transaksi

- Satu pengguna dapat mencatat banyak transaksi.
- Satu transaksi memiliki banyak detail transaksi.
- Satu menu dapat muncul pada banyak detail transaksi.
- Subtotal setiap item disimpan pada detail transaksi agar perubahan harga menu tidak mengubah riwayat lama.
- Menu menggunakan soft delete agar relasi transaksi lama tetap valid.
- Total transaksi mencakup PPN 10%.
- Metode pembayaran disimpan sebagai `cash` atau `qris`.
- Catatan pesanan hanya digunakan selama proses pemesanan dan tidak disimpan ke database.

## Status pengembangan

Fitur inti menu, keranjang, checkout, dan profil sudah tersedia. Pekerjaan berikutnya meliputi:

- pembatasan akses berdasarkan role admin dan kasir;
- riwayat transaksi;
- cetak struk;
- dashboard statistik penjualan;
- perapian nama route agar halaman profil, dashboard, kasir, dan kelola menu terpisah dengan jelas.

## Pembagian peran tim

| Anggota | Tanggung jawab |
| --- | --- |
| Ridho | Project manager |
| Irfan | UI/UX dan pemeriksaan desain |
| Niken | Frontend autentikasi, menu publik, riwayat, dan dashboard |
| Faza | Frontend autentikasi, menu publik, riwayat, dan dashboard |
| Qinta | Backend autentikasi, menu, riwayat, dan deployment |
| Bagas | Backend database, keranjang, transaksi, dan statistik penjualan |
