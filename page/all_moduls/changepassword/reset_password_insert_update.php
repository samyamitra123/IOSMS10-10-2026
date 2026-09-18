<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");;
ob_start();
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
include_once ('../../../includes/library/database.class.php');
include_once '../../../includes/library/myvalidation.class.php';
require '../../../includes/library/qrtstr_encrp.php';
include_once '../../../includes/library/cryptography.class.php';
	
	if(isset($_POST['change_block'])){
		$new_password=$_POST['new_password'];
		$confirm_password=$_POST['confirm_password'];
		$dist=$_POST['dist'];
		$block_name=$_POST['block'];
		$log_id=$_POST['block'];
		
		if($validator->blank_select($new_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter New Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_prd/district/reset_password_for_block.php');
		exit(0);	
		}
		else if($validator->blank_select($confirm_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Confirm Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_prd/district/reset_password_for_block.php');
		exit(0);
		}
		else if($validator->blank_select($dist)==FALSE || $validator->pattern_number($dist)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Block Name.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_prd/district/reset_password_for_block.php');
		exit(0);
		}
		else if($validator->blank_select($block_name)==FALSE || $validator->pattern_number($block_name)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select Block Name.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_prd/district/reset_password_for_block.php');
		exit(0);
		}
		/*else if($validator->blank_select($log_id)==FALSE || $validator->pattern_number($log_id)==FALSE)
		{
			$_SESSION['msg']= '<div id="error">Please select circle name.</div>';	
			header('Location: '. $config['base_url'] . 'page/intra_ehrms/state/reset_password_for_block.php') ;
			exit;	
		}*/
		else if($new_password!=$confirm_password)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Passwords dose not match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_prd/district/reset_password_for_block.php');
		exit(0);	
		}
		
	}
	
	elseif(isset($_POST['change_gp'])){
		//echo "circle";exit;
		$new_password=$_POST['new_password'];
		//echo "1111";
		$confirm_password=$_POST['confirm_password'];
		$log_id=$_POST['gp'];
		
		//echo $circle_name;exit;
		//$log_id=$_SESSION['user_info']['stake_user'].$_POST['district_name'];
		
		if($validator->blank_select($new_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter New Password.</strong></div>';
		header('location:'.$config['base_url'].'page/all_moduls/changepassword/reset_password.php');
		exit(0);	
		}
		else if($validator->blank_select($confirm_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Confirm Password.</strong></div>';
		header('location:'.$config['base_url'].'page/all_moduls/changepassword/reset_password.php');
		exit(0);
		}
		else if($validator->blank_select($log_id)==FALSE || $validator->pattern_number($log_id)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select GP Name.</strong></div>';
		header('location:'.$config['base_url'].'page/all_moduls/changepassword/reset_password.php');
		exit(0);	
		}
		else if($new_password!=$confirm_password)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Passwords dose not match.</strong></div>';
		header('location:'.$config['base_url'].'page/all_moduls/changepassword/reset_password.php');
		exit(0);	
		}
		
		
	}
	
	elseif(isset($_POST['change_district'])){
		//echo "1111";exit;
		//echo "circle";exit;
		$new_password=$_POST['new_password'];
		//echo "1111";
		$confirm_password=$_POST['confirm_password'];
		$log_id=$_POST['district'];
		
		//echo $circle_name;exit;
		//$log_id=$_SESSION['user_info']['stake_user'].$_POST['district_name'];
		
		if($validator->blank_select($new_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter New Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_prd/state/reset_password_for_district.php');
		exit(0);	
		}
		else if($validator->blank_select($confirm_password)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Confirm Password.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_prd/state/reset_password_for_district.php');
		exit(0);
		}
		else if($validator->blank_select($log_id)==FALSE || $validator->pattern_number($log_id)==FALSE)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please Select District Name.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_prd/state/reset_password_for_district.php');
		exit(0);	
		}
		else if($new_password!=$confirm_password)
		{
			$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Passwords dose not match.</strong></div>';
		header('location:'.$config['base_url'].'page/intra_prd/state/reset_password_for_district.php');
		exit(0);	
		}
		
		
	}
	
		
		
		
	if($confirm_password==$new_password)
	{
		
	 $db=new database();
	 $cryptography=new cryptography();
	 //echo $new_password."<br>";
	// $new_pass=sha1($new_password);
	 //echo $new_pass;
	 //exit;
	/* echo "UPDATE prd_stack_user_login
		                     SET 
							 stake_password='".$new_pass."'
							 WHERE stake_user='".$log_id."'";exit;*/
							 
							
	
	$update= $db->update("UPDATE prd_stack_user_login
		                     SET 
							 new_stake_password='".$new_password."'
							
							 WHERE stake_user='".$log_id."'
							 and password_change_status = '1'
		                   ");
						   
						   
						   
						  
				if($update)
				{
					
				$_SESSION['reset_msg']= '<div class="alert alert-success" style="text-align:center"><strong>Password Successfully Updated.</strong></div>';	
				if(isset($_POST['change_gp'])){
				header('Location: '. $config['base_url'] . 'page/all_moduls/changepassword/reset_password.php') ;
				}
				elseif(isset($_POST['change_block'])){
				header('Location: '. $config['base_url'] . 'page/intra_prd/district/reset_password_for_block.php') ;
				}
				elseif(isset($_POST['change_district'])){
				header('Location: '. $config['base_url'] . 'page/intra_prd/state/reset_password_for_district.php') ;
				}
				exit(0);
				
				/*$msg=$cryptography->encode('Password Successfully Update',3);
				header('Location: '. $config['base_url'] . "page/intra_ehrms/changepassword/change_password.php?msg=".$msg);
				exit;*/
				}
				else
				{
					
					$_SESSION['reset_msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Password Updation Fails.</strong></div>';	
				if(isset($_POST['change_gp'])){
				header('Location: '. $config['base_url'] . 'page/intra_prd/block/reset_password_for_gp.php') ;
				}
				/*elseif(isset($_POST['change_block'])){
				header('Location: '. $config['base_url'] . 'page/intra_prd/state/reset_password_for_block.php') ;
				}*/
				
				elseif(isset($_POST['change_block'])){
				header('Location: '. $config['base_url'] . 'page/intra_prd/district/reset_password_for_block.php') ;
				}
				elseif(isset($_POST['change_district'])){
				header('Location: '. $config['base_url'] . 'page/intra_prd/state/reset_password_for_district.php') ;
				}
				
				
					exit(0);
				
				}
	}
				
	
	?>
