<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

ob_start();
session_start();

//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Origin");
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config_api.php';
require '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
include( '../../../all_function/fun_store/ifms_functions.php');

//$path = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_006/';
$path = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_006/';
$crypto = new cryptography();
//header("Contet-type : text/xml");
$schcd = '3299001';
$mo = date('m');
$ye = date('Y');

$drn_number =$crypto->decode($_GET['drn_no'],4);
$bill_type =$crypto->decode($_GET['bill_type'],4);
$emp_type=$crypto->decode($_GET['emp_type'],4); 
$monthyear=$_GET['monthyear'];
$bill_serial_no=$crypto->decode($_GET['bill_serial_no'],4);
$yemo = $ye.$mo;
$db = new database();
if($bill_type=='1001')
{
$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1' AND zp_emp_type='".$emp_type."'");
}
else if($bill_type=='1002')
{
$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1' AND zp_emp_type='".$emp_type."' AND bill_serial_no='".$bill_serial_no."'");
}
else if($bill_type=='1004')
{
$emp_type='0';
$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1'  AND bill_serial_no='".$bill_serial_no."'");

}
else if($bill_type=='1003')
{
$emp_type='0';
$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1'  AND bill_serial_no='".$bill_serial_no."'");

}

else if($bill_type=='1005')
{
$emp_type='0';
$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1'  AND bill_serial_no='".$bill_serial_no."'");

}


$sftp_file_existance_check=$db->fetch_table(" SELECT sftp_benf_id_pk FROM prd_sftp_benf_upload_response WHERE bill_id_fk='".$bill_det[0]['block_bill_pk']."' AND active_status='1' ");

