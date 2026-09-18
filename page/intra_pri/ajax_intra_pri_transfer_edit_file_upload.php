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
//print_r($_POST); exit;

 $file_flag=$crypto->decode($_POST['f'],4);   
 $app_id=$crypto->decode($_POST['app_id'],4);
 $emp_id=$crypto->decode($_POST['emp_id'],4); 
 $location = ($_POST['transfer_type'] != 'D')?'../../readwrite/intra_pri_upload/within_district/':'../../readwrite/intra_pri_upload/outhside_district/';
 if($_FILES["file"]["name"] != '' && ($_FILES["file"]["size"] != 0) && $file_flag!=''){
	 
	 //echo 55555555; die;
 	    
 	    //echo $location; exit;
	 	$rand=rand(100000, 999999); 
	 	$location = $location.$rand.$_FILES["file"]["name"]; 	    
		if(move_uploaded_file($_FILES['file']['tmp_name'], $location))
		   {


			$db=new database();
			$fileName = $rand.$_FILES['file']['name'];
			$Query = "INSERT INTO intra_pri_file_upload
											(application_id, flag, file_name,status,emp_id_const)
											values ('".$app_id."','".$file_flag."','".$fileName.
												"','1','".$emp_id."' ) ";
			$db->insert($Query);
?>
<a style="text-decoration:none" href="<?= $config['base_url']?>page/intra_pri/doc_download_trasfer.php?employee_id=<?= $crypto->encode($emp_id,4)?>&flag=<?=$file_flag?>"><?php echo substr($fileName,6); ?></a>
                   <span ><a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="del('<?php echo $crypto->encode($file_flag,4); ?>','<?php echo $crypto->encode("delete",4); ?>','<?php echo $crypto->encode($emp_id,4); ?>','<?php echo $crypto->encode($app_id,4); ?>');" >Delete</a></span>
<?php			
		   }	

							
    }
 
?>
