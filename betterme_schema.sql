-- ------------------------------------------------------------
-- BetterMe Habit Tracking System - Database Schema
-- Course: CYB325 - Web Application Development
-- ------------------------------------------------------------


CREATE DATABASE IF NOT EXISTS betterme
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE betterme;



CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS habits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    duration_days INT NOT NULL,                   -- 30 / 60 / 90
    tracking_type ENUM('daily','weekly') NOT NULL,
    reminders_enabled TINYINT(1) NOT NULL DEFAULT 0,
    start_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;



CREATE TABLE IF NOT EXISTS habit_checkins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    habit_id INT NOT NULL,
    checkin_date DATE NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (habit_id) REFERENCES habits(id) ON DELETE CASCADE,
    
    -- منع تسجيل نفس اليوم أكثر من مرة
    UNIQUE KEY unique_checkin (habit_id, checkin_date)
) ENGINE=InnoDB;

