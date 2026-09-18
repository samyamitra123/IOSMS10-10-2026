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

//echo 222; die;
 $crypto=new cryptography();
	 $flag=$_GET['flag'];  
	//$application_id=$_GET['application_id'];
	 $employee_id=$crypto->decode($_GET['employee_id'],4); 
	$db=new database();
	
	
	$file=$db->fetch_table("select file_name from intra_pri_file_upload where   status = '1' AND flag= '".$flag."' and emp_id_const='".$employee_id."' order by upload_file_id_pk desc LIMIT 1 OFFSET 0");
	//print_r("select file_name from intra_pri_file_upload where   status = '1' AND flag= '".$flag."' and emp_id_const='".$employee_id."' order by upload_file_id_pk desc LIMIT 1 OFFSET 0"); exit;
	 $file_name_substr = substr($file['0']["file_name"],6);
	 $file_name=$file['0']["file_name"]; 
//	$file_name=$_GET['file'];
	//var_dump($file_name); die;
	if($flag=='40')
	{
		$file_a = '../../readwrite/intra_pri_upload/within_district/'.$file_name;
	   $fileurl = $config['base_url'].'readwrite/intra_pri_upload/within_district/'.$file_name;
   }
	else if($flag=='41' || $flag=='42' || $flag=='43' )
	{
		$file_a = '../../readwrite/intra_pri_upload/outhside_district/'.$file_name;
		$fileurl = $config['base_url'].'readwrite/intra_pri_upload/outhside_district/'.$file_name;

		//echo $config['base_url'].'readwrite/intra_pri_upload/outhside_district/'.$file_name;exit;
	}
	
	
	//echo $file_a; die;
	/*header("Content-disposition: attachment; filename=".$file_name_substr);
	header("Content-type: application/pdf");
	readfile($file_a);*/
	
	
   header('Location:'.$fileurl);
?>
