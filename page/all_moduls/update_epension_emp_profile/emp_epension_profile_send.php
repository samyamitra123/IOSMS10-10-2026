<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>User Profile Submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}
//echo 11; die;

$crypto = new cryptography();
$db=new database();

	////////////////////////////////////////////////////////////////condisation/////////////////////////////////////

	
$stake=$_SESSION['user_info']['stake_level'];
if($stake==37)
{
	$stake_level=$crypto->encode($stake,4);
	 $id=$crypto->encode(1,4);
}
else if($stake==64)
{
	$stake_level=$crypto->encode($stake,4);
	 $id=$crypto->encode(3,4);
}
else if($stake==52)
{
	$stake_level=$crypto->encode($stake,4);
	 $id=$crypto->encode(5,4);
}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 $emp_id_fk=$crypto->decode($_REQUEST['emp_id'],4);



 
	$query=$db->update("UPDATE prd_stake_epension_employee_profile SET
	                 status='2'
					 where emp_id_fk='".$emp_id_fk."'
					 and status='1'
					  ");
	if($query)
	{
		$update=$db->update("UPDATE prd_employee_master SET
		update_status='2'
		where emp_id_pk='".$emp_id_fk."'
		and update_status='1'
		");
	
	}

if($update)
		{
			
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Employee profile send Successfully...</strong></div>';
			header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Employee profile sending failed...</strong></div>';
			header('Location:e_pension_user_profile_view.php?stake_level='.$stake_level.'&id='.$id);
		}

?>