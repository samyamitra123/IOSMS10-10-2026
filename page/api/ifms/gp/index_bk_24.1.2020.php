<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

set_time_limit(0);
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
//include( '../../../all_function/fun_store/ifms_functions1.php');


function dateshow_slash($dateval)
{	
	$date=substr($dateval,0,10);
	if($date=='')
	{
		return '01/01/1900';
	}
	if($date=='0001-01-01')
	{
		return '01/01/1900';
	}
	$datearr=explode('-',$date);
	$dob= $datearr['2'].'/'.$datearr['1'].'/'.$datearr['0'];
	return $dob=='01/01/1900'?'':$dob;
}
$crypto = new cryptography();
$db = new database();
$mo = date('m');
$ye = date('Y');
$bill_type=$crypto->decode($_GET['bill_type'],4);
$drn_number=$crypto->decode($_GET['drn_no'],4);
$yemo = $ye.$mo;
 $monthyear=$_GET['monthyear'];
$bill_serial_no=$crypto->decode($_GET['bill_serial_no'],4); 
  $ropa_status=$crypto->decode($_GET['ropa_status_new'],4);

/*$bill_file_existance_checking=$db->fetch_table("SELECT response_code FROM prd_block_bill_details WHERE drn_number='".$drn_number."' AND salary_monthyear='".date('Ym')."'");*/
$bill_file_existance_checking=$db->fetch_table("SELECT response_code FROM prd_block_bill_details WHERE drn_number='".$drn_number."' AND salary_monthyear='".$monthyear."'");


if(trim($bill_file_existance_checking[0]['response_code'])=='0')
{
	echo 'File has been sent successfully. No need to send again.';
	exit;
}



//$bill_type=1001;
//$drn_number='201805001000001';
$path = '../../../../readwrite/xml_file/';

//$data= array("name"=>"debjit","age"=>"21");
//$string= http_build_query($data);
//$ch=curl_init("http://192.168.1.254/epension/data.php");
//curl_setopt($ch,CURLOPT_POST,true);
//curl_setopt($ch,CURLOPT_POSTFIELDS,$string);
//curl_setopt($ch,CURLOPT_RETURNTRANSFER,false);
//curl_exec($ch);
//curl_close($ch);

if ($_SESSION['user_info']['stake_abbr']=='BDO' )
{
    $ebop_flag='B';
}
else if($_SESSION['user_info']['stake_abbr']=='EO')
{
    $ebop_flag='P';
}
//%%%%%%%%%%%%%%%%%%%%%%%

// $treasury_dts = $db->fetch_table("select ddo_code,treasury_block_code from 
//prd_dise_admin
//WHERE 
//block_code='" . $_SESSION['user_info']['stake_user'] . "'");

