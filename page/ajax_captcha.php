<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");
*/
session_start() ;
require_once("captcha/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha( array(
	'min_length' => 5,
	'max_length' => 5,
	'characters' => 'abcdefghjkmnprstuvwxyz23456789',
	'min_font_size' => 25,
	'max_font_size' => 30,
	'color' => '#222',
	'angle_min' => 0,
	'angle_max' => 0,
	'shadow' => true,
	'shadow_color' => '#FFF',
	'shadow_offset_x' => -1,
	'shadow_offset_y' => 1
));
echo $_SESSION['captcha']['image_src'] ;
?>
