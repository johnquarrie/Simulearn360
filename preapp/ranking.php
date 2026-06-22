<?php
require_once __DIR__ . '/config.php';
?>
<?php include 'header.php'; ?>

<section>
  <h2>Global Ranking</h2>
  <div class="card">
    <p class="small">Top teams for selected simulation (sample).</p>
    <?php
    $mysqli = db_connect();
    $res = $mysqli->query("SELECT team_name, score FROM rankings ORDER BY score DESC LIMIT 10");
    if ($res && $res->num_rows){
      echo '<table class="table"><tr><th>Rank</th><th>Team</th><th>Score</th></tr>';
      $i = 1;
      while($r = $res->fetch_assoc()){
        echo '<tr><td>'.$i++.'</td><td>'.htmlspecialchars($r['team_name']).'</td><td>'.htmlspecialchars($r['score']).'</td></tr>';
      }
      echo '</table>';
    } else {
      echo '<p class="small">No ranking data available.</p>';
    }
    ?>
  </div>
</section>

<?php include 'footer.php'; ?>
