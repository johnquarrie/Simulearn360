<?php
require_once __DIR__ . '/config.php';
?>
<?php include 'header.php'; ?>

<section>
  <h2>Join the Simulation</h2>
  <div class="card">
    <form method="post">
      <label>Game ID<br><input name="game_id" required></label><br><br>
      <label>License key<br><input name="license" required></label><br><br>
      <button class="btn" type="submit">Join</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      $game = $mysqli = db_connect();
      $game_id = $mysqli->real_escape_string($_POST['game_id']);
      $license = $mysqli->real_escape_string($_POST['license']);
      // For this prototype accept any input and create a link row in games
      $mysqli->query("INSERT INTO games (name, company_name, current_round) VALUES ('Game_".$game_id."', 'Company', 1)");
      echo '<p class="small">Joined simulation. Go to <a href="mygames.php">My Games</a>.</p>';
    }
    ?>
  </div>
</section>

<?php include 'footer.php'; ?>
