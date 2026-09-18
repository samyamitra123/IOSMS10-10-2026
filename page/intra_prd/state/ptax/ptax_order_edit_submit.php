<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
include_once ('../../../../includes/library/database.class.php');
include_once '../../../../includes/library/myvalidation.class.php';
include_once '../../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';

function dbdate($caldate){
				if($caldate=="" || $caldate=="0001-01-01"){
						$redate="0001-01-01";
				}else{
 						$tmp=explode("-",$caldate);
						$redate=$tmp[2]."-".$tmp[1]."-".$tmp[0];
				}
  return  $redate;
 } 
?>
<?php 
		$cryptography=new cryptography();
		$ptax_id_pk=$_POST['ptax_id_pk'];
		$sec_time_token=$_POST['sec_tok'];
    	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	if($sec_time_token!=$enc_session){
		$error_msg='<div class="alert alert-danger" style="text-align:center;"><strong>Time Out!..Please Try Again.</strong></div>';
		include 'ptax_order_insert.php';
		exit;
	}else{

	if(!isset($_POST['ptax_order_submit'])){
		header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_insert.php");
		exit;
	}else if(isset($_POST['ptax_order_submit'])){
		$msg="";
		$date=date("Y-m-d");
		$activation_date=$_POST['activation_date'];
		$order_file=$_FILES['order_file']['name'];
		$ptax_id_pk=$_POST['ptax_id_pk'];
		$allowedExts = array("pdf","PDF"); 
		$extension = end(explode(".", $_FILES["order_file"]["name"]));
		
		if($_FILES['order_file']['size']>0){
		$flag=1;
		}
		else{
		$flag=2;
		}
		if(!$validator->blank_select($activation_date) ){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Select Activation date.</strong></div>';
			header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3));
		    exit;

		}
		
		if(!$validator->date_match1($activation_date) ){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Invalide Activation date.</strong></div>';
			header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3));
		    exit;

		}
			if(!(in_array($extension, $allowedExts))){
				$error_msg1='<div class="alert alert-danger" style="text-align:center"><strong>Sorry...Only PDF files are allowed.</strong></div>';
				header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3));
			exit;
			}
			
			if($flag==2){
				$error_msg1='<div class="alert alert-danger" style="text-align:center"><strong>Sorry...Invalid File Size(Maximum Allowed File Size 2MB).</strong></div>';
				header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3));
			exit;
			}
		
		$mime = mime_content_type($_FILES['order_file']['tmp_name']) ;
		if($mime != "application/pdf"){
			//echo 1;
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Sorry...Invalid File Choosen.</strong></div>';
			header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3));
			exit;
		}
		
		if($validator->blank_select($order_file)==FALSE)
		{
			
			 $db=new database();
			 $cryptography=new cryptography();
			
			$insert=$db->insert("		
		 UPDATE prd_ptax_order_file
									SET 
									create_date='".$date."',
									activation_date='".dbdate($activation_date)."',
									 entry_ip='".$_SESSION['user_agent']['USER_IP']."',
									 file_name='".$_POST['file_name']."' WHERE ptax_orderfile_pk=$ptax_id_pk
									 
  							");	
							/*echo "  UPDATE ehrms_ptax_order_file
									SET 
									create_date='".$date."',
									activation_date='".dbdate($activation_date)."',
									 entry_ip='".$_SESSION['user_agent']['USER_IP']."',
									 file_name='".$_POST['file_name']."' WHERE ptax_orderfile_pk=$ptax_id_pk";exit;*/
			$error_msg1='<div class="alert alert-success" style="text-align:center;"><strong>Data Successfully Updated.</strong></div>';
			//include 'ptax_order_form.php';
			//echo $error_msg1;
			//header('Location: '. $config['base_url'] . "page/intra_ehrms/state/ptax/ptax_order_edit_form.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3) );
			header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3));
		    exit;
			//echo 1;
		}
			
$file_name=md5(microtime()).".pdf";		
$allowedExts = array("pdf");
$temp = explode(".", $_FILES["order_file"]["name"]);
$extension = end($temp);

/*if (($_FILES['order_file']['name'] != '') && 
($_FILES['order_file']['size'] != 0) && 
($_FILES['order_file']['type'] == 'application/pdf') &&
($_FILES['order_file']['size'] <= 41943040)) {*/
      move_uploaded_file($_FILES["order_file"]["tmp_name"],
     "../../../../readwrite/upload/ptax_order_upload/" . $file_name);
    /*}
	else {
$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Select a Valid File.</strong></div>';
  header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3));
			exit;
}*/
  
} 

	 $db=new database();
	 //$cryptography=new cryptography();
	
		$insert=$db->insert("		
		  UPDATE prd_ptax_order_file
									SET 
									create_date='".$date."',
									activation_date='".dbdate($activation_date)."',
									 entry_ip='".$_SESSION['user_agent']['USER_IP']."',
									 file_name='".$file_name."' WHERE ptax_orderfile_pk=$ptax_id_pk
									
									
									 
  							");	
							
							/*echo " UPDATE ehrms_ptax_order_file
									SET 
									create_date='".$date."',
									activation_date='".dbdate($activation_date)."',
									 entry_ip='".$_SESSION['user_agent']['USER_IP']."',
									 file_name='".$file_name."' WHERE ptax_orderfile_pk=$ptax_id_pk";exit;*/
									
				
				if($insert)
				{
				$msg='<div class="alert alert-success" style="text-align:center;"><strong>Data Successfully Updated. </strong></div>';
 					//include 'ptax_order_form.php';
					header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($msg,3));
			        exit;
				}
				else
				{
					$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Data Insertion Failed.</strong></div>';
					header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3));
 					//include 'ptax_order_form.php';
			        exit;

				}
	  
		header('Location: '. $config['base_url'] . "page/intra_prd/state/ptax/ptax_order_insert.php?msg=".$msg);
		exit;
	}
?>
