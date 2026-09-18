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

$path = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_006/';

$crypto = new cryptography();
//header("Contet-type : text/xml");
$schcd = '3299001';
$mo = date('m');
$ye = date('Y');

$drn_number =$crypto->decode($_GET['drn_no'],4);
$bill_type =$crypto->decode($_GET['bill_type'],4);
$monthyear=$_GET['monthyear'];
$monthyear=$_GET['monthyear'];
$bill_serial_no=$crypto->decode($_GET['bill_serial_no'],4);
  $ropa_status=$crypto->decode($_GET['ropa_status_new'],4);

//$yemo = $ye.$mo;
$yemo = $ye.$mo;
$db = new database();
if($bill_type=='1001')
{
$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1' ");
}
else if($bill_type=='1002' || $bill_type=='1004' || $bill_type=='1005' || $bill_type=='1003')
{
	$bill_det=$db->fetch_table("SELECT block_bill_pk,bill_no,bill_entry_time,salary_monthyear FROM prd_block_bill_details WHERE ps_id_fk='".$_SESSION['location']['ps_id']."' and drn_number='".$drn_number."' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1' AND bill_serial_no='".$bill_serial_no."' ");

}

$sftp_file_existance_check=$db->fetch_table(" SELECT sftp_benf_id_pk FROM prd_sftp_benf_upload_response WHERE bill_id_fk='".$bill_det[0]['block_bill_pk']."' AND active_status='1' ");

