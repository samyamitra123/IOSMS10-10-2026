<?
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';     
//require '../../page_visite.php';


error_reporting(0);

/*$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);*/
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

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


$db = new database();

$year=$crypto->decode($_GET['ye'],4);
$month=$crypto->decode($_GET['mo'],4);
$yemo=$year.$month;
$bill_serial_no=$_GET['bill_serial_no'];

$find_bill = $db->fetch_table("
		SELECT * FROM prd_block_bill_details
		WHERE zp_id_fk='".$_SESSION['location']['district_id']."'
		AND salary_monthyear ='".$yemo."'
		AND bill_serial_no='".$bill_serial_no."'
");

/*if($find_bill[0]['bill_entry_time']!='')
{
	$bill_entry_time=date('m-d-Y',strtotime($find_bill[0]['bill_entry_time']));	
} else {
	$bill_entry_time=$find_bill[0]['bill_entry_time'];	
}*/

/*echo "SELECT * FROM ehrms_dpsc_bill_details
		WHERE dpsc_code = '".$_SESSION['user_info']['stake_user']."'
		AND salary_monthyear ='".$moye."'";exit;*/

// echo "<pre>";
// print_r($salsource);
// echo "<pre>";
//print_r($_GET);

echo json_encode(array('bill'=>$find_bill[0]['bill_no'],'bill_date'=>date_frmt_change($find_bill[0]['bill_entry_time'])));

?>
<?php @pg_close($con); ?>