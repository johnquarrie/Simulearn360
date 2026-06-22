<?php
require_once 'includes/config.php';
$current_page = 'dashboard';
$page_title   = 'Dashboard';

// Demo data — replace with DB queries
$user = ['first_name' => 'Alex', 'last_name' => 'Demo', 'role' => 'player', 'email' => 'player@demo.com'];
$_SESSION['user'] = $user; // demo only

$active_sims = [
  ['id' => 1, 'name' => 'RetailSim: Fashion Brand', 'round' => 3, 'total_rounds' => 8, 'score' => 78, 'rank' => 2, 'status' => 'active', 'deadline' => '2026-07-01'],
  ['id' => 2, 'name' => 'StartupSim: Tech Venture', 'round' => 1, 'total_rounds' => 6, 'score' => 62, 'rank' => 5, 'status' => 'active', 'deadline' => '2026-07-10'],
  ['id' => 3, 'name' => 'SupplyChain Masters',       'round' => 6, 'total_rounds' => 6, 'score' => 91, 'rank' => 1, 'status' => 'completed', 'deadline' => null],
];

$recent_results = [
  ['sim' => 'RetailSim', 'round' => 2, 'revenue' => 142500, 'profit' => 21300, 'rank' => 2, 'date' => '2026-06-18'],
  ['sim' => 'StartupSim', 'round' => 1, 'revenue' => 38000,  'profit' => -4200,  'rank' => 5, 'date' => '2026-06-15'],
];

include 'includes/head.php';
include 'includes/dashboard_layout.php';
?>

<!-- Page Header -->
<div class="page-header">
  <div class="breadcrumb">
    <a href="dashboard.php">Home</a>
    <span class="sep">/</span>
    <span>Dashboard</span>
  </div>
  <h1>Good <?= date('G') < 12 ? 'morning' : (date('G') < 18 ? 'afternoon' : 'evening') ?>, <?= e($user['first_name']) ?> 👋</h1>
  <p>Here's what's happening with your simulations today.</p>
</div>

<!-- Stats -->
<div class="stats-grid">
  <div class="stat-card blue">
    <div class="stat-card-icon"><i class="fa fa-gamepad"></i></div>
    <div class="stat-card-label">Active Simulations</div>
    <div class="stat-card-value">2</div>
    <div class="stat-card-delta up"><i class="fa fa-arrow-up"></i> 1 new this week</div>
  </div>
  <div class="stat-card green">
    <div class="stat-card-icon"><i class="fa fa-trophy"></i></div>
    <div class="stat-card-label">Best Rank</div>
    <div class="stat-card-value">#1</div>
    <div class="stat-card-delta up"><i class="fa fa-arrow-up"></i> SupplyChain Masters</div>
  </div>
  <div class="stat-card orange">
    <div class="stat-card-icon"><i class="fa fa-star"></i></div>
    <div class="stat-card-label">Avg Score</div>
    <div class="stat-card-value">77</div>
    <div class="stat-card-delta up"><i class="fa fa-arrow-up"></i> +4 pts from last round</div>
  </div>
  <div class="stat-card red">
    <div class="stat-card-icon"><i class="fa fa-clock"></i></div>
    <div class="stat-card-label">Decisions Due</div>
    <div class="stat-card-value">2</div>
    <div class="stat-card-delta down"><i class="fa fa-circle-exclamation"></i> Next: Jul 1</div>
  </div>
</div>

