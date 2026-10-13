# Menjalankan TeamSakit di KSWEB (Android)

Panduan ini untuk menjalankan website secara lokal di HP Android. Jangan membuka lab vulnerable ke internet.

## 1. Siapkan repo

Unduh ZIP repo dari GitHub atau clone repo menggunakan Termux, lalu ekstrak ke folder yang mudah diakses KSWEB. Pastikan struktur folder tetap utuh, khususnya `app/`, `database/`, dan `public/`.

Atur Document Root web server KSWEB ke folder `public/` di dalam repo. Jangan menjadikan root repo atau folder `app/` sebagai Document Root. Gunakan Apache jika tersedia karena URL seperti `/labs/L01` memerlukan aturan rewrite dari `public/.htaccess`.

## 2. Siapkan PHP

Pastikan versi PHP yang dipilih KSWEB kompatibel dengan PHP 8.x dan ekstensi PDO MySQL aktif. Website menggunakan `PDO` dan `pdo_mysql`.

Jika muncul pesan `could not find driver`, aktifkan ekstensi PDO MySQL pada konfigurasi PHP KSWEB atau pilih runtime PHP KSWEB yang menyediakannya. Jika URL `/labs/L01` menghasilkan 404 sementara halaman utama terbuka, periksa dukungan `mod_rewrite` dan apakah Apache mengizinkan aturan `.htaccess`.

## 3. Siapkan database MySQL

1. Mulai layanan MySQL dari KSWEB.
2. Buat database bernama `teamsakit_lab` (atau gunakan nama lain dan samakan konfigurasinya).
3. Impor file `database/init.sql` menggunakan phpMyAdmin yang tersedia di instalasi KSWEB atau alat database lain.
4. Pastikan tabel `articles` dan `users` berhasil dibuat dan tabel `articles` berisi tiga artikel dummy.

Port MySQL KSWEB bisa berbeda dari nilai umum 3306. Gunakan port yang benar-benar ditampilkan di pengaturan KSWEB.

## 4. Buat konfigurasi lokal

Di folder `app/`, salin `config.local.example.php` menjadi `config.local.php`, lalu sesuaikan database dan kredensial. File `config.local.php` sudah dikecualikan dari Git.

Contoh konfigurasi untuk database lokal:

```php
<?php
return [
    'APP_ENV' => 'development',
    'APP_MODE' => 'fixed',
    'LAB_ENABLED' => 'false',
    'LAB_USERNAME' => 'username-lab-milikmu',
    'LAB_PASSWORD' => 'ganti-dengan-password-panjang-dan-unik',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'teamsakit_lab',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
];
```

Contoh di atas hanya template. Ganti nama pengguna, password, port, dan kredensial sesuai pengaturan MySQL KSWEB. Jangan menggunakan password contoh pada server publik.

Docker/hosting tetap menggunakan environment variables; nilai environment variables akan mengalahkan nilai dalam file konfigurasi lokal.

## 5. Buka website

Buka alamat lokal dan port HTTP yang ditampilkan KSWEB, misalnya `http://127.0.0.1:8080/`. Port contoh ini bukan jaminan port KSWEB milikmu.

Periksa:
- Beranda tampil dan tiga artikel dummy muncul.
- `/article/mulai-dari-rumah` membuka artikel.
- `/labs/` menghasilkan 404 saat `LAB_ENABLED=false`.
- PHP tidak menampilkan warning atau detail kredensial database ke browser.

## 6. Mengaktifkan lab lokal

Secara default, biarkan `LAB_ENABLED=false` dan `APP_MODE=fixed`.

Untuk latihan pada HP sendiri, setelah database dan akses lokal berhasil, ubah nilai konfigurasi lokal menjadi `LAB_ENABLED=true`, tetapkan kredensial Basic Auth yang kuat, dan pilih `APP_MODE=vulnerable` hanya jika ingin membandingkan skenario rentan dengan versi fixed. Buka `/labs/`; browser akan meminta username dan password.

**Peringatan:** mode vulnerable sengaja mengandung perilaku tidak aman. Jangan mengaktifkannya pada website publik, jangan meneruskan port HP ke internet, dan gunakan hanya data dummy. Kembalikan ke `LAB_ENABLED=false` dan `APP_MODE=fixed` setelah latihan.

## 7. Batas kompatibilitas

- KSWEB dan versi PHP/MySQL yang dibundel berbeda-beda; konfigurasi perlu diperiksa di perangkat.
- KSWEB tidak menjalankan `docker compose`. Docker tetap menjadi jalur terpisah untuk pengembangan/hosting.
- Jika KSWEB memakai Nginx dan tidak menerapkan `.htaccess`, aturan rewrite Apache ini tidak berlaku. Gunakan Apache atau tambahkan konfigurasi rewrite Nginx yang sesuai.
- Panduan ini belum menggantikan pengujian runtime di perangkat; verifikasi semua URL dan skenario setelah instalasi.
