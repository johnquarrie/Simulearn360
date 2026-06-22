<?php
require_once 'includes/config.php';
if (is_logged_in()) redirect('dashboard.php');

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request.';
    } else {
        $role       = in_array($_POST['role'] ?? '', ['player','instructor']) ? $_POST['role'] : 'player';
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name  = trim($_POST['last_name'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $password   = $_POST['password'] ?? '';
        $country    = trim($_POST['country'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');
        $agreed     = !empty($_POST['terms']);
        $access_key = trim($_POST['access_key'] ?? '');

        if (!$first_name) $errors[] = 'First name is required.';
        if (!$last_name)  $errors[] = 'Last name is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if (!$agreed)     $errors[] = 'You must accept the Terms of Service.';
        if ($role === 'instructor' && !$access_key) $errors[] = 'Instructor access key is required.';

        if (!$errors) {
            // TODO: Insert into DB
            // $hash = password_hash($password, PASSWORD_DEFAULT);
            $success = true;
        }
    }
}

$page_title = 'Create Account';
include 'includes/head.php';
?>

<div class="auth-wrapper">

  <!-- Left panel -->
  <div class="auth-bg">
    <div class="auth-bg-slides">
      <div class="auth-bg-slide active" style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1400&q=80');"></div>
    </div>
    <div class="auth-bg-overlay"></div>
    <div class="auth-bg-content">
      <div class="auth-brand">
        <div class="auth-brand-logo">SL</div>
        <div class="auth-brand-name">Simu<span>Learn</span>360</div>
      </div>
      <div class="auth-bg-tagline">
        <h1>Start your <span>journey</span> today.</h1>
        <p>Join thousands of students and educators in the world's most engaging business simulation platform.</p>
      </div>
      <div class="auth-bg-stats">
        <div class="auth-stat-item"><div class="auth-stat-number">Free</div><div class="auth-stat-label">To Join</div></div>
        <div class="auth-stat-item"><div class="auth-stat-number">2 min</div><div class="auth-stat-label">Setup</div></div>
        <div class="auth-stat-item"><div class="auth-stat-number">∞</div><div class="auth-stat-label">Scenarios</div></div>
      </div>
    </div>
  </div>

  <!-- Right panel -->
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
      <h2 class="auth-panel-title">Create your account</h2>
      <p class="auth-panel-subtitle">Choose your role to get started</p>

      <?php if ($success): ?>
        <div class="alert alert-success">
          <i class="fa fa-circle-check"></i> Account created successfully! <a href="index.php">Sign in</a>
        </div>
      <?php else: ?>

        <?php foreach ($errors as $e): ?>
          <div class="alert alert-danger"><i class="fa fa-circle-exclamation"></i> <?= e($e) ?></div>
        <?php endforeach; ?>

        <form method="POST" action="register.php" id="registerForm">
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
          <input type="hidden" name="role" id="roleInput" value="player">

          <!-- Role selection -->
          <div class="role-cards mb-24">
            <div class="role-card selected" id="rolePlayer" onclick="selectRole('player')">
              <div class="role-card-icon">🎮</div>
              <div class="role-card-title">Player</div>
              <div class="role-card-desc">Student, trainee, or participant in simulations</div>
            </div>
            <div class="role-card" id="roleInstructor" onclick="selectRole('instructor')">
              <div class="role-card-icon">🎓</div>
              <div class="role-card-title">Instructor</div>
              <div class="role-card-desc">Teacher, coach, or simulation administrator</div>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">First Name</label>
              <input type="text" name="first_name" class="form-input" placeholder="Alex" value="<?= e($_POST['first_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
              <label class="form-label">Last Name</label>
              <input type="text" name="last_name" class="form-input" placeholder="Johnson" value="<?= e($_POST['last_name'] ?? '') ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-input" placeholder="you@example.com" value="<?= e($_POST['email'] ?? '') ?>" required>
          </div>

          <div class="form-group">
            <label class="form-label">Password</label>
            <div class="form-input-icon">
              <input type="password" name="password" class="form-input" id="pwdInput" placeholder="Min. 8 characters" required>
              <i class="fa fa-eye icon" onclick="togglePwd()"></i>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Country</label>
              <select name="country" class="form-input">
                <option value="">Select country…</option>
                <option value="PL">Poland</option>
                <option value="DE">Germany</option>
                <option value="GB">United Kingdom</option>
                <option value="US">United States</option>
                <option value="NG">Nigeria</option>
                <option value="FR">France</option>
                <option value="OTHER">Other</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Phone (optional)</label>
              <input type="tel" name="phone" class="form-input" placeholder="+1 234 567 890" value="<?= e($_POST['phone'] ?? '') ?>">
            </div>
          </div>

          <!-- Instructor-only field -->
          <div class="form-group" id="accessKeyGroup" style="display:none;">
            <label class="form-label">Instructor Access Key</label>
            <input type="text" name="access_key" class="form-input" placeholder="Provided by your institution" value="<?= e($_POST['access_key'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label class="form-check">
              <input type="checkbox" name="terms" <?= !empty($_POST['terms']) ? 'checked' : '' ?>>
              <span class="form-check-label">
                I have read and agree to the <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy Policy</a>
              </span>
            </label>
          </div>

          <div class="form-group">
            <label class="form-check">
              <input type="checkbox" name="marketing">
              <span class="form-check-label">Send me updates about new simulations and features (optional)</span>
            </label>
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="fa fa-user-plus"></i> Create Account
          </button>
        </form>

      <?php endif; ?>

      <div class="auth-footer-link">
        Already have an account? <a href="index.php">Sign in</a>
      </div>
    </div>

    <div class="auth-copyright">© <?= date('Y') ?> SimuLearn360. All rights reserved.</div>
  </div>
</div>

<script src="js/app.js"></script>
<script>
function selectRole(role) {
  document.getElementById('roleInput').value = role;
  document.getElementById('rolePlayer').classList.toggle('selected', role === 'player');
  document.getElementById('roleInstructor').classList.toggle('selected', role === 'instructor');
  document.getElementById('accessKeyGroup').style.display = role === 'instructor' ? 'block' : 'none';
}
<?php if (!empty($_POST['role']) && $_POST['role'] === 'instructor'): ?>
selectRole('instructor');
<?php endif; ?>
</script>
</body>
</html>
