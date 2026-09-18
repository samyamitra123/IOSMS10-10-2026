<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' code.jequery.com 'unsafe-inline'; img-src 'self'; font-src 'self'; 
 connect-src 'self'; 
 form-action 'self'; frame-ancestors 'none'; ");

 header("Strict-Transport-Security: max-age=63072000");*/
session_start();
 require_once '../../includes/config/config.php';
    require_once '../../includes/config/database.config.php';
    require_once '../../includes/library/database.class.php';
    require_once '../../includes/library/cryptography.class.php';



	$flag=$_GET['flag'];
	$application_id=$_GET['application_id'];
	$db=new database();
	
	
	$file=$db->fetch_table("select file_name from intra_pri_file_upload where   status = '1' AND flag= '".$flag."' and application_id='".$application_id."'");
	 $file_name=$file['0']["file_name"]; 
//	$file_name=$_GET['file'];
	
	
		//$file_a = '../../readwrite/intra_pri_upload/com_ground/'.$file_name;
		$file_a = '../../readwrite/intra_pri_upload/overage_condonation/'.$file_name;
	
	//echo $file_a; die;
	header("Content-disposition: attachment; filename=".$file_name."");
	header("Content-type: application/pdf");
	readfile($file_a);
?>
