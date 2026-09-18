<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../all_function/fun_store/zp_ps_gp_function.php';
//require '../../page_visite.php';
//require 'includes/library/session.class.php';

//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}
//redirect to login page when login session not found
if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
//------------------------------------------------------------------------------------------------------
$cryptoGraph=new cryptography();

$db=new database();

$fun_store=new zp_ps_gp_class();

$emp_data=$db->fetch_table("select * from prd_employee_master where emp_id_pk='".$cryptoGraph->decode($_REQUEST['id'],4)."'");
/*foreach($emp_data as $item){
	print_r($item);
}
*/
$ps_name=$db->fetch_table("Select ps_name from prd_location_master_panchayat_samiti where ps_id_pk='".$emp_data[0]['ps_id_fk']."'");
$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");

$desig_data = $db->fetch_table("
							SELECT designation_id, designation_name
							FROM zpemp_emp_desig_master;
	
	");

/*function fun_common($tcode, $code)
{
	foreach ($code as $key) {
		if($key['code'] == $tcode){
				return $key['description'];
		}
	}
}

function fun_payband($val)
{
	$db = new database();
	$data = @$db->fetch_table("SELECT payband_name FROM prd_payband_master where payband_code='".$val."';");
	return $data[0]['payband_name'];
}

function fun_grade_pay($val)
{
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
	return $dist_data2[0]['grade_amount'];
}
	
function fun_payscale($val)
{
	$db = new database();
	$data = @$db->fetch_table("SELECT payscale_range FROM prd_dise_payscale_master where payscale_code='".$val."';");
	return $data[0]['payscale_range'];
}

function fun_dist($val)
{
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT district_code, district_name FROM prd_location_master_district where district_id_pk='".$val."';");
	return $dist_data2[0]['district_name'];
}

function fun_bank($val)
{
	$db = new database();
	$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
	return $dist_data2[0]['bank_name'];
}

function date_frmt($original_date)
{
	if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date =='' || $original_date == NULL)
	{
		return "---";
	}
	else
	{
		$old=explode("-",$original_date);
        $new=$old[2]."-".$old[1]."-".$old[0];
		return $new;
	}
}

function fun_emp_type($val)
{
	$db = new database();
	$emp_type = @$db->fetch_table("SELECT description FROM prd_dise_code_master where code='".$val."';");
	return $emp_type[0]['description'];
}
	
function fun_state($val)
{
	if($val=='32')
	{
		return 'WEST BENGAL';
	}
	else
	{
		return 'OTHERS';
	}
}*/

$Employee_ID_val=$emp_data[0]['emp_id_const']; 
if($Employee_ID_val=='0')
{
	 $Employee_ID='';
}
else
{
	$Employee_ID=$emp_data[0]['emp_id_const']; 
}
	
if($emp_data[0]['emp_land_no']==0)
{
	$Land_Tel_No=='';
}
else
{
	$Land_Tel_No==$emp_data[0]['emp_id_const'];
}
	
if($emp_data[0]['emp_desig_first_app']=='1114' ||$emp_data[0]['emp_desig_first_app']=='1115'|| $emp_data[0]['emp_desig_first_app']=='1118')
{
	$emp_desig_first_app='GP&nbsp;'.$fun_store->fun_common($emp_data[0]['emp_desig_first_app'],$code_data);
}
else
{
	//$emp_desig_first_app=$fun_store->fun_common($emp_data[0]['emp_desig_first_app'],$code_data);
	$emp_desig_first_app=$fun_store->fun_desig( $emp_data[0]['emp_desig_first_app'],$desig_data);
}
	
if($emp_data[0]['emp_accommodation']=="1") 
{ 
	$emp_accommodation = "YES"; 
} 
else 
{ 
	$emp_accommodation = "NO";
}
 		
//

//print_r($emp_data);
//exit;
//------------------------------------------------------------------------------------------------------	
define("_MPDF_TEMP_PATH", '../../../locker/temp/');
include ('../../../includes/third-party/mpdf/mpdf.php');
//$stylesheet = file_get_contents($config['base_url'].'themes/default/css/mpdf_employee_details.css');
$stylesheet ='';
$mpdf=new mPDF();

// This sets sufficient rights for the user to modify your annotations
//$mpdf->SetUserRights(false, '/Create/Delete/Modify/Copy/Import/Export');

// If you want to encrypt the file, include the necessary permissions
//$mpdf->SetProtection(array(), 'userpass', 'nicpass');

//------------------------------------------------------------------------------------------------------




$mpdf->WriteHTML($stylesheet,1);

//Header and footer
$mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_user'].'|{PAGENO}');



$mpdf->WriteHTML('
					
					
					<table>
						<tr>
							<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"></div></td>
							<td class="he2" style="text-align: center;width: 480px;">
							
								<div class="logo" style="text-align: center;"><img width="30" src="../../../themes/default/image/ashoka.jpg" /></div>
								<p class="department" style="font-size: 11px;margin: 0px;padding:0px;">Panchayats & Rural Development Department, GOVT. OF WB</p>
								<h3 class="school" style="color: #008200;font-size: 24px;">'. $_SESSION['location']['ps_name'] .'</h3>
								<p><b>STAFF DETAILS</b></p>	
							</td>
							<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><img width="100" src="../../../themes/default/image/iosms_logo.png" /></div></td>
							
						</tr>
					</table>
					
					');
                if($emp_data[0]['emp_id_const']=='0')
				{
						$mpdf->WriteHTML('<table align="right"><tr><td>
							
								<div align="right" style="color:#E38A3F;">[ WAITING FOR FINALIZE ]</div>
								
							</td>   </tr></table>
					',2);
				}
//Staff details (PRIMARY DETAILS OF EMPLOYEE)
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">PRIMARY DETAILS OF EMPLOYEE</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Employee ID :</td>
					<td style="width:25%;">'.$Employee_ID.'</td>
				</tr>',2);
				if($emp_data[0]['zp_emp_type']=='366')
				{
$mpdf->WriteHTML('<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Employee Type :</td>
					<td style="width:25%;">'.$fun_store->fun_emp_type($emp_data[0]['zp_emp_type']).'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Government ID :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_govt_id'].'</td>
				</tr>',2);
				}
				else
				{
					$mpdf->WriteHTML('<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Employee Type :</td>
					<td style="width:25%;">'.$fun_store->fun_emp_type($emp_data[0]['zp_emp_type']).'</td>
					</tr>',2);
				}
$mpdf->WriteHTML('<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Name :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Date of Birth :</td>
					<td style="width:25%;">'.$fun_store->date_frmt($emp_data[0]['emp_dob']).'</td>
				</tr>
				
				
				<tr>
					
			
			       <td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Sex :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_sex'],$code_data).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Caste :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_caste'],$code_data).'</td>
				</tr>
				<tr>
					
				
		          <td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Voter ID :</td>
					<td style="width:25%;">'.strtoupper($emp_data[0]['emp_voter_id']).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Aadhaar ID :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_aadhar_no'].'</td>
				</tr>
				<tr>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">PAN No :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_pan_no'].'</td>',2);
				if($emp_data[0]['zp_emp_type']=='367')
				{
	$mpdf->WriteHTML('<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Educational Qualification :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_edu_quali'],$code_data).'</td>',2);
				}
$mpdf->WriteHTML('</tr>
				</table>',2);


//Staff details (professional DETAILS)
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">PROFESSIONAL DETAILS</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;" >Notification number of the Appointment :</td>
					<td style="width:25%;">'.$emp_data[0]['notification_no'].'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;" >Designation :</td>
					<td style="width:25%;">'.$fun_store->fun_desig( $emp_data[0]['emp_desig'],$desig_data).'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Date of First Joining in Service :</td>
					<td style="width:25%;">'.$fun_store->date_frmt($emp_data[0]['emp_first_join_date']).'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Date of Confirmation in Service Applicable :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['conf_dt_flag'],$code_data).'</td>
				
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Date of Confirmation in Service :</td>
					<td style="width:25%;">'.$fun_store->date_frmt($emp_data[0]['emp_conf_join_date']).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;" >Date of Joining in the Present Post :</td>
					<td style="width:25%;">'.$fun_store->date_frmt($emp_data[0]['emp_join_prsnt_post_date']).'</td>
				</tr>
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;" >Date of Joining in the Present Office :</td>
					<td style="width:25%;">'. $fun_store->date_frmt($emp_data[0]['emp_join_prsnt_office_date']).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Date of Retirement/Termination :</td>
					<td style="width:25%;">'.$fun_store->date_frmt($emp_data[0]['emp_retirement_date']).'</td>
				</tr>',2);
				if($emp_data[0]['emp_desig']!='1'){  
                $mpdf->WriteHTML('<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Whether on Deputation :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_status_deputation'],$code_data).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Employee Group:</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_group'],$code_data).'</td>
				</tr>',2);
				}
                $mpdf->WriteHTML('<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Designation at First Appointment :</td>
					<td style="width:25%;">' .$emp_desig_first_app.'</td>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Date of Next Increment :</td>
					<td style="width:25%;">'.$fun_store->date_frmt($emp_data[0]['emp_next_increment_date']).'</td>
				</tr>
                <tr>
					
					<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Amount of Increment (On Basic Pay):</td>
					<td style="width:25%;">'.$emp_data[0]['emp_next_increment_amount'].'</td>
				</tr>
				</table>',2);		
//Staff details (SALARY DETAILS)
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">SALARY DETAILS</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">',2);
				if($emp_data[0]['emp_desig']=='1'){  
               $mpdf->WriteHTML(' <tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;" >Consolidated Pay :</td>
					<td colspan="3" style="width:25%;">'.$emp_data[0]['emp_cosolidated_pay'].'</td>
				</tr>',2);
                 } else { 
				$mpdf->WriteHTML('<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Pay Band :</td>
					<td style="width:25%;">'.$fun_store->fun_payband($emp_data[0]['emp_pay_band']).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;;font-family: "Arial Narrow";font-style: italic;">Pay Scale :</td>
				<td style="width:25%;">'.$fun_store->fun_payscale($emp_data[0]['emp_pay_scale']).'</td>
				</tr>
				<tr>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Pay in Pay Band :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_pay_in_payband'].'</td>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Grade Pay :</td>
					<td style="width:25%;">'.$fun_store->fun_grade_pay($emp_data[0]['emp_grade_pay']).'</td>
				</tr>',2);
                }
                $mpdf->WriteHTML('<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Bank Name :</td>
					<td>'.$fun_store->fun_bank($emp_data[0]['emp_bank_name']).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Branch Name :</td>
				<td style="width:25%;">'.$emp_data[0]['emp_bank_branch'].'</td>
				</tr>
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Branch Code :</td>
					<td style="width:25%;">'.strtoupper($emp_data[0]['emp_branch_code']).'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">MICR Code :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_micr_no'].'</td>
				</tr>
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Account No :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_acc_no'].'</td>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">IFSC Code :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_ifsc_no'].'</td>
				</tr>',2);
				if($emp_data[0]['zp_emp_type']=='366')
				{
					$mpdf->WriteHTML('<tr>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">GPF Account No :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_gpf_acc_no'].'</td>
					</tr>',2);
				}
				
				$mpdf->WriteHTML('
				</table>
				 ',2);			 

	
//Staff details (PERSONAL DETAILS)
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">PERSONAL DETAILS</h4>
				<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;" >Father’s Name :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_father_name'].'</td>
				</tr>
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;" >Mother’s Name :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_mother_name'].'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Religion :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_religion'],$code_data).'</td>
				
					<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Mother Tongue</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_mother_tongue'],$code_data).'</td>
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Marital status :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_marital_status'],$code_data).'</td>
					
				</tr>',2);
                if($emp_data[0]['emp_marital_status']!='242'){ 
               $mpdf->WriteHTML(' <tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;" >Spouse Name :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_spouse_name'].'</td>
				</tr>',2);
                } if($emp_data[0]['emp_desig']!='1'){ 
				
				if($emp_data[0]['emp_marital_status']!='242'){
				$mpdf->WriteHTML('
                
                <tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Whether spouse is employed :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_spouse_job_status'],$code_data).'</td>
					
                </tr>
				<tr>
				<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;"> Employment Details :</td>
					<td style="width:25%;">'.(strtoupper($emp_data[0]['emp_spouse_details'])).'</td>
					
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Spouse pay :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_spouse_pay'].'</td>
					<tr>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Spouse HRA:</td>
					<td style="width:25%;">'.$emp_data[0]['emp_spouse_hra'].'</td>
				</tr>',2);
                } 
                $mpdf->WriteHTML('
				<tr>
					<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Wheather Spouse is being Provided any Accomodation by Employer :</td>
					<td style="width:25%;">'.$emp_accommodation.'</td>',2);
			
			if($emp_data[0]['zp_emp_type'] == 366 || $emp_data[0]['zp_emp_type'] == 367)
			{
				$mpdf->WriteHTML('
					<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Opted for enrolment in WB Health Scheme:</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['spouse_medical_allowance'],$code_data).'</td>',2);
			}
				$mpdf->WriteHTML('
				</tr>
				<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Residential Status :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_spouse_res'],$code_data).'</td>
			
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Housing Scheme :</td>
					<td style="width:25%;">'.(strtoupper($emp_data[0]['emp_spouse_house_schm'])).'</td>
				</tr>',2);
                } 
                $mpdf->WriteHTML(
				   '<tr>
					
				
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Blood Group :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_blood_grp'],$code_data).'</td>
				</tr>',2);
                $mpdf->WriteHTML('<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Height :</td>',2);
					
					if($emp_data[0]['emp_height']!='0'){
					$mpdf->WriteHTML('<td style="width:25%;">'.$emp_data[0]['emp_height'].'</td>',2);
					}else{
					$mpdf->WriteHTML('
					<td style="width:25%;"></td>',2);
					}
					$mpdf->WriteHTML('
				<td class="col3" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Identification Mark :</td>
					<td style="width:25%;">'.$emp_data[0]['emp_idf_mark'].'</td>
				</tr>',2);
                $mpdf->WriteHTML('<tr>
					<td class="col1" style="color: #1F6377;font-weight: bold;width:25%;font-family: "Arial Narrow";font-style: italic;">Whether Differently Abled :</td>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['emp_diff_able'],$code_data).'</td>',2);
					if($emp_data[0]['emp_diff_able']=='1'){ 
                    $mpdf->WriteHTML('<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Status of Disability :</td>
					<td style="width:25%;">'.(strtoupper($emp_data[0]['emp_disable_status'])).'</td>',2);
					 $mpdf->WriteHTML('<tr><td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether Eligible For Conveyance Allowance :</td></tr>
					<td style="width:25%;">'.$fun_store->fun_common($emp_data[0]['conv_allow_status'],$code_data).'</td>',2);
                    } 
				$mpdf->WriteHTML('</tr>
				</table>',2);
				
//$mpdf->AddPage();	//Commented to avoid page breaking			
//Staff details (CONTACT DETAILS)
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">CONTACT DETAILS</h4>
						<h5 style="margin: 0px;padding: 0px;text-transform: uppercase;">Present Address</h5>
						<hr />
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
							<tr>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">State :</td>
								<td style="width:25%;">'.$fun_store->fun_state($emp_data[0]['pre_state']).'</td>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Police Station :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_pre_ps'].'</td>
								
							</tr>
							<tr>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">House No. :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_pre_house_no'].'</td>
							
									<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Street :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_pre_street_no'].'</td>

							</tr>
							<tr>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Town/ Village :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_pre_vill'].'</td>
								
								<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Post Office :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_pre_post'].'</td>

							</tr>
							<tr>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">PIN :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_pre_pin'].'</td>
								
								<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">District :</td>
								<td style="width:25%;">'.$fun_store->fun_dist($emp_data[0]['emp_pre_dist']).'</td>

							</tr>
						</table>
						
						<h5 style="margin: 0px;padding: 0px;text-transform: uppercase;">Permanent Address</h5>
						<hr />
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
							<tr>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">State :</td>
								<td style="width:25%;">'.$fun_store->fun_state($emp_data[0]['per_state']).'</td>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Police Station :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_per_ps'].'</td>
							</tr>
							<tr>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">House No. :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_per_house_no'].'</td>
							
								<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Street :</td>
							<td style="width:25%;">'.$emp_data[0]['emp_per_street_no'].'</td>

							</tr>
							<tr>
							<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Town/ Village :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_per_vill'].'</td>
								
								<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Post Office :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_per_post'].'</td>

							</tr>
							<tr>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">PIN :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_per_pin'].'</td>
								
								<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">District :</td>
								<td style="width:25%;">'.$fun_store->fun_dist($emp_data[0]['emp_per_dist']).'</td>

							</tr>
						</table>
						
						<h5 style="margin: 0px;padding: 0px;text-transform: uppercase;">Contact Details</h5>
						<hr />
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
							<tr>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Land Tel. No :</td>
								<td style="width:25%;">'.$Land_Tel_No.'</td><td class="text_r"></td><td class="text_r"></td>
								<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Mobile No. :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_mobile_no'].'</td>

							</tr>
							<tr>
								<td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Email Id :</td>
								<td style="width:25%;">'.$emp_data[0]['emp_mail_id'].'</td>
							
								

							</tr>
						</table>
				',2);
	if($emp_data[0]['emp_status']=='6')
{
	$mpdf->WriteHTML('<span style="color:#660066;font-weight:bold">PROFILE SENT</span>');	
}
else if($emp_data[0]['emp_status']=='1')
{
	$mpdf->WriteHTML('<span style="color:green;font-weight:bold">PROFILE APPROVED</span>');		
}
else if($emp_data[0]['emp_status']=='8')
{
	$mpdf->WriteHTML('<span style="color:RED;font-weight:bold">WAITING FOR UNLOCK</span>');		
}
else if($emp_data[0]['emp_status']=='4')
{
	$mpdf->WriteHTML('<span style="color:green;font-weight:bold">WAITING FOR EDIT</span>');		
}
else if($emp_data[0]['emp_status']=='9')
{
	$mpdf->WriteHTML('<span style="color:green;font-weight:bold">PROFILE SUSPENDED</span>');		
}
else if($emp_data[0]['emp_status']=='3')
{
	$mpdf->WriteHTML('<span style="color:green;font-weight:bold">FORWARDED TO AEO</span>');		
}
			


$mpdf->Output($emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].'.pdf','D');

//$mpdf->Output($emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].' ('. $emp_data[0]['emp_system_code'].').pdf','D');

?>
