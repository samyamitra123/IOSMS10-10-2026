<?php

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");;

ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once'../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
/*require '../../../page_visite.php';
*/

/*if (
!isset($_SESSION['user_info']['stake_user'])
|| !isset($_SESSION['user_info']['stake_level'])
|| !isset($_SESSION['user_info']['flag'])
|| isset($_SESSION['blocked_privilege']['0801'])
){
header('Location: '. $config['base_url'] . "page/login.php");
exit;
}*/

$path = $_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsgp/webservice/';
//$path = '../../../../readwrite/xml_file/ifmsgp/webservice/';
//$path = $config['base_url'] .'readwrite/xml_file/ifmsgp/webservice/';$_SERVER['DOCUMENT_ROOT'].'/readwrite/xml_file/ifmsps/webservice/';

//echo $path ;die;
$crypto = new cryptography();
//header("Contet-type : text/xml");
$schcd = $_SESSION['user_info']['stake_user'];
$mo = date('m');
$ye = date('Y');

//$bill =$crypto->decode($_GET['bill'],4);
//$bill='44';
$bill_type=$crypto->decode($_GET['bill_type'],4);
 $drn_number=$crypto->decode($_GET['drn_no'],4); 
//$drn_number =$crypto->decode($_GET['drn'],4); die;
//$drn_number='201805001000001';
//$bill_type =$crypto->decode($_GET['bill_type'],4);
$ropa_status=$crypto->decode($_GET['ropa_status_new'],4);


if ($_SESSION['user_info']['stake_abbr']=='BDO' )
{
    $ebop_flag='B';
}
else if($_SESSION['user_info']['stake_abbr']=='EO')
{
    $ebop_flag='P';
}
//$bill_type='1001';
$yemo = $ye.$mo;
 $monthyear=$_GET['monthyear']; 
 $bill_serial_no=$crypto->decode($_GET['bill_serial_no'],4); 
$db = new database();

/*$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];*/


$sql_query_schtype=$db->fetch_table("select * from prd_head_code where type_status='1'");
 
/*$schtype_code=$sql_query_schtype[0]['depart_code'].$sql_query_schtype[0]['demand_no'].$sql_query_schtype[0]['maj_head'].$sql_query_schtype[0]['s_maj_head'].$sql_query_schtype[0]['minor_head'].$sql_query_schtype[0]['plan_head'].$sql_query_schtype[0]['schm_head'].$sql_query_schtype[0]['vot_ch'].$sql_query_schtype[0]['dtl_head'].$sql_query_schtype[0]['sdtl_head'];*/  

$schtype_code=$sql_query_schtype[0]['demand_no'].$sql_query_schtype[0]['maj_head'].$sql_query_schtype[0]['s_maj_head'].$sql_query_schtype[0]['minor_head'].$sql_query_schtype[0]['schm_head'].$sql_query_schtype[0]['dtl_head'].$sql_query_schtype[0]['sdtl_head'].$sql_query_schtype[0]['vot_ch'];

$det_head=$db->fetch_table("select * from prd_head_details where block_code='".$schcd."'");

//$ptax_head=$det_head[0]['pt_maj_head'].$det_head[0]['pt_s_maj_head'].$det_head[0]['pt_minor_head'].$det_head[0]['pt_schm_head'].$det_head[0]['pt_dtl_head'];

//$i_tax_head=$det_head[0]['it_maj_head'].$det_head[0]['it_s_maj_head'].$det_head[0]['it_minor_head'].$det_head[0]['it_schm_head'].$det_head[0]['it_dtl_head'];

//$ag_head=$det_head[0]['pf_maj_head'].$det_head[0]['pf_s_maj_head'].$det_head[0]['pf_minor_head'].$det_head[0]['pf_schm_head'].$det_head[0]['pf_dtl_head'];

$ptax_head='0000280010700103000';
$i_tax_head='0086580011200120000';
$ag_head='0083360080000719000';

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

