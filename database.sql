CREATE DATABASE IF NOT EXISTS kearsipan_dprd
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kearsipan_dprd;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','petugas') DEFAULT 'petugas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE arsip (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_dokumen VARCHAR(100) NOT NULL,
    judul VARCHAR(255) NOT NULL,
    kategori_id INT NULL,
    tanggal_dokumen DATE NULL,
    pengirim VARCHAR(150) NULL,
    keterangan TEXT NULL,
    nama_file VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO users (nama, username, password, role)
VALUES ('Administrator', 'admin', SHA2('admin123',256), 'admin');

INSERT INTO kategori (nama_kategori) VALUES
('Surat Masuk'), ('Surat Keluar'), ('Notulen Rapat'),
('Laporan'), ('Peraturan/Dokumen Hukum'), ('Lainnya');
