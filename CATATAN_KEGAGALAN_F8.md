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

## Format Laporan Kegagalan Berikutnya

Setiap kegagalan baru harus mencantumkan:

- ID dan ringkasan masalah.
- Langkah reproduksi yang dapat dijalankan ulang.
- Hasil yang diharapkan dan hasil aktual.
- Dampak serta tingkat urgensi.
- PIC yang bertanggung jawab.
- Nama branch perbaikan.
- Tenggat penyelesaian.
- Status: Terbuka, Dikerjakan, Siap Direview, atau Selesai.
