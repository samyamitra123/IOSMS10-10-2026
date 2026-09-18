<?php 
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
include( '../../layout/ifms_functions.php');
$crypto = new cryptography();

$db = new database();

$Query = "SELECT block_id_pk FROM prd_location_master_block WHERE ngipf_status = 1";
$blockData = $db->fetch_table($Query);
$blockIds = array();

foreach($blockData as $block)
{
	  $blockIds[] = $block['block_id_pk'];
	  //echo '<p>'.$ps['ps_name'].' PS-CODE: '.$ps['ps_code'].' | PS IOSMS ID:'.$ps['ps_id_pk'].'</p>';
}
$Query = "SELECT gp_id_pk FROM prd_location_master_gp WHERE block_id_fk IN (".implode(',',$blockIds).")";
$gpData = $db->fetch_table($Query);

$gpIds = array();

foreach($gpData as $gp)
{
	  $gpIds[] = $gp['gp_id_pk'];
	  //echo '<p>'.$ps['ps_name'].' PS-CODE: '.$ps['ps_code'].' | PS IOSMS ID:'.$ps['ps_id_pk'].'</p>';
}
//print_r($gpIds); exit;

$Query = "SELECT emp_id_pk,emp_id_const from prd_employee_master
        WHERE gp_id_fk IN (".implode(',',$gpIds).") AND emp_status = 1 AND emp_cosolidated_pay=0 AND ropa_status =1";
$gpEmployeeData = $db->fetch_table($Query);

//print_r($gpEmployeeData); exit;

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
foreach($gpEmployeeData as $empData){
   if(in_array($empData['emp_id_const'],$empIds))
   	 {
   	 	$Query = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const,send_status,pritype) VALUES (".$empData['emp_id_pk'].", '".$empData['emp_id_const']."', 1,'gp')";
   	 	$db->update($Query);
   	 }
   else
   	 {
   	 	$Query = "INSERT INTO prd_ngipf_request_cron (emp_id_fk,emp_id_const,pritype) VALUES (".$empData['emp_id_pk'].", '".$empData['emp_id_const']."','gp')"; 
   	 	$db->update($Query); 	 	
   	 }
   	//print($Query); exit;
}
//$Query = implode('; ',$Query);

print_r("Done"); exit;
?>