if(count($sftp_file_existance_check)>0)
{
	echo 3;
	exit;
}
else
{

	$party_code='007';
	
	
	$ddo_sql = $db->fetch_table("Select ddo_code,treasury_code from psemp_ps_profile where ps_id_fk = '".$_SESSION['location']['ps_id']."'");
	
	$ddo_code=substr($ddo_sql[0]['treasury_code'],0,3);
	$pl_operator=str_pad($ddo_sql[0]['ddo_code'],6,'0',STR_PAD_LEFT);
	
	$financial_year = (date("m") >= 4) ? (date("Y")."-".(date("Y")+1)) : ((date("Y")-1)."-".date("Y"));
	
	
	
	$sftp_details_fetch=$db->fetch_table(" SELECT sftp_benf_id_pk,sftp_benf_file_name FROM prd_sftp_benf_upload_response WHERE bill_id_fk='".$bill_det[0]['block_bill_pk']."' AND active_status='2' ");
	
	if(count($sftp_details_fetch)==0 )
	{ 
		$file_sequence='01';
	}
	else
	{
		
		$seq_no=substr($sftp_details_fetch[0]['sftp_benf_file_name'],20,2);
		$sum=$seq_no+1;
		$file_sequence=str_pad($sum,2,'0',STR_PAD_LEFT);
	}
	
	
	 
	if($bill_type=='1001')
{
	if($monthyear!=$yemo)
	{
		
	$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name, 
									emp.ps_id_fk ,
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
									INNER JOIN prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.ps_id_fk=emp.ps_id_fk)
								where 
								trim(sal.salary_monthyear)='".$monthyear."'
									AND emp.emp_status in('1','9','2') 
									AND sal.delete_status='1' 
									AND sal.status_flag='3'
									AND sal.is_saved='1'
									AND sal.ps_id_fk='" . $_SESSION['location']['ps_id'] . "' 
									AND sal.requisition_type='".$bill_type."'
									AND sal.salary_type!='8'
									AND sal.gross_salary!='0'
									AND sal.ropa_status='".$ropa_status."'
									
									ORDER BY emp.ps_id_fk ASC");
	
	}
	else
	{
		
		$teacher_dtls=$db->fetch_table("select distinct(emp.emp_system_code, emp.empcd), 
									emp.emp_first_name,
									emp.emp_second_name,
									emp.emp_last_name, 
									emp.ps_id_fk ,
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
									INNER JOIN prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.ps_id_fk=emp.ps_id_fk)
								where 
								trim(sal.salary_monthyear)='".$monthyear."'
									AND emp.emp_status in('1','9') 
									AND sal.delete_status='1' 
									AND sal.status_flag='3'
									AND sal.is_saved='1'
									AND sal.ps_id_fk='" . $_SESSION['location']['ps_id'] . "' 
									AND sal.requisition_type='".$bill_type."'
									AND sal.salary_type!='8'
									AND sal.gross_salary!='0'
									AND sal.ropa_status='".$ropa_status."'
									
									ORDER BY emp.ps_id_fk ASC");
	}
	
	//$sql_teacher=$db->fetch_table($teacher_sql);
	
	//$fetch_code_master=$db->fetch_table("select code, description from prd_dise_code_master");
	
	
	
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
									emp.ps_id_fk ,
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
									INNER JOIN prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.ps_id_fk=emp.ps_id_fk)
								where 
								trim(sal.salary_monthyear)='".$monthyear."'
									AND emp.emp_status in('1','9') 
									AND sal.delete_status='1' 
									AND sal.status_flag='3'
									AND sal.is_saved='1'
									AND sal.ps_id_fk='" . $_SESSION['location']['ps_id'] . "' 
									AND sal.requisition_type='".$bill_type."'
									AND sal.salary_type!='8'
									AND sal.gross_salary!='0'
									
									ORDER BY emp.ps_id_fk ASC");
	
	
	
	//$sql_teacher=$db->fetch_table($teacher_sql);
	
	//$fetch_code_master=$db->fetch_table("select code, description from prd_dise_code_master");
	
	
	
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
				salary_monthyear = '" . $monthyear . "' AND  ps_id_fk = '".$_SESSION['location']['ps_id']."'
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
									INNER JOIN prd_employee_arrear sal ON (sal.emp_id_fk=emp.emp_id_pk and sal.ps_id_fk=emp.ps_id_fk)
								where 
								trim(sal.salary_monthyear)='".$monthyear."'
									AND emp.emp_status in('1','9') 
									AND sal.delete_status='1' 
									AND sal.status_flag='3'
									AND sal.is_saved='1'
									AND sal.ps_id_fk='" . $_SESSION['location']['ps_id'] . "' 
									AND sal.bill_serial_no='".$bill_serial_no."'
									AND sal.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
									ORDER BY emp.gp_id_fk ASC");
									
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
		//$pf_loan_total=($pf_loan_total)+ ($teacher_row['pf_loan']);
		//$cooperative_loan=($cooperative_loan)+ ($teacher_row['cooperative_loan']); 
		//$hbl_loan=($hbl_loan)+ ($teacher_row['hbl_loan']); 
		//$festival_loan=($festival_loan)+ ($teacher_row['festival_loan']); 
		//$overdrawn=($overdrawn)+ ($teacher_row['overdrawn']);
		//$advance_amount=($advance_amount)+ ($teacher_row['advance_amount']);
		//$gsli_deduction=($gsli_deduction)+ ($teacher_row['gsli']);
		$gsli_deduction=0;
		//$hra_deduction=($hra_deduction)+ ($teacher_row['hra_deduction']);
		$hra_deduction=0;
		$gsli_deduction=0;
		$hra_deduction=0;
		$out_acc_deduction=0;
		$total_loan_deduction=0;
		
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
	
	
	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND  ps_id_fk = '".$_SESSION['location']['ps_id']."'
				AND bill_serial_no='".$bill_serial_no."' and status='1'");
				

	$teacher_dtls=$db->fetch_table("select  distinct(emp.emp_id_pk), 
		  emp.emp_id_const,
		  emp.emp_first_name,
		  emp.emp_second_name,
		  emp.emp_last_name, 
		  emp.ps_id_fk, 
		  emp.gp_id_fk,
		  emp.zp_id_fk,
		  emp.emp_status, 
		  emp.emp_acc_no as accountno,
		  emp.emp_acc_no,
		  emp.emp_ifsc_no as bank_ifsc,
		   emp.emp_ifsc_no ,
		  emp.emp_branch_code,  
		  emp.emp_bank_name, 
		  emp.emp_micr_no, 
		  emp.emp_mobile_no,
		  emp.emp_pan_no, 
		  emp.emp_desig, 
		  emp.emp_acc_no, 
		  emp.emp_ifsc_no,
		  bonus.bonus_amount as net,
		  emp.emp_mail_id,
		  bonus.delete_status
		FROM 
		   prd_employee_master emp
		  inner join prd_employee_bonus_details bonus 
		  ON bonus.emp_id_fk=emp.emp_id_pk AND bonus.ps_id_fk=emp.ps_id_fk
		where
			bonus.delete_status='1' 
			AND bonus.bonus_status='5'
			AND emp.emp_status in('1','9','2')
			AND bonus .bill_serial_no='".$bill_serial_no."'
			AND emp.ps_id_fk='" . $_SESSION['location']['ps_id'] . "' 
			AND bonus.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
			");
	foreach($teacher_dtls as $teacher_row)
	{
	$net_amount=($net_amount)+ ($teacher_row['net']);
	
	
	}
}

