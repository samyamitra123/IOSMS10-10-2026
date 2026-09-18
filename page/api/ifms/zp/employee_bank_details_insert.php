<?php
set_time_limit(0);
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
require '../../../../includes/library/myvalidation.class.php';

$crypto = new cryptography();
$db = new database();

$err_arr=array();
$success_arr=array();

$emp_id_pk=$crypto->decode($_POST['emp_id'],4);
$fname=strtoupper(trim($_POST['tch_fname']));
$mname=strtoupper(trim($_POST['tch_mname']));
$lname=strtoupper(trim($_POST['tch_lname']));
$txt_bank=$_POST['txt_bank'];
$tch_bank_branch=$_POST['tch_bank_branch']==''?'':$_POST['tch_bank_branch'];
$tch_branch_code=$_POST['tch_branch_code']==''?'':$_POST['tch_branch_code'];
$tch_micr_code=$_POST['tch_micr_code']==''?0:$_POST['tch_micr_code'];
$txt_account=$_POST['txt_account'];
$txt_ifsc=$_POST['txt_ifsc'];
$pension_stat=$_POST['pension_stat'];
if($pension_stat=='0')
{
	$update_pension='0';
}
elseif($pension_stat=='1' || $pension_stat=='2') 
{
	$update_pension='2';
}

$bank_data = $db->fetch_table("
									SELECT bank_code
									FROM prd_dise_bank_master;
									
									");


$acc_no_check=$db->fetch_table("select emp_acc_no from prd_employee_master where 
	emp_acc_no !=(select emp_acc_no from prd_employee_master where emp_id_pk='$emp_id_pk' )");	

$flag=0;
if($validator->blank_select($txt_account) == TRUE)
{
	foreach($acc_no_check as $key)
	{
		if($txt_account == $key['emp_acc_no'])
		{
			array_push($err_arr,"Acount Number");
			$flag=1;
		}
	}
}
if($flag=='0')
{
	if($validator->blank_select($fname) == FALSE)
	{
		array_push($err_arr,"First Name");
	}
	else if($validator->pattern_math_chcarecter($fname) == FALSE)
	{
		array_push($err_arr,"First Name");
	}
	else if($validator->blank_select($mname) == TRUE && $validator->pattern_math_chcarecter($mname) == FALSE)
	{
		array_push($err_arr,"Middle Name");
	}
	else if($validator->blank_select($lname) == TRUE && $validator->pattern_math_chcarecter($lname) == FALSE)
	{
		array_push($err_arr,"Last Name");
	}
	else if($validator->blank_select($txt_bank) == FALSE)
	{
		array_push($err_arr,"Bank Name");
	}
	else if($validator->blank_select($txt_account) == FALSE)
	{
		array_push($err_arr,"Account Number");
	}
	else if($validator->blank_select($txt_ifsc) == FALSE)
	{
		array_push($err_arr,"IFSC Code");
	}
	else if($validator->valid_bank($txt_bank,$bank_data) == FALSE)
	{
		array_push($err_arr,"Bank Name");
	}
	else if($validator->pattern_number($txt_account) == FALSE)
	{
		array_push($err_arr,"Account Number");
	}
	else if($validator->pattern_match_alphanumeric($tch_branch_code) == FALSE)
	{
		array_push($err_arr,"Branch Code");
	}
	else if($validator->pattern_match_alphanumeric($txt_ifsc) == FALSE)
	{
		array_push($err_arr,"IFSC Code");
	}
	else
	{
		pg_query('BEGIN');
		$query_archive=$db->insert("Insert into prd_employee_master_archive
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
											emp_accommodation,emp_pre_ps,emp_per_ps, emp_unlock_status) 
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
											emp_accommodation,emp_pre_ps,emp_per_ps, emp_unlock_status
											FROM prd_employee_master where emp_id_pk='$emp_id_pk'");
		if($query_archive)
		{								
			$query_insert=$db->update("UPDATE prd_employee_master set
											emp_first_name='$fname',
											emp_second_name='$mname',   
											emp_last_name='$lname',
											emp_bank_name='$txt_bank',
											emp_bank_branch='$tch_bank_branch',
											emp_branch_code='$tch_branch_code',
											emp_micr_no='$tch_micr_code',
											emp_acc_no='$txt_account',
											emp_ifsc_no='$txt_ifsc',
											entry_time='now()',
											entry_ip='".$_SERVER['REMOTE_ADDR']."',
											emp_pension_status='".$update_pension."'
											WHERE
											emp_id_pk='$emp_id_pk'");
											
			$salary_save_query_insert=$db->update("UPDATE prd_employee_salary_save set
											bankname='$txt_bank',
											accountno='$txt_account',
											bank_ifsc='$txt_ifsc'
											WHERE
											emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' 
											AND status_flag='4' AND is_saved='1' AND delete_status='1'");
											
			$final_arc_query_insert=$db->update("UPDATE prd_monthly_salary_archive_final set
											bankname='$txt_bank',
											accountno='$txt_account',
											bank_ifsc='$txt_ifsc'
											WHERE
											emp_id_fk='$emp_id_pk' AND salary_monthyear='".date('Ym')."' 
											AND status_flag='4' AND is_saved='1' AND delete_status='1'");
											
			$benf_update=$db->update(" UPDATE prd_sftp_benf_failure_details SET active_status=2 WHERE emp_id_fk='$emp_id_pk' AND active_status in (1,2) ");
			
		}
		if($query_insert && $benf_update)
		{
			pg_query('COMMIT');
			array_push($success_arr,1);
			array_push($success_arr,$fname." ".$mname." ".$lname);
			array_push($success_arr,$emp_id_pk);
		}
		else
		{
			pg_query('ROLLBACK');
			array_push($success_arr,0);
		}
		
		
	}
}
echo json_encode(array($err_arr,$success_arr));
?>