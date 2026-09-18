<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

if($logged_user=='zpacc' || $logged_user=='DA' || $logged_user=='GP')
{
	$db = new database();
	
	$code_data = $db->fetch_table("SELECT code, description FROM prd_dise_code_master;");
	
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
	
	$monthno=$_REQUEST['year'].$_REQUEST['month'];
	if($_REQUEST['month']=='01')
	{
		$Month='January';
	}
	else if($_REQUEST['month']=='02')
	{
		$Month='February';
	}
	else if($_REQUEST['month']=='03')
	{
		$Month='March';
	}
	else if($_REQUEST['month']=='04')
	{
		$Month='April';
	}
	else if($_REQUEST['month']=='05')
	{
		$Month='May';
	}
	else if($_REQUEST['month']=='06')
	{
		$Month='June';
	}
	else if($_REQUEST['month']=='07')
	{
		$Month='July';
	}
	else if($_REQUEST['month']=='08')
	{
		$Month='Auguest';
	}
	else if($_REQUEST['month']=='09')
	{
		$Month='September';
	}
	else if($_REQUEST['month']=='10')
	{
		$Month='October';
	}
	else if($_REQUEST['month']=='11')
	{
		$Month='November';
	}
	else if($_REQUEST['month']=='12')
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
	
	
	if($logged_user=='zpacc')
	{
		$stake_clause='zp_id_fk='.$_SESSION['location']['district_id'];
		$stake_join='emp.zp_id_fk=sal.zp_id_fk';
		$pdf_name=$_SESSION['location']['district_name'];
		$bill_clause=$stake_clause;
	}
	if($logged_user=='DA')
	{
		$stake_clause='ps_id_fk='.$_SESSION['location']['ps_id'];
		$stake_join='emp.ps_id_fk=sal.ps_id_fk';
		$pdf_name=$_SESSION['location']['ps_name'];
		$bill_clause=$stake_clause;
	}
	if($logged_user=='GP')
	{
		$stake_clause='gp_id_fk='.$_SESSION['location']['gp_id'];
		$stake_join='CAST(emp.gp_id_fk as character varying)=sal.gp_id_fk';
		$pdf_name=$_SESSION['location']['gp_name'];
		$block_code=substr($_SESSION['user_info']['stake_user'],0,7);
		$bill_clause="block_code='".$block_code."'";
	}
	
	
	$year=$_GET['year'];
	$month=$_GET['month'];
	$emp_id=$crypto->decode($_GET['id'],4);
	$type=$crypto->decode($_GET['type'],4);
	
	if($logged_user=='zpacc')
	{
	
		$bill_details_gen_govt=$db->fetch_table(" SELECT 
													bill_no, bill_entry_time
												FROM prd_block_bill_details
												WHERE salary_monthyear='".$year.$month."' AND requisition_type='1001' 
												AND zp_emp_type=366 AND status='1' AND ".$bill_clause);
												
		$bill_details_supp_govt=$db->fetch_table(" SELECT 
													bill_no, bill_entry_time
												FROM prd_block_bill_details
												WHERE salary_monthyear='".$year.$month."' AND requisition_type='1003' 
												AND zp_emp_type=366 AND status='1' AND ".$bill_clause);
												
		$bill_details_gen_grant=$db->fetch_table(" SELECT 
													bill_no, bill_entry_time
												FROM prd_block_bill_details
												WHERE salary_monthyear='".$year.$month."' AND requisition_type='1001' 
												AND zp_emp_type=367 AND status='1' AND ".$bill_clause);
												
		$bill_details_supp_grant=$db->fetch_table(" SELECT 
													bill_no, bill_entry_time
												FROM prd_block_bill_details
												WHERE salary_monthyear='".$year.$month."' AND requisition_type='1003' 
												AND zp_emp_type=367 AND status='1' AND ".$bill_clause);
	}
	
	if($logged_user=='DA' || $logged_user=='GP')
	{
		$bill_details_gen=$db->fetch_table(" SELECT 
													bill_no, bill_entry_time
												FROM prd_block_bill_details
												WHERE salary_monthyear='".$year.$month."' AND requisition_type='1001' 
												AND status='1' AND ".$bill_clause);
												
		$bill_details_supp=$db->fetch_table(" SELECT 
													bill_no, bill_entry_time
												FROM prd_block_bill_details
												WHERE salary_monthyear='".$year.$month."' AND requisition_type='1003' 
												AND status='1' AND ".$bill_clause);
	}
	
	
	
	if($type=='indi')
	{
		$emp_details_fetch=$db->fetch_table("
												SELECT
													emp_id_pk,emp_first_name,emp_second_name,emp_last_name,emp_pay_in_payband,
													emp_pay_scale,emp_group,emp_cosolidated_pay,emp_pay_band,
													emp_pan_no,emp_id_const,emp_desig,emp_gpf_acc_no,zp_emp_type
												FROM prd_employee_master emp
												WHERE emp_id_pk='".$emp_id."' and emp_status in('1','9')
												AND ".$stake_clause);
		/*$emp_details_fetch=$db->fetch_table("
												SELECT
													emp.emp_first_name,emp.emp_second_name,emp.emp_last_name,emp.emp_pay_in_payband,
													emp.emp_pay_scale,emp.emp_group,emp.emp_pay_band,emp.emp_pan_no,emp.emp_id_const,
													emp.emp_desig,emp.emp_gpf_acc_no,emp.zp_emp_type,
													sal.bankname,sal.accountno,sal.basic,sal.da,sal.hra,sal.ma,sal.cpf,
													sal.pf_loan,sal.p_tax,sal.i_tax,sal.net,sal.bank_ifsc,sal.sal_source,sal.spl_pay,
													sal.pf_deduct,sal.code,sal.spl_alo,sal.status_flag,sal.salary_monthyear,sal.category_id,
													sal.block_code,sal.emp_id_fk,sal.pay_payband,sal.tch_grade_pay,sal.hill_allowance,
													sal.gpf,sal.gross_salary,sal.is_saved,sal.conv_allow,sal.overdrawn,sal.salary_type,
													sal.cause,sal.gsli,sal.delete_status,sal.consolidated_pay,sal.other_deduction_cause,
													sal.other_deduction,sal.cooperative_loan,sal.hbl_loan,sal.festival_loan,
													sal.cooperative_loan_cause,sal.hbl_loan_cause,sal.festival_loan_cause,sal.hra_deduction,
													sal.part_salary_cause,sal.no_salary_cause,sal.interim_relief,sal.lock_status,
													sal.other_loan_deduction,sal.requisition_type
												FROM prd_employee_master emp
												INNER JOIN prd_monthly_salary_archive_final sal
												ON emp.emp_id_pk=sal.emp_id_fk AND ".$stake_join."
												WHERE emp.emp_id_pk='".$emp_id."' and emp.emp_status in('1','9')
												AND sal.status_flag in (3,4) AND sal.delete_status='1'
												AND sal.salary_monthyear='".$year.$month."' AND sal.is_saved='1' AND sal.payment_status='11'
												AND emp.".$stake_clause);*/
		$emp_id_string=$emp_id;
	
	}
	else if($type=='all')
	{
		
		$emp_details_fetch=$db->fetch_table("
												SELECT
													emp_id_pk,emp_first_name,emp_second_name,emp_last_name,
													emp_pay_in_payband,emp_pay_scale,emp_group,emp_pay_band,
													emp_pan_no,emp_id_const,emp_desig,emp_gpf_acc_no,zp_emp_type
												FROM prd_employee_master emp
												WHERE emp_status in('1','9') AND ".$stake_clause);
		
		for($i=0;$i<count($emp_details_fetch);$i++)
		{
			$emp_id_string.=$emp_details_fetch[$i]['emp_id_pk'];
			if($i!=count($emp_details_fetch)-1)
			{
				$emp_id_string=$emp_id_string.",";
			}
		}
				
		echo "
												SELECT
													emp.emp_first_name,emp.emp_second_name,emp.emp_last_name,emp.emp_pay_in_payband,emp.emp_cosolidated_pay,
													emp.emp_pay_scale,emp.emp_group,emp.emp_pay_band,emp.emp_pan_no,emp.emp_id_const,
													emp.emp_desig,emp.emp_gpf_acc_no,emp.zp_emp_type,
													sal.bankname,sal.accountno,sal.basic,sal.da,sal.hra,sal.ma,sal.cpf,
													sal.pf_loan,sal.p_tax,sal.i_tax,sal.net,sal.bank_ifsc,sal.sal_source,sal.spl_pay,
													sal.pf_deduct,sal.code,sal.spl_alo,sal.status_flag,sal.salary_monthyear,sal.category_id,
													sal.block_code,sal.emp_id_fk,sal.pay_payband,sal.tch_grade_pay,sal.hill_allowance,
													sal.gpf,sal.gross_salary,sal.is_saved,sal.conv_allow,sal.overdrawn,sal.salary_type,
													sal.cause,sal.gsli,sal.delete_status,sal.consolidated_pay,sal.other_deduction_cause,
													sal.other_deduction,sal.cooperative_loan,sal.hbl_loan,sal.festival_loan,
													sal.cooperative_loan_cause,sal.hbl_loan_cause,sal.festival_loan_cause,sal.hra_deduction,
													sal.part_salary_cause,sal.no_salary_cause,sal.interim_relief,sal.lock_status,sal.other_loan_deduction
												FROM prd_employee_master emp
												INNER JOIN prd_monthly_salary_archive_final sal
												ON emp.emp_id_pk=sal.emp_id_fk AND ".$stake_join."
												WHERE emp.emp_status in('1','9')
												AND sal.status_flag in (3,4) AND sal.delete_status='1'
												AND sal.salary_monthyear='".$year.$month."' AND sal.is_saved='1' AND sal.payment_status='11'
												AND emp.".$stake_clause;die;
		$emp_details_fetch=$db->fetch_table("
												SELECT
													emp.emp_first_name,emp.emp_second_name,emp.emp_last_name,emp.emp_pay_in_payband,emp.emp_cosolidated_pay,
													emp.emp_pay_scale,emp.emp_group,emp.emp_pay_band,emp.emp_pan_no,emp.emp_id_const,
													emp.emp_desig,emp.emp_gpf_acc_no,emp.zp_emp_type,
													sal.bankname,sal.accountno,sal.basic,sal.da,sal.hra,sal.ma,sal.cpf,
													sal.pf_loan,sal.p_tax,sal.i_tax,sal.net,sal.bank_ifsc,sal.sal_source,sal.spl_pay,
													sal.pf_deduct,sal.code,sal.spl_alo,sal.status_flag,sal.salary_monthyear,sal.category_id,
													sal.block_code,sal.emp_id_fk,sal.pay_payband,sal.tch_grade_pay,sal.hill_allowance,
													sal.gpf,sal.gross_salary,sal.is_saved,sal.conv_allow,sal.overdrawn,sal.salary_type,
													sal.cause,sal.gsli,sal.delete_status,sal.consolidated_pay,sal.other_deduction_cause,
													sal.other_deduction,sal.cooperative_loan,sal.hbl_loan,sal.festival_loan,
													sal.cooperative_loan_cause,sal.hbl_loan_cause,sal.festival_loan_cause,sal.hra_deduction,
													sal.part_salary_cause,sal.no_salary_cause,sal.interim_relief,sal.lock_status,sal.other_loan_deduction
												FROM prd_employee_master emp
												INNER JOIN prd_monthly_salary_archive_final sal
												ON emp.emp_id_pk=sal.emp_id_fk AND ".$stake_join."
												WHERE emp.emp_status in('1','9')
												AND sal.status_flag in (3,4) AND sal.delete_status='1'
												AND sal.salary_monthyear='".$year.$month."' AND sal.is_saved='1' AND sal.payment_status='11'
												AND emp.".$stake_clause);
	}
	
	$sal_details_fetch=$db->fetch_table("SELECT
											emp_id_fk,bankname,accountno,basic,da,hra,ma,cpf,pf_loan,p_tax,i_tax,net,bank_ifsc,
											sal_source,spl_pay,pf_deduct,code,spl_alo,status_flag,salary_monthyear,category_id,
											block_code,emp_id_fk,pay_payband,tch_grade_pay,hill_allowance,gpf,gross_salary,
											is_saved,conv_allow,overdrawn,salary_type,cause,gsli,delete_status,consolidated_pay,
											other_deduction_cause,other_deduction,cooperative_loan,hbl_loan,festival_loan,
											cooperative_loan_cause,hbl_loan_cause,festival_loan_cause,hra_deduction,total_loan_deduction
											part_salary_cause,no_salary_cause,interim_relief,lock_status,other_loan_deduction,requisition_type
										FROM prd_monthly_salary_archive_final
										WHERE status_flag in (3,4) AND delete_status='1'
										AND salary_monthyear='".$year.$month."' AND is_saved='1' 
										AND emp_id_fk in (".$emp_id_string.")
										");
	
	echo "<pre>";
	
	$details_array=array();
	$i=0;
	foreach($emp_details_fetch as $arr)
	{
		$details_array[$arr['emp_id_pk']]['emp_id_pk']=$arr['emp_id_pk'];
		$details_array[$arr['emp_id_pk']]['emp_first_name']=$arr['emp_first_name'];
		$details_array[$arr['emp_id_pk']]['emp_second_name']=$arr['emp_second_name'];
		$details_array[$arr['emp_id_pk']]['emp_last_name']=$arr['emp_last_name'];
		$details_array[$arr['emp_id_pk']]['emp_pay_in_payband']=$arr['emp_pay_in_payband'];
		$details_array[$arr['emp_id_pk']]['emp_pay_scale']=$arr['emp_pay_scale'];
		$details_array[$arr['emp_id_pk']]['emp_group']=$arr['emp_group'];
		$details_array[$arr['emp_id_pk']]['emp_pay_band']=$arr['emp_pay_band'];
		$details_array[$arr['emp_id_pk']]['emp_pan_no']=$arr['emp_pan_no'];
		$details_array[$arr['emp_id_pk']]['emp_id_const']=$arr['emp_id_const'];
		$details_array[$arr['emp_id_pk']]['emp_desig']=$arr['emp_desig'];
		$details_array[$arr['emp_id_pk']]['emp_gpf_acc_no']=$arr['emp_gpf_acc_no'];
		$details_array[$arr['emp_id_pk']]['zp_emp_type']=$arr['zp_emp_type'];
		
	}
	
	foreach($sal_details_fetch as $arr1)
	{
		$sal_details_array[$arr1['emp_id_fk']]['emp_id_fk']=$arr1['emp_id_fk'];
		$sal_details_array[$arr1['emp_id_fk']]['bankname']=$arr1['bankname'];
		$sal_details_array[$arr1['emp_id_fk']]['accountno']=$arr1['accountno'];
		$sal_details_array[$arr1['emp_id_fk']]['basic']=$arr1['basic'];
		$sal_details_array[$arr1['emp_id_fk']]['da']=$arr1['da'];
		$sal_details_array[$arr1['emp_id_fk']]['hra']=$arr1['hra'];
		$sal_details_array[$arr1['emp_id_fk']]['ma']=$arr1['ma'];
		$sal_details_array[$arr1['emp_id_fk']]['cpf']=$arr1['cpf']; 
		$sal_details_array[$arr1['emp_id_fk']]['pf_loan']=$arr1['pf_loan'];
		$sal_details_array[$arr1['emp_id_fk']]['p_tax']=$arr1['p_tax'];
		$sal_details_array[$arr1['emp_id_fk']]['i_tax']=$arr1['i_tax'];
		$sal_details_array[$arr1['emp_id_fk']]['net']=$arr1['net'];
		$sal_details_array[$arr1['emp_id_fk']]['bank_ifsc']=$arr1['bank_ifsc'];
		$sal_details_array[$arr1['emp_id_fk']]['sal_source']=$arr1['sal_source'];
		$sal_details_array[$arr1['emp_id_fk']]['spl_pay']=$arr1['spl_pay'];
		$sal_details_array[$arr1['emp_id_fk']]['pf_deduct']=$arr1['pf_deduct'];
		$sal_details_array[$arr1['emp_id_fk']]['code']=$arr1['code'];
		$sal_details_array[$arr1['emp_id_fk']]['spl_alo']=$arr1['spl_alo'];
		$sal_details_array[$arr1['emp_id_fk']]['status_flag']=$arr1['status_flag'];
		$sal_details_array[$arr1['emp_id_fk']]['salary_monthyear']=$arr1['salary_monthyear'];
		$sal_details_array[$arr1['emp_id_fk']]['category_id']=$arr1['category_id'];
		$sal_details_array[$arr1['emp_id_fk']]['block_code']=$arr1['block_code'];     
		$sal_details_array[$arr1['emp_id_fk']]['pay_payband']=$arr1['pay_payband'];
		$sal_details_array[$arr1['emp_id_fk']]['tch_grade_pay']=$arr1['tch_grade_pay'];
		$sal_details_array[$arr1['emp_id_fk']]['hill_allowance']=$arr1['hill_allowance'];
		$sal_details_array[$arr1['emp_id_fk']]['gpf']=$arr1['gpf'];
		$sal_details_array[$arr1['emp_id_fk']]['gross_salary']=$arr1['gross_salary'];
		$sal_details_array[$arr1['emp_id_fk']] ['is_saved']=$arr1['is_saved'];
		$sal_details_array[$arr1['emp_id_fk']]['conv_allow']=$arr1['conv_allow'];
		$sal_details_array[$arr1['emp_id_fk']]['overdrawn']=$arr1['overdrawn'];
		$sal_details_array[$arr1['emp_id_fk']]['salary_type']=$arr1['salary_type'];
		$sal_details_array[$arr1['emp_id_fk']] ['cause']=$arr1['cause'];
		$sal_details_array[$arr1['emp_id_fk']]['gsli']=$arr1['gsli'];
		$sal_details_array[$arr1['emp_id_fk']]['delete_status']=$arr1['delete_status'];
		$sal_details_array[$arr1['emp_id_fk']]['consolidated_pay']=$arr1['consolidated_pay'];
		$sal_details_array[$arr1['emp_id_fk']]['other_deduction_cause']=$arr1['other_deduction_cause'];
		$sal_details_array[$arr1['emp_id_fk']]['other_deduction']=$arr1['other_deduction'];
		$sal_details_array[$arr1['emp_id_fk']]['cooperative_loan']=$arr1['cooperative_loan'];
		$sal_details_array[$arr1['emp_id_fk']]['hbl_loan']=$arr1['hbl_loan'];
		$sal_details_array[$arr1['emp_id_fk']]['festival_loan']=$arr1['festival_loan'];
		$sal_details_array[$arr1['emp_id_fk']]['cooperative_loan_cause']=$arr1['cooperative_loan_cause'];
		$sal_details_array[$arr1['emp_id_fk']]['hbl_loan_cause']=$arr1['hbl_loan_cause'];
		$sal_details_array[$arr1['emp_id_fk']]['festival_loan_cause']=$arr1['festival_loan_cause'];
		$sal_details_array[$arr1['emp_id_fk']]['hra_deduction']=$arr1['hra_deduction'];
		$sal_details_array[$arr1['emp_id_fk']]['part_salary_cause']=$arr1['part_salary_cause'];
		$sal_details_array[$arr1['emp_id_fk']]['no_salary_cause']=$arr1['no_salary_cause'];
		$sal_details_array[$arr1['emp_id_fk']]['interim_relief']=$arr1['interim_relief'];
		$sal_details_array[$arr1['emp_id_fk']]['lock_status']=$arr1['lock_status'];
		$sal_details_array[$arr1['emp_id_fk']]['other_loan_deduction']=$arr1['other_loan_deduction'];
		$sal_details_array[$arr1['emp_id_fk']]['requisition_type']=$arr1['requisition_type']; 
	}
	
	
	print_r($sal_details_array);die;
	
	
	
	
	$details_sal_array=array();
	$i=0;
	foreach($sal_details_fetch as $arr1)
	{
		
		$details_sal_array[$arr1['emp_id_fk']]=$arr1;
		//$i++;
	}
	
	//print_r($details_sal_array);
	print_r(array_merge_recursive($details_array,$sal_details_array));
	//print_r($details_array);
	die;
	foreach($emp_details_fetch as $key=>$value)
	{
		$emp_arr_det[$value['emp_id_pk']] = array($value['emp_first_name'],$value['emp_id_const']);
		//$emp_arr_det[$value['emp_id_pk']] = array( $key['emp_id_pk']=> , 'month' => date('m', strtotime($start_date)), 'total_days'=> (int)date('t', strtotime($start_date)), 'remaining_days' => ((int)date('t', strtotime($start_date)) - (int)date('j', strtotime($start_date)))+1,);
	}
	print_r($emp_arr_det);
	die;
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
		$mpdf->SetFooter('priemp.wbprd.gov.in|Page-{PAGENO}|Date of Generation: '.date('jS \of F Y'));
		
		foreach($emp_details_fetch as $key)
		{
			foreach($sal_details_fetch as $key2)
			{
				if($key['emp_id_pk']==$key2['emp_id_fk'])
				{
					$amt_net_total=number_to_words($key2['net']);
					if($key2['consolidated_pay']=='0')
					{
					
						$total_ern=$key2['pay_payband']+$key2['tch_grade_pay']+$key2['da']+$key2['hra']+$key2['ma']+$key2['conv_allow']+$key2['hill_allowance']+$key2['interim_relief'];
						$emp_group=fun_common($key['emp_group']);
						$emp_scale=fun_common($key['emp_pay_band']).'('.get_pay_scale($key['emp_pay_scale']).')';
					}
					else
					{
						$total_ern=$key['consolidated_pay'];
						$emp_group='';
						$emp_scale='';
					}
					$requisition_type=$key2['requisition_type'];
					$total_deduct=$key2['gpf']+$key2['p_tax']+$key2['i_tax']+$key2['gsli']+$key2['overdrawn']+$key2['cooperative_loan']+$key2['festival_loan']+$key2['hbl_loan']+$key2['hra_deduction'];
					
					if($logged_user=='zpacc')
					{
						$emp_desig=fun_desig($key['emp_desig']);
						if($key['zp_emp_type']=='366' && $key2['requisition_type']=='1001')
						{
							$bill_no=$bill_details_gen_govt[0]['bill_no'];
							$bill_date=date_frmt($bill_details_gen_govt[0]['bill_entry_time']);
						}
						else if($key['zp_emp_type']=='366' && $key2['requisition_type']=='1003')
						{
							$bill_no=$bill_details_supp_govt[0]['bill_no'];
							$bill_date=date_frmt($bill_details_supp_govt[0]['bill_entry_time']);
						}
						else if($key['zp_emp_type']=='367' && $key2['requisition_type']=='1001')
						{
							$bill_no=$bill_details_gen_grant[0]['bill_no'];
							$bill_date=date_frmt($bill_details_gen_grant[0]['bill_entry_time']);
						}
						else if($key['zp_emp_type']=='367' && $key2['requisition_type']=='1003')
						{
							$bill_no=$bill_details_supp_grant[0]['bill_no'];
							$bill_date=date_frmt($bill_details_supp_grant[0]['bill_entry_time']);
						}
					}
					else
					{
						$emp_desig=fun_common($key['emp_desig']);
						if($key2['requisition_type']=='1001')
						{
							$bill_no=$bill_details_gen[0]['bill_no'];
							$bill_date=date_frmt($bill_details_gen[0]['bill_entry_time']);
						}
						else if($key2['requisition_type']=='1003')
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
							<td class="he1" style="width: 100px;vertical-align: top;"><div class="qrcode"><img width="100" src="../../../themes/default/image/iosms_logo.png" /></div></td>
							<td class="he2" style="text-align: center;width: 480px;">
								<p class="department" style="font-size: 15px;margin: 0px;padding:0px;font-weight:bold;">GOVERNMENT OF WEST BENGAL</p>
								<p class="department" style="font-size: 15px;margin: 0px;padding:0px;font-weight:bold;">Panchayats & Rural Development Department </p>',2);
								if($logged_user=='GP')
								{
									$mpdf->WriteHTML('<p class="school" style="color: #004080;font-size: 12px;font-weight:bold;">'.$_SESSION['location']['gp_name']."," .$_SESSION['location']['block_name'].",".$_SESSION['location']['district_name'].'</p>',2);
								}
								else if($logged_user=='DA')
								{
									$mpdf->WriteHTML('<p class="school" style="color: #004080;font-size: 12px;font-weight:bold;">'. $_SESSION['location']['ps_name'].",".$_SESSION['location']['district_name'].'</p>',2);
								}
								else if($logged_user=='zpacc')
								{
									$mpdf->WriteHTML('<p class="school" style="color: #004080;font-size: 12px;font-weight:bold;">'. $_SESSION['location']['district_name'].' ZILLA PARISHAD</p>',2);
								}
							$mpdf->WriteHTML('</td>
							<td class="he3" style="width: 100px;vertical-align: top;text-align: right;"><div class="qrcode"></div></td>
						</tr>
					</table>
					
					',2);
					
					$mpdf->WriteHTML('
					<div class="segment">
						<p style="text-align:center;font-size:12px;margin-top:0.5%;font-weight:bold;">PAY SLIP FOR THE MONTH OF '.strtoupper($Month).",".$_GET['year'].'</p>
						<table width="100%" style="font-size:12px;padding-top:3%">
							<tr>
								<td><b>EMPLOYEE NAME:</b> '.$key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'].'</td>
								<td><b>BILL NUMBER:</b> '.$bill_no.'</td>
							</tr>
							<tr>
								<td><b>EMPLOYEE ID:</b> '.$key['emp_id_const'].'</td>
								<td><b>BILL DATE:</b> '.$bill_date.'</td>
							</tr>
							<tr> 
								<td><b>DESIGNATION: </b>'.$emp_desig.'</td>
								<td><b>BILL TYPE:</b> '.fun_common($requisition_type).'</td>
							</tr>
							<tr> 
								<td><b> GROUP: </b>'.$emp_group.'</td>',2);
								if($key['emp_gpf_acc_no']!='')
								{
									$mpdf->WriteHTML('<td><b>GPF ACC NO:</b> '.$key['emp_gpf_acc_no'].'</td>',2);
								}
							$mpdf->WriteHTML('
							</tr>
							<tr>
								<td><b>SCALE:</b> '.$emp_scale.'</td>
							</tr>
							<tr>
								<td><b>PAN:</b> '.$key['emp_pan_no'].'</td>
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
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">CONS</td><td align="right">'.$key2['consolidated_pay'].'</td></tr>',2);
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
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">PAY</td><td align="right">'.$key2['pay_payband'].'</td></tr>',2);
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">GP</td><td align="right">'.$key2['tch_grade_pay'].'</td></tr>',2);
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">DA</td><td align="right">'.$key2['da'].'</td></tr>',2);
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">HRA</td><td align="right">'.$key2['hra'].'</td></tr>',2);
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">MA</td><td align="right">'.$key2['ma'].'</td></tr>',2);
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">CA</td><td align="right">'.$key2['conv_allow'].'</td></tr>',2);
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">IR</td><td align="right">'.$key2['interim_relief'].'</td></tr>',2);
										if($key2['hill_allowance']!='0' && $key2['hill_allowance']!='')
										{
											$mpdf->WriteHTML('<tr><td style="font-weight:bold">HA</td><td align="right">'.$key2['hill_allowance'].'</td></tr>',2);
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
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">GPF</td><td align="right">'.$key2['gpf'].'</td></tr>',2);
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">PT</td><td align="right">'.$key2['p_tax'].'</td></tr>',2);
										$mpdf->WriteHTML('<tr><td style="font-weight:bold">IT</td><td align="right">'.$key2['i_tax'].'</td></tr>',2);
										if($key2['gsli']!='0' && $key2['gsli']!='')
										{
											$mpdf->WriteHTML('<tr><td style="font-weight:bold">GSLI</td><td align="right">'.$key2['gsli'].'</td></tr>',2);
										}
										else
										{
											$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
										}
										if($key2['overdrawn']!='0' && $key2['overdrawn']!='')
										{
											$mpdf->WriteHTML('<tr><td style="font-weight:bold">OVD</td><td align="right">'.$key2['overdrawn'].'</td></tr>',2);
										}
										else
										{
											$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
										}
										if($key2['festival_loan']!='0' && $key2['festival_loan']!='')
										{
											$mpdf->WriteHTML('<tr><td style="font-weight:bold">FAD</td><td align="right">'.$key2['festival_loan'].'</td></tr>',2);
										}
										else
										{
											$mpdf->WriteHTML('<tr><td>&nbsp;</td></tr>',2);
										}
										if($key2['hra_deduction']!='0' && $key2['hra_deduction']!='')
										{
											$mpdf->WriteHTML('<tr><td style="font-weight:bold">HRD</td><td align="right">'.$key2['hra_deduction'].'</td></tr>',2);
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
										if($key2['pf_loan']!='0'  && $key2['pf_loan']!='' && ($logged_user=='GP' || $logged_user=='DA'))
										{
											$mpdf->WriteHTML('<tr><td style="font-weight:bold">PF LOAN</td><td align="right">'.$key2['pf_loan'].'</td></tr>',2);
											$pf_loan=$key2['pf_loan'];
										}
										else if($key2['total_loan_deduction']!='0'  && $key2['total_loan_deduction']!='' && $logged_user=='zpacc')
										{
											$mpdf->WriteHTML('<tr><td style="font-weight:bold">TOTAL LOAN</td><td align="right">'.$key2['total_loan_deduction'].'</td></tr>',2);
											$pf_loan=$key2['total_loan_deduction'];
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
										if($key2['other_loan_deduction']!='0' && $key2['other_loan_deduction']!='')
										{
											$oad=$key2['other_loan_deduction'];
											$mpdf->WriteHTML('<tr><td style="font-weight:bold">OAD</td><td align="right">'.$key2['other_loan_deduction'].'</td></tr>',2);
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
								<th colspan="4" style="text-align:left;border-top:1px solid #666;">GROSS PAY: '.$key2['gross_salary'].'</th>
							</tr>
							<tr>
								<th colspan="4" style="text-align:left;border-top:1px solid #666;">NET PAY:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$key2['net'].' (Rupees &nbsp;<b>'.$amt_net_total.'</b>&nbsp;only)</th>
							</tr>
							<tr>
								<th colspan="4" style="text-align:left">Transferred to '.fun_bank($key2['bankname']).' Account no '.$key2['accountno']. '&nbsp;&nbsp;&nbsp;&nbsp; IFSC Code '.$key2['bank_ifsc'].'</th>
							</tr>
						</table>
					</div>
					
					<div>
						<br />
						<div>
							<p style="font-size:11px;">GP: Grade Pay, DA: Dearness Allowance, HRA: House Rent Allowance, MA: Medical Allowance, CA: Conveyance Allowance, </p>
							<p style="font-size:11px;">GSLI: Group Savings Linked Insurance, IR: Interim Relief, OVD: Overdrawn, OAD: Out of Account Deduction, .</p>
						</div>
						<br />
						<p style="padding-top:45%"><b>Disclaimer:</b>&nbsp;This is a computer generated Pay Slip and hence does not require any signature.</p>
					</div>
					',2);
				}
			}
		}
		if($type=='indi')
		{
			$mpdf->Output('PAYSLIP FOR '.$key['emp_id_const'].' OF '.$pdf_name.'.pdf','D');
		}
		else
		{
			$mpdf->Output('PAYSLIP FOR ALL EMPLOYEES OF '.$pdf_name.'.pdf','D');
		}
	}
	else
	{
		$_SESSION['msg']='<div id="error">No Data Found.</div>';
		header('Location:'.$config['base_url'].'page/all_moduls/payslip_generation/employee_number_chosen_action.php');
		exit(0);
	}
}
else
{
	header('Location:'.$config['base_url']."page/errordoc.php?id=1");
    exit("Do not paste URL directly");
}
?>