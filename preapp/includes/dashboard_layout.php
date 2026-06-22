<?php
require_login();
$user = current_user();
$current_page = $current_page ?? '';
?>
<div class="app-layout">

  <!-- ── Top Nav ──────────────────────────────────────────── -->
  <nav class="top-nav">
    <div class="top-nav-brand">
      <div class="top-nav-logo">SL</div>
      <div class="top-nav-name">Simu<span>Learn</span>360</div>
    </div>

    <div class="top-nav-search">
      <i class="fa fa-search icon"></i>
      <input type="text" placeholder="Search simulations, courses…">
    </div>

    <div class="top-nav-actions">
      <button class="top-nav-icon-btn" title="Notifications">
        <i class="fa fa-bell"></i>
        <span class="badge"></span>
      </button>
      <button class="top-nav-icon-btn" title="Help">
        <i class="fa fa-circle-question"></i>
      </button>
      <div class="top-nav-avatar" title="<?= e($user['first_name'] ?? 'User') ?>">
        <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1)) ?>
      </div>
    </div>
  </nav>

  <!-- ── Sidebar ──────────────────────────────────────────── -->
  <aside class="sidebar">
    <div class="sidebar-section">
      <div class="sidebar-section-title">Main</div>
      <a href="dashboard.php" class="sidebar-item <?= $current_page === 'dashboard' ? 'active' : '' ?>">
        <i class="fa fa-house icon"></i> Dashboard
      </a>
      <a href="simulations.php" class="sidebar-item <?= $current_page === 'simulations' ? 'active' : '' ?>">
        <i class="fa fa-gamepad icon"></i> Simulations
        <span class="badge">3</span>
      </a>
      <a href="leaderboard.php" class="sidebar-item <?= $current_page === 'leaderboard' ? 'active' : '' ?>">
        <i class="fa fa-trophy icon"></i> Leaderboard
      </a>
      <a href="results.php" class="sidebar-item <?= $current_page === 'results' ? 'active' : '' ?>">
        <i class="fa fa-chart-line icon"></i> My Results
      </a>
    </div>

    <?php if (($user['role'] ?? '') === 'instructor'): ?>
    <div class="sidebar-section">
      <div class="sidebar-section-title">Instructor</div>
      <a href="manage-groups.php" class="sidebar-item <?= $current_page === 'groups' ? 'active' : '' ?>">
        <i class="fa fa-users icon"></i> Groups
      </a>
      <a href="manage-simulations.php" class="sidebar-item <?= $current_page === 'manage-sims' ? 'active' : '' ?>">
        <i class="fa fa-sliders icon"></i> Manage Sims
      </a>
      <a href="reports.php" class="sidebar-item <?= $current_page === 'reports' ? 'active' : '' ?>">
        <i class="fa fa-file-lines icon"></i> Reports
      </a>
    </div>
    <?php endif; ?>

    <div class="sidebar-section">
      <div class="sidebar-section-title">Account</div>
      <a href="profile.php" class="sidebar-item <?= $current_page === 'profile' ? 'active' : '' ?>">
        <i class="fa fa-user icon"></i> Profile
      </a>
      <a href="settings.php" class="sidebar-item <?= $current_page === 'settings' ? 'active' : '' ?>">
        <i class="fa fa-gear icon"></i> Settings
      </a>
      <a href="logout.php" class="sidebar-item">
        <i class="fa fa-right-from-bracket icon"></i> Logout
      </a>
    </div>
  </aside>

  <!-- ── Main Content ─────────────────────────────────────── -->
  <main class="main-content">
