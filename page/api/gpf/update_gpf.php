<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
set_time_limit(0);
//ini_set('display_errors',1);
header("Access-Control-Allow-Headers: Origin");
require '../../../includes/config/config.php';
require '../../../includes/config/database.config_api.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
include( '../../layout/ifms_functions.php');
$db = new database();
include( 'ngipfFunction.php');
$crypto = new cryptography();
// Data Initialization Sector....
$emp_id_pk = $crypto->decode($_REQUEST['emp_id_pk'],4);
$stake_abbr = $_SESSION['user_info']['stake_abbr'];
$block_code = $_SESSION['location']['block_code'];
$ps_id = $_SESSION['location']['ps_id'];

//var_dump($emp_id_pk); die;


$employee_dtls = $db->fetch_table("select * from 
	                                 prd_employee_master_ngipf as pem
	                                 LEFT JOIN prd_stake_epension_employee_profile as pseep ON pem.emp_id_pk=pseep.emp_id_fk
	                                 WHERE emp_id_pk ='".$emp_id_pk."' 
	                                 ORDER BY emp_id_pk ASC");
$employee_dtls = $employee_dtls[0];
$emp_id_const = $employee_dtls['emp_id_const'];

// Check If Employee Already In NGIPF..
$Query = "SELECT * from prd_gpf_request_master WHERE emp_id_const='".$emp_id_const."' AND pfaccno!=''";
$prevEmployeeDetails = $db->fetch_table($Query);
$status="I";
if(count($prevEmployeeDetails) > 0)
  {
      $status="M";
      $pfaccno = $prevEmployeeDetails[0]['pfaccno'];
      $prevEmployeeDetails = $prevEmployeeDetails[0]['pnrd_request'];
      //print_r(json_decode($prevEmployeeDetails)); exit;
      
  }
 
//print_r($status); exit;

//$emp_id_const=$employee_dtls['emp_id_const'];

if($stake_abbr =='BDO'){
	
	$ddo_dtls = $db->fetch_table("select * from prd_dise_admin WHERE block_code ='".$block_code."' ");
	$ddoIsActive = 'Y';
	$opCodeLf =" ";
}
else if($stake_abbr =='EO'){ 

	$ddo_dtls = $db->fetch_table("select * from psemp_ps_profile WHERE ps_id_fk ='".$ps_id."' ");
	$ddoIsActive = 'N';
	$opCodeLf =$ddo_dtls[0]['ddo_code'];
	$ddo_dtls[0]['ddo_code'] = '';
}
//print_r($_SESSION); exit;
//print_r($employee_dtls); exit;

$curr_date = date("d/m/Y");

$emp_name = $employee_dtls['emp_first_name'].' '.$employee_dtls['emp_second_name'].' '.$employee_dtls['emp_last_name'];
$emp_id_const = $employee_dtls['emp_id_const'];

$s_falge=$employee_dtls['gpf_sent_status'];


$emp_dob = dateshow($employee_dtls['emp_dob']); 

//$emp_dob = preg_match($employee_dtls['emp_dob']); 
$emp_first_join_date = dateshow($employee_dtls['emp_first_join_date']);
$emp_join_prsnt_post_date = dateshow($employee_dtls['emp_join_prsnt_post_date']);
$emp_present_memo_date = ($employee_dtls['emp_present_memo_date'] != '')?dateshow($employee_dtls['emp_present_memo_date']):dateshow($employee_dtls['presnt_memo_wef_date']);
$emp_conf_join_date = dateshow($employee_dtls['emp_conf_join_date']);


$gender = fun_code($employee_dtls['emp_sex']);
$desig = fun_code($employee_dtls['emp_desig']);
$emp_religion = fun_code($employee_dtls['emp_religion']);
$maritalStatus = fun_code($employee_dtls['emp_marital_status']);
$district = fun_dist($employee_dtls['emp_pre_dist']);
 //print_r($district); exit;

if($employee_dtls['emp_marital_status']=='241')
{
	$spouse_name=$employee_dtls['emp_spouse_name'];
	$spouse_status='Y';
}
else
{
	$spouse_name=NULL;
	$spouse_status='N';
}
$emp_termination_date = dateshow($employee_dtls['emp_termination_date']);

if($stake_abbr == 'BDO'){ $party_code = '006'; }
else if($stake_abbr == 'PS' || $stake_abbr == 'EO'){ $party_code = '007'; }
else if($stake_abbr == 'ZP'){ $party_code = '008'; }

//print_r($employee_dtls); exit;
//var_dump($emp_first_name); die;





if($employee_dtls['ropa_status'] == 0){ $ropa_status ="WGSROPA09";}
else if($employee_dtls['ropa_status'] == 1){ $ropa_status ="WGSROPA19";}







$key3="basicDtls";
$basicDtls = array("empNm"=>$emp_name, "empId"=>$emp_id_const, "gender"=>$gender,"religion"=>$emp_religion,"dob"=>$emp_dob,
"doj"=>$emp_first_join_date,
"isActive"=>'Y',"retDt"=>$emp_termination_date,"gpfCpf"=>'GPF');

$basicDtls_array= array( $key3=> $basicDtls);

if($employee_dtls['emp_status'] != 1)
	{ 
		$Query = "SELECT reason from prd_stop_sal_reason WHERE emp_id_fk=".$employee_dtls['emp_id_pk'];
		$TerminationReason = $db->fetch_table($Query);
      $TerminationReason = $TerminationReason[0]['reason'];
      switch($TerminationReason)
        {
        	 case '1992':
        	  $emp_status ="EMRS";
        	 break;
        	 case '1993':
        	  $emp_status ="EMD";
        	 break;
        	 case '1991':
        	  $emp_status ="EMD";
        	 break;
        }
	}
else
	{
		$emp_status ="EME";
	}


$key4="otherDtls";


$otherDtls = array(
	                   "empType"=>$emp_status, 
	                   "mobile"=>$employee_dtls['emp_mobile_no'], 
	                   "email"=>$employee_dtls['emp_mail_id'],
	                   "aadhaar"=>$employee_dtls['emp_aadhar_no'],
	                   "pan"=>$employee_dtls['emp_pan_no'],
	                   "maritalStatus"=>$maritalStatus,
	                   "maritalStatusIsActive"=>$spouse_status,
	                   "maritalStatusWef"=>$curr_date,
	                   "oldGpfAccNo"=>"",
	                   // Added for Modify Purpose 
	                   "cpfRefDt"=>"",
	                   "cpfRefTresNm"=>"",
	                   "cpfRefAmt"=>"",
	                   "cpfRefIsActive"=>'N',
	                   "cpfRefWef"=>"",
	                   "partyCode"=>$party_code,
	                );
$otherDtls_array= array( $key4=> $otherDtls);




$key5="relationDtls";
$relationDtls = array(
	                     "fatherNm"=>$employee_dtls['emp_father_name'], 
	                     "motherNm"=>$employee_dtls['emp_mother_name']
	                   );
 $relationDtls_array= array( $key5=> $relationDtls);



 $spouseDtls = array();
 $key11="spouseDtls";
 $spouseDtls = array(
 	                      "spouseNm"=>$spouse_name, 
 	                      "spouseNmIsActive"=>$spouse_status, 
 	                      "spouseNmWef"=>$curr_date
 	                    );
// $spouseDtls[] = array("spouseNm"=>$spouse_name.'Rai', "spouseNmIsActive"=>$spouse_status, "spouseNmWef"=>" ");
 $spouseDtls_array= array($key11=> $spouseDtls);

//This is for Subikar Testing Start
//$spouse_status = 'N';
 //$employee_dtls['emp_pre_vill'] = 'Goa';
// $employee_dtls['emp_pre_vill'] = 'Goa';
//This is for Subikar Testing end

// State = 10 As per GPF Instruction Do not change..
$key6="addrDtls";
$addrDtls = array(
	                  "presStreet"=>($employee_dtls['emp_pre_street_no']!='')?$employee_dtls['emp_pre_street_no']:'NA', 
	                  "presCity"=>$employee_dtls['emp_pre_vill'], 
	                  "presDist"=>$district ,"presState"=>'10',
	                  "presPin"=>$employee_dtls['emp_pre_pin'], 
	                  "presAdrIsActive"=>'Y',
	                  "presAdrWef"=>$curr_date, 
	                  "permStreet"=>$employee_dtls['emp_per_street_no'],
	                  "permCity"=>$employee_dtls['emp_pre_vill'],
	                  "permDist"=>$district,
	                  "permState"=>'10',
	                  "permPin"=>$employee_dtls['emp_per_pin'],
	                  'permAdrIsActive'=>'Y',
	                  'permAdrWef'=>$curr_date
	                );
$addrDtls_array= array( $key6=> $addrDtls);

 
 
$workDtls = array();
$key12="workDtls";
$opCodePf = ($ddo_dtls[0]['operator_code_pf'])?$ddo_dtls[0]['operator_code_pf']:$ddo_dtls[0]['pl_code_pf'];
$workDtls = array(
	                   "ddoCode"=>$ddo_dtls[0]['ddo_code'], 
	                   "ddoWef"=>($ddo_dtls[0]['ddo_code'] != '')?$curr_date:'', 
	                   "ddoIsActive"=>($ddo_dtls[0]['ddo_code'] != '')?$ddoIsActive:'',
	                   "opCodeLf"=>$opCodeLf,
	                   "opCodeLfWef"=>($opCodeLf != '')?$curr_date:'',
	                   "opCodeLfIsActive"=>($opCodeLf != '')?"Y":'', 
	                   "opCodeLfTresCode"=>$ddo_dtls[0]['t_code_pf'],
	                   "opCodePf"=>$opCodePf,
	                   "opCodePfWef"=>($opCodePf != '')?$curr_date:'',
	                   "opCodePfIsActive"=>($opCodePf != '')?"Y":'', 
	                   "opCodePfTresCode"=>$ddo_dtls[0]['t_code_pf'], 
	                   "hooCode"=>$ddo_dtls[0]['hoo_code'], 
	                   "hooCodeWef"=>($ddo_dtls[0]['hoo_code'] != '')?$curr_date:'', 
	                   "hooCodeIsActive"=>($ddo_dtls[0]['hoo_code'] != '')?'Y':'', 
	                   "sancAuthCode"=>$ddo_dtls[0]['hoo_code'], // Mandatory Field
	                   "sancAuthWef"=>$curr_date, 
	                   "sancAuthIsActive"=>"N", 
	                   "recAuthCode"=>"", 
	                   "recAuthCodeWef"=>"", 
	                   "recAuthCodeIsActive"=>"", 
	                   "sectionCode"=>"", 
	                   "sectionCodeWef"=>"", 
	                   "sectionCodeIsActive"=>"", 
	                   "desig"=>$desig, 
	                   "desigWef"=>$emp_join_prsnt_post_date, 
	                   "desigIsActive"=>"Y", 
	                   "apptAppNo"=>($employee_dtls['emp_present_memo_no'] =='')?$employee_dtls['present_memo_no']:$employee_dtls['emp_present_memo_no'], 
	                   "apptAppDt"=>$emp_present_memo_date, 
	                   "apptWefDt"=>$emp_join_prsnt_post_date, 
	                   "apptAppIsActive"=>"Y"
	                );


$workDtls_array= array( $key12=> json_encode($workDtls));



 
$key7="exitSerDtls";
$exitSerDtls = array(
	                       "terType"=>$emp_status, 
	                       "terDt"=>$emp_termination_date
	                  );
$exitSerDtls_array= array( $key7=> $exitSerDtls);

 
 $payInfoDtls = array();
 $key8="payInfoDtls";
$payInfoDtls = array(
	                       "ropa"=>$ropa_status, 
	                       "ropaWef"=>$curr_date,
	                       "ropaIsActive"=>1
	                    );
//print_r($payInfoDtls); exit;
//$payInfoDtls[] = array("ropa"=>$ropa_status, "ropaWef"=>$curr_date);
$payInfoDtls_array= array( $key8=> json_encode($payInfoDtls));

 
 $payAllowDtls = array();
 $key9="payAllowDtls";
 $payAllowDtls = array(
 	                         "basicPay"=>$employee_dtls['emp_pay_in_payband'], 
 	                         "basicPayWef"=>$curr_date,
 	                         'basicPayIsActive'=>'Y', 
 	                         "gradePay"=>$employee_dtls['emp_grade_pay'], 
 	                         "gradePayWef"=>$curr_date, 
 	                         'gradePayIsActive'=>'Y'
 	                       );
 $payAllowDtls_array= array( $key9=> json_encode($payAllowDtls));



$key10="benfDtls";
$benfDtls = array(
	                  "ifsc"=>$employee_dtls['emp_ifsc_no'], 
	                  "accNo"=>$employee_dtls['emp_acc_no'],
	                  "accNoIsActive"=>'Y',
	                 // "benfWef"=>$emp_conf_join_date
	                );
$benfDtls_array= array( $key10=> $benfDtls);


$keydate="cDateTime";
$cDateTime = date('Y-m-d h:i');
$cDateTime_array= array( $keydate=> $cDateTime);

$key20="empDtls";
$empDtls = array($key3=> $basicDtls, $key4=> $otherDtls, $key5=> $relationDtls, $key11=>$spouseDtls, $key6=> $addrDtls, $key12=> $workDtls, $key7=> $exitSerDtls, $key8=> $payInfoDtls, $key9=> $payAllowDtls, $key10=> $benfDtls, $keydate=>$cDateTime);
$empDtls_array= array( $key20=> $empDtls);



$key30="req";
$req = array($key20=> $empDtls);
$req_array= array($key30=> $req);


$all_array= array($key30=> $req);
//print_r($all_array); exit;
$all_array_json= json_encode($all_array);

//print_r($all_array_json); exit;
//print($status); exit;
//print_r($all_array_json); exit;
if($status == 'M')
{
	//print("test"); exit;
	$all_array_json = ModifyData($all_array_json,$prevEmployeeDetails,$pfaccno);
}
$all_array_json = str_replace('\/','/',$all_array_json);
//print_r(json_decode($all_array_json)); exit;


   $gpf_id_generation=gpf_id_generation($party_code);
   
 
   $ben_upload = call_GPF($emp_id_const,$gpf_id_generation,$all_array_json,$status);
 
   $configData = json_decode($ben_upload, true); 
   $Argument = array(
   	                 'emp_id_const'=>$emp_id_const,
   	                 'gpf_id_generation'=>$gpf_id_generation,
   	                 'pnrd_request'=>$all_array_json,
   	                 'sendstatus'=>$ben_upload,
                    );

   SaveRequest($Argument);
   header('Location:/page/intra_ps/eo/employee_list_gpf.php');
 
 /*$insert_request=$db->insert("INSERT INTO prd_ifms_request_master(drn_no,month_year,request_id,request_type,date, response_code, full_response)
								VALUES (
								'".$drn_number."',
								'".$monthyear."',
								'".$request_id."',
								'BENF',
								now(),
								'".$response_code."',
								'".$ben_upload."'
								)");*/
	

 

?>