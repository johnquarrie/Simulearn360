<?php
require_once 'includes/config.php';
require_login();
$current_page = 'profile';
$page_title   = 'Profile';
$user = current_user();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf_token'] ?? '')) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    db_execute('UPDATE users SET first_name = ?, last_name = ? WHERE id = ?', [$first_name, $last_name, $user['id']]);
    $_SESSION['user']['first_name'] = $first_name;
    $_SESSION['user']['last_name']  = $last_name;
    $user = current_user();
    $message = 'Profile updated.';
}

include 'includes/head.php';
include 'includes/dashboard_layout.php';
?>

<div class="page-header">
  <div class="breadcrumb">
    <a href="dashboard.php">Home</a>
    <span class="sep">/</span>
    <span>Profile</span>
  </div>
  <h1>Profile</h1>
  <p>Update your personal details.</p>
</div>

<div class="card" style="max-width:560px;">
  <div class="card-body">
    <?php if ($message): ?>
      <div class="alert alert-success"><i class="fa fa-circle-check"></i> <?= e($message) ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">First Name</label>
          <input type="text" name="first_name" class="form-input" value="<?= e($user['first_name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Last Name</label>
          <input type="text" name="last_name" class="form-input" value="<?= e($user['last_name'] ?? '') ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" class="form-input" value="<?= e($user['email'] ?? '') ?>" disabled>
      </div>
      <div class="form-group">
        <label class="form-label">Role</label>
        <input type="text" class="form-input" value="<?= e(ucfirst($user['role'] ?? '')) ?>" disabled>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Save Changes</button>
    </form>
  </div>
</div>

  </main>
</div><!-- /.app-layout -->

<div class="toast-container" id="toastContainer"></div>
<script src="js/app.js"></script>
</body>
</html>
