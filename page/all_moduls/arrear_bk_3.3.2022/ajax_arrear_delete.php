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

$arrear_id= $crypto->decode($_POST['arrear_id'],4); 

/*if($logged_user=='BDO')
{
*/	$emp_check = $db->fetch_table("SELECT delete_status FROM prd_employee_arrear WHERE arrear_id_pk='".$arrear_id."'");
	
	if($emp_check[0]['delete_status']!="")
	{
		$upd_delete_status = $db->update("UPDATE prd_employee_arrear SET delete_status=0  WHERE arrear_id_pk='".$arrear_id."' AND delete_status=1");
	}
		
	if($upd_delete_status)
	{
		echo '1';
	}
	else
	{
		echo '0';
	}
/*}
else if($logged_user=='EO')
{
	$emp_check = $db->fetch_table("SELECT delete_status FROM prd_employee_arrear WHERE arrear_id_pk='".$arrear_id."'");
	
	if($emp_check[0]['delete_status']!="")
	{
		$upd_delete_status = $db->update("UPDATE prd_employee_arrear SET delete_status=0  WHERE arrear_id_pk='".$arrear_id."' AND delete_status=1");
	}
		
	if($upd_delete_status)
	{
		echo '1';
	}
	else
	{
		echo '0';
	}
}*/


 
?>