if(count($sftp_file_existance_check)>0)
{
	echo "File has been sent already. Do not send again.";
	exit;
}
else
{
	
	$party_code='008';
	
	
	$ddo_sql = $db->fetch_table("Select ddo_code,pl_code from zpemp_zp_profile where district_id_fk = '".$_SESSION['location']['district_id']."'");
	
	$ddo_code=substr($ddo_sql[0]['ddo_code'],0,3);
	$pl_operator=str_pad($ddo_sql[0]['pl_code'],6,'0',STR_PAD_LEFT);
	
	$financial_year = (date("m") >= 4) ? (date("Y")."-".(date("Y")+1)) : ((date("Y")-1)."-".date("Y"));
	
	if($bill_type=='1001')
	{
	$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$yemo."' AND requisition_type='".$bill_type."' AND status='1' AND zp_emp_type='".$emp_type."'");
	}
	else if($bill_type=='1002' || $bill_type=='1003' || $bill_type=='1004' || $bill_type=='1005')
	{
	$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1' AND zp_emp_type='".$emp_type."'");
	
	}
	
	$sftp_details_fetch=$db->fetch_table(" SELECT sftp_benf_id_pk,sftp_benf_file_name FROM prd_sftp_benf_upload_response WHERE bill_id_fk='".$bill_det[0]['block_bill_pk']."' AND active_status='2' ");
	
	if(count($sftp_details_fetch)==0)
	{ 
		$file_sequence='01';
	}
	else
	{
		
		$seq_no=substr($sftp_details_fetch[0]['sftp_benf_file_name'],20,2);
		$sum=$seq_no+1;
		$file_sequence=str_pad($sum,2,'0',STR_PAD_LEFT);
	}
	
	
	
	$det_head=$db->fetch_table("SELECT * FROM prd_head_details WHERE block_code='".$schcd."'");
	
	$no_of_row=count($det_head);   
	
	if($bill_type=='1001')
{
	$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name, 
									emp.gp_id_fk ,
									emp.emp_status,
									emp.emp_sex,
									emp.emp_grade_pay,
									emp.emp_caste,
									emp.emp_id_const,
									sal.salary_monthyear,
									emp.emp_bank_name,
									emp.emp_acc_no,
									emp.emp_mobile_no,
									sal.accountno,
									sal.bank_ifsc,
									sal.basic,
									sal.da,
									sal.interim_relief,
									sal.hra,
									sal.ma, 
									sal.gpf,
									sal.pf_loan, 
									sal.p_tax, 
									sal.i_tax,
									sal.net, 
									emp.emp_ifsc_no, 
									sal.pf_deduct,
									sal.conv_allow,
									sal.consolidated_pay,
									sal.hill_allowance,
									sal.festival_loan,
									sal.overdrawn,
									sal.gsli,
									sal.gross_salary,
									sal.hra_deduction,
									sal.total_loan_deduction,
									sal.other_loan_deduction
									FROM prd_employee_master emp
									INNER JOIN prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.zp_id_fk=emp.zp_id_fk)
								where 
								trim(sal.salary_monthyear)='".date('Ym')."'
									AND emp.emp_status in('1','9') 
									AND sal.delete_status='1' 
									AND sal.status_flag='4'
									AND sal.is_saved='1'
									AND sal.zp_id_fk='" . $_SESSION['location']['district_id'] . "' 
									AND sal.requisition_type='".$bill_type."'
									AND sal.zp_emp_type='".$emp_type."'
								    AND sal.salary_type!='8'
									AND sal.gross_salary!='0'
									ORDER BY emp.gp_id_fk ASC");
	
	
	
	//$sql_teacher=$db->fetch_table($teacher_sql);
	
	$fetch_code_master=$db->fetch_table("select code, description from prd_dise_code_master");
	
	
	
	foreach($teacher_dtls as $teacher_row)
	{
		//$teacher_row['basic'];
		//$ern_amount_grade=($ern_amount_grade)+ ($teacher_row['tch_grade_pay']);
		$ern_amount_da=($ern_amount_da)+ ($teacher_row['da']);
		$ern_amount_interim_relief=($ern_amount_interim_relief)+ ($teacher_row['interim_relief']);
		$ern_amount_hra=($ern_amount_hra)+ ($teacher_row['hra']);
		$ern_amount_ma=($ern_amount_ma)+ ($teacher_row['ma']);
		$ern_amount_basic=($ern_amount_basic)+ ($teacher_row['basic']);
		$ern_amount_spl_pay=($ern_amount_spl_pay)+ ($teacher_row['spl_pay']);
		$ern_amount_conv_allow=($ern_amount_conv_allow)+ ($teacher_row['conv_allow']);
		$hill_allowance=($hill_allowance)+ ($teacher_row['hill_allowance']);
		$consolidated_pay=($consolidated_pay)+ ($teacher_row['consolidated_pay']);
		$gpf=($gpf)+ ($teacher_row['gpf']);
		$p_tax=($p_tax)+ ($teacher_row['p_tax']);
		$i_tax=($i_tax)+ ($teacher_row['i_tax']);
		//$pf_total=($pf_total)+ ($teacher_row['pf_deduct']);
		$pf_loan_total=($pf_loan_total)+ ($teacher_row['pf_loan']);
		$cooperative_loan=($cooperative_loan)+ ($teacher_row['cooperative_loan']); 
		$hbl_loan=($hbl_loan)+ ($teacher_row['hbl_loan']); 
		$festival_loan=($festival_loan)+ ($teacher_row['festival_loan']); 
		$overdrawn=($overdrawn)+ ($teacher_row['overdrawn']);
		$advance_amount=($advance_amount)+ ($teacher_row['advance_amount']);
		$gsli_deduction=($gsli_deduction)+ ($teacher_row['gsli']);
		$hra_deduction=($hra_deduction)+ ($teacher_row['hra_deduction']);
		$out_acc_deduction=($out_acc_deduction)+ ($teacher_row['other_loan_deduction']);
		$total_loan_deduction=($total_loan_deduction)+ ($teacher_row['total_loan_deduction']);
		$gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
		$net_salary=($net_salary)+ ($net_salary['net']);
		$ag_total=$gpf+$pf_loan_total;
	
	}
	$ern_amount=($gross_salary)-($overdrawn)-($festival_loan)-($advance_amount);
	$ern_amount1=($gross_salary);
	
	$ag_total=($gpf)+($pf_loan_total);
	$deduc_amt=($i_tax)+($p_tax);
	//$deduc_total= ($deduc_amt)+($ag_total);
	$deduc_total= ($deduc_amt)+($ag_total)+($hra_deduction)+($gsli_deduction)+($out_acc_deduction)+($total_loan_deduction);
	$net_amount=($ern_amount)-($deduc_total);
}

