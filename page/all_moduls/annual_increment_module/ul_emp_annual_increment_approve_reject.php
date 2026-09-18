<?php
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

$cryptoGraph=new cryptography();

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}



if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");


//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

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
else if($_SESSION['user_info']['stake_abbr']=='SECRETARY')
{
	$logged_user='zpsec';
}
else
{
	// $_SESSION['location']['ps_id']; 
	$logged_user=$_SESSION['user_info']['stake_abbr']; 
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	

$cryptoGraph=new cryptography();



$emp_id=$cryptoGraph->decode($_POST['annu_emp'],4);
$gp_id=$cryptoGraph->decode($_POST['annu_gp'],4);
$increment_condition=$cryptoGraph->decode($_POST['increment_condition'],4);
//var_dump($increment_condition); die;

if($logged_user=='BDO')
{
	$gp_id_encript=$_POST['annu_gp'];
}
else if($logged_user=='EO')
{
	$gp_id_encript= $cryptoGraph->encode($_SESSION['location']['ps_id'],4);
}
else if($logged_user=='zpddo')
{
	$gp_id_encript= $cryptoGraph->encode($_SESSION['location']['district_id'],4);
}

$increment_amount=$cryptoGraph->decode($_POST['inc_amt'],4); 
$action=$_POST['action']; 
$db = new database();

$pension_stat_fetch=$db->fetch_table(" SELECT emp_cosolidated_pay,emp_pension_status, increment_count FROM prd_employee_master WHERE emp_id_pk='".$emp_id."' ");
$emp_consolidated_pay=$pension_stat_fetch[0]['emp_cosolidated_pay'];
 $increment_count=$pension_stat_fetch[0]['increment_count']; 
$pension_stat=$pension_stat_fetch[0]['emp_pension_status'];
if($pension_stat=='0')
{
	$update_pension='0';
}
elseif($pension_stat=='1' || $pension_stat=='2') 
{
	$update_pension='2';
}

													
if($action=="accept")
{
	//print("test"); exit;
	$db = new database();
	
	pg_query('BEGIN');
	
	$Query = "UPDATE prd_employee_annual_increment_details  
									SET status='2',acceptance_time='now()',acceptance_ip='".$_SERVER['REMOTE_ADDR']."'
									WHERE emp_id_fk = '".$emp_id."' and annual_increment_amount='".$increment_amount."'
									AND status='1' AND substr(effective_monthyear,1,4)='".date('Y')."'";
	
	
    //print($Query); exit;
	$update_status = $db->update($Query);
	$Query = "Insert into prd_employee_master_archive
											(emp_id_pk,gp_id_fk,emp_first_name,emp_second_name,emp_last_name,
											emp_dob,emp_sex,emp_caste,emp_voter_id,emp_aadhar_no,emp_edu_quali,
											emp_desig,emp_first_join_date,emp_conf_join_date,emp_join_prsnt_post_date,
											emp_join_prsnt_office_date,emp_retirement_date,emp_termination_date,
											emp_status_deputation,emp_group,emp_desig_first_app,emp_next_increment_date,
											emp_next_increment_amount,emp_cosolidated_pay,emp_pay_band,
											emp_pay_in_payband,emp_grade_pay_bk,emp_pay_scale,emp_bank_name,
											emp_bank_branch,emp_branch_code,emp_micr_no,emp_acc_no,emp_ifsc_no,
											emp_father_name,emp_mother_name,emp_religion,emp_mother_tongue,
											emp_marital_status,emp_spouse_name,emp_spouse_job_status,emp_spouse_details,
											emp_spouse_pay,emp_spouse_hra,emp_spouse_res,emp_spouse_house_schm,
											emp_pan_no,emp_blood_grp,emp_height,emp_diff_able,emp_disable_status,
											emp_idf_mark,pre_state,emp_pre_house_no,emp_pre_street_no,
											emp_pre_vill,emp_pre_post,emp_pre_pin,emp_pre_dist,emp_pre_dist_others,
											per_state,emp_per_house_no,emp_per_street_no,emp_per_vill,
											emp_per_post,emp_per_pin,emp_per_dist,emp_per_dist_others,
											emp_land_no,emp_mobile_no,emp_mail_id,emp_system_code,emp_form_status,
											emp_status,entry_time,entry_ip,empcd,emp_grade_pay,emp_id_const,
											bank_upd_status,bank_msg_flag,conf_dt_flag,interim_relief,ps_id_fk,
											profile_update_time,profile_update_ip_address,spouse_medical_allowance,
											conv_allow_status,emp_pension_status,zp_emp_type,zp_id_fk,notification_no,
											emp_accommodation,emp_pre_ps,emp_per_ps, emp_unlock_status,ropa_level) 
											SELECT 
											emp_id_pk, gp_id_fk, emp_first_name, emp_second_name, emp_last_name, 
											emp_dob, emp_sex, emp_caste, emp_voter_id, emp_aadhar_no, emp_edu_quali, 
											emp_desig, emp_first_join_date, emp_conf_join_date, emp_join_prsnt_post_date, 
											emp_join_prsnt_office_date, emp_retirement_date, emp_termination_date, 
											emp_status_deputation, emp_group, emp_desig_first_app, emp_next_increment_date, 
											CAST (emp_next_increment_amount as numeric), emp_cosolidated_pay, emp_pay_band, 
											emp_pay_in_payband, emp_grade_pay_bk, emp_pay_scale, emp_bank_name, 
											emp_bank_branch, emp_branch_code, emp_micr_no, emp_acc_no, emp_ifsc_no, 
											emp_father_name, emp_mother_name, emp_religion, emp_mother_tongue, 
											emp_marital_status, emp_spouse_name, emp_spouse_job_status, emp_spouse_details, 
											emp_spouse_pay, emp_spouse_hra, emp_spouse_res, emp_spouse_house_schm, 
											emp_pan_no, emp_blood_grp, emp_height, emp_diff_able, emp_disable_status, 
											emp_idf_mark, pre_state, emp_pre_house_no, emp_pre_street_no, 
											emp_pre_vill, emp_pre_post, emp_pre_pin, emp_pre_dist, emp_pre_dist_others, 
											per_state, emp_per_house_no, emp_per_street_no, emp_per_vill, 
											emp_per_post, emp_per_pin, emp_per_dist, emp_per_dist_others, 
											emp_land_no, emp_mobile_no, emp_mail_id, emp_system_code, emp_form_status, 
											emp_status, entry_time, entry_ip, empcd, emp_grade_pay, emp_id_const,
											bank_upd_status,bank_msg_flag,conf_dt_flag,interim_relief,ps_id_fk,
											'now()','".$_SERVER['REMOTE_ADDR']."',spouse_medical_allowance,
											conv_allow_status,emp_pension_status,zp_emp_type,zp_id_fk,notification_no,
											emp_accommodation,emp_pre_ps,emp_per_ps, emp_unlock_status,ropa_level
											FROM prd_employee_master where emp_id_pk='".$emp_id."'";	
    $query_archive=$db->insert($Query);											
    //print($query_archive); exit;													
			
								
	
	
	
	$increment_count = $increment_count+$increment_condition;
	//var_dump($increment_count); die;
	if($emp_consolidated_pay=='0')
	{			
	
		$increment_amount_update=$db->update(" UPDATE prd_employee_master emp 
											SET emp_pay_in_payband=anu.new_emp_pay_in_payband,emp_pension_status='".$update_pension."', increment_count= '".$increment_count."'
											FROM prd_employee_annual_increment_details anu
											WHERE emp.emp_id_pk=anu.emp_id_fk AND emp.emp_id_pk = '".$emp_id."' 
											AND anu.annual_increment_amount='".$increment_amount."'	
											AND anu.status='2' AND substr(anu.effective_monthyear,1,4)='".date('Y')."' ");
	}
	else
	{
			
			
			
				$increment_amount_update=$db->update("UPDATE prd_employee_master emp 
											SET emp_cosolidated_pay=anu.new_consolidated_pay,emp_pension_status='".$update_pension."', increment_count= '".$increment_count."'
											FROM prd_employee_annual_increment_details anu
											WHERE emp.emp_id_pk=anu.emp_id_fk AND emp.emp_id_pk = '".$emp_id."' 
											AND anu.annual_increment_amount='".$increment_amount."'	
											AND anu.status='2' AND substr(anu.effective_monthyear,1,4)='".date('Y')."' ");
	}
	
	
	if( $update_status && $query_archive && $increment_amount_update)	
	{
		pg_query('COMMIT');											
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center"><strong>Employee Annual Increment Details Has Been Accepted Successfully.</strong></div>';	
		header('location:ul_emp_annual_increment_approve_reject_view.php?gp_id_fk='.$gp_id_encript);
		exit;													
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Acceptance Failed!!! </strong></div>';	
		header('location:ul_emp_annual_increment_approve_reject_view.php?gp_id_fk='.$gp_id_encript);
		exit;
	}
	
}
else if($action=="reject")
{
	$db = new database();
	
	$update_status = $db->update("UPDATE prd_employee_annual_increment_details  SET status='3'
									WHERE emp_id_fk = '".$emp_id."' and annual_increment_amount='".$increment_amount."'
									AND status='1'");
	
	
	if( $update_status)	
	{											
	
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center"><strong>Employee Annual Increment Details Has Been Rejected Successfully.</strong></div>';	
		header('location:ul_emp_annual_increment_approve_reject_view.php?gp_id_fk='.$_POST['annu_gp']);
		exit;													
	}
	else
	{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Rejection Failed!!! </strong></div>';	
		header('location:ul_emp_annual_increment_approve_reject_view.php?gp_id_fk='.$_POST['annu_gp']);
		exit;
	}													

}
else
{
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Security Error!!! </strong></div>';	
	header('location:ul_emp_annual_increment_approve_reject_view.php?gp_id_fk='.$_POST['annu_gp']);
	exit;
}
 