<?php 
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
include( '../../layout/ifms_functions.php');
$crypto = new cryptography();

$db = new database();

$Query = "SELECT ps_id_pk FROM prd_location_master_panchayat_samiti WHERE ngipf_status = 1";
$psData = $db->fetch_table($Query);
$psIds = array();

foreach($psData as $ps)
{
	  $psIds[] = $ps['ps_id_pk'];
	  //echo '<p>'.$ps['ps_name'].' PS-CODE: '.$ps['ps_code'].' | PS IOSMS ID:'.$ps['ps_id_pk'].'</p>';
}

$Query = "SELECT emp_id_pk,emp_id_const from prd_employee_master
        WHERE ps_id_fk IN (".implode(',',$psIds).") AND emp_status = 1";
$psEmployeeData = $db->fetch_table($Query);

$Query = "SELECT emp_id_const from prd_gpf_request_master WHERE emp_id_const != '0'";
$ngipfAlredySent = $db->fetch_table($Query);

$empIds = array();

foreach($ngipfAlredySent as $ngipf)
{
	  $empIds[] = $ngipf['emp_id_const'];
	  //echo '<p>'.$ps['ps_name'].' PS-CODE: '.$ps['ps_code'].' | PS IOSMS ID:'.$ps['ps_id_pk'].'</p>';
}
//print_r($empIds); 
$Query = '';
foreach($psEmployeeData as $empData){
   if(in_array($empData['emp_id_const'],$empIds))
   	 {
   	 	$Query = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const,send_status) VALUES (".$empData['emp_id_pk'].", '".$empData['emp_id_const']."', 1)";
   	 	$db->update($Query);
   	 }
   else
   	 {
   	 	$Query = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const) VALUES (".$empData['emp_id_pk'].", '".$empData['emp_id_const']."')"; 
   	 	$db->update($Query); 	 	
   	 }
}
//$Query = implode('; ',$Query);

print_r("Done"); exit;
?>