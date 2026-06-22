<?php
require_once __DIR__ . '/config.php';
?>
<?php include 'header.php'; ?>

<section>
  <h2>My Games</h2>
  <div class="card">
    <p class="small">List of games you are participating in (sample output).</p>
    <?php
    $mysqli = db_connect();
    $res = $mysqli->query("SELECT id, name, company_name, current_round FROM games ORDER BY id DESC LIMIT 20");
    if ($res && $res->num_rows){
      echo '<table class="table"><tr><th>Start</th><th>Game name</th><th>Company</th><th>Round</th></tr>';
      while($row = $res->fetch_assoc()){
        echo '<tr><td>' . htmlspecialchars($row['id']) . '</td><td>' . htmlspecialchars($row['name']) . '</td><td>' . htmlspecialchars($row['company_name']) . '</td><td>' . htmlspecialchars($row['current_round']) . '</td></tr>';
      }
      echo '</table>';
    } else {
      echo '<p class="small">No games found. You can <a href="join.php">join a simulation</a>.</p>';
    }
    ?>
  </div>
</section>

<?php include 'footer.php'; ?>
