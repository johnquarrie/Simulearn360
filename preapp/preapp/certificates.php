<?php
require_once 'includes/config.php';
require_login();
$current_page = 'results';
$page_title   = 'Certificates';
$user = current_user();

$certs = db_fetch_all('SELECT id, title, issued_date FROM certificates WHERE user_id = ? ORDER BY issued_date DESC', [$user['id']]);
if (!$certs) {
    $certs = [['id' => 1, 'title' => 'SupplyChain Masters — Completion Certificate', 'issued_date' => date('Y-m-d')]];
}

include 'includes/head.php';
include 'includes/dashboard_layout.php';
?>

<div class="page-header">
  <div class="breadcrumb">
    <a href="dashboard.php">Home</a>
    <span class="sep">/</span>
    <span>Certificates</span>
  </div>
  <h1>Certificates</h1>
  <p>Download certificates for your completed simulations.</p>
</div>

<div class="card">
  <div class="card-body" style="padding-top:16px;">
    <?php if ($certs): ?>
      <div class="table-wrapper">
        <table>
          <thead><tr><th>Title</th><th>Issued</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach ($certs as $c): ?>
              <tr>
                <td><?= e($c['title']) ?></td>
                <td class="text-sm text-muted"><?= e($c['issued_date']) ?></td>
                <td><a href="#" class="btn btn-secondary btn-sm"><i class="fa fa-download"></i> Download</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-sm text-muted">No certificates yet — complete a simulation to earn one.</p>
    <?php endif; ?>
  </div>
</div>

  </main>
</div><!-- /.app-layout -->

<div class="toast-container" id="toastContainer"></div>
<script src="js/app.js"></script>
</body>
</html>
