<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
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

$logged_user=$_SESSION['user_info']['stake_abbr'];

//------------------------------------------------------------------------------------------------------
$cryptoGraph=new cryptography();

$db=new database();


$emp_id=$cryptoGraph->decode($_GET['emp_id'],4);
$gp_id=$cryptoGraph->decode($_GET['gp_id'],4);





$transfer_update=$db->update("UPDATE 
								prd_employee_transfer 
							SET 
								lpc_status=1 
							WHERE
								gp_id_fk='".$gp_id."' AND emp_id_fk='".$emp_id."' AND transfer_emp_status=0
							");




if($transfer_update)
{
	

	
	$emp_data=$db->fetch_table("select * from prd_employee_master where emp_id_pk='".$emp_id."'");
	
	$gp_name=$db->fetch_table("Select gp_name from prd_location_master_gp where gp_id_pk='".$emp_data[0]['gp_id_fk']."'");
	$code_data = $db->fetch_table("
								SELECT code, description
								FROM prd_dise_code_master;
		
		");
	
	function fun_common($tcode, $code){
		foreach ($code as $key) {
			if($key['code'] == $tcode){
					return $key['description'];
			}
		}
	}
	function fun_payband($val){
			$db = new database();
			$data = @$db->fetch_table("SELECT payband_name FROM prd_payband_master where payband_code='".$val."';");
			return $data[0]['payband_name'];
		}
	function fun_grade_pay($val){
			$db = new database();
			$dist_data2 = @$db->fetch_table("SELECT grade_amount FROM prd_dise_gradepay_master where grade_code='".$val."';");
			return $dist_data2[0]['grade_amount'];
		}	
	function fun_payscale($val){
			$db = new database();
			$data = @$db->fetch_table("SELECT payscale_range FROM prd_dise_payscale_master where payscale_code='".$val."';");
			return $data[0]['payscale_range'];
		}
	function fun_dist($val){
			$db = new database();
			$dist_data2 = @$db->fetch_table("SELECT district_code, district_name FROM prd_location_master_district where district_id_pk='".$val."';");
			return $dist_data2[0]['district_name'];
		}
	function fun_bank($val){
			$db = new database();
			$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
			return $dist_data2[0]['bank_name'];
		}
	function date_frmt($original_date){
		if($original_date =="0001-01-01" || $original_date =='1970-01-01' || $original_date =='' || $original_date == NULL){
			return "---";
		}
		else{
			$old=explode("-",$original_date);
			$new=$old[2]."-".$old[1]."-".$old[0];
			return $new;
		}
	}	
		function fun_state($val){
			if($val=='32'){
				return 'WEST BENGAL';
			}else{
				return 'OTHERS';
			}
		}
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
			
			
	//
	
	//print_r($emp_data);
	//exit;
	//------------------------------------------------------------------------------------------------------	
	define("_MPDF_TEMP_PATH", '../../../locker/temp/');
	include ('../../../includes/third-party/mpdf/mpdf.php');
	$stylesheet ='';
	$mpdf=new mPDF();
	
	
	
	$mpdf->WriteHTML($stylesheet,1);
	
	//Header and footer
	$mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_user'].'|{PAGENO}');
	
	//PDF header
	$mpdf->WriteHTML('
						
						
						<table>
							<tr>
								<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"></div></td>
								<td class="he2" style="text-align: center;width: 480px;">
								
									<div class="logo" style="text-align: center;"><img width="30" src="../../../../themes/default/image/ashoka.jpg" /></div>
									<p class="department" style="font-size: 11px;margin: 0px;padding:0px;">Panchayats & Rural Development Department, GOVT. OF WB</p>
									<h3 class="school" style="color: #008200;font-size: 24px;">'. $gp_name[0]['gp_name'] .'</h3>
									
									<p><b>STAFF DETAILS</b></p>	
								</td>
								<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><img width="100" src="../../../../themes/default/image/iosms_logo.png" /></div></td>
								
								
							</tr>
						
						</table>',2);
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
						<td  class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">Employee ID :</td>
						<td colspan="3" style="font-weight:bold">'.$Employee_ID.'</td>
					</tr>
					<tr>
						<td  class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">Name :</td>
						<td colspan="3" style="font-weight:bold">'.$emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].'</td>
					</tr>
					<tr>
						<td  class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">Date of Birth :</td>
						<td>'.date_frmt($emp_data[0]['emp_dob']).'</td>
						<td class="text_r">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">Sex :</td>
						<td>'.fun_common($emp_data[0]['emp_sex'],$code_data).'</td>
						<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">Caste :</td>
						<td>'.fun_common($emp_data[0]['emp_caste'],$code_data).'</td>
					</tr>
					<tr>
						<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">Voter ID :</td>
						<td> '.strtoupper($emp_data[0]['emp_voter_id']).'</td>
						<td class="col3" style="color: #1F6377;font-weight: bold;width: 156px;font-family: "Arial Narrow";font-style: italic;">Aadhaar ID :</td>
						<td>'.$emp_data[0]['emp_aadhar_no'].'</td>
					</tr>
					<tr>
						<td class="col1" style="color: #1F6377;font-weight: bold;width: 225px;font-family: "Arial Narrow";font-style: italic;">Educational Qualification :</td>
						<td>'.fun_common($emp_data[0]['emp_edu_quali'],$code_data).'</td>
						<td class="text_r"></td>
						<td></td>
					</tr>
					</table>',2);
	
	
	
	
	$mpdf->Output($emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].'.pdf','D');
	$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>LPC successfully generated...</strong></div>';


}
else
{
	
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>LPC generation fails...</strong></div>';
	header('Location:lpc_generation.php?gp_id_fk='.$_POST['gp_id']);
}
?>