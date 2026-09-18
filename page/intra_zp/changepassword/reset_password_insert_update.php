<?php
/*print_r($_REQUEST);
exit;*/


ob_start();
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
include_once ('../../../includes/library/database.class.php');
include_once '../../../includes/library/myvalidation.class.php';
require '../../../includes/library/qrtstr_encrp.php';
include_once '../../../includes/library/cryptography.class.php';

   
	
		$sec_time_token=$_POST['sec_tok'];
		$session_token=$_SESSION['security_token'];
		$enc_session=md5('369'.$session_token);
		$security_code = htmlentities(strip_tags($_POST['security_code']));
	 // md5($_SESSION['security_code']);
	//die;
		
	 if(isset($_POST['change_fc_and_cao'])){
	     
		$new_password=$_POST['new_password'];
		$confirm_password=$_POST['confirm_password'];
		$db=new database();
		$data11 = $db->fetch_table("SELECT COUNT(*) AS result_count FROM prd_stack_user_login where stake_level_id_fk = '53'  AND stake_user='".$_SESSION['user_info']['stake_user']."'");
	     $count11 = $data11[0][result_count];
		$stake_user_alias=$_POST['fc_and_cao_code'];
		$stake_user=$_POST['fc_and_cao'];
		$stake_level_id = $_POST['stake_level_id'];
		$log_id=$_POST['fc_and_cao']; 
		//$log_id=$_SESSION['user_info']['stake_user'].$_POST['district_name'];
		
		//die;
		if($sec_time_token!=$enc_session){
		$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_fc_and_cao.php');
		exit(0);
	}
	else{

	if ($security_code == "") {
		$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter captcha code!!</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_fc_and_cao.php');
		exit(0);

	}
	 elseif (md5($_SESSION['security_code']) != $security_code) {
		
$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Capcha Does Not Match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_fc_and_cao.php');
		exit(0);
	}
		
		
		else if($validator->blank_select($new_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter New Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_fc_and_cao.php');
		exit(0);	
		}
		else if($validator->blank_select($confirm_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Confirm Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_fc_and_cao.php');
		exit(0);
		}
		//else if($validator->blank_select($log_id)==FALSE || $validator->pattern_number($log_id)==FALSE)
		else if($validator->blank_select($stake_user_alias)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select FC And CAO Name.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_fc_and_cao.php');
		exit(0);	
		}
		else if($new_password!=$confirm_password)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Passwords dose not match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_fc_and_cao.php');
		exit(0);	
		}
		
	}
	}
	
	else if(isset($_POST['change_sec'])){
		$new_password=$_POST['new_password'];
		$confirm_password=$_POST['confirm_password'];
		$db=new database();
		$data10 = $db->fetch_table("SELECT COUNT(*) AS result_count FROM prd_stack_user_login where stake_level_id_fk = '51'  AND stake_user='".$_SESSION['user_info']['stake_user']."'");
		$count10 = $data10[0][result_count];
		$stake_user_alias=$_POST['sec_code'];
		$stake_user=$_POST['sec'];
		$stake_level_id = $_POST['stake_level_id'];
		$log_id=$_POST['sec'];
		//$log_id=$_SESSION['user_info']['stake_user'].$_POST['district_name'];
		

	if($sec_time_token!=$enc_session){
		$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_sec.php');
		exit(0);
	}
	else{

	if ($security_code == "") {
		$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter captcha code!!</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_sec.php');
		exit(0);

	}
	 elseif (md5($_SESSION['security_code']) != $security_code) {
		
$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Capcha Does Not Match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_sec.php');
		exit(0);
	}
	
	
		else if($validator->blank_select($new_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter New Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_sec.php');
		exit(0);	
		}
		else if($validator->blank_select($confirm_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Confirm Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_sec.php');
		exit(0);
		}
		else if($validator->blank_select($stake_user_alias)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Secretary Name.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_sec.php');
		exit(0);	
		}
		else if($new_password!=$confirm_password)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Passwords dose not match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/aeo/reset_password_for_sec.php.php');
		exit(0);	
		}
	}
	
	}
	

else if(isset($_POST['change_dae'])){
		$new_password=$_POST['new_password'];
		$confirm_password=$_POST['confirm_password'];
		$db=new database();
		$data12 = $db->fetch_table("SELECT COUNT(*) AS result_count FROM prd_stack_user_login where stake_level_id_fk = '52'  AND stake_user='".$_SESSION['user_info']['stake_user']."'");
		$count12 = $data12[0][result_count];
		$stake_user_alias=$_POST['dae_code'];
		$stake_user=$_POST['dae'];
		$stake_level_id = $_POST['stake_level_id'];
		$log_id=$_POST['dae'];
		//$log_id=$_SESSION['user_info']['stake_user'].$_POST['district_name'];
		if($sec_time_token!=$enc_session){
		$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/secretary/reset_password_for_dae.php');
		exit(0);
	}
	else{

	if ($security_code == "") {
		$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter captcha code!!</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/secretary/reset_password_for_dae.php');
		exit(0);

	}
	 elseif (md5($_SESSION['security_code']) != $security_code) {
		
$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Capcha Does Not Match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/secretary/reset_password_for_dae.php');
		exit(0);
	}
		
		
		else if($validator->blank_select($new_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter New Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/secretary/reset_password_for_dae.php');
		exit(0);	
		}
		else if($validator->blank_select($confirm_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Confirm Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/secretary/reset_password_for_dae.php');
		exit(0);
		}
		else if($validator->blank_select($stake_user_alias)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Dealing Assistant (Establishment) Name.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/secretary/reset_password_for_dae.php');
		exit(0);	
		}
		else if($new_password!=$confirm_password)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Passwords dose not match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/secretary/reset_password_for_dae.php');
		exit(0);	
		}
	}
		
	}

	
	else if(isset($_POST['change_acc'])){
		$new_password=$_POST['new_password'];
		$confirm_password=$_POST['confirm_password'];
		$db=new database();
		$data13 = $db->fetch_table("SELECT COUNT(*) AS result_count FROM prd_stack_user_login where stake_level_id_fk = '54'  AND stake_user='".$_SESSION['user_info']['stake_user']."'");
		$count13 = $data13[0][result_count];
		$stake_user_alias=$_POST['acc_code'];
		$stake_user=$_POST['acc'];
		$stake_level_id = $_POST['stake_level_id'];
		$log_id=$_POST['acc'];
		//$log_id=$_SESSION['user_info']['stake_user'].$_POST['district_name'];
		
		if($sec_time_token!=$enc_session){
		$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_fcncao/reset_password_for_accountant.php');
		exit(0);
	}
	else{

	if ($security_code == "") {
		$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter captcha code!!</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_fcncao/reset_password_for_accountant.php');
		exit(0);

	}
	 elseif (md5($_SESSION['security_code']) != $security_code) {
		
$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Capcha Does Not Match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_fcncao/reset_password_for_accountant.php');
		exit(0);
	}
		
		
		
		else if($validator->blank_select($new_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter New Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_fcncao/reset_password_for_accountant.php');
		exit(0);	
		}
		else if($validator->blank_select($confirm_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Confirm Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_fcncao/reset_password_for_accountant.php');
		exit(0);
		}
		else if($validator->blank_select($stake_user_alias)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Accountant Name.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_fcncao/reset_password_for_accountant.php');
		exit(0);	
		}
		else if($new_password!=$confirm_password)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Passwords dose not match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_fcncao/reset_password_for_accountant.php');
		exit(0);	
		}
	}
		
	}
	
	
	else if(isset($_POST['change_daa'])){
		$new_password=$_POST['new_password'];
		$confirm_password=$_POST['confirm_password'];
		$db=new database();
		$data14 = $db->fetch_table("SELECT COUNT(*) AS result_count FROM prd_stack_user_login where stake_level_id_fk = '55'  AND stake_user='".$_SESSION['user_info']['stake_user']."'");
		$count14 = $data14[0][result_count];
		$stake_user_alias=$_POST['daa_code'];
		$stake_user=$_POST['daa'];
		$stake_level_id = $_POST['stake_level_id'];
		$log_id=$_POST['daa'];
		//$log_id=$_SESSION['user_info']['stake_user'].$_POST['district_name'];
		
		if($sec_time_token!=$enc_session){
		$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_accountant/reset_password_for_daa.php');
		exit(0);
	}
	else{

	if ($security_code == "") {
		$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter captcha code!!</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_accountant/reset_password_for_daa.php');
		exit(0);

	}
	 elseif (md5($_SESSION['security_code']) != $security_code) {
		
$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Capcha Does Not Match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_accountant/reset_password_for_daa.php');
		exit(0);
	}
		
		
		else if($validator->blank_select($new_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter New Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_accountant/reset_password_for_daa.php');
		exit(0);	
		}
		else if($validator->blank_select($confirm_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Confirm Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_accountant/reset_password_for_daa.php');
		exit(0);
		}
		else if($validator->blank_select($stake_user_alias)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Dealing Assistant (Account) Name.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_accountant/reset_password_for_daa.php');
		exit(0);	
		}
		else if($new_password!=$confirm_password)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Passwords dose not match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_zp/salary_accountant/reset_password_for_daa.php');
		exit(0);	
		}
	}
		
	}	
		
		
	if($confirm_password==$new_password)
	{
		
	 $db=new database();
	 $cryptography=new cryptography();
	 //echo $new_password."<br>";
	 $new_pass=sha1($new_password);
	   
	 
	if(($count11 < 1) && ($stake_level_id == 53) )
	{
//		echo "rrr";
//		die;
	 $db=new database();

	$query_insert=$db->insert("INSERT into prd_stack_user_login
											(											
											 stake_level_id_fk,
											 stake_user,   
											 stake_password,
											 stake_credential_flag,
											 entry_time,
											 entry_ip,
											 stake_user_alias,
											 password_status
											 )
											 VALUES(
											 '$stake_level_id',
											 '$stake_user',
											 '$new_pass',
											 '1',
											 'now()',
											 '".$_SERVER['REMOTE_ADDR']."',
											 '".$stake_user_alias."',
											 '1')");
								
	}	
	
	else if(($count10 < 1) && ($stake_level_id == 51))
	{
	  
		//echo "rrr";
		//die;
	 $db=new database();

	$query_insert=$db->insert("INSERT into prd_stack_user_login
											(											
											 stake_level_id_fk,
											 stake_user,   
											 stake_password,
											 stake_credential_flag,
											 entry_time,
											 entry_ip,
											 stake_user_alias,
											 password_status
											 )
											 VALUES(
											 '$stake_level_id',
											 '$stake_user',
											 '$new_pass',
											 '1',
											 'now()',
											 '".$_SERVER['REMOTE_ADDR']."',
											 '".$stake_user_alias."',
											 '1')");
								
	}	
	
	
	else if(($count12 < 1) && ($stake_level_id == 52))
	{
		//echo "rrr";
		//die;
	 $db=new database();
	$query_insert=$db->insert("INSERT into prd_stack_user_login
											(											
											 stake_level_id_fk,
											 stake_user,   
											 stake_password,
											 stake_credential_flag,
											 entry_time,
											 entry_ip,
											 stake_user_alias,
											 password_status
											 )
											 VALUES(
											 '$stake_level_id',
											 '$stake_user',
											 '$new_pass',
											 '1',
											 'now()',
											 '".$_SERVER['REMOTE_ADDR']."',
											 '".$stake_user_alias."',
											 '1')");
								
	}	
	
	
	else if(($count13 < 1) && ($stake_level_id == 54))
	{
		//echo "rrr";
		//die;
	 $db=new database();
	$query_insert=$db->insert("INSERT into prd_stack_user_login
											(											
											 stake_level_id_fk,
											 stake_user,   
											 stake_password,
											 stake_credential_flag,
											 entry_time,
											 entry_ip,
											 stake_user_alias,
											 password_status
											 )
											 VALUES(
											 '$stake_level_id',
											 '$stake_user',
											 '$new_pass',
											 '1',
											 'now()',
											 '".$_SERVER['REMOTE_ADDR']."',
											 '".$stake_user_alias."',
											 '1')");
								
	}
	
	else if(($count14 < 1) && ($stake_level_id == 55))
	{
		//echo "rrr";
		//die;
	 $db=new database();
	$query_insert=$db->insert("INSERT into prd_stack_user_login
											(											
											 stake_level_id_fk,
											 stake_user,   
											 stake_password,
											 stake_credential_flag,
											 entry_time,
											 entry_ip,
											 stake_user_alias,
											 password_status
											 )
											 VALUES(
											 '$stake_level_id',
											 '$stake_user',
											 '$new_pass',
											 '1',
											 'now()',
											 '".$_SERVER['REMOTE_ADDR']."',
											 '".$stake_user_alias."',
											 '1')");
											 
											
								
	}
	
		   
	else
	{	
	    
	$update= $db->update("UPDATE prd_stack_user_login
		                     SET 
							 stake_password='".$new_pass."'
							 WHERE stake_user_alias='".$stake_user_alias."'
		                   ");
						 
	}
						   
				if($query_insert)
				{
					$cryptoGraph=new cryptography();
					//$cryptography=new cryptography();
				
				if(isset($_POST['change_fc_and_cao'])){
				$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>FC & CAO Profile Successfully Created.</strong></div>';	
				header('Location:'. $config['base_url'].'page/dashboard.php?confirm='.$cryptoGraph->encode('success_fc_cao',7)) ;
				//die;
				}
				elseif(isset($_POST['change_sec'])){
				$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Secretary Profile Successfully Created.</strong></div>';	
				header('Location:'. $config['base_url'].'page/dashboard.php?confirm='.$cryptoGraph->encode('success_sec',7)) ;
				}
				elseif(isset($_POST['change_dae'])){
				$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Dealing Assistant (Establishment) Profile Successfully Created.</strong></div>';	
				//header('Location:'. $config['base_url'].'page/dashboard.php?confirm='.$cryptoGraph->encode('success_dae',7)) ;
				//header('Location: '. $config['base_url'] . 'page/intra_zp/secretary/reset_password_for_dae.php') ;
				header('Location:'. $config['base_url'].'page/dashboard.php?confirm='.$cryptoGraph->encode('success_dae',7)) ;
				}
				
				elseif(isset($_POST['change_acc'])){
				$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Accountant Profile Successfully Created.</strong></div>';	
				//header('Location:'. $config['base_url'].'page/dashboard.php?confirm='.$cryptoGraph->encode('success_dae',7)) ;
				//header('Location: '. $config['base_url'] . 'page/intra_zp/salary_fcncao/reset_password_for_accountant.php') ;
				header('Location:'. $config['base_url'].'page/dashboard.php?confirm='.$cryptoGraph->encode('success_acc',7)) ;
				}
				elseif(isset($_POST['change_daa'])){
				$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Dealing Assistant (Account) Profile Successfully Created.</strong></div>';	
				header('Location:'. $config['base_url'].'page/dashboard.php?confirm='.$cryptoGraph->encode('success_daa',7)) ;
				//header('Location: '. $config['base_url'] . 'page/intra_zp/salary_accountant/reset_password_for_daa.php') ;
				}
				
				/*elseif(isset($_POST['change_district'])){
				header('Location: '. $config['base_url'] . 'page/intra_prd/state/reset_password_for_district.php') ;
				}*/
				exit(0);
				
				/*$msg=$cryptography->encode('Password Successfully Update',3);
				header('Location: '. $config['base_url'] . "page/intra_ehrms/changepassword/change_password.php?msg=".$msg);
				exit;*/
				}		   
						   
						  
				else if($update)
				{
					
				$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Password Successfully Updated.</strong></div>';	
				if(isset($_POST['change_fc_and_cao'])){
				header('Location: '. $config['base_url'] . 'page/intra_zp/aeo/reset_password_for_fc_and_cao.php') ;
				}
				elseif(isset($_POST['change_sec'])){
				header('Location: '. $config['base_url'] . 'page/intra_zp/aeo/reset_password_for_sec.php') ;
				}
				elseif(isset($_POST['change_dae'])){
				header('Location: '. $config['base_url'] . 'page/intra_zp/secretary/reset_password_for_dae.php') ;
				}
				elseif(isset($_POST['change_acc'])){
				header('Location: '. $config['base_url'] . 'page/intra_zp/salary_fcncao/reset_password_for_accountant.php') ;
				}
				elseif(isset($_POST['change_daa'])){
				header('Location: '. $config['base_url'] . 'page/intra_zp/salary_accountant/reset_password_for_daa.php') ;
				}
				
				/*elseif(isset($_POST['change_district'])){
				header('Location: '. $config['base_url'] . 'page/intra_prd/state/reset_password_for_district.php') ;
				}*/
				exit(0);
				
				/*$msg=$cryptography->encode('Password Successfully Update',3);
				header('Location: '. $config['base_url'] . "page/intra_ehrms/changepassword/change_password.php?msg=".$msg);
				exit;*/
				}
				else
				{
				   
					
					$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Password Updation Fails.</strong></div>';	
				
			/*	if(isset($_POST['change_da'])){
				header('Location: '. $config['base_url'] . 'page/intra_ps/eo/reset_password_for_da.php') ;
				}
				elseif(isset($_POST['change_block'])){
				header('Location: '. $config['base_url'] . 'page/intra_prd/district/reset_password_for_block.php') ;
				}*/
				
				if(isset($_POST['change_fc_and_cao'])){
				header('Location: '. $config['base_url'] . 'page/intra_zp/aeo/reset_password_for_fc_and_cao.php') ;
				}
				elseif(isset($_POST['change_sec'])){
				header('Location: '. $config['base_url'] . 'page/intra_zp/aeo/reset_password_for_sec.php') ;
				}
				elseif(isset($_POST['change_dae'])){
				header('Location: '. $config['base_url'] . 'page/intra_zp/secretary/reset_password_for_dae.php') ;
				}
				elseif(isset($_POST['change_acc'])){
				header('Location: '. $config['base_url'] . 'page/intra_zp/salary_fcncao/reset_password_for_accountant.php') ;
				}
				elseif(isset($_POST['change_daa'])){
				header('Location: '. $config['base_url'] . 'page/intra_zp/salary_accountant/reset_password_for_daa.php') ;
				}
				
					exit(0);
				
				}
	}
				
	
	?>
