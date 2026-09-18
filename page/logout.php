<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");*/

session_start();
require '../includes/config/config.php';
session_destroy();
header('Location: '. $config['base_url'] . "page/login.php");
	exit;

?>