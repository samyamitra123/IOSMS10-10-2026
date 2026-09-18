<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

 
session_start();
require '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
include_once ('../../includes/library/database.class.php');
include_once '../../includes/library/myvalidation.class.php';
require '../../includes/library/qrtstr_encrp.php';
include_once '../../includes/library/cryptography.class.php';
//require '../../page_visite.php';

?>
<?php

	$officer_id=$_POST['officer_id'];
	$e_mobile=$_POST['e_mobile'];
	$otp=$_POST['d_pin'];
	
	$hashedOTP = hash('sha256', $otp);
			
	$db = new database();
	
					$update= $db->update("UPDATE intra_pri_master
		                     SET user_pin='".$hashedOTP."', active_status='0'
							 WHERE officer_id_const='".$officer_id."'
							 and mobile_no = '".$e_mobile."' ");
							 
			if($update==true)
				{
					$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Password Updated successfully.</strong></div>';
					header('location:reset_password_intra_pri.php');
					exit(0);
				}
				else
				{
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Password update Failed.</strong></div>';
					header('location:reset_password_intra_pri.php');
					exit(0);
				}
					 
	
	?>
