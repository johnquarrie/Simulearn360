<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (is_logged_in()) redirect('dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $user     = null;

        $row = db_fetch_one('SELECT * FROM users WHERE email = ? LIMIT 1', [$email]);
        if ($row && password_verify($password, $row['password_hash'])) {
            $user = $row;
        }

        // Demo fallback so the prototype works without a DB connection
        if (!$user) {
            if ($email === 'player@demo.com' && $password === 'demo') {
                $user = ['id' => 1, 'first_name' => 'Alex', 'last_name' => 'Demo', 'role' => 'player', 'email' => $email];
            } elseif ($email === 'instructor@demo.com' && $password === 'demo') {
                $user = ['id' => 2, 'first_name' => 'Dr. Jordan', 'last_name' => 'Smith', 'role' => 'instructor', 'email' => $email];
            }
        }

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user']    = [
                'id'         => $user['id'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'role'       => $user['role'],
                'email'      => $user['email'],
            ];
            redirect('dashboard.php');
        } else {
            $error = 'Invalid email address or password.';
        }
    }
}

$page_title = 'Log In';
include 'includes/head.php';
?>

<div class="auth-wrapper">

  <!-- ── Left: Animated background ─────────────────────── -->
  <div class="auth-bg">
    <div class="auth-bg-slides">
      <div class="auth-bg-slide active" style="background-image: url('https://images.unsplash.com/photo-1553877522-43269d4ea984?w=1400&q=80');"></div>
      <div class="auth-bg-slide" style="background-image: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1400&q=80');"></div>
      <div class="auth-bg-slide" style="background-image: url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1400&q=80');"></div>
    </div>
    <div class="auth-bg-overlay"></div>

    <div class="auth-bg-content">
      <div class="auth-brand">
        <div class="auth-brand-logo">SL</div>
        <div class="auth-brand-name">Simu<span>Learn</span>360</div>
      </div>

      <div class="auth-bg-tagline">
        <h1>Learn by <span>Doing.</span><br>Lead by Example.</h1>
        <p>Experience real-world business scenarios in a safe, competitive simulation environment. Make decisions. See outcomes. Grow.</p>
        <div class="auth-bg-dots">
          <div class="auth-bg-dot active"></div>
          <div class="auth-bg-dot"></div>
          <div class="auth-bg-dot"></div>
        </div>
      </div>

      <div class="auth-bg-stats">
        <div class="auth-stat-item">
          <div class="auth-stat-number">12K+</div>
          <div class="auth-stat-label">Students</div>
        </div>
        <div class="auth-stat-item">
          <div class="auth-stat-number">340+</div>
          <div class="auth-stat-label">Simulations</div>
        </div>
        <div class="auth-stat-item">
          <div class="auth-stat-number">98%</div>
          <div class="auth-stat-label">Satisfaction</div>
        </div>
      </div>
    </div>
  </div>

  <!-- ── Right: Login form ──────────────────────────────── -->
  <div class="auth-panel">
    <div class="auth-lang">
      <button class="auth-lang-btn" onclick="toggleLang()">
        <i class="fa fa-globe"></i> English <i class="fa fa-chevron-down" style="font-size:0.7rem"></i>
      </button>
      <div class="auth-lang-dropdown" id="langDropdown">
        <a href="?lang=en">🇬🇧 English</a>
        <a href="?lang=pl">🇵🇱 Polski</a>
        <a href="?lang=de">🇩🇪 Deutsch</a>
        <a href="?lang=fr">🇫🇷 Français</a>
      </div>
    </div>

    <div class="auth-panel-inner">
      <h2 class="auth-panel-title">Welcome back</h2>
      <p class="auth-panel-subtitle">Sign in to your SimuLearn360 account</p>

      <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fa fa-circle-exclamation"></i> <?= e($error) ?></div>
      <?php endif; ?>

      <form method="POST" action="index.php">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <div class="form-group">
          <label class="form-label">Email address</label>
          <input type="email" name="email" class="form-input" placeholder="you@example.com"
                 value="<?= e($_POST['email'] ?? '') ?>" required autofocus>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="form-input-icon">
            <input type="password" name="password" class="form-input" placeholder="••••••••" id="pwdInput" required>
            <i class="fa fa-eye icon" id="pwdToggle" onclick="togglePwd()"></i>
          </div>
        </div>

        <div class="form-footer">
          <label class="form-check">
            <input type="checkbox" name="remember">
            <span class="form-check-label">Remember me</span>
          </label>
          <a href="restore-password.php" class="form-link">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fa fa-right-to-bracket"></i> Sign In
        </button>
      </form>

      <div class="auth-divider">or continue with</div>

      <div class="oauth-buttons">
        <a href="auth/google.php" class="oauth-btn">
          <svg viewBox="0 0 24 24" fill="none"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          Sign in with Google
        </a>
        <a href="auth/microsoft.php" class="oauth-btn">
          <svg viewBox="0 0 24 24" fill="none"><path d="M2 2h9.5v9.5H2V2z" fill="#f25022"/><path d="M12.5 2H22v9.5h-9.5V2z" fill="#7fba00"/><path d="M2 12.5h9.5V22H2v-9.5z" fill="#00a4ef"/><path d="M12.5 12.5H22V22h-9.5v-9.5z" fill="#ffb900"/></svg>
          Sign in with Microsoft
        </a>
      </div>

      <div class="auth-footer-link">
        Don't have an account? <a href="register.php">Create one</a>
      </div>

      <!-- Demo hint -->
      <div class="alert alert-info mt-16" style="font-size:0.8rem;">
        <i class="fa fa-circle-info"></i>
        <strong>Demo:</strong> player@demo.com / demo &nbsp;|&nbsp; instructor@demo.com / demo
      </div>
    </div>

    <div class="auth-copyright">© <?= date('Y') ?> SimuLearn360. All rights reserved.</div>
  </div>

</div>

<script src="js/app.js"></script>
</body>
</html>