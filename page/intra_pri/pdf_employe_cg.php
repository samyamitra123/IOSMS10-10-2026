

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


$emp_data=$db->fetch_table("select * from intra_pri_cg_profile_master where emp_id_const='".$cryptoGraph->decode($_REQUEST['emp_id_const'],4)."'");
$emp_name=$emp_data[0]['emp_first_name'].'&nbsp;'.$emp_data[0]['emp_second_name'].'&nbsp;'.$emp_data[0]['emp_last_name'];


if($emp_data[0]['candidate_p_roforma']=='1')
{
	$candidate_p_roforma='YES';
}
else
{
	$candidate_p_roforma='NO';
	

}
if($emp_data[0]['candidate_unfit_cer']=='1')
{
	$candidate_unfit_cer='YES';
}
else
{
	$candidate_unfit_cer='NO';
	

}

if($emp_data[0]['divorce_decree']=='1')
{
	$divorce_decree='YES';
}
else
{
	$divorce_decree='NO';
	

}

if($emp_data[0]['candidate_enquiry_recommedation']=='1')
{
	$candidate_enquiry_recommedation='YES';
}
else
{
	$candidate_enquiry_recommedation='NO';
	

}

if($emp_data[0]['candidate_fulfil_rules']=='1')
{
	$candidate_fulfil_rules='YES';
}
else
{
	$candidate_fulfil_rules='NO';
	

}
if($emp_data[0]['candidate_any_relaxation']=='1')
{
	$candidate_any_relaxation='YES';
}
else
{
	$candidate_any_relaxation='NO';
	

}
if($emp_data[0]['candidate_any_relaxation']=='1')
{


if($emp_data[0]['check_age_value']=='1')
{
	$check_age_value='AGE';
}
else
{
	$check_age_value='Education & Qualification';
	

}
}


if($emp_data[0]['clear_vacany_roster']=='1')
{
	$clear_vacany_roster='YES';
}
else
{
	$clear_vacany_roster='NO';
	

}

if($emp_data[0]['candidate_fulfil_all']=='1')
{
	$candidate_fulfil_all='YES';
}
else
{
	$candidate_fulfil_all='NO';
	

}

if($emp_data[0]['employee_type']=='1993')
{
	$last_date_of_service=date("d-m-Y",strtotime($emp_data[0]['death_date']));
}
else
{
	$last_date_of_service=date("d-m-Y",strtotime($emp_data[0]['premature_date']));
	

}







//$candidate_enquiry_recommedation


/*foreach($emp_data as $item){
	print_r($item);
}
*/


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
$mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_level'].'|{PAGENO}');
function fun_relastion($code)
	{
		$db = new database();
		$data = $db->fetch_table(" SELECT  description FROM intra_pri_cg_relation_master where code='".$code."'");
		return $data[0]['description'];
	}


