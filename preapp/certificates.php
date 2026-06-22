<?php
require_once __DIR__ . '/config.php';
?>
<?php include 'header.php'; ?>

<section>
  <h2>Certificates</h2>
  <div class="card">
    <p class="small">Download certificates for completed simulations.</p>
    <?php
    $mysqli = db_connect();
    $res = $mysqli->query("SELECT id, title, issued_date FROM certificates ORDER BY issued_date DESC LIMIT 20");
    if ($res && $res->num_rows){
      echo '<table class="table"><tr><th>Title</th><th>Date</th><th>Action</th></tr>';
      while($r = $res->fetch_assoc()){
        echo '<tr><td>'.htmlspecialchars($r['title']).'</td><td>'.htmlspecialchars($r['issued_date']).'</td><td><a href="#">Download</a></td></tr>';
      }
      echo '</table>';
    } else {
      echo '<p class="small">No certificates yet.</p>';
    }
    ?>
  </div>
</section>

<?php include 'footer.php'; ?>
