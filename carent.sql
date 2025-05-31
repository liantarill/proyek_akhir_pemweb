CREATE DATABASE carent_db;
USE carent_db;

CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    no_hp VARCHAR(15),
    profile_picture VARCHAR(255) DEFAULT 'profile.png',
    foto_ktp VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vehicle (
    id_vehicle INT AUTO_INCREMENT PRIMARY KEY,
    merk VARCHAR(50) NOT NULL,
    tipe VARCHAR(50) NOT NULL,
    tahun YEAR NOT NULL,
    no_plat VARCHAR(20) NOT NULL UNIQUE,
    harga_per_hari DECIMAL(10,2) NOT NULL,
    deskripsi TEXT,
    foto VARCHAR(255),
    status ENUM('Tersedia', 'Disewa', 'Pemeliharaan') DEFAULT 'Tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rental (
    id_rental INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_vehicle INT NOT NULL,
    rental_date DATE NOT NULL,
    return_date DATE NOT NULL,
    total_price DECIMAL(15,2) NOT NULL,
    rental_status ENUM('Menunggu Verifikasi', 'Terverifikasi', 'Ditolak') DEFAULT 'Menunggu Verifikasi',
    payment_proof VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id_user),
    FOREIGN KEY (id_vehicle) REFERENCES vehicle(id_vehicle)
);

INSERT INTO admin (username, password) VALUES ('admin', '$2y$12$tT7oApo21LEZTjB62pr/AecKrKOc6GymKGJ/GyONTMxo2NhioDLKy');