$treasury_dts = $db->fetch_table("select ddo_code,treasury_block_code from 
									prd_dise_admin
									WHERE 
									block_code='" . $_SESSION['user_info']['stake_user'] . "'");


$treasury_code=$treasury_dts[0]['treasury_block_code'];
$ddo_code_name=$treasury_dts[0]['ddo_code'];

//$ddo_code_array=explode("-",$ddo_code);
//$ddo_code_name=$ddo_code_array[0].$ddo_code_array[1].$ddo_code_array[2];
//$ddo_code_slash=$ddo_code_array[0]."/".$ddo_code_array[1]."/".$ddo_code_array[2];
$financial_year = (date("m") >= 4) ? (date("Y")."-".(date("Y")+1)) : ((date("Y")-1)."-".date("Y"));

$bill_det=$db->fetch_table("select bill_no,bill_entry_time,salary_monthyear,drn_number from prd_block_bill_details where block_code='".$schcd."'  and salary_monthyear= '".$monthyear."' AND requisition_type='".$bill_type."' AND status='1' and drn_number='".$drn_number."'");

$det_head=$db->fetch_table("select * from prd_head_details where block_code='".$schcd."'");

$no_of_row=count($det_head);   

/*$teacher_sql="select  distinct(emp.emp_id_pk),
emp.empcd, 
emp.emp_first_name,
emp.emp_second_name,
emp.emp_last_name, 
emp.gp_id_fk, 
emp.emp_status, 
emp.emp_branch_code, 
emp.emp_system_code,  
save.empcd, 
save.bankname, 
emp.emp_micr_no, 
emp.emp_pan_no, 
emp.emp_desig, 
emp.emp_acc_no, 
save.basic,
save.bank_ifsc,
save.net,
emp.emp_group,
emp.emp_mail_id,
emp.emp_aadhar_no,
emp.emp_mobile_no,
emp.emp_pre_dist,
emp.emp_per_dist,
save.delete_status
FROM 
prd_employee_master emp
left join prd_employee_salary_save save 
ON save.emp_id_fk=emp.emp_id_pk
where
trim(save.salary_monthyear)='".$yemo."'
AND save.delete_status='1' 
AND save.status_flag='3'
AND emp.emp_status in('1','9')
AND save.block_code='".$schcd."'
AND save.requisition_type='".$requisition_type."'
ORDER BY emp.gp_id_fk ASC
";*/
if($bill_type=='1001')
{
	if($monthyear!=$yemo){
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
	AND emp.emp_status in('1','9','2') 
	AND sal.delete_status='1' 
	AND sal.status_flag='3'
	AND sal.is_saved='1'
	AND sal.block_code='".$schcd."' 
	AND sal.requisition_type='".$bill_type."'
	AND sal.ropa_status='".$ropa_status."'
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
	AND sal.block_code='".$schcd."' 
	AND sal.requisition_type='".$bill_type."'
	AND sal.ropa_status='".$ropa_status."'
	ORDER BY emp.gp_id_fk ASC");
	
	}
}
else if($bill_type=='1002')
{
		$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND  block_code='" . $_SESSION['user_info']['stake_user'] . "'
				AND bill_serial_no='".$bill_serial_no."'");
				
			
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
									sal.gsli,
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
				AND bill_serial_no='".$bill_serial_no."'");
				*/
			
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
	AND sal.block_code='".$schcd."' 
	AND sal.requisition_type='".$bill_type."'
	ORDER BY emp.gp_id_fk ASC");
		
		
	}
	
	else if($bill_type=='1004')
	{
	$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
	salary_monthyear = '" . $monthyear . "' AND  block_code='" . $_SESSION['user_info']['stake_user'] . "'
	AND bill_serial_no='".$bill_serial_no."'");

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
	AND emp.emp_status in('1','9','2')
	AND bonus .bill_serial_no='".$bill_serial_no."'
	AND bonus.block_code='" . $_SESSION['user_info']['stake_user'] . "'
	AND bonus.bill_id_fk='".$arrear_bill_id_pk[0]['block_bill_pk']."'
	");
	}
	else if($bill_type=='1005')
	{
		$arrear_bill_id_pk=$db->fetch_table("SELECT block_bill_pk FROM prd_block_bill_details WHERE 
				salary_monthyear = '" . $monthyear . "' AND  block_code='" . $_SESSION['user_info']['stake_user'] . "'
	AND bill_serial_no='".$bill_serial_no."'");
				
		
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
	
	
//$sql_teacher=$db->fetch_table($teacher_sql);

$fetch_code_master=$db->fetch_table("select code, description from prd_dise_code_master");


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

foreach($teacher_dtls as $teacher_row)
{
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
		//$gsli_deduction=0;
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
	
	else if($bill_type=='1003')
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
		$ern_amount1=($ern_amount1)+ ($teacher_row['festival_advance_total_amount']);
		$net_amount=($net_amount)+ ($teacher_row['festival_advance_total_amount']);
		
	}

}
	if($bill_type!='1004' && $bill_type!='1005')
	{
	$ern_amount=($gross_salary)-($overdrawn)-($festival_loan)-($advance_amount);
	$ern_amount1=($gross_salary);
	$ag_total=($gpf)+($pf_loan_total);
	$deduc_amt=($i_tax)+($p_tax);
	$deduc_total= ($deduc_amt)+($ag_total);
	$net_amount=($ern_amount)-($deduc_total);
	
	}
$total_beneficiary=count($teacher_dtls);

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

    $ngipfSal = ($_SESSION['location']['ngipf_sal'] == 1)?'Y':'N';
	$ngipfSal = ($bill_type != '1001')?'N':$ngipfSal;
	$ngipfSal = ($bill_det[0]['ropa_status'] == 2)?'N':$ngipfSal; 
	   
	$subsflag = $xml->createElement('SUBS_FLAG',$ngipfSal); // Once Live Need to set to Y
	$subsflag = $container->appendChild($subsflag);	
	
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


//find_dist(substr($_SESSION['schcd'],0,4))
//$xml->formatOutput = TRUE;


//echo substr($bill_det[0]['salary_monthyear'],4).substr($bill_det[0]['salary_monthyear'],0,4)."31".$ddo_code_name.$billno.$billdate."_Benf.xml";exit;

if($xml->formatOutput = TRUE)
{
    
	//$string_value = $xml->saveXML();
	//$xml->save('../readwrite/xml2/'.$_SESSION['schcd'].'_'.$_SESSION['schname'].'_beneficiary_salary.xml');
	//	MONTH(10)YEAR(2015)TR_TYPE(31)DDO_CODE(NPCEDS002)ANY_SERIAL_NO(132)CREATED_DATE(07102015)_Benf(1)
	//31TISTTS00101011900_Benf
  
	 $filename = $drn_number."_Benf.xml"; 
	$name = strftime($filename); 
	header('Content-Disposition: attachment;filename=' . $name);
	header('Content-Type: text/xml');
	echo $xml->saveXML();
	$xml->save($path.$name);
}


?>
