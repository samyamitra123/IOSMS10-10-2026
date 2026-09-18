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
	  $file_name_substr = substr($file['0']["file_name"],6); 
	  $file_name = $file['0']["file_name"]; 
//	$file_name=$_GET['file'];
	//var_dump($file_name); die;
	if($flag=='40')
	{
		$file_a = '../../readwrite/intra_pri_upload/intra_transfer/'.$file_name;
	}
	else if($flag=='20'||$flag=='21'||$flag=='22'||$flag=='23'||$flag=='24'||$flag=='25'||$flag=='26' ||$flag=='38')
	{
		$file_a = '../../readwrite/intra_pri_upload/overage_condonation/'.$file_name;
	}
	else if($flag=='1'||$flag=='2'||$flag=='3'||$flag=='4'||$flag=='5'||$flag=='6'||$flag=='7'||$flag=='8'||$flag=='9'||$flag=='10'||$flag=='12'||$flag=='13'||$flag=='14'||$flag=='15' ||$flag=='16' ||$flag=='17' ||$flag=='11' || $flag=='18' || $flag=='19')
	{
		$file_a = '../../readwrite/intra_pri_upload/com_ground/'.$file_name;
	}
	else //if($flag=='30'||$flag=='31'||$flag=='32'||$flag=='33'||$flag=='34'||$flag=='35'||$flag=='36')
	{
		$file_a = '../../readwrite/intra_pri_upload/9008/'.$file_name;
	}
	$pathInfo = pathinfo($file_name,PATHINFO_EXTENSION);
	//print($pathInfo); exit;
	
	if($pathInfo =='pdf')
	{
	//echo $file_a; die;
	//header("Content-disposition: attachment; filename=".$file_name_substr);
	header("Content-type: application/pdf");
	readfile($file_a);

	
	}
	else
	{
	header("Content-disposition: attachment; filename=".$file_name_substr);
	header("Content-type: application/jpg");
	readfile($file_a);
	}
?>