else if($bill_type=='1005')
{
	
	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND ps_id_fk = '".$_SESSION['location']['ps_id']."'
				AND bill_serial_no='".$bill_serial_no."' and status='1' ");

	$teacher_dtls=$db->fetch_table("select  distinct(emp.emp_id_pk), 
		  emp.emp_id_const,
		  emp.emp_first_name,
		  emp.emp_second_name,
		  emp.emp_last_name, 
		  emp.ps_id_fk, 
		  emp.gp_id_fk,
		  emp.zp_id_fk,
		  emp.emp_status, 
		  emp.emp_acc_no as accountno,
		  emp.emp_acc_no,
		  emp.emp_ifsc_no as bank_ifsc,
		   emp.emp_ifsc_no ,
		  emp.emp_branch_code,  
		  emp.emp_bank_name, 
		  emp.emp_micr_no, 
		  emp.emp_mobile_no,
		  emp.emp_pan_no, 
		  emp.emp_desig, 
		  emp.emp_acc_no, 
		  emp.emp_ifsc_no,
		  fav.festival_advance_total_amount as net,
		  emp.emp_mail_id
		 
		FROM 
		   prd_employee_master emp
	
		   inner join prd_festival_advance_employee_details fav on fav.emp_id_fk=emp.emp_id_pk AND fav.ps_id_fk=emp.ps_id_fk
		where
			
			 fav.festival_advance_status='5'
			AND emp.emp_status in('1','9')
			AND fav .bill_serial_no='".$bill_serial_no."'
			AND emp.ps_id_fk='" . $_SESSION['location']['ps_id'] . "'   
			AND fav.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
			");
			
		foreach($teacher_dtls as $teacher_row)
		{
		$net_amount=($net_amount)+ ($teacher_row['net']);
		
		
		}
	
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
		if(strlen($filename)!='32')
		{
			echo 0;
			exit;
		}
		$name = strftime($filename);
		//header('Content-Disposition: attachment;filename=' . $name);
		//header('Content-Type: text/xml');
		$xml->saveXML();
		
		$xml->save($path.$name);
	//}
	
	///////////////////////////////////////////////  ANJAN 17.07.2020 /////////////////////////////////////////////////////////////////////
//var_dump($name); die;

if(isset($name)){
	
  $zip = new ZipArchive();
  $new_name= substr($name,0,-4);
 // $filename_zip = "../../../../readwrite/xml_file/ifmsgp/ePayment_Files_006/".$new_name.".zip";
 
 $filename_zip=$_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_006/'.$new_name.".zip";

  if ($zip->open($filename_zip, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$filename_zip>\n");
  }
//$file='../ACKTISTTS0010062506201801007796.xml';
  $dir = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_006/';

  // Create zip
  createZip($zip,$dir,$name,$path);

  $zip->close();
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_006/';  
$username = 'gen007';
$password_hash = 'Api@123';
$password = hash("sha512", $password_hash);
$fileType = 'BENF';
$flag='1';
$zipped_name= $new_name.".zip";
$request_id=request_id_generation($party_code);
$party_code_api='gen007';
$ben_upload = call_WEBSERVICE($filename, $local_directory, $username, $password, $fileType, $flag, $zipped_name, $party_code_api, $request_id);

//var_dump($ben_upload); die;

$configData = json_decode($ben_upload, true);
	
	if($configData['status']==0)
	{
		 $response_code=$configData['status'];
	}
	
	/*if($_SESSION['location']['ps_id']=='342' )
		 {
		//print_r($data); die;
		 die;
		 }*/
	//print_r($data); die;
	
	//set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib1.0.11');
	//include('Net/SFTP.php');
	//$local_directory = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/ePayment_Files_006/';
	//$remote_dept = '/apps/ePaymentFiles/gen007/';  
	//$remote_dept = '/ifms_web_cache/ekuber/ePaymentFiles/gen007/';
	
	//$ben_upload=sftp_benf_upload($config['sftp_ip_ps'],$config['sftp_user_name_ps'],$config['sftp_user_password_ps'],$remote_dept,$local_directory,$filename);
	
	if($response_code==1)
	{
		//echo "Sorry! Connection Fails";
		echo 0;
	}
	else if($response_code==1)
	{
		//echo "Sorry! Beneficiary Upload Fails";
		echo 0;
	}
	else if($response_code==0)
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
										'4',
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


