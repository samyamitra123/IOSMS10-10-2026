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



//var_dump($_SESSION['location']); die;


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
$employee_dtls = $db->fetch_table("select * from prd_employee_master WHERE emp_id_pk ='".$emp_id_pk."' AND emp_status IN(1,9) ORDER BY emp_id_pk ASC");

//var_dump($employee_dtls); die;

if($employee_dtls[0]['gp_id_fk'] !='0'){
	
	$ddo_dtls = $db->fetch_table("select * from prd_dise_admin WHERE block_code ='".$_SESSION['location']['block_code']."' ");
	$ddoIsActive = 'Y';
	$opCodeLf =" ";
}
else if($employee_dtls[0]['ps_id_fk'] !='0'){ 

	$ddo_dtls = $db->fetch_table("select * from psemp_ps_profile WHERE ps_id_fk ='".$_SESSION['location']['ps_id_fk']."' ");
	$ddoIsActive = 'N';
	$opCodeLf =$ddo_dtls[0]['ddo_code'];
}

$curr_date = date("d/m/Y");

$emp_name = $employee_dtls[0]['emp_first_name'].' '.$employee_dtls[0]['emp_second_name'].' '.$employee_dtls[0]['emp_last_name'];
$emp_id_const = $employee_dtls[0]['emp_id_const'];
$emp_dob = dateshow($employee_dtls[0]['emp_dob']); 

//$emp_dob = preg_match($employee_dtls[0]['emp_dob']); 
$emp_first_join_date = dateshow($employee_dtls[0]['emp_first_join_date']);
$emp_join_prsnt_post_date = dateshow($employee_dtls[0]['emp_join_prsnt_post_date']);
$emp_present_memo_date = dateshow($employee_dtls[0]['emp_present_memo_date']);


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


if($employee_dtls[0]['emp_status'] == 1){ $emp_status ="EME";}
else if($employee_dtls[0]['emp_status'] == 9){ $emp_status ="EMS";}


if($employee_dtls[0]['ropa_status'] == 0){ $ropa_status ="WGSROPA09";}
else if($employee_dtls[0]['ropa_status'] == 1){ $ropa_status ="WGSROPA19";}







$key3="basicDtls";
$basicDtls = array("empNm"=>$emp_name, "empId"=>$emp_id_const, "gender"=>$gender,"religion"=>$emp_religion,"dob"=>$emp_dob,
"doj"=>$emp_first_join_date,
"isActive"=>'Y',"retDt"=>$emp_termination_date,"gpfCpf"=>43);

$basicDtls_array= array( $key3=> $basicDtls);




$key4="otherDtls";
$otherDtls = array("empType"=>"EMRE", "mobile"=>$employee_dtls[0]['emp_mobile_no'], "email"=>$employee_dtls[0]['emp_mail_id'],"aadhaar"=>$employee_dtls[0]['emp_aadhar_no'],"pan"=>$employee_dtls[0]['emp_pan_no'],
"maritalStatus"=>$maritalStatus,
"oldGpfAccNo"=>" ","cpfRefDt"=>$curr_date,"cpfRefTresNm"=>" ","cpfRefAmt"=>" ","partyCode"=>$party_code);
$otherDtls_array= array( $key4=> $otherDtls);




$key5="relationDtls";
$relationDtls = array("fatherNm"=>$employee_dtls[0]['emp_father_name'], "motherNm"=>$employee_dtls[0]['emp_mother_name']);
 $relationDtls_array= array( $key5=> $relationDtls);



$key11="spouseDtls";
$spouseDtls = array("spouseNm"=>$spouse_name, "spouseNmIsActive"=>$spouse_status, "spouseNmWef"=>" ");
 $spouseDtls_array= array( $key11=> $spouseDtls);