<!-- Active Simulations -->
<div class="card mb-24">
  <div class="card-header">
    <span class="card-title"><i class="fa fa-gamepad text-accent"></i> Active Simulations</span>
    <a href="simulations.php" class="btn btn-secondary btn-sm">View all</a>
  </div>
  <div class="card-body" style="padding-top:0;">
    <?php foreach ($active_sims as $sim): ?>
      <div style="padding: 16px 0; border-bottom: 1px solid var(--border);">
        <div class="d-flex align-center justify-between mb-8">
          <div>
            <div class="fw-600"><?= e($sim['name']) ?></div>
            <div class="text-sm text-muted">
              Round <?= $sim['round'] ?> / <?= $sim['total_rounds'] ?>
              <?php if ($sim['deadline']): ?>
                &nbsp;·&nbsp; Due <?= date('M j', strtotime($sim['deadline'])) ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="d-flex align-center gap-12">
            <span class="badge <?= $sim['status'] === 'active' ? 'badge-blue' : 'badge-green' ?>">
              <?= ucfirst($sim['status']) ?>
            </span>
            <span class="text-sm fw-600">Rank #<?= $sim['rank'] ?></span>
            <?php if ($sim['status'] === 'active'): ?>
              <a href="play.php?id=<?= $sim['id'] ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-play"></i> Play
              </a>
            <?php else: ?>
              <a href="results.php?sim=<?= $sim['id'] ?>" class="btn btn-secondary btn-sm">Results</a>
            <?php endif; ?>
          </div>
        </div>
        <div class="progress-bar">
          <div class="progress-fill <?= $sim['status'] === 'completed' ? 'green' : '' ?>"
               style="width: <?= round(($sim['round']/$sim['total_rounds'])*100) ?>%"></div>
        </div>
        <div class="d-flex justify-between mt-4">
          <span class="text-sm text-muted">Progress <?= round(($sim['round']/$sim['total_rounds'])*100) ?>%</span>
          <span class="text-sm text-muted">Score <?= $sim['score'] ?></span>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Bottom grid -->
<div class="grid-2">
  <!-- Recent Results -->
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fa fa-chart-bar text-accent"></i> Recent Results</span>
    </div>
    <div class="card-body" style="padding-top:16px;">
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>Simulation</th>
              <th>Round</th>
              <th>Revenue</th>
              <th>Profit</th>
              <th>Rank</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_results as $r): ?>
              <tr>
                <td><?= e($r['sim']) ?></td>
                <td><?= $r['round'] ?></td>
                <td>$<?= number_format($r['revenue']) ?></td>
                <td class="<?= $r['profit'] >= 0 ? 'text-success' : 'text-danger' ?>">
                  <?= $r['profit'] >= 0 ? '+' : '' ?>$<?= number_format($r['profit']) ?>
                </td>
                <td><span class="badge badge-blue">#<?= $r['rank'] ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Leaderboard preview -->
  <div class="card">
    <div class="card-header">
      <span class="card-title"><i class="fa fa-trophy text-accent"></i> Leaderboard Snapshot</span>
      <a href="leaderboard.php" class="btn btn-ghost btn-sm">Full table</a>
    </div>
    <div class="card-body" style="padding-top:16px;">
      <?php
      $lb = [
        ['rank'=>1, 'name'=>'Alex Demo',    'score'=>91, 'you'=>true],
        ['rank'=>2, 'name'=>'Maria K.',      'score'=>87, 'you'=>false],
        ['rank'=>3, 'name'=>'James L.',      'score'=>84, 'you'=>false],
        ['rank'=>4, 'name'=>'Priya S.',      'score'=>79, 'you'=>false],
        ['rank'=>5, 'name'=>'Chen W.',       'score'=>76, 'you'=>false],
      ];
      foreach ($lb as $entry): ?>
        <div class="d-flex align-center justify-between" style="padding:10px 0; border-bottom:1px solid var(--border);">
          <div class="d-flex align-center gap-12">
            <span class="fw-700 text-sm" style="width:20px; color: <?= $entry['rank']<=3 ? 'var(--accent-warn)' : 'var(--text-muted)' ?>">
              #<?= $entry['rank'] ?>
            </span>
            <div class="top-nav-avatar" style="width:28px;height:28px;font-size:0.7rem;<?= $entry['you'] ? 'background:var(--accent)' : '' ?>">
              <?= strtoupper(substr($entry['name'],0,1)) ?>
            </div>
            <span class="text-sm <?= $entry['you'] ? 'fw-600 text-accent' : '' ?>">
              <?= e($entry['name']) ?><?= $entry['you'] ? ' (you)' : '' ?>
            </span>
          </div>
          <span class="fw-600"><?= $entry['score'] ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

  </main>
</div><!-- /.app-layout -->

<div class="toast-container" id="toastContainer"></div>
<script src="js/app.js"></script>
</body>
</html>
