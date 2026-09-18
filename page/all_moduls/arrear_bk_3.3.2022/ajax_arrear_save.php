<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';

require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';


$logged_user=$_SESSION['user_info']['stake_abbr'];

$f=0;

$crypto = new cryptography();
$db = new database();	

 $emp_id_pk=$crypto->decode($_GET['emp_id'],4);
 $gpcd = $crypto->decode($_GET['gp_id'],4); 


if($logged_user=='BDO')
{
	$arr = $db->fetch_table("SELECT net FROM prd_employee_arrear WHERE status_flag=1 AND delete_status=1  AND gp_id_fk='".$gpcd."' AND emp_id_fk='".$emp_id_pk."' 
	and ropa_status='0'");
	
	if($arr[0]['net'])
	{
		$upd = $db->update("UPDATE prd_employee_arrear SET is_saved=1  WHERE gp_id_fk='".$gpcd."' AND delete_status=1 AND status_flag=1 AND emp_id_fk='".$emp_id_pk."'
		and ropa_status='0' ");
		if($upd)
		{	
			$f=2;				
		}
		else
		{
			$f=3;
		}
	}
		
	
	
	$emp_name_fetch=$db->fetch_table("SELECT emp_first_name,emp_second_name,emp_last_name FROM prd_employee_master WHERE gp_id_fk='".$gpcd."' AND emp_id_pk='".$emp_id_pk."'");
	
	$emp_full_name=$emp_name_fetch[0]['emp_first_name']." ".$emp_name_fetch[0]['emp_second_name']." ".$emp_name_fetch[0]['emp_last_name'];
	$save_count=$db->fetch_table("
									SELECT 
									COUNT(CASE WHEN status_flag=1 THEN emp_id_fk END) AS total_arrear,
									COUNT(CASE WHEN status_flag=1 AND is_saved='1' THEN emp_id_fk END) AS total_save_arrear
									FROM prd_employee_arrear WHERE block_code='".$_SESSION['user_info']['stake_user']."' AND delete_status=1 AND salary_monthyear is null
									and ropa_status='0'
								");

}
else if($logged_user=='EO')
{
	$arr = $db->fetch_table("SELECT net FROM prd_employee_arrear WHERE status_flag=1 AND delete_status=1  AND ps_id_fk='".$_SESSION['location']['ps_id']."' AND emp_id_fk='".$emp_id_pk."' and ropa_status='0' ");
	
	if($arr[0]['net'])
	{
		$upd = $db->update("UPDATE prd_employee_arrear SET is_saved=1  WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' AND delete_status=1 AND status_flag=1 AND emp_id_fk='".$emp_id_pk."' and ropa_status='0'");
		if($upd)
		{	
			$f=2;				
		}
		else
		{
			$f=3;
		}
	}
		
	
	
	$emp_name_fetch=$db->fetch_table("SELECT emp_first_name,emp_second_name,emp_last_name FROM prd_employee_master WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' AND emp_id_pk='".$emp_id_pk."'");
	
	$emp_full_name=$emp_name_fetch[0]['emp_first_name']." ".$emp_name_fetch[0]['emp_second_name']." ".$emp_name_fetch[0]['emp_last_name'];
	$save_count=$db->fetch_table("
									SELECT 
									COUNT(CASE WHEN status_flag=1 THEN emp_id_fk END) AS total_arrear,
									COUNT(CASE WHEN status_flag=1 AND is_saved='1' THEN emp_id_fk END) AS total_save_arrear
									FROM prd_employee_arrear WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' AND delete_status=1 AND salary_monthyear is null
									and ropa_status='0'
								");

}

else if($logged_user=='FC&CAO')
{
	$arr = $db->fetch_table("SELECT net FROM prd_employee_arrear WHERE status_flag=1 AND delete_status=1  AND zp_id_fk='".$_SESSION['location']['district_id']."' AND emp_id_fk='".$emp_id_pk."' and ropa_status='0' ");
	
	if($arr[0]['net'])
	{
		$upd = $db->update("UPDATE prd_employee_arrear SET is_saved=1  WHERE zp_id_fk='".$_SESSION['location']['district_id']."' AND delete_status=1 AND status_flag=1 AND emp_id_fk='".$emp_id_pk."' and ropa_status='0' ");
		if($upd)
		{	
			$f=2;				
		}
		else
		{
			$f=3;
		}
	}
		
	
	
	$emp_name_fetch=$db->fetch_table("SELECT emp_first_name,emp_second_name,emp_last_name FROM prd_employee_master WHERE zp_id_fk='".$_SESSION['location']['district_id']."' AND emp_id_pk='".$emp_id_pk."'");
	
	$emp_full_name=$emp_name_fetch[0]['emp_first_name']." ".$emp_name_fetch[0]['emp_second_name']." ".$emp_name_fetch[0]['emp_last_name'];
	$save_count=$db->fetch_table("
									SELECT 
									COUNT(CASE WHEN status_flag=1 THEN emp_id_fk END) AS total_arrear,
									COUNT(CASE WHEN status_flag=1 AND is_saved='1' THEN emp_id_fk END) AS total_save_arrear
									FROM prd_employee_arrear WHERE zp_id_fk='".$_SESSION['location']['district_id']."' AND delete_status=1 AND salary_monthyear is null
									and ropa_status='0'
								");

}






	echo json_encode(array($f,$save_count[0]['total_arrear'],$save_count[0]['total_save_arrear'],$emp_full_name));

 
?>