else if($bill_type=='1003')
{
	$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name, 
									emp.gp_id_fk ,
									emp.emp_status,
									emp.emp_sex,
									emp.emp_grade_pay,
									emp.emp_caste,
									emp.emp_id_const,
									sal.salary_monthyear,
									emp.emp_bank_name,
									emp.emp_acc_no,
									emp.emp_mobile_no,
									sal.accountno,
									sal.bank_ifsc,
									sal.basic,
									sal.da,
									sal.interim_relief,
									sal.hra,
									sal.ma, 
									sal.gpf,
									sal.pf_loan, 
									sal.p_tax, 
									sal.i_tax,
									sal.net, 
									emp.emp_ifsc_no, 
									sal.pf_deduct,
									sal.conv_allow,
									sal.consolidated_pay,
									sal.hill_allowance,
									sal.festival_loan,
									sal.overdrawn,
									sal.gsli,
									sal.gross_salary,
									sal.hra_deduction,
									sal.total_loan_deduction,
									sal.other_loan_deduction
									FROM prd_employee_master emp
									INNER JOIN prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.zp_id_fk=emp.zp_id_fk)
								where 
								trim(sal.salary_monthyear)='".$monthyear."'
									AND emp.emp_status in('1','9') 
									AND sal.delete_status='1' 
									AND sal.status_flag='4'
									AND sal.is_saved='1'
									AND sal.zp_id_fk='" . $_SESSION['location']['district_id'] . "' 
									AND sal.requisition_type='".$bill_type."'
								    AND sal.salary_type!='8'
									AND sal.gross_salary!='0'
									ORDER BY emp.gp_id_fk ASC");
	
	
	
	//$sql_teacher=$db->fetch_table($teacher_sql);
	
	$fetch_code_master=$db->fetch_table("select code, description from prd_dise_code_master");
	
	
	
	foreach($teacher_dtls as $teacher_row)
	{
		//$teacher_row['basic'];
		//$ern_amount_grade=($ern_amount_grade)+ ($teacher_row['tch_grade_pay']);
		$ern_amount_da=($ern_amount_da)+ ($teacher_row['da']);
		$ern_amount_interim_relief=($ern_amount_interim_relief)+ ($teacher_row['interim_relief']);
		$ern_amount_hra=($ern_amount_hra)+ ($teacher_row['hra']);
		$ern_amount_ma=($ern_amount_ma)+ ($teacher_row['ma']);
		$ern_amount_basic=($ern_amount_basic)+ ($teacher_row['basic']);
		$ern_amount_spl_pay=($ern_amount_spl_pay)+ ($teacher_row['spl_pay']);
		$ern_amount_conv_allow=($ern_amount_conv_allow)+ ($teacher_row['conv_allow']);
		$hill_allowance=($hill_allowance)+ ($teacher_row['hill_allowance']);
		$consolidated_pay=($consolidated_pay)+ ($teacher_row['consolidated_pay']);
		$gpf=($gpf)+ ($teacher_row['gpf']);
		$p_tax=($p_tax)+ ($teacher_row['p_tax']);
		$i_tax=($i_tax)+ ($teacher_row['i_tax']);
		//$pf_total=($pf_total)+ ($teacher_row['pf_deduct']);
		$pf_loan_total=($pf_loan_total)+ ($teacher_row['pf_loan']);
		$cooperative_loan=($cooperative_loan)+ ($teacher_row['cooperative_loan']); 
		$hbl_loan=($hbl_loan)+ ($teacher_row['hbl_loan']); 
		$festival_loan=($festival_loan)+ ($teacher_row['festival_loan']); 
		$overdrawn=($overdrawn)+ ($teacher_row['overdrawn']);
		$advance_amount=($advance_amount)+ ($teacher_row['advance_amount']);
		$gsli_deduction=($gsli_deduction)+ ($teacher_row['gsli']);
		$hra_deduction=($hra_deduction)+ ($teacher_row['hra_deduction']);
		$out_acc_deduction=($out_acc_deduction)+ ($teacher_row['other_loan_deduction']);
		$total_loan_deduction=($total_loan_deduction)+ ($teacher_row['total_loan_deduction']);
		$gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
		$net_salary=($net_salary)+ ($net_salary['net']);
		$ag_total=$gpf+$pf_loan_total;
	
	}
	$ern_amount=($gross_salary)-($overdrawn)-($festival_loan)-($advance_amount);
	$ern_amount1=($gross_salary);
	
	$ag_total=($gpf)+($pf_loan_total);
	$deduc_amt=($i_tax)+($p_tax);
	//$deduc_total= ($deduc_amt)+($ag_total);
	$deduc_total= ($deduc_amt)+($ag_total)+($hra_deduction)+($gsli_deduction)+($out_acc_deduction)+($total_loan_deduction);
	$net_amount=($ern_amount)-($deduc_total);
}




