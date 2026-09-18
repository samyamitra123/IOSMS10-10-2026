<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
//error_reporting(0);
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';

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
		$sec_time_token=$_POST['sec_tok'];
    	$session_token=$_SESSION['security_token'];
		$enc_session=md5('369'.$session_token);
		$allowedExts = array("pdf","PDF"); 
		$extension = end(explode(".", $_FILES["order_file"]["name"]));
	if($sec_time_token!=$enc_session){
		$error_msg='<div class="alert alert-danger" style="text-align:center;"><strong>Time Out!..Please Try Again.</strong></div>';
		include 'paychange_order_insert.php';
		exit;
	}else{

	if(!isset($_POST['paychange_order_submit'])){
		header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_order_insert.php");
		exit;
	}else if(isset($_POST['paychange_order_submit'])){
		$msg="";
		$date=date("Y-m-d");
		$activation_date=$_POST['activation_date'];
	
		if($_FILES['order_file']['size']>0){
		$flag=1;
		}
		else{
		$flag=2;
		}
		if(!$validator->blank_select($activation_date) ){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Select Activation Date</strong></div>';
			include 'paychange_order_insert.php';
		    exit;

		}
		if(!$validator->date_match1($activation_date) ){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Invalide Activation date</strong></div>';
			include 'paychange_order_insert.php';
		    exit;

		}
		if(!(in_array($extension, $allowedExts))){
				$error_msg1='<div class="alert alert-danger" style="text-align:center"><strong>Sorry...Only PDF files are allowed.</strong></div>';
							include 'paychange_order_insert.php';
		   					exit;
				}
		if($flag==2){
				$error_msg1='<div class="alert alert-danger" style="text-align:center"><strong>Sorry...Invalid File Size(Maximum Allowed File Size 2MB).</strong></div>';
							include 'paychange_order_insert.php';
		   					exit;
			}
		$mime = mime_content_type($_FILES['order_file']['tmp_name']) ;
		if($mime != "application/pdf"){
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Sorry...Invalid File Choosen.</strong></div>';
			include 'paychange_order_insert.php';
		    exit;
		}
		/*if($validator->blank_select($_FILES['order_file']['name']) == FALSE){
			$error_msg1='<span style="color:#D83118; font-weight:bolder;">Select File</span>';
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
	//echo $_FILES["order_file"]["tmp_name"];exit;
      move_uploaded_file($_FILES["order_file"]["tmp_name"],
      "../../../../readwrite/upload/paychange_order_upload/" . $file_name);
	 // echo 1;exit;
    /*}
	else {
			$error_msg1='<div class="alert alert-danger" style="text-align:center;"><strong>Select a Valid File</strong></div>';
			 include 'paychange_order_form.php';
			exit;
}*/
  
} 

	 $db=new database();
	 $cryptography=new cryptography();
	 $search_db=$db->fetch_table('select paychange_id_pk from prd_admin_paychange');
	 if(count($search_db)>0){
		$flag='FALSE'; 
	 } else {
		$flag='TRUE'; 
	 }
	
		$insert=$db->insert("		
		 INSERT INTO prd_admin_paychange
									(entrydate,
									paychange_fromdate,
									paychange_ip,
									 order_file_name,
									 flag
									 )
									 values (
									 '".$date."',
									 '".dbdate($activation_date)."',
									 '".$_SESSION['user_agent']['USER_IP']."','".$file_name."',
									 '".$flag."'									
									  )
  							");	
									
				
				if($insert)
				{
					//$msg=$cryptography->encode('<div id="sucess">Data Successfully Updated.</div>',3);
				$msg=$cryptography->encode('<div class="alert alert-success" style="text-align:center;"><strong>Data Successfully Entered</strong></div>',3);
				}
				else
				{
					$msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Data Insertion Failed</strong></div>',3);
					/*$error_msg1='<span style="color:#D83118; font-weight:bolder;">Data Insertion Failed</span>';
 					include 'paychange_order_form.php';
			        exit;*/

				}
	  
		header('Location: '. $config['base_url'] . "page/intra_prd/state/paychange/paychange_order_insert.php?msg=".$msg);
		exit;
	}
?>