$treasury_dts = $db->fetch_table("select ddo_code,treasury_block_code from 
									prd_dise_admin
									WHERE 
									block_code='" . $_SESSION['user_info']['stake_user'] . "'");


$treasury_code=$treasury_dts[0]['treasury_block_code'];
 $ddo_code_name=$treasury_dts[0]['ddo_code']; 

//%%%%%%%%%%%%%%%%%%%%%%%%

 $sql_query_schtype=$db->fetch_table("select * from prd_head_code where type_status='1'");
 
/*$schtype_code=$sql_query_schtype[0]['depart_code'].$sql_query_schtype[0]['demand_no'].$sql_query_schtype[0]['maj_head'].$sql_query_schtype[0]['s_maj_head'].$sql_query_schtype[0]['minor_head'].$sql_query_schtype[0]['plan_head'].$sql_query_schtype[0]['schm_head'].$sql_query_schtype[0]['vot_ch'].$sql_query_schtype[0]['dtl_head'].$sql_query_schtype[0]['sdtl_head']; */ 



$schtype_code=$sql_query_schtype[0]['demand_no'].$sql_query_schtype[0]['maj_head'].$sql_query_schtype[0]['s_maj_head'].$sql_query_schtype[0]['minor_head'].$sql_query_schtype[0]['schm_head'].$sql_query_schtype[0]['dtl_head'].$sql_query_schtype[0]['sdtl_head'].$sql_query_schtype[0]['vot_ch'];



 //%%%%%%%%%%%%%%%%%%%%%%%%%%
 
//$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
//$requisition_type=$requisition[0]['code'];
//%%%%%%%%%%%%%%%%%%%%%%%%%%%
//$det_head=$db->fetch_table("select * from prd_head_details where block_code='".$_SESSION['location']['block_code']."'");

$det_head=$db->fetch_table("select * from prd_head_details where block_code='" . $_SESSION['user_info']['stake_user'] . "'");

//$ptax_head=$det_head[0]['pt_maj_head'].$det_head[0]['pt_s_maj_head'].$det_head[0]['pt_minor_head'].$det_head[0]['pt_schm_head'].'V'.$det_head[0]['pt_dtl_head'];
$ptax_head='0000280010700103000';

//=$det_head[0]['it_maj_head'].$det_head[0]['it_s_maj_head'].$det_head[0]['it_minor_head'].$det_head[0]['it_schm_head'].'V'.$det_head[0]['it_dtl_head'];
$i_tax_head='0086580011200120000';

//$ag_head=$det_head[0]['pf_maj_head'].$det_head[0]['pf_s_maj_head'].$det_head[0]['pf_minor_head'].$det_head[0]['pf_schm_head'].'V'.$det_head[0]['pf_dtl_head'];


$ag_head='0083360080000719000';
//%%%%%%%%%%%%%%%%%%%%%%%%%%%

//$conf_xml=$db->fetch_table("select count(emp_id_fk) as cnt from prd_block_bill_details bill 
//inner join prd_ifms_bill_status_check chk on bill.block_bill_pk=chk.block_bill_fk and check_status='1'
//inner join prd_ifms_bill_status_check_employee_benf emp_check on chk.status_check_id_pk=emp_check.status_check_id_fk and confirm_status='1'
// where  bill.salary_monthyear='201805' 
// and bill.block_code='3299001' 
// and bill.status='1' 
// and bill.requisition_type='1001'
//");
//$$$$$$$$$$$$$$$$$$$$$$$$$$$$
//if($conf_xml[0]['cnt']>0){
//
//$teacher_dtls=$db->fetch_table("select distinct(emp.emp_id_pk), 
//		chk.status_check_id_pk,
//		chk.ifms_id_fk,
//		emp.emp_first_name,
//		emp.emp_second_name,
//		emp.emp_last_name, 
//		emp.gp_id_fk ,
//		emp.emp_status,
//		emp.emp_sex,
//		emp.emp_grade_pay,
//		emp.emp_caste,
//		emp.emp_id_const,
//		sal.salary_monthyear,
//		emp.emp_bank_name,
//		emp.emp_acc_no,
//		sal.accountno,
//		sal.bank_ifsc,
//		sal.basic,
//		sal.da,
//		sal.interim_relief,
//		sal.hra,
//		sal.ma, 
//		sal.gpf,
//		sal.pf_loan, 
//		sal.p_tax, 
//		sal.i_tax,
//		sal.net, 
//		emp.emp_ifsc_no, 
//		sal.pf_deduct,
//		sal.conv_allow,
//		sal.consolidated_pay,
//		sal.hill_allowance,
//		sal.festival_loan,
//		sal.overdrawn,
//		sal.gsli,
//		sal.gross_salary
//		from prd_location_master_block block 
// inner join prd_block_bill_details bill on CAST(block.block_code as character varying)=bill.block_code 
// inner join prd_ifms_bill_status_check chk on bill.block_bill_pk=chk.block_bill_fk and check_status='2' 
// inner join prd_ifms_bill_status_check_employee_benf emp_check on chk.status_check_id_pk=emp_check.status_check_id_fk and confirm_status='2' 
// left join prd_employee_master emp ON emp_check.emp_id_fk=emp.emp_id_pk 
// left join prd_employee_salary_save sal ON emp.emp_id_pk=sal.emp_id_fk 
// 
// where (sal.salary_monthyear)='201805' 
// AND emp.emp_status in('1','9') 
// AND sal.delete_status='1' 
// AND sal.status_flag='3' 
// AND sal.is_saved='1' 
// AND sal.block_code='3299001' 
// AND sal.requisition_type='1001' 
// ORDER BY emp.gp_id_fk ASC");
//
//}else{
if($bill_type=='1001')
{
	if($monthyear!=$yemo)
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
		sal.gross_salary
		from prd_location_master_block block
		inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk          
		inner join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
		left join prd_monthly_salary_archive_final sal ON (sal.emp_id_fk=emp.emp_id_pk and CAST(block.block_code as character varying)=sal.block_code)
		where 
		trim(sal.salary_monthyear)='".$monthyear."'
		AND emp.emp_status in('1','9') 
		AND sal.delete_status='1' 
		AND sal.status_flag='3'
		AND sal.is_saved='1'
		AND sal.block_code='" . $_SESSION['user_info']['stake_user'] . "' 
		AND sal.requisition_type='".$bill_type."'
		AND sal.ropa_status='". $ropa_status."'
		ORDER BY emp.gp_id_fk ASC");
		
		
	}
	else
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
		sal.gross_salary
		from prd_location_master_block block
		inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk          
		inner join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
		left join prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and CAST(block.block_code as character varying)=sal.block_code)
		where 
		trim(sal.salary_monthyear)='".$monthyear."'
		AND emp.emp_status in('1','9') 
		AND sal.delete_status='1' 
		AND sal.status_flag='3'
		AND sal.is_saved='1'
		AND sal.block_code='" . $_SESSION['user_info']['stake_user'] . "' 
		AND sal.requisition_type='".$bill_type."'
		AND sal.ropa_status='". $ropa_status."'
		ORDER BY emp.gp_id_fk ASC");
	
	
	}
}
else if($bill_type=='1002')
{
		$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND  block_code='" . $_SESSION['user_info']['stake_user'] . "'
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
		from prd_location_master_block block
		inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk          
		inner join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
		left join prd_employee_arrear sal ON (sal.emp_id_fk=emp.emp_id_pk and CAST(block.block_code as character varying)=sal.block_code)
		where 
		trim(sal.salary_monthyear)='".$monthyear."'
		AND emp.emp_status in('1','9') 
		AND sal.delete_status='1' 
		AND sal.status_flag='3'
		AND sal.is_saved='1'
		AND sal.block_code='" . $_SESSION['user_info']['stake_user'] . "' 
		AND sal.bill_serial_no='".$bill_serial_no."'
		AND sal.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
		ORDER BY emp.gp_id_fk ASC");
		
		
	}
	
	else if($bill_type=='1003')
{
		/*$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND  block_code='" . $_SESSION['user_info']['stake_user'] . "'
				AND bill_serial_no='".$bill_serial_no."'");*/
				
	
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
		sal.gross_salary
		from prd_location_master_block block
		inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk          
		inner join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
		left join prd_employee_salary_save sal ON (sal.emp_id_fk=emp.emp_id_pk and CAST(block.block_code as character varying)=sal.block_code)
		where 
		trim(sal.salary_monthyear)='".$monthyear."'
		AND emp.emp_status in('1','9') 
		AND sal.delete_status='1' 
		AND sal.status_flag='3'
		AND sal.is_saved='1'
		AND sal.block_code='" . $_SESSION['user_info']['stake_user'] . "' 
		AND sal.requisition_type='".$bill_type."'
		ORDER BY emp.gp_id_fk ASC");
		
		
	}
	else if($bill_type=='1004')
	{
	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
	salary_monthyear = '" . $monthyear . "' AND  block_code='" . $_SESSION['user_info']['stake_user'] . "'
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
	emp.emp_acc_no,
	emp.emp_ifsc_no,
	emp.emp_branch_code,  
	emp.emp_bank_name, 
	emp.emp_micr_no, 
	emp.emp_pan_no, 
	emp.emp_desig, 
	emp.emp_acc_no, 
	emp.emp_ifsc_no,
	bonus.bonus_amount,
	emp.emp_mail_id,
	bonus.delete_status
	FROM 
	prd_location_master_block block
	inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk          
	inner join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
	left join prd_employee_bonus_details bonus on bonus.emp_id_fk=emp.emp_id_pk AND bonus.gp_id_fk=emp.gp_id_fk
	where
	bonus.delete_status='1' 
	AND bonus.bonus_status='5'
	AND emp.emp_status in('1','9')
	AND bonus .bill_serial_no='".$bill_serial_no."'
	AND bonus.block_code='" . $_SESSION['user_info']['stake_user'] . "'
	AND bonus.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
	");
	}
	
	else if($bill_type=='1005')
	{
		$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND  block_code='" . $_SESSION['user_info']['stake_user'] . "'
	AND bill_serial_no='".$bill_serial_no."' and status='1'");
				
				
		
				$teacher_dtls=$db->fetch_table("select distinct(emp.emp_id_pk), 
		  emp.emp_id_const,
		  emp.emp_first_name,
		  emp.emp_second_name,
		  emp.emp_last_name, 
		  emp.ps_id_fk, 
		  emp.gp_id_fk,
		  emp.zp_id_fk,
		  emp.emp_status, 
		  emp.emp_acc_no,
		  emp.emp_ifsc_no,
		  emp.emp_branch_code,  
		  emp.emp_bank_name, 
		  emp.emp_micr_no, 
		  emp.emp_pan_no, 
		  emp.emp_desig, 
		  emp.emp_acc_no, 
		  emp.emp_ifsc_no,
		  fav.festival_advance_total_amount,
		  emp.emp_mail_id
		from 
		prd_location_master_block block
	inner join prd_location_master_gp gp on block.block_id_pk=gp.block_id_fk          
	inner join prd_employee_master emp on gp.gp_id_pk=emp.gp_id_fk
		  
		  left join prd_festival_advance_employee_details fav on fav.emp_id_fk=emp.emp_id_pk AND fav.gp_id_fk=emp.gp_id_fk
		  
		where
			
			 fav.festival_advance_status='5'
			AND emp.emp_status in('1','9')
			AND fav .bill_serial_no='".$bill_serial_no."'
			AND fav.block_code='" . $_SESSION['user_info']['stake_user'] . "'
			AND fav.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
			");
	}

//		trim(sal.salary_monthyear)='".date(Ym)."'
//		AND emp.emp_status in('1','9') 
//		AND sal.delete_status='1' 
//		AND sal.status_flag='3'
//		AND sal.is_saved='1'
//		AND sal.block_code='".$_SESSION['location']['block_code']."' 
//		AND sal.requisition_type='".$requisition_type."'
//		ORDER BY emp.gp_id_fk ASC");	

foreach($teacher_dtls as $teacher_row)
{
	//print_r($teacher_row);
	//$teacher_row['basic'];
	//$ern_amount_grade=($ern_amount_grade)+ ($teacher_row['tch_grade_pay']);
	if($bill_type=='1001')
	{
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
		$gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
		$net_salary=($net_salary)+ ($net_salary['net']);
		$ag_total=$gpf+$pf_loan_total;
	}
	else if( $bill_type=='1002')
	{
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
		//$out_acc_deduction=($out_acc_deduction)+ ($teacher_row['other_loan_deduction']);
		$out_acc_deduction=0;
		//$total_loan_deduction=($total_loan_deduction)+ ($teacher_row['total_loan_deduction']);
		$total_loan_deduction=0;
		$gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
		$net_salary=($net_salary)+ ($net_salary['net']);
		$ag_total=$gpf+$pf_loan_total;
	}
	
	if($bill_type=='1003')
	{
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
		$gross_salary=($gross_salary)+ ($teacher_row['gross_salary']);
		$net_salary=($net_salary)+ ($net_salary['net']);
		$ag_total=$gpf+$pf_loan_total;
	}
		else if($bill_type=='1004')
	{
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
		$pf_loan_total=0;
		$cooperative_loan=0; 
		$hbl_loan=0; 
		$festival_loan=0; 
		$overdrawn=0;
		$advance_amount=0;
		$gsli_deduction=0;
		$hra_deduction=0;
		$out_acc_deduction=0;
		$total_loan_deduction=0;
		$gross_salary=($gross_salary)+ ($teacher_row['bonus_amount']);
		$net_salary=($net_salary)+ ($teacher_row['bonus_amount']);
		$ag_total=0;
		//$ern_amount1=($ern_amount1)+ ($teacher_row['bonus_amount']);
		$net_amount=($net_amount)+ ($teacher_row['bonus_amount']);
		$ern_amount=($ern_amount)+ ($teacher_row['bonus_amount']);
	}
	
	else if($bill_type=='1005')
	{
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
		$pf_loan_total=0;
		$cooperative_loan=0; 
		$hbl_loan=0; 
		$festival_loan=0; 
		$overdrawn=0;
		$advance_amount=0;
		$gsli_deduction=0;
		$hra_deduction=0;
		$out_acc_deduction=0;
		$total_loan_deduction=0;
		$gross_salary=($gross_salary)+ ($teacher_row['festival_advance_total_amount']);
		$net_salary=($net_salary)+ ($teacher_row['festival_advance_total_amount']);
		$ag_total=0;
		//$ern_amount1=($ern_amount1)+ ($teacher_row['bonus_amount']);
		$net_amount=($net_amount)+ ($teacher_row['festival_advance_total_amount']);
		$ern_amount=($ern_amount)+ ($teacher_row['festival_advance_total_amount']);
	}
	
}
	if($bill_type!='1004' && $bill_type!='1005')
	{
	$ern_amount=($gross_salary)-($overdrawn)-($festival_loan);
	$ern_amount1=($gross_salary);
	
	$ag_total=($gpf)+($pf_loan_total);
	$deduc_amt=($i_tax)+($p_tax);
	$deduc_total= ($deduc_amt)+($ag_total);
	$net_amount=($ern_amount)-($deduc_total);
	
	}

 $total_beneficiary=count($teacher_dtls);
	
 //%%%%%%%%%%%%%%%%%%%%%%%

$bill_det=$db->fetch_table("select bill_no,bill_entry_time,salary_monthyear, block_bill_pk from prd_block_bill_details where block_code='" . $_SESSION['user_info']['stake_user'] . "' and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1' AND drn_number='".$drn_number."'");

//		$bill_sql_query=$db->fetch_table("select bill_no,bill_entry_time,salary_monthyear from prd_block_bill_details where block_code='".$_SESSION['location']['block_code']."' and salary_monthyear= '".date(Ym)."' AND requisition_type='".$requisition_type."' AND status='1'");
		


 


//%%%%%%%%%% DRN Genarate %%%%%%%%%%%%%
/*$i=1;
	
for($j=2017;$j<=date('Y');$j++)
{
		$number='002';
		$starting_year=(date('Y')-2017);
		$c=$starting_year;
		$a = sprintf("%06d", $c);
		$drn_sequence_number=date('Ym').$number.$a ;
	
}*/
 //%%%%%%%%%%%%%%%%%%%%%%%

/* $drn_checking=$db->fetch_table("select salary_monthyear,drn_number,response_code from prd_ifms_bill_reference where salary_monthyear= '".date('Ym')."' and drn_number='".$drn_number."' and bill_sending_status='2'");
if(count($drn_checking)>0)
{*/
	//bill_sending_status[0->Delete Status,1->New Data Insert 2->After Response sgiven status ]
	
	/*$update_data_track=$db->update("UPDATE prd_ifms_bill_reference SET
											bill_sending_status='0'
											WHERE salary_monthyear= '".date('Ym')."' AND drn_number='".$drn_number."' AND bill_sending_status='2'");
}
*/
//%%%%%%%%%%%%%%%%%%%%%%%
/*if($bill_sql_query>0)
{
	$ifms_track_insart=$db->insert("INSERT INTO prd_ifms_bill_reference
									(block_bill_fk, 
									salary_monthyear, 
									bill_sending_status,
									response_code,
									drn_number,
									date)
									VALUES
									('".$bill_sql_query[0]['block_bill_pk']."',
									'".$bill_sql_query[0]['salary_monthyear']."',
									'1',
									'',
									'".$drn_number."',
									now()) ");
}*/



$ifms_track_insart=$db->update(" UPDATE prd_block_bill_details SET 
								bill_sending_status='1',
								response_code='',
								bill_sending_time='now()',
								bill_sending_ip='".$_SESSION['user_agent']['USER_IP']."'
								WHERE salary_monthyear= '".$monthyear."' AND drn_number='".$drn_number."'");

	
//==================                File Name                         ========================

$billno1='';$billno='';$billdate='';$bill_date_array='';

$bill_array=explode("/",$bill_det[0]['bill_no']); 

foreach($bill_array as $a=>$i)
{
	$billno1=$billno1.$i;
}

 $bill_array=explode("-",$billno1);

foreach($bill_array as $a=>$i)
{
	$billno=$billno.$i; 
}


$bill_date_array=explode("/",dateshow_slash($bill_det[0]['bill_entry_time'])); 

/*foreach($bill_date_array as $a=>$i)
{ 
	$billdate=$billdate.$i;
}*/
$bill_n=$bill_det[0]['bill_no'];
$billdate=dateshow_slash($bill_det[0]['bill_entry_time']);

$filename = substr($bill_det[0]['salary_monthyear'],4).substr($bill_det[0]['salary_monthyear'],0,4)."31".$ddo_code_name.$billno.$billdate."_Benf.xml";
$name = strftime($filename);

//=======================================================================================


if($validator->blank_select($drn_number) == FALSE || $drn_number == '0')
{
	echo 'DRN Number Missing.';
	exit;
}
else if($validator->blank_select($treasury_code) == FALSE || $treasury_code == '0')
{
	echo 'Treasury Code Missing.';
	exit;
}
else if($validator->blank_select($ddo_code_name) == FALSE || $ddo_code_name == '0')
{
	echo 'DDO Code Missing.';
	exit;
}
else if($validator->blank_select($gross_salary) == FALSE || $gross_salary == '0')
{
	echo 'Gross Amount Blank.';
	exit;
}
else if($validator->blank_select($net_amount) == FALSE || $net_amount == '0')
{
	echo 'Net Amount Blank.';
	exit;
}
else if($validator->blank_select($billno) == FALSE || $billno == '0')
{
	echo 'Bill Number Missing.';
	exit;
}
/*else if(!ctype_alpha($treasury_code))
{
	echo 'Wrong Treasury Code.';
	exit;
}
else if(!ctype_alpha($ddo_code_name))
{
	echo 'Wrong Treasury Code.';
	exit;
}*/


if($ifms_track_insart)
{
    

//    $xml =  new DOMDocument("1.0","UTF-8");
//	$container = $xml->createElement('GEN_EPAYMENT');
//	$container = $xml->appendChild($container);
//	
//	$row = $xml->createElement('DRN',$drn_number);
//	$row = $container->appendChild($row);
//	$sln = $xml->createElement('EBOP_FLAG','B');
//	$sln = $container->appendChild($sln);
//	
//	$name = $xml->createElement('BENF_FLAG','4');
//	$name = $container->appendChild($name);
//	
//	$trsc = $xml->createElement('TREASURY_CODE',$treasury_code);
//	$trsc = $container->appendChild($trsc);
//	
//	$ddc = $xml->createElement('DDO_CODE',$ddo_code_name);
//	$ddc = $container->appendChild($ddc);
//	
//	$plc= $xml->createElement('PL_CODE','0');
//	$plc = $container->appendChild($plc);
//	
//	$smc = $xml->createElement('SCHEME_CODE','0');
//	$smc = $container->appendChild($smc);
//	
//	$hoa = $xml->createElement('HEAD_OF_ACCOUNT',$schtype_code);
//	$hoa = $container->appendChild($hoa);
//	
//	$ga = $xml->createElement('GROSS_AMOUNT',$ern_amount1);
//	$ga = $container->appendChild($ga);
//	
//	$na = $xml->createElement('NET_AMOUNT',$net_amount);
//	$na = $container->appendChild($na);
//	
//	$billnumber = $xml->createElement('BILL_NO',$billno);
//	$billnumber = $container->appendChild($billnumber);
//	
//	$bill_date = $xml->createElement('BILL_DATE',$billdate);
//	$bill_date = $container->appendChild($bill_date);
//	
//	$billtype = $xml->createElement('BILL_TYPE','4');
//	$billtype = $container->appendChild($billtype);
//	
//	$sacno = $xml->createElement('SANCTION_NO','0');
//	$sacno = $container->appendChild($sacno);
//	
//	$sacdate = $xml->createElement('SANCTION_DATE',$billdate);
//	$sacdate = $container->appendChild($sacdate);
//	
//	$issu = $xml->createElement('ISSUING_AUTHORITY','NIL');
//	$issu = $container->appendChild($issu);
//	
//	$sa = $xml->createElement('SANCTION_AMOUNT',$ern_amount1);
//	$sa = $container->appendChild($sa);
//	
//	$subdetails = $xml->createElement('SUBDETAIL');
//	$subdetails = $container->appendChild($subdetails);
//	
//	$subdetails_hed = $xml->createElement('SUBDETAIL_HEAD','E');
//	$subdetails_hed = $subdetails->appendChild($subdetails_hed);
//	
//	$subdetails_amount = $xml->createElement('SUBDETAIL_AMOUNT','0');
//	$subdetails_amount = $subdetails->appendChild($subdetails_amount);
//	
//	$bynsfer = $xml->createElement('BYTRANSFER');
//	$bynsfer = $container->appendChild($bynsfer);
//	
//	$bthoa = $xml->createElement('BT_HOA',$ptax_head);
//	$bthoa = $bynsfer->appendChild($bthoa);
//	
//	$bt_effect = $xml->createElement('BT_EFFECT','T');
//	$bt_effect = $bynsfer->appendChild($bt_effect);
//	
//	$bammount = $xml->createElement('BT_AMOUNT',$p_tax);
//	$bammount = $bynsfer->appendChild($bammount);
//	
//	$bynsfer = $xml->createElement('BYTRANSFER');
//	$bynsfer = $container->appendChild($bynsfer);
//	
//	$bthoa = $xml->createElement('BT_HOA',$i_tax_head);
//	$bthoa = $bynsfer->appendChild($bthoa);
//	
//	$bt_effect = $xml->createElement('BT_EFFECT','T');
//	$bt_effect = $bynsfer->appendChild($bt_effect);
//	
//	$bammount = $xml->createElement('BT_AMOUNT',$i_tax);
//	$bammount = $bynsfer->appendChild($bammount);
//	
//	$bynsfer = $xml->createElement('BYTRANSFER');
//	$bynsfer = $container->appendChild($bynsfer);
//	
//	$bthoa = $xml->createElement('BT_HOA',$ag_head);
//	$bthoa = $bynsfer->appendChild($bthoa);
//	
//	$bt_effect = $xml->createElement('BT_EFFECT','T');
//	$bt_effect = $bynsfer->appendChild($bt_effect);
//	
//	$bammount = $xml->createElement('BT_AMOUNT',$ag_total);
//	$bammount = $bynsfer->appendChild($bammount);

 $xml =  new DOMDocument("1.0","UTF-8");
	$container = $xml->createElement('GEN_EPAYMENT');
	$container = $xml->appendChild($container);
	
	$row = $xml->createElement('DRN',$drn_number);
	$row = $container->appendChild($row);
	$sln = $xml->createElement('EBOP_FLAG','B');
	$sln = $container->appendChild($sln);
	
	$name = $xml->createElement('BENF_FLAG','4');
	$name = $container->appendChild($name);
	
	$trsc = $xml->createElement('TREASURY_CODE',$treasury_code);
	$trsc = $container->appendChild($trsc);
	
	$ddc = $xml->createElement('DDO_CODE',$ddo_code_name);
	$ddc = $container->appendChild($ddc);
	
	$plc= $xml->createElement('PL_CODE','0');
	$plc = $container->appendChild($plc);
	
	$smc = $xml->createElement('SCHEME_CODE','0');
	$smc = $container->appendChild($smc);
	
	$hoa = $xml->createElement('HEAD_OF_ACCOUNT',$schtype_code);
	$hoa = $container->appendChild($hoa);
	
	$ga = $xml->createElement('GROSS_AMOUNT',$ern_amount);
	$ga = $container->appendChild($ga);
	
	$na = $xml->createElement('NET_AMOUNT',$net_amount);
	$na = $container->appendChild($na);
	
	//$billnumber = $xml->createElement('BILL_NO',$billno);
	//$billnumber = $container->appendChild($billnumber);
	
	$billnumber = $xml->createElement('BILL_NO',$bill_n);
	$billnumber = $container->appendChild($billnumber);
	
	$bill_date = $xml->createElement('BILL_DATE',$billdate);
	$bill_date = $container->appendChild($bill_date);
	
	$billtype = $xml->createElement('BILL_TYPE','4');
	$billtype = $container->appendChild($billtype);
	
	$sacno = $xml->createElement('SANCTION_NO','0');
	$sacno = $container->appendChild($sacno);
	
	$sacdate = $xml->createElement('SANCTION_DATE',$billdate);
	$sacdate = $container->appendChild($sacdate);
	
	$issu = $xml->createElement('ISSUING_AUTHORITY','NIL');
	$issu = $container->appendChild($issu);
	
	$sa = $xml->createElement('SANCTION_AMOUNT',$ern_amount);
	$sa = $container->appendChild($sa);
	
	$subdetails = $xml->createElement('SUBDETAIL');
	$subdetails = $container->appendChild($subdetails);
	
	$subdetails_hed = $xml->createElement('SUBDETAIL_HEAD','E');
	$subdetails_hed = $subdetails->appendChild($subdetails_hed);
	
	$subdetails_amount = $xml->createElement('SUBDETAIL_AMOUNT','0');

	$subdetails_amount = $subdetails->appendChild($subdetails_amount);
	
	$bynsfer = $xml->createElement('BYTRANSFER');
	$bynsfer = $container->appendChild($bynsfer);
	
	$bthoa = $xml->createElement('BT_HOA',$ptax_head);
	$bthoa = $bynsfer->appendChild($bthoa);
	
	$bt_effect = $xml->createElement('BT_EFFECT','T');
	$bt_effect = $bynsfer->appendChild($bt_effect);
	
	$bammount = $xml->createElement('BT_AMOUNT',$p_tax);
	$bammount = $bynsfer->appendChild($bammount);
	
	$bynsfer = $xml->createElement('BYTRANSFER');
	$bynsfer = $container->appendChild($bynsfer);
	
	$bthoa = $xml->createElement('BT_HOA',$i_tax_head);
	$bthoa = $bynsfer->appendChild($bthoa);
	
	$bt_effect = $xml->createElement('BT_EFFECT','T');
	$bt_effect = $bynsfer->appendChild($bt_effect);
	
	$bammount = $xml->createElement('BT_AMOUNT',$i_tax);
	$bammount = $bynsfer->appendChild($bammount);
	
	$bynsfer = $xml->createElement('BYTRANSFER');
	$bynsfer = $container->appendChild($bynsfer);
	
	$bthoa = $xml->createElement('BT_HOA',$ag_head);
	$bthoa = $bynsfer->appendChild($bthoa);
	
	$bt_effect = $xml->createElement('BT_EFFECT','T');
	$bt_effect = $bynsfer->appendChild($bt_effect);
	
	$bammount = $xml->createElement('BT_AMOUNT',$ag_total);
	$bammount = $bynsfer->appendChild($bammount);
	
	$input_xml =$xml->saveXML();
	
	
	//$url = "http://192.168.1.254/epension/data.php"; 
	
	//setting the curl parameters.
//$configData=bill_send('GET', $input_xml,false);

$data = array (
        'xmlString' =>$input_xml
     );
	 print_r($data);die;
	/*if( $_SESSION['user_info']['stake_user']=='3299001')
	{
	print_r($data);die;
	}*/

    $get_data = callAPI('GET', '202.61.117.96/webfd/billDetailsWithBeneficiary.html', $data);
    // $get_data = callAPI('GET', '202.61.117.90/webfd/billDetailsWithBeneficiary.html?xmlString=', $input_xml);
    
     $arry=simplexml_load_string($get_data);
     $json= json_encode($arry);
     $configData = json_decode($json, true);
  
	if($configData)
	{ 
	
		$data_track=$db->update("UPDATE prd_block_bill_details SET
								bill_sending_status='2',
								response_code='".trim($configData['RESPONSE'])."',
								response_recieve_time='now()'
								WHERE 
								 drn_number='".$drn_number."'
								");
				/*$arr=array();
			
			$arr['RESPONSE']=$configData['RESPONSE'];				
			echo json_encode($arr);*/
			
	
		
			if(trim($configData['RESPONSE'])=='0')
			{
				echo "Success";
			}
			else
			{
				$error_type_fetch=$db->fetch_table("SELECT description FROM prd_ifms_response_code_master WHERE code='".trim($configData['RESPONSE'])."'");
				echo $error_type_fetch[0]['description'];
			}
								
	}
	
}

//echo 11;die;
?>