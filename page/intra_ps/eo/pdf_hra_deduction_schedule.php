<?php

ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';

$crypto = new cryptography();
//---------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------


$db = new database();

$code_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master;
	
	");
	
function fun_common( $code)
{
	$db = new database();
	$emp_desg_data = $db->fetch_table("
							SELECT code, description
							FROM prd_dise_code_master where code='".$code."'");

	return $emp_desg_data[0]['description'];
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
			if ($n)
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
	{  
		$result = "zero"; 
	} 
	return $result;
}




/*$emp_details= $db->fetch_table("SELECT 
							emp.emp_first_name,
							emp.emp_second_name,
							emp.emp_last_name,
							emp.emp_desig,
							emp.emp_spouse_hra,
							emp.emp_id_const,
							sal.basic,
							sal.hra,
							sal.pay_payband,
							sal.tch_grade_pay
						FROM prd_employee_salary_save as sal 
						INNER JOIN prd_employee_master as emp 
						ON sal.emp_id_fk = emp.emp_id_pk 
						INNER JOIN prd_location_master_panchayat_samiti as ps 
						ON emp.ps_id_fk=ps.ps_id_pk
						INNER JOIN prd_block_bill_details as bill
						ON ps.ps_id_pk=bill.ps_id_fk AND sal.salary_monthyear=bill.salary_monthyear and bill.requisition_type in('1001','1003')
						WHERE
							emp.emp_status in('1','9') 
							AND sal.status_flag = 3 
							AND sal.salary_monthyear='".date('Ym')."'
							AND sal.is_saved='1'
							AND sal.requisition_type in ('1001','1003')
							AND emp.emp_spouse_res='251'
							AND ps.ps_id_pk='".$_SESSION['location']['ps_id']."'
							AND bill.status='1'
							");*/
							
							
				$emp_details= $db->fetch_table(" SELECT emp.emp_first_name, 
				emp.emp_second_name, emp.emp_last_name,
				emp.emp_desig, emp.emp_spouse_hra, emp.emp_id_const,
				sal.basic, sal.hra,
				sal.pay_payband,
				sal.tch_grade_pay
				FROM prd_employee_master as emp 
				
				INNER JOIN prd_employee_salary_save as sal
				ON sal.emp_id_fk = emp.emp_id_pk 
				WHERE emp.emp_status in('1','9') 
				AND sal.status_flag = '3'
				and sal.delete_status='1' 
				AND sal.salary_monthyear='".date('Ym')."'
				AND sal.is_saved='1'
				AND sal.requisition_type in ('1001')
				AND emp.emp_spouse_res='251'
				AND sal.ps_id_fk='".$_SESSION['location']['ps_id']."'
				
				");



  
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
$mpdf->SetFooter('priemp.wbprd.gov.in|Page-{PAGENO}|Date of Generation: '.date('jS \of F Y'));

//PDF header
/*$mpdf->WriteHTML('
					
					<table style="margin-top:-5%">
						<tr>
							<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"><img width="100" src="../../../themes/default/image/iosms_logo.png" /></div></td>
							<td class="he2" style="text-align: center;width: 480px;">
								<p class="department" style="font-size: 15px;margin: 0px;padding:0px;font-weight:bold;">GOVERNMENT OF WEST BENGAL</p>
								<p class="department" style="font-size: 15px;margin: 0px;padding:0px;font-weight:bold;">Panchayats & Rural Development Department </p>
								<p class="school" style="color: #004080;font-size: 12px;font-weight:bold;">'. $circle[0]['ps_name'].",".$circle[0]['district_name'].'</p>
							
								
							</td>
						<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"><!--<img src="'.$config['base_url'].'page/qrcode/image.php?msg='.urlencode($msg).'" alt="generation qr-code" style="border: solid 1px black;width:50px;height:50px;">--></div></td>
						</tr>
					</table>
					
					',2);*/
					
$mpdf->WriteHTML('<div class="segment">
					<p style="text-align:center;font-size:12px;margin-top:0.5%;font-weight:bold;">GOVT. HOUSING DEDUCTION SCHEDULE FOR THE MONTH OF '.strtoupper(date('F')).",".date('Y').'</p>
					<br><br>
					<table width="100%" style="font-size:12px;padding-top:3%">
					<tr>
					<td><b>Form No :</b> T.R. FORM NO.-12</td>
					</tr>
					<tr> 
					<td><b>Schedule for : </b> GOVERNMENT HOUSING SCHEME</td>
					</tr>
					<tr> 
					<td><b>Department : </b> EXECUTIVE OFFICER, '.$_SESSION['location']['state_name'].', '.$_SESSION['location']['ps_name'].', '.$_SESSION['location']['district_name'].'</td>
					</tr>
					<tr>
					<td><b>Establishment :</b> PANCHAYAT SAMITI</td>
					</tr>
					<tr>
					<td><b>Pay Month & Year :</b> '.strtoupper(date('F')).", ".date('Y').'</td>
					</tr>
					</table>
				<br><table width="100%" style="border: 1px solid #666; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px;font-size: 12px;vertical-align: bottom;">
					<tr>
					  <th style="border-bottom:1px solid #666; border-right:1px solid #666; vertical-align:middle;">SL NO.</th>
					  <th style="border-bottom:1px solid #666;border-right:1px solid #666; vertical-align:middle;">NAME</th>
					  <th style="border-bottom:1px solid #666;border-right:1px solid #666; vertical-align:middle;">EMPLOYEE ID</th>
					  <th style="border-bottom:1px solid #666; border-right:1px solid #666; vertical-align:middle;">DESIGNATION</th>
					  <th style="border-bottom:1px solid #666; vertical-align:middle;">AMOUNT (Rs)</th>
					  </tr>',2);
						 $cnt=1;$total_hra=0;
						if(count($emp_details)>0)
						{
							foreach($emp_details as $key)
							{
							  
								$full_basic=$key['basic'];
								$hra_per=12;
									if($key['emp_spouse_hra']=='0' || $key['emp_spouse_hra']=='' || !$key['emp_spouse_hra'])
									{
										$hra_emp = ($full_basic/100)*$hra_per;
										if($hra_emp > 12000)
										{
											$full_hra = 12000;
										}
										else
										{
											$full_hra = round($hra_emp);
										}
									}
									else if($key['emp_spouse_hra'] >= 12000)
									{
										$full_hra = 0;
									}
									else if($key['emp_spouse_hra'] < 12000)
									{
											 //spouse HRA < 6000
										$hra_emp = ($full_basic/100)*$hra_per;
										if($hra_emp >= 12000)
										{
											$valid_hra  = (12000-$key['emp_spouse_hra']);
											$full_hra = round($valid_hra);
										}
										else
										{
											$mix_hra = $hra_emp+$key['emp_spouse_hra'];
											if($mix_hra > 12000)
											{
												if($key['emp_spouse_hra'] > $hra_emp)
												{
													$valid_hra = 12000-$key['emp_spouse_hra'];
													if($valid_hra>$hra_emp)
													{
														$valid_hra = $hra_emp;
													}
													$full_hra = round($valid_hra);
												}
												else if($key['emp_spouse_hra'] <= $hra_emp)
												{
													$valid_hra = 12000-$key['emp_spouse_hra'];
													if($valid_hra>$hra_emp)
													{
														$valid_hra=$hra_emp;
													}
													$full_hra = round($valid_hra);
												}
											}
											else
											{
												$full_hra = round($hra_emp);
											}
										}
									}
								
							  $total_hra=$total_hra+$full_hra;
							  $mpdf->WriteHTML('<tr>
								  <td style="border-bottom:1px solid #666; border-right:1px solid #666; text-align:center;">'.$cnt.'</td>
								  <td style="border-bottom:1px solid #666; border-right:1px solid #666; text-align:center;">'.$key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'].'</td>
								  <td style="border-bottom:1px solid #666; border-right:1px solid #666; text-align:center;">'.$key['emp_id_const'].'</td>
								  <td style="border-bottom:1px solid #666; border-right:1px solid #666; text-align:center;">'.fun_common($key['emp_desig']).'</td>
								  <td style="border-bottom:1px solid #666; text-align:center;">'.$full_hra.'</td>
							  </tr>',2);
							$cnt++;
							}
						
							  $mpdf->WriteHTML('<tr style="border-top:1px;">
							  <th>&nbsp;</th>
							  <th>&nbsp;</th>
							  <th>&nbsp;</th>
							  <th style="text-align:center;">Grand Total</th>
							  <th style="text-align:center;">'.$total_hra.'</th>
							  </tr>',2);
						}
						else
						{
							$mpdf->WriteHTML('<tr style="border-top:1px;">
							  <th colspan="5" style="color:red;font-weight:bold">No Data Found</th>
							  </tr>',2);
						}
						  $mpdf->WriteHTML('</table>',2);
						  if(count($emp_details)>0)
						{
						  $mpdf->WriteHTML('<p>( Total : Ruppes '.number_to_words($total_hra).')</p>',2);
						}
						  $mpdf->WriteHTML('</div>
						 
						  <div style="padding-top:80px;">
						  <table style="width:100%;">
						  	<tr>
							<td style="text-align:left">Date:</td>
							<td style="text-align:right">( Signature of EXECUTIVE OFFICER )</td>
							</tr>
						  </table>
						   </div>
							',2);
$mpdf->Output('GOVT. HOUSING DEDUCTION SCHEDULE OF '.$_SESSION['location']['ps_name'].'.pdf','D');

?>