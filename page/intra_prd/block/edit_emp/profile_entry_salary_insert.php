<?

ob_start();
session_start();
//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";exit;
$_SESSION['http_referer'] = $_SERVER['HTTP_REFERER'];
require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';
require '../../../../includes/library/myvalidation.class.php';

if(
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

)
{
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	$emp_id=$cryptoGraph->encode($_REQUEST['emp_id_pk'],4);
	$gpid=$cryptoGraph->encode($_REQUEST['gp_id'],4);
	include 'profile_entry_salary_form.php';
	exit;
}
else
{
	$update_id=$cryptoGraph->decode($_POST['update_id'],4); 
	$pension_id=$cryptoGraph->decode($_POST['pension_id'],4);
	$gp_id=$_REQUEST['gp_id'];
	$emp_id_pk=$_REQUEST['emp_id_pk'];
	$desig=$_REQUEST['desig'];
	$pay_band=$_REQUEST['pay_band']==''?0:$_REQUEST['pay_band'];
	$pay_in_payband=$_REQUEST['pay_in_payband']==''?0:$_REQUEST['pay_in_payband'];
	$grade_pay=$_REQUEST['grade_pay']==''?0:$_REQUEST['grade_pay'];
	$pay_scale=$_REQUEST['pay_scale']==''?0:$_REQUEST['pay_scale'];
	$cons_pay=$_REQUEST['cons_pay']==''?0:$_REQUEST['cons_pay'];
	$txt_bank=$_REQUEST['txt_bank'];
	$tch_bank_branch=$_REQUEST['tch_bank_branch']==''?'':$_REQUEST['tch_bank_branch'];
	$tch_branch_code=$_REQUEST['tch_branch_code']==''?'':$_REQUEST['tch_branch_code'];
	$tch_micr_code=$_REQUEST['tch_micr_code']==''?0:$_REQUEST['tch_micr_code'];
	$txt_account=$_REQUEST['txt_account'];
	$txt_ifsc=$_REQUEST['txt_ifsc'];
	$form_status=$_REQUEST['form_status'];
	$ropa_status=$_REQUEST['ropa_status'];
	$ropa_level=$_REQUEST['ropa_level'];
	$emp_first_join_date=$_POST['emp_first_join_date'];
	//$pay_in_payband=$_REQUEST['basic_pay'];
	
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
	
	$db = new database();
	
	$code_data = $db->fetch_table("
									SELECT code, description
									FROM prd_dise_code_master;
									
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
	
	$validator = new Validation();
	
	if($validator->blank_select($txt_account) == TRUE)
	{
		foreach($acc_no_check as $key)
		{
			if($txt_account == $key['emp_acc_no'])
			{
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Account number already exists. Please Enter valid account number.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				$gpid=$cryptoGraph->encode($gp_id,4);
				include 'profile_entry_salary_form.php';
				exit;
			}
		}
	}
	if($desig !='1120' && $desig !='1124' && $desig !='1125')
	{
		if($ropa_status=='1' || $emp_first_join_date>='2020-01-01' )
		{
			
			if($validator->blank_select($pay_in_payband) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter BASIC PAY.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
			}
			else if($validator->blank_select($ropa_level) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select LEVEL.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_salary_form.php';
			exit;
			}
		}
		else
		{
		if($validator->blank_select($pay_band) == FALSE && $ropa_status !=1)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Payband.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->blank_select($pay_in_payband) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Pay In Payband.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if(($pay_in_payband < $range[0] || $pay_in_payband > $range[1]) && $ropa_status !=1)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Pay In Payband.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->blank_select($grade_pay) == FALSE && $ropa_status !=1)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Grade Pay.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->blank_select($pay_scale) == FALSE && $ropa_status !=1)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Pay Scale.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->code_match($pay_band,$code_data) == FALSE && $ropa_status !=1)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Valid Pay Band.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->pattern_number(trim($pay_in_payband)) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Pay in Pay Band.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if(strlen($pay_in_payband)>5)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Pay in Pay Band Maximum 5 digit Long.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
	  }
	}
	else
	{
		if($validator->blank_select($cons_pay) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Consolidated Pay.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if($validator->pattern_number(trim($cons_pay)) == FALSE)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Consolidated Pay.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		else if(strlen($cons_pay)>5)
		{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Consolidated Pay Maximum 5 digit Long.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			$gpid=$cryptoGraph->encode($gp_id,4);
			include 'profile_entry_salary_form.php';
			exit;
		}
		
	}
	
	if($validator->blank_select($txt_bank) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Bank Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		$gpid=$cryptoGraph->encode($gp_id,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->blank_select($txt_account) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Account Number.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		$gpid=$cryptoGraph->encode($gp_id,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->blank_select($txt_ifsc) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter IFSC Number.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		$gpid=$cryptoGraph->encode($gp_id,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->valid_bank($txt_bank,$bank_data) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Valid Bank Name.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		$gpid=$cryptoGraph->encode($gp_id,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->pattern_number($txt_account) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Account No.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		$gpid=$cryptoGraph->encode($gp_id,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->pattern_match_alphanumeric($tch_branch_code) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Branch Code.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		$gpid=$cryptoGraph->encode($gp_id,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else if($validator->pattern_match_alphanumeric($txt_ifsc) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid IFSC.</strong></div>';
		$emp_id=$cryptoGraph->encode($emp_id_pk,4);
		$gpid=$cryptoGraph->encode($gp_id,4);
		include 'profile_entry_salary_form.php';
		exit;
	}
	else
	{
		$db = new database();	
		
		if($update_id=='true')
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
										profile_update_time,profile_update_ip_address,spouse_medical_allowance,conv_allow_status,
										ropa_status,ropa_level) 
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
										'now()','".$_SERVER['REMOTE_ADDR']."',spouse_medical_allowance,conv_allow_status,
										ropa_status,ropa_level
										FROM prd_employee_master where emp_id_pk='$emp_id_pk'");
			
			
			
			if($ropa_status==1 || $emp_first_join_date>='2020-01-01'){
				
				//echo 11222; die;
				
			
				 
				$query_insert=$db->update("UPDATE prd_employee_master set
										emp_pay_in_payband='$pay_in_payband',
										emp_cosolidated_pay='$cons_pay',
										emp_bank_name='$txt_bank',
										emp_bank_branch='$tch_bank_branch',
										emp_branch_code='$tch_branch_code',
										emp_micr_no='$tch_micr_code',
										emp_acc_no='$txt_account',
										emp_ifsc_no='$txt_ifsc',
										emp_form_status='$emp_form_status',
										ropa_level='$ropa_level',
										ropa_status='1',
										entry_time='now()',
										entry_ip='".$_SERVER['REMOTE_ADDR']."',
										emp_pension_status='".$update_pension."'
										WHERE
										emp_id_pk='$emp_id_pk'");
			}
			else{
				 
				
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
										emp_pension_status='".$update_pension."'
										WHERE
										emp_id_pk='$emp_id_pk'");
		
			}
		}
		else
		{
			
			$query_insert=TRUE;
		}
		
		if($query_insert && $update_id=='true')
		{
			
			$query_fetch_bank=$db->fetch_table("
							SELECT 							  
									  count(emp_id_fk) as count
									  
							FROM prd_employee_salary_save
							WHERE
										emp_id_fk='$emp_id_pk' and salary_monthyear='".date('Ym')."' and delete_status='1'
	");
	//echo $query_fetch_bank[0]['count']; die;
	if($query_fetch_bank[0]['count']!='0'  )
	{
		
		$query_insert_bank=$db->update("UPDATE prd_employee_salary_save set
										bankname='$txt_bank',
										accountno='$txt_account',
										bank_ifsc='$txt_ifsc'
										WHERE
										emp_id_fk='$emp_id_pk' and salary_monthyear='".date('Ym')."' and delete_status='1'");
	}
	//echo $update_id;exit;
			
			header('Location:profile_entry_per.php?desig='.$cryptoGraph->encode($_REQUEST['desig'],4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&gp_id='.$cryptoGraph->encode($gp_id,4).'&confirm=success');
			exit(0);
		}
		else if($update_id=='false')
		{
			header('Location:profile_entry_per.php?desig='.$cryptoGraph->encode($_REQUEST['desig'],4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&gp_id='.$cryptoGraph->encode($gp_id,4));
			exit(0);
		}			
		else
		{
			header('Location:profile_entry_sal.php?desig='.$cryptoGraph->encode($_REQUEST['desig'],4).'&emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&gp_id='.$cryptoGraph->encode($gp_id,4).'&confirm=false');
			exit(0);
		}
	}
}
?>