$mpdf->WriteHTML('
					
					
					<table>
						<tr>
							<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"></div></td>
							<td class="he2" style="text-align: center;width: 480px;">
							
							
								<p class="department" style="font-size: 11px;margin: 0px;padding:0px;">Common Check List to be used for 
processing an application for employment on 
compassionate ground in die-in-harness/ 
retired incapacitated cases
</p>
								
								<p><b>STAFF DETAILS</b></p>
									
								<h3 class="school" style="color: #008200;font-size: 24px;">'.$emp_name.'</h3>
							</td>
							<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><img width="100" src="../../themes/default/image/iosms_logo.png" /></div></td>
							
						</tr>
					</table>
					
					');
             
			 
			 
			 
$mpdf->WriteHTML('
					<h1 class="school" style="color: #008200;font-size: 24px;">PART I</h1>
					
					<table  border="1" solid>
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;">Sl NO</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Item</td>
							<td class="he1" style="width: 300px;vertical-align: top;">Remarks</td>
						</tr>
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;">1.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Name of the Deceased / retired employee and date of death/incapacitation</td>
							<td class="he1" style="width: 300px;vertical-align: top;">'.$emp_name.'</td>
						</tr>
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;">2.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Office and last served as</td>
							<td class="he1" style="width: 300px;vertical-align: top;">'.$last_date_of_service.'</td>
						</tr>
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;">3.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Name of the applicant</td>
							<td class="he1" style="width: 300px;vertical-align: top;">'.$emp_data[0]['applicant_name'].'</td>
						</tr>
						
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;">4.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Relatonship with the Goverment employee and whether the applicant falls within the defination of dependant as per the order No.251-EMP,dated 03.12.2013 read with 26-EMP dated 01.03.2016</td>
		<td class="he1" style="width: 300px;vertical-align: top;">'.fun_relastion($emp_data[0]['relation_employee']).'</td>
						</tr>
						
						
						
						
						
						
						
						
						
						
						
						<tr>
						
							<td rowspan="2" class="he1" style="width: 20px;vertical-align: top;">5.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">(A) whether application has been submited in Prescribed Proforma in terms of 251-EMP dated 03.12.2013(YES/NO)</td>
							
							
							<td class="he1" style="width: 300px;vertical-align: top;">'.$candidate_p_roforma.'</td>
							
							
						</tr>
						
						
						
					<tr>
						<td>(B)	Date of submission of Proforma application</td>
						<td>'.date("d-m-Y",strtotime($emp_data[0]['application_date'])).'</td>
					</tr>	
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">6.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Whether the application submitted by one dependant has been concurred upon by other family members/dependants in writing</td>
							
							<td class="he1" style="width: 300px;vertical-align: top;"></td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">7.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Whether Medically Unfit Certificate has been submitted from the Competent Authority(in case of premature retirement due to permanent incapaction) </td>
							
							<td class="he1" style="width: 300px;vertical-align: top;">'.$candidate_unfit_cer.'</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">8.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Whether Divorce Decree has been submitted by the applicant who obtained such decree before or after the death of the ex-employee (in case of divorced daughter) </td>
							
							<td class="he1" style="width: 300px;vertical-align: top;">'.$divorce_decree.'</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">9.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Gross monthly salary drawn by the deceased/ incapacitated employee immediately before death/ premature retirement:-
Basic Pay, Dearness Pay (if any), Dearness Allowance, H.R.A., Medical Allowance as per C1.6) of 251-Emp dated 03.12.2013
 </td>
							
							<td class="he1" style="width: 300px;vertical-align: top;">RS/-'.$emp_data[0]['last_pay_drawn'].'</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">10.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Family Pension sanctioned/ entitled</td>
							
							<td class="he1" style="width: 300px;vertical-align: top;">RS/-'.$emp_data[0]['family_pension'].'</td>
							
							
						</tr>
						
						
						<tr>
						
							<td rowspan="6" class="he1" style="width: 20px;vertical-align: top;">11.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Lump sum terminal dues/ entitlement (GPF not to be included):
Ref: Cl. 6(a)(II) of 251-Emp, dated 03.12.2013
)</td>
							
							
							<td class="he1" style="width: 300px;vertical-align: top;"></td>
							
							
						</tr>
						
						
						
					<tr>
						<td>(A)	Death Gratuity</td>
						<td>RS/-'.$emp_data[0]['death_gratuity'].'</td>
					</tr>	
					<tr>
						<td>(B)Group Insurance</td>
						<td>RS/-'.$emp_data[0]['group_insurance'].'</td>
					</tr>
					<tr>
						<td>(C)	Encashment of leave</td>
						<td>RS/-'.$emp_data[0]['encashment_leave'].'</td>
					</tr>
					<tr>
						<td>(D)	Any other payments received (give details)</td>
						<td>RS/-'.$emp_data[0]['any_payment'].'</td>
					</tr>
					<tr>
						<td>(E)	Total</td>
						<td>RS/-'.$emp_data[0]['total_lumsum'].'</td>
					</tr>	
						
						
						
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">12.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Expenses incurred on account of hospitalization to be supported by the payment vouchers etc.</td>
							
							<td>RS/-'.$emp_data[0]['calculation_hospitalization'].'</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">13.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Amount to be considered for monthly income: Ref: Cl. 6 of 251-Emp, dated 03.12.2013</td>
							
							<td class="he1" style="width: 300px;vertical-align: top;">RS/-'.$emp_data[0]['caluculation_expen'].'</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">14.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Monthly interest income @8% per annum on amount at Sl. No.11</td>
							
							<td class="he1" style="width: 300px;vertical-align: top;">'.$emp_data[0]['interest_calculation'].'%</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">15.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Monthly	income	from	other	movable	or
immovable property</td>
							
							<td class="he1" style="width: 300px;vertical-align: top;">RS/-'.$emp_data[0]['move_immovable'].'</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">16.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Monthly income from the dependants of the ex-employee if any, supported by declaration from the family members</td>
							
							<td class="he1" style="width: 300px;vertical-align: top;">RS/-'.$emp_data[0]['income_dependant_employee'].'</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">17.</td>
							<td class="he1" style="width: 500px;vertical-align: top;">Total	monthly	income	of	the	family
