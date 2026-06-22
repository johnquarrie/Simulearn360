<?php
require_once 'includes/config.php';
require_login();
$current_page = 'simulations';
$page_title   = 'Simulations';
$user = current_user();

$my_games = [];
$catalog  = [];

$my_games = db_fetch_all(
    'SELECT g.id, s.id AS sim_id, s.name, s.total_rounds, g.current_round, g.score, g.status, g.deadline
     FROM games g JOIN simulations s ON s.id = g.simulation_id
     WHERE g.user_id = ? ORDER BY g.status = "active" DESC, g.id DESC',
    [$user['id']]
);

$joined_ids = array_column($my_games, 'sim_id');
$sql = 'SELECT id, name, description, total_rounds FROM simulations';
if ($joined_ids) {
    $placeholders = implode(',', array_fill(0, count($joined_ids), '?'));
    $sql .= " WHERE id NOT IN ($placeholders)";
}
$catalog = db_fetch_all($sql, $joined_ids);

if (!$my_games && !$catalog) {
    $catalog = [
        ['id' => 1, 'name' => 'RetailSim: Fashion Brand', 'description' => 'Run a fashion retail brand through pricing, marketing and inventory decisions.', 'total_rounds' => 8],
        ['id' => 2, 'name' => 'StartupSim: Tech Venture', 'description' => 'Grow an early-stage tech startup from seed to Series A.', 'total_rounds' => 6],
        ['id' => 3, 'name' => 'SupplyChain Masters', 'description' => 'Optimise a multi-tier supply chain under demand uncertainty.', 'total_rounds' => 6],
    ];
}

include 'includes/head.php';
include 'includes/dashboard_layout.php';
?>

<div class="page-header">
  <div class="breadcrumb">
    <a href="dashboard.php">Home</a>
    <span class="sep">/</span>
    <span>Simulations</span>
  </div>
  <h1>Simulations</h1>
  <p>Your in-progress simulations and ones you can join.</p>
</div>

<?php if ($my_games): ?>
<div class="card mb-24">
  <div class="card-header">
    <span class="card-title"><i class="fa fa-gamepad text-accent"></i> My Simulations</span>
  </div>
  <div class="card-body" style="padding-top:0;">
    <?php foreach ($my_games as $sim): ?>
      <div style="padding: 16px 0; border-bottom: 1px solid var(--border);">
        <div class="d-flex align-center justify-between mb-8">
          <div>
            <div class="fw-600"><?= e($sim['name']) ?></div>
            <div class="text-sm text-muted">
              Round <?= (int)$sim['current_round'] ?> / <?= (int)$sim['total_rounds'] ?>
              <?php if ($sim['deadline']): ?>
                &nbsp;·&nbsp; Due <?= date('M j', strtotime($sim['deadline'])) ?>
              <?php endif; ?>
            </div>
          </div>
          <div class="d-flex align-center gap-12">
            <span class="badge <?= $sim['status'] === 'active' ? 'badge-blue' : 'badge-green' ?>">
              <?= ucfirst($sim['status']) ?>
            </span>
            <?php if ($sim['status'] === 'active'): ?>
              <a href="play.php?id=<?= (int)$sim['id'] ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-play"></i> Play
              </a>
            <?php else: ?>
              <a href="results.php?sim=<?= (int)$sim['id'] ?>" class="btn btn-secondary btn-sm">Results</a>
            <?php endif; ?>
          </div>
        </div>
        <div class="progress-bar">
          <div class="progress-fill <?= $sim['status'] === 'completed' ? 'green' : '' ?>"
               style="width: <?= round(($sim['current_round']/max(1,$sim['total_rounds']))*100) ?>%"></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <span class="card-title"><i class="fa fa-compass text-accent"></i> Join a New Simulation</span>
    <a href="join.php" class="btn btn-secondary btn-sm">Have a license key?</a>
  </div>
  <div class="card-body grid-3" style="padding-top:16px;">
    <?php if ($catalog): foreach ($catalog as $sim): ?>
      <div class="card" style="margin:0;">
        <div class="card-body">
          <div class="fw-600 mb-8"><?= e($sim['name']) ?></div>
          <div class="text-sm text-muted mb-16"><?= e($sim['description'] ?? '') ?></div>
          <div class="d-flex justify-between align-center">
            <span class="badge badge-gray"><?= (int)$sim['total_rounds'] ?> rounds</span>
            <a href="join.php?sim=<?= (int)$sim['id'] ?>" class="btn btn-primary btn-sm">
              <i class="fa fa-right-to-bracket"></i> Join
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; else: ?>
      <p class="text-sm text-muted">You've joined every available simulation. Nice work!</p>
    <?php endif; ?>
  </div>
</div>

  </main>
</div><!-- /.app-layout -->

<div class="toast-container" id="toastContainer"></div>
<script src="js/app.js"></script>
</body>
</html>