else if($bill_type=='1002')
{
	
	
	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND  zp_id_fk = '".$_SESSION['location']['district_id']."'
				AND bill_serial_no='".$bill_serial_no."' and status='1'");
				

	$teacher_dtls=$db->fetch_table("select  
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name, 
									emp.gp_id_fk ,
									emp.emp_status,
									emp.emp_sex,
									emp.emp_grade_pay,
									emp.emp_caste,
									emp.emp_mobile_no,
									emp.emp_id_const,									
									sal.salary_monthyear,
									emp.emp_bank_name,
									emp.emp_acc_no,
									sal.accountno,
									sal.bank_ifsc,
									sal.basic,
									sal.da,
									sal.interim_relief,
									sal.hra,
									sal.ma, 
									sal.gpf,
									sal.pf_loan, 
									sal.p_tax, 
									sal.i_tax,
									sal.net, 
									emp.emp_ifsc_no, 
									sal.conv_allow,
									sal.consolidated_pay,
									sal.hill_allowance,
									sal.gross_salary
									FROM prd_employee_master emp
									INNER JOIN prd_employee_arrear sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.zp_id_fk=emp.zp_id_fk)
								where 
								trim(sal.salary_monthyear)='".$monthyear."'
									AND emp.emp_status in('1','9') 
									AND sal.delete_status='1' 
									AND sal.status_flag='3'
									AND sal.is_saved='1'
									AND sal.zp_id_fk='" . $_SESSION['location']['district_id'] . "' 
									AND sal.zp_emp_type='".$emp_type."'
									AND sal.bill_serial_no='".$bill_serial_no."'
									AND sal.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
									");
	
	foreach($teacher_dtls as $teacher_row)
	{
		//$teacher_row['basic'];
		//$ern_amount_grade=($ern_amount_grade)+ ($teacher_row['tch_grade_pay']);
		$ern_amount_da=($ern_amount_da)+ ($teacher_row['da']);
		$ern_amount_interim_relief=($ern_amount_interim_relief)+ ($teacher_row['interim_relief']);
		$ern_amount_hra=($ern_amount_hra)+ ($teacher_row['hra']);
		$ern_amount_ma=($ern_amount_ma)+ ($teacher_row['ma']);
		$ern_amount_basic=($ern_amount_basic)+ ($teacher_row['basic']);
		$ern_amount_spl_pay=($ern_amount_spl_pay)+ ($teacher_row['spl_pay']);
		$ern_amount_conv_allow=($ern_amount_conv_allow)+ ($teacher_row['conv_allow']);
		$hill_allowance=($hill_allowance)+ ($teacher_row['hill_allowance']);
		$consolidated_pay=($consolidated_pay)+ ($teacher_row['consolidated_pay']);
		$gpf=($gpf)+ ($teacher_row['gpf']);
		$p_tax=($p_tax)+ ($teacher_row['p_tax']);
		$i_tax=($i_tax)+ ($teacher_row['i_tax']);
		//$pf_total=($pf_total)+ ($teacher_row['pf_deduct']);
		/*$pf_loan_total=($pf_loan_total)+ ($teacher_row['pf_loan']);
		$cooperative_loan=($cooperative_loan)+ ($teacher_row['cooperative_loan']); 
		$hbl_loan=($hbl_loan)+ ($teacher_row['hbl_loan']); 
		$festival_loan=($festival_loan)+ ($teacher_row['festival_loan']); 
		$overdrawn=($overdrawn)+ ($teacher_row['overdrawn']);
		$advance_amount=($advance_amount)+ ($teacher_row['advance_amount']);*/
		$gsli_deduction=0;
		$hra_deduction=0;
		$out_acc_deduction=0;
		$total_loan_deduction=0;
		//$gsli_deduction=($gsli_deduction)+ ($teacher_row['gsli']);
		//$hra_deduction=($hra_deduction)+ ($teacher_row['hra_deduction']);
		//$out_acc_deduction=($out_acc_deduction)+ ($teacher_row['other_loan_deduction']);
		//$total_loan_deduction=($total_loan_deduction)+ ($teacher_row['total_loan_deduction']);
		$gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
		$net_salary=($net_salary)+ ($net_salary['net']);
		$ag_total=$gpf+$pf_loan_total;
	
	}
	$ern_amount=($gross_salary)-($overdrawn)-($festival_loan)-($advance_amount);
	$ern_amount1=($gross_salary);
	
	$ag_total=($gpf)+($pf_loan_total);
	$deduc_amt=($i_tax)+($p_tax);
	//$deduc_total= ($deduc_amt)+($ag_total);
	$deduc_total= ($deduc_amt)+($ag_total)+($hra_deduction)+($gsli_deduction)+($out_acc_deduction)+($total_loan_deduction);
	$net_amount=($ern_amount)-($deduc_total);
	
	
}