(10+14+15+16)
</td>
							
							<td class="he1" style="width: 300px;vertical-align: top;">RS/-'.$emp_data[0]['total_income'].'</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">18.</td>
							
							
							<td class="he1" style="width: 500px;vertical-align: top;">Percentage of total monthly income at Si. No. 17
.
in relation to gross salary at Sl. No.9

</td>
					
							
							<td class="he1" style="width: 300px;vertical-align: top;">RS/-'.$emp_data[0]['percentage_monthly_income'].'</td>
							
							
						</tr>
						
						
						<tr>
						
							<td rowspan="2"  style="width: 20px;vertical-align: top;">19.</td>
							
							
							<td class="he1" style="width: 500px;vertical-align: top;">(A)	Memo No. & Date of formation of three men Screening-cum-Enquiry Committee

</td>
					
							
							<td class="he1" style="width: 300px;vertical-align: top;">Memo No-'.$emp_data[0]['memo_no_ec'].'  Memo Date-'.date("d-m-Y",strtotime($emp_data[0]['memo_date_ec'])).'</td>
							
							
						</tr>
						
							
					<tr>
						<td>(B)	Date(s) of inquiry</td>
						<td>'.date("d-m-Y",strtotime($emp_data[0]['date_inquery'])).'</td>
					</tr>
					
					
					<tr>
						
							<td rowspan="2"  style="width: 20px;vertical-align: top;">20.</td>
							
							
							<td class="he1" style="width: 500px;vertical-align: top;">(A)	Name of Enquiry Officers and the date of
submission of the	report by the committee


</td>
					
							
							<td class="he1" style="width: 300px;vertical-align: top;">First Officer Name-'.$emp_data[0]['name_officer_first'].' &nbsp;Scond Officer Name-'.$emp_data[0]['name_officer_first'].' &nbsp;Third Officer Name-'.$emp_data[0]['name_officer_third'].'</td>
							
							
						</tr>
						
							
					<tr>
						<td>(B)	Whether recommended for employment</td>
						<td>yyy</td>
					</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">21.</td>
							
							
							<td class="he1" style="width: 500px;vertical-align: top;">Whether the recommendation by the enquiry committee has been unanimous (Yes/No)

</td>
					
							
							<td class="he1" style="width: 300px;vertical-align: top;">'.$candidate_enquiry_recommedation.'</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">22.</td>
							
							
							<td class="he1" style="width: 500px;vertical-align: top;">Comments of Controlling Officer

</td>
					
							
							<td class="he1" style="width: 300px;vertical-align: top;">'.$emp_data[0]['comment_officer'].'</td>
							
							
						</tr>
						
						<tr>
						
							<td rowspan="2"  style="width: 20px;vertical-align: top;">23.</td>
							
							
							<td class="he1" style="width: 500px;vertical-align: top;">(A)	Whether	the	candidate	fulfils	the 
requirements of the Recruitment Rules for the post</td>
					
							
							<td class="he1" style="width: 300px;vertical-align: top;">'.$candidate_fulfil_rules.'</td>
							
							
						</tr>
						
						<tr>
						<td>(B)Is any relaxation of rule etc. required</td>
						<td>'.$candidate_any_relaxation.'('.$check_age_value.')</td>
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">24.</td>
							
							
							<td class="he1" style="width: 500px;vertical-align: top;">Whether a clear vacancy is available as per 100 Point Roster vide Notification No. 50-Emp dated 01.03.2011

</td>
					
							
							<td class="he1" style="width: 300px;vertical-align: top;">'.$clear_vacany_roster.'</td>
							
							
						</tr>
						
						<tr>
						
							<td class="he1" style="width: 20px;vertical-align: top;">25.</td>
							
							
							<td class="he1" style="width: 500px;vertical-align: top;">Whether the applicant has fulfilled all the criteria 
as laid down in Notification No. 251-Emp dated 
03.12.2013 read with 26-Emp dated 01.03.2016


</td>
					
							
							<td class="he1" style="width: 300px;vertical-align: top;">'.$candidate_fulfil_all.'</td>
							
							
						</tr>
						
					</table>
					&nbsp;
					&nbsp;
					');




			
			
			
			
			
						 
			 
