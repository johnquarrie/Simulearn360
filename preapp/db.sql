-- SQL schema for Simulearn360 (MySQL)
-- Create database and tables, run this in your MySQL server
CREATE DATABASE IF NOT EXISTS `simulearn360` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `simulearn360`;

-- users table (simple prototype)
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(200) NOT NULL,
  `name` VARCHAR(120) DEFAULT '',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- games table
CREATE TABLE IF NOT EXISTS `games` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `company_name` VARCHAR(200) DEFAULT '',
  `start_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `current_round` INT DEFAULT 1
);

-- certificates
CREATE TABLE IF NOT EXISTS `certificates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `issued_date` DATE DEFAULT CURDATE()
);

-- rankings
CREATE TABLE IF NOT EXISTS `rankings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `team_name` VARCHAR(200) NOT NULL,
  `score` DECIMAL(8,3) DEFAULT 0
);

-- sample data
INSERT INTO `games` (name, company_name, current_round) VALUES
('ESZC_2025_demo','VAST MEDIA',13),
('MARKET_101','Team Alpha',5);

INSERT INTO `rankings` (team_name, score) VALUES
('Patryczek.pl',2.10),
('Pstryczek.pl',1.91),
('Imiejsce',1.69);

INSERT INTO `certificates` (user_id, title, issued_date) VALUES
(1,'Simulation Completion',CURDATE());