else if($bill_type=='1004')
{
	
		
$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1'  AND bill_serial_no='".$bill_serial_no."'");

	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
	salary_monthyear = '" . $monthyear . "' AND  zp_id_fk = '".$_SESSION['location']['district_id']."'
	AND bill_serial_no='".$bill_serial_no."' and status='1'");
				
				

	$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name, 
									emp.gp_id_fk ,
									emp.emp_status,
									emp.emp_sex,
									
									emp.emp_mobile_no,
									emp.emp_id_const,									
									sal.bonus_amount as net,
									emp.emp_bank_name,
									emp.emp_acc_no as accountno,
									emp.emp_acc_no,
									emp.emp_ifsc_no,
									emp.emp_ifsc_no as bank_ifsc
									
									FROM prd_employee_master emp
									INNER JOIN prd_employee_bonus_details sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.zp_id_fk=emp.zp_id_fk)
								where 
								 emp.emp_status in('1','9') 
									AND sal.delete_status='1' 
									AND sal.bonus_status='5'
									AND sal.zp_id_fk='" . $_SESSION['location']['district_id'] . "' 
									AND sal.bill_serial_no='".$bill_serial_no."'
									AND sal.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
									");
	
	foreach($teacher_dtls as $teacher_row)
	{
		//$teacher_row['basic'];
		//$ern_amount_grade=($ern_amount_grade)+ ($teacher_row['tch_grade_pay']);
		$ern_amount_da=0;
		$ern_amount_interim_relief=0;
		$ern_amount_hra=0;
		$ern_amount_ma=0;
		$ern_amount_basic=0;
		$ern_amount_spl_pay=0;
		$ern_amount_conv_allow=0;
		$hill_allowance=0;
		$consolidated_pay=0;
		$gpf=0;
		$p_tax=0;
		$i_tax=0;
		//$pf_total=($pf_total)+ ($teacher_row['pf_deduct']);
		/*$pf_loan_total=($pf_loan_total)+ ($teacher_row['pf_loan']);
		$cooperative_loan=($cooperative_loan)+ ($teacher_row['cooperative_loan']); 
		$hbl_loan=($hbl_loan)+ ($teacher_row['hbl_loan']); 
		$festival_loan=($festival_loan)+ ($teacher_row['festival_loan']); 
		$overdrawn=($overdrawn)+ ($teacher_row['overdrawn']);
		$advance_amount=($advance_amount)+ ($teacher_row['advance_amount']);*/
		$gsli_deduction=0;
		$hra_deduction=0;
		$out_acc_deduction=0;
		$total_loan_deduction=0;
		//$gsli_deduction=($gsli_deduction)+ ($teacher_row['gsli']);
		//$hra_deduction=($hra_deduction)+ ($teacher_row['hra_deduction']);
		//$out_acc_deduction=($out_acc_deduction)+ ($teacher_row['other_loan_deduction']);
		//$total_loan_deduction=($total_loan_deduction)+ ($teacher_row['total_loan_deduction']);
		$gross_salary=($gross_salary)+ ($teacher_row['net']);
		$net_salary=($net_salary)+ ($net_salary['net']);
		$ag_total=$gpf+$pf_loan_total;
	
	}
	$ern_amount=($gross_salary)-($overdrawn)-($festival_loan)-($advance_amount);
	$ern_amount1=($gross_salary);
	
	$ag_total=($gpf)+($pf_loan_total);
	$deduc_amt=($i_tax)+($p_tax);
	//$deduc_total= ($deduc_amt)+($ag_total);
	$deduc_total= ($deduc_amt)+($ag_total)+($hra_deduction)+($gsli_deduction)+($out_acc_deduction)+($total_loan_deduction);
	//$net_amount=($ern_amount)-($deduc_total);
	$net_amount=$gross_salary;
	
}

