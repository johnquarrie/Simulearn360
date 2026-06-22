<?php
// ── SimuLearn360 Configuration ─────────────────────────────
define('APP_NAME',    'SimuLearn360');
define('APP_VERSION', '1.0.0');
define('BASE_URL',    '');  // Set to your domain, e.g. https://yourdomain.com

// Database (adjust as needed)
define('DB_HOST', 'localhost');
define('DB_NAME', 'simulearn360');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', 3306);

// Session
session_start();

// Helpers
function is_logged_in() {
    return isset($_SESSION['user_id']);
}
function require_login() {
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}
function current_user() {
    return $_SESSION['user'] ?? null;
}
function redirect($url) {
    header('Location: ' . $url);
    exit;
}
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function verify_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>