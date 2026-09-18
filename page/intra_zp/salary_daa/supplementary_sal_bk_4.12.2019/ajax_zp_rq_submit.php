<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
$str=$_SESSION['user_info']['stake_user'];
$state10=substr($str,0,4);  

require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';

require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

$crypto = new cryptography();
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='407'");
$requisition_type=$requisition[0]['code']; 

$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('371371371'.$session_token);
if($sec_time_token!=$enc_session)
{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.<strong></div>';
	header('location:zp_emp_sal_requisition.php');
	exit(0);
}
else
{
	$part_day = $_POST['working_days'];
	//$tchcd = $_POST['empcd'];
	
	$emp_desig=$crypto->decode($_POST['emp_desig'],4); 
	$zp_emp_type=$crypto->decode($_POST['zp_emp_type'],4); ;
	
	$emp_id_pk=$_POST['emp_id_pk']; 
	$schcd = $_POST['zp_id_fk'];
	$tchname = $_POST['tchname'];
	$bankname = $_POST['bankname'];
	$accountno = $_POST['accountno'];
	$bank_ifsc = $_POST['bank_ifsc'];
	$code = $_POST['code'];
	$teacher_id_pk = $_POST['teacher_id_pk'];
	$basic = $_POST['basic'];
	$pay_in_band = $_POST['pay_in_band'];
	$consolidated_pay=0;
	$grade_pay = $_POST['grade_pay'];
	$da = $_POST['da'];
	$interim_relief = $_POST['interim_relief'];
	$hra = $_POST['hra'];
	$ma = $_POST['ma'];
	$conv_allow = $_POST['conv_allow'];
	$total_loan_deduction = $_POST['total_loan_deduction'];
	//$emp_bonus = $_POST['emp_bonus'];
	
	$other_deduction = $_POST['other_deduction'];
	if($state10=='3219')
	{
		$hill_allow = $_POST['hill_allow'];
	}
	else
	{
		$hill_allow =0;
	}
	
	//$cpf = $_POST['cpf'];
	$gross = $_POST['gross'];
	$gpf = $_POST['gpf'];
	$pf_loan = $_POST['pf_loan'];
	//$cpf_deduct = $_POST['cpf_deduct'];
	$p_tax = $_POST['p_tax'];
	$i_tax = $_POST['i_tax'];
	$gsli = $_POST['gsli'];
	$is_overdrawn = $_POST['is_overdrawn'];
	$overdrawn = $_POST['overdrawn'];
	//$Co_operative_Loan= $_POST['reduction'];
	
	//$str1=(explode (',',$str));
	//$cooperative_loan= $_POST['n7']; 			
	//$hbl= $_POST['n8'];
	//$festival= $_POST['n9'];
	
	
	//$o_deduction_cause1=0; 
	//$o_deduction=0;
	$other_deduction_cause = $_POST['reduction']; 
	if($other_deduction_cause=='no_ovd1')
	{
		$hra_loan=0;
		$hra_deduction=0;
		$festival_loan_cause=0;
		$festival_val= 0;
	}
	else
	{       
		$hra_deduction= $_POST['hra_deduc'];
		if($hra_deduction>0)
		{
			$hra_loan=$_POST['n7'];
		}
		else
		{
			$hra_loan=0;
		}
		$festival_val= $_POST['reduct3'];
		if($festival_val>0)
		{
			$festival_loan_cause=$_POST['n8'];
		}
		else
		{
			$festival_loan_cause=0;
		}
	}
	$net = $_POST['net'];
	$salary_type = $_POST['salary_type'];
	$cause_msg = $_POST['cause_msg'];
	$part_salary_cause_msg=$_POST['part_salary_cause_msg']; 
	$no_salary_cause_msg=$_POST['no_salary_cause_msg'];
	if($_POST['rank'])
	{
		$rank = $_POST['rank'];
	}
	else
	{
		$rank = 0;
	}
	
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$salary_monthyear = date('Ym');
	$query_string = '?dise='.$crypto->encode($schcd,3);
	
	$total_allowance=$pay_in_band+$grade_pay+$da+$hra+$ma+$conv_allow+$hill_allow+$interim_relief;
	$total_deductions=$gpf+$pf_loan+$p_tax+$i_tax+$gsli+$overdrawn+$festival_val+$hra_deduction+$total_loan_deduction; 
	
	if($validator->blank_select($emp_id_pk) == FALSE || $validator->pattern_number($crypto->decode($emp_id_pk,4)) == FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong Employee.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	if($validator->blank_select($schcd) == FALSE || $validator->pattern_number($crypto->decode($schcd,4)) == FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong ZP Code.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	if($validator->blank_select($tchname) == FALSE || $validator->pattern_match_csf($tchname) == FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong Employee Name.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	if($validator->blank_select($bankname) == FALSE || $validator->pattern_match_csf($bankname) == FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Wrong Bank Name.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	if($validator->blank_select($accountno) == FALSE || $validator->pattern_number($accountno) == FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Invalid Account Number.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	if($validator->blank_select($bank_ifsc) == FALSE || $validator->pattern_match_csf($bank_ifsc) == FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Invalid Bank IFSC Number.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	/*if($validator->blank_select($code) == FALSE || $validator->pattern_match_csf($code) == FALSE)
	{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Invalid Salary Source.<strong></div>';
	header('location:zp_emp_sal_requisition.php'.$query_string);
	exit(0);
	}*/
	else if(($validator->blank_select($pay_in_band) == FALSE || $validator->pattern_number($pay_in_band) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Pay in Pay Band.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	/*else if(($validator->blank_select($consolidated_pay) == FALSE || $validator->pattern_number($consolidated_pay) == FALSE))
	{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Consolidated Pay.<strong></div>';
	header('location:zp_emp_sal_requisition.php'.$query_string);
	exit(0);
	}*/
	else if(($validator->blank_select($grade_pay) == FALSE || $validator->pattern_number($grade_pay) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Grade Pay.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($validator->blank_select($da) == FALSE || $validator->pattern_number($da) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid DA.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($validator->blank_select($hra) == FALSE || $validator->pattern_number($hra ) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid HRA.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($validator->blank_select($ma) == FALSE || $validator->pattern_number($ma) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid MA.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($validator->blank_select($conv_allow) == FALSE || $validator->pattern_number($conv_allow) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Conveyance Allowance.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	//Hill?????????????????????????????????
	else if(($validator->blank_select($gpf) == FALSE || $validator->pattern_number($gpf) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>GPF amount is not valid.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($validator->blank_select($p_tax) == FALSE || $validator->pattern_number($p_tax) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>PTAX amount is not valid.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($validator->blank_select($i_tax) == FALSE || $validator->pattern_number($i_tax) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>ITAX amount is not valid.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($validator->blank_select($gsli) == FALSE || $validator->pattern_number($gsli) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>GSLI amount is not valid.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($validator->blank_select($gross) == FALSE || $validator->pattern_number($gross) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Gross amount is not valid.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if($other_deduction_cause=="yes_ovd1")
	{ 
		if($festival_loan_cause==1) 
		{
			if(($validator->blank_select($festival_val) == FALSE || $validator->pattern_match_csf($festival_val) == FALSE) || $festival_val==0)
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> Festival Advance Recovery amount is not valid.<strong></div>';
				header('location:zp_emp_sal_requisition.php'.$query_string);
				exit(0);
			}
		}
		if($hra_loan==1) 
		{
			if(($validator->blank_select($hra_deduction) == FALSE || $validator->pattern_match_csf($hra_deduction) == FALSE) || $hra_deduction==0)
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong> HRA Deduction amount is not valid.<strong></div>';
				header('location:zp_emp_sal_requisition.php'.$query_string);
				exit(0);
			}
		}
	}
	else if(($validator->blank_select($net) == FALSE || $validator->pattern_number($net) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Net amount is not valid.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($validator->blank_select($overdrawn) == FALSE || $validator->pattern_number($overdrawn) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Overdrawn amount is not valid.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($validator->blank_select($salary_type) == FALSE || $validator->pattern_number($salary_type) == FALSE))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please choose valid salary type.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if(($part_day!='' && ($validator->blank_select($part_day) == FALSE || $validator->pattern_number($part_day) == FALSE)))
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid part day.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	else if($is_overdrawn=='yes_ovd')
	{
		if(($cause_msg=='' && ($validator->blank_select($cause_msg) == FALSE || $validator->pattern_match_csf($cause_msg) == FALSE)))
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid cause.<strong></div>';
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
	}
	/*else if($other_deduction_cause=='yes_ovd1')
	{
	
	if(($hra_loan=='' && ($validator->blank_select($hra_loan) == FALSE )))
	{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please select Deduction cause.<strong></div>';
	header('location:zp_emp_sal_requisition.php'.$query_string);
	exit(0);
	}
	}*/
	else if($salary_type=='1' || $salary_type=='2')
	{
		if($hra_loan==1)
		{
			if($hra_deduction=='' || $hra_deduction==0)
			{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid HRA deduction amount.<strong></div>';
				header('location:zp_emp_sal_requisition.php'.$query_string);
				exit(0);
			}
		}
	}
	else if($salary_type=='2')
	{
		if(($part_salary_cause_msg=='' || ($validator->blank_select($part_salary_cause_msg) == FALSE || $validator->pattern_match_csf($part_salary_cause_msg) == FALSE)))
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid cause for part salary.<strong></div>';
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
	}
	else if($salary_type=='8')
	{
		if(($no_salary_cause_msg=='' || ($validator->blank_select($no_salary_cause_msg) == FALSE || $validator->pattern_match_csf($no_salary_cause_msg) == FALSE)))
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid cause for no salary.<strong></div>'; 
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
	}
	else if(($validator->blank_select($interim_relief) == FALSE || $validator->pattern_number($interim_relief) == FALSE) && $interim_relief!='0')
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Interim Relief.<strong></div>';
		header('location:zp_emp_sal_requisition.php'.$query_string);
		exit(0);
	}
	if($_POST['n7']==1)
	{
		if($hra_deduction=='' || $hra_deduction==0)
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid HRA deduction amount.<strong></div>';
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
	}
	if($_POST['n8']==1)
	{
		if($festival_val=='' || $festival_val==0)
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Festival advance recovery amount.<strong></div>';
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
	}
	/*else if($emp_desig=='1120')
	{
	if($consolidated_pay=='' || $consolidated_pay=='0')
	{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Please enter valid Consolidated Pay.<strong></div>';
	header('location:emp_sal_requisition.php'.$query_string);
	exit(0);
	}
	else if($pay_in_band!='0' || $grade_pay!='0' || $da!='0' || $hra!='0' || $ma!='0' || $conv_allow!='0' || $hill_allow!='0' || $interim_relief!='0' || $gpf!='0' || $pf_loan!='0' || $i_tax!='0' || $gsli!='0' || $overdrawn!='0' || $festival_val!='0')
	{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.<strong></div>';
	header('location:emp_sal_requisition.php'.$query_string);
	exit(0);
	}
	else if($consolidated_pay!=$gross)
	{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.<strong></div>';
	header('location:emp_sal_requisition.php'.$query_string);
	exit(0);
	}
	else if(($consolidated_pay-$p_tax)!=$net)
	{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.<strong></div>';
	header('location:emp_sal_requisition.php'.$query_string);
	exit(0);
	}
	
	}*/
	//else if($emp_desig!='1120')
	if($emp_desig!='1')
	{
		/*if($consolidated_pay!='0')
		{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.<strong></div>';
		header('location:emp_sal_requisition.php'.$query_string);
		exit(0);
		}*/
		if($total_allowance!=$gross)
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.<strong></div>';
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
		else if(($total_allowance-$total_deductions)!=$net)
		{
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Security Error.<strong></div>';
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
	}
	
	$crypto = new cryptography();
	
	$arr = $db->fetch_table("SELECT pay_payband,salary_type 
							FROM prd_employee_salary_save 
							WHERE emp_id_fk='".$crypto->decode($emp_id_pk,4)."' AND zp_id_fk='".$_SESSION['location']['district_id']."' 
							AND salary_monthyear='".date('Ym')."' AND requisition_type='".$requisition_type."'");
	
	if($arr[0]['salary_type'])
	{
		pg_query("begin");
		
		$upd = $db->update("UPDATE prd_employee_salary_save
							SET delete_status='0',is_saved=0
							WHERE emp_id_fk='".$crypto->decode($emp_id_pk,4)."' AND zp_id_fk='".$_SESSION['location']['district_id']."' 
							AND salary_monthyear='".date('Ym')."' AND requisition_type='".$requisition_type."'");	
		
		
		$ins = $db->insert("INSERT INTO prd_employee_salary_save(slno,latestupdate_time, latestupdate_ip_address, zp_id_fk, empcd, 
																bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
																i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
																spl_alo, status_flag, salary_monthyear, 
																category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
																hill_allowance, gpf, cpf_deduct, gross_salary, is_saved,conv_allow,
																overdrawn,salary_type,cause,gp_code,part_day,gsli,delete_status,
																consolidated_pay,festival_loan,festival_loan_cause,part_salary_cause,
																no_salary_cause,interim_relief,requisition_type,gp_id_fk,total_loan_deduction,
																hra_deduction,hra_loan_cause,other_deduction_cause,zp_emp_type,emp_desig
																)
																VALUES ('$rank', 'now()', '$user_ip', '".$_SESSION['location']['district_id']."', '0', 
																'$bankname', '$accountno', '$basic', '$da', '$hra', '$ma', '0', '0', '$p_tax', 
																'$i_tax', '$net', '$bank_ifsc', 'NA', '0', '$p_tax', '0', 
																'0', '1', '$salary_monthyear', 
																'1', '0', '".$crypto->decode($emp_id_pk,4)."', 
																'$pay_in_band', '$grade_pay', '$hill_allow','$gpf', '0', '$gross', '0',
																'$conv_allow','$overdrawn','$salary_type','$cause_msg','".$_SESSION['user_info']['stake_user']."',
																'$part_day','$gsli','1','$consolidated_pay',
																'$festival_val','$festival_loan_cause','$part_salary_cause_msg',
																'$no_salary_cause_msg','$interim_relief','".$requisition_type."','0','".$total_loan_deduction."','".$hra_deduction."','".$hra_loan."','".$other_deduction_cause."','".$zp_emp_type."','".$emp_desig."')");	
		
		
		if($upd && $ins)
		{					
			pg_query("commit"); 
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>The salary requisition of '.$tchname.' has been successfully updated.</strong></div>';
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
		else
		{
			pg_query("rollback");
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Salary Requisition Updation Fails.</strong></div>';
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
	}
	else
	{
		pg_query("begin");
		
		$ins = $db->insert("INSERT INTO prd_employee_salary_save(slno,latestupdate_time, latestupdate_ip_address, zp_id_fk, empcd, 
																bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
																i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
																spl_alo, status_flag, salary_monthyear, 
																category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
																hill_allowance, gpf, cpf_deduct, gross_salary, is_saved,conv_allow,
																overdrawn,salary_type,cause,gp_code,part_day,gsli,delete_status,
																consolidated_pay,festival_loan,festival_loan_cause,part_salary_cause,
																no_salary_cause,interim_relief,requisition_type,gp_id_fk,total_loan_deduction,
																hra_deduction,hra_loan_cause,other_deduction_cause,zp_emp_type,emp_desig
																)
																VALUES ('$rank', 'now()', '$user_ip', '".$_SESSION['location']['district_id']."', '0', 
																'$bankname', '$accountno', '$basic', '$da', '$hra', '$ma', '0', '0', '$p_tax', 
																'$i_tax', '$net', '$bank_ifsc', 'NA', '0', '$p_tax', '0', 
																'0', '1', '$salary_monthyear', 
																'1', '0', '".$crypto->decode($emp_id_pk,4)."', 
																'$pay_in_band', '$grade_pay', '$hill_allow','$gpf', '0', '$gross', '0',
																'$conv_allow','$overdrawn','$salary_type','$cause_msg',
																'".$_SESSION['user_info']['stake_user']."',
																'$part_day','$gsli','1','$consolidated_pay',
																'$festival_val','$festival_loan_cause','$part_salary_cause_msg',
																'$no_salary_cause_msg','$interim_relief','".$requisition_type."','0',
																'".$total_loan_deduction."','".$hra_deduction."','".$hra_loan."',
																'".$other_deduction_cause."','".$zp_emp_type."','".$emp_desig."')");
		
		if($ins)
		{					
			pg_query("commit"); 
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>The salary requisition of '.$tchname.' has been successfully updated.</strong></div>';
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
		else
		{
			pg_query("rollback");
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Salary Requisition Updation Fails.</strong></div>';
			header('location:zp_emp_sal_requisition.php'.$query_string);
			exit(0);
		}
	}
}