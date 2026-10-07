CREATE DATABASE IF NOT EXISTS gymnastics_db;
USE gymnastics_db;

CREATE TABLE IF NOT EXISTS gymnasts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    membership_id VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    contact_no VARCHAR(15) NOT NULL,
    dob DATE NOT NULL,
    training_program ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    enrollment_date DATE NOT NULL,
    status ENUM('ACTIVE', 'ON_HOLD', 'COMPLETED', 'PENDING') DEFAULT 'ACTIVE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);