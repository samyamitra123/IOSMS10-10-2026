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
$crypto=new cryptography();

//echo 23;die;
//var_dump($_POST['application_id']); 

 //print_r($_FILES["file"]); exit;
  if($_FILES["file"]["name"] != '' && ($_FILES["file"]["size"] != 0)){
  	//echo $_FILES["file"]["name"];die;
	 	$db=new database();
		$rand=rand(100000, 999999);
	 	$insert_file=$db->insert(" INSERT INTO intra_pri_file_upload
										(application_id, flag, file_name,emp_id_const,officer_id_const,status)
										values ('".$crypto->decode($_POST['application_id'],4)."','".$_POST['flag_id']."','".$rand.$_FILES['file']['name']."','".$_POST['emp_id_const']."','".$_SESSION['user_info']['officer_id_const']."',1 ) ");

	if($insert_file == 1){
		$location = '../../readwrite/intra_pri_upload/9008/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
	  echo 1;
	}
										
										
 } else { echo 0; }
 
?>
