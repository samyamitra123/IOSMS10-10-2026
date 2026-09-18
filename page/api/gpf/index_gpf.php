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
global $db;
include( 'ngipfFunction.php');

$crypto = new cryptography();
// Data Initialization Sector....
$emp_id_pk = $crypto->decode($_REQUEST['emp_id_pk'],4);
$debug = isset($_GET['debug'])?1:0;

//print($emp_id_pk); exit;
//$stake_abbr = $_SESSION['user_info']['stake_abbr'];
//$block_code = $_SESSION['location']['block_code'];
//$ps_id = $_SESSION['location']['ps_id'];
$redirectUrl = $_SERVER['HTTP_REFERER'];
//var_dump($emp_id_pk); die;

//LEFT JOIN prd_stake_epension_employee_profile as pseep ON pem.emp_id_pk=pseep.emp_id_fk
$employee_dtls = $db->fetch_table("select * from 
	                                 prd_employee_master as pem
	                                 WHERE pem.emp_id_pk ='".$emp_id_pk."'  AND pem.emp_cosolidated_pay=0
	                                 ORDER BY emp_id_pk ASC"); //AND pem.ropa_status = 1
if(count($employee_dtls) < 1)
{
	echo "You are trying invalid PRI Employee"; 
	exit;
}
$employee_dtls = $employee_dtls[0];

$emp_id_const = $employee_dtls['emp_id_const'];

// Check If Employee Already In NGIPF..
$Query = "SELECT * from prd_gpf_request_master WHERE emp_id_const='".$emp_id_const."'";
$prevEmployeeDetails = $db->fetch_table($Query);
$status="I";
if(count($prevEmployeeDetails) > 0 && $debug == 0)
  {
      /*$status="M";
      $pfaccno = $prevEmployeeDetails[0]['pfaccno'];
      $prevEmployeeDetails = $prevEmployeeDetails[0]['pnrd_request'];
      */
     $deleteQuery = "DELETE from prd_gpf_request_master WHERE request_id_pk='".$prevEmployeeDetails[0]['request_id_pk']."'";
     $db->update($deleteQuery);      
      
  }

//print_r($status); exit;

//$emp_id_const=$employee_dtls['emp_id_const'];
$SectionCode = '';
// For BDO
if($employee_dtls['gp_id_fk'] > 0){
	

  $gpId = $employee_dtls['gp_id_fk'];
  $Query = "SELECT plmg.*, plmb.block_code from prd_location_master_gp as plmg
            LEFT JOIN prd_location_master_block as plmb ON plmg.block_id_fk = plmb.block_id_pk 
            WHERE gp_id_pk=".$gpId;
  $gpData = $db->fetch_table($Query);
  $SectionCode = ($gpData[0]['section_code'] == 0)?'':$gpData[0]['section_code'];
	$ddo_dtls = $db->fetch_table("select * from prd_dise_admin WHERE block_code ='".$gpData[0]['block_code']."' ");
	$ddoIsActive = 'Y';
	$opCodeLf =""; 
	$ddo_dtls[0]['treasury_code'] = $ddo_dtls[0]['t_code_pf']; 
 // print_r($ddo_dtls); exit;


}
else if($employee_dtls['ps_id_fk'] > 0){ 
  // For Panchyat Samiti
	$ddo_dtls = $db->fetch_table("select * from psemp_ps_profile WHERE ps_id_fk ='".$employee_dtls['ps_id_fk']."' ");
	//print_r($ddo_dtls); exit;
	$ddoIsActive = 'N';
	$opCodeLf =$ddo_dtls[0]['ddo_code'];
	$ddo_dtls[0]['ddo_code'] = '';
}else if($employee_dtls['zp_id_fk'] > 0){ 
  // For Zilla Parishad
	$ddo_dtls = $db->fetch_table("select * from zpemp_zp_profile WHERE district_id_fk ='".$employee_dtls['zp_id_fk']."' ");
	//print_r("select * from zpemp_zp_profile WHERE district_id_fk ='".$employee_dtls['zp_id_fk']."' "); exit;
	$ddo_dtls[0]['treasury_code'] = $ddo_dtls[0]['ddo_code'];

	$ddoIsActive = 'N';
	$opCodeLf =$ddo_dtls[0]['operator_code_pf'];
	$ddo_dtls[0]['ddo_code'] = '';
}
else if($employee_dtls['zp_id_fk'] > 0)
{ 
	  // For Zilla Parishad
		$ddo_dtls = $db->fetch_table("select * from zpemp_zp_profile WHERE district_id_fk ='".$employee_dtls['zp_id_fk']."' ");

		$ddo_dtls[0]['treasury_code'] = $ddo_dtls[0]['ddo_code'];

		$ddoIsActive = 'N';
		$opCodeLf =$ddo_dtls[0]['operator_code_pf'];
		$ddo_dtls[0]['ddo_code'] = '';
	}
else
{
	 echo "Sorry the employee PRI selection is wrong"; exit;
}
//print_r($_SESSION); exit;
//print_r($employee_dtls); exit;

$curr_date = date("d/m/Y");
$employee_dtls['emp_second_name'] = (rtrim($employee_dtls['emp_second_name']) != '')?$employee_dtls['emp_second_name'].' ':'';
$emp_name = $employee_dtls['emp_first_name'].' '.$employee_dtls['emp_second_name'].$employee_dtls['emp_last_name'];
$emp_id_const = $employee_dtls['emp_id_const'];

$s_falge=$employee_dtls['gpf_sent_status'];


$emp_dob = dateshow($employee_dtls['emp_dob']); 

//$emp_dob = preg_match($employee_dtls['emp_dob']); 
$emp_first_join_date = ($employee_dtls['emp_first_join_date'] != '')?dateshow($employee_dtls['emp_first_join_date']):'';
$emp_join_prsnt_post_date = ($employee_dtls['emp_join_prsnt_post_date'] != '')?dateshow($employee_dtls['emp_join_prsnt_post_date']):'';
$emp_present_memo_date = ($employee_dtls['emp_present_memo_date'] != '')?dateshow($employee_dtls['emp_present_memo_date']):dateshow($employee_dtls['presnt_memo_wef_date']);
$emp_conf_join_date = ($employee_dtls['emp_conf_join_date'] != '' && $employee_dtls['emp_conf_join_date'] != '0001-01-01')?dateshow($employee_dtls['emp_conf_join_date']):'';
//print($employee_dtls['emp_conf_join_date']); exit;

$gender = fun_code($employee_dtls['emp_sex']);

$desig = ($employee_dtls['zp_id_fk'] > 0)?getZpDesign($employee_dtls['emp_desig']):fun_code($employee_dtls['emp_desig']);

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

if($employee_dtls['gp_id_fk'] > 0){ $party_code = '006'; }
else if($employee_dtls['ps_id_fk'] > 0){ $party_code = '007'; }
else if($employee_dtls['zp_id_fk'] > 0){ $party_code = '008'; }

//print_r($employee_dtls); exit;
//var_dump($emp_first_name); die;





if($employee_dtls['ropa_status'] == 0){ $ropa_status ="WGSROPA09";}
else if($employee_dtls['ropa_status'] == 1){ $ropa_status ="WGSROPA19";}







$key3="basicDtls";
$basicDtls = array("empNm"=>$emp_name, "empId"=>$emp_id_const, "gender"=>$gender,"religion"=>$emp_religion,"dob"=>$emp_dob,
"doj"=>$emp_first_join_date,
"isActive"=>'Y',"retDt"=>$emp_termination_date,"gpfCpf"=>'GPF');

$basicDtls_array= array( $key3=> $basicDtls);
$empTermination = '';
$emp_status ="EME";
//print($employee_dtls['emp_status']); exit;
if($employee_dtls['emp_status'] != 1)
	{ 
		$Query = "SELECT * from prd_stop_sal_reason WHERE emp_id_fk=".$employee_dtls['emp_id_pk']." order by reason_id_pk desc";
		$TerminationReason = $db->fetch_table($Query);
		//print_r($TerminationReason); exit;
    $TerminationReason = $TerminationReason[0]['reason'];

      switch($TerminationReason)
        {
        	 case '1992': // Resignation
        	  $emp_status ="EMTER";
        	  $empTermination = 'R';
        	 break;
        	 case '1993': // Die in Harness
        	  $emp_status ="EMTER";
        	  $empTermination = 'D';
        	 break;
        	 case '1991': // Retired
        	  $emp_status ="EMTER";
        	  $empTermination = 'N';
        	 break;
        	 case '1990': // Transfer Initiated
        	 case '1994': // Deputated Employee
        	  $emp_status ="EME";
        	  //$empTermination = 'N';
        	 break;         	 
      /*  	 case '1991': // Resignation for new post in PRI
        	  $emp_status ="EMRS";
        	  $empTermination = 'O';
        	 break;  */      	 
        }
	}
else
	{
		$emp_status ="EME";
	}

if($employee_dtls['emp_status'] == 9)
	{
	    	 $emp_status ="EMS";
  }   

$key4="otherDtls";


$otherDtls = array(
	                   "empType"=>$emp_status, 
	                   "empTypeWef"=>($emp_status == 'EME')?$emp_first_join_date:$emp_termination_date,
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
 $spouseDtls[] = array(
 	                      "spouseNm"=>$spouse_name, 
 	                      "spouseNmIsActive"=>($spouse_name !='')?$spouse_status:'', 
 	                      "spouseNmWef"=>($spouse_name !='')?$curr_date:''
 	                    );
// $spouseDtls[] = array("spouseNm"=>$spouse_name.'Rai', "spouseNmIsActive"=>$spouse_status, "spouseNmWef"=>" ");
 $spouseDtls_array= array($key11=> json_encode($spouseDtls));

//This is for Subikar Testing Start
//$spouse_status = 'N';
 //$employee_dtls['emp_pre_vill'] = 'Goa';
// $employee_dtls['emp_pre_vill'] = 'Goa';
//This is for Subikar Testing end

// State = 10 As per GPF Instruction Do not change..
    $Address = $employee_dtls['emp_pre_street_no'].$employee_dtls['emp_pre_vill']; 

   $addressCount = strlen($Address);
   $presStreet = '';
   if($addressCount > 100)
   {

      $presStreet = substr($Address,0,100);
      $Address = substr($Address,100);

   }
   
   $AddressPerm = $employee_dtls['emp_per_street_no'].$employee_dtls['emp_per_vill']; 

   $addressCount = strlen($AddressPerm);
   $permStreet = '';
   if($addressCount > 100)
   {

      $permStreet = substr($AddressPerm,0,100);
      $AddressPerm = substr($AddressPerm,100);

   }   
$key6="addrDtls";
$addrDtls = array(
                    "presStreet"=>($presStreet!='')?$presStreet:'NA', 
                    "presCity"=>$Address, 
	                  "presDist"=>$district ,
	                  "presState"=>'10',
	                  "presPin"=>$employee_dtls['emp_pre_pin'], 
	                  "presAdrIsActive"=>'Y',
	                  "presAdrWef"=>$curr_date, 
	                  "permStreet"=>($permStreet!='')?$permStreet:'NA',
	                  "permCity"=>$AddressPerm,
	                  "permDist"=>$district,
	                  "permState"=>'10',
	                  "permPin"=>$employee_dtls['emp_per_pin'],
	                  'permAdrIsActive'=>'Y',
	                  'permAdrWef'=>$curr_date
	                );
$addrDtls_array= array( $key6=> $addrDtls);

$ddo_dtls[0]['treasury_code'] = strtoupper($ddo_dtls[0]['treasury_code']);
//print($ddo_dtls[0]['t_code_pf']); exit;
$ddo_dtls[0]['hoo_code']  = strtoupper($ddo_dtls[0]['hoo_code']);
$ddo_dtls[0]['ddo_code']  = strtoupper($ddo_dtls[0]['ddo_code']); 
$workDtls = array();
$key12="workDtls";
$opCodePf = ($ddo_dtls[0]['operator_code_pf'])?$ddo_dtls[0]['operator_code_pf']:$ddo_dtls[0]['pl_code_pf'];
$workDtls[] = array(
	                   "ddoCode"=>$ddo_dtls[0]['ddo_code'], 
	                   "ddoWef"=>($ddo_dtls[0]['ddo_code'] != '')?$curr_date:'', 
	                   "ddoIsActive"=>($ddo_dtls[0]['ddo_code'] != '')?$ddoIsActive:'',
	                   "opCodeLf"=>($opCodeLf != '')?$opCodeLf:'NA',
	                   "opCodeLfWef"=>($opCodeLf != '')?$curr_date:'',
	                   "opCodeLfIsActive"=>($opCodeLf != '')?"Y":'', 
	                   "opCodeLfTresCode"=>$ddo_dtls[0]['treasury_code'],
	                   "opCodePf"=>$opCodePf,
	                   "opCodePfWef"=>($opCodePf != '')?$curr_date:'',
	                   "opCodePfIsActive"=>($opCodePf != '')?"Y":'', 
	                   "opCodePfTresCode"=>$ddo_dtls[0]['treasury_code'], 
	                   "hooCode"=>$ddo_dtls[0]['hoo_code'], 
	                   "hooCodeWef"=>($ddo_dtls[0]['hoo_code'] != '')?$curr_date:'', 
	                   "hooCodeIsActive"=>($ddo_dtls[0]['hoo_code'] != '')?'Y':'', 
	                   "sancAuthCode"=>$ddo_dtls[0]['hoo_code'], // Mandatory Field
	                   "sancAuthWef"=>($ddo_dtls[0]['hoo_code'] != '')?$curr_date:'', 
	                   "sancAuthIsActive"=>($ddo_dtls[0]['hoo_code'] != '')?'Y':'', 
	                   "recAuthCode"=>"", 
	                   "recAuthCodeWef"=>"", 
	                   "recAuthCodeIsActive"=>"", 
	                   "sectionCode"=>$SectionCode, 
	                   "sectionCodeWef"=>($SectionCode !='')?$curr_date:'',
	                   "sectionCodeIsActive"=>($SectionCode !='')?'Y':'',
	                   "desig"=>$desig, 
	                   "desigWef"=>$emp_join_prsnt_post_date, 
	                   "desigIsActive"=>"Y", 
	                   "apptAppNo"=>($employee_dtls['emp_present_memo_no'] =='')?$employee_dtls['present_memo_no']:$employee_dtls['emp_present_memo_no'], 
	                   "apptAppDt"=>$emp_present_memo_date, 
	                   "apptWefDt"=>($emp_present_memo_date !='')?$emp_join_prsnt_post_date:'', 
	                   "apptAppIsActive"=>($emp_present_memo_date !='')?'Y':'',
	                );


$workDtls_array= array( $key12=> json_encode($workDtls));



 
$key7="exitSerDtls";
$exitSerDtls = array(
	                       "terType"=>$empTermination, 
	                       "terDt"=>($empTermination != '')?$emp_termination_date:''
	                  );
$exitSerDtls_array= array( $key7=> $exitSerDtls);

 
 $payInfoDtls = array();
 $key8="payInfoDtls";
$payInfoDtls[] = array(
	                       "ropa"=>$ropa_status, 
	                       "ropaWef"=>$curr_date,
	                       "ropaIsActive"=>1
	                    );
//print_r($payInfoDtls); exit;
//$payInfoDtls[] = array("ropa"=>$ropa_status, "ropaWef"=>$curr_date);
$payInfoDtls_array= array( $key8=> json_encode($payInfoDtls));

 
 $payAllowDtls = array();
 $key9="payAllowDtls";
 $payAllowDtls[] = array(
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
	                  "benfWef"=>$emp_conf_join_date
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
if($debug == 1)
  { 
    //print_r($ddo_dtls);
  	print_r($all_array); 
  	exit; 
  }
$all_array_json= json_encode($all_array);
$all_array_json = str_replace('\/','/',$all_array_json);
//print_r($all_array_json); exit;
/*if($status == 'M')
{
	//print("test"); exit;
	$all_array_json = ModifyData($all_array_json,$prevEmployeeDetails,$pfaccno);
}*/

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
   header('Location:'.$redirectUrl);
 
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