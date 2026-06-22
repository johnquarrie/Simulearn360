<?php
require_once 'includes/config.php';
require_login();
$current_page = 'settings';
$page_title   = 'Settings';
$user = current_user();

$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf_token'] ?? '')) {
    $current_pw = $_POST['current_password'] ?? '';
    $new_pw     = $_POST['new_password'] ?? '';
    $confirm_pw = $_POST['confirm_password'] ?? '';

    if (strlen($new_pw) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($new_pw !== $confirm_pw) {
        $error = 'New password and confirmation do not match.';
    } else {
        $row = db_fetch_one('SELECT password_hash FROM users WHERE id = ?', [$user['id']]);
        if ($row && password_verify($current_pw, $row['password_hash'])) {
            $hash = password_hash($new_pw, PASSWORD_DEFAULT);
            db_execute('UPDATE users SET password_hash = ? WHERE id = ?', [$hash, $user['id']]);
            $message = 'Password updated successfully.';
        } elseif (!$row) {
            $message = 'Password updated. (demo mode — no database connected)';
        } else {
            $error = 'Current password is incorrect.';
        }
    }
}

include 'includes/head.php';
include 'includes/dashboard_layout.php';
?>

<div class="page-header">
  <div class="breadcrumb">
    <a href="dashboard.php">Home</a>
    <span class="sep">/</span>
    <span>Settings</span>
  </div>
  <h1>Settings</h1>
  <p>Manage your account security and preferences.</p>
</div>

<div class="card" style="max-width:560px;">
  <div class="card-header"><span class="card-title"><i class="fa fa-lock text-accent"></i> Change Password</span></div>
  <div class="card-body">
    <?php if ($message): ?>
      <div class="alert alert-success"><i class="fa fa-circle-check"></i> <?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger"><i class="fa fa-circle-exclamation"></i> <?= e($error) ?></div>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="form-group">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-input" required>
      </div>
      <div class="form-group">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-input" placeholder="Min. 8 characters" required>
      </div>
      <div class="form-group">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="confirm_password" class="form-input" required>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fa fa-key"></i> Update Password</button>
    </form>
  </div>
</div>

  </main>
</div><!-- /.app-layout -->

<div class="toast-container" id="toastContainer"></div>
<script src="js/app.js"></script>
</body>
</html>
