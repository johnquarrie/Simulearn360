<?php
// login.php now simply forwards to index.php (the canonical login screen)
// kept as a friendly alias so old links / bookmarks keep working.
require_once 'includes/config.php';
redirect('index.php');
