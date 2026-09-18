<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
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
//------------------------------------------------------------------------------------------------------
$cryptoGraph=new cryptography();




		$emp_id=$cryptoGraph->decode($_GET['emp_id'],4);
		$ps_id=$cryptoGraph->decode($_GET['ps_id'],4);
		$salary_monthyear=date("Ym",strtotime("-1 months")); 
		$trsfer_date=substr($trsfer_gp_id_fk[0]['transfer_date'],5,-3);
		//$prev_month = date("M",strtotime( "last month"));
		$prev_month = date("M",strtotime("previous month")); 
		$prev_dig_month = date("m",strtotime("previous month")); 
		$prev_year = date("Y",strtotime("previous year"));
		$emp_next_increment_date=date('d-m-Y', strtotime($trsfer_gp_id_fk[0]['emp_next_increment_date']));
		$emp_trsfer_date=date('d-m-Y', strtotime($trsfer_gp_id_fk[0]['transfer_date']));
		$trsfer_year=substr($trsfer_gp_id_fk[0]['transfer_date'],0,4); 
		
		if($prev_month=='Dec')
		{
			$lpc_m_y=$prev_dig_month.$prev_year;
			$lpc_month_year=$prev_month.' '.$prev_year;
		
		}
		else
		{
			$lpc_m_y=$prev_dig_month.date("Y");
			$lpc_month_year=$prev_month.' '.date("Y");
		
		}


$db=new database();
/*echo ("UPDATE 
								prd_employee_transfer 
							SET 
								lpc_status=1
							WHERE
								emp_id_fk='".$emp_id."' AND lpc_status=0 AND ps_id_fk='".$ps_id."' AND transfer_emp_status not in (2)
							"); die;*/
