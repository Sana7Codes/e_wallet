<?php
require_once __DIR__ . "/../config/connection.php";

try {
    // Ensure Database Exists
    $conn->query("CREATE DATABASE IF NOT EXISTS ewallet_db");
    $conn->select_db("ewallet_db");

    //  USERS TABLE 
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone VARCHAR(15) UNIQUE NOT NULL,
        password_hash CHAR(60) NOT NULL,
        birthdate DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql);


    //  WALLETS TABLE
    $sql = "CREATE TABLE IF NOT EXISTS wallets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        balance DECIMAL(15,2) DEFAULT 0.00 CHECK (balance >= 0),
        currency VARCHAR(10) DEFAULT 'USD',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->query($sql);

    //  TRANSACTIONS TABLE
    $sql = "CREATE TABLE IF NOT EXISTS transactions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        amount DECIMAL(15,2) NOT NULL CHECK (amount > 0),
        type ENUM('deposit', 'withdrawal', 'transfer') NOT NULL,
        status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->query($sql);

    //  KYC VERIFICATION TABLE
    $sql = "CREATE TABLE IF NOT EXISTS kyc_verification (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        document_type VARCHAR(50) NOT NULL,
        document_path VARCHAR(255) NOT NULL,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->query($sql);

    //  SYSTEM LOGS TABLE
    $sql = "CREATE TABLE IF NOT EXISTS system_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        action TEXT NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $conn->query($sql);

    echo "✅ Database migration completed successfully!";
} catch (Exception $e) {
    die("❌ Database migration failed: " . $e->getMessage());
} finally {
    $conn->close();
}
