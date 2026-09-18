<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
include_once ('../../../includes/library/database.class.php');
include_once '../../../includes/library/myvalidation.class.php';
require '../../../includes/library/qrtstr_encrp.php';
include_once '../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';

?>
<?php

	if(!isset($_POST['changepassword_submit'])){
		header('Location: '. $config['base_url'] . "page/intra_prd/changepassword/change_password.php");
		exit;
	}else if(isset($_POST['changepassword_submit'])){
		
		$sec_time_token=$_POST['sec_tok'];
	$session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	$security_code = htmlentities(strip_tags($_POST['security_code']));
		
	if($sec_time_token!=$enc_session){
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
		header('location:change_password.php');
		exit(0);
	}else{
	if (isset($security_code)) {

	if ($security_code == "") {
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter captcha code!!</strong></div>';
		header('location:change_password.php');
		exit(0);

	} elseif (md5($_SESSION['security_code']) == $security_code) {


		$msg="";
		
		$old_pass=$_POST['old_pass'];
		$new_pass=$_POST['new_pass'];
		$conf_pass=$_POST['conf_pass'];
		
		
		$date=date("Y-m-d");
		
		
		if((!$validator->blank_select($old_pass)) ){
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert Old Password.</strong></div>';
			header('location:change_password.php');
			exit(0);

		}
		if((!$validator->blank_select($new_pass)) ){
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert New Password.</strong></div>';
			header('location:change_password.php');
			exit(0);

		}
		if((!$validator->blank_select($conf_pass)) ){	
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Insert Confirm Password.</strong></div>';
			header('location:change_password.php');
			exit(0);

		}
		if($new_pass==$old_pass){
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>New Password should not be old password.</strong></div>';
			header('location:change_password.php');
			exit(0);
		}
		if($new_pass!=$conf_pass){
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>New Password and Confirm Password does not match.</strong></div>';
			header('location:change_password.php');
			exit(0);
		}
		
			
	}
	
	
    if($conf_pass==$new_pass)
	{
	
	 
							  	
	 $db=new database();
	 $cryptography=new cryptography();
	 //echo $old_pass."<br>";exit;
	 $new_pass=sha1($new_pass);
	 $old_pass=sha1($old_pass);
	// echo $new_pass;
	/* $old_pass_chk=$db->fetch_table("SELECT * FROM prd_stack_user_login
	                         WHERE stake_user='".$_SESSION['user_info']['stake_user']."'
							 AND stake_password='".$old_pass."'
							 AND stake_level_id_fk='".$_SESSION['user_info']['stake_level']."'
	 
	                                  ");
									  if(count($old_pass_chk)<1)
									  {
										$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Old Password Does Not Match For this User.</strong></div>';
										header('location:change_password.php');
										exit(0);
										  
	 								  } */
                           // AND stake_password='".$old_pass."'

	 					   $Query = "UPDATE prd_stack_user_login
		                     SET 
							 stake_password='".$new_pass."'
							 WHERE stake_user='".$_SESSION['user_info']['stake_user']."'
							 AND stake_level_id_fk='".$_SESSION['user_info']['stake_level']."'
		                   ";
	// print_r($Query); exit;
	$update= $db->update($Query);
				if($update)
				{
					$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Password has been successfully changed.</strong></div>';
					header('location:change_password.php');
					exit(0);
				}
				else
				{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Password Update Failed.</strong></div>';
					header('location:change_password.php');
					exit(0);
				}
	}
	else
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>New password And Confirm Password does not matched.</strong></div>';
		header('location:change_password.php');
		exit(0);
	}
		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>'.$msg.'</strong></div>';
		header('location:change_password.php');
		exit(0);
	}
	
	
	else
	{
		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Capcha Does Not Match.</strong></div>';
		header('location:change_password.php');
		exit(0);
	}
	}
	
	}
	?>
