<?php
require_once 'includes/config.php';
require_login();
$current_page = 'simulations';
$user = current_user();
$game_id = (int)($_GET['id'] ?? 0);

$game = null;
if ($game_id) {
    $game = db_fetch_one(
        'SELECT g.*, s.name AS sim_name, s.total_rounds FROM games g
         JOIN simulations s ON s.id = g.simulation_id
         WHERE g.id = ? AND g.user_id = ?',
        [$game_id, $user['id']]
    );
}

// Demo fallback
if (!$game) {
    $game = ['id' => $game_id ?: 1, 'sim_name' => 'RetailSim: Fashion Brand', 'current_round' => 3, 'total_rounds' => 8, 'score' => 78, 'company_name' => 'Alex Co.'];
}

$page_title = $game['sim_name'];
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf($_POST['csrf_token'] ?? '')) {
    $price       = (float)($_POST['price'] ?? 0);
    $marketing   = (float)($_POST['marketing_budget'] ?? 0);
    $production  = (int)($_POST['production_units'] ?? 0);
    $rd          = (float)($_POST['rd_budget'] ?? 0);

    db_execute(
        'INSERT INTO decisions (game_id, round, price, marketing_budget, production_units, rd_budget) VALUES (?,?,?,?,?,?)',
        [$game['id'], $game['current_round'], $price, $marketing, $production, $rd]
    );
    flash_set('success', 'Decisions submitted for Round ' . $game['current_round'] . '.');
    redirect('play.php?id=' . $game['id']);
}

$flashes = flash_get();

include 'includes/head.php';
include 'includes/dashboard_layout.php';
?>

<div class="page-header">
  <div class="breadcrumb">
    <a href="dashboard.php">Home</a>
    <span class="sep">/</span>
    <a href="simulations.php">Simulations</a>
    <span class="sep">/</span>
    <span><?= e($game['sim_name']) ?></span>
  </div>
  <h1><?= e($game['sim_name']) ?></h1>
  <p><?= e($game['company_name'] ?? '') ?> — make your decisions for this round.</p>
</div>

<?php foreach ($flashes as $f): ?>
  <div class="alert alert-<?= e($f['type']) ?> mb-16"><i class="fa fa-circle-check"></i> <?= e($f['msg']) ?></div>
<?php endforeach; ?>

<div class="game-grid">

  <!-- ── Sidebar: status ─────────────────────────────────── -->
  <div class="game-sidebar">
    <div class="card">
      <div class="card-body">
        <div class="sim-period-badge mb-16">
          <i class="fa fa-clock"></i> Round <?= (int)$game['current_round'] ?> of <?= (int)$game['total_rounds'] ?>
        </div>
        <div class="progress-bar mb-8">
          <div class="progress-fill" style="width: <?= round(($game['current_round']/max(1,$game['total_rounds']))*100) ?>%"></div>
        </div>
        <div class="text-sm text-muted">Score so far: <strong class="text-accent"><?= (float)$game['score'] ?></strong></div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><span class="card-title"><i class="fa fa-list-check text-accent"></i> Checklist</span></div>
      <div class="card-body">
        <div class="decision-row"><div class="decision-label">Set pricing<small>Per unit, in $</small></div></div>
        <div class="decision-row"><div class="decision-label">Allocate marketing<small>Quarterly budget</small></div></div>
        <div class="decision-row"><div class="decision-label">Plan production<small>Units to manufacture</small></div></div>
        <div class="decision-row"><div class="decision-label">Invest in R&amp;D<small>Optional</small></div></div>
      </div>
    </div>
  </div>

  <!-- ── Main: decision form ─────────────────────────────── -->
  <div class="game-main">
    <div class="card">
      <div class="card-header"><span class="card-title"><i class="fa fa-sliders text-accent"></i> Round <?= (int)$game['current_round'] ?> Decisions</span></div>
      <div class="card-body">
        <form method="post">
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

          <div class="decision-row">
            <div class="decision-label">Unit price<small>What customers pay per unit</small></div>
            <input type="number" step="0.01" min="0" name="price" class="decision-input" placeholder="$ 0.00" required>
          </div>
          <div class="decision-row">
            <div class="decision-label">Marketing budget<small>Spend to drive demand</small></div>
            <input type="number" step="0.01" min="0" name="marketing_budget" class="decision-input" placeholder="$ 0.00" required>
          </div>
          <div class="decision-row">
            <div class="decision-label">Production units<small>How many units to make this round</small></div>
            <input type="number" min="0" name="production_units" class="decision-input" placeholder="0" required>
          </div>
          <div class="decision-row">
            <div class="decision-label">R&amp;D budget<small>Optional — improves future rounds</small></div>
            <input type="number" step="0.01" min="0" name="rd_budget" class="decision-input" placeholder="$ 0.00" value="0">
          </div>

          <button type="submit" class="btn btn-primary mt-16">
            <i class="fa fa-paper-plane"></i> Submit Decisions
          </button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><span class="card-title"><i class="fa fa-chart-line text-accent"></i> Market Trend</span></div>
      <div class="card-body">
        <div class="chart-placeholder">
          <i class="fa fa-chart-area" style="font-size:2rem;color:var(--text-muted);"></i>
        </div>
      </div>
    </div>
  </div>
</div>

  </main>
</div><!-- /.app-layout -->

<div class="toast-container" id="toastContainer"></div>
<script src="js/app.js"></script>
</body>
</html>
