<?php
session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
?>

<?
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$logged_user=$_SESSION['user_info']['stake_abbr'];

error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
$crypto = new cryptography();

if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center"><strong>IFMS details submitted Successfully...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center"><strong>Data insertion failed. Please try again...</strong></div>';
}
function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

$yemo=date("Y").date("m");
$db = new database();



//$bill_status=$crypto->decode($_GET['bill_status'],4);
//$bill_type= $crypto->decode($_GET['emp_type'],4);

$cryptoGraph=new cryptography();
 $emp_type=$cryptoGraph->decode($_REQUEST['emp_type'],4); 
if($emp_type=='366')
{
	$type=$cryptoGraph->encode(6,4); 
}
else if($emp_type=='367')
{
	$type=$cryptoGraph->encode(7,4); 
}
else
{
	$type=$cryptoGraph->encode(5,4); 
	//$type=0;
}
/*
echo "<pre>";
print_r($_SESSION);
echo "<pre>";*/

if($logged_user=='EO')
{
	
	$upd = $db->update("
						UPDATE prd_employee_arrear  SET status_flag=1
	where status_flag=2 AND delete_status=1 AND is_saved=1 AND salary_monthyear is null AND ps_id_fk = '".$_SESSION['location']['ps_id']."' and bill_serial_no='0' AND ropa_status='1'
						");

}
else if($logged_user=='BDO')
{
	
	$upd = $db->update("
						UPDATE prd_employee_arrear  SET status_flag=1
	where status_flag=2 AND delete_status=1 AND is_saved=1 AND salary_monthyear is null AND block_code = '".$_SESSION['user_info']['stake_user']."' and bill_serial_no='0' AND ropa_status='1'
						");
}
else if($logged_user=='FC&CAO')
{
	
	$upd = $db->update("
						UPDATE prd_employee_arrear  SET status_flag=1
	where status_flag=2 AND delete_status=1 AND is_saved=1 AND salary_monthyear is null AND zp_id_fk='".$_SESSION['location']['district_id']."' AND zp_emp_type='".$emp_type."' and bill_serial_no='0' AND ropa_status='1'
						");
}



	if($upd && $emp_type!='5')
	{
	
		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Bill unlock has been finalized successfully.</strong></div>';
		header('Location:'.$config['base_url'].'page/all_moduls/arrear/employee_lists_ropa_2019.php?emp_type='.$type);
		exit(0);
	
	} 
	else if($upd && ($emp_type=='5' || $emp_type==''))	
	{
	
		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Bill unlock has been finalized successfully.</strong></div>';
		header('Location:'.$config['base_url'].'page/all_moduls/arrear/employee_lists_ropa_2019.php?emp_type='.$type);
		exit(0);
	
	}
			
			
	else 
	{
	
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Bill unlock has been Failed</strong></div>';
		header('Location:'.$config['base_url'].'page/all_moduls/arrear/employee_lists_ropa_2019.php?emp_type='.$type);
		exit(0);
	} ?>



