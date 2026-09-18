<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

set_time_limit(0);
session_start();


header("Access-Control-Allow-Headers: Origin");
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
include( '../../layout/ifms_functions.php');
$crypto = new cryptography();






function dateshow($dateval)
{	
	$date=substr($dateval,0,10);
	//return $date;
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'/'.$datearr['1'].'/'.$datearr['0'];
	return $dob=='--'?'':$dob;
}

function fun_code($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT * FROM prd_dise_code_master where code='".$val."';");
		return $dist_data2[0]['gpf_code_master'];
	}


function fun_dist($val){
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT district_code, district_name FROM prd_location_master_district where district_id_pk='".$val."';");
		return $dist_data2[0]['district_name'];
	}



$emp_id_pk = $crypto->decode($_REQUEST['emp_id_pk'],4);

//var_dump($emp_id_pk); die;

$db = new database();
$employee_dtls = $db->fetch_table("select * from prd_employee_master WHERE emp_id_pk ='".$emp_id_pk."' ORDER BY emp_id_pk ASC");

//var_dump($employee_dtls); die;


$emp_name = $employee_dtls[0]['emp_first_name'].' '.$employee_dtls[0]['emp_second_name'].' '.$employee_dtls[0]['emp_last_name'];
$emp_id_const = $employee_dtls[0]['emp_id_const'];
$emp_dob = dateshow($employee_dtls[0]['emp_dob']); 
$emp_first_join_date = dateshow($employee_dtls[0]['emp_first_join_date']);

$gender = fun_code($employee_dtls[0]['emp_sex']);
$emp_religion = fun_code($employee_dtls[0]['emp_religion']);


//var_dump($emp_first_name); die;


$key3="basicDtls";
$basicDtls = array("empNm"=>$emp_name, "empId"=>$emp_id_const, "gender"=>$gender,"religion"=>$emp_religion,"dob"=>$emp_dob,
"doj"=>$emp_first_join_date,
"isActive"=>'Y',"salBillPrep"=>43,"retDt"=>43,"gpfCpf"=>43);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$basicDtls_array= array( $key3=> $basicDtls);
//echo $basicDtls_array_jeson=json_encode($basicDtls_array);
//$key1 => $basicDtls



//die;


$key4="otherDtls";
$otherDtls = array("empType"=>35, "mobile"=>37, "email"=>43,"aadhaar"=>43,"pan"=>43,
"maritalStatus"=>43,
"oldGpfAccNo"=>43,"cpfRefDt"=>43,"cpfRefTresNm"=>43,"cpfRefAmt"=>43,"partyCode"=>43);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$otherDtls_array= array( $key4=> $otherDtls);
 //echo $otherDtls_array_jeson=json_encode($otherDtls_array);
//$key1 => $basicDtls



$key5="relationDtls";
$relationDtls = array("fatherNm"=>35, "motherNm"=>37, "spouseNm"=>43);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
 $relationDtls_array= array( $key5=> $relationDtls);
//echo $relationDtls_array_jeson=json_encode($relationDtls_array);
//$key1 => $basicDtls


$key6="addrDtls";
$addrDtls = array("presStreet"=>35, "presCity"=>37, "presDist"=>43,"presState"=>43,"presPin"=>43,
"permStreet"=>43,
"permCity"=>43,"permDist"=>43,"permState"=>43,"permPin"=>43);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$addrDtls_array= array( $key6=> $addrDtls);
 //echo $addrDtls_array_jeson=json_encode($addrDtls_array);
 
 
$key7="exitSerDtls";
$exitSerDtls = array("terType"=>35, "terDt"=>37);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$exitSerDtls_array= array( $key7=> $exitSerDtls);
 //echo $exitSerDtls_array_jeson=json_encode($exitSerDtls_array);
 
 
 $key8="payInfoDtls";
$payInfoDtls = array("ropa"=>35, "ropaWef"=>37);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$payInfoDtls_array= array( $key8=> $payInfoDtls);
 //echo $payInfoDtls_array_jeson=json_encode($payInfoDtls_array);
 
 
 $key9="payAllowDtls";
$payAllowDtls = array("basicPay"=>35, "basicPayWef"=>37, "gradePay"=>37, "gradePayWef"=>37);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$payAllowDtls_array= array( $key9=> $payAllowDtls);
 //echo $payAllowDtls_array_jeson=json_encode($payAllowDtls_array);


$key10="benfDtls";
$benfDtls = array("ifsc"=>35, "accNo"=>37);
//$a=json_encode($basicDtls);
//$a=$basicDtls;
$benfDtls_array= array( $key10=> $benfDtls);
 //echo $benfDtls_array_jeson=json_encode($benfDtls_array);




$all_array= array( $key3=> $basicDtls, $key4=> $otherDtls, $key5=> $relationDtls, $key6=> $addrDtls, $key7=> $exitSerDtls, $key8=> $payInfoDtls, $key9=> $payAllowDtls, $key10=> $benfDtls);
 echo $all_array_jeson=json_encode($all_array);

die;
 
 
 

?>