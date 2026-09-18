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
// $_POST['residental_status']; 
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	$emp_id=$cryptoGraph->encode($_POST['emp_id_pk'],4);
	include 'profile_entry_personal_form.php';
	exit;
}
else
{
	$desig=$_POST['desig'];
	$emp_id_pk=$_POST['emp_id_pk'];
	$father_fname = strtoupper($_POST['father_fname']);
	if($_POST['father_mname']=='MIDDLE')
	{
		$father_mname = '';
	}
	else
	{
		$father_mname = strtoupper($_POST['father_mname']);
	}
	if($_POST['father_lname']=='LAST')
	{
		$father_lname = '';
	}
	else
	{
		$father_lname = strtoupper($_POST['father_lname']);
	}
	$father_name=$father_fname." ".$father_mname." ".$father_lname;	 
	$mother_fname = strtoupper($_POST['mother_fname']);
	if($_POST['mother_mname'] == 'MIDDLE')
	{
		$mother_mname = '';
	}
	else
	{
		$mother_mname = strtoupper($_POST['mother_mname']);
	}
	if($_POST['mother_lname']=='LAST')
	{
		$mother_lname = '';
	}
	else
	{
		$mother_lname = strtoupper($_POST['mother_lname']);
	}
	$mother_name=$mother_fname." ".$mother_mname." ".$mother_lname;
	
	$religion=$_POST['religion'];
	$mother_tounge=$_POST['mother_tounge'];
	$marital_status = $_POST['marital_status'];
	if($_POST['spouse_fname']=='FIRST')
	{
		$spouse_fname = '';
	}
	else
	{
		$spouse_fname = strtoupper($_POST['spouse_fname']);
	}
	if($_POST['spouse_mname']=='MIDDLE')
	{
		$spouse_mname = '';
	}
	else
	{
		$spouse_mname = strtoupper($_POST['spouse_mname']);
	}
	if($_POST['spouse_lname']=='LAST')
	{
		$spouse_lname = '';
	}
	else
	{
		$spouse_lname = strtoupper($_POST['spouse_lname']);
	}
	$edit_status =$cryptoGraph->decode($_POST['edit_status'],4);
	//$spouse_name=$spouse_fname." ".$spouse_mname." ".$spouse_lname;
	if($marital_status == 241 )
	{
		$spouse_name=$spouse_fname." ".$spouse_mname." ".$spouse_lname;
	}
	else
	{
		$spouse_name='';
	}
	$employee_status=$_POST['employed_not']==''?'0':$_POST['employed_not'];
	$employed_details=$_POST['employed_details'];
	$spouse_pay=$_POST['spouse_pay']==''?'0.00':$_POST['spouse_pay'];
	$spouse_hra=$_POST['spouse_hra']==''?'0.00':$_POST['spouse_hra'];
	$residental_status=$_POST['residental_status']==''?'0':$_POST['residental_status'];
	$hra_scheem_type=$_POST['hra_scheem_type']==''?'0':$_POST['hra_scheem_type']; 
	$hra_ammount=$_POST['hra_ammount_value']==''?'0':$_POST['hra_ammount_value'];
	if($residental_status=='251')
	{
		 //$hra_scheem_type=$_POST['hra_scheem_type'];
		if($hra_scheem_type=='200' || $hra_scheem_type=='202')
		{
			
			 $hra_ammount=0;
		}
		else
		{
			 $hra_ammount=$_POST['hra_ammount_value'];
		}
	}
	$house_space_name=$_POST['house_space_name'];
	$tch_blood_group=$_POST['tch_blood_group']==''?'0':$_POST['tch_blood_group'];
	$height=$_POST['height']==''?'0':$_POST['height'];
	$identification_mark=$_POST['identification_mark'];
	$differently_able=$_POST['differently_able']==''?'0':$_POST['differently_able'];
	$state_details=$_POST['state_details'];
	$conv_status=$_POST['conv_eligible']==''?'0':$_POST['conv_eligible'];
	$form_status=$_POST['form_status'];
	if($marital_status != 241 || $desig=='1')
	{
		$emp_accommodation = 0;
	}
	else
	{
		$emp_accommodation=$_POST['emp_accommodation'];
	}
	$spouse_medical_allowance=$_POST['spouse_medical_allowance']; 
	
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
	
	if($form_status>4)
	{
		$emp_form_status=$form_status;
	}
	else
	{
		$emp_form_status=4;
	}
	$db = new database();
	$check_emp_type = $db->fetch_table("SELECT zp_emp_type FROM prd_employee_master
										WHERE emp_id_pk = '".$emp_id_pk."'
										and zp_id_fk = '".$_SESSION['location']['district_id']."'");
	
	$chk_zp_emp_type = $check_emp_type[0]['zp_emp_type'];
	
	if($validator->blank_select($father_fname) == TRUE && $validator->pattern_math_chcarecter($father_fname) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Father Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($validator->blank_select($father_mname) == TRUE && $validator->pattern_math_chcarecter($father_mname) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Father Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($validator->blank_select($father_lname) == TRUE && $validator->pattern_math_chcarecter($father_lname) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Father Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($validator->blank_select($mother_fname) == TRUE && $validator->pattern_math_chcarecter($mother_fname)== FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Mother Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($validator->blank_select($mother_mname) == TRUE && $validator->pattern_math_chcarecter($mother_mname)== FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Mother Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($validator->blank_select($mother_lname) == TRUE && $validator->pattern_math_chcarecter($mother_lname)== FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Mother Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($validator->blank_select($religion) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Relegion.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($validator->blank_select($mother_tounge) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Mother Tongue.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($validator->blank_select($marital_status) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Mariatal Status.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($validator->pattern_number($height) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Plese Enter Numeric Value In Height Field.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if(!empty($height) && strlen($height)>4)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Height Should be maximum 4 digit long.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($validator->blank_select($residental_status) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Residential Status.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($residental_status=='251' && $hra_scheem_type=='201' && ($validator->blank_select($hra_ammount) == FALSE || $hra_ammount=='0'))
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter HRA Ammount.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($residental_status=='251' && ($hra_scheem_type=='200' || $hra_scheem_type=='202') &&  $hra_ammount!='0')
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Wrong HRA Ammount Inserted.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	else if($marital_status=='241')
	{
		if($validator->blank_select($_POST['spouse_fname']) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Spouse Name.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_personal_form.php';
			exit;
		}
		else if($validator->blank_select($_POST['spouse_fname']) == TRUE && $validator->pattern_math_chcarecter($_POST['spouse_fname']) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Spouse Name.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_personal_form.php';
			exit;
		}
		else if($validator->blank_select($_POST['spouse_mname']) == TRUE && $validator->pattern_math_chcarecter($_POST['spouse_mname']) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Spouse Name.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_personal_form.php';
			exit;
		}
		else if($validator->blank_select($_POST['spouse_lname']) == TRUE && $validator->pattern_math_chcarecter($_POST['spouse_lname']) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Only Character in Spouse Name.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_personal_form.php';
			exit;
		}
		else if($desig!='1' && $validator->blank_select($_POST['emp_accommodation']) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Whether Spouse is being provided any accommodation by Employer.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_personal_form.php';
			exit;
		}
		else if(($chk_zp_emp_type == 366 || $chk_zp_emp_type == 367) && ($validator->blank_select($spouse_medical_allowance) == FALSE))
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Please Select Spouse opted for enrolment in West Bengal Health Scheme.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_personal_form.php';
			exit;
		}
		else if($employee_status=='1')
		{
			if($validator->blank_select($employed_details) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Spouse Employement Details.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_personal_form.php';
				exit;
			}
			else if($validator->blank_select($spouse_pay) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Spouse Pay.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_personal_form.php';
				exit;
			}
			else if($validator->pattern_number($spouse_pay) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Plese Enter Numeric Value In Spouse Pay.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_personal_form.php';
				exit;
			}
			else if($validator->blank_select($spouse_hra) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Spouse HRA.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_personal_form.php';
				exit;
			}
			else if($validator->pattern_number($spouse_hra) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Plese Enter Numeric Value In Spouse HRA.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_personal_form.php';
				exit;
			}
			else if($desig!='1' && $validator->blank_select($emp_accommodation) == FALSE)
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Whether Spouse is being provided any accommodation by Employer.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_personal_form.php';
				exit;
			}
			
		}
	}
	
	$db = new database();
	$code_data = $db->fetch_table("
									SELECT code, description
									FROM prd_dise_code_master;
									");
	
	if($validator->code_match($religion,$code_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Religion Selection.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	if($validator->code_match($mother_tounge,$code_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Mother Tongue Selection.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	if($validator->code_match($marital_status,$code_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Marital Status Selection.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
	if($validator->code_match($residental_status,$code_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Residential Status Selection.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'profile_entry_personal_form.php';
		exit;
	}
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
				emp_father_name='$father_name',
				emp_mother_name='$mother_name',
				emp_religion='$religion',
				emp_mother_tongue='$mother_tounge',
				emp_marital_status='$marital_status',
				emp_spouse_name='$spouse_name',
				emp_spouse_job_status='$employee_status',
				emp_spouse_details='$employed_details',
				emp_spouse_pay='$spouse_pay',
				emp_spouse_hra='$spouse_hra',
				emp_spouse_res='$residental_status',
				emp_spouse_house_schm='$house_space_name',
				emp_blood_grp='$tch_blood_group',
				emp_height='$height',
				emp_diff_able='$differently_able',
				emp_disable_status='$state_details',
				emp_idf_mark='$identification_mark',
				emp_form_status='$emp_form_status',
				entry_time='now()',
				entry_ip='".$_SERVER['REMOTE_ADDR']."',
				spouse_medical_allowance='$spouse_medical_allowance',
				conv_allow_status='".$conv_status."',
				emp_accommodation='".$emp_accommodation."',
				emp_pension_status='".$new_emp_pension_status."',
				emp_gvt_hra_type='".$hra_scheem_type."',
				emp_gvt_hra_ammount='".$hra_ammount."'
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
			header('Location:profile_entry_contact.php?emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=success');
			exit(0);
		}
		else
		{
			header('Location:profile_entry_per.php?emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&desig='.$cryptoGraph->encode($_POST['desig'],4).'&confirm=false');
			exit(0);
		}
		
	}
}
?>