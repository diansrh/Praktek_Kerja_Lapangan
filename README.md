# SIKAD — Sistem Kearsipan Dokumen Digital

Prototype sistem informasi kearsipan berbasis PHP + MySQL untuk Komisi IV DPRD Kota Medan.

## Fitur
- Login admin/petugas
- Dashboard statistik
- CRUD arsip dokumen
- Upload dokumen
- Download dokumen
- Pencarian arsip
- Kategori arsip
- Metadata: nomor dokumen, judul, kategori, tanggal, pengirim, keterangan
- Relasi pengguna, kategori, dan arsip

## Instalasi XAMPP
1. Salin folder `sistem_kearsipan_digital` ke `htdocs`.
2. Jalankan Apache dan MySQL dari XAMPP.
3. Buka phpMyAdmin dan import `database.sql`.
4. Pastikan konfigurasi MySQL di `config/database.php` sesuai.
5. Buka `http://localhost/sistem_kearsipan_digital/`.
6. Login: username `admin`, password `admin123`.

## Catatan
Ini adalah prototype akademik. Untuk implementasi resmi, tambahkan HTTPS, password_hash/password_verify, CSRF protection, audit log, pembatasan akses berbasis role, validasi MIME yang lebih ketat, backup, antivirus/file scanning, dan kebijakan retensi arsip.

## Tema Tampilan
Versi ini menggunakan tema pink dengan nuansa administrasi modern.
