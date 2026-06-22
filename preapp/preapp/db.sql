-- SQL schema for SimuLearn360 (MySQL)
CREATE DATABASE IF NOT EXISTS `simulearn360` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `simulearn360`;

-- ── users ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(200) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('player','instructor') NOT NULL DEFAULT 'player',
  `country` VARCHAR(10) DEFAULT NULL,
  `phone` VARCHAR(40) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── simulations (the scenarios/templates instructors run) ──
CREATE TABLE IF NOT EXISTS `simulations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT,
  `total_rounds` INT DEFAULT 6,
  `created_by` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
);

-- ── games (a running instance of a simulation a user/team joined) ──
CREATE TABLE IF NOT EXISTS `games` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `simulation_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `company_name` VARCHAR(200) DEFAULT '',
  `current_round` INT DEFAULT 1,
  `status` ENUM('active','completed') DEFAULT 'active',
  `score` DECIMAL(8,2) DEFAULT 0,
  `joined_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `deadline` DATE DEFAULT NULL,
  FOREIGN KEY (`simulation_id`) REFERENCES `simulations`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- ── decisions submitted each round ──────────────────────────
CREATE TABLE IF NOT EXISTS `decisions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `game_id` INT NOT NULL,
  `round` INT NOT NULL,
  `price` DECIMAL(10,2) DEFAULT NULL,
  `marketing_budget` DECIMAL(10,2) DEFAULT NULL,
  `production_units` INT DEFAULT NULL,
  `rd_budget` DECIMAL(10,2) DEFAULT NULL,
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE
);

-- ── results per round (computed after decisions are processed) ──
CREATE TABLE IF NOT EXISTS `results` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `game_id` INT NOT NULL,
  `round` INT NOT NULL,
  `revenue` DECIMAL(12,2) DEFAULT 0,
  `costs` DECIMAL(12,2) DEFAULT 0,
  `profit` DECIMAL(12,2) DEFAULT 0,
  `market_share` DECIMAL(5,2) DEFAULT 0,
  `rank` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE
);

-- ── certificates ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `certificates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `game_id` INT DEFAULT NULL,
  `title` VARCHAR(255) NOT NULL,
  `issued_date` DATE DEFAULT (CURRENT_DATE),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- ── sample data ──────────────────────────────────────────────
INSERT INTO `users` (first_name, last_name, email, password_hash, role) VALUES
('Alex', 'Demo', 'player@demo.com', '$2y$10$1pXJ9d2T1q1G9z1q1G9z1eJ9d2T1q1G9z1q1G9z1q1G9z1q1G9z1u', 'player'),
('Dr. Jordan', 'Smith', 'instructor@demo.com', '$2y$10$1pXJ9d2T1q1G9z1q1G9z1eJ9d2T1q1G9z1q1G9z1q1G9z1q1G9z1u', 'instructor');
-- NOTE: replace the hashes above with real password_hash('demo', PASSWORD_DEFAULT) output before relying on DB login.

INSERT INTO `simulations` (name, description, total_rounds, created_by) VALUES
('RetailSim: Fashion Brand', 'Run a fashion retail brand through pricing, marketing and inventory decisions.', 8, 2),
('StartupSim: Tech Venture', 'Grow an early-stage tech startup from seed to Series A.', 6, 2),
('SupplyChain Masters', 'Optimise a multi-tier supply chain under demand uncertainty.', 6, 2);

INSERT INTO `games` (simulation_id, user_id, company_name, current_round, status, score, deadline) VALUES
(1, 1, 'Alex Co.', 3, 'active', 78, '2026-07-01'),
(2, 1, 'Alex Ventures', 1, 'active', 62, '2026-07-10'),
(3, 1, 'Alex Logistics', 6, 'completed', 91, NULL);

INSERT INTO `results` (game_id, round, revenue, costs, profit, market_share, rank) VALUES
(1, 2, 142500, 121200, 21300, 18.4, 2),
(2, 1, 38000, 42200, -4200, 9.1, 5);

INSERT INTO `certificates` (user_id, game_id, title) VALUES
(1, 3, 'SupplyChain Masters — Completion Certificate');
