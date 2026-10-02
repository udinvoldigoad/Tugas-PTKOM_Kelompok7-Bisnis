# Catatan Kegagalan Integrasi Fase 8

Tanggal pengujian: 3 Oktober 2026  
Branch integrasi: `dev`

## Ringkasan

Pengujian alur login, kelola menu, menu publik, keranjang, penyimpanan transaksi, riwayat, dan dashboard berhasil. Seluruh suite aplikasi lulus dengan 63 tes dan 277 assertion. Tidak ditemukan kegagalan fungsional aplikasi yang masih terbuka.

## Daftar Kegagalan

| ID | Kegagalan | PIC | Branch perbaikan | Tenggat | Status |
| --- | --- | --- | --- | --- | --- |
| F8-FAIL-01 | Pemeriksaan status migration MySQL gagal karena layanan MySQL lokal tidak aktif pada `127.0.0.1:3306`. | Bagas | Tidak memerlukan branch, perbaikan lingkungan lokal | 3 Oktober 2026 | Selesai |
| F8-FAIL-02 | Assertion awal tes edit menu mengharapkan harga berupa string `25000.00`, sedangkan API mengembalikan angka `25000`. | Ridho | `dev` | 3 Oktober 2026 | Selesai |
| F8-FAIL-03 | Penghapusan akun kasir berpotensi menghapus seluruh transaksi miliknya karena foreign key memakai `cascadeOnDelete`. | Bagas | `dev` | 3 Oktober 2026 | Selesai |
| F8-FAIL-04 | Nama menu pada riwayat mengikuti nama menu terbaru karena detail transaksi belum menyimpan snapshot. | Bagas | `dev` | 3 Oktober 2026 | Selesai |

## F8-FAIL-01: MySQL lokal tidak aktif

### Langkah reproduksi

1. Pastikan layanan MySQL Laragon dalam keadaan berhenti.
2. Buka terminal pada direktori proyek.
3. Jalankan `php artisan migrate:status`.
4. Perintah gagal dengan `SQLSTATE[HY000] [2002]` karena koneksi ke port `3306` ditolak.

### Dampak

Status migration pada database MySQL lokal belum dapat diperiksa. Struktur migration tetap berhasil diverifikasi melalui SQLite ketika seluruh tes dijalankan.

### Tindakan perbaikan

1. Server MySQL bawaan Laragon sudah dijalankan menggunakan konfigurasi `my.ini` Laragon.
2. Koneksi pada `127.0.0.1:3306` sudah diperiksa dengan `mysqladmin ping` dan menghasilkan `mysqld is alive`.
3. `php artisan migrate:status` berhasil dijalankan.
4. Seluruh sembilan migration berstatus `Ran`; tidak ada migration yang tertunda.

## F8-FAIL-02: Tipe data harga pada assertion tes

### Langkah reproduksi

1. Jalankan `php -d extension=pdo_sqlite -d extension=sqlite3 vendor/bin/phpunit tests/Feature/CompleteApplicationFlowTest.php`.
2. Tes mengedit harga menu menjadi `25000`.
3. Assertion membandingkan respons API dengan string `25000.00`.
4. Tes gagal karena respons JSON mengembalikan angka `25000`.

### Dampak

Tidak ada dampak pada aplikasi. Kegagalan hanya berasal dari tipe nilai yang salah pada assertion tes.

### Tindakan perbaikan

Assertion sudah disesuaikan dengan kontrak JSON aktual. Tes alur lengkap kemudian lulus dengan 32 assertion.

## F8-FAIL-03: Riwayat terhapus saat akun kasir dihapus

### Langkah reproduksi

1. Buat akun kasir dan simpan satu transaksi milik akun tersebut.
2. Hapus akun melalui endpoint profil.
3. Pada skema lama, foreign key `transaksis.user_id` memakai `ON DELETE CASCADE` sehingga transaksi ikut terhapus.

### Tindakan perbaikan

Foreign key diubah menjadi `ON DELETE RESTRICT`. Controller profil juga menolak penghapusan akun yang mempunyai riwayat transaksi dengan pesan validasi yang jelas.

## F8-FAIL-04: Nama menu lama berubah pada riwayat

### Langkah reproduksi

1. Simpan transaksi untuk sebuah menu.
2. Ubah nama dan harga menu tersebut.
3. Buka detail riwayat transaksi lama.
4. Pada skema lama, nama pada riwayat mengikuti data menu terbaru.

### Tindakan perbaikan