$transfer_update=$db->update("UPDATE 
								prd_employee_transfer 
							SET 
								lpc_status=1,lpc_monthyear=".$lpc_m_y."
							WHERE
								emp_id_fk='".$emp_id."' AND lpc_status=0 AND ps_id_fk='".$ps_id."' AND transfer_emp_status not in (2)
							");




if($transfer_update)

{	

	
			$emp_data=$db->fetch_table("select * from prd_employee_master where emp_id_pk='".$emp_id."'");
			
			$block=$db->fetch_table("select block_code, bdo_name, mobile_no, road_name, vill_name, 
			post_office, police_station, pin_code, email_id, contact_no, 
			tan_no from prd_block_profile where block_code='".$_SESSION['location']['block_code']."'");
			
	  
			$trsfer_gp_id_fk= $db->fetch_table("SELECT transfer_gp_id_fk,
			transfer_block_id_fk,
			transfer_district_id_fk,
			emp_pay_band,
			emp_pay_in_payband,
			emp_pay_scale,
			emp_grade_pay,
			transfer_date,
			emp_ifsc_no,
			emp_acc_no,	
			emp_id_const,
			interim_relief,
			transfer_ps_id_fk,gp_id_fk,emp_next_increment_date
			FROM prd_employee_transfer  where ps_id_fk='".$ps_id."' and emp_id_fk='".$emp_id."'");

 //echo $trsfer_gp_id_fk[0]['transfer_gp_id_fk']; die;

			$salary=$db->fetch_table("select  basic, da, hra, ma ,i_tax, 
			p_tax, pf_deduct,gsli from prd_employee_salary_save where salary_monthyear='".$salary_monthyear."' AND emp_id_fk='".$emp_id."'");



			function fun_gp($gp)
			{ 
				$db = new database();
				$data = $db->fetch_table("SELECT gp_name FROM  prd_location_master_gp  where gp_id_pk='".$gp."'");
				return $data[0]['gp_name'];
			}
			function fun_ps($p)
			{ 
				$db = new database();
				$data = $db->fetch_table("SELECT ps_name FROM  prd_location_master_panchayat_samiti  where ps_id_pk='".$p."'");
				return $data[0]['ps_name'];
			}
			function fun_block($block)
			{ 
				$db = new database();
				$data = $db->fetch_table("select block_name,block_id_pk
				from prd_location_master_block where block_id_pk='".$block."'");
				return $data[0]['block_name'];
			}
			function fun_block_name($block_code)
			{ 
				$db = new database();
				$data = $db->fetch_table("select block_name,block_id_pk
				from prd_location_master_block where block_code='".$block_code."'");
				return $data[0]['block_name'];
			}
			function fun_dis($dis)
			{ 
				$db = new database();
				//echo("SELECT gp_name FROM  prd_location_master_gp where gp_id_pk='".$gp_id."'");die;
				$data = $db->fetch_table("select district_code,district_name 
				from prd_location_master_district where district_id_pk='".$dis."'");
				return $data[0]['district_name'];
			}
			function fun_desig($dsig)
			{ 
			$db = new database();
				/*echo("select description,code 
				from prd_dise_code_master where code='".$dsig."'");die;*/
				$data = $db->fetch_table("select description,code 
				from prd_dise_code_master where code='".$dsig."'");
				return $data[0]['description'];
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
			include ('../../../../includes/third-party/mpdf/mpdf.php');
			$stylesheet ='';
			$mpdf=new mPDF();
			
			 		
			//echo 'Previous Month--'.$prev_month;
			//echo 'Current Month--'.date('M');die;
			
			$mpdf->WriteHTML($stylesheet,1);
			
			//Header and footer
			$mpdf->SetFooter('priemp.wbprd.gov.in|'.$_SESSION['user_info']['stake_user'].'|{PAGENO}');
			
			//PDF header
			$mpdf->WriteHTML('
			
			
			<table>
			<tr>
			<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"></div></td>
			<td class="he2" style="text-align: center;width: 480px;">
			
			<div class="logo" style="text-align: center;"><img width="38" src="../../../../themes/default/image/ashoka.jpg" /></div>
			<p class="department" style="font-size: 15px;margin: 0px;padding:0px;">Government of West Bengal</p>
			<p class="department" style="font-size: 15px;margin: 0px;padding:0px;">Office of the Block development officer</p>
			<p class="department" style="font-size: 15px;margin: 0px;padding:0px;">'.fun_block_name($_SESSION['location']['block_code']).','.$_SESSION['location']['district_name'].',PIN:'.$block[0]['pin_code'].'</p>
			
			<p class="department" style="font-size: 15px;margin: 0px;padding:0px;">Phone/Fax : '.$block[0]['mobile_no'].'::Email :<u>'.$block[0]['email_id'].'</u></p>
			</td>	
			</tr>
			
			</table><hr>',2);
			
			if($emp_data[0]['emp_id_const']=='0')
			{
			$mpdf->WriteHTML('<table align="right"><tr><td>
			
			<div align="right" style="color:#E38A3F;">[ WAITING FOR FINALIZE ]</div>
			
			</td>   </tr></table>
			',2);
			}
			
			
				if($employee_g_s_l_i=='')
			{
				 $employee_g_s_l_i='Nil';
				
			}
			else
			
			{
				$employee_g_s_l_i='Rs.'. $salary[0]['gsli'].'.00'; 
				
			}
			
			
			if ($trsfer_gp_id_fk[0]['transfer_ps_id_fk']!=0)
			{
			
			$g=fun_block_name($_SESSION['location']['block_code']) .'proceeding on to '.fun_ps($trsfer_gp_id_fk[0]['transfer_ps_id_fk']).',under '.fun_block($trsfer_gp_id_fk[0]['transfer_block_id_fk']).','.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']);
			
			}
			
			else
			{
			$g=fun_block_name($_SESSION['location']['block_code']) .' proceeding on to '.fun_gp($trsfer_gp_id_fk[0]['transfer_gp_id_fk']).',under '.fun_block($trsfer_gp_id_fk[0]['transfer_block_id_fk']).','.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']);
			}
			
			
			$mpdf->WriteHTML('<div class="segment">
			<br></br>
			<p class="department" style="font-size: 11px;margin: 0px;padding:0px;"><b>T.R.Form No.13 (See Sub-Rule(l) of T.R.4021) :</b></p>
			<p class="department" style="font-size: 15px;" align="center"><b><u>LAST PAY CERTIFICATE</u></b></p>
			
			<table border="0" width="100%"  style="vertical-align: bottom;font-size: 14px;border-collapse: collapse;">
			<tr>
			<td>1.	Last Pay Certificate of '.$trsfer_gp_id_fk[0]['emp_first_name'].' '.$trsfer_gp_id_fk[0]['emp_second_name'].' '.$trsfer_gp_id_fk[0]['emp_last_name'].', erstwhile Gram Panchayat '.fun_desig($trsfer_gp_id_fk[0]['emp_desig']).' of '.fun_ps($ps_id).' under '.$g.'  .</td></tr>
			</table>',2);
			
			
			$mpdf->WriteHTML(' 
			<table border="0" width="100%"  style="vertical-align: bottom;font-size: 14px;border-collapse: collapse;">
			<tr>
			<td>2.He has been paid up to '.$lpc_month_year.' at the following rates in the revised Pay Band
			of Rs.'.$trsfer_gp_id_fk[0]['emp_pay_in_payband'].'.00/-+ Grade Pay Rs.'.fun_grade_pay($trsfer_gp_id_fk[0]['emp_grade_pay']).'.00 ('.fun_payband($trsfer_gp_id_fk[0]['emp_pay_band']).').Scale:'.fun_payscale($trsfer_gp_id_fk[0]['emp_pay_scale']).'.
			
			</td>
			</tr>
			<tr>
			<td>3.He made over the Official Charges on '.$emp_trsfer_date.'.
			</td>
			</tr>
			</table>',2);
			
			$mpdf->WriteHTML('<div class="segment"><br></br>
			<table border="0" width="100%"  style="vertical-align: bottom;font-size: 14px;border-collapse: collapse;">
			
			<tr>
			<th  align="left" color="#263238;font-size: 18px;"><u>PARTICULARS :-</u></th>
			<th align="left" color="#263238;font-size: 18px;"><u>RATE OF DEDUCTION</u></th>
			</tr>
			
			<tr>
			<td style="width:50%">Band Pay:-Rs.'.$trsfer_gp_id_fk[0]['emp_pay_in_payband'].'.00</td>
			<td style="width:50%">A)P.F.Subscription:-Rs.'.$salary[0]['pf_deduct'].'.00</td>
			</tr>
			<tr>
			<td style="width:50%">Grade Pay:-Rs.'.fun_grade_pay($trsfer_gp_id_fk[0]['emp_grade_pay']).'.00</td>
			<td style="width:50%">B)Income Tax:-Rs.'.$salary[0]['i_tax'].'.00</td>
			</tr>
			<tr>
			<td style="width:50%">Basic Pay:-Rs.'.$salary[0]['basic'].'.00</td>
			<td style="width:50%">C)Profession Tax:-Rs.'.$salary[0]['p_tax'].'.00</td>
			</tr>
			<tr>
			<td style="width:50%">Special Pay:-</td>
			<td style="width:50%">D)G.S.L.I:-'. $employee_g_s_l_i.' </td>
			</tr>
			<tr>
			<td style="width:50%">Personal Pay:- --</td>
			<<td style="width:50%">E)loan  Recovery:- -- </td>
			</tr>
			<tr>
			<td style="width:50%">Leave Salary:- --</td>
			</tr>
			</table>',2);
			
			$mpdf->WriteHTML('<div class="segment"><br></br>
			<table border="0" width="100%"  style="vertical-align: bottom;font-size: 14px;border-collapse: collapse;">
			<tr>
			<th  align="left" color="#263238;font-size: 18px;"><u>ALLOWANCES:</u></th>
			</tr>
			<tr>
			<td style="width:50%">a) D.A./A.D.A :-Rs.'.$salary[0]['da'].'.00</td>
			</tr>
			<tr>
			<td style="width:50%">b) Medical Allowances:-Rs.'.$salary[0]['ma'].'.00</td>
			</tr>
			<tr>
			<td style="width:50%">c)H.R.A:-Rs.'.$salary[0]['hra'].'.00</td>
			</tr>
			<tr>
			<td style="width:50%">d)I.R:-Rs.'.$trsfer_gp_id_fk[0]['interim_relief'].'.00</td>
			</tr><br></br><br></br>
			<tr>
			<td style="width:50%">4.His <b>i-OSMS</b> Employee Code No:<b>'.$trsfer_gp_id_fk[0]['emp_id_const'].'</b></td>
			</tr>
			<tr>
			<td style="width:50%">5. His Permanent Salary Bank A/C No.:'.$trsfer_gp_id_fk[0]['emp_acc_no'].'.IFSC CODE:'.$trsfer_gp_id_fk[0]['emp_ifsc_no'].', He is entitled to draw the following:</td>
			</tr>
			<tr>
			<td style="width:50%">6.He has been sanctioned X Leave Proceeding joining time for X days.</td>
			</tr>
			<tr>
			<td style="width:50%">7.His date of next Increment is:.'.$emp_next_increment_date.'</td>
			</tr>
			<tr>
			<td style="width:50%">8.  He enjoyed casual leave for Current Calendar year '.date("Y").' :02(Two ) days.</td>
			</tr>
			<br><br><br></br></br></br>
			<tr>
			<td align="right"><b>Block Development Officer</b></td>
			</tr>
			<tr>
			<td align="right"><b>'. fun_block_name($_SESSION['location']['block_code']) .','.$_SESSION['location']['district_name'].'</b></td>
			</tr>
			<br><br></br></br>
			
			<tr>
			<td align="left"><b>Memo  No.:·...................../......</b></td>
			</tr>
			<tr>
			<td align="right"><b>Date:-</b></td>
			</tr>
			<tr>
			<td style="width:50%">Forwarded to the Block Development Officer,'.fun_block($trsfer_gp_id_fk[0]['transfer_block_id_fk']).','.fun_dis($trsfer_gp_id_fk[0]['transfer_district_id_fk']).' for his kind Information.</td>
			
			</tr>
			<tr>
			<td align="right"><b>Block Development Officer</b></td>
			</tr>
			<tr>
			<td align="right"><b>'. fun_block_name($_SESSION['location']['block_code']) .','.$_SESSION['location']['district_name'].'</b></td>
			</tr>
			
			</table>',2);
		
			$mpdf->Output($emp_data[0]['emp_first_name'].' '.$emp_data[0]['emp_second_name'].' '.$emp_data[0]['emp_last_name'].'.pdf','D');
			$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>LPC successfully generated...</strong></div>';
}
			else
			{
			
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>LPC generation fails...</strong></div>';
			header('Location:employee_list.php?ps_id_fk='.$ps_id);
			}
			?>