else if($bill_type=='1005')
{
	
		
$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE zp_id_fk='".$_SESSION['location']['district_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1'  AND bill_serial_no='".$bill_serial_no."'");

	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
	salary_monthyear = '" . $monthyear . "' AND  zp_id_fk = '".$_SESSION['location']['district_id']."'
	AND bill_serial_no='".$bill_serial_no."' and status='1'");
				
				
				$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
									
									sal.festival_advance_total_amount as net,
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name, 
									emp.gp_id_fk ,
									emp.emp_status,
									emp.emp_sex,
									
									emp.emp_mobile_no,
									emp.emp_id_const,
									
									emp.emp_bank_name,
									emp.emp_acc_no as accountno,
									emp.emp_acc_no,
									emp.emp_ifsc_no,
									emp.emp_ifsc_no as bank_ifsc
							
									
									FROM prd_employee_master emp
									INNER JOIN prd_festival_advance_employee_details sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.zp_id_fk=emp.zp_id_fk)
								where 
								
									 emp.emp_status in('1','9') 
									
									AND sal.festival_advance_status='5'
									AND sal.fad_monthyear='" . $monthyear . "'
									
									AND sal.zp_id_fk='" . $_SESSION['location']['district_id'] . "' 
									
									AND sal.bill_serial_no='".$bill_serial_no."'
									AND sal.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
									");

	
	
	foreach($teacher_dtls as $teacher_row)
	{
		//$teacher_row['basic'];
		//$ern_amount_grade=($ern_amount_grade)+ ($teacher_row['tch_grade_pay']);
		$ern_amount_da=0;
		$ern_amount_interim_relief=0;
		$ern_amount_hra=0;
		$ern_amount_ma=0;
		$ern_amount_basic=0;
		$ern_amount_spl_pay=0;
		$ern_amount_conv_allow=0;
		$hill_allowance=0;
		$consolidated_pay=0;
		$gpf=0;
		$p_tax=0;
		$i_tax=0;
		//$pf_total=($pf_total)+ ($teacher_row['pf_deduct']);
		/*$pf_loan_total=($pf_loan_total)+ ($teacher_row['pf_loan']);
		$cooperative_loan=($cooperative_loan)+ ($teacher_row['cooperative_loan']); 
		$hbl_loan=($hbl_loan)+ ($teacher_row['hbl_loan']); 
		$festival_loan=($festival_loan)+ ($teacher_row['festival_loan']); 
		$overdrawn=($overdrawn)+ ($teacher_row['overdrawn']);
		$advance_amount=($advance_amount)+ ($teacher_row['advance_amount']);*/
		$gsli_deduction=0;
		$hra_deduction=0;
		$out_acc_deduction=0;
		$total_loan_deduction=0;
		//$gsli_deduction=($gsli_deduction)+ ($teacher_row['gsli']);
		//$hra_deduction=($hra_deduction)+ ($teacher_row['hra_deduction']);
		//$out_acc_deduction=($out_acc_deduction)+ ($teacher_row['other_loan_deduction']);
		//$total_loan_deduction=($total_loan_deduction)+ ($teacher_row['total_loan_deduction']);
		$gross_salary=($gross_salary)+ ($teacher_row['net']);
		$net_salary=($net_salary)+ ($net_salary['net']);
		$ag_total=$gpf+$pf_loan_total;
	
	}
	$ern_amount=($gross_salary)-($overdrawn)-($festival_loan)-($advance_amount);
	$ern_amount1=($gross_salary);
	
	$ag_total=($gpf)+($pf_loan_total);
	$deduc_amt=($i_tax)+($p_tax);
	//$deduc_total= ($deduc_amt)+($ag_total);
	$deduc_total= ($deduc_amt)+($ag_total)+($hra_deduction)+($gsli_deduction)+($out_acc_deduction)+($total_loan_deduction);
	$net_amount=($ern_amount)-($deduc_total);
	
	
}
	$total_beneficiary=count($teacher_dtls);
	
	if($validator->blank_select($drn_number) == FALSE || $drn_number == '0')
	{
		echo 'DRN Number Missing.';
		exit;
	}
	else if($validator->blank_select($net_amount) == FALSE || $net_amount == '0')
	{
		echo 'Net Amount Missing.';
		exit;
	}
	else if($validator->blank_select($total_beneficiary) == FALSE || $total_beneficiary == '0')
	{
		echo 'Total Beneficiary Missing.';
		exit;
	}
	
	$xml =  new DOMDocument("1.0","UTF-8");
	
	$container = $xml->createElement('bulkecs');
	$container->setAttribute("totalamount",$net_amount);
	$container->setAttribute("benfcount",$total_beneficiary);
	$container = $xml->appendChild($container);
	
	$drn=$xml->createElement('DRN',$drn_number);
	$drn = $container->appendChild($drn);
	
	
	
	foreach($teacher_dtls as $teacher_row)
	{
		/*$bank = $xml->createElement('BANK_DETAIL');
		$bank = $container->appendChild($bank);*/
		
		if($validator->blank_select($teacher_row['emp_first_name']) == FALSE)
		{
			echo 'Beneficiary Name Missing.';
			exit;
		}
		else if($validator->blank_select($teacher_row['emp_acc_no']) == FALSE || $teacher_row['emp_acc_no'] == '0')
		{
			echo 'Account Number Missing.';
			exit;
		}
		else if($validator->blank_select($teacher_row['emp_ifsc_no']) == FALSE || $teacher_row['emp_ifsc_no'] == '0')
		{
			echo 'IFSC Code Missing.';
			exit;
		}
		else if($validator->blank_select($teacher_row['emp_mobile_no']) == FALSE || $teacher_row['emp_mobile_no'] == '0')
		{
			echo 'Mobile Number Missing.';
			exit;
		}
		else if($validator->blank_select($teacher_row['emp_id_const']) == FALSE || $teacher_row['emp_id_const'] == '0')
		{
			echo 'Employee ID Missing.';
			exit;
		}
		else if(($validator->blank_select($teacher_row['net']) == FALSE && $bill_type!='1004') || ($validator->blank_select($teacher_row['net']) == FALSE && $bill_type=='1004'))
		{
			echo 'Amount Missing.';
			exit;
		}
		
		if (trim($teacher_row['emp_second_name']) == '') 
		{
			$last_name = preg_replace('/\s+/', ' ', trim($teacher_row['emp_last_name']));
			$tch_name = trim($teacher_row['emp_first_name']) . " " . trim($last_name);
		} 
		else 
		{
			$last_name = preg_replace('/\s+/', ' ', trim($teacher_row['emp_last_name']));
			$tch_name = trim($teacher_row['emp_first_name']) . " " . trim($teacher_row['emp_second_name']) . " " . trim($last_name);
		}
	
		$beneficiary =  $xml->createElement('BENEFICIARY');
		$beneficiary = $container->appendChild($beneficiary);
		
		$benf_name = $xml->createElement('BENF_NAME',$tch_name);
		$benf_name = $beneficiary->appendChild($benf_name);
		
		$ano = $xml->createElement('ACCOUNT_NO',$teacher_row['accountno']);
		$ano = $beneficiary->appendChild($ano);
		
		$ifsc = $xml->createElement('IFSC_CODE',$teacher_row['bank_ifsc']);
		$ifsc = $beneficiary->appendChild($ifsc);
	
		$mob_no = $xml->createElement('MOBILE_NO',$teacher_row['emp_mobile_no']);
		$mob_no = $beneficiary->appendChild($mob_no);
		
		$amount = $xml->createElement('AMOUNT',$teacher_row['net']);
		$amount = $beneficiary->appendChild($amount);
		
		$u_id = $xml->createElement('ID',$teacher_row['emp_id_const']);
		$u_id = $beneficiary->appendChild($u_id);
	
		$on = $xml->createElement('ORDER_NO');
		$on = $beneficiary->appendChild($on);
		
		$u_id = $xml->createElement('UNIQUE_ID');
		$u_id = $beneficiary->appendChild($u_id);
		
	}
	
	
	
	$sys_date=date('dmY');
	
	$seq_number=substr($drn_number,9,6);
	
	//$xml->formatOutput = FALSE;
	/*if($xml->formatOutput = TRUE)
	{*/
		$filename = $ddo_code.$pl_operator.$party_code.$sys_date.$file_sequence.$seq_number.".xml";
		$name = strftime($filename);
		//header('Content-Disposition: attachment;filename=' . $name);
		//header('Content-Type: text/xml');
		$xml->saveXML();
		$xml->save($path.$name);
		//die;
	//}