$mpdf->WriteHTML('
					<h1 class="school" style="color: #008200;font-size: 24px;">PART II</h1>
					
					<table  border="1" solid>
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;font-size: 24px;">Sl NO</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">Item</td>
							<td class="he1" style="width: 300px;vertical-align: top;font-size: 24px;">Requirement</td>
							<td class="he1" style="width: 300px;vertical-align: top;font-size: 24px;">Finding/actual 
position</td>
							

						</tr>
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;font-size: 24px;">1.</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">Percentage of total monthly income in relation in gross monthly salary</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">Below 90% (Cl. 6(a)(i) of 251-Emp dated 03.12.2013)</td>
							<td class="he1" style="width: 300px;vertical-align: top;font-size: 24px;"></td>
						</tr>
						
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;font-size: 24px;">2.</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">Relationship	with the deceased/
incapacitated
</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">(A)Wife/Husband/Son/Unmarried daughter/Divorced daughter/
(B) Dependent brother or sister in case of unmarried employee
(as per Para 3of 251-Emp, dated 03.12.2013 read with Para 3(dd) of 26-Emp dated 01.03.2016)
</td>
							<td class="he1" style="width: 300px;vertical-align: top;font-size: 24px;"></td>
						</tr>
						
						
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;font-size: 24px;">3.</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">Time	limit	for
submission	of 
application
</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">Within 2 Years of death/ incapacitation (para-10(a) under the Heading "TIMELINES" of 26- Emp, dated 01.03.2016) or within 5 years in the cases, comes under Cl 10(aa) of 26-Emp dated 01.03.2016 under the Heading "BELATED REQUESTS"
</td>
							<td class="he1" style="width: 300px;vertical-align: top;font-size: 24px;"></td>
						</tr>
						
					</table>
					&nbsp;
					&nbsp;
					');
					
					
					
					
					
					
					
					
					
					
					
					
					
					$mpdf->WriteHTML('
					<h1 class="school" style="color: #008200;font-size: 24px;">PART III</h1>
					
					<table  border="1" solid>
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;font-size: 24px;">Sl NO</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">Item</td>
							<td class="he1" style="width: 300px;vertical-align: top;font-size: 24px;">Requirement</td>
							<td class="he1" style="width: 300px;vertical-align: top;font-size: 24px;">Finding/actual 
position</td>
							

						</tr>
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;font-size: 24px;">1.</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">Whether fully exhausted 
all kinds of leave	(does
not	required	for	the
employees died-in-harness but mandatory for the employees retired prematurely due to permanent
incapacitation)
</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">YES</td>
							<td class="he1" style="width: 300px;vertical-align: top;font-size: 24px;"></td>
						</tr>
						
						<tr>
							<td class="he1" style="width: 20px;vertical-align: top;font-size: 24px;">2.</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">Whether 2 or more years 
of service is left (does not 
required	for	the
employees died-in-harness but mandatory for the employees retired prematurely due to permanent
incapacitation)

</td>
							<td class="he1" style="width: 500px;vertical-align: top;font-size: 24px;">YES
</td>
							<td class="he1" style="width: 300px;vertical-align: top;font-size: 24px;"></td>
						</tr>
						
						
					
						
					</table>
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;
					&nbsp;&nbsp;
					&nbsp;&nbsp;
					&nbsp;
					');
					
					
					
					
					
					$mpdf->WriteHTML('
					<h1 class="school" style="color: #008200;font-size: 24px;">PART IV</h1>
					
					<table  border="1" solid>
					
					<tr>
							<td class="he1" style="width: 200px;vertical-align: top;">1. Remarks of the Dealing Assistant</td>
							
							
							

						</tr>
						<tr>
							<td class="he1" style="width: 200px;vertical-align: top;">Checked and examined</td>
							
							
							

						</tr>
						
							
							
							

					
						
						
					
						
					</table>
					&nbsp;
					&nbsp;
					');
					
					
					
					
					
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			


$mpdf->Output($emp_data[0]['stake_level'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].'.pdf','I');

//$mpdf->Output($emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].' ('. $emp_data[0]['emp_system_code'].').pdf','I');




?>
<!--<td class="he1" style="width: 500px;vertical-align: top;">(B) Date of submission of Proforma application</td>
							
							<td class="he1" style="width: 300px;vertical-align: top;">aaaaa</td>-->
                            
                            <!--<th style="border-bottom:1px solid #666; border-right:1px solid #666; vertical-align:middle;">EARNING(Rs)</th>-->
