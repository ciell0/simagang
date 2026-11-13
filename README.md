# SIMAGANG

Sistem Informasi Pendaftaran Magang, dibangun dengan Laravel 12, Blade, Tailwind CSS, dan MySQL. Fase 1 menyediakan fondasi aplikasi, autentikasi Breeze, role admin/pelamar, dan skema basis data inti.

## Persyaratan

- PHP 8.3 atau lebih baru melalui XAMPP, dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, dan `zip` aktif.
- Composer, Git, Node.js dan npm.
- MySQL/MariaDB dari XAMPP.

Jika Composer tidak dapat mengekstrak paket, buka `C:\xampp\php\php.ini`, aktifkan `extension=zip` (hapus tanda `;` di depannya), lalu buka ulang terminal.

## Instalasi XAMPP

1. Jalankan Apache dan MySQL dari XAMPP Control Panel.
2. Buat basis data bernama `simagang` di phpMyAdmin dengan kolasi `utf8mb4_unicode_ci`.
3. Buka terminal di folder proyek, lalu jalankan `composer install`.
4. Salin `.env.example` menjadi `.env` dengan `Copy-Item .env.example .env`. Untuk pemasangan awal, isi sementara `DB_USERNAME` dan `DB_PASSWORD` dengan akun MySQL yang memiliki hak DDL pada database tersebut.
5. Isi `SIMAGANG_DEMO_PASSWORD` di `.env` dengan kata sandi lokal minimal 8 karakter. Nilai ini dipakai untuk seluruh akun demo dan tidak disimpan di repositori.
6. Jalankan `php artisan key:generate`.
7. Jalankan `php artisan migrate --seed` menggunakan akun DDL.
8. Di phpMyAdmin sebagai administrator MySQL, impor `database/sql/02_index_view.sql`, `03_stored_procedure.sql`, `04_function.sql`, dan `05_trigger.sql`. Migration Laravel sudah membuat `v_rekap_pelamar`; skrip indeks/view dapat dijalankan untuk memasang indeks tambahan dan menyelaraskan definisi view.
9. Buat akun runtime aplikasi di tab SQL phpMyAdmin. Ganti nilai placeholder password dengan kata sandi pilihan Anda:

	```sql
	CREATE USER 'simagang_user'@'127.0.0.1' IDENTIFIED BY 'GANTI_DENGAN_PASSWORD_KUAT';
	GRANT SELECT, INSERT, UPDATE, DELETE ON simagang.* TO 'simagang_user'@'127.0.0.1';
	FLUSH PRIVILEGES;
	```

10. Atur `DB_USERNAME=simagang_user` dan `DB_PASSWORD` ke password tadi pada `.env`. Akun ini tidak memiliki hak DDL atau akses ke database lain; jalankan migration dan impor SQL sebagai administrator.
11. Jalankan `npm install` lalu `npm run build`.
12. Jalankan aplikasi dengan `php artisan serve`, kemudian buka `http://127.0.0.1:8000`.

Skrip `database/sql/01_dml.sql` dan `06_transaction.sql` adalah contoh latihan. Skrip DML memakai satu judul data latihan dan menghapus hanya baris tersebut; transaksi menggunakan ID `0` sebagai placeholder aman. Ganti ID hanya pada database uji.

Untuk memakai Apache langsung, arahkan DocumentRoot ke folder `public/` proyek dan pastikan aturan rewrite Apache aktif.

## Akun Demo

Semua akun menggunakan nilai `SIMAGANG_DEMO_PASSWORD` dari `.env`:

- Admin: `admin@simagang.test`
- Pelamar: `pelamar1@simagang.test` sampai `pelamar5@simagang.test`

Notifikasi verifikasi email Breeze menggunakan mailer `log`; tautannya dicatat di `storage/logs/laravel.log` sehingga tidak memerlukan server SMTP lokal.

## Fase 1

- Autentikasi Breeze dengan Blade dan Tailwind CSS.
- Registrasi pelamar dengan NIM, nomor HP, email, serta kata sandi tervalidasi.
- Middleware `role:admin` dan `role:pelamar`, dengan dashboard terpisah.
- Migrasi tabel `users`, `lowongan`, `pendaftaran`, `log_status`, `request_logs`, dan `alert_logs`.
- Seeder satu admin, lima pelamar, dan enam lowongan; aman dijalankan ulang.
- Laravel Pint, Larastan (PHPStan level 5), PHPUnit, dan Debugbar sebagai dependensi pengembangan.

## Pemeriksaan

```powershell
vendor/bin/phpunit
vendor/bin/pint --test
vendor/bin/phpstan analyse
```

Debugbar hanya dipasang sebagai dependensi pengembangan. Untuk menonaktifkannya secara eksplisit di mesin lokal, set `DEBUGBAR_ENABLED=false` di `.env`.

## Keputusan Fase 1

- Laravel 12 dipilih karena kompatibel dengan PHP XAMPP 8.3+ yang tersedia dan menjadi versi target proyek.
- NIM dan nomor HP wajib diisi saat pelamar mendaftar; NIM admin boleh kosong.
- Penghapusan akun menghapus data pendaftaran terkait melalui foreign key cascade.
- Akun demo hanya untuk pengembangan lokal; ganti kata sandi sebelum penggunaan selain lokal.

## Fase 2

- Admin dapat membuat, mengubah, menutup/membuka, dan menghapus lowongan yang belum memiliki pendaftar.
- Pelamar dapat mencari judul, memfilter divisi, melihat detail, dan menelusuri katalog dengan 9 lowongan per halaman.
- Form pendaftaran memvalidasi IPK, semester, pengalaman organisasi, dan CV PDF maksimal 2 MB. Berkas disimpan dengan nama acak di `storage/app/public/cv`.
- Setiap pelamar hanya dapat mendaftar sekali untuk satu lowongan; lowongan tertutup atau melewati tenggat ditolak kembali di sisi server.
- Status pendaftaran dapat dilihat pada halaman “Pendaftaran saya”; data dibatasi ke akun yang sedang masuk.
- Dari dashboard pelamar, alur sampai pengiriman pendaftaran membutuhkan maksimal 4 klik.

## Fase 3

- `database/sql/01_dml.sql` berisi contoh INSERT, UPDATE, DELETE, SELECT, dan JOIN; berkas 02-06 berisi indeks/view, stored procedure, function, trigger, dan transaksi.
- Migration membuat view `v_rekap_pelamar`; halaman rekap admin membacanya langsung.
- Penerimaan pelamar dan pengurangan kuota dijalankan dalam `DB::transaction` dengan penguncian baris, pemeriksaan status, dan rollback jika kuota habis.
- Akun runtime `simagang_user` hanya diberi hak SELECT, INSERT, UPDATE, dan DELETE pada `simagang.*`. Jalankan migration dan skrip DDL memakai akun administrator, lalu gunakan akun terbatas untuk aplikasi.
