CREATE DATABASE IF NOT EXISTS ruangrelawan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ruangrelawan;

CREATE TABLE akun (
 id_akun VARCHAR(36) PRIMARY KEY,
 nama VARCHAR(100) NOT NULL,
 email VARCHAR(100) NOT NULL UNIQUE,
 kata_sandi_hash VARCHAR(255) NOT NULL,
 peran ENUM('user','community','admin') NOT NULL,
 dibuat_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 diperbarui_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE profil_user (
 id_profil_user VARCHAR(36) PRIMARY KEY,
 id_akun VARCHAR(36) NOT NULL UNIQUE,
 foto_profil VARCHAR(255),
 no_hp VARCHAR(20),
 jenis_kelamin VARCHAR(20),
 tanggal_lahir DATE,
 alamat TEXT,
 preferensi_lokasi VARCHAR(100),
 dibuat_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 diperbarui_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_profil_akun FOREIGN KEY (id_akun) REFERENCES akun(id_akun) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE community (
 id_community VARCHAR(36) PRIMARY KEY,
 id_akun VARCHAR(36) NOT NULL UNIQUE,
 nama_community VARCHAR(100) NOT NULL,
 deskripsi TEXT,
 logo VARCHAR(255),
 alamat TEXT,
 kota VARCHAR(100),
 kontak VARCHAR(100),
 status_verifikasi ENUM('belum','terverifikasi') NOT NULL DEFAULT 'belum',
 dibuat_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 diperbarui_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_community_akun FOREIGN KEY (id_akun) REFERENCES akun(id_akun) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE kategori_aktivitas (
 id_kategori VARCHAR(36) PRIMARY KEY,
 nama_kategori VARCHAR(100) NOT NULL UNIQUE,
 deskripsi TEXT
) ENGINE=InnoDB;

CREATE TABLE aktivitas (
 id_aktivitas VARCHAR(36) PRIMARY KEY,
 id_community VARCHAR(36) NOT NULL,
 id_kategori VARCHAR(36) NOT NULL,
 judul VARCHAR(150) NOT NULL,
 deskripsi TEXT NOT NULL,
 lokasi VARCHAR(150) NOT NULL,
 tanggal_mulai DATETIME NOT NULL,
 tanggal_selesai DATETIME NOT NULL,
 batas_pendaftaran DATETIME,
 kuota_relawan INT NOT NULL,
 target_dampak TEXT,
 tautan_whatsapp VARCHAR(255),
 donasi_aktif BOOLEAN NOT NULL DEFAULT FALSE,
 target_donasi DECIMAL(15,2),
 tujuan_donasi TEXT,
 nama_bank VARCHAR(100),
 nomor_rekening VARCHAR(50),
 nama_pemilik_rekening VARCHAR(150),
 status_aktivitas ENUM('menunggu_verifikasi','dipublikasikan','berlangsung','selesai','ditolak') NOT NULL DEFAULT 'menunggu_verifikasi',
 dibuat_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 diperbarui_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_aktivitas_community FOREIGN KEY(id_community) REFERENCES community(id_community) ON DELETE RESTRICT,
 CONSTRAINT fk_aktivitas_kategori FOREIGN KEY(id_kategori) REFERENCES kategori_aktivitas(id_kategori) ON DELETE RESTRICT,
 CONSTRAINT ck_aktivitas_tanggal CHECK (tanggal_selesai >= tanggal_mulai),
 CONSTRAINT ck_kuota_relawan CHECK (kuota_relawan >= 0),
 CONSTRAINT ck_target_donasi CHECK (target_donasi IS NULL OR target_donasi >= 0)
) ENGINE=InnoDB;

CREATE TABLE verifikasi_aktivitas (
 id_verifikasi VARCHAR(36) PRIMARY KEY,
 id_aktivitas VARCHAR(36) NOT NULL,
 id_admin VARCHAR(36) NOT NULL,
 keputusan ENUM('disetujui','ditolak') NOT NULL,
 catatan TEXT,
 tanggal_verifikasi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_verifikasi_aktivitas FOREIGN KEY(id_aktivitas) REFERENCES aktivitas(id_aktivitas) ON DELETE RESTRICT,
 CONSTRAINT fk_verifikasi_admin FOREIGN KEY(id_admin) REFERENCES akun(id_akun) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE pendaftaran_relawan (
 id_pendaftaran VARCHAR(36) PRIMARY KEY,
 id_aktivitas VARCHAR(36) NOT NULL,
 id_profil_user VARCHAR(36) NOT NULL,
 tanggal_daftar DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 status_pendaftaran ENUM('menunggu','diterima','ditolak') NOT NULL DEFAULT 'menunggu',
 catatan TEXT,
 tanggal_keputusan DATETIME,
 UNIQUE KEY uq_pendaftaran_aktivitas_user(id_aktivitas,id_profil_user),
 CONSTRAINT fk_pendaftaran_aktivitas FOREIGN KEY(id_aktivitas) REFERENCES aktivitas(id_aktivitas) ON DELETE RESTRICT,
 CONSTRAINT fk_pendaftaran_user FOREIGN KEY(id_profil_user) REFERENCES profil_user(id_profil_user) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE penyelesaian_aktivitas (
 id_penyelesaian VARCHAR(36) PRIMARY KEY,
 id_aktivitas VARCHAR(36) NOT NULL UNIQUE,
 ringkasan_pelaksanaan TEXT NOT NULL,
 hasil_aktual TEXT NOT NULL,
 tanggal_pengajuan_selesai DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_penyelesaian_aktivitas FOREIGN KEY(id_aktivitas) REFERENCES aktivitas(id_aktivitas) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE dokumentasi_aktivitas (
 id_dokumentasi VARCHAR(36) PRIMARY KEY,
 id_aktivitas VARCHAR(36) NOT NULL,
 file_dokumentasi VARCHAR(255) NOT NULL,
 keterangan TEXT,
 dibuat_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 diperbarui_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_dokumentasi_aktivitas FOREIGN KEY(id_aktivitas) REFERENCES aktivitas(id_aktivitas) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE donasi (
 id_donasi VARCHAR(36) PRIMARY KEY,
 id_aktivitas VARCHAR(36) NOT NULL,
 id_profil_user VARCHAR(36) NOT NULL,
 nama_pengirim VARCHAR(100) NOT NULL,
 nominal DECIMAL(15,2) NOT NULL,
 bukti_transfer VARCHAR(255) NOT NULL,
 status_donasi ENUM('menunggu','diterima','ditolak') NOT NULL DEFAULT 'menunggu',
 tanggal_donasi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 tanggal_pemeriksaan DATETIME,
 CONSTRAINT fk_donasi_aktivitas FOREIGN KEY(id_aktivitas) REFERENCES aktivitas(id_aktivitas) ON DELETE RESTRICT,
 CONSTRAINT fk_donasi_user FOREIGN KEY(id_profil_user) REFERENCES profil_user(id_profil_user) ON DELETE RESTRICT,
 CONSTRAINT ck_nominal_donasi CHECK (nominal > 0)
) ENGINE=InnoDB;

CREATE INDEX idx_aktivitas_status ON aktivitas(status_aktivitas);
CREATE INDEX idx_verifikasi_aktivitas_tanggal ON verifikasi_aktivitas(id_aktivitas,tanggal_verifikasi);
CREATE INDEX idx_donasi_aktivitas_status ON donasi(id_aktivitas,status_donasi);
