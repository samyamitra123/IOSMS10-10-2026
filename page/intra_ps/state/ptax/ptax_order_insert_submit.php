<?php
error_reporting(0);
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


	if(!isset($_POST['ptax_order_submit'])){
		header('Location: '. $config['base_url'] . "page/intra_ps/state/ptax/ptax_order_insert.php");
		exit;
	}else if(isset($_POST['ptax_order_submit'])){
		
		if($validator->pattern_match_alphanumeric($_POST['file_name'])==FALSE || $validator->pattern_match_alphanumeric($_POST['ptax_id_pk'])==FALSE || $validator->pattern_match_alphanumeric($_POST['ptax_order_submit'])==FALSE){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Valid Input.</strong></div>';
			include 'ptax_order_form.php';
			exit;
		}
		
		$sec_time_token=$_POST['sec_tok'];
	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	if($sec_time_token!=$enc_session){
		$error_msg='<div class="alert alert-danger" style="text-align:center;"><strong>Time Out!..Please Try Again.</strong></div>';
		include 'ptax_order_insert.php';
		exit;
	}else{
		if($_FILES['order_file']['size']>0){
		$flag=1;
		}
		else{
		$flag=2;
		}
		
		$msg="";
		
		$ptax_id_pk=$_POST['ptax_id_pk'];
		
		
		$date=date("Y-m-d");
		$activation_date=$_POST['activation_date'];
		$allowedExts = array("pdf","PDF"); 
		$extension = end(explode(".", $_FILES["order_file"]["name"]));
		//echo $activation_date;exit;
		if($validator->blank_select($_POST['activation_date']) == FALSE){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Select Activation date</strong></div>';
			include 'ptax_order_form.php';
		    exit;

		}
		
		if(!($validator->date_match1($_POST['activation_date']))){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Activation date</strong></div>';
			include 'ptax_order_form.php';
		    exit;
		}
		if(!(in_array($extension, $allowedExts))){
				$error_msg1='<div class="alert alert-danger" style="text-align:center"><strong>Sorry...Only PDF files are allowed.</strong></div>';
							include 'ptax_order_form.php';
		   					exit;
				}
		if($flag==2){
				$error_msg1='<div class="alert alert-danger" style="text-align:center"><strong>Sorry...Invalid File Size(Maximum Allowed File Size 2MB).</strong></div>';
				include 'ptax_order_form.php';
		    	exit;
			}
		$mime = mime_content_type($_FILES['order_file']['tmp_name']) ;
		if($mime != "application/pdf"){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Sorry...Invalid File Choosen.</strong></div>';
			include 'ptax_order_form.php';
		    exit;
		}
		/*if($validator->blank_select($_FILES['order_file']['name']) == FALSE){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Select File</span>';
			include 'ptax_order_form.php';
		    exit;

		}*/
		
		/*if(!($validator->pattern_match_csf($_POST['activation_date']))){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Activation date</span>';
			include 'ptax_order_form.php';
		    exit;
		}*/
		
		
			
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
$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Select a Valid File</strong></div>';
  include 'ptax_order_form.php';
			exit;
}*/
  
} 

	 $db=new database();
	 $cryptography=new cryptography();
	
		$insert=$db->insert("		
		 INSERT INTO psemp_ptax_order_file
									(create_date,
									 active_status,
									activation_date,
									 entry_ip,
									 file_name
									 )
									 values (
									 '".$date."',
									 '0',
									 '".dbdate($activation_date)."',
									 '".$_SESSION['user_agent']['USER_IP']."','".$file_name."'
									
									  )
  							");	
									
				
				if($insert)
				{
				$msg=$cryptography->encode('<div class="alert alert-success" style="text-align:center;"><strong>Data Successfully Entered</strong></div>',3);
				}
				else
				{
					$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Data Insertion Failed</strong></div>';
 					//include 'ptax_order_form.php';
			        //exit;
				}
	  
		header('Location: '. $config['base_url'] . "page/intra_ps/state/ptax/ptax_order_insert.php?msg=".$msg);
		exit;
	}
?>
