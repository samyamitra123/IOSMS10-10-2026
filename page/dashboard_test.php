<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
ob_start();

echo "Step 1: config<br>";
require '../includes/config/config.php';

echo "Step 2: db config<br>";
require '../includes/config/database.config.php';

echo "Step 3: db class<br>";
require '../includes/library/database.class.php';

echo "Step 4: crypto class<br>";
require '../includes/library/cryptography.class.php';

echo "✅ Loaded everything without redirect<br>";
exit;
?>
