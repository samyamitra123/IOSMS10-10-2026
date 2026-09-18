<?php

session_start();
//error_reporting(0);

ob_start();
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:dashboard_intra_pri.php");
}

$crypto = new cryptography();


 $file_flag=$crypto->decode($_POST['f'],4);   
 $app_id='';
 $emp_id=$crypto->decode($_POST['emp_id'],4); 
 if($_FILES["file"]["name"] != '' && ($_FILES["file"]["size"] != 0) && $file_flag!=''){
	 
	 //echo 55555555; die;
	 	 $rand=rand(100000, 999999); 
		$db=new database();
		
	
		
		$insert_file=$db->insert(" INSERT INTO intra_pri_file_upload
										(application_id, flag, file_name,status,emp_id_const)
										values ('".$app_id."','".$file_flag."','".$rand.$_FILES['file']['name']."','1','".$emp_id."' ) ");	

	if($insert_file == 1 && $file_flag=='40'){
		$location = '../../readwrite/intra_pri_upload/within_district/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "40";
	
	}
	
	if($insert_file == 1 && $file_flag=='41'){
		$location = '../../readwrite/intra_pri_upload/outhside_district/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "40";
	
	}
	
							
										
 }
 
?>
