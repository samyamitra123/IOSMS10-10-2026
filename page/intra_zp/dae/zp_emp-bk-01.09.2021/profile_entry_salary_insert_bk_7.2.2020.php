<?

ob_start();
session_start();
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
require '../../../../includes/library/myvalidation.class.php';


if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	$emp_id=$cryptoGraph->encode($_POST['emp_id_pk'],4);
	include 'profile_entry_salary_form.php';
	exit;
}
else
{
	$edit_status=$cryptoGraph->decode($_POST['edit_status'],4);  
	$emp_id_pk=$_POST['emp_id_pk'];
	$emp_type=$_POST['emp_type'];
	$desig=$_POST['desig'];
	$pay_band=$_POST['pay_band']==''?0:$_POST['pay_band'];
	$pay_in_payband=$_POST['pay_in_payband']==''?0:$_POST['pay_in_payband'];
	$grade_pay=$_POST['grade_pay']==''?0:$_POST['grade_pay'];
	$pay_scale=$_POST['pay_scale']==''?0:$_POST['pay_scale'];
	$cons_pay=$_POST['cons_pay']==''?0:$_POST['cons_pay'];
	$txt_bank=$_POST['txt_bank'];
	$tch_bank_branch=$_POST['tch_bank_branch']==''?'':$_POST['tch_bank_branch'];
	$tch_branch_code=$_POST['tch_branch_code']==''?'':$_POST['tch_branch_code'];
	$tch_micr_code=$_POST['tch_micr_code']==''?0:$_POST['tch_micr_code'];
	$txt_account=$_POST['txt_account'];
	$txt_ifsc=$_POST['txt_ifsc'];
	$gpf_account=$_POST['gpf_account'];
	$form_status=$_POST['form_status'];
	
	$emp_pension_status=$cryptoGraph->decode($_POST['emp_pension_status'],4);
	if($emp_pension_status == 0)
	{
		$new_emp_pension_status = 0;
	}
	else if($emp_pension_status == 1)
	{
		$new_emp_pension_status = 2;
	}
	else if($emp_pension_status == 2)
	{
		$new_emp_pension_status = 2;
	}
	
	
	$db = new database();
	$code_data = $db->fetch_table("
									SELECT payband_code,payband_name
									FROM prd_payband_master
									");
	
	$bank_data = $db->fetch_table("
									SELECT bank_code
									FROM prd_dise_bank_master;
									");
	
	$check_scale= $db->fetch_table("select payscale_range from prd_dise_payscale_master where payscale_code='$pay_scale'");
	$scale=$check_scale[0]['payscale_range'];
	$range=explode("-",$scale);
	
	$acc_no_check=$db->fetch_table("select emp_acc_no from prd_employee_master where 
									emp_acc_no !=(select emp_acc_no from prd_employee_master where emp_id_pk='$emp_id_pk' )");	
	
	if($form_status>3)
	{
		$emp_form_status=$form_status;
	}
	else
	{
		$emp_form_status=3;
	}
	
	if($validator->blank_select($txt_account) == TRUE)
	{
		foreach($acc_no_check as $key)
		{
			if($txt_account == $key['emp_acc_no'])
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Account number already exists. Please Enter valid account number.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_salary_form.php';
				exit;
			}
		}
	}
	
	if($desig !='1')
	{
		if($validator->blank_select($pay_band) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Payband.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->blank_select($pay_in_payband) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Pay In Payband.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($pay_in_payband < $range[0] || $pay_in_payband > $range[1])
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Pay In Payband.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->blank_select($grade_pay) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Grade Pay.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->blank_select($pay_scale) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Pay Scale.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->payband_match($pay_band,$code_data) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Valid Pay Band.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->pattern_number(trim($pay_in_payband)) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Pay in Pay Band.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if(strlen($pay_in_payband)>5)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Pay in Pay Band Maximum 5 digit Long.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
	}
	else
	{
		if($validator->blank_select($cons_pay) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Consolidated Pay.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->pattern_number(trim($cons_pay)) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Consolidated Pay.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if(strlen($cons_pay)>5)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Consolidated Pay Maximum 5 digit Long.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
	}
	
	if($validator->blank_select($txt_bank) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Bank Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->blank_select($txt_account) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Account Number.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->blank_select($txt_ifsc) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter IFSC Number.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->valid_bank($txt_bank,$bank_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Valid Bank Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->pattern_number($txt_account) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Account No.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->pattern_match_alphanumeric($tch_branch_code) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Branch Code.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->pattern_match_alphanumeric($txt_ifsc) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid IFSC.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
//	else if($validator->blank_select($gpf_account) == FALSE && $emp_type=='366')
//	{
//		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter GPF Account Number.</strong></div>';
//		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
//		include 'profile_entry_salary_form.php';
//		exit;
//	}
	/*else if($validator->pattern_number($gpf_account) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid GPF Account No.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_salary_form.php';
		exit;
	}*/
	else
	{
	  
		if($edit_status == 2)
		{
			$db = new database();	
			$emp_status=$db->fetch_table(" SELECT emp_status FROM prd_employee_master WHERE emp_id_pk='$emp_id_pk' ");
			
			/*if($emp_status[0]['emp_status']=='4')
			{
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
			profile_update_time,profile_update_ip_address,spouse_medical_allowance,conv_allow_status) 
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
			'now()','".$_SERVER['REMOTE_ADDR']."',spouse_medical_allowance,conv_allow_status
			FROM prd_employee_master where emp_id_pk='$emp_id_pk'");
			
			}*/
			
			//if($emp_status[0]['emp_status']!='4' || $query_archive)
			if($emp_status[0]['emp_status']!='4' || $emp_status[0]['emp_status']=='4')
			{	
			    
				$query_insert=$db->update("UPDATE prd_employee_master set
												emp_pay_band='$pay_band',
												emp_pay_in_payband='$pay_in_payband',
												emp_grade_pay='$grade_pay',
												emp_pay_scale='$pay_scale',
												emp_cosolidated_pay='$cons_pay',
												emp_bank_name='$txt_bank',
												emp_bank_branch='$tch_bank_branch',
												emp_branch_code='$tch_branch_code',
												emp_micr_no='$tch_micr_code',
												emp_acc_no='$txt_account',
												emp_ifsc_no='$txt_ifsc',
												emp_form_status='$emp_form_status',
												entry_time='now()',
												entry_ip='".$_SERVER['REMOTE_ADDR']."',
												emp_gpf_acc_no='".$gpf_account."'
												WHERE
												emp_id_pk='$emp_id_pk'");
			}
		}
		else if($edit_status == 1)
		{
			$query_insert = 1;
		}
		
		if($query_insert)
		{
			header('Location:profile_entry_per.php?desig='.$cryptoGraph->encode($_POST['desig'],4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=success');
			exit(0);
		}
		else
		{
			header('Location:profile_entry_sal.php?desig='.$cryptoGraph->encode($_POST['desig'],4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=false');
			exit(0);
		}
	}
}
?>