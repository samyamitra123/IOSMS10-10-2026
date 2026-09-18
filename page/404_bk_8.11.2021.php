<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' code.jequery.com 'unsafe-inline'; img-src 'self'; font-src 'self'; connect-src 'self'; form-action 'self'; frame-ancestors 'none'; ");

 header("Strict-Transport-Security: max-age=63072000");
session_start();
//echo $_GET['e']; die;?>
 <!DOCTYPE html>
<html>
<head>
<title>404. Page not found. </title>
</head>
<body>

<!--<h1>404.Page not found. </h1>
<p>This requested URL  was not found on this server.</p>-->

</body>
</html> 
<?php ?>
<?php echo "404.Page not found"; ?>
