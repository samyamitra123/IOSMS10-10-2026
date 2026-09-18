<?php
//error_reporting(0);
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
//require '../../page_visite.php';
//print_r($_POST);exit;
function dbdate2($caldate){
				if($caldate=="" || $caldate=="0001-01-01"){
						$redate="0001-01-01";
				}else{
 						$tmp=explode("-",$caldate);
						$redate=$tmp[2]."-".$tmp[1]."-".$tmp[0];
				}
  return  $redate;
 } 
 $cryptography=new cryptography();
$date=date("Y-m-d");

	if(!isset($_POST['ptax_order_submit'])){
		//echo 1111;exit;
		header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_edit.php");
		exit;
	}else if(isset($_POST['ptax_order_submit'])){
		//echo 2222;
		
		$sec_time_token=$_POST['sec_tok'];
	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	if($sec_time_token!=$enc_session){
		//echo "ddd";exit;
		$error_msg='<div class="alert alert-danger" style="text-align:center;"><strong>Time Out!..Please Try Again.</strong></div>';
		include 'paychange_order_edit.php';
		exit;
	}else{
		//echo 3333;
		$msg="";
		
		$ptax_id_pk=$_POST['ptax_id_pk'];
		$file_name_prev=$_POST['file_name'];
		$order_file=$_FILES['order_file']['name'];
		$activation_date=$_POST['activation_date'];
		$allowedExts = array("pdf","PDF"); 
		$extension = end(explode(".", $_FILES["order_file"]["name"]));
		//echo $order_file;exit;
		//echo $file_name_prev;
		
		
		
		//echo $activation_date;exit;
		if($_FILES['order_file']['size']>0){
		$flag=1;
		}
		else{
		$flag=2;
		}
		if(($validator->blank_select($activation_date)==FALSE) ){
			//echo "sss";exit;
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Please Insert Activation date</strong></div>';
			header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3) );
			//include 'paychange_order_edit.php';
		    exit;

		}
		
		if(($validator->date_match1($activation_date)==FALSE)){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Activation date</strong></div>';
			header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3) );
			//include 'paychange_order_edit.php';
		    exit;

		}
			if(!(in_array($extension, $allowedExts))){
				$error_msg1='<div class="alert alert-danger" style="text-align:center"><strong>Sorry...Only PDF files are allowed.</strong></div>';
				header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3) );
			//include 'paychange_order_edit.php';
		    exit;
			}
			
			if($flag==2){
				$error_msg1='<div class="alert alert-danger" style="text-align:center"><strong>Sorry...Invalid File Size(Maximum Allowed File Size 2MB).</strong></div>';
				header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3) );
			//include 'paychange_order_edit.php';
		    exit;
			}
			
		$mime = mime_content_type($_FILES['order_file']['tmp_name']) ;
		if($mime != "application/pdf"){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Sorry...Invalid File Choosen.</strong></div>';
			header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3) );
			//include 'paychange_order_edit.php';
		    exit;
		}
			
		
		if($validator->blank_select($order_file)==FALSE)
		{
			//echo "dd";exit;
			 $db=new database();
			 $cryptography=new cryptography();
			$insert=$db->insert("		
		 UPDATE psemp_admin_paychange
									SET 
									entrydate='".$date."',
									paychange_fromdate='".dbdate2($activation_date)."',
									 paychange_ip='".$_SESSION['user_agent']['USER_IP']."',
									 order_file_name='".$file_name_prev."' WHERE paychange_id_pk=$ptax_id_pk;
									 
  							");	
							/*echo " UPDATE prd_admin_paychange
									SET 
									entrydate='".$date."',
									paychange_fromdate='".dbdate1($activation_date)."',
									 paychange_ip='".$_SESSION['user_agent']['USER_IP']."',
									 order_file_name='".$file_name_prev."' WHERE paychange_id_pk=$ptax_id_pk";exit;*/
			$error_msg1='<div class="alert alert-success" style="text-align:center;"><strong>Data Successfully Updated</strong></div>';
			header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3) );
			//include 'paychange_order_edit.php?order_id='.$cryptography->encode($ptax_id_pk,3);
		    exit;
			
		}
			
			
			
$file_name=md5(microtime()).".pdf";	
//echo $file_name;exit;
$allowedExts = array("pdf");
$temp = explode(".", $_FILES["order_file"]["name"]);
$extension = end($temp);

/*if (($_FILES['order_file']['name'] != '') && 
($_FILES['order_file']['size'] != 0) && 
($_FILES['order_file']['type'] == 'application/pdf') &&
($_FILES['order_file']['size'] <= 41943040)) {*/
      move_uploaded_file($_FILES["order_file"]["tmp_name"],
      "../../../../readwrite/upload/paychange_order_upload/" . $file_name);
    /*}
	else {
$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Select a Valid File</strong></div>';
header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($error_msg1,3) );
  //include 'paychange_order_edit.php';
			exit;
}*/
  
} 

	 $db=new database();
	 $cryptography=new cryptography();
	
		$insert=$db->insert("		
		 UPDATE psemp_admin_paychange
									SET 
									entrydate='".$date."',
									paychange_fromdate='".dbdate2($activation_date)."',
									 paychange_ip='".$_SESSION['user_agent']['USER_IP']."',
									 order_file_name='".$file_name."' WHERE paychange_id_pk=$ptax_id_pk;
									 
  							");	
									
				
				if($insert)
				{
					//echo "sdsd";exit;
					//$msg=$cryptography->encode('<div id="sucess">Data Successfully Updated.</div>',3);
					$msg='<div class="alert alert-success" style="text-align:center;"><strong>Data Successfully Updated </strong></div>';
 					//include 'ptax_order_form.php';
					header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($msg,3));
			        exit;
				}
				else
				{
					//echo "ssdeeeee";exit;
					$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Data Insertion Failed</strong></div>';
					//header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/ptax_order_form.php?msg=".$error_msg1);
 					//include 'ptax_order_form.php';
					header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_edit.php?order_id=".$cryptography->encode($ptax_id_pk,3)."&msg=".$cryptography->encode($msg,3));
			        exit;
				}
	  
		//header('Location: '. $config['base_url'] . "page/intra_ps/state/paychange/paychange_order_details.php?msg=".$msg);
		//exit;
	}
?>
