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
	include 'entry_prof.php';
	exit;
}
else
{
	$emp_id_pk=$_POST['emp_id_pk'];
	$emp_type=$_POST['emp_type'];
	$vice_desig=!empty($_POST['vice_desig'])?$_POST['vice_desig'] : '0';
	$first_join=explode('-',$_POST['first_join_date']);
	$first_join_date=$first_join[2].'-'.$first_join[1].'-'.$first_join[0];
	
	if($_POST['conf_dt_applicable']=='1'){
	$conf_dt=explode('-',$_POST['confirm_date']);
	$confirm_date=$conf_dt[2].'-'.$conf_dt[1].'-'.$conf_dt[0];
	}else{
	$confirm_date='0001-01-01';
	}
	$post_date=explode('-',$_POST['present_post_date']);
	$present_post_date=$post_date[2].'-'.$post_date[1].'-'.$post_date[0];
	$office_date=explode('-',$_POST['present_office_date']);
	$present_office_date=$office_date[2].'-'.$office_date[1].'-'.$office_date[0];
	$retire_date=explode('-',$_POST['emp_date_retirement']);
	$emp_date_retirement=$retire_date[2].'-'.$retire_date[1].'-'.$retire_date[0];
	//$emp_date_retirement=date("Y-m-d", strtotime($_POST['emp_date_retirement']));
	$termination=explode('-',$_POST['emp_date_retirement']);
	$emp_date_termination=$termination[2].'-'.$termination[1].'-'.$termination[0];
	if($emp_type=='366')
	{
		$deputation='1';
	}
	else
	{
		$deputation=$_POST['deputation']==''?'0':$_POST['deputation'];
	}
	$employee_group=$_POST['employee_group']==''?'0':$_POST['employee_group'];
	$first_desig=!empty($_POST['first_desig'])?$_POST['first_desig']:'0';
	$increment_date=date("Y-m-d", strtotime($_POST['increment_date']));
	$increment_amount=!empty($_POST['increment_amount'])?$_POST['increment_amount']:'0.00';
	$conf_dt_applicable=$_POST['conf_dt_applicable'];
	$form_status=$_POST['form_status'];
	$notification_no=$_POST['notice_no']; 
	
	$edit_status=$cryptoGraph->decode($_POST['edit_status'],4);  
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
	
	if($form_status>2)
	{
		$emp_form_status=$form_status;
	}
	else
	{
		$emp_form_status=2;
	}
	
	$db = new database();
	$code_data = $db->fetch_table("SELECT designation_id, designation_name from zpemp_emp_desig_master where status='1'");

	if($validator->blank_select($notification_no) == FALSE || $notification_no == '0')
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please enter Notification number of confirming the Appointment.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	if($validator->blank_select($vice_desig) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Designation.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	else if($validator->blank_select($first_join_date) == FALSE || $first_join_date == '0001-01-01')
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of First Joining in service.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	
	else if($_POST['conf_dt_applicable']=='1' && ($validator->blank_select($confirm_date) == FALSE || $confirm_date == '0001-01-01'))
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of Confirmation in service.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	/*else if($_POST['conf_dt_applicable']=='1' && strtotime($confirm_date)<strtotime($first_join_date)){
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Date of Confirmation in service.</strong></div>';
	$emp_id=$cryptoGraph->encode($emp_id_pk,4);
	include 'entry_prof.php';
	exit;
	}*/
	else if($validator->blank_select($present_post_date) == FALSE || $present_post_date == '0001-01-01')
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of joining in the present post.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	/*else if(strtotime($present_post_date)<strtotime($first_join_date)){
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Date of joining in the present post.</strong></div>';
	$emp_id=$cryptoGraph->encode($emp_id_pk,4);
	include 'entry_prof.php';
	exit;
	}*/
	else if($validator->blank_select($present_office_date) == FALSE || $present_office_date == '0001-01-01')
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of Joining in the Present Office.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	else if($validator->blank_select($increment_date) == FALSE || $increment_date == '0001-01-01')
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of Increment .</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	/*else if(strtotime($present_office_date)<strtotime($first_join_date) || strtotime($present_office_date)<strtotime($confirm_date) || strtotime($present_office_date)<strtotime($present_post_date)){
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Date of Joining in the Present Office.</strong></div>';
	$emp_id=$cryptoGraph->encode($emp_id_pk,4);
	include 'entry_prof.php';
	exit;
	}*/
	else if($validator->blank_select($emp_date_retirement) == FALSE || $emp_date_retirement == '0001-01-01')
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Date of Retirement / Termination.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	else if($validator->blank_select($increment_amount) == FALSE || $increment_amount == '0')
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Increment Amount.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	/*else if($vice_desig!='1')
	{
		if($validator->blank_select($deputation) == FALSE || $deputation == '0001-01-01')
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Whether on Deputation.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
		}
		else if($validator->blank_select($employee_group) == FALSE || $employee_group == '0001-01-01')
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Employee Group.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'entry_prof.php';
			exit;
		}
	}*/
	
	
	else if($validator->zp_desig_match($vice_desig,$code_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Designation Selection.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
		exit;
	}
	else if( $first_desig!='0' && $validator->zp_desig_match($first_desig,$code_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Invalid Designation at First Appointment Selection.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		include 'entry_prof.php';
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
				emp_desig='$vice_desig',
				emp_first_join_date='$first_join_date',
				emp_conf_join_date='$confirm_date',
				emp_join_prsnt_post_date='$present_post_date',
				emp_join_prsnt_office_date='$present_office_date',
				emp_retirement_date='$emp_date_retirement',
				emp_termination_date='$emp_date_termination',
				emp_status_deputation='$deputation',
				emp_group='$employee_group',
				emp_desig_first_app='$first_desig',
				emp_next_increment_date='$increment_date',
				emp_next_increment_amount='$increment_amount',
				conf_dt_flag='$conf_dt_applicable',
				emp_form_status='$emp_form_status',
				entry_time='now()',
				notification_no='".$notification_no."',
				entry_ip='".$_SERVER['REMOTE_ADDR']."',
				emp_pension_status='".$new_emp_pension_status."'
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
			header('Location:profile_entry_sal.php?desig='.$cryptoGraph->encode($_POST['vice_desig'],4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=success');
			exit(0);
		}
		else
		{
			header('Location:profile_entry_prof.php?desig='.$cryptoGraph->encode($_POST['vice_desig'],4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'confirm=false');
			exit(0);
		}
	}
}
?>