<?php
require_once 'includes/config.php';
require_login();
$current_page = 'simulations';
$page_title   = 'Join a Simulation';
$user = current_user();

$message = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $sim_id  = (int)($_POST['simulation_id'] ?? 0);
        $license = trim($_POST['license'] ?? '');

        if (!$sim_id) {
            $error = 'Please choose a simulation.';
        } else {
            $sim = db_fetch_one('SELECT * FROM simulations WHERE id = ?', [$sim_id]);
            if ($sim) {
                $ok = db_execute(
                    'INSERT INTO games (simulation_id, user_id, company_name, current_round, status) VALUES (?,?,?,1,"active")',
                    [$sim_id, $user['id'], $_POST['company_name'] ?? ($user['first_name'] . "'s Company")]
                );
                $message = $ok
                    ? 'Joined "' . e($sim['name']) . '"! Head to your simulations to start playing.'
                    : 'Joined! (demo mode — no database connected)';
            } else {
                // No DB / table, or simulation not found — fall back to a friendly demo message
                $message = 'Joined! (demo mode — no database connected)';
            }
        }
    }
}

$simulations = db_fetch_all('SELECT id, name, total_rounds FROM simulations ORDER BY name');
if (!$simulations) {
    $simulations = [
        ['id' => 1, 'name' => 'RetailSim: Fashion Brand', 'total_rounds' => 8],
        ['id' => 2, 'name' => 'StartupSim: Tech Venture', 'total_rounds' => 6],
        ['id' => 3, 'name' => 'SupplyChain Masters', 'total_rounds' => 6],
    ];
}
$preselect = (int)($_GET['sim'] ?? 0);

include 'includes/head.php';
include 'includes/dashboard_layout.php';
?>

<div class="page-header">
  <div class="breadcrumb">
    <a href="dashboard.php">Home</a>
    <span class="sep">/</span>
    <a href="simulations.php">Simulations</a>
    <span class="sep">/</span>
    <span>Join</span>
  </div>
  <h1>Join the Simulation</h1>
  <p>Pick a simulation, name your company, and start playing.</p>
</div>

<div class="card" style="max-width:560px;">
  <div class="card-body">
    <?php if ($message): ?>
      <div class="alert alert-success"><i class="fa fa-circle-check"></i> <?= $message ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger"><i class="fa fa-circle-exclamation"></i> <?= e($error) ?></div>
    <?php endif; ?>

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

      <div class="form-group">
        <label class="form-label">Simulation</label>
        <select name="simulation_id" class="form-input" required>
          <option value="">Select a simulation…</option>
          <?php foreach ($simulations as $sim): ?>
            <option value="<?= (int)$sim['id'] ?>" <?= $preselect === (int)$sim['id'] ? 'selected' : '' ?>>
              <?= e($sim['name']) ?> (<?= (int)$sim['total_rounds'] ?> rounds)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Company name</label>
        <input type="text" name="company_name" class="form-input" placeholder="e.g. Northwind Retail">
      </div>

      <div class="form-group">
        <label class="form-label">License key (optional)</label>
        <input type="text" name="license" class="form-input" placeholder="Provided by your instructor, if required">
      </div>

      <button type="submit" class="btn btn-primary w-100">
        <i class="fa fa-right-to-bracket"></i> Join Simulation
      </button>
    </form>
  </div>
</div>

  </main>
</div><!-- /.app-layout -->

<div class="toast-container" id="toastContainer"></div>
<script src="js/app.js"></script>
</body>
</html>