$key6="addrDtls";
$addrDtls = array("presStreet"=>$employee_dtls[0]['emp_pre_street_no'], "presCity"=>" ", "presDist"=>$employee_dtls[0]['emp_pre_dist'],"presState"=>$employee_dtls[0]['pre_state'],"presPin"=>$employee_dtls[0]['emp_pre_pin'],
"permStreet"=>$employee_dtls[0]['emp_per_street_no'],
"permCity"=>" ","permDist"=>$employee_dtls[0]['emp_per_dist'],"permState"=>$employee_dtls[0]['per_state'],"permPin"=>$employee_dtls[0]['emp_per_pin']);
$addrDtls_array= array( $key6=> $addrDtls);

 
 
 
$key12="workDtls";
$workDtls = array("ddoCode"=>$ddo_dtls[0]['ddo_code'], "ddoWef"=>$curr_date, "ddoIsActive"=>$ddoIsActive,"opCodeLf"=>$opCodeLf,"opCodeLfWef"=>$curr_date,"opCodeLfIsActive"=>"Y", "opCodeLfTresCode"=>" ","opCodePf"=>$ddo_dtls[0]['operator_code_pf'],"opCodePfWef"=>$curr_date,"opCodePfIsActive"=>"Y", "opCodePfTresCode"=>$ddo_dtls[0]['treasury_block_code'], "hooCode"=>$ddo_dtls[0]['hoo_code'], "hooCodeWef"=>$curr_date, "hooCodeIsActive"=>"Y", "sancAuthCode"=>" ", "sancAuthWef"=>$curr_date, "sancAuthIsActive"=>"N", "recAuthCode"=>" ", "recAuthCodeWef"=>$curr_date, "recAuthCodeIsActive"=>"N", "sectionCode"=>" ", "sectionCodeWef"=>$curr_date, "sectionCodeIsActive"=>"N", "desig"=>$employee_dtls[0]['emp_desig'], "desigWef"=>$emp_join_prsnt_post_date, "desigIsActive"=>"Y", "apptAppNo"=>$employee_dtls[0]['emp_present_memo_no'], "apptAppDt"=>$emp_present_memo_date, "apptWefDt"=>$emp_join_prsnt_post_date, "apptAppIsActive"=>"Y");

$workDtls_array= array( $key12=> $workDtls);



 
$key7="exitSerDtls";
$exitSerDtls = array("terType"=>$emp_status, "terDt"=>$emp_termination_date);
$exitSerDtls_array= array( $key7=> $exitSerDtls);

 
 
 $key8="payInfoDtls";
$payInfoDtls = array("ropa"=>$ropa_status, "ropaWef"=>$curr_date);
$payInfoDtls_array= array( $key8=> $payInfoDtls);

 
 
 $key9="payAllowDtls";
$payAllowDtls = array("basicPay"=>$employee_dtls[0]['emp_pay_in_payband'], "basicPayWef"=>$curr_date, "gradePay"=>" ", "gradePayWef"=>$curr_date);
$payAllowDtls_array= array( $key9=> $payAllowDtls);



$key10="benfDtls";
$benfDtls = array("ifsc"=>$employee_dtls[0]['emp_ifsc_no'], "accNo"=>$employee_dtls[0]['emp_acc_no']);
$benfDtls_array= array( $key10=> $benfDtls);




$key20="empDtls";
$empDtls = array($key3=> $basicDtls, $key4=> $otherDtls, $key5=> $relationDtls, $key11=>$spouseDtls, $key6=> $addrDtls, $key12=> $workDtls, $key7=> $exitSerDtls, $key8=> $payInfoDtls, $key9=> $payAllowDtls, $key10=> $benfDtls);
$empDtls_array= array( $key20=> $empDtls);



$key30="req";
$req = array($key20=> $empDtls);
$req_array= array($key30=> $req);


//$all_array= array($key3=> $basicDtls, $key4=> $otherDtls, $key5=> $relationDtls, $key11=>$spouseDtls, $key6=> $addrDtls, $key12=> $workDtls, $key7=> $exitSerDtls, $key8=> $payInfoDtls, $key9=> $payAllowDtls, $key10=> $benfDtls);

$all_array= array($key30=> $req);
 echo $all_array_json= json_encode($all_array);

die;
 
 
 

?>