<?php
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

session_start();
    require_once '../../includes/config/config.php';
    require_once '../../includes/config/database.config.php';
    require_once '../../includes/library/database.class.php';
    require_once '../../includes/library/cryptography.class.php';
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

//------------------------------------------------------------------------------------------------------
$cryptoGraph=new cryptography();

$db=new database();


$data=$db->fetch_table("select * from intra_pri_cg_profile_master where emp_id_const='".$cryptoGraph->decode($_REQUEST['emp_id_const'],4)."'");
$stakeLevel = $_SESSION['user_info']['stake_level'];
if($stakeLevel == 'STATE')
{
    $remarks_prev_fetch = $db->fetch_table(" SELECT forw.forwarding_id_pk as forwarding_id_pk, forw.remarks as remarks, forw.submitted_on, forw.officer_info, ipdm.designation , forw.for_app_rej as for_app_rej, forw.to_officer_id_const , forw.msg_file , master.officer_name as officer_name, master.login_level_stake, master.stake_user_code FROM intra_pri_forwarding as forw
						LEFT JOIN intra_pri_master as master ON master.officer_id_const = forw.from_officer_id_const
						LEFT JOIN intra_pri_designation_master as ipdm ON master.stake_level_code = ipdm.designation_code
							WHERE forw.status='2' AND forw.application_id='".$data[0]['application_id']."' ORDER BY forw.forwarding_id_pk ASC ");
    $remarksHtml = array();
    $remarksHtml[] = '<div class="segment">';
    $remarksHtml[] = '<h4 style="background-color: #ffffff;color: #000;text-align: center;">NoteSheet</h4>';
    
    foreach($remarks_prev_fetch as $remarks)
    {
		if($remarks['for_app_rej'] == 'MR'){ $status = "May be Rejected";}
		else if($remarks['for_app_rej'] == 'MF'){ $status = "May be Approved";}
		else if($remarks['for_app_rej'] == 'FA'){ $status = "Forwarded for onward transmission";}
		else if($remarks['for_app_rej'] == 'FR'){ $status = "Forwarded for backward transmission";}
		else if($remarks['for_app_rej'] == 'FD'){ $status = "Following Documents required";}
		else if($remarks['for_app_rej'] == 'RS'){ $status = "Returned for Re-submission";}
		else if($remarks['for_app_rej'] == 'A'){ $status = "Approved";}
		else if($remarks['for_app_rej'] == 'R'){ $status = "Rejected";}    	
       $remarksHtml[] = '<div style="font-size: 11px;vertical-align: bottom; border:1px solid #c6c6c6; padding-bottom:10px;">';
       $remarksHtml[] = '
						
				<div style="background-color: #3E9B96;color: #FFF;text-align: left;">
					<span  style="color: #ffffff;font-weight: bold;height: 22px;font-family: "Arial Narrow";font-style: italic;">Comments of '.$remarks['officer_info'].'</span>
					<span style="text-align:right; float:right">Date:'.$remarks['submitted_on'].'</span>
				</div>
				<div style="color: #1F6377;font-weight: bold;font-family: "Arial Narrow";font-style: italic;">
				'.$remarks['remarks'].'
				<p>Status:'.$status.'</p>
				</div>';
				$remarksHtml[] = '</div>';
    }
    
    $remarksHtml[] = '</div>';
    $remarksHtml = implode('',$remarksHtml);
    //print($remarksHtml); exit;
}

				
//print_r($remarks_prev_fetch); exit;

$application_date=$data[0]['application_date'];
	 $date_submision_inquery=$data[0]['date_submision_inquery']; 
	$divorce_decree=$data[0]['divorce_decree'];
    $divorce_decree_remarks=$data[0]['divorce_decree_remarks'];
	$candidate_unfit_cer=$data[0]['candidate_unfit_cer'];
    $candidate_unfit_cer_remarks=$data[0]['candidate_unfit_cer_remarks'];
	$candidate_p_roforma=$data[0]['candidate_p_roforma'];
    $candidate_p_roforma_remarks=$data[0]['candidate_p_roforma_remarks'];
	
	  $employee_type=$data[0]['employee_type'];
    $application_id=$data[0]['application_id'];
    $emp_id_const=$data[0]['emp_id_const'];
    $nomine_details=$data[0]['nomine_details']; 
    
    $emp_first_name=$data[0]['emp_first_name'];
    $emp_second_name=$data[0]['emp_second_name'];
    $emp_last_name=$data[0]['emp_last_name'];
    $emp_desig=$data[0]['emp_desig'];
    $gp_id_fk=$data[0]['gp_id_fk'];
    $ps_id_fk=$data[0]['ps_id_fk'];
    $zp_id_fk=$data[0]['zp_id_fk'];
    $emp_first_join_date=$data[0]['emp_first_join_date'];
    $group=$data[0]['emp_group'];
    $last_pay=$data[0]['last_pay_drawn'];
    $death_date=$data[0]['death_date'];
	$premature_date=$data[0]['premature_date'];
    $applicant_name=$data[0]['applicant_name'];
    $applicant_relation=$data[0]['relation_employee'];
    $applicant_birth=$data[0]['applicant_birth'];
    $applicant_sex=$data[0]['applicant_sex']; 
    $nationality=$data[0]['nationality'];
    $applicant_mobile_no=$data[0]['applicant_mobile_no'];
    $applicant_religion=$data[0]['applicant_religion']; 
    $applicant_quali=$data[0]['applicant_quali'];
    $applicant_cast=$data[0]['applicant_cast'];
    $differently_able=$data[0]['differently_able']; 
   $present_address_state=$data[0]['present_address_state'];
    $present_house_no=$data[0]['present_house_no'];
    $present_street=$data[0]['present_street'];
    $present_town_vill=$data[0]['present_town_vill'];
    $present_post_office=$data[0]['present_post_office'];
    $present_pin=$data[0]['present_pin'];
    $present_police_station=$data[0]['present_police_station'];
    $present_city_district=$data[0]['present_district'];
    $family_pension=$data[0]['family_pension'];
    $death_gratuity=$data[0]['death_gratuity'];
    $group_insurance=$data[0]['group_insurance'];
    $encashment_leave=$data[0]['encashment_leave'];
    $any_payment=$data[0]['any_payment'];
    $total_lumsum=$data[0]['total_lumsum'];
    $calculation_hospitalization=$data[0]['calculation_hospitalization'];
    $caluculation_expen=$data[0]['caluculation_expen'];
    $interest_calculation=$data[0]['interest_calculation'];
    $move_immovable=$data[0]['move_immovable'];
    $income_dependant_employee=$data[0]['income_dependant_employee'];
    $total_income=$data[0]['total_income'];
    $percentage_monthly_income=$data[0]['percentage_monthly_income'];
    $name_officer_first=$data[0]['name_officer_first'];
    $name_officer_second=$data[0]['name_officer_second'];
    $name_officer_third=$data[0]['name_officer_third'];
	
	$name_designation_first=$data[0]['name_designation_first'];
    $name_designation_second=$data[0]['name_designation_second'];
    $name_designation_third=$data[0]['name_designation_third'];
    $memo_no_ec=$data[0]['memo_no_ec'];
    $memo_date_ec=$data[0]['memo_date_ec'];
    $date_inquery=$data[0]['date_inquery']; 
    $comment_officer=$data[0]['comment_officer']; 
    $candidate_fulfil_rules=$data[0]['candidate_fulfil_rules'];
    $candidate_fulfil_remarks=$data[0]['candidate_fulfil_remarks']; 
    //$group='634';
    $clear_vacany_roster=$data[0]['clear_vacany_roster']; 
    $clear_vacany_roster_remarks=$data[0]['clear_vacany_roster_remarks'];
    $candidate_fulfil_all=$data[0]['candidate_fulfil_all'];  
    $candidate_fulfil_all_remarks=$data[0]['candidate_fulfil_all_remarks'];
    $candidate_enquiry_recommedation=$data[0]['candidate_enquiry_recommedation'];
    $candidate_enquiry_recommedation_remarks=$data[0]['candidate_enquiry_recommedation_remarks'];
    
    $candidate_any_relaxation=$data[0]['candidate_any_relaxation'];
    $candidate_any_relaxation_remarks=$data[0]['candidate_any_relaxation_remarks']; 
    
    $check_age_value=$data[0]['check_age_value'];
    $check_education_value=$data[0]['check_education_value']; 
    $proposal=$data[0]['proposal'];
    
	$note=$data[0]['note'];
	$histroy=$data[0]['histroy'];
	$candidate_part_from=$data[0]['candidate_part_from']; 
	$candidate_part_from_remarks=$data[0]['candidate_part_from_remarks'];
	$vist_sport_remarks=$data[0]['vist_sport_remarks'];
	$vist_sport=$data[0]['vist_sport'];
	$candidate_favour=$data[0]['candidate_favour'];
	$candidate_favour_remarks=$data[0]['candidate_favour_remarks'];
	$authenticat_hoo=$data[0]['authenticat_hoo']; 
	$authenticat_hoo_remarks=$data[0]['authenticat_hoo_remarks'];
$emp_name=$data[0]['emp_first_name'].'&nbsp;'.$data[0]['emp_second_name'].'&nbsp;'.$data[0]['emp_last_name'];
    if($gp_id_fk!='0')
    {
    
    $db=new database();
    
    $id =$db->fetch_table("SELECT district_name FROM prd_location_master_district as d
    
    inner join prd_location_master_block as b on b.district_id_fk=d.district_id_pk
    inner join prd_location_master_gp as gp on gp.block_id_fk=b.block_id_pk 
    WHERE gp.gp_id_pk='".$gp_id_fk."' ");
    
    
    
    }
    
    else if($ps_id_fk!='0')
    {
    $id =$db->fetch_table("SELECT district_name FROM prd_location_master_district as d
    
    inner join prd_location_master_panchayat_samiti as p on p.district_id_fk=d.district_id_pk
    
    WHERE p.ps_id_pk='".$ps_id_fk."' ");
    }
    
    
 
    
    
    $db = new database();
    $arr_desig = $db->fetch_table("select code,description from prd_dise_code_master where length(code)=4 and code like '12%' and code in('1210','1211','1202','1203','1204','1206','1209','1212') order by code");
    
    $arr_relation = $db->fetch_table("select code,description from intra_pri_cg_relation_master where status='1' order by code");
	$arr_de = $db->fetch_table("select code,description from intra_pri_cg_relation_master where status='3' order by code");

$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");



    function dateshow($dateval)
    {	
    
    //echo $dateval;
    $date=substr($dateval,0,10);
    //return $date;
    $datearr=explode('-',$date);
    $dob= $datearr['2'].'-'.$datearr['1'].'-'.$datearr['0'];
    return $dob=='--'?'':$dob;
    }
   
    
    function code_gp($val)
    {
    
    //echo 222; 
    $db=new database();
    
    $arr =$db->fetch_table("SELECT gp_id_pk, gp_name  FROM prd_location_master_gp WHERE gp_id_pk='".$val."'");
    return $arr[0]['gp_name'];																	
                                                    
    }
    
    function code_block($val)
    {
    
  
    $db=new database();
    
    $arr =$db->fetch_table("SELECT block_name
    FROM prd_location_master_block as b
    inner join prd_location_master_gp as gp 
    on gp.block_id_fk=b.block_id_pk
    WHERE gp.gp_id_pk='".$val."'");
    return $arr[0]['block_name'];																	
                                                    
    }
    function code_ps($val)
    {
    
    //echo 222; 
    $db=new database();
    
    $arr =$db->fetch_table("SELECT ps_id_pk,ps_name FROM prd_location_master_panchayat_samiti WHERE ps_id_pk ='".$val."'");
    return $arr[0]['ps_name'];																	
                                                    
    }
	
	function desig_code($val)
    {
    
    //echo 222; 
    $db=new database();
    
	
	$arr = $db->fetch_table("select code,description from prd_dise_code_master where code in('1114','1115','1116','1117','1118','1119','1120','1122','1123','9001','9002','9003','9004','9005','9006','9007','9008','9009','9010','9011')  where code='".$val."'order by code");
    
    return $arr[0]['description'];
	return $arr[0]['code'];																		
                                                    
    }
	
	
	function fun_d($val)
    {
    
    //echo 222; 
    $db=new database();
    
    $arr =$db->fetch_table("select code,description from intra_pri_cg_relation_master where status='2' and  code ='".$val."'");
    return $arr[0]['description'];																	
                                                    
    }
	
	function fun_dd($val)
    {
    
    //echo 222; 
    $db=new database();
    
    $arr =$db->fetch_table("select code,description from intra_pri_cg_relation_master where status='1' and  code ='".$val."'");
    return $arr[0]['description'];																	
                                                    
    }
	
	function fun_ddd($val)
    {
    
    //echo 222; 
    $db=new database();
    
    $arr =$db->fetch_table("select code,description from intra_pri_cg_relation_master where status='3' and  code ='".$val."'");
    return $arr[0]['description'];																	
                                                    
    }
    
    function code_district($val)
    {
    
    //echo 222; 
    $db=new database();
    
    $arr =$db->fetch_table("SELECT district_id_pk ,district_name  FROM prd_location_master_district WHERE district_id_pk ='".$val."'");
    return $arr[0]['district_name'];																	
                                                    
    }
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
			
		$Land_Tel_No==$emp_data[0]['emp_land_no'];
	
	}
	
	if($emp_data[0]['emp_desig_first_app']=='1114' ||$emp_data[0]['emp_desig_first_app']=='1115'|| $emp_data[0]['emp_desig_first_app']=='1118')
	{
		$emp_desig_first_app='GP&nbsp;'.fun_common($emp_data[0]['emp_desig_first_app'],$code_data);
	}
	else
	{
		$emp_desig_first_app=fun_common($emp_data[0]['emp_desig_first_app'],$code_data);
	}
		
		
		
 		if($differently_able=='1')
		{
			$differently_able_status='YES';
			
		}
		else
		{
			$differently_able_status='NO';
		}
			
			if($candidate_p_roforma=='1')
			{
			$candidate_p_roforma_status='YES';
			
			}
			else
			{
			$candidate_p_roforma_status='NO';
			}
			
			if($divorce_decree=='1')
			{
			$divorce_decree_status='YES';
			
			}
			else
			{
			$divorce_decree_status='NO';
			}
			
			
			
			
			if($candidate_enquiry_recommedation=='1')
			{
			$candidate_enquiry_recommedation_status='YES';
			
			}
			else
			{
			$candidate_enquiry_recommedation_status='NO';
			}
			
			
			if($candidate_part_from=='1')
			{
			$candidate_part_from_status='YES';
			
			}
			else
			{
			$candidate_part_from_status='NO';
			}
			
			
			
			if($vist_sport=='1')
			{
			$vist_sport_status='YES';
			
			}
			else
			{
			$vist_sport_status='NO';
			}
			
			if($candidate_favour=='1')
			{
			$candidate_favour_status='YES';
			
			}
			else
			{
			$candidate_favour_status='NO';
			}
			
			if($candidate_any_relaxation=='1')
			{
			$candidate_any_relaxation_status='YES';
			
			}
			else
			{
			$candidate_any_relaxation_status='NO';
			}
			
			if($candidate_fulfil_rules=='1')
			{
			$candidate_fulfil_rules_status='YES';
			
			}
			else
			{
			$candidate_fulfil_rules_status='NO';
			}
			
			
			if($authenticat_hoo=='1')
			{
			$authenticat_hoo_status='YES';
			
			}
			else
			{
			$authenticat_hoo_status='NO';
			}
			
			if($clear_vacany_roster=='1')
			{
			$clear_vacany_roster_status='YES';
			
			}
			else
			{
			$clear_vacany_roster_status='NO';
			}
			
			if($candidate_fulfil_all=='1')
			{
			$candidate_fulfil_all_status='YES';
			
			}
			else
			{
			$candidate_fulfil_all_status='NO';
			}
			
			
			
			
			
//

//print_r($emp_data);
//exit;
//------------------------------------------------------------------------------------------------------	
define("_MPDF_TEMP_PATH", '../../../locker/temp/');
include ('../../includes/third-party/mpdf/mpdf.php');
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
							
								
								<p class="department" style="font-size: 11px;margin: 0px;padding:0px;">Panchayats & Rural Development Department, GOVT. OF WB</p>
								
								<p><b>STAFF DETAILS</b></p>	
							</td>
							<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><img width="100" src="../../themes/default/image/iosms_logo.png" /></div></td>
							
						</tr>
					</table>
					
					');
                /*if($emp_data[0]['emp_id_const']=='0')
				{
						$mpdf->WriteHTML('<table align="right"><tr><td>
							
								<div align="right" style="color:#E38A3F;">[ WAITING FOR FINALIZE ]</div>
								
							</td>   </tr></table>
					',2);
				}*/
//Staff details (PRIMARY DETAILS OF EMPLOYEE)
$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;"> PART A( Details Of Deceased Employee )</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Employee ID :</td>
					<td style="width:25%;">'.$emp_id_const.'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">APPLICATION ID :</td>
					<td style="width:25%;">'.$application_id.'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Name :</td>
					<td style="width:25%;">'.$data[0]['emp_first_name'].' '.$data[0]['emp_second_name'].' '.$data[0]['emp_last_name'].'</td>
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Designation :</td>
					<td style="width:25%;">'.fun_common($emp_desig,$code_data).'</td>
					
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Date of First Joining in Service :</td>
					<td style="width:25%;">'.dateshow($emp_first_join_date).'</td>
				</tr>
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Place Of Posting In GP :</td>
					<td style="width:25%;">'.code_gp($gp_id_fk).'</td></tr>
			<tr>
			       <td class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Block Name :</td>
					<td style="width:25%;">'.code_block($gp_id_fk).'</td>
					
					<tr>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">District :</td>
					<td style="width:25%;">'.$id[0]['district_name'].'</td>
					</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">GROUP :</td>
					<td style="width:25%;">'.fun_common($group,$code_data).'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">EMPLOYEE TYPE :</td>
					<td style="width:25%;">'.fun_d($employee_type).'</td>
			<tr>
			
			<tr>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Last Pay Drawn(GROSS) :</td>
					<td style="width:25%;">'.$last_pay.'</td>
					</tr>
					
					<tr>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Nomine Details If Any :</td>
					<td style="width:25%;">'.$nomine_details.'</td>
					</tr>
					
					<tr>
					<td class="col3" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Date of Death :</td>
					<td style="width:25%;">'.dateshow($death_date).'</td>
					</tr>
		          
				  
				</tr>
				</table>',2);


$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;"> PART B( APPLICANT DEATILS )</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Name Of the Applicant :</td>
					<td style="width:25%;">'.$applicant_name.'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Relation With Employee:</td>
					<td style="width:25%;">'.fun_dd($applicant_relation).'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Applicant Date Of Birth :</td>
					<td style="width:25%;">'.dateshow($applicant_birth).'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Gender:</td>
					<td style="width:25%;">'.fun_common($applicant_sex,$code_data).'</td>
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Nationality :</td>
					<td style="width:25%;">'.fun_common($nationality,$code_data).'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Applicant Mobile No:</td>
					<td style="width:25%;">'.$applicant_mobile_no.'</td>
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Religion :</td>
					<td style="width:25%;">'.fun_common($applicant_religion,$code_data).'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Educational Qualification:</td>
					<td style="width:25%;">'.fun_common($applicant_quali,$code_data).'</td>
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Caste :</td>
					<td style="width:25%;">'.fun_common($applicant_cast,$code_data).'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether Differently Able:</td>
					<td style="width:25%;">'.$differently_able_status.'</td>
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether application has been submitted in Prescribed Proforma in terms of 251-Emp dated 03.12.2013 and Subsequent relevant Order(Yes/No) :</td>
					<td style="width:25%;">'.$candidate_p_roforma_status.'</td>',2);
					
					 if($candidate_p_roforma=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether Differently Able:</td>
					<td style="width:25%;">'.$candidate_p_roforma_remarks.'</td>',2);
					}
					
					
				$mpdf->WriteHTML('</tr>
				
				<tr></tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether Divorce Decree has been submitted by the applicant who obtained such decree before or after the death of the ex-employee ( in case of divorced daughter) :</td>
					<td style="width:25%;">'.$divorce_decree_status.'</td>',2);
					
					 if($divorce_decree=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether Differently Able:</td>
					<td style="width:25%;">'.$divorce_decree_remarks.'</td>',2);
					}
		          
				  $mpdf->WriteHTML('</tr>
				  
				  <tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Date of submission of Proforma application:</td>
					<td style="width:25%;">'.dateshow($application_date).'</td>
					
				</tr>
				
				
				<h5 style="margin: 0px;padding: 0px;text-transform: uppercase;">Present Address</h5>
				<h5 style="margin: 0px;padding: 0px;text-transform: uppercase;">APPLICANT RESIDENTIAL ADDRESS</h5>
				
				
				 <tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">State:</td>
					<td style="width:25%;">WEST BENGAL</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Police Station:</td>
					<td style="width:25%;">'.$present_police_station.'</td>
					
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">House No:</td>
					<td style="width:25%;">'.$present_house_no.'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Street:</td>
					<td style="width:25%;">'.$present_street.'</td>
					
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Town/ Village:</td>
					<td style="width:25%;">'.$present_town_vill.'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Post Office:</td>
					<td style="width:25%;">'.$present_post_office.'</td>
					
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">PIN:</td>
					<td style="width:25%;">'.$present_pin.'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">District :</td>
					<td style="width:25%;">'.code_district($present_city_district).'</td>
					
				</tr>
				
				</table>',2);
				
				
				
				
				
				$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">PART C( FINANCIAL STATEMENT AS PER OFFICE RECORD & APPLICANT )</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Family Pension(in RS) :</td>
					<td style="width:25%;">'.$family_pension.'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Death Gratuity(in RS) :</td>
					<td style="width:25%;">'.$death_gratuity.'</td>
				
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Group Insurance(in RS):</td>
					<td style="width:25%;">'.$group_insurance.'</td>
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Encashment Of Leave(in RS) :</td>
					<td style="width:25%;">'.$encashment_leave.'</td>
				
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Disclosed Source of Income(in RS):</td>
					<td style="width:25%;">'.$any_payment.'</td>
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">TOTAL(in RS):</td>
					<td style="width:25%;">'.$total_lumsum.'</td>
				</tr>
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Expenses incurred on account of hospitalization(in RS) :</td>
					<td style="width:25%;">'.$calculation_hospitalization.'</td>
				
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Amount to be considered for monthly income(in RS):</td>
					<td style="width:25%;">'.$caluculation_expen.'</td>
				</tr>
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Monthly Interest Calculation(@8%)</td>
					<td style="width:25%;">'.$interest_calculation.'</td>
				</tr>
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Monthly income from Other movable Or immovable(in RS)</td>
					<td style="width:25%;">'.$move_immovable.'</td>
				
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Monthly income from dependant of the ex-employee if any(in RS)</td>
					<td style="width:25%;">'.$income_dependant_employee.'</td>
				</tr>
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Total Monthly Income Of the Family(in RS)</td>
					<td style="width:25%;">'.$total_income.'</td>
				</tr>
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Last Pay Drawn(GROSS)</td>
					<td style="width:25%;">'.$last_pay.'</td>
				
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Percentage Of total monthly income in realtion to Gross Salary(%)</td>
					<td style="width:25%;">'.$percentage_monthly_income.'</td>
				</tr>

				
		          
				  
				</tr>
				</table>',2);
				
				
				
				
				
								$mpdf->WriteHTML('<div class="segment">
						<h4 style="background-color: #3E9B96;color: #FFF;text-align: center;">PART D( DETAILS OF 3-MEN E.C REPORT )</h4>
						<table border="0" width="100%" style="font-size: 11px;vertical-align: bottom;">
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">1st Office Member OF E.C</td>
					<td style="width:25%;">'.$name_officer_first.'</td>
					
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">1st Office Member OF E.C Designation:</td>
					<td style="width:25%;">'.$name_designation_first.'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">2nd Office Member OF E.C:</td>
					<td style="width:25%;">'.$name_officer_second.'</td>
				
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">2st Office Member OF E.C Designation:</td>
					<td style="width:25%;">'.$name_designation_second.'</td>
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">3rd Office Member OF E.C:</td>
					<td style="width:25%;">'.$name_officer_third.'</td>
				
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">3rd Office Member OF E.C Designation:</td>
					<td style="width:25%;">'.$name_designation_third.'</td>
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Memo no of formation of 3 MEN E.C:</td>
					<td style="width:25%;">'.$memo_no_ec.'</td>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Memo Date of formation of 3 MEN E.C :</td>
					<td style="width:25%;">'.dateshow($memo_date_ec).'</td>
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Date of inquery:</td>
					<td style="width:25%;">'.dateshow($date_inquery).'</td>
					
				</tr>
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether the Recommedation by the enquiry committe has been unanimous:</td>
					<td style="width:25%;">'.$candidate_enquiry_recommedation_status.'</td>',2);
				
					 if($candidate_enquiry_recommedation=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Remarks:</td>
					<td style="width:25%;">'.$candidate_enquiry_recommedation_remarks.'</td>',2);
					}
				$mpdf->WriteHTML('</tr>
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether the Applicant submited PART I and PART II application:</td>
					<td style="width:25%;">'.$candidate_part_from_status.'</td>',2);
				
					 if($candidate_part_from=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Remarks:</td>
					<td style="width:25%;">'.$candidate_part_from_remarks.'</td>',2);
					}
				$mpdf->WriteHTML('</tr>

				
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether the 3-MEN E.C Visit Applicant location:</td>
					<td style="width:25%;">'.$vist_sport_status.'</td>',2);
				
					 if($vist_sport=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Remarks:</td>
					<td style="width:25%;">'.$vist_sport_remarks.'</td>',2);
					}
				$mpdf->WriteHTML('</tr>

		          
				  
				  
				  <tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether Recommended for the Employment in favour of Applicant:</td>
					<td style="width:25%;">'.$candidate_favour_status.'</td>',2);
				
					 if($candidate_favour=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Remarks:</td>
					<td style="width:25%;">'.$candidate_favour_remarks.'</td>',2);
					}
				$mpdf->WriteHTML('</tr>
				<tr>
				  <td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Date of Submission inquery report:</td>
					<td style="width:25%;">'.dateshow($date_submision_inquery).'</td>
				  
				</tr>
				
				<tr>
				  <td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Comments Of Controlling Officer:</td>
					<td style="width:25%;">'.$comment_officer.'</td>
				  
				</tr>
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Is any relaxation of rule etc. required:</td>
					<td style="width:25%;">'.$candidate_any_relaxation_status.'</td>',2);
				
					 if($candidate_any_relaxation=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Remarks:</td>
					<td style="width:25%;">'.$candidate_any_relaxation_remarks.'</td>',2);
					}
				$mpdf->WriteHTML('</tr>
				
				
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether the candidate fulfils the requirement of Recruitment Rules for the Post:</td>
					<td style="width:25%;">'.$candidate_fulfil_rules_status.'</td>',2);
				
					 if($candidate_fulfil_rules=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Remarks:</td>
					<td style="width:25%;">'.$candidate_fulfil_remarks.'</td>',2);
					}
				$mpdf->WriteHTML('</tr>
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Summary sheet duly authenticated by HOO:</td>
					<td style="width:25%;">'.$authenticat_hoo_status.'</td>',2);
				
					 if($authenticat_hoo=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Remarks:</td>
					<td style="width:25%;">'.$authenticat_hoo_remarks.'</td>',2);
					}
				$mpdf->WriteHTML('</tr>
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether a clear Vacancy on Examted category is available as per 100 pointer Roster vide Notification No. 50-EMP dated 01.03.2011</td>
					<td style="width:25%;">'.$clear_vacany_roster_status.'</td>',2);
				
					 if($clear_vacany_roster=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Remarks:</td>
					<td style="width:25%;">'.$clear_vacany_roster_remarks.'</td>',2);
					}
				$mpdf->WriteHTML('</tr>
				
				
				<tr>
					<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Whether the applicant has fulfiled all the criteria as laid down in Notification No.251-EMP dated 03.12.2013 read with 26-EMP dated 01.03.2016</td>
					<td style="width:25%;">'.$candidate_fulfil_all_status.'</td>',2);
				
					 if($candidate_fulfil_all=='0')
					{
						$mpdf->WriteHTML('<td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Remarks:</td>
					<td style="width:25%;">'.$candidate_fulfil_all_remarks.'</td>',2);
					}
				$mpdf->WriteHTML('</tr>
				
				
				<tr>
				  <td  class="col1" style="color: #1F6377;font-weight: bold;width: 25%;font-family: "Arial Narrow";font-style: italic;">Coments of the Appointment authority:</td>
					<td style="width:25%;">'.$note.'</td>
				  
				</tr>
				
				
				</table>',2);
				
				$mpdf->WriteHTML($remarksHtml,2);


$mpdf->Output($data[0]['stake_level'].' '.$data[0]['emp_second_name'].' '.$data[0]['emp_last_name'].'.pdf','I');

//$mpdf->Output($emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].' ('. $emp_data[0]['emp_system_code'].').pdf','D');

?>