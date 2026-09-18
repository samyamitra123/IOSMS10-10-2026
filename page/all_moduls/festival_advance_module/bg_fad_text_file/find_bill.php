<?php

//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
//error_reporting(0);
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';

$crypto = new cryptography();

//require 'includes/library/session.class.php';

//Functions --------------------------------------------------------------------------------------------------------------------------------------------
function date_frmt_change($original_date){
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date ==NULL || $original_date == "") {
		return "--";
	}
	else{
		return $newDate = date("d-m-Y", strtotime($original_date));
	}
}

$moye = trim($crypto->decode($_GET['ye'], 4)) . trim($crypto->decode($_GET['mo'], 4));
//$salso = trim($crypto->decode($_GET['ss'], 4));
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
	
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];


$year=$crypto->decode($_GET['ye'],4);
$month=$crypto->decode($_GET['mo'],4);
$yemo=$year.$month;
$find_bill = $db->fetch_table("
		SELECT * FROM prd_block_bill_details
		WHERE ps_id_fk ='".$_SESSION['location']['ps_id']."'
		AND salary_monthyear ='".$yemo."' AND requisition_type='".$requisition_type."'  AND status='1'
	
");

if($find_bill[0]['bill_entry_time']!='')
{
	$bill_entry_time=date('m-d-Y',strtotime($find_bill[0]['bill_entry_time']));	
} else {
	$bill_entry_time=$find_bill[0]['bill_entry_time'];	
}

/*echo "SELECT * FROM ehrms_dpsc_bill_details
		WHERE dpsc_code = '".$_SESSION['user_info']['stake_user']."'
		AND salary_monthyear ='".$moye."'";exit;*/

// echo "<pre>";
// print_r($salsource);
// echo "<pre>";
//print_r($_GET);

echo json_encode(array('bill'=>$find_bill[0]['bill_no'],'bill_date'=>$bill_entry_time));

?>