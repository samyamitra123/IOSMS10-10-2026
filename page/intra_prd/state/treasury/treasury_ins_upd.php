<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
include_once '../../../../includes/library/myvalidation.class.php';
require '../../../../includes/library/cryptography.class.php';

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$db = new database();
$cryptography= new cryptography();
$sec_time_token=$_POST['sec_tok'];
	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	if($sec_time_token!=$enc_session){
		//echo "ddd";exit;
		$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Time Out!..Please Try Again.</strong></div>',3);
		header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				//include 'treasury_details_for_secondary.php' ;
		exit;
	}
	else if(isset($_POST['submit'])){
	$district_name = $_POST['district_name'] ;
	$block_name = $_POST['block_name'] ;
	$block_code = $_POST['block_code'] ;
	
	$treasury_block_code = $_POST['treasury_block_code'] ;
	//echo $treasury_ddo_code;
	$line1 = $_POST['line1'] ;
	$line2 = $_POST['line2'] ;
	$line = $line1.",".$line2 ;
	$treasury_name = $_POST['treasury_name'] ;
	$lead_bank_branch = $_POST['lead_bank_branch'] ;
	$branch_name = $_POST['branch_name'] ;
	$dpp = $_POST['dpp'] ;
	$desig_block=$_POST['desig_block'];
	$tan_no = $_POST['tan_no'] ;
	
	if($validator->blank_select($district_name) == FALSE)
			{
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Select District Name.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				//include 'treasury_details_for_secondary.php' ;
				exit;
			}
			
			if($validator->pattern_match_alphanumeric($district_name) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid District Name.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			
			if($validator->blank_select($block_name) == FALSE)
			{
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Select Block Name.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				//include 'treasury_details_for_secondary.php' ;
				exit;
			}
			
			if($validator->pattern_match_alphanumeric($block_name) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Block Name.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			
			if($validator->blank_select($block_code) == FALSE)
			{
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Block Code.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				//include 'treasury_details_for_secondary.php' ;
				exit;
			}
			
			if($validator->pattern_match_alphanumeric($block_code) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Block Code.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			
			
			if($validator->blank_select($treasury_block_code) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Block CODE.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				//include 'treasury_details_for_secondary.php' ;
				exit;
			}
			
			
			if($validator->pattern_match_alphanumeric($treasury_block_code) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Treasury Block Code.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			
			if($validator->blank_select($desig_block) == FALSE)
			{
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Designation of Block.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				//include 'treasury_details_for_secondary.php' ;
				exit;
			}
			
			if($validator->pattern_match_alphanumeric($desig_block) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Designation of Block.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			
			//echo 2;exit;
			if($validator->blank_select($line1) == FALSE)
			{
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Address Line1.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				//include 'treasury_details_for_secondary.php' ;
				exit;
			}
			if($validator->pattern_match_alphanumeric($line1) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Address Line1.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			
			
			if($validator->pattern_match_alphanumeric($line2) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Address Line2.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			
			if($validator->blank_select($treasury_name) == FALSE)
			{
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Treasury Name.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				//include 'treasury_details_for_secondary.php' ;
				exit;
			}
			if($validator->pattern_match_alphanumeric($treasury_name) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Treasury Name.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			if($validator->blank_select($lead_bank_branch) == FALSE)
			{
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Lead Bank Branch.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				//include 'treasury_details_for_secondary.php' ;
				exit;
			}
			
			if($validator->pattern_match_alphanumeric($lead_bank_branch) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Lead Bank Branch.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			
			if($validator->blank_select($branch_name) == FALSE)
			{
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Please Enter Branch Name.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				//include 'treasury_details_for_secondary.php' ;
				exit;
			}
			
			if($validator->pattern_match_alphanumeric($branch_name) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Branch Name.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			
			
			
			
			if($validator->pattern_match_alphanumeric($tan_no) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Tan No.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
			
			if($validator->pattern_match_alphanumeric($dpp) == FALSE)
			{
				
				$error_msg=$cryptography->encode('<div class="alert alert-danger" style="text-align:center;"><strong>Invalid Duration of Permanent Post.</strong></div>',3);
				header('Location: '. $config['base_url'] . "page/intra_prd/state/treasury_details.php?msg_er=".$error_msg);
				exit;
			}
	
	$block = $db->fetch_table("SELECT distcd, block_code, treasury_block_code, treasury_name, lead_bank,lead_branch, entry_time, ip_add, 	desig_block,app_duration, address_line2,tan_no, admin_id_pk FROM prd_dise_admin where block_code='$block_name'") ; 
	   //echo count($block);exit;
	if(count($block)>0){
		$update = $db->update("update prd_dise_admin set treasury_block_code ='$treasury_block_code', address_line2='$line', treasury_name='$treasury_name', lead_bank='$lead_bank_branch',lead_branch='$branch_name',app_duration='$dpp',tan_no='$tan_no',desig_block='$desig_block' where block_code='".$block_code."'");
		//echo "update prd_dise_admin set treasury_ddo_code ='$treasury_ddo_code', address_line2='$line', treasury_name='$treasury_name', lead_bank='$lead_bank_branch',lead_branch='$branch_name',app_duration='$dpp',tan_no='$tan_no' where ddo_code='".$sub_div_code."'";exit;
		if($update)
		{
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center;"><strong>Treasury Details Has Been Updated successfully.</strong></div>';	
			header('location:treasury_details.php') ;
			exit(0);
		}
	}else{
		$district_code=$district_name;
		$now1=$db->fetch_table("select now()");
		$now=$now1[0]['now'];
		
		$insert = $db->insert("INSERT INTO prd_dise_admin(
            distcd, block_code, treasury_block_code, treasury_name, lead_bank, 
            lead_branch, entry_time, ip_add, desig_block, app_duration, address_line2, 
            tan_no)
    VALUES ('$district_code','$block_name','$treasury_block_code','$treasury_name','$lead_bank_branch','$branch_name', 
            '$now','".$_SESSION['user_agent']['USER_IP']."','$desig_block','$dpp', 
            '$line','$tan_no')");
			
			if($insert)
			{
				$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center;"><strong>Treasury Details Has Been Inserted successfully.</strong></div>';	
			header('location:treasury_details.php') ;
			exit(0);	
				
			}
			
	}
}else{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Treasury Details Not Inserted.</strong></div>';	
	header('location:treasury_details.php') ;
}
