<?php
ob_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
$crypto = new cryptography();
//---------------------------------------------------------------------------------------------------
$db = new database();
$Arguments = $crypto->decode($_GET['var'],2);
$Arguments = json_decode($Arguments,true);
$empData =$db->fetch_table("Select plmd.*,pem.* 
from 
prd_employee_master as pem
RIGHT JOIN prd_location_master_district as plmd 
ON pem.zp_id_fk = plmd.district_id_pk
WHERE emp_id_pk='".$Arguments['user_id']."'");
if(count($empData) > 0)
{
	$empData = $empData[0];
}

//print_r($empData); exit;
//--------------------------------------------------------------------------------------------------



if(isset($empData['emp_id_pk']))
{
	
	
	//$code_data = $db->fetch_table("SELECT code, description FROM prd_dise_code_master;");
	//print_r($code_data); exit;
	function fun_common($code)
	{
		$db = new database();
		$emp_desg_data = $db->fetch_table(" SELECT code, description FROM prd_dise_code_master where code='".$code."'");
		return $emp_desg_data[0]['description'];
	}
	
	function fun_desig($code)
	{
		$db = new database();
		$emp_desg_data = $db->fetch_table(" SELECT designation_id, designation_name FROM zpemp_emp_desig_master where designation_id='".$code."'");
		return $emp_desg_data[0]['designation_name'];
	}
	function fun_bank($val)
	{
		$db = new database();
		$dist_data2 = @$db->fetch_table("SELECT bank_name FROM prd_dise_bank_master where bank_code='".$val."';");
		return $dist_data2[0]['bank_name'];
	}
	function date_frmt($original_date)
	{
		if($original_date =="0001-01-01" || $original_date =="1970-01-01")
		{
			return "---";
		}
		else
		{
			//return $newDate = date("d-m-Y", strtotime($original_date));
			$old=explode("-",$original_date);
			$new=$old[2]."-".$old[1]."-".$old[0];
			return $new;
		}
	}
	
	$monthno=$Arguments['year'].$Arguments['month'];
	if($Arguments['month']=='01')
	{
		$Month='January';
	}
	else if($Arguments['month']=='02')
	{
		$Month='February';
	}
	else if($Arguments['month']=='03')
	{
		$Month='March';
	}
	else if($Arguments['month']=='04')
	{
		$Month='April';
	}
	else if($Arguments['month']=='05')
	{
		$Month='May';
	}
	else if($Arguments['month']=='06')
	{
		$Month='June';
	}
	else if($Arguments['month']=='07')
	{
		$Month='July';
	}
	else if($Arguments['month']=='08')
	{
		$Month='Auguest';
	}
	else if($Arguments['month']=='09')
	{
		$Month='September';
	}
	else if($Arguments['month']=='10')
	{
		$Month='October';
	}
	else if($Arguments['month']=='11')
	{
		$Month='November';
	}
	else if($Arguments['month']=='12')
	{
		$Month='December';
	}
	
	function get_pay_scale($pay_code)
	{
	
		$db  = new database();
		$data = $db->fetch_table("SELECT payscale_id, payscale_range, payscale_code
									FROM prd_dise_payscale_master WHERE payscale_code='".$pay_code."'");
									return $data[0]['payscale_range'];
	
	}
	
	function number_to_words($number)
	{
		if ($number > 999999999)
		{
			throw new Exception("Number is out of range");
		}
		$Gn = floor($number / 10000000);  /* Millions (giga) */
		$number -= $Gn * 10000000;
		$Ln = floor($number / 100000);  /* Millions (giga) */
		$number -= $Ln * 100000;
		$kn = floor($number / 1000);     /* Thousands (kilo) */
		$number -= $kn * 1000;
		$Hn = floor($number / 100);      /* Hundreds (hecto) */
		$number -= $Hn * 100;
		$Dn = floor($number / 10);       /* Tens (deca) */
		$n = $number % 10;               /* Ones */
		$cn = round(($number-floor($number))*100); /* Cents */
		$result = ""; 
		if ($Gn)
		{  $result .= (empty($result) ? "" : " ") . number_to_words($Gn) . " Crore";  } 
		if ($Ln)
		{  $result .= (empty($result) ? "" : " ") . number_to_words($Ln) . " Lakh"; } 
		if ($kn)
		{  $result .= (empty($result) ? "" : " ") . number_to_words($kn) . " Thousand"; } 
		if ($Hn)
		{  $result .= (empty($result) ? "" : " ") . number_to_words($Hn) . " Hundred";  } 
		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
		"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
		"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
		"Nineteen");
		$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
		"Seventy", "Eigthy", "Ninety"); 
		if ($Dn || $n)
		{
			if (!empty($result))
			{  
				$result .= " and ";
			} 
			if ($Dn < 2)
			{  
				$result .= $ones[$Dn * 10 + $n];
			}
			else
			{  
				$result .= $tens[$Dn];
				if($n)
				{  
					$result .= "-" . $ones[$n];
				}
			}
		}
		if ($cn)
		{
			if (!empty($result))
			{  
				$result .= ' and ';
			}
			$title = $cn==1 ? 'paisa ': 'paisa';
			$result .= strtolower(number_to_words($cn)).' '.$title;
		}
		if (empty($result))
		{  $result = "zero"; } 
		return $result;
	}
	

        //print_r($empData); exit;
		$stake_clause='zp_id_fk='.$empData['district_id_pk'];
		$stake_join='emp.zp_id_fk=sal.zp_id_fk';
		$pdf_name=$empData['district_name'];
		$bill_clause=$stake_clause;
		$zp_details_fetch=$db->fetch_table(" SELECT tan_no,vill_name,police_station_name,pin_code FROM zpemp_zp_profile WHERE district_id_fk='".$empData['district_id_pk']."' ");
		$tan_no=$zp_details_fetch[0]['tan_no'];
		$village_name=$zp_details_fetch[0]['vill_name'];
		$police_station=$zp_details_fetch[0]['police_station_name'];
		$pin_code=$zp_details_fetch[0]['pin_code'];

	// Subikar
    $year=$Arguments['year'];
	$month=$Arguments['month'];
	$emp_id=$Arguments['id'];
	$type='indi';
	$ropa_status=$Arguments['ropa'];
	
	if($ropa_status ==''){
		$ropa_status = 0;
	}

		
	
		$bill_details_gen_govt=$db->fetch_table(" SELECT 
													bill_no, bill_entry_time
												FROM prd_block_bill_details
												WHERE salary_monthyear='".$year.$month."' AND requisition_type='1001' AND ropa_status='".$ropa_status."'
												AND zp_emp_type=366 AND status='1' AND ".$bill_clause);
												
		$bill_details_supp_govt=$db->fetch_table(" SELECT 
													bill_no, bill_entry_time
												FROM prd_block_bill_details
												WHERE salary_monthyear='".$year.$month."' AND requisition_type='1003' AND ropa_status='".$ropa_status."'
												AND zp_emp_type=366 AND status='1' AND ".$bill_clause);
												
		$bill_details_gen_grant=$db->fetch_table(" SELECT 
													bill_no, bill_entry_time
												FROM prd_block_bill_details
												WHERE salary_monthyear='".$year.$month."' AND requisition_type='1001' AND ropa_status='".$ropa_status."'
												AND zp_emp_type=367 AND status='1' AND ".$bill_clause);
												
		$bill_details_supp_grant=$db->fetch_table(" SELECT 
													bill_no, bill_entry_time
												FROM prd_block_bill_details
												WHERE salary_monthyear='".$year.$month."' AND requisition_type='1003' AND ropa_status='".$ropa_status."'
												AND zp_emp_type=367 AND status='1' AND ".$bill_clause);
	
	
	
	
	if($type=='indi')
	{
		$Query = "SELECT
													emp.emp_first_name,emp.emp_second_name,emp.emp_last_name,emp.emp_pay_in_payband,
													emp.emp_cosolidated_pay,emp.emp_pay_scale,emp.emp_group,emp.emp_pay_band,
													emp.emp_pan_no,emp.emp_id_const,emp.emp_desig,emp.emp_gpf_acc_no,emp.zp_emp_type,emp.ropa_level,
													sal.bankname,sal.accountno,sal.basic,sal.da,sal.hra,sal.ma,sal.pf_loan,
													sal.p_tax,sal.i_tax,sal.net,sal.bank_ifsc,sal.pf_deduct,sal.status_flag,
													sal.salary_monthyear,sal.emp_id_fk,sal.pay_payband,sal.tch_grade_pay,
													sal.hill_allowance, sal.gpf,sal.gross_salary,sal.is_saved,sal.conv_allow,
													sal.overdrawn,sal.gsli,sal.consolidated_pay,sal.other_deduction,
													sal.cooperative_loan,sal.hbl_loan,sal.festival_loan,sal.hra_deduction,sal.allowance,
													sal.interim_relief,sal.total_loan_deduction,sal.other_loan_deduction,sal.requisition_type 
												FROM prd_monthly_salary_archive_final sal
												LEFT JOIN prd_employee_master emp
												ON emp.emp_id_pk=sal.emp_id_fk AND ".$stake_join."
												WHERE emp.emp_id_pk='".$empData['emp_id_pk']."' and emp.emp_status in('1','9','2')
												AND sal.status_flag in (3,4) AND sal.delete_status='1'
												AND sal.salary_monthyear='".$year.$month."' AND sal.is_saved='1'
												AND sal.lock_status='0' AND sal.ropa_status='".$ropa_status."'
												AND emp.".$stake_clause;
		$emp_details_fetch=$db->fetch_table($Query);
		
	
	}

	
	//print_r($emp_details_fetch);die;
	if(count($emp_details_fetch)>0)
	{
		
		define("_MPDF_TEMP_PATH", '../../../locker/temp/');
		include ('../../../includes/third-party/mpdf/mpdf.php');
		
		//$stylesheet = file_get_contents($config['base_url'].'themes/default/css/mpdf_employee_details.css');
		$stylesheet='';
		$mpdf=new mPDF();
		
		// This sets sufficient rights for the user to modify your annotations
		//$mpdf->SetUserRights(false, '/Create/Delete/Modify/Copy/Import/Export');
		
		// If you want to encrypt the file, include the necessary permissions
		//$mpdf->SetProtection(array(), 'userpass', 'nicpass');
		
		//------------------------------------------------------------------------------------------------------
		
		
		
		$mpdf->WriteHTML($stylesheet,1);
		
		//Header and footer
		$mpdf->SetFooter('priemp.wbprd.gov.in|Page-{PAGENO}|');
		
		foreach($emp_details_fetch as $key)
		{
			//$mpdf->AddPage();
			
			$amt_net_total=number_to_words($key['net']);
			if($key['consolidated_pay']=='0')
			{
			
				$total_ern=$key['pay_payband']+$key['tch_grade_pay']+$key['da']+$key['hra']+$key['ma']+$key['conv_allow']+$key['hill_allowance']+$key['interim_relief']+$key['allowance'];
				$emp_group=fun_common($key['emp_group']);
				$emp_scale=fun_common($key['emp_pay_band']).'('.get_pay_scale($key['emp_pay_scale']).')';
			}
			else
			{
				$total_ern=$key['consolidated_pay'];
				$emp_group='';
				$emp_scale='';
			}
			$requisition_type=$key['requisition_type'];
			$total_deduct=$key['gpf']+$key['p_tax']+$key['i_tax']+$key['gsli']+$key['overdrawn']+$key['cooperative_loan']+$key['festival_loan']+$key['hbl_loan']+$key['hra_deduction'];
			$logged_user = 'zpacc';
			if($logged_user=='zpacc')
			{
				$emp_desig=fun_desig($key['emp_desig']);
				if($key['zp_emp_type']=='366' && $key['requisition_type']=='1001')
				{
					$bill_no=$bill_details_gen_govt[0]['bill_no'];
					$bill_date=date_frmt($bill_details_gen_govt[0]['bill_entry_time']);
				}
				else if($key['zp_emp_type']=='366' && $key['requisition_type']=='1003')
				{
					$bill_no=$bill_details_supp_govt[0]['bill_no'];
					$bill_date=date_frmt($bill_details_supp_govt[0]['bill_entry_time']);
				}
				else if($key['zp_emp_type']=='367' && $key['requisition_type']=='1001')
				{
					$bill_no=$bill_details_gen_grant[0]['bill_no'];
					$bill_date=date_frmt($bill_details_gen_grant[0]['bill_entry_time']);
				}
				else if($key['zp_emp_type']=='367' && $key['requisition_type']=='1003')
				{
					$bill_no=$bill_details_supp_grant[0]['bill_no'];
					$bill_date=date_frmt($bill_details_supp_grant[0]['bill_entry_time']);
				}
				
				if($key['zp_emp_type']=='366')
				{
					$emp_category='GOVERNMENT EMPLOYEE ON DEPUTATION';
				}
				else if($key['zp_emp_type']=='367')
				{
					$emp_category='ZP EMPLOYEE';
				}
			}
			else
			{
				$emp_desig=fun_common($key['emp_desig']);
				if($key['requisition_type']=='1001')
				{
					$bill_no=$bill_details_gen[0]['bill_no'];
					$bill_date=date_frmt($bill_details_gen[0]['bill_entry_time']);
				}
				else if($key['requisition_type']=='1003')
				{
					$bill_no=$bill_details_supp[0]['bill_no'];
					$bill_date=date_frmt($bill_details_supp[0]['bill_entry_time']);
				}
			}
			
			$mpdf->AddPage();
			
			//PDF header
			$mpdf->WriteHTML('
			<table style="margin-top:-5%">
				<tr>
					<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"></div></td>
					<td class="he2" style="text-align: center;width: 480px;">',2);

					$mpdf->WriteHTML('<p class="department" style="font-size: 15px;margin: 0px;padding:0px;font-weight:bold;">'. $empData['district_name'].' ZILLA PARISHAD</p>',2);

					$mpdf->WriteHTML('</td>
					<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"></div></td>
				</tr>
			</table>
			<div style="height:25px;"></div>
			<table style="margin-top:-4%;" align="center">
				<tr>
					<td class="he2" style="text-align: center;width: 530px;"><p class="school" style="color: #004080;font-size: 12px;font-weight:bold;">VILL/TOWN : '.$village_name.', POLICE STATION : '.$police_station.', PIN : '.$pin_code.'</p></td>
				</tr>
			</table>
			
			',2);
			
			$mpdf->WriteHTML('
			<div class="segment">
				<p style="text-align:center;font-size:12px;margin-top:0.5%;font-weight:bold;">PAY SLIP FOR THE MONTH OF '.strtoupper($Month).",".$Arguments['year'].'</p>
				<table width="100%" style="font-size:12px;padding-top:3%">
					<tr>
						<td style="width:50%"><b>EMPLOYEE NAME:</b> '.$key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'].'</td>
						<td style="width:50%"><b>BILL NUMBER:</b> '.$bill_no.'</td>
					</tr>
					<tr>
						<td style="width:50%"><b>EMPLOYEE ID:</b> '.$key['emp_id_const'].'</td>
						<td style="width:50%"><b>BILL DATE:</b> '.$bill_date.'</td>
					</tr>
					<tr> 
						<td style="width:50%"><b>DESIGNATION: </b>'.$emp_desig.'</td>
						<td style="width:50%"><b>TAN NUMBER: </b>'.$tan_no.'</td>
						
					</tr>
					<tr>
						<td style="width:50%"><b> GROUP: </b>'.$emp_group.'</td>
						<td style="width:50%"><b> CATEGORY: </b>'.$emp_category.'</td>
					</tr>
					<tr>',2);
				if($ropa_status==2 || $ropa_status==0){ 
						$mpdf->WriteHTML('<td style="width:50%"><b>SCALE:</b> '.$emp_scale.'</td>',2);
				}	
					$mpdf->WriteHTML('
					</tr>
					<tr>
						<td style="width:50%"><b>PAN:</b> '.$key['emp_pan_no'].'</td>
					</tr>
				</table>
				<br>
				<table width="100%" style="border: 1px solid #666; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;font-size: 12px;vertical-align: bottom;">
					<tr>
						<th style="border-bottom:1px solid #666; border-right:1px solid #666; vertical-align:middle;">EARNING(Rs)</th>
						<th style="border-bottom:1px solid #666;border-right:1px solid #666; vertical-align:middle;">DEDUCTION(Rs)</th>
						<th style="border-bottom:1px solid #666; border-right:1px solid #666; vertical-align:middle;">RECOVERIES OF LOAN(Rs)</th>
						<th style="border-bottom:1px solid #666; vertical-align:middle;">OUT/ACCT.DED (Rs)</th>
					</tr>
					
					<tr>
						<td style="padding-left: 5px; padding-right: 2%; border-right:1px solid #666;width:25%">',2);
							$mpdf->WriteHTML('
							<table style="font-size:12px;" width="500px;">',2);
							if($key['emp_cosolidated_pay']!='0' && $key['emp_cosolidated_pay']!='')
							{
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">CONS</td><td align="right">'.$key['consolidated_pay'].'</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<<tr><td>&nbsp;</td><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td><td>&nbsp;</td></tr>',2);
							}
							else
							{
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">PAY</td><td align="right">'.$key['pay_payband'].'</td></tr>',2);
							if($ropa_status==2 || $ropa_status==0){ 
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">GP</td><td align="right">'.$key['tch_grade_pay'].'</td></tr>',2);
							}
							else if($ropa_status==1){
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">LEVEL</td><td align="right">'.$key['ropa_level'].'</td></tr>',2);	
							}	
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">DA</td><td align="right">'.$key['da'].'</td></tr>',2);
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">HRA</td><td align="right">'.$key['hra'].'</td></tr>',2);
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">MA</td><td align="right">'.$key['ma'].'</td></tr>',2);
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">CA</td><td align="right">'.$key['conv_allow'].'</td></tr>',2);
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">AA</td><td align="right">'.$key['allowance'].'</td></tr>',2);
								if($year<='2018')
								{
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">IR</td><td align="right">'.$key['interim_relief'].'</td></tr>',2);
								}
								if($key['hill_allowance']!='0' && $key['hill_allowance']!='')
								{
									$mpdf->WriteHTML('<tr><td style="font-weight:bold">HA</td><td align="right">'.$key['hill_allowance'].'</td></tr>',2);
								}
								else
								{
									$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								}
								
							}
							$mpdf->WriteHTML('
							</table>
						</td>
						<td style="padding-left: 5px; padding-right: 2%; border-right:1px solid #666;width:25%">
							<table style="font-size:12px;" width="150px;">',2);
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">GPF</td><td align="right">'.$key['gpf'].'</td></tr>',2);
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">PT</td><td align="right">'.$key['p_tax'].'</td></tr>',2);
								$mpdf->WriteHTML('<tr><td style="font-weight:bold">IT</td><td align="right">'.$key['i_tax'].'</td></tr>',2);
								if($key['gsli']!='0' && $key['gsli']!='')
								{
									$mpdf->WriteHTML('<tr><td style="font-weight:bold">GSLI</td><td align="right">'.$key['gsli'].'</td></tr>',2);
								}
								else
								{
									$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								}
								if($key['overdrawn']!='0' && $key['overdrawn']!='')
								{
									$mpdf->WriteHTML('<tr><td style="font-weight:bold">OVD</td><td align="right">'.$key['overdrawn'].'</td></tr>',2);
								}
								else
								{
									$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								}
								if($key['festival_loan']!='0' && $key['festival_loan']!='')
								{
									$mpdf->WriteHTML('<tr><td style="font-weight:bold">FAD</td><td align="right">'.$key['festival_loan'].'</td></tr>',2);
								}
								else
								{
									$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								}
								if($key['hra_deduction']!='0' && $key['hra_deduction']!='')
								{
									$mpdf->WriteHTML('<tr><td style="font-weight:bold">HRD</td><td align="right">'.$key['hra_deduction'].'</td></tr>',2);
								}
								else
								{
									$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								}
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);	
							$mpdf->WriteHTML('
							</table>
						</td>
						
						<td style="text-align:right;padding-right: 2%; border-right:1px solid #666;width:25%">
							<table style="font-size:12px;" width="150px;">',2);
                                if($key['total_loan_deduction']!='0'  && $key['total_loan_deduction']!='')
								{
									$mpdf->WriteHTML('<tr><td style="font-weight:bold">PF RECOVERY</td><td align="right">'.$key['total_loan_deduction'].'</td></tr>',2);
									$pf_loan=$key['total_loan_deduction'];
								}
								else
								{
									$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
									$pf_loan='';
								}
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
							$mpdf->WriteHTML('
							</table>
						</td>
						
						<td style="text-align:right;padding-right: 2%;width:25%">
							<table style="font-size:12px;" width="150px;">',2);
								if($key['other_loan_deduction']!='0' && $key['other_loan_deduction']!='')
								{
									$oad=$key['other_loan_deduction'];
									$mpdf->WriteHTML('<tr><td style="font-weight:bold">OAD</td><td align="right">'.$key['other_loan_deduction'].'</td></tr>',2);
								}
								else
								{
									$oad='';
									$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								}
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
								$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
							$mpdf->WriteHTML('
							</table>
						</td>
					</tr>
					<tr>
						<th style="text-align:left;border-top:1px solid #666;">Total:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$total_ern.'</th>
						<td style="text-align:right;padding-right:2%;border-top:1px solid #666;font-weight:bold;">'.$total_deduct.'</td>
						<td style="text-align:right;padding-right:2%;border-top:1px solid #666;font-weight:bold;">'.$pf_loan.'</td>
						<td style="text-align:right;padding-right:2%;border-top:1px solid #666;font-weight:bold;">'.$oad.'</td>
					</tr>
					<tr>
						<th colspan="4" style="text-align:left;border-top:1px solid #666;">GROSS PAY: '.$key['gross_salary'].'</th>
					</tr>
					<tr>
						<th colspan="4" style="text-align:left;border-top:1px solid #666;">NET PAY:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$key['net'].' (Rupees &nbsp;<b>'.$amt_net_total.'</b>&nbsp;only)</th>
					</tr>
					<tr>
						<th colspan="4" style="text-align:left">Transferred to '.fun_bank($key['bankname']).' Account no '.$key['accountno']. '&nbsp;&nbsp;&nbsp;&nbsp; IFSC Code '.$key['bank_ifsc'].'</th>
					</tr>
				</table>
			</div>
			
			<div>
				<br />
				<div>
					<p style="font-size:11px;">GP: Grade Pay, DA: Dearness Allowance, HRA: House Rent Allowance, MA: Medical Allowance, CA: Conveyance Allowance, AA: Administrative Allowance </p>
					<p style="font-size:11px;">GSLI: Group Savings Linked Insurance, OVD: Overdrawn, OAD: Out of Account Deduction.</p>
				</div>
				<br />
				<p style="padding-top:40%">Date : '.date('d-m-Y').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Authorised Signatory</p>
			</div>
			',2);
		
		}
		if($type=='indi')
		{
			$mpdf->Output('PAYSLIP FOR '.$key['emp_id_const'].' OF '.$pdf_name.'.pdf','D');
		}

	}
	else
	{
		//$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>NO DATA FOUND</strong></div>';
		//header('Location:'.$config['base_url'].'page/all_moduls/payslip_generation/employee_number_chosen_action.php');
		echo "Sorry No payslip found";
		exit(0);
	}
}
else
{
	echo "Sorry No payslip found";
	//header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
}
?>