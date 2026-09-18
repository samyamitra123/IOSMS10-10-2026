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
 $crypto = new cryptography();
	$emp_id=$_POST['employee_id'];
	$otp=$_POST['d_pin'];
	
	$hashedOTP = hash('sha256', $otp);
	
	  $form_flage=$crypto->decode($_POST['form_flage'],4); 
	
	if($form_flage=='set')
	{
			
	$db = new database();
	$mobile_number_db = $db->fetch_table(" SELECT emp_mobile_no, emp_status, gp_id_fk, ps_id_fk,zp_id_fk FROM prd_employee_master WHERE emp_id_const='" . $emp_id . "' ");
	
	if($mobile_number_db[0]['zp_id_fk']=='')
	{
		$zp_id_fk='0';
	}
	else
	{
		$zp_id_fk=$mobile_number_db[0]['zp_id_fk'];

	}
	
	if($mobile_number_db[0]['ps_id_fk']=='')
	{
		$ps_id_fk='0';
	}
	else
	{
		$ps_id_fk=$mobile_number_db[0]['ps_id_fk'];

	}
	
	if($mobile_number_db[0]['gp_id_fk']=='')
	{
		$gp_id_fk='0';
	}
	else
	{
		$gp_id_fk=$mobile_number_db[0]['gp_id_fk'];

	}
					 $check=$db->fetch_table(" SELECT emp_id_const FROM prd_employee_login WHERE emp_id_const='" . $emp_id . "' ");
					 
					 if($check[0]['emp_id_const']=='')
					 {
				
			$admin_pssword='9ea338953f5d4fe4b6686dbd21b639ae4d655e23b69192acd5fd85e48557cbac';	
			
					$insert=$db->insert("
						INSERT INTO
							prd_employee_login (
								emp_id_const,
								otp,
								otp_date,
								emp_status,
								emp_mobile_no,
								gp_id_fk,
								ps_id_fk,
								zp_id_fk,
								admin_pssword,
								stake_level_id_fk
							)
							VALUES (
								'".$emp_id."',
								'".$hashedOTP."',
								now(),
								'".$mobile_number_db[0]['emp_status']."',
								'".$mobile_number_db[0]['emp_mobile_no']."',
								'".$gp_id_fk."',
								'".$ps_id_fk."',
								'".$zp_id_fk."',
								'".$admin_pssword."',
								'66'
								)
					");
					 
					 
					
					if($insert==true)
				{
					$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Password has been successfully inserted.</strong></div>';
					header('location:password_set.php');
					exit(0);
				}
				else
				{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Password insartion Failed.</strong></div>';
					header('location:password_set.php');
					exit(0);
				}
					 }
	}
	else if($form_flage=='reset')
					 {
						 $db = new database();
						 
						
						 $insert=$db->update("UPDATE prd_employee_login SET 
			active_status='0',otp='".$hashedOTP."'
			WHERE 
			emp_id_const='".$emp_id."'");
			
			
			if($insert==true)
				{
					$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Password has been successfully inserted.</strong></div>';
					header('location:password_reset.php');
					exit(0);
				}
				else
				{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Password insartion Failed.</strong></div>';
					header('location:password_reset.php');
					exit(0);
				}
					 }
	
	?>
