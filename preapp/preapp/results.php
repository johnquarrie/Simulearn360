<?php
require_once 'includes/config.php';
require_login();
$current_page = 'results';
$page_title   = 'My Results';
$user = current_user();

$results = db_fetch_all(
    'SELECT s.name AS sim, r.round, r.revenue, r.costs, r.profit, r.market_share, r.rank, r.created_at
     FROM results r JOIN games g ON g.id = r.game_id JOIN simulations s ON s.id = g.simulation_id
     WHERE g.user_id = ? ORDER BY r.created_at DESC',
    [$user['id']]
);
if (!$results) {
    $results = [
        ['sim' => 'RetailSim',  'round' => 2, 'revenue' => 142500, 'costs' => 121200, 'profit' => 21300, 'market_share' => 18.4, 'rank' => 2, 'created_at' => '2026-06-18'],
        ['sim' => 'StartupSim', 'round' => 1, 'revenue' => 38000,  'costs' => 42200,  'profit' => -4200,  'market_share' => 9.1,  'rank' => 5, 'created_at' => '2026-06-15'],
    ];
}

include 'includes/head.php';
include 'includes/dashboard_layout.php';
?>

<div class="page-header">
  <div class="breadcrumb">
    <a href="dashboard.php">Home</a>
    <span class="sep">/</span>
    <span>My Results</span>
  </div>
  <h1>My Results</h1>
  <p>Round-by-round performance across all your simulations.</p>
</div>

<div class="card">
  <div class="card-body" style="padding-top:16px;">
    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Simulation</th><th>Round</th><th>Revenue</th><th>Costs</th><th>Profit</th><th>Market Share</th><th>Rank</th><th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($results as $r): ?>
            <tr>
              <td><?= e($r['sim']) ?></td>
              <td><?= (int)$r['round'] ?></td>
              <td>$<?= number_format((float)$r['revenue']) ?></td>
              <td>$<?= number_format((float)$r['costs']) ?></td>
              <td class="<?= $r['profit'] >= 0 ? 'text-success' : 'text-danger' ?>">
                <?= $r['profit'] >= 0 ? '+' : '' ?>$<?= number_format((float)$r['profit']) ?>
              </td>
              <td><?= number_format((float)$r['market_share'], 1) ?>%</td>
              <td><span class="badge badge-blue">#<?= e($r['rank']) ?></span></td>
              <td class="text-sm text-muted"><?= e(substr($r['created_at'], 0, 10)) ?></td>
            </tr>
          <?php endforeach; ?>
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
