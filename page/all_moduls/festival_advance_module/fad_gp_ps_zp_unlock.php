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
if(isset($_GET['dise'])){
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

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "VIEW SALARY REQUISITION | iOSMS | Govt. of West Bengal ";


/*$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];*/
//var_dump($_SESSION['user_info']['stake_abbr']); die;
	$db=new database();
	
	if($_SESSION['user_info']['stake_abbr'] =='BDO')
	{ 
		$query_unlock=$db->update("UPDATE prd_festival_advance_employee_details fad SET festival_advance_status='2'
							WHERE block_code='".$_SESSION['location']['block_code']."' AND festival_advance_status='4' 
							AND substr(fad_monthyear,1,4)='".date('Y')."'"); 
			
		if($query_unlock)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Festival Advance Unlocked Successfully...</strong></div>';
			header('Location:ul_fad_gp_list.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Festival Advance Unlocking failed. Please try again...</strong></div>';
			header ('Location:ul_fad_gp_list.php');
		}
	}
	
	
	else if($_SESSION['user_info']['stake_abbr'] =='EO')
	{
		$query_unlock=$db->update("UPDATE prd_festival_advance_employee_details fad SET festival_advance_status='2'
							WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' AND festival_advance_status='4' 
							AND substr(fad_monthyear,1,4)='".date('Y')."'"); 
												
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
	
	
	else if($_SESSION['user_info']['stake_abbr'] =='FC&CAO')
	{
		$query_unlock=$db->update("UPDATE prd_festival_advance_employee_details fad SET festival_advance_status='2'
							WHERE zp_id_fk='".$_SESSION['location']['district_id']."' AND festival_advance_status='4' 
							AND substr(fad_monthyear,1,4)='".date('Y')."'"); 
												
		if($query_unlock)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Festival Advance Unlocked Successfully...</strong></div>';
			header('Location:up_festive_bill_view.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Festival Advance Unlocking failed. Please try again...</strong></div>';
			header ('Location:up_festive_bill_view.php');
		}
	}
	
	

?>