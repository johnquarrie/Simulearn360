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
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Database connection (PDO) ───────────────────────────────
function db() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', DB_HOST, DB_PORT, DB_NAME);
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $ex) {
            // Fail soft: pages should still render with empty/demo data if DB is unavailable
            $pdo = false;
        }
    }
    return $pdo;
}

// ── Helpers ──────────────────────────────────────────────────
function is_logged_in() {
    return isset($_SESSION['user_id']);
}
function require_login() {
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}
function require_role($role) {
    require_login();
    $user = current_user();
    if (($user['role'] ?? '') !== $role) {
        header('Location: dashboard.php');
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
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}
function flash_set($type, $msg) {
    $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg];
}
function flash_get() {
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

// Fail-soft query helper: returns a PDOStatement on success, or null on any
// failure (no DB, missing table, bad SQL, etc). Callers should check for null
// and fall back to demo data rather than letting the page crash.
function db_query($sql, $params = []) {
    $pdo = db();
    if (!$pdo) return null;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $ex) {
        error_log('SimuLearn360 DB query failed: ' . $ex->getMessage());
        return null;
    }
}
function db_fetch_all($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt ? $stmt->fetchAll() : [];
}
function db_fetch_one($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt ? $stmt->fetch() : null;
}
function db_execute($sql, $params = []) {
    // For INSERT/UPDATE/DELETE — returns true/false instead of throwing.
    return db_query($sql, $params) !== null;
}
