<?
ob_start();
session_start();
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
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
	include 'profile_entry_basic_edit_form.php';
	exit;
}
else
{
	$update_id=$cryptoGraph->decode($_POST['update_id'],4);
	$pension_id=$cryptoGraph->decode($_POST['pension_id'],4);
	$emp_id_pk=$_POST['emp_id_pk'];
	$emp_date_retirement=$_POST['emp_date_retirement'];
	$fname=strtoupper($_POST['tch_fname']);
	$mname=strtoupper($_POST['tch_mname']);
	$lname=strtoupper($_POST['tch_lname']);
	$dob=date("Y-m-d",strtotime($_POST['tch_dob']));
	$drpSex=$_POST['drpSex'];
	$drpCast=$_POST['drpCast'];
	$voter_id=$_POST['voter_id'];
	$aadhar_status=$_POST['aadhar_status'];
	$aadhaar_no=$_POST['aadhaar_no'];
	$emp_quali=$_POST['emp_quali'];
	
	$pension_stat=$_POST['pension_stat'];
	if($pension_stat=='0')
	{
		$update_pension='0';
	}
	elseif(($pension_stat=='1' || $pension_stat=='2') && $pension_id=='true')
	{
		$update_pension='2';
	}
	elseif($pension_id=='false')
	{
		$update_pension=$pension_stat;
	}
	
	if($validator->blank_select($fname) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter First Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->pattern_math_chcarecter($fname) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in First Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->blank_select($mname) == TRUE && $validator->pattern_math_chcarecter($mname) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Middle Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->blank_select($lname) == TRUE && $validator->pattern_math_chcarecter($lname) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Last Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->blank_select($dob) == FALSE || $dob == '0001-01-01')
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid DOB.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->blank_select($dob) == TRUE && $validator->valid_age($dob) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Date Of Birth must be greater than 18 year.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->blank_select($drpSex) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Gender.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->blank_select($drpCast) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Caste.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->blank_select($voter_id) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Voter ID.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->blank_select($aadhar_status) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Whether Aadhaar Card Present or not.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_form.php';
		exit;
	}
	else if($validator->blank_select($aadhar_status) == FALSE && $validator->blank_select($aadhaar_no) == TRUE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Whether Aadhaar Card Present or not.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_form.php';
		exit;
	}
	else if($aadhar_status == '1' && $validator->blank_select($aadhaar_no) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Aadhaar No.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_form.php';
		exit;
	}
	else if($validator->blank_select($emp_quali) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Educational Qualification.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->pattern_number(trim($aadhaar_no)) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Aadhar Number.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	else if($validator->blank_select($aadhaar_no) == TRUE && strlen($aadhaar_no) !=12)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Aadhar Number Must be 12 digit long.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	/*-----------------------------------------------Code_Master Checking--------------------------------------------------------------------*/
	
	$db = new database();
	$voterid_check=$db->fetch_table("select emp_id_pk,emp_voter_id from prd_employee_master where emp_voter_id !=
									(select emp_voter_id from prd_employee_master where emp_id_pk='$emp_id_pk' and 
									ps_id_fk='".$_SESSION['location']['ps_id']."')");
	
	$aadhar_check=$db->fetch_table("select emp_id_pk,emp_aadhar_no from prd_employee_master where emp_aadhar_no !=
									(select emp_aadhar_no from prd_employee_master where emp_id_pk='$emp_id_pk' and 
									ps_id_fk='".$_SESSION['location']['ps_id']."')");
	
	$code_data = $db->fetch_table("
									SELECT code, description
									FROM prd_dise_code_master;
									");
	
	if($validator->blank_select($voter_id) == TRUE)
	{
		foreach($voterid_check as $key)
		{
			if(strtoupper($voter_id) == strtoupper($key['emp_voter_id']))
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Voter ID already exists. Please Enter valid Voter ID.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_basic_edit_form.php';
				exit;
			}
		
		}
	}
	if($validator->blank_select($aadhaar_no) == TRUE)
	{
		foreach($aadhar_check as $key)
		{
			if($aadhaar_no == $key['emp_aadhar_no'])
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Aadhaar ID already exists. Please Enter valid Aadhaar ID.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_basic_edit_form.php';
				exit;
			}
		
		}
	}
	if($validator->code_match($drpCast,$code_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Caste Selection.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	if($validator->code_match($drpSex,$code_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Gender Selection.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	if($validator->code_match($emp_quali,$code_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Educational Qualification Selection.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_basic_edit_form.php';
		exit;
	}
	/*--------------------------------------------------END-------------------------------------------------------------------------*/
	
	else
	{
		$db = new database();	
		if(!empty($emp_date_retirement))
		{
			$retire_date=explode('-',$_POST['emp_date_retirement']);
			$emp_retirement_date=$retire_date[2].'-'.$retire_date[1].'-'.$retire_date[0];
			
			$query_insert=$db->update("UPDATE prd_employee_master SET
										emp_first_name='$fname',
										emp_second_name='$mname',   
										emp_last_name='$lname',
										emp_dob='$dob',
										emp_sex='$drpSex',
										emp_caste='$drpCast',
										emp_voter_id='".strtoupper($voter_id)."',
										emp_aadhar_no='$aadhaar_no',
										emp_edu_quali='$emp_quali',
										emp_retirement_date='$emp_retirement_date',
										entry_time='now()',
										entry_ip='".$_SERVER['REMOTE_ADDR']."',
										emp_pension_status='".$update_pension."'
										WHERE emp_id_pk='$emp_id_pk'"
										);
		}
		else
		{
		
			$emp_status=$db->fetch_table(" SELECT emp_status,emp_id_const FROM prd_employee_master WHERE emp_id_pk='$emp_id_pk' ");
			if(($emp_status[0]['emp_status']=='4' && $update_id=='true') || ($emp_status[0]['emp_status']=='10' && $emp_status[0]['emp_id_const']!='0'))
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
			
			}
			
			if($emp_status[0]['emp_status']!='4' || $query_archive)
			{
				$query_insert=$db->update("UPDATE prd_employee_master SET
											emp_first_name='$fname',
											emp_second_name='$mname',   
											emp_last_name='$lname',
											emp_dob='$dob',
											emp_sex='$drpSex',
											emp_caste='$drpCast',
											emp_voter_id='".strtoupper($voter_id)."',
											emp_aadhar_no='$aadhaar_no',
											emp_edu_quali='$emp_quali',
											entry_time='now()',
											entry_ip='".$_SERVER['REMOTE_ADDR']."',
											emp_pension_status='".$update_pension."'
											WHERE emp_id_pk='$emp_id_pk'"
											);	
			}
			else
			{
				$query_insert=TRUE;
			}
		}
		
		
		if($query_insert && $update_id=='true')
		{
			
			if($emp_date_retirement!='')
			{
				header('Location:profile_entry_prof.php?emp_date_retirement='.$cryptoGraph->encode($emp_date_retirement,4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=success');
				exit(0);
			}
			else
			{
				header('Location:profile_entry_prof.php?emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=success');
				exit(0);
			}
		}
		else if($update_id=='false')
		{
			header('Location:profile_entry_prof.php?emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&gp_id='.$cryptoGraph->encode($gp_id,4));
			exit(0);
		}
		else
		{
			header('Location:profile_entry_basic_edit.php?emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=false');
			exit(0);
		}
	}
}
?>