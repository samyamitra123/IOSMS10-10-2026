<?php

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';


//---------------------------------------------


//require_once '../../../../includes/library/cryptography.class.php';
//require_once '../../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

//$cryp = new cryptography();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

$logged_user=$_SESSION['user_info']['stake_abbr'];

$db = new database();
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
}
if($logged_user=='BDO')
{
	
	$upd = $db->update("
						UPDATE prd_employee_arrear arr SET status_flag=2
	FROM prd_employee_master emp WHERE arr.emp_id_fk=emp.emp_id_pk AND arr.gp_id_fk=emp.gp_id_fk AND arr.status_flag=1 AND arr.delete_status=1 AND arr.is_saved=1 AND arr.salary_monthyear is null AND arr.block_code='".$_SESSION['user_info']['stake_user']."' AND arr.ropa_status='1'
						");
}
else if($logged_user=='EO')
{
	
	$upd = $db->update("
						UPDATE prd_employee_arrear arr SET status_flag=2
	FROM prd_employee_master emp WHERE arr.emp_id_fk=emp.emp_id_pk AND arr.ps_id_fk=emp.ps_id_fk AND arr.status_flag=1 AND arr.delete_status=1 AND arr.is_saved=1 AND arr.salary_monthyear is null AND arr.ps_id_fk='".$_SESSION['location']['ps_id']."' AND arr.ropa_status='1'
						");
}
else if($logged_user=='FC&CAO')
{
	
	$upd = $db->update("
						UPDATE prd_employee_arrear arr SET status_flag=2
	FROM prd_employee_master emp WHERE arr.emp_id_fk=emp.emp_id_pk AND arr.zp_id_fk=emp.zp_id_fk AND arr.status_flag=1 AND arr.delete_status=1 AND arr.is_saved=1 AND arr.salary_monthyear is null AND arr.zp_id_fk='".$_SESSION['location']['district_id']."' AND arr.zp_emp_type='".$emp_type."' AND arr.ropa_status='1'
						");
}


			if($upd && $emp_type!='5')
			{
			
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Arrear has been finalized successfully.</strong></div>';
			header('Location:'.$config['base_url'].'page/all_moduls/arrear/employee_lists_ropa_2019.php?emp_type='.$type);
			exit(0);
			//}
			
			
			} 
			else if($upd && ($emp_type=='5' || $emp_type==''))	
			{
			
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Arrear has been finalized successfully.</strong></div>';
			header('Location:'.$config['base_url'].'page/all_moduls/arrear/employee_lists_ropa_2019.php?emp_type='.$type);
			exit(0);
			
			}
			
			
			else 
			{
			//echo "in else";
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Arrear Finalization has been Failed</strong></div>';
			header('Location:'.$config['base_url'].'page/all_moduls/arrear/employee_lists_ropa_2019.php?emp_type='.$type);
			exit(0);
			} ?>
