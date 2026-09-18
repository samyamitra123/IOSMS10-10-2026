<?php
session_start();
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



//var_dump($_SESSION['user_info']['stake_abbr']); die;


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

//$emp_dob = preg_match($employee_dtls[0]['emp_dob']); 
$emp_first_join_date = dateshow($employee_dtls[0]['emp_first_join_date']);

$gender = fun_code($employee_dtls[0]['emp_sex']);
$emp_religion = fun_code($employee_dtls[0]['emp_religion']);
$maritalStatus = fun_code($employee_dtls[0]['emp_marital_status']);

if($employee_dtls[0]['emp_marital_status']=='241')
{
	$spouse_name=$employee_dtls[0]['emp_spouse_name'];
	$spouse_status='Y';
}
else
{
	$spouse_name=NULL;
	$spouse_status='N';
}
$emp_termination_date = dateshow($employee_dtls[0]['emp_termination_date']);

if($_SESSION['user_info']['stake_abbr'] == 'BDO'){ $party_code = '006'; }
else if($_SESSION['user_info']['stake_abbr'] == 'PS'){ $party_code = '007'; }
else if($_SESSION['user_info']['stake_abbr'] == 'ZP'){ $party_code = '008'; }

//var_dump($emp_first_name); die;












$key3="basicDtls";
$basicDtls = array("empNm"=>$emp_name, "empId"=>$emp_id_const, "gender"=>$gender,"religion"=>$emp_religion,"dob"=>$emp_dob,
"doj"=>$emp_first_join_date,
"isActive"=>'Y',"retDt"=>$emp_termination_date,"gpfCpf"=>43);

$basicDtls_array= array( $key3=> $basicDtls);




$key4="otherDtls";
$otherDtls = array("empType"=>"EMRE", "mobile"=>$employee_dtls[0]['emp_mobile_no'], "email"=>$employee_dtls[0]['emp_mail_id'],"aadhaar"=>$employee_dtls[0]['emp_aadhar_no'],"pan"=>$employee_dtls[0]['emp_pan_no'],
"maritalStatus"=>$maritalStatus,
"oldGpfAccNo"=>null,"cpfRefDt"=>NULL,"cpfRefTresNm"=>NULL,"cpfRefAmt"=>NULL,"partyCode"=>$party_code);
$otherDtls_array= array( $key4=> $otherDtls);




$key5="relationDtls";
$relationDtls = array("fatherNm"=>$employee_dtls[0]['emp_father_name'], "motherNm"=>$employee_dtls[0]['emp_mother_name']);
 $relationDtls_array= array( $key5=> $relationDtls);



$key11="spouseDtls";
$spouseDtls = array("spouseNm"=>$spouse_name, "spouseNmIsActive"=>$spouse_status, "spouseNmWef"=>NULL);
 $spouseDtls_array= array( $key11=> $spouseDtls);




$key6="addrDtls";
$addrDtls = array("presStreet"=>35, "presCity"=>37, "presDist"=>43,"presState"=>43,"presPin"=>43,
"permStreet"=>43,
"permCity"=>43,"permDist"=>43,"permState"=>43,"permPin"=>43);
$addrDtls_array= array( $key6=> $addrDtls);

 
 
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




$all_array= array( $key3=> $basicDtls, $key4=> $otherDtls, $key5=> $relationDtls, $key11=>$spouseDtls, $key6=> $addrDtls, $key7=> $exitSerDtls, $key8=> $payInfoDtls, $key9=> $payAllowDtls, $key10=> $benfDtls);
 echo $all_array_jeson=json_encode($all_array);

die;
 
 
 

?>