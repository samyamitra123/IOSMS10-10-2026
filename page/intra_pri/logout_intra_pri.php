<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");


session_start();
require '../../includes/config/config.php';
session_destroy();
header('Location: '. $config['base_url'] . "index.php");
	exit;

?>