
<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");*/
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Under Maintenance</title>
</head>
<style type="text/css">
.maint {
	text-align: center;
	margin: 50px 0;
}
.maint h1, .maint h3 {
	font-family: Calibri;
	margin: 0;
}
.maint h1{
	color: #CF5121;	
	font-size: 38px;
}
.maint h3{
	color: #167EC1;	
	font-size: 28px;
}
</style>
<body>
<div class="maint">
	<img src="<?=$config['base_url']?>page/UnderConstruction.jpg">
    <h1>The Website is under Maintenance</h1> 
    <h3>We will come back soon..</h3>
    </div>
</body>
</html>
