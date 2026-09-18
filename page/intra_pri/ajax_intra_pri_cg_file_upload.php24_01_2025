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
 $app_id=$crypto->decode($_POST['app_id'],4); 
 $emp_id=$crypto->decode($_POST['emp_id'],4); 
 if($_FILES["file"]["name"] != '' && ($_FILES["file"]["size"] != 0) && $file_flag!=''){
	 
	 //echo 55555555; die;
	 	$rand=rand(100000, 999999);
		$db=new database();
		
	
		
		$insert_file=$db->insert(" INSERT INTO intra_pri_file_upload
										(application_id, flag, file_name,status,emp_id_const)
										values ('".$app_id."','".$file_flag."','".$rand.$_FILES['file']['name']."','1','".$emp_id."' ) ");	

	if($insert_file == 1 && $file_flag=='1'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "1";
	
	}
	
	if($insert_file == 1 && $file_flag=='2'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "2";
	
	}
	
	if($insert_file == 1 && $file_flag=='3'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "3";
	
	}
	if($insert_file == 1 && $file_flag=='4'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "4";
	
	}
	
	if($insert_file == 1 && $file_flag=='5'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "5";
	
	}
	
	if($insert_file == 1 && $file_flag=='6'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "6";
	
	}
	if($insert_file == 1 && $file_flag=='7'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "7";
	
	}
	if($insert_file == 1 && $file_flag=='8'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "8";
	
	}
	if($insert_file == 1 && $file_flag=='9'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "9";
	
	}
	if($insert_file == 1 && $file_flag=='10'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "10";
	
	}
	if($insert_file == 1 && $file_flag=='12'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "12";
	
	}
	if($insert_file == 1 && $file_flag=='13'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "13";
	
	}
	if($insert_file == 1 && $file_flag=='14'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "14";
	
	}
	if($insert_file == 1 && $file_flag=='15'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "15";
	
	}
	if($insert_file == 1 && $file_flag=='11'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "11";
	
	}
	if($insert_file == 1 && $file_flag=='16'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "16";
	
	}
	if($insert_file == 1 && $file_flag=='17'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "17";
	
	}
	if($insert_file == 1 && $file_flag=='18'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "18";
	
	}
	
	if($insert_file == 1 && $file_flag=='19'){
		$location = '../../readwrite/intra_pri_upload/com_ground/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "19";
	
	}
	
	if($insert_file == 1 && $file_flag=='40'){
		$location = '../../readwrite/intra_pri_upload/intra_transfer/'.$rand.$_FILES["file"]["name"];
		move_uploaded_file($_FILES['file']['tmp_name'], $location);
		
		echo "40";
	
	}
										
										
 }
 
?>
