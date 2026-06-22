<?php
require_once __DIR__ . '/config.php';
?>
<?php include 'header.php'; ?>

  <section class="hero">
    <div class="intro">
      <h1>Welcome to Simulearn360</h1>
      <p>A compact business simulation platform for learning and training. Track your games, view certificates and check global rankings.</p>
      <p style="margin-top:16px"><a class="btn" href="join.php">Join a Simulation</a></p>
    </div>
    <div>
      <div class="card">
        <h3>Quick overview</h3>
        <p class="small">Start by joining a simulation with a Game ID and License Key, or view your active games.</p>
        <table class="table">
          <tr><th>Players</th><td>120</td></tr>
          <tr><th>Simulations</th><td>34</td></tr>
          <tr><th>Active rounds</th><td>13</td></tr>
        </table>
      </div>
    </div>
  </section>

  <section>
    <h2>Explore</h2>
    <div class="card-grid">
      <a class="card" href="mygames.php"><h3>My Games</h3><p class="small">Open your active games and enter rounds.</p></a>
      <a class="card" href="join.php"><h3>Join Simulation</h3><p class="small">Use a Game ID and License Key to join a simulation.</p></a>
      <a class="card" href="certificates.php"><h3>Certificates</h3><p class="small">Download certificates for completed simulations.</p></a>
      <a class="card" href="ranking.php"><h3>Global Ranking</h3><p class="small">See top teams across simulations.</p></a>
    </div>
  </section>

<?php include 'footer.php'; ?>
