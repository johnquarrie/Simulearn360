<?php
require_once 'includes/config.php';
if (is_logged_in()) redirect('dashboard.php');

$sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf_token'] ?? '')) {
    $email = trim($_POST['email'] ?? '');
    // In production: look up user, generate a signed reset token, email it.
    // We always show success to avoid leaking which emails are registered.
    $sent = true;
}

$page_title = 'Reset Password';
include 'includes/head.php';
?>
<div class="auth-wrapper">
  <div class="auth-bg">
    <div class="auth-bg-slides">
      <div class="auth-bg-slide active" style="background-image: url('https://images.unsplash.com/photo-1553877522-43269d4ea984?w=1400&q=80');"></div>
    </div>
    <div class="auth-bg-overlay"></div>
    <div class="auth-bg-content">
      <div class="auth-brand">
        <div class="auth-brand-logo">SL</div>
        <div class="auth-brand-name">Simu<span>Learn</span>360</div>
      </div>
      <div class="auth-bg-tagline">
        <h1>Forgot something? <span>No worries.</span></h1>
        <p>We'll send you a link to get back into your account in no time.</p>
      </div>
    </div>
  </div>

  <div class="auth-panel">
    <div class="auth-panel-inner">
      <h2 class="auth-panel-title">Reset your password</h2>
      <p class="auth-panel-subtitle">Enter your email and we'll send you a reset link</p>

      <?php if ($sent): ?>
        <div class="alert alert-success">
          <i class="fa fa-circle-check"></i> If an account exists for that email, a reset link is on its way.
        </div>
      <?php else: ?>
        <form method="post">
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
          <div class="form-group">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-input" placeholder="you@example.com" required autofocus>
          </div>
          <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send Reset Link</button>
        </form>
      <?php endif; ?>

      <div class="auth-footer-link">
        <a href="index.php"><i class="fa fa-arrow-left"></i> Back to Sign In</a>
      </div>
    </div>
    <div class="auth-copyright">© <?= date('Y') ?> SimuLearn360. All rights reserved.</div>
  </div>
</div>
<script src="js/app.js"></script>
</body>
</html>