Detail transaksi sekarang menyimpan `nama_menu` dan `harga_satuan` sebagai snapshot. Tampilan daftar, modal, detail, dan struk membaca snapshot tersebut. Sebanyak 30 detail transaksi lama berhasil dibackfill tanpa nilai snapshot kosong.

## Verifikasi F8-04

Audit ulang autentikasi, pengelolaan menu, menu publik, dan riwayat dilakukan pada 3 Oktober 2026 setelah pengujian integrasi.

- Autentikasi internal, login gagal, logout, dan pencatatan login terakhir berfungsi.
- Registrasi publik, reset password melalui email, dan verifikasi email tetap nonaktif.
- Route kelola menu dan riwayat hanya dapat diakses setelah login.
- Tambah, edit, validasi, status ketersediaan, soft delete, dan pemulihan menu berfungsi.
- Menu yang dihapus tidak tampil pada menu publik, tetapi detail transaksi lama tetap dapat dibaca di riwayat.
- Daftar riwayat, detail transaksi, filter, urutan terbaru, dan kondisi kosong berfungsi.
- Pengujian terarah menghasilkan 32 tes dan 147 assertion lulus.

Tidak ditemukan kegagalan fungsional terbuka pada area autentikasi, menu, dan riwayat. Tidak diperlukan branch perbaikan tambahan untuk F8-04.

## Verifikasi F8-05

Audit ulang database, keranjang, transaksi, dan statistik penjualan dilakukan pada 3 Oktober 2026.

- Server MySQL merespons dengan `mysqld is alive`.
- Seluruh sembilan migration berstatus `Ran` dan tidak ada migration tertunda.
- Harga dan status menu pada keranjang selalu diambil ulang dari database.
- Menu habis, jumlah tidak valid, dan keranjang kosong ditolak.
- Transaksi dan detail transaksi disimpan dalam satu database transaction.
- Kegagalan penyimpanan melakukan rollback dan tidak mengosongkan keranjang.
- Keranjang dikosongkan hanya setelah checkout berhasil dan transaksi yang sama tidak dapat tersimpan dua kali.
- Metode pembayaran, subtotal, PPN 10%, dan total transaksi tersimpan dengan benar.
- Total penjualan harian mengikuti zona waktu `Asia/Jakarta`.
- Menu terlaris dihitung berdasarkan jumlah item terjual, dengan subtotal dan ID menu sebagai aturan urutan ketika jumlah sama.
- Pengujian terarah menghasilkan 16 tes dan 96 assertion lulus.

Tidak ditemukan kegagalan fungsional terbuka pada area database, keranjang, transaksi, dan statistik penjualan. Tidak diperlukan branch perbaikan tambahan untuk F8-05.

## Verifikasi F8-06

Audit ulang autentikasi, dashboard, dan frontend lintas halaman dilakukan pada 3 Oktober 2026.

- Login berhasil dan gagal, logout, pembaruan password, serta pencatatan login terakhir berfungsi.
- Route autentikasi internal tetap konsisten dan route publik yang dinonaktifkan tidak aktif kembali.
- Dashboard menampilkan kondisi data tersedia dan kosong tanpa mencampur data antar kasir.
- Kartu total penjualan, menu terlaris, label periode, grafik, dan navigasi dashboard terhubung ke data backend.
- Route dashboard dan profil tidak tertukar; status aktif sidebar sesuai dengan halaman yang dibuka.
- Pemeriksaan Blade tidak menemukan URL internal hard-coded atau karakter encoding rusak.
- Pengujian terarah menghasilkan 30 tes dan 103 assertion lulus.
- Build frontend produksi berhasil.

Tidak ditemukan kegagalan fungsional terbuka pada area autentikasi, dashboard, dan frontend lintas halaman. Tidak diperlukan branch perbaikan tambahan untuk F8-06.

## Verifikasi F8-07

Audit ulang menu publik, riwayat transaksi, dan frontend lintas halaman dilakukan pada 3 Oktober 2026.