/*if($_SESSION['location']['district_id']=='21' )
		 {
		//print_r($data); die;
		 die;
		 }*/
	
	//die;
	
	/*set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
	include('Net/SFTP.php');
	$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_006/';
	$remote_dept = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/';  */
	set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
	include('Net/SFTP.php');
	$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmszp/ePayment_Files_006/';
	$remote_dept = '/ifms_web_cache/ekuber/ePaymentFiles/gen008/'; 
	$ben_upload=sftp_benf_upload($config['sftp_ip_zp'],$config['sftp_user_name_zp'],$config['sftp_user_password_zp'],$remote_dept,$local_directory,$filename);
	
	if($ben_upload==2)
	{
		//echo "Sorry! Connection Fails";
		echo 2; 
	}
	else if($ben_upload==0)
	{
		//echo "Sorry! Beneficiary Upload Fails";
		echo 0;
	}
	else if($ben_upload==1)
	{
		//echo "Success";
		pg_query('BEGIN');
		
		if($sftp_details_fetch[0]['sftp_benf_id_pk']=='')
		{
			 $sftp_id=0; 
		}
		else
		{
			 $sftp_id=$sftp_details_fetch[0]['sftp_benf_id_pk']; 
		}
		
		$update_sftp_benf_details=$db->update(" UPDATE prd_sftp_benf_upload_response SET active_status='0' WHERE sftp_benf_id_pk='".$sftp_id."' AND active_status='2' ");
		
		$update_sftp_benf_failure=$db->update(" UPDATE prd_sftp_benf_failure_details SET active_status='4' WHERE sftp_benf_id_fk='".$sftp_id."' AND active_status='3'");
	
		$insert_sftp_benf_details=$db->insert("INSERT INTO prd_sftp_benf_upload_response
										( bill_id_fk,
										  sftp_benf_file_name,
										  sftp_benf_sending_status,
										  sftp_benf_sending_time,
										  sftp_benf_sending_ip,
										  client_type,
										  active_status,
										  total_beneficiary,
										  total_amount)
										VALUES
										('".$bill_det[0]['block_bill_pk']."',
										'".$filename."',
										'3',
										'now();',
										'".$_SERVER['REMOTE_ADDR']."',
										557,
										'1',
										'".$total_beneficiary."',
										'".$net_amount."') ");
										
		if($update_sftp_benf_details && $update_sftp_benf_failure && $insert_sftp_benf_details)
		{
			pg_query('COMMIT');
			//echo "File Send Successfully";
			echo 1;
		}
		else
		{
			pg_query('ROLLBACK');
			//echo "File Send Successfully";
			echo 0;
		}
	
	}
	
}

?>


