<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

require '../../../includes/library/myvalidation.class.php';

//require '../../../page_visite.php';

$crypto = new cryptography();
if($_GET['dise']){
$_SESSION['dise'] = $crypto->decode($_GET['dise'],3);
}

if (
!isset($_SESSION['user_info']['stake_user'])
| !isset($_SESSION['user_info']['stake_level'])
| !isset($_SESSION['user_info']['flag'])

){
header('Location: '. $config['base_url'] . "page/login.php");
exit;
}

//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "VIEW SALARY REQUISITION | iOSMS | Govt. of West Bengal ";

$db=new database();

/*$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];*/

if($crypto->decode($_POST['flag'],4)=='LOCK')
{
	$db=new database();
	if($logged_user=='EO')
	{
		$query_lock=$db->update("UPDATE prd_festival_advance_employee_details fad SET festival_advance_status='4'
							FROM prd_employee_master emp 
							WHERE emp.ps_id_fk='".$_SESSION['location']['ps_id']."' AND festival_advance_status='3' 
							AND substr(fad_monthyear,1,4)='".date('Y')."'"); 
	}
	else if($logged_user=='zpacc')
	{
		$query_lock=$db->update("UPDATE prd_festival_advance_employee_details fad SET festival_advance_status='4',zp_id_fk='".$_SESSION['location']['district_id']."'
							FROM prd_employee_master emp 
							WHERE emp.zp_id_fk='".$_SESSION['location']['district_id']."' AND festival_advance_status='3' 
							AND substr(fad_monthyear,1,4)='".date('Y')."'"); 
	}
	
	if($query_lock)
	{
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Festival Advance Locked Successfully...</strong></div>';
		header('Location:ul_emp_festival_advance_list.php');
	}
	else
	{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Festival Advance Locking failed. Please try again...</strong></div>';
		header('Location:ul_emp_festival_advance_list.php');
	}
}
if($crypto->decode($_POST['flag'],4)=='UNLOCK')
{
	$db=new database();
	
	if($logged_user=='EO')
	{
		$query_unlock=$db->update("UPDATE prd_festival_advance_employee_details fad SET festival_advance_status='2'
							FROM prd_employee_master emp 
							WHERE emp.ps_id_fk='".$_SESSION['location']['ps_id']."' AND festival_advance_status='3' 
							AND substr(fad_monthyear,1,4)='".date('Y')."'"); 
	}
	else if($logged_user=='zpacc')
	{
		$query_unlock=$db->update("UPDATE prd_festival_advance_employee_details fad SET festival_advance_status='2'
							FROM prd_employee_master emp 
							WHERE emp.zp_id_fk='".$_SESSION['location']['district_id']."' AND festival_advance_status='3' 
							AND substr(fad_monthyear,1,4)='".date('Y')."'"); 
	}
	
	if($query_unlock)
	{
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Festival Advance Unlocked Successfully...</strong></div>';
		header('Location:ul_emp_festival_advance_list.php');
	}
	else
	{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Festival Advance Unlocking failed. Please try again...</strong></div>';
		header ('Location:ul_emp_festival_advance_list.php');
	}
}
?>