- Menu publik menampilkan nama, kategori, harga, foto atau kondisi tanpa foto, serta status habis dari database.
- Menu yang terkena soft delete tidak muncul pada halaman publik.
- Menu berstatus habis tetap muncul dengan penanda yang jelas.
- Daftar riwayat menampilkan transaksi terbaru, kondisi kosong, filter, pencarian, dan pagination.
- Detail riwayat menampilkan kasir, metode pembayaran, item, jumlah, subtotal, PPN, dan total.
- Riwayat transaksi lama tetap dapat dibaca setelah menu berubah atau terkena soft delete.
- Route halaman publik dan riwayat tetap konsisten dengan navigasi aplikasi.
- Pemeriksaan Blade pada menu publik, riwayat, dan komponen bersama tidak menemukan URL internal hard-coded atau karakter encoding rusak.
- Pengujian terarah menghasilkan 11 tes dan 71 assertion lulus.
- Build frontend produksi berhasil.

Tidak ditemukan kegagalan fungsional terbuka pada area menu publik, riwayat, dan frontend lintas halaman. Tidak diperlukan branch perbaikan tambahan untuk F8-07.

## Verifikasi F8-09

Audit fitur mock, integritas route dan view, serta seluruh pengujian dilakukan pada 3 Oktober 2026.

- Seluruh fitur yang ditandai selesai memakai controller, database, dan alur server yang nyata.
- Empat method scaffold kosong pada `TransaksiController` (`create`, `edit`, `update`, dan `destroy`) dihapus karena tidak memiliki route dan bukan bagian dari fitur aktif.
- Seluruh referensi `view(...)` pada controller dan route memiliki file Blade yang tersedia.
- Seluruh 26 route aplikasi berhasil didaftarkan oleh Laravel.
- Route autentikasi, dashboard, profil, menu publik, kelola menu, keranjang, transaksi, dan riwayat mengarah ke handler yang tersedia.
- `welcome.blade.php` dan partial autentikasi bawaan tidak terhubung ke route aktif sehingga tidak dianggap sebagai fitur selesai.
- Seluruh suite menghasilkan 63 tes dan 277 assertion lulus.
- Laravel Pint dan build frontend produksi berhasil.

Tidak ditemukan route menuju view yang hilang atau fitur mock yang terhubung ke route aktif.

## Format Laporan Kegagalan Berikutnya

## F8-FAIL-05 — Target transaksi harian tersimpan langsung di controller

**PIC:** Bagas
**Status:** Selesai
**Tanggal selesai:** 3 Oktober 2026

Target transaksi harian sebelumnya bernilai tetap `120` di `DashboardController`, sehingga perubahan target harus dilakukan melalui kode. Target sekarang dibaca dari konfigurasi `sales.daily_transaction_target` dan dapat diatur melalui `DAILY_TRANSACTION_TARGET` pada environment. Nilai bawaan tetap 120 dan nilai minimum dibatasi menjadi 1.

## F8-FAIL-06 — Checkout belum mempunyai perlindungan idempotency

**PIC:** Bagas
**Status:** Selesai
**Tanggal selesai:** 3 Oktober 2026

Checkout sekarang mengirim UUID idempotency dan database menerapkan unique constraint gabungan pada `user_id` dan `idempotency_key`. Pengiriman ulang dengan kunci yang sama mengembalikan transaksi pertama tanpa membuat transaksi atau detail baru. Kunci dipertahankan ketika request gagal dan diganti setelah checkout berhasil, sehingga retry aman tetapi transaksi berikutnya tetap dapat disimpan.

Pengujian mencakup kunci wajib, retry oleh kasir yang sama, penggunaan kunci yang sama oleh kasir berbeda, keranjang kosong setelah transaksi selesai, serta keberadaan kunci dan metode pembayaran pada halaman keranjang.

## F8-FAIL-07 — Contoh konfigurasi database berbeda dari lingkungan pengembangan

**PIC:** Bagas
**Status:** Selesai
**Tanggal selesai:** 3 Oktober 2026

`.env.example` sebelumnya memakai SQLite, sedangkan panduan dan lingkungan pengembangan tim memakai MySQL Laragon. Contoh konfigurasi sekarang langsung memakai koneksi MySQL pada `127.0.0.1:3306`, database `kasir_kafe`, pengguna `root`, dan password kosong sesuai konfigurasi standar Laragon. Anggota tim tetap dapat mengganti nilai tersebut pada `.env` tanpa mengubah file contoh.

Setiap kegagalan baru harus mencantumkan:

- ID dan ringkasan masalah.
- Langkah reproduksi yang dapat dijalankan ulang.
- Hasil yang diharapkan dan hasil aktual.
- Dampak serta tingkat urgensi.
- PIC yang bertanggung jawab.
- Nama branch perbaikan.
- Tenggat penyelesaian.
- Status: Terbuka, Dikerjakan, Siap Direview, atau Selesai.
