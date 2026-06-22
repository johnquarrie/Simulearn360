<?php
require_once 'includes/config.php';
require_login();
$current_page = 'leaderboard';
$page_title   = 'Leaderboard';
$user = current_user();

$rows = db_fetch_all(
    'SELECT u.first_name, u.last_name, g.user_id, g.score, s.name AS sim_name
     FROM games g JOIN users u ON u.id = g.user_id JOIN simulations s ON s.id = g.simulation_id
     ORDER BY g.score DESC LIMIT 50'
);
if (!$rows) {
    $rows = [
        ['first_name' => 'Alex',  'last_name' => 'Demo',  'user_id' => 1, 'score' => 91, 'sim_name' => 'SupplyChain Masters'],
        ['first_name' => 'Maria', 'last_name' => 'K.',    'user_id' => 9, 'score' => 87, 'sim_name' => 'RetailSim'],
        ['first_name' => 'James', 'last_name' => 'L.',    'user_id' => 8, 'score' => 84, 'sim_name' => 'StartupSim'],
        ['first_name' => 'Priya', 'last_name' => 'S.',    'user_id' => 7, 'score' => 79, 'sim_name' => 'RetailSim'],
        ['first_name' => 'Chen',  'last_name' => 'W.',    'user_id' => 6, 'score' => 76, 'sim_name' => 'SupplyChain Masters'],
    ];
}

include 'includes/head.php';
include 'includes/dashboard_layout.php';
?>

<div class="page-header">
  <div class="breadcrumb">
    <a href="dashboard.php">Home</a>
    <span class="sep">/</span>
    <span>Leaderboard</span>
  </div>
  <h1>Global Leaderboard</h1>
  <p>Top performers across all simulations.</p>
</div>

<div class="card">
  <div class="card-body" style="padding-top:16px;">
    <div class="table-wrapper">
      <table>
        <thead>
          <tr><th>Rank</th><th>Player</th><th>Simulation</th><th>Score</th></tr>
        </thead>
        <tbody>
          <?php $i = 1; foreach ($rows as $r): $is_you = (int)$r['user_id'] === (int)$user['id']; ?>
            <tr>
              <td>
                <span class="fw-700" style="color: <?= $i<=3 ? 'var(--accent-warn)' : 'var(--text-muted)' ?>">#<?= $i ?></span>
              </td>
              <td class="<?= $is_you ? 'fw-600 text-accent' : '' ?>">
                <?= e($r['first_name'] . ' ' . $r['last_name']) ?><?= $is_you ? ' (you)' : '' ?>
              </td>
              <td class="text-sm text-muted"><?= e($r['sim_name']) ?></td>
              <td><span class="badge badge-blue"><?= (float)$r['score'] ?></span></td>
            </tr>
          <?php $i++; endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

  </main>
</div><!-- /.app-layout -->

<div class="toast-container" id="toastContainer"></div>
<script src="js/app.js"></script>
</body>
</html>
