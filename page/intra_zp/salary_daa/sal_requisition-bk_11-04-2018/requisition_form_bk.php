<?php

require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}
if (
	  !isset($_SESSION['user_info']['stake_user'])
	| !isset($_SESSION['user_info']['stake_level'])
	| !isset($_SESSION['user_info']['flag'])

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
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

$crypto = new cryptography();
//require 'includes/library/session.class.php';
$createdby = substr ($_SESSION[location][gpcode],0,7);

$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//echo "nirupam";
//echo $enc_session=md5('369'.$_SESSION['security_token']);
$_SESSION['security_token_save']=$time_token;
$str=$_SESSION['location']['gpcode'];

$state10=substr($str,0,4); // dont know why used nirupam 23_03_17

//-------------------------------------------------------------------

		$db = new database();
		$suspend_salary_status = 0;
		$dauname = substr($uname,0,2);
		//$dise1 = !empty($_GET['dise'])?$crypto->decode($_GET['dise'],3):$_SESSION['location']['gp_id'];
		// To get the Municipality Id by nirupam 23_03_2017
		$dise1 = !empty($_GET['dise'])?$crypto->decode($_GET['dise'],3):$_SESSION['location']['gpcode'];
	 	$dise = substr($dise1,0,7);
		//End To get the Municipality Id by nirupam 23_03_2017
		
		function func_gradepay($val){
			$db = new database();
			$arr = $db->fetch_table("select grade_amount from mad_dise_gradepay_master where grade_code='$val'");
			return $arr[0]['grade_amount'];
		}
		function salaryType($sal_type){
			$db = new database();
			$arr = $db->fetch_table("select salary_type from mad_salary_type where type_id='$sal_type'");
			return $arr[0]['salary_type'];
		}
		function getAmount($dise,$empcd,$type,$basic,$emp_id_pk){
			$db = new database();
			/*if($type=='cpf'){
				$arr = $db->fetch_table("select cpf from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND emp_id_fk='".$dise."'");
				if($arr[0]['cpf']){
					return $arr[0]['cpf'];
				}else{
					return 0;
				}
			}*/ //Commented on 23.03.2017 because it is not required right now by Rupesh.
			//die;
		
			/*if($type=='pfl'){
				$arr = $db->fetch_table("select pf_loan from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND emp_id_fk='".$dise."'");
				if($arr[0]['pf_loan']){
					return $arr[0]['pf_loan'];
				}else{
					return 0;
				}
			}*/ //Changed for other deduction module
			if($type=='scc'){
				$arr = $db->fetch_table("select staff_coop_contribution from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND municipality_id_fk='".$dise."' AND emp_id_fk = '".$emp_id_pk."'"); 
				
				
				if(count($arr) > 0){
					return $arr[0]['staff_coop_contribution'];
				}else{
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM mad_other_deduction
												WHERE other_deduction_type_variable = 'scc'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pk."' 
												AND municipal_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'");
												
				if($other_deduction_data[0]['deduction_amount'] > 0)
				{
					return $other_deduction_data[0]['deduction_amount'];
				}
				else
				{
					return 0;	
				}
			  }
			}
			
			if($type=='ssl'){
				$arr = $db->fetch_table("select sal_savings_lic from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND municipality_id_fk='".$dise."' AND emp_id_fk = '".$emp_id_pk."'"); 
				
				
				if(count($arr) > 0){
					return $arr[0]['sal_savings_lic'];
				}else{
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM mad_other_deduction
												WHERE other_deduction_type_variable = 'ssl'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pk."' 
												AND municipal_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'");
												
				if($other_deduction_data[0]['deduction_amount'] > 0)
				{
					return $other_deduction_data[0]['deduction_amount'];
				}
				else
				{
					return 0;	
				}
			  }
			}
			
			if($type=='scl'){
				$arr = $db->fetch_table("select staff_coop_lic from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND municipality_id_fk='".$dise."' AND emp_id_fk = '".$emp_id_pk."'"); 
				
				
				if(count($arr) > 0){
					return $arr[0]['staff_coop_lic'];
				}else{
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM mad_other_deduction
												WHERE other_deduction_type_variable = 'scl'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pk."' 
												AND municipal_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'");
												
				if($other_deduction_data[0]['deduction_amount'] > 0)
				{
					return $other_deduction_data[0]['deduction_amount'];
				}
				else
				{
					return 0;	
				}
			  }
			}
			
			if($type=='gpf'){
				//die;
				$arr = $db->fetch_table("select gpf from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND municipality_id_fk='".$dise."' AND emp_id_fk = '".$emp_id_pk."'"); 
				
				
				if(count($arr) > 0){
					return $arr[0]['gpf'];
				}else{
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM mad_other_deduction
												WHERE other_deduction_type_variable = 'gpf'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pk."' 
												AND municipal_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'");
				if($other_deduction_data[0]['deduction_amount'] > 0)
				{
					return $other_deduction_data[0]['deduction_amount'];
				}
				else
				{
					return 0;	
				}
			  }
			}
			
			
			if($type=='itax'){
				
				$arr = $db->fetch_table("select i_tax from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND municipality_id_fk='".$dise."'"); 

				if($arr[0]['i_tax']){
					return $arr[0]['i_tax'];
				}else{
					return 0;
				}
			}
			
			if($type=='ovd'){ 
				$arr = $db->fetch_table("select overdrawn from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND municipality_id_fk='".$dise."'"); 
				if($arr[0]['overdrawn']){
					return $arr[0]['overdrawn'];
				}else{
					return 0;
				}
			}
			
			
			
			/*if($type=='gsli'){ changed for other deduction module
				$arr = $db->fetch_table("select gsli from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND emp_id_fk='".$dise."'");
				if($arr[0]['gsli']){
					return $arr[0]['gsli'];
				}else{
					return 0;
				}
			}*/
			if($type=='gsli'){
				$arr = $db->fetch_table("select gsli from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND municipality_id_fk='".$dise."' AND emp_id_fk = '".$emp_id_pk."'"); 

				if(count($arr) > 0){
					return $arr[0]['gsli'];
				}else{
					$other_deduction_data = $db->fetch_table("SELECT deduction_amount FROM mad_other_deduction
												WHERE other_deduction_type_variable = 'gsli'
												AND status in ('2') AND approval_status in ('3')
												AND emp_id_fk = '".$emp_id_pk."' 
												AND municipal_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'");
				if($other_deduction_data[0]['deduction_amount'] > 0)
				{
					return $other_deduction_data[0]['deduction_amount'];
				}
				else
				{
					return 0;	
				}
			  }
			}
			
			//ARREAR DEDUCTION AMOUNT START 
			if($type=='arrear'){
				$arr = $db->fetch_table("select total_arrear_deduction from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND municipality_id_fk='".$dise."' AND emp_id_fk = '".$emp_id_pk."'"); 

				if(count($arr) > 0){
					return $arr[0]['total_arrear_deduction'];
				}else{
					$arrear_amount = $db->fetch_table("SELECT remaning_amt FROM mad_arrear_calculation
												WHERE emp_id_fk='".$emp_id_pk."' 																												
											    AND status in ('1')
											    AND delete_status in ('1')
											    AND municipality_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'");
												
				if($arrear_amount[0]['remaning_amt'] > 0)
				{
					return $arrear_amount[0]['remaning_amt'];
				}
				else
				{
					return 0;	
				}
			  }
			}
			//ARREAR DEDUCTION AMOUNT END 
		
	// for festival advance by nd on 04072017	
		
		if($type=='festival_loan'){
			//die;
				$arr = $db->fetch_table("select festival_loan from mad_employee_salary_save where status_flag=1 AND delete_status=1  AND empcd='$empcd' AND salary_monthyear='".date('Ym')."' AND municipality_id_fk='".$dise."' AND emp_id_fk = '".$emp_id_pk."'"); 
				if(count($arr) > 0){
					return $arr[0]['festival_loan'];
				}else{
					$other_deduction_data = $db->fetch_table("SELECT festival_advance_installment_amount FROM mad_festival_advance_entry_sal
												WHERE 
												status in ('2') AND approval_status in ('3') AND delete_status in ('1')
												AND emp_id_fk = '".$emp_id_pk."' 
												AND municipal_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."' 
												AND deduction_start_monthyear <= '".date("Ym")."'");
				if($other_deduction_data[0]['festival_advance_installment_amount'] > 0)
				{
					return $other_deduction_data[0]['festival_advance_installment_amount'];
				}
				else
				{
					return 0;	
				}
			  }
			}
		}
	//end of for festival advance by nd on 04072017		
		
		function empPtax($amount){
			$db = new database();
			//$arr = $db->fetch_table("select ptax_amount from mad_ptax_deduction where mn_amount < '$amount' and mx_amount > '$amount' AND ptax_order_id_fk='1'");
			$arr=$db->fetch_table("select mn_amount,mx_amount,ptax_amount from mad_ptax_deduction as mad_ded
									INNER JOIN mad_ptax_order_file as mad_od
									ON mad_od.ptax_orderfile_pk=mad_ded.ptax_order_id_fk
									where mn_amount <= '$amount' and mx_amount >= '$amount'
									and active_status='1'");
			return $arr[0]['ptax_amount'];
		}
		
		function empBonus($emp_id_fk){
			//-------------------------------
			//CHECK FOR BONUS DATA
			//-------------------------------
			$db = new database();
			$emp_bonus_details = $db->fetch_table("SELECT bonus_amount FROM mad_bonus_entry_sal 
													WHERE emp_id_fk = '".$emp_id_fk."' AND monthyear = '".date("Ym")."'
													AND municipal_id_fk = '".substr($_SESSION['user_info']['stake_user'],0,7)."'
													AND status in ('2') AND approval_status in ('3')");
			if($emp_bonus_details[0]['bonus_amount'] > 0)
			{
				return $emp_bonus_details[0]['bonus_amount'];
			}
			else
			{
				return 0;	
			}
		}

		$tch_final = array();
		
			
								$tch_final = $db->fetch_table("
								SELECT
									municipality_id_fk
								FROM
									mad_employee_salary_save
								WHERE 
									(status_flag = 2 OR status_flag = 3 OR status_flag = 4 OR status_flag = 6) AND category_id=1 AND delete_status=1 AND municipality_id_fk = '".$dise."' and salary_monthyear='".date('Ym')."'
								");
	
		//print_r(count($tch_final));
		// start if no data available in salary save table for this municipality
	//if(count($tch_final) == 0)
	if(count($tch_final) == 0 || count($tch_final) > 0)
	{
		$tch = array();

//Function copy employee data For Promotion & Increment Start
function copy_employee_data($emp_id_pk_func, $session_stake_user)
{
	$db = new database();
	$employee_master_function=$db->insert("INSERT INTO  mad_employee_master_archive_delete (emp_id_pk,
												  emp_id_fk,
												  emp_first_name,
												  emp_second_name,
												  emp_last_name ,
												  emp_dob ,
												  emp_sex,
												  emp_caste,
												  emp_voter_id ,
												  emp_aadhar_no ,
												  emp_edu_quali,
												  emp_desig ,
												  emp_first_join_date ,
												  emp_conf_join_date ,
												  emp_join_prsnt_post_date ,
												  emp_join_prsnt_office_date ,
												  emp_retirement_date ,
												  emp_termination_date ,
												  emp_status_deputation,
												  emp_group,
												  emp_desig_first_app,
												  emp_next_increment_date ,
												  emp_next_increment_amount ,
												  emp_cosolidated_pay,
												  emp_pay_band ,
												  emp_pay_in_payband,
												  emp_grade_pay_bk ,
												  emp_pay_scale ,
												  emp_bank_name ,
												  emp_bank_branch ,
												  emp_branch_code ,
												  emp_micr_no,
												  emp_acc_no ,
												  emp_ifsc_no ,
												  emp_father_name ,
												  emp_mother_name ,
												  emp_religion,
												  emp_mother_tongue ,
												  emp_marital_status,
												  emp_spouse_name ,
												  emp_spouse_job_status ,
												  emp_spouse_details ,
												  emp_spouse_pay ,
												  emp_spouse_hra,
												  emp_spouse_res ,
												  emp_spouse_house_schm ,
												  emp_pan_no ,
												  emp_blood_grp ,
												  emp_height,
												  emp_diff_able,
												  emp_disable_status ,
												  emp_idf_mark ,
												  pre_state ,
												  emp_pre_house_no ,
												  emp_pre_street_no ,
												  emp_pre_vill ,
												  emp_pre_post ,
												  emp_pre_pin ,
												  emp_pre_dist,
												  emp_pre_dist_others ,
												  per_state ,
												  emp_per_house_no ,
												  emp_per_street_no ,
												  emp_per_vill ,
												  emp_per_post ,
												  emp_per_pin ,
												  emp_per_dist ,
												  emp_per_dist_others ,
												  emp_land_no,
												  emp_mobile_no,
												  emp_mail_id ,
												  emp_system_code,
												  emp_form_status,
												  emp_status ,
												  entry_time,
												  entry_ip,
												  empcd ,
												  emp_grade_pay ,
												  emp_id_const,
												  bank_upd_status ,
												  bank_msg_flag,
												  conf_dt_flag,
												  delete_reason,
												  delete_stake_user,
												  interim_relief,
												  spouse_medical_allowance,
												  conv_allow_status,
												  emp_pass_year,
												  emp_prof_degree,
												  emp_dcrb_option,
												  emp_dcrb_opt,
												  emp_post_year,
												  emp_treasury_name,
												  emp_case_no,
												  emp_case_info,
												  emp_court_flag,
												  emp_refund_date,
												  emp_amt_refund,
												  emp_case_year,
												  who,
												  created_by,
												  emp_group_now,
												  emp_case_no_2,
												  emp_case_year_2,
												  emp_case_info_2,
												  emp_case_no_3,
												  emp_case_year_3,
												  emp_case_info_3,
												  case_no_count,
												  emp_approval_status,
												  municipality_id_fk,
												  app_order_no_of_dlb,
												  date_of_order,
												  nom_first_name,
												  nom_middle_name,
												  nom_last_name,
												  nom_family_declaration,
												  emp_approval_status_by_dlb,
												  deletion_time,
												  deletion_ip)
												  
												SELECT emp_id_pk ,
												  emp_id_fk,
												  emp_first_name,
												  emp_second_name,
												  emp_last_name ,
												  emp_dob ,
												  emp_sex,
												  emp_caste,
												  emp_voter_id ,
												  emp_aadhar_no ,
												  emp_edu_quali,
												  emp_desig ,
												  emp_first_join_date ,
												  emp_conf_join_date ,
												  emp_join_prsnt_post_date ,
												  emp_join_prsnt_office_date ,
												  emp_retirement_date ,
												  emp_termination_date ,
												  emp_status_deputation,
												  emp_group,
												  emp_desig_first_app,
												  emp_next_increment_date ,
												  emp_next_increment_amount ,
												  emp_cosolidated_pay,
												  emp_pay_band ,
												  emp_pay_in_payband,
												  emp_grade_pay_bk ,
												  emp_pay_scale ,
												  emp_bank_name ,
												  emp_bank_branch ,
												  emp_branch_code ,
												  emp_micr_no,
												  emp_acc_no ,
												  emp_ifsc_no ,
												  emp_father_name ,
												  emp_mother_name ,
												  emp_religion,
												  emp_mother_tongue ,
												  emp_marital_status,
												  emp_spouse_name ,
												  emp_spouse_job_status ,
												  emp_spouse_details ,
												  emp_spouse_pay ,
												  emp_spouse_hra,
												  emp_spouse_res ,
												  emp_spouse_house_schm ,
												  emp_pan_no ,
												  emp_blood_grp ,
												  emp_height,
												  emp_diff_able,
												  emp_disable_status ,
												  emp_idf_mark ,
												  pre_state ,
												  emp_pre_house_no ,
												  emp_pre_street_no ,
												  emp_pre_vill ,
												  emp_pre_post ,
												  emp_pre_pin ,
												  emp_pre_dist,
												  emp_pre_dist_others ,
												  per_state ,
												  emp_per_house_no ,
												  emp_per_street_no ,
												  emp_per_vill ,
												  emp_per_post ,
												  emp_per_pin ,
												  emp_per_dist ,
												  emp_per_dist_others ,
												  emp_land_no,
												  emp_mobile_no,
												  emp_mail_id ,
												  emp_system_code,
												  emp_form_status,
												  emp_status ,
												  entry_time,
												  entry_ip,
												  empcd ,
												  emp_grade_pay ,
												  emp_id_const,
												  bank_upd_status ,
												  bank_msg_flag,
												  conf_dt_flag,
												  'Promotion',
												  '0',
												  interim_relief,
												  spouse_medical_allowance,
												  conv_allow_status,
												  emp_pass_year,
												  emp_prof_degree,
												  emp_dcrb_option,
												  emp_dcrb_opt,
												  emp_post_year,
												  emp_treasury_name,
												  emp_case_no,
												  emp_case_info,
												  emp_court_flag,
												  emp_refund_date,
												  emp_amt_refund,
												  emp_case_year,
												  who,
												  created_by,
												  emp_group_now,
												  emp_case_no_2,
												  emp_case_year_2,
												  emp_case_info_2,
												  emp_case_no_3,
												  emp_case_year_3,
												  emp_case_info_3,
												  case_no_count,
												  emp_approval_status,
												  municipality_id_fk,
												  app_order_no_of_dlb,
												  date_of_order,
												  nom_first_name,
												  nom_middle_name,
												  nom_last_name,
												  nom_family_declaration,
												  emp_approval_status_by_dlb,
												  now(),
												  '".$_SERVER['REMOTE_ADDR']."'
												
												FROM mad_employee_master
												where emp_id_pk='".$emp_id_pk_func."' 
												AND municipality_id_fk='".substr($session_stake_user,0,7)."'");	
		if($employee_master_function)
		{
			return true;	
		}
		else
		{
			return false;	
		}
}
//Function copy employee data For Promotion & Increment End

//Promotion and Increment Implementation 25.10.2017 Start
		$employee_for_promotion_increment = $db->fetch_table("SELECT 
															tch.emp_id_pk,
															tch.emp_pay_in_payband,
															mdgm.grade_amount,
															tch.emp_next_increment_date,
															tch.emp_next_increment_amount,
															tch.promotion_status,
															tch.emp_retirement_date,
															tch.emp_id_const
															FROM
															mad_employee_master as tch
															INNER JOIN mad_dise_gradepay_master as mdgm
															ON tch.emp_grade_pay = mdgm.grade_code
															WHERE
															tch.created_by LIKE'".$createdby."%'
															AND (tch.emp_status='8' OR tch.emp_status='11')");						
		//print_r($employee_for_promotion_increment);
		foreach($employee_for_promotion_increment as $employees_for_promotion_increment)
		{
			//Retaired Employee Profile Deactivation Forcefully Start
			$retairement_date = $employees_for_promotion_increment['emp_retirement_date'];
		    $retairement_date_arr = explode("-", $retairement_date);
		    $retairement_date_year = $retairement_date_arr [0];
		    $retairement_date_month = $retairement_date_arr [1];
		    $retairement_date_year_month = $retairement_date_year.$retairement_date_month; 
		    $current_year_month = date("Y").date("m");
			
			if($retairement_date_year_month < $current_year_month)
			{
				$update_employee_retairement_data = $db->update("UPDATE mad_employee_master 
																SET emp_status = '2',
																retairement_status = '1'
																where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
																AND municipality_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."'");
				
				if($update_employee_retairement_data)
				{
					$stop_salary_insert_reason = $db->insert("
								INSERT INTO
								mad_stop_sal_reason (
								ip_address,
								date,
								stopped_by,
								reason,
								reason_text,
								emp_id_fk,
								municipality_id_fk,
								reason_date,
								emp_id_const_fk
								)
								VALUES (
								'".$_SESSION['user_agent']['USER_IP']."',
								now(),
								'".$_SESSION['user_info']['stake_user']."',
								'1991',
								'Forcefully Stopped For Retairement',
								'".$employees_for_promotion_increment['emp_id_pk']."',
								'".substr($_SESSION['user_info']['stake_user'],0,7)."',
								'0001-01-01',
								'".$employees_for_promotion_increment['emp_id_const']."'
								)");
				}
			}
			//Retaired Employee Profile Deactivation Forcefully End
			
			
			
			
			//echo $employees_for_promotion_increment['emp_id_pk'];
			//For pending increment Start
			$emp_next_increment_date = $employees_for_promotion_increment['emp_next_increment_date'];
			$get_emp_next_increment_mnth_yr = explode("-", $emp_next_increment_date);
			$promotion_status = $employees_for_promotion_increment['promotion_status'];
			
			//print_r($emp_next_increment_mnth_yr);
			$emp_next_increment_mnth_yr = $get_emp_next_increment_mnth_yr[0].$get_emp_next_increment_mnth_yr[1];
			/*$increment_data_pending = $db->fetch_table("SELECT effective_monthyear
												FROM mad_employee_annual_increment_details
												WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
												AND status in('3')
												AND delete_status in('1')
												AND effective_monthyear = '".$emp_next_increment_mnth_yr."'");*/						
			//if((date("Ym") > $emp_next_increment_mnth_yr) && ($promotion_status != 1) && (count($increment_data_pending)))
			if((date("Ym") > $emp_next_increment_mnth_yr) && ($promotion_status != 1))
			{
				$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
				$pay_in_paynband_pending = ($employees_for_promotion_increment['emp_pay_in_payband'] + $employees_for_promotion_increment['emp_next_increment_amount']);
				if($employee_master_archive_delete)
				{
					$update_employee_data = $db->update("UPDATE mad_employee_master 
														SET emp_pay_in_payband = '".$pay_in_paynband_pending."',
														promotion_effective_date = '".date("Y-m-d")."',
														promotion_status = '1'
														where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
														AND municipality_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."'");
														
														//if annual increment and promotion updated together by nd on 01_11_2017
				}
			}
			
			//For pending increment End
			
			//Promotion Start
			$promotion_data = $db->fetch_table("SELECT emp_grade_pay,
												increment_type,
												dop_or_doi,
												effective_date,
												emp_desig,
												emp_pay_scale,
												annual_increment_date,
												emp_pay_band,
												increment_amount,
												pre_emp_pay_in_payband,
												pre_emp_grade_pay,
												emp_pay_in_payband
												FROM mad_employee_promotion_details
												WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
												AND status in('2','3') AND approval_status in('9','11')
												AND approval_status_by_ddo in('4') AND delete_status in('1')");
												
											
			//print_r($promotion_data);
			if(count($promotion_data) > 0)
			{
				$promotion_eff_date = $promotion_data[0]['effective_date'];
				$get_promotion_eff_month_year = explode("-", $promotion_eff_date);
				$promotion_eff_yearmonth = $get_promotion_eff_month_year[0].$get_promotion_eff_month_year[1];
				if((date("Ym") >= $promotion_eff_yearmonth))    //Date of promotion
				{	
					if($promotion_data[0]['increment_type'] == 1)
					{
						$sql_designation_update = ", emp_desig = '".$promotion_data[0]['emp_desig']."'";
						
					}
					else if($promotion_data[0]['increment_type'] == 2)
					{
						$sql_designation_update = "";
					}
																												
					if(($promotion_data[0]['dop_or_doi'] == 4) && ($promotion_data[0]['effective_date'] == $promotion_data[0]['annual_increment_date']))
					{
						$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
						//$promotional_grade_pay = $promotion_data[0]['emp_grade_pay'];
						//$emp_pre_basic = $employees_for_promotion_increment['emp_pay_in_payband'] + $employees_for_promotion_increment['grade_amount'];
						
						//$promotional_increment = ($emp_pre_basic + (($emp_pre_basic*3)/100));     //confirm again
						//$promotional_increment = ($employees_for_promotion_increment['emp_pay_in_payband'] + (($emp_pre_basic*3)/100));
						//$round_promotional_increment = round(($promotional_increment/10))*10;
						
						$round_promotional_increment = $promotion_data[0]['emp_pay_in_payband'];
					
						if($employee_master_archive_delete)
						{
							$update_employee_data = $db->update("UPDATE mad_employee_master 
																SET emp_pay_in_payband = '".$round_promotional_increment."',
																emp_pay_scale = '".$promotion_data[0]['emp_pay_scale']."',
																emp_pay_band = '".$promotion_data[0]['emp_pay_band']."',
																emp_grade_pay = '".$promotion_data[0]['emp_grade_pay']."'
																".$sql_designation_update.",
																promotion_effective_date = '".date("Y-m-d")."'
																where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
																AND municipality_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."'");
							if($update_employee_data)		
							{
								$inactive_promotion_data = $db->update("UPDATE mad_employee_promotion_details
															SET delete_status='2'
															WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
															AND status in('2','3') AND approval_status in('9','11')
															AND approval_status_by_ddo in('4') AND delete_status in('1')");
							}
						}
					}
					else if(($promotion_data[0]['dop_or_doi'] == 3) && ($promotion_data[0]['annual_increment_date'] != $promotion_data[0]['effective_date']))
					{
						$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
						if($employee_master_archive_delete)
						{
							$update_employee_data = $db->update("UPDATE mad_employee_master 
																SET emp_grade_pay = '".$promotion_data[0]['emp_grade_pay']."'
																".$sql_designation_update.",
																promotion_effective_date = '".date("Y-m-d")."'
																where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
																AND municipality_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."'");
							if($update_employee_data)		
							{
								$inactive_promotion_data = $db->update("UPDATE mad_employee_promotion_details
															SET delete_status='3'
															WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
															AND status in('2','3') AND approval_status in('9','11')
															AND approval_status_by_ddo in('4') AND delete_status in('1')");
							}
						}
					}
					else if(($promotion_data[0]['dop_or_doi'] == 5) && ($promotion_data[0]['annual_increment_date'] != $promotion_data[0]['effective_date']))
					{
						$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
						//$emp_pre_basic = $employees_for_promotion_increment['emp_pay_in_payband'] + $employees_for_promotion_increment['grade_amount'];
						//$promotional_increment = ($emp_pre_basic + (($emp_pre_basic*3)/100));     //confirm again
						//$promotional_increment = ($employees_for_promotion_increment['emp_pay_in_payband'] + (($emp_pre_basic*3)/100));
						//$round_promotional_increment = round(($promotional_increment/10))*10;
						 $round_promotional_increment = $promotion_data[0]['emp_pay_in_payband'];
					
						if($employee_master_archive_delete)
						{
							$update_employee_data = $db->update("UPDATE mad_employee_master 
																SET emp_pay_in_payband = '".$round_promotional_increment."',
																emp_pay_scale = '".$promotion_data[0]['emp_pay_scale']."',
																emp_pay_band = '".$promotion_data[0]['emp_pay_band']."',
																emp_grade_pay = '".$promotion_data[0]['emp_grade_pay']."'
																".$sql_designation_update.",
																promotion_effective_date = '".date("Y-m-d")."'
																where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
																AND municipality_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."'");
										
							if($update_employee_data)		
							{
								$inactive_promotion_data = $db->update("UPDATE mad_employee_promotion_details
															SET delete_status='2'
															WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
															AND status in('2','3') AND approval_status in('9','11')
															AND approval_status_by_ddo in('4') AND delete_status in('1')");
							}
						}
					}
				}
			}
			//Promotion End
			
			
			//Increment Start
			$increment_data = $db->fetch_table("SELECT effective_monthyear
												FROM mad_employee_annual_increment_details
												WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
												AND status in('3')
												AND delete_status in('1')");
			//print_r($increment_data);
			if(count($increment_data) > 0) 
			{														
				if($increment_data[0]['effective_monthyear'] <= date("Ym"))
				{
					$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
					// have to check
					$emp_pre_basic = $employees_for_promotion_increment['emp_pay_in_payband'] + $employees_for_promotion_increment['grade_amount'];
					//$promotional_increment = ($emp_pre_basic + (($emp_pre_basic*3)/100));     //confirm again
					$increment_payband = ($employees_for_promotion_increment['emp_pay_in_payband'] + (($emp_pre_basic*3)/100));
					$round_increment_payband = round(($increment_payband/10))*10;
				
				
					if($employee_master_archive_delete)
					{
						$update_employee_data = $db->update("UPDATE mad_employee_master 
															SET emp_pay_in_payband = '".$round_increment_payband."',
															promotion_effective_date = '".date("Y-m-d")."'
															where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
															AND municipality_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."'");
						if($update_employee_data)		
						{
							$inactive_increment_data = $db->update("UPDATE mad_employee_annual_increment_details
														SET delete_status='2'
														WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
														AND status in('3')
														AND delete_status in('1')");
						}
					}
				}
			}
			//Increment End
			
			//Increments for DOI start
			$promotion_increment_data = $db->fetch_table("SELECT 
												dop_or_doi,
												effective_date,
												annual_increment_date,
												increment_amount
												FROM mad_employee_promotion_details
												WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
												AND status in('2','3') AND approval_status in('9','11')
												AND approval_status_by_ddo in('4') AND delete_status in('3')
												AND dop_or_doi in('3')");
			if(count($promotion_increment_data) > 0)
			{
				$get_pro_inc_eff_month_year	= explode("-", $promotion_increment_data[0]['annual_increment_date']);
				$pro_inc_eff_month_year = $get_pro_inc_eff_month_year[0].$get_pro_inc_eff_month_year[1];
				//if(date("Ym") == $pro_inc_eff_month_year)
				if(date("Ym") >= $pro_inc_eff_month_year)
				{
					//$emp_pre_basic = $employees_for_promotion_increment['emp_pay_in_payband'] + $employees_for_promotion_increment['grade_amount'];
					//$promotional_increment = ($emp_pre_basic + (($emp_pre_basic*3)/100));     //confirm again
					//$promotional_increment1 = ($employees_for_promotion_increment['emp_pay_in_payband'] + (($emp_pre_basic*3)/100));
					//$emp_pre_basic2 = $promotional_increment1 + $employees_for_promotion_increment['grade_amount'];
					//$round_promotional_increment = round(($promotional_increment/10))*10;
					//$promotional_increment2 = ($promotional_increment1 + (($emp_pre_basic2*3)/100));
					//$round_promotional_increment2 = round(($promotional_increment2/10))*10;
					$round_promotional_increment2 = $promotion_increment_data[0]['increment_amount'];
					
					$employee_master_archive_delete = copy_employee_data($employees_for_promotion_increment['emp_id_pk'], $_SESSION['user_info']['stake_user']);
					if($employee_master_archive_delete)
					{
						$update_employee_data = $db->update("UPDATE mad_employee_master 
															SET emp_pay_in_payband = '".$round_promotional_increment2."',
															promotion_effective_date = '".date("Y-m-d")."'
															where emp_id_pk='".$employees_for_promotion_increment['emp_id_pk']."' 
															AND municipality_id_fk='".substr($_SESSION['user_info']['stake_user'],0,7)."'");
						if($update_employee_data)		
						{
							$inactive_promotion_data = $db->update("UPDATE mad_employee_promotion_details
														SET delete_status='2'
														WHERE emp_id_fk ='".$employees_for_promotion_increment['emp_id_pk']."'
														AND status in('2','3') AND approval_status in('9','11')
														AND approval_status_by_ddo in('4') AND delete_status in('3')
														AND dop_or_doi in('3')");
						}
					}
				}
			}
			//Increments for DOI End
			
		}
											
//Promotion and Increment Implementation 25.10.2017 End
		if(count($tch_final) == 0)
		{
		
								$tch= $db->fetch_table("
								SELECT 
										tch.emp_id_pk,
										tch.emp_first_name,
										tch.emp_second_name,
										tch.emp_last_name,
										tch.emp_system_code,
										tch.emp_pay_in_payband,
										tch.emp_grade_pay,
										tch.emp_pay_band,
										tch.emp_spouse_hra,
										tch.emp_diff_able,
										tch.emp_spouse_res,
										tch.emp_bank_name,
										tch.emp_acc_no,
										tch.emp_ifsc_no,
										tch.emp_id_fk,
										tch.empcd,
										emp_retirement_date,
										tch.emp_desig,
										tch.emp_cosolidated_pay,
										tch.emp_id_fk,
										tch.emp_diff_able,
										tch.emp_first_join_date,
										tch.interim_relief,
										tch.spouse_medical_allowance,
										tch.conv_allow_status,
										tch.municipality_id_fk
										
									FROM
										mad_employee_master as tch
									WHERE
											tch.created_by LIKE'".$createdby."%'
											AND (tch.emp_status='8' OR tch.emp_status='11')
											
		
						");
		}
		else if(count($tch_final) > 0)
		{
								$tch= $db->fetch_table("
								SELECT 
										tch.emp_id_pk,
										tch.emp_first_name,
										tch.emp_second_name,
										tch.emp_last_name,
										tch.emp_system_code,
										tch.emp_pay_in_payband,
										tch.emp_grade_pay,
										tch.emp_pay_band,
										tch.emp_spouse_hra,
										tch.emp_diff_able,
										tch.emp_spouse_res,
										tch.emp_bank_name,
										tch.emp_acc_no,
										tch.emp_ifsc_no,
										tch.emp_id_fk,
										tch.empcd,
										emp_retirement_date,
										tch.emp_desig,
										tch.emp_cosolidated_pay,
										tch.emp_id_fk,
										tch.emp_diff_able,
										tch.emp_first_join_date,
										tch.interim_relief,
										tch.spouse_medical_allowance,
										tch.conv_allow_status,
										tch.municipality_id_fk
										
									FROM
										mad_employee_master as tch
										INNER JOIN mad_employee_salary_save as mess
										ON tch.emp_id_pk = mess.emp_id_fk
									WHERE
											tch.created_by LIKE'".$createdby."%'
											AND (tch.emp_status='8' OR tch.emp_status='11')
											AND status_flag in ('1','7')
											AND is_saved in ('1','0')
											AND delete_status in('1')");
			
		}
		

		//echo count($tch);
//further check if no data available in salary save table for this municipality
		$sal_saved = $db->fetch_table("SELECT save.status_flag,save.empcd,mad_emp.emp_desig,save.emp_id_fk,
											save.is_saved FROM mad_employee_salary_save 
											as save 
											INNER JOIN mad_employee_master as mad_emp
											ON save.emp_id_fk=mad_emp.emp_id_pk
											WHERE
												(save.status_flag = 1 OR save.status_flag = 5 OR save.status_flag = 7)AND save.delete_status=1 AND save.category_id=1 
												AND save.is_saved=1
												AND save.municipality_id_fk = '".$dise."' AND (mad_emp.emp_status=8 OR mad_emp.emp_status =11)
												AND salary_monthyear='".date('Ym')."'
										");
										
		/*$sal_saved = $db->fetch_table("SELECT save.status_flag,save.empcd,mad_emp.emp_desig,save.emp_id_fk,
											save.is_saved FROM mad_employee_salary_save 
											as save 
											INNER JOIN mad_employee_master as mad_emp
											ON save.emp_id_fk=mad_emp.emp_id_pk
											WHERE
												save.delete_status=1 AND save.category_id=1 
												
												AND save.municipality_id_fk = '".$dise."' AND (mad_emp.emp_status=8 OR mad_emp.emp_status =11)
												AND salary_monthyear='".date('Ym')."'
										");*/
										
		//For Finalize buton Start								
		$sal_saved_locked_or_finalized = $db->fetch_table("SELECT emp_id_fk
											FROM mad_employee_salary_save 
											WHERE
												(status_flag = 3 OR status_flag = 4) AND delete_status=1 AND category_id=1 
												AND is_saved=1
												AND municipality_id_fk = '".$dise."' 
												AND salary_monthyear='".date('Ym')."'
										");
		//For Finalize buton End
		
		//For ajax Finalize buton Start
		$sal_saved_removed = $db->fetch_table("SELECT emp_id_fk
											FROM mad_employee_salary_save 
											WHERE
												(status_flag = 7) AND delete_status=1 AND category_id=1 
												AND municipality_id_fk = '".$dise."' 
												AND salary_monthyear='".date('Ym')."'
										");
										
		$tch_removed = $db->fetch_table("SELECT emp_id_fk
											FROM mad_employee_salary_save 
											WHERE
												(status_flag = 1) AND delete_status=1 AND category_id=1 
												AND municipality_id_fk = '".$dise."' 
												AND salary_monthyear='".date('Ym')."'
										");
		//For ajax Finalize buton End
								

		$tch_count = count($sal_saved);
		
		if(count($tch))
			{ // For getting percentage of DA, HRA and other allowences
				$paychange = $db->fetch_table("
							SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
       entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
       paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
							FROM mad_admin_paychange
							WHERE flag = 'TRUE';
						");
						
							
					if(count($paychange)>0){			
					$da_per = $paychange[0]['paychange_da'];
					$max_ma = $paychange[0]['paychange_ma'];
					 $hra_per = $paychange[0]['paychange_hra'];
					$cpf_per = $paychange[0]['paychange_cpf'];
					$conveyance_allowance_max = $paychange[0]['conveyance_allowance'];
					$hill_allowance_per = $paychange[0]['hill_allowance'];
					}
					else{
					$da_per = 0;
					$max_ma = 0;
					$hra_per = 0;
					$cpf_per = 0;
					$conveyance_allowance_max = 0;
					$hill_allowance_per = 0;
						
					}
			}

	//
	//echo "<pre>";
	//print_r($sal_saved);
	//echo "</pre>";
?>
<style>
	#wait{
		display: none;
	}
	.headRow > div{
		 text-align:center !important;
	}
	#divcol > div{
		text-align:center !important;
	}
</style>

 
<?php 
				function getEmpAmount($type,$dise,$emp_id_pk){
				$db = new database();
				if($type == 'pay_in_pay_band')
				{
				$arr = $db->fetch_table("select emp_pay_in_payband from mad_employee_master where emp_id_pk='".$emp_id_pk."'");
				
					if($arr[0]['emp_pay_in_payband'])
					{
						return $arr[0]['emp_pay_in_payband'];
					}else{
						return 0;
					}
				}

				if($type == 'grade_pay')
				{
				$arr = $db->fetch_table("select emp_grade_pay,grade_amount from mad_employee_master as emp
				INNER JOIN mad_dise_gradepay_master as gd 
				ON trim(emp.emp_grade_pay)=gd.grade_code
				where emp_id_pk='".$emp_id_pk."'");
				
					if($arr[0]['grade_amount'])
					{
						 $arr[0]['grade_amount']; 
						return $arr[0]['grade_amount'];
					}else{
						return 0;
					}
				}
				

}  
// End checking if no data available in salary save table for this municipality

	   	   
	   ?>

<table width="100%">

            <tr>
            <th></th>
            <th colspan="9"><strong>PAY & ALLOWANCE</strong></th>
            
            <th colspan="1"></th>
            <!--<th></th>-->
            <th colspan="11"><strong>DEDUCTION</strong></th>
            <th></th>
            <th colspan="2"><strong>ACTION</strong></th>
            </tr>
            <tr>
            <th style="width: 5%;">SL NO.</th>
            <th>EMPLOYEE NAME</th>
            <!--<th>CONSOLIDATED PAY</th>-->
            <th>PAY IN PAY BAND</th>
            <th>GRADE PAY</th>
            <th>D.A.(<?php echo $da_per; ?>%)</th>
            
            <th>H.R.A (<?php echo $hra_per; ?>%)</th>
            <th>M.A</th>
            <!--<th>C.P.F</th>-->
            <th>SPCL<br>ALLOW<br>(PWD EMP)</th>
             <!--<th>HIll AllOW<span  style="font-size:12px;">(min 15%)</span></th>-->
             <th>INTERIM RELIEF</th>
             <th>BONUS</th>
            <th>GROSS SALARY</th>
            <th>PF CONTRIBUTION<br><span  style="font-size:12px;"><!--(min 6%)--></span></th>
            
            <th>SALARY SAVINGS LIC</th>
            <th>STAFF COOPERATIVE LIC</th>
            <th>STAFF COOPERATIVE CONTRIBUTION</th>
            <!--<th>CPF</th>-->
            <th>P.TAX</th>
            <th>TDS</th>
            <th>GSLI</th>
            <th>OVERDRAWN</th>
           <!-- <th>Co-operative Loan Recovery</th>
             <th>HBL Recovery</th>-->
            <th>FESTIVAL ADVANCE RECOVERY</th>
            <th>TOTAL ARREAR DEDUCTION</th>
           <!-- <th>Other Deduction</th>-->
            <th width="10%">TOTAL LOAN DEDUCTION</th>
            <th>NET SALARY</th>
            <th>EDIT</th>
            <th>SAVE</th>
            </tr>

			<?php
			 
			if(count($tch)){
				$paychange = $db->fetch_table("
							SELECT paychange_id_pk, paychange_ip, paychange_fromdate, paychange_todate, 
       entrydate, paychange_da, paychange_hra, paychange_ma, paychange_cpf, 
       paychange_ptax, paychange_pdf, flag, order_file_name,conveyance_allowance,hill_allowance
							FROM mad_admin_paychange
							WHERE flag = 'TRUE';
						");

						
				if(count($paychange)>0)
				{			
				$da_per = $paychange[0]['paychange_da'];
				$max_ma = $paychange[0]['paychange_ma'];
				$hra_per = $paychange[0]['paychange_hra'];
				$cpf_per = $paychange[0]['paychange_cpf'];
				$conveyance_allowance_max = $paychange[0]['conveyance_allowance'];
				$hill_allowance_per = $paychange[0]['hill_allowance'];
				}
				else
				{
				$da_per = 0;
				$max_ma = 0;
				$hra_per = 0;
				$cpf_per = 0;
				$conveyance_allowance_max = 0;
				$hill_allowance_per = 0;
					
				}
			?>

            <?php $count = 1; 
			foreach ($tch as $key) {
				
				//print_r($key);
				$empcd = $key['empcd'];
				$emp_id_pk=$key['emp_id_pk'];
				
				$sal_save_status_flag = $db->fetch_table("SELECT save.status_flag
											FROM mad_employee_salary_save as save 
											WHERE
												save.status_flag = 7 AND save.delete_status=1 
												AND save.municipality_id_fk = '".$dise."'
												AND salary_monthyear='".date('Ym')."'
												AND emp_id_fk = '".$emp_id_pk."'
										");
				
				
				$emp_bonus = empBonus($emp_id_pk);
				
				$sal_chk = $db->fetch_table("select slno, latestupdate_time, latestupdate_ip_address, emp_id_fk, empcd, 
       bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
       category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
       hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
       overdrawn, salary_type, cause,gp_code, part_day, gsli,consolidated_pay,other_deduction,festival_loan,festival_loan_cause,interim_relief from mad_employee_salary_save where (status_flag=1 OR status_flag=2 OR status_flag=3 OR status_flag=4 OR status_flag=7)  AND delete_status=1 AND salary_monthyear='".date('Ym')."'  AND emp_id_fk='".$key['emp_id_pk']."'");
	   
	   /*$sal_chk = $db->fetch_table("select slno, latestupdate_time, latestupdate_ip_address, emp_id_fk, empcd, 
       bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
       i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
       emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
       category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
       hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
       overdrawn, salary_type, cause,gp_code, part_day, gsli,consolidated_pay,other_deduction,festival_loan,festival_loan_cause,interim_relief from mad_employee_salary_save where (status_flag=2 OR status_flag=3 OR status_flag=4 OR status_flag=7)  AND delete_status=1 AND salary_monthyear='".date('Ym')."'  AND emp_id_fk='".$key['emp_id_pk']."'");*/

//For annual increment and promotion

$annual_increment = $db->fetch_table("select * from mad_employee_annual_increment_details where emp_id_fk='".$key['emp_id_pk']."'"); 
 $annual_increment_date = $annual_increment['effective_monthyear'];	
//echo "select * from mad_employee_annual_increment_details where AND emp_id_fk='".$key['emp_id_pk']."'";	
 $current_month_year = date(Y).date(m);
 
 
 // by nd on 01_11_2017
 
/* $promotion_data_cal = $db->fetch_table("SELECT emp_id_fk,
 												emp_grade_pay,
												increment_type,
												dop_or_doi,
												effective_date,
												emp_desig,
												emp_pay_scale,
												annual_increment_date,
												emp_pay_band,
												emp_pay_in_payband,
												increment_amount,
												pre_emp_pay_in_payband,
												pre_emp_grade_pay
												FROM mad_employee_promotion_details
												WHERE emp_id_fk ='".$key['emp_id_pk']."'
												AND delete_status in('2') AND promotion_effective_status in('1')");
												
  $emp_id_for_promotion_part = 	$promotion_data_cal[0]['emp_id_fk'];									
  $pre_emp_pay_band_for_promotion_cal = $promotion_data_cal[0]['pre_emp_pay_in_payband'];
  $pre_emp_grade_pay_for_promotion_cal = (func_gradepay($promotion_data_cal[0]['pre_emp_grade_pay']));
  $emp_effective_date_for_promotion_cal =  $promotion_data_cal[0]['effective_date'];
  $emp_pay_band_for_promotion_cal =  $promotion_data_cal[0]['emp_pay_in_payband'];
  $emp_grade_pay_for_promotion_cal =  (func_gradepay($promotion_data_cal[0]['emp_grade_pay']));
   $emp_effective_day_for_promotion_cal = substr ($emp_effective_date_for_promotion_cal, -2);
  $max_days_of_current_month = date(t);*/
  
 // end of by nd on 01_11_2017
//die;

//die;	
	
//End of For annual increment and promotion	
	
	 
	   //die('1234');

	        //---------- Start Gpf=0 before retirement--------------------
			 $retirement_date=$key['emp_retirement_date'];
			// $retirement_date_str=(explode ('-',$retirement_date)); 
		    // $retirement_date_str_final=$retirement_date_str[0].$retirement_date_str[1]; 
			//$current_date=date("Ym");
			// $date=date("Ym", strtotime("+2 months"));
			  $date=date('Y-m-d', strtotime('-6 month',strtotime($retirement_date))); 	
					 $emp_first_join_date=$key['emp_first_join_date'];
			 $emp_first_join_match_date=date('Y-m-30', strtotime('+12 month',strtotime($emp_first_join_date)));
			//----------- End Gpf=0 before retirement ---------------------
			
				if($sal_chk[0]['emp_id_fk'] && $sal_chk[0]['empcd'])
				{ 
				
				
					if($key['emp_desig']=='999999'){						//1120 -> 999999 Final Check 121217
						$consolidated_pay=$sal_chk[0]['consolidated_pay'];	
						$pay_in_band = 0;
						$grade_pay = 0;
						$basic = 0;
						$da = 0;
						$hra = 0;
						$ma = 0;
						$interim_relief=0;
						$conveyance_allowance = 0;
						$hill_allowance = 0;
						$cpf = 0;
						$gross_salary = $sal_chk[0]['gross_salary'];
						$gpf = 0;
						$scc = 0;
						$cpf_deduct = 0;
						$ptax = $sal_chk[0]['p_tax'];
						$itax = 0;
						$gsli =0;
						$ssl = 0;
						$scl = 0;
						
						/*$operative_loan=0;
						$hbl_loan=0;
						$festival_loan=0;*/
						//$cooperative_loan= $sal_chk[0]['cooperative_loan']; 
						//$hbl_loan= $sal_chk[0]['hbl_loan']; 
						$festival_loan= 0; 
						//$festival_loan= $sal_chk[0]['festival_loan']; 
						$overdrawn = $sal_chk[0]['overdrawn'];
						//$other_deduction= $sal_chk[0]['$other_deduction']; 
						 $net_salary = $sal_chk[0]['net'];
					}
					else
					{ 
					// for suspend salary
				 //echo $sal_chk[0]['salary_type'];
					   if($sal_chk[0]['salary_type']=='1')
					   {
						
							 // echo "nirupam"; 
						$emp_suspend=$db->fetch_table("
						SELECT 
								sus.slno,
								sus.suspend_effect_date,
								sus.suspend_withdrawn_date,
								sus.pencentage_basic,
								sus.suspend_start_date
								
							FROM
								mad_suspend_dts as sus
								INNER JOIN mad_employee_master   em1 on sus.emp_id_fk=em1.emp_id_pk
							WHERE
									 sus.emp_id_fk='$emp_id_pk' and em1.emp_status='11' ORDER BY slno DESC
					  ");
					 
					  
					        $suspend_effect_date=$emp_suspend[0]['suspend_effect_date']; 
					 			
								 if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
								  {//echo "nirupam"; 
									  
									  $pencentage_basic=$emp_suspend[0]['pencentage_basic']; 
									  //07_11_2017 To Fetch From Save Table for promotion
									  //$full_pay_in_band_sus =getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
									  //$full_pay_band = ($full_pay_in_band_sus*$pencentage_basic)/100;
									  //$full_grade_pay_sus = getEmpAmount('grade_pay',$dise,$emp_id_pk);
									  //$full_grade_pay=($full_grade_pay_sus*$pencentage_basic)/100;
									  //07_11_2017 To Fetch From Save Table for promotion
									  
									  $full_pay_in_band_sus = $sal_chk[0]['pay_payband'];
									  $full_pay_band = $sal_chk[0]['pay_payband'];
									  $full_grade_pay_sus = $sal_chk[0]['tch_grade_pay'];
									  $full_grade_pay= $sal_chk[0]['tch_grade_pay'];
									  
									  $full_basic = $full_pay_in_band_sus+$full_grade_pay_sus;
									  $full_basic_sus = $full_pay_band+$full_grade_pay;
									  $full_basic = $full_basic_sus;
									  $full_da_sus = ($full_basic/100)*$da_per;
									  $full_da = ($full_basic_sus/100)*$da_per;
									  $full_interim_relief =0;
									  
								  
								  }
								  else
								  {
									
									 //$full_pay_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);   //07_11_2017 To Fetch From Save Table for promotion
									 $full_pay_band = $sal_chk[0]['pay_payband'];
									 //$full_pay_band = getEmpAmount('pay_in_pay_band',$dise,$emp_id_pk);
									 //$full_grade_pay = getEmpAmount('grade_pay',$dise,$emp_id_pk);		//07_11_2017 To Fetch From Save Table for promotion
									 $full_grade_pay = $sal_chk[0]['tch_grade_pay'];
									 $full_basic = $full_pay_band+$full_grade_pay;
									 $full_da = ($full_basic/100)*$da_per;
									 $full_interim_relief = $key['interim_relief'];
								
								  }

					 $consolidated_pay=0;
								  if($key['emp_spouse_res']=='251')
								  {
										$full_hra = 0; 		//If obtain any govt. housing scheme.
								  }
								  else
								  {
									if($key['emp_spouse_hra']=='0' || $key['emp_spouse_hra']=='' || !$key['emp_spouse_hra'])
									{
										$hra_emp = ($full_basic/100)*$hra_per;
										if($hra_emp > 6000)
										{
											$full_hra = 6000;
										}
										else
										{
											$full_hra = round($hra_emp);
										}
									}
								    else if($key['emp_spouse_hra'] >= 6000)
								    {
									//echo "HRA";
									  $full_hra = 0;
									}
									else if($key['emp_spouse_hra'] < 6000)
									{ //spouse HRA < 6000
									$hra_emp = ($full_basic/100)*$hra_per;
									//echo $hra_emp;
										if($hra_emp >= 6000)
										{
											$valid_hra  = (6000-$key['emp_spouse_hra']);
											$full_hra = round($valid_hra);
										}
										else
										{
											$mix_hra = $hra_emp+$key['emp_spouse_hra'];
											if($mix_hra > 6000)
											{
									
												if($key['emp_spouse_hra'] > $hra_emp)
												{
												$valid_hra = 6000-$key['emp_spouse_hra'];
											
													if($valid_hra>$hra_emp)
													{
														$valid_hra = $hra_emp;
													}
												$full_hra = round($valid_hra);
												}
											else if($key['emp_spouse_hra'] <= $hra_emp)
											{
											//$valid_hra = $hra_emp-$tch[0]['spouse_hra'];
											$valid_hra = 6000-$key['emp_spouse_hra'];
												if($valid_hra>$hra_emp)
												{
													$valid_hra=$hra_emp;
												}
											$full_hra = round($valid_hra);
											}
									
										}
									   else
									   {
									 $full_hra = round($hra_emp);
								}
							}
						}
					}

					//$full_hra=$hra;
				
					if($key['spouse_medical_allowance']=='1')
					{
						
						$full_ma = 0;
					}
					else
					{
						$full_ma = $max_ma;
					}
					
					
				if($key['emp_diff_able']=='1')
				{
					
					// Comment by ND on 16_10_2017_for fonal_salary_code_edit 
					
					//if($key['conv_allow_status']==1)
					//{
					//07_11_2017 To Fetch From Save Table for promotion
					 /*$cal_ma=round(($full_basic*5)/100); 
						if($cal_ma>=400)
						{
						
						$full_conveyance_allowance = $conveyance_allowance_max;
						}
						else
						{
						 $full_conveyance_allowance=round($cal_ma);
						}*/
					//07_11_2017 To Fetch From Save Table for promotion
					//}
					//else
					//{
					//	$full_conveyance_allowance = 0;
					//}
					
					 // End of Comment by ND on 16_10_2017_for fonal_salary_code_edit
			
					$full_conveyance_allowance = $sal_chk[0]['conv_allow'];
				}
				else
				{
					$full_conveyance_allowance = 0;
				}	
					/*if($key['emp_diff_able']=='1'){
						
					 $conveyance_allowance = $conveyance_allowance_max; 
				    }else{
					$conveyance_allowance = 0;
				    }*/
					/*if($tch[0]['hill_allowance']==1){
						
						$hill_allowance_amt = ($full_basic/100)*$hill_allowance_per;
						
						if($hill_allowance_amt > 1500){
							$full_hill_allowance = 1500;
						}else{
							$full_hill_allowance = round($hill_allowance_amt);
						}
					}else{
						$full_hill_allowance = 0;
					}*/
					/*if($state10=='3219'){ // used for hill area dont need by nirupam 23_03_2017
						$hill_p = ($full_pay_band/100)*$hill_allowance_per;
						$hill_g = ($full_grade_pay/100)*$hill_allowance_per;
						$hill_allowance_amt = $hill_p+$hill_g;
						if($hill_allowance_amt > 1500){
							 $full_hill_allowance = 1500;
						}else{
						 $full_hill_allowance = $hill_allowance_amt;
						}
					}else{
						 $full_hill_allowance = 0;
					}*/
					$pay_in_band =$full_pay_band;
					$grade_pay =$full_grade_pay;
					$basic=$full_basic;
					$interim_relief=$full_interim_relief;
					$hill_allowance = $full_hill_allowance; 
					$da=$full_da;
					$hra=$full_hra;
					$ma=$full_ma;
				    $conveyance_allowance=$full_conveyance_allowance;
					//$hill_allowance = $sal_chk[0]['hill_allowance'];
					//added bonus amount.
					 $full_gross_salary =round($pay_in_band+$grade_pay+$da+$interim_relief+$hill_allowance+$hra+$ma+$conveyance_allowance+$emp_bonus);
					
				//Final Check 121217 Start
				/*if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
				{
					$full_gpf=0;
				}	
				else if(strtotime($date)<=strtotime(date('Y-m-d')) && strtotime($date)>0)
			    {
				      $full_gpf=0;
			    }
			    else
			    {
			     	if($sal_chk[0]['gpf']!='')
				 	{
					       $full_gpf = $sal_chk[0]['gpf']; 
						  // $full_gpf = getAmount($dise,$empcd,'gpf',$full_basic);
					}
					 else
					{
					  $full_gpf = getAmount($dise,$empcd,'gpf',$full_basic,$emp_id_pk);
					}
			     }*/
				 
				 //Final Check 121217 End
				 
					//$full_gpf = getAmount($dise,$empcd,'gpf',$full_basic); 
					/* if($sal_chk[0]['gpf']!='')
					 {
					 $full_gpf = $sal_chk[0]['gpf']; 
					 }
					else
					{
						 $full_gpf = getAmount($dise,$empcd,'gpf',$full_basic); 
					
					}*/
					
					
					 if($key['emp_diff_able']=='1')
					 {
						// $full_ptax = empPtax($full_gross_salary);
					    $full_ptax = 0;
				     }
					 else
					 {
						 if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
						 {
							//added bonus amount.
							//calculation ptax for suspend salary
							  $full_gross_salary_sus =round($pay_in_band+$grade_pay+$da+$interim_relief+$hill_allowance+$hra+$ma+$conveyance_allowance+$emp_bonus);
							//  closed for wrong ptax for suspend salary
							 // $full_gross_salary_sus =round($full_pay_in_band_sus+$full_grade_pay_sus+$full_da_sus+$interim_relief+$hra+$ma+$conveyance_allowance+$emp_bonus);
					
					     $full_ptax = empPtax($full_gross_salary_sus);
					     }
					  	 else
						 {
						  $full_ptax = empPtax($full_gross_salary);
					     }
					
					//$full_ptax = 0;
					}
					
					if($sal_chk[0]['i_tax'])
					{
					$full_itax=$sal_chk[0]['i_tax'].'';
					}
					else{
					 $full_itax = getAmount($dise,$empcd,'itax','',$emp_id_pk).'';
					//$full_itax = $sal_chk[0]['i_tax'];
					}

					//$gpf=$full_gpf;
					
					$gpf = getAmount($dise,$empcd,'gpf','',$emp_id_pk); //changed Rupesh
					$full_gpf =	$gpf; //Final Check 121217 For full_gpf variable
					 
					$ptax=$full_ptax;
					//$itax = $sal_chk[0]['i_tax'];
					$gross_salary=$full_gross_salary;
					$itax=$full_itax;
					
					//$gsli = $sal_chk[0]['gsli'];
					//$pfl = $sal_chk[0]['pf_loan'];
					$scc = getAmount($dise,$empcd,'scc','',$emp_id_pk); //changed Rupesh
					$gsli = getAmount($dise,$empcd,'gsli','',$emp_id_pk);  //changed Rupesh
					$ssl = getAmount($dise,$empcd,'ssl','',$emp_id_pk);  //Added Rupesh
					$scl = getAmount($dise,$empcd,'scl','',$emp_id_pk);  //Added Rupesh
					$arrear_amount = getAmount($dise,$empcd,'arrear','',$emp_id_pk);  //Added Rupesh
					
					$overdrawn = $sal_chk[0]['overdrawn'];
					//$cooperative_loan = $sal_chk[0]['cooperative_loan'];
				   // $hbl_loan = $sal_chk[0]['hbl_loan'];
				    $festival_loan = getAmount($dise,$empcd,'festival_loan','',$emp_id_pk);  //changed Rupesh
				   // $festival_loan = $sal_chk[0]['festival_loan']; 
					
					//$total_deduct = $gpf+$pfl+$ptax+$itax+$gsli+$overdrawn+$cooperative_loan+$hbl_loan+$festival_loan;
					//$full_net_salary = $full_gross_salary-$total_deduct;
					
					 $total_deduct = $gpf+$scc+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$ssl+$scl+$arrear_amount;
				     $full_net_salary = $full_gross_salary-$total_deduct;
					 $net_salary=$full_net_salary;
					 
				} // end of if salary_type==1
				
				if($sal_chk[0]['salary_type']=='2'){
			
			    $pay_in_band = $sal_chk[0]['pay_payband'];
				$consolidated_pay=0;
				$grade_pay = $sal_chk[0]['tch_grade_pay'];
				$basic = $sal_chk[0]['basic'];
				$da = $sal_chk[0]['da'];
				$hra = $sal_chk[0]['hra'];
				$interim_relief = $sal_chk[0]['interim_relief'];
				$ma = $sal_chk[0]['ma'];
				$conveyance_allowance = $sal_chk[0]['conv_allow'];
				$hill_allowance = $sal_chk[0]['hill_allowance'];
				$cpf = $sal_chk[0]['cpf'];
				$gross_salary = $sal_chk[0]['gross_salary'];
				
				//$gpf = $sal_chk[0]['gpf'];
				$gpf = getAmount($dise,$empcd,'gpf','',$emp_id_pk);  //changed Rupesh
				//$pfl = $sal_chk[0]['pf_loan'];
				$scc = getAmount($dise,$empcd,'scc','',$emp_id_pk);  //changed Rupesh
				$cpf_deduct = $sal_chk[0]['cpf_deduct'];
				$ptax = $sal_chk[0]['p_tax'];
				$itax = $sal_chk[0]['i_tax'];
				//$gsli = $sal_chk[0]['gsli'];
				$gsli = getAmount($dise,$empcd,'gsli','',$emp_id_pk);   //changed Rupesh
				$ssl = getAmount($dise,$empcd,'ssl','',$emp_id_pk);  //Added Rupesh
				$scl = getAmount($dise,$empcd,'scl','',$emp_id_pk);  //Added Rupesh
				$arrear_amount = getAmount($dise,$empcd,'arrear','',$emp_id_pk);  //Added Rupesh
				$overdrawn = $sal_chk[0]['overdrawn']; 
				//$cooperative_loan = $sal_chk[0]['cooperative_loan'];
			   // $hbl_loan = $sal_chk[0]['hbl_loan'];
			   $festival_loan = getAmount($dise,$empcd,'festival_loan','',$emp_id_pk);   //changed Rupesh
				//$festival_loan = $sal_chk[0]['festival_loan'];
				$net_salary = $sal_chk[0]['net'];
				}
				if($sal_chk[0]['salary_type']=='8'){
			
				$pay_in_band = $sal_chk[0]['pay_payband'];
				$consolidated_pay=0;
				$grade_pay = $sal_chk[0]['tch_grade_pay'];
				$basic = $sal_chk[0]['basic'];
				$da = $sal_chk[0]['da'];
				$hra = $sal_chk[0]['hra'];
				$interim_relief = $sal_chk[0]['interim_relief'];
				$ma = $sal_chk[0]['ma'];
				$conveyance_allowance = $sal_chk[0]['conv_allow'];
				$hill_allowance = $sal_chk[0]['hill_allowance'];
				$cpf = $sal_chk[0]['cpf'];
				$gross_salary = $sal_chk[0]['gross_salary'];
				//$gpf = $sal_chk[0]['gpf'];
			   $gpf = getAmount($dise,$empcd,'gpf','',$emp_id_pk);  //changed Rupesh
				//$pfl = $sal_chk[0]['pf_loan'];
				$scc = getAmount($dise,$empcd,'scc','',$emp_id_pk);     //changed Rupesh
				$cpf_deduct = $sal_chk[0]['cpf_deduct'];
				$ptax = $sal_chk[0]['p_tax'];
				$itax = $sal_chk[0]['i_tax'];
				//$gsli = $sal_chk[0]['gsli'];
				$gsli = getAmount($dise,$empcd,'gsli','',$emp_id_pk);   //changed Rupesh
				$ssl = getAmount($dise,$empcd,'ssl','',$emp_id_pk);  //Added Rupesh
				$scl = getAmount($dise,$empcd,'scl','',$emp_id_pk);  //Added Rupesh
				$arrear_amount = getAmount($dise,$empcd,'arrear','',$emp_id_pk);  //Added Rupesh
				$overdrawn = $sal_chk[0]['overdrawn']; 
				//$cooperative_loan = $sal_chk[0]['cooperative_loan'];
			   // $hbl_loan = $sal_chk[0]['hbl_loan'];
			   $festival_loan = getAmount($dise,$empcd,'festival_loan','',$emp_id_pk);   //changed Rupesh
				//$festival_loan = $sal_chk[0]['festival_loan'];
				 $net_salary = $sal_chk[0]['net'];
				}
					
		}
		
		$sal_type_for_leave = $sal_chk[0]['salary_type']; 							//Added On 25_08_2017		
	}
	
	else   //foreach ($tch as $key)
	{ 
	
	
	// Calculation for monthly leave by nd on 10072017
				$extra_monthly_leave = $db->fetch_table("select extra_leave_taken from mad_monthly_employee_leave where status='2' AND lock_status='2' AND delete_status='1' AND edit_status='1' AND approval_status='3' AND emp_id_fk='".$key['emp_id_pk']."' AND month='".date('m')."' AND year='".date('Y')."'");
			
				           $total_extra_leave = $extra_monthly_leave[0]['extra_leave_taken'];
						   $days_in_month = date('t');
						   $total_working_days = $days_in_month - $total_extra_leave;
				
							if($extra_monthly_leave[0]['extra_leave_taken'] > 0 && $extra_monthly_leave[0]['extra_leave_taken'] < $days_in_month)
							{
							  $sal_type_for_leave = "2";
							 $insert_val_for_working_days = $total_working_days;
							 $insert_part_salary_cause = 'For '.$total_extra_leave.' days extra leave taken.';
							 $insert_no_salary_cause = '';
							}
							else if ($extra_monthly_leave[0]['extra_leave_taken'] == $days_in_month)
							{
							  $sal_type_for_leave = "8";
							 $insert_val_for_working_days = '';
							 $insert_no_salary_cause = 'Absent for the whole month.';
							 $insert_part_salary_cause = '';
							}
							else  
							{
							 $sal_type_for_leave = "1";
							 $insert_val_for_working_days = '';
							 $insert_no_salary_cause = '';
							 $insert_part_salary_cause = '';
							}

			//echo "hhh";	
			// End of Calculation for monthly leave by nd on 10072017
	

			// previous month and year calculation	
			$month_by_n = date('m');
			$year_by_n = date('Y');
			//$month_by_n = 12;
			//$year_by_n = 2017;
			$last_month = $month_by_n-1%12;
			if($last_month ==0)
			{
				$new_last_month = '01';
			}
			if($last_month >0 && $last_month <10)
			{
				$new_last_month = '0'.$last_month;
			}
			else

			{
			  $new_last_month = $last_month;	
			}
			if($month_by_n == 01)
			{
			  $pre_yr = $year_by_n - 1;
			}	
			else
			{
			$pre_yr = $year_by_n;	
			}
			$sal_mnth_yr = $pre_yr.$new_last_month;
			// End of previous month and year calculation
			
	$sal_save=	$db->fetch_table("select emp_id_fk, empcd, 
		   bankname, accountno, basic, da, hra, ma, cpf, pf_loan, p_tax, 
		   i_tax, net, bank_ifsc, sal_source, spl_pay, pf_deduct, code, 
		   emp_salary_id_pk, spl_alo, status_flag, salary_monthyear, 
		   category_id, block_code, emp_id_fk, pay_payband, tch_grade_pay, 
		   hill_allowance, gpf, cpf_deduct, gross_salary, is_saved, conv_allow, 
		   overdrawn, salary_type, cause,gp_code, part_day,gsli,consolidated_pay,festival_loan,festival_loan_cause,interim_relief  from mad_employee_salary_save where status_flag=4 AND delete_status=1 AND salary_monthyear='".$sal_mnth_yr."' AND emp_id_fk='$emp_id_pk'");
		   
		
//salary_type changed from 3 to 4.		   
//echo "fff";
		
		//Added New Start
		$promotion_data_cal = $db->fetch_table("SELECT emp_grade_pay,
												emp_id_fk,
												increment_type,
												dop_or_doi,
												effective_date,
												emp_desig,
												emp_pay_scale,
												annual_increment_date,
												emp_pay_band,
												emp_pay_in_payband,
												increment_amount,
												pre_emp_pay_in_payband,
												pre_emp_grade_pay
												FROM mad_employee_promotion_details
												WHERE emp_id_fk ='".$emp_id_pk."'
												AND promotion_effective_status in('1') 
												AND status in('2','3') AND approval_status in('9','11')
												AND delete_status in('1','2','3')");
												
		$emp_id_for_promotion_part = $promotion_data_cal[0]['emp_id_fk'];									
		$pre_emp_pay_band_for_promotion_cal = $promotion_data_cal[0]['pre_emp_pay_in_payband'];
		$pre_emp_grade_pay_for_promotion_cal = (func_gradepay($promotion_data_cal[0]['pre_emp_grade_pay']));
		
		$emp_effective_date_for_promotion_cal =  $promotion_data_cal[0]['effective_date'];
		$emp_pay_band_for_promotion_cal =  $promotion_data_cal[0]['emp_pay_in_payband'];
		$emp_grade_pay_for_promotion_cal =  (func_gradepay($promotion_data_cal[0]['emp_grade_pay']));
		$emp_effective_day_for_promotion_cal = substr ($emp_effective_date_for_promotion_cal, -2);
		$max_days_of_current_month = date(t);
		$dop_doi = $promotion_data_cal[0]['dop_or_doi'];
		
		$eff_date = explode('-', $emp_effective_date_for_promotion_cal);
		$eff_year_month = $eff_date[0].$eff_date[1];
		
		 if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && $dop_doi == 3 && $eff_year_month == date("Ym"))
		 {
			 $count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
			 $count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
			 $part_grade_pay_for_pre_days = round(($pre_emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 //$part_pay_in_pay_band_for_pre_days = round(($pre_emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_grade_pay_for_post_days = round(($emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 //$part_pay_in_pay_band_for_post_days = round(($emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 $count_days_total_grade_pay_for_promotion = $part_grade_pay_for_pre_days + $part_grade_pay_for_post_days;
			 $count_days_total_pay_pay_band_for_promotion = $key['emp_pay_in_payband'];
		 }
		 else if($emp_effective_day_for_promotion_cal != 01 && $emp_id_for_promotion_part != "" && ($dop_doi == 4 || $dop_doi == 5) && $eff_year_month == date("Ym"))
		 {
			 $count_days_pre_part_sal = (($emp_effective_day_for_promotion_cal)-'1');
			 $count_days_post_part_sal = ($max_days_of_current_month - $count_days_pre_part_sal);
			 $part_grade_pay_for_pre_days = round(($pre_emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_pay_in_pay_band_for_pre_days = round(($pre_emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_pre_part_sal);
			 $part_grade_pay_for_post_days = round(($emp_grade_pay_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 $part_pay_in_pay_band_for_post_days = round(($emp_pay_band_for_promotion_cal/$max_days_of_current_month)*$count_days_post_part_sal);
			 
			 $count_days_total_grade_pay_for_promotion = $part_grade_pay_for_pre_days + $part_grade_pay_for_post_days;
			 $count_days_total_pay_pay_band_for_promotion = $part_pay_in_pay_band_for_pre_days + $part_pay_in_pay_band_for_post_days;
		 }
		 else
		 {
			 $count_days_total_pay_pay_band_for_promotion = $key['emp_pay_in_payband'];
			 $count_days_total_grade_pay_for_promotion = func_gradepay($key['emp_grade_pay']);
		 }
		//Added New End

	   	$emp_suspend=$db->fetch_table("
								SELECT 
										sus.slno,
										sus.suspend_effect_date,
										sus.suspend_withdrawn_date,
										sus.pencentage_basic,
										sus.suspend_start_date,
										sus.suspend_salary_check_status
										
									FROM
										mad_suspend_dts as sus
										INNER JOIN mad_employee_master   em1 on sus.emp_id_fk=em1.emp_id_pk
									WHERE
											sus.municipality_id_fk = '".$dise."' and sus.emp_id_fk='$emp_id_pk' and em1.emp_status='11' and sus.delete_status='0'
		                      ");
							  
 
								$suspend_effect_date=$emp_suspend[0]['suspend_effect_date']; 
			
			
//Final Check 121217 Start
/*//For annual increment and promotion

$annual_increment = $db->fetch_table("select * from mad_employee_annual_increment_details where emp_id_fk='".$emp_id_pk."'"); 
  $annual_increment_date = $annual_increment[0]['effective_monthyear'];	
  $annual_payin_payband = $annual_increment[0]['increment_amount'];	
//echo "select * from mad_employee_annual_increment_details where  emp_id_fk='".$key['emp_id_pk']."'";	
  $current_month_year = date(Y).date(m);
//die;

//die;	
	
//End of For annual increment and promotion	
					if($annual_increment_date==$current_month_year){
						$asd =56;
					}
				
				//End of For annual increment and promotion	*/
				//Final Check 121217 End
										
				if($key['emp_desig']=='999999')									//1120 -> 999999 Final Check 121217
				{ // dont know why used nirupam 23_03_17
					//die;
					$consolidated_pay=$key['emp_cosolidated_pay'];	
					$pay_in_band = 0;
					$grade_pay = 0;
					$basic = 0;
					$bas=$consolidated_pay+$grade_pay;
					$da = 0;
					$interim_relief=0;
					$hra = 0;
					$ma = 0;
					$conveyance_allowance = 0;
					$hill_allowance = 0;
					$cpf = 0;
					//$gross_salary = round($basic+$da+$hra+$ma+$conveyance_allowance+$cpf);
					$gpf = 0;
					$scc = 0;
					$cpf_deduct = 0;
					//added bonus amount.
					$gross_salary = round($bas+$da+$hra+$ma+$interim_relief+$conveyance_allowance+$cpf+$hill_allowance+$emp_bonus);
					if($key['emp_diff_able']=='1')
					{
					 $ptax = 0;
					}
					else
					{
						$ptax = empPtax($gross_salary);
					}
					$itax = 0;
					$gsli =0;
					$arrear_amount = 0;  //Added Rupesh
					$overdrawn = getAmount($dise,$empcd,'ovd','',$emp_id_pk);
					//$cooperative_loan = 0;
				   // $hbl_loan = 0;
				    $festival_loan = 0;
					
					$total_deduct = $gpf+$scc+$cpf_deduct+$ptax+$itax+$gsli+$overdrawn+$festival_loan+$ssl+$scl+$arrear_amount;
					$net_salary = $gross_salary-$total_deduct;
				}
				else
				{	
				// For first time part salary
				    if($sal_type_for_leave=='2')
			        {
						/*if(($annual_increment_date==$current_month_year))
						{
						$pay_in_band = (($key['emp_pay_in_payband'] + $annual_payin_payband) * $total_working_days)/ $days_in_month;
						//$consolidated_pay=0;
						//$grade_pay = 0;
						}
						else{*/
						$pay_in_band = ($key['emp_pay_in_payband'] * $total_working_days)/ $days_in_month;
						//$consolidated_pay=0;
						/*}*/
						$grade_pay = ( func_gradepay($key['emp_grade_pay']) * $total_working_days)/ $days_in_month;
						
						$basic = $pay_in_band + $grade_pay;
						$da = ($basic/100)*$da_per;
						
						//$interim_relief = $key['interim_relief'];
						
						$interim_relief = ($key['interim_relief'] * $total_working_days)/ $days_in_month;
						
						if($key['spouse_medical_allowance']=='1')
						{
							 $ma = 0;
						}
						else
						{
							 $ma = ($max_ma * $total_working_days)/$days_in_month;
						}
						
						
			         }
					 // by nd on 31_07_2017
					 
				/*else if($sal_type_for_leave=='8')
				{
					$pay_in_band = 0;
					//$consolidated_pay=0;
					$grade_pay = 0;
					$basic = 0;
					$da = 0;
					$interim_relief = 0;
					$hra = 0;
					$ma = 0;
					//echo $max_ma =0;
					
					// by nd on 31_07_2017 for ma
					//$max_ma=0;
					// by nd on 31_07_2017 for ma
					$conveyance_allowance = 0;
					$hill_allowance = 0;
					$cpf = 0;
					$gross_salary = 0; 
					$gpf = 0;
					$scc = 0;
					$cpf_deduct = 0;
					$ptax = 0;
					$itax = 0;
					$ovd = 0;
					$gsli = 0;
					$ssl = 0;
					$scl = 0;
					$festival_loan = 0;
					$emp_bonus = 0;
					$net_salary = 0;
					$total_deduct = 0;
					$arrear_amount = 0;
				}	*/
				//end by nd on 31_07_2017	
					 else if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
			          {
						  $suspend_salary_status = 1;
						  $get_suspend_effect_date = explode("-",$suspend_effect_date);
						  //print_r($get_suspend_effect_date);
						  $suspend_month_year = $get_suspend_effect_date[0].$get_suspend_effect_date[1]; 
						  if($emp_suspend[0]['suspend_salary_check_status'] != '1')
						  {
							  $get_suspend_salary = $db->fetch_table("SELECT archive_final_pk FROM mad_monthly_salary_archive_final
																	WHERE is_saved in(1) AND delete_status in('1') AND status_flag in(4)
																	AND suspend_salary_status in(1)
																	AND salary_monthyear >= '".$suspend_month_year."'
																	AND emp_id_fk = '".$emp_id_pk."'");
							  //echo count($get_suspend_salary);
							  if(count($get_suspend_salary) > 2)
							  {
								  $copy_date = $db->insert("INSERT INTO  mad_suspend_dts_archive (
															  slno,
															  municipality_id_fk,
															  emp_id_fk,
															  suspend_effect_date,
															  suspend_withdrawn_date,
															  pencentage_basic,
															  suspend_start_date,
															  delete_status,
															  resion,
															  emp_id_const_fk,
															  suspend_salary_check_status,
															  update_date,
															  who
															)  
															SELECT slno,
															  municipality_id_fk,
															  emp_id_fk,
															  suspend_effect_date,
															  suspend_withdrawn_date,
															  pencentage_basic,
															  suspend_start_date,
															  delete_status,
															  resion,
															  emp_id_const_fk,
															  suspend_salary_check_status,
															  now(),
															  '".$_SESSION['user_info']['stake_user']."'
														FROM mad_suspend_dts 
														WHERE emp_id_fk='".$emp_id_pk."' 
														AND delete_status='0'");
									if($copy_date)
									{
										$update_basic_per = $db->update("UPDATE mad_suspend_dts 
																		SET pencentage_basic = '75',
																		suspend_salary_check_status = 1
																		WHERE emp_id_fk='".$emp_id_pk."' 
																		AND delete_status='0'");
										
									}					
							  }
						  }
						  $emp_suspend=$db->fetch_table("SELECT pencentage_basic FROM
														mad_suspend_dts 	
														WHERE
														municipality_id_fk = '".$dise."' AND emp_id_fk='".$emp_id_pk."' 
														AND delete_status='0'");
						  //echo "hhh";
						  $pencentage_basic=$emp_suspend[0]['pencentage_basic']; 
						  //$pay_in_band_sus = $key['emp_pay_in_payband'];
						  $pay_in_band_sus = $count_days_total_pay_pay_band_for_promotion;
						  $pay_in_band = ($pay_in_band_sus*$pencentage_basic)/100;
						  //$grade_pay_sus = func_gradepay($key['emp_grade_pay']);
						  $grade_pay_sus = $count_days_total_grade_pay_for_promotion;
						  $grade_pay=($grade_pay_sus*$pencentage_basic)/100;
						  $basic = $pay_in_band_sus+$grade_pay_sus;
						  $basic_sus = $pay_in_band+$grade_pay;
						  $basic = $basic_sus;					//For calculation with 50% of basic by Rupesh.
						  $da_sus = ($basic/100)*$da_per;
						  $da = ($basic_sus/100)*$da_per;
						  $interim_relief =0;
						  
						  if($key['spouse_medical_allowance']=='1')
						  {
							 $ma = 0;
						  }
						  else
						  {
							 $ma = $max_ma;
						  }
				     
			          }
					  else
					  {
						 /*if(($annual_increment_date==$current_month_year))
							{
							$pay_in_band = (($key['emp_pay_in_payband'] + $annual_payin_payband) * $total_working_days)/ $days_in_month;
							//$consolidated_pay=0;
							//$grade_pay = 0;
							}
						 else{*/
					     //$pay_in_band = $key['emp_pay_in_payband'];
						 $pay_in_band = $count_days_total_pay_pay_band_for_promotion;
						 /*}*/
						 //$grade_pay = func_gradepay($key['emp_grade_pay']);
						 $grade_pay = $count_days_total_grade_pay_for_promotion;
			             $basic = $pay_in_band+$grade_pay;
						 $da = ($basic/100)*$da_per;
						 $interim_relief = $key['interim_relief'];
						 
						  if($key['spouse_medical_allowance']=='1')
						  {
							 $ma = 0;
						  }
						  else
						  {
							 $ma = $max_ma;
						  }
						 	  /* if($sal_save[0]['interim_relief']!='') {
				           $interim_relief = $sal_save[0]['interim_relief'];
					         }
					         else{
				             $interim_relief = $key['interim_relief'];
					         }*/
						 
					 	}

						$consolidated_pay=0;
						//$cooperative_loan=0;
						//$hbl_loan=0;
						//$festival_loan=0;
			
							if($key['emp_spouse_res']=='251')
							{
						 		$hra = 0; 
							}
							else{
								if($key['emp_spouse_hra']=='0' ||$key['emp_spouse_hra']=='' || !$key['emp_spouse_hra']){
								$hra_emp = ($basic/100)*$hra_per;
									if($hra_emp > 6000)
									{
										$hra = 6000;
									}
									else
									{
										$hra = round($hra_emp);
									}
  								}
						else if($key['emp_spouse_hra'] >= 6000)
						{
							//echo "HRA";
							$hra = 0;
						}
						else if($key['emp_spouse_hra'] < 6000)
						{ //spouse HRA < 6000
							$hra_emp = ($basic/100)*$hra_per;
							
							if($hra_emp >= 6000)
							{
								$valid_hra  = (6000-$key['emp_spouse_hra']);
								$hra = round($valid_hra);
							
						    }/*
							else
							{
							$hra_emp = (($basic/100)*$hra_per)+$key['emp_spouse_hra'];
							if($hra_emp > 6000){
								$valid_hra  = (6000-$key['emp_spouse_hra']);
								$hra = round($valid_hra);
							}
*/							
							else
							{
							   $mix_hra = $hra_emp+$key['emp_spouse_hra'];
							   if($mix_hra > 6000)
							   {
								 if($key['emp_spouse_hra'] > $hra_emp)
								 {
								
							//$valid_hra = $key['spouse_hra']-$hra_emp;
								$valid_hra = 6000-$key['emp_spouse_hra'];
									 if($valid_hra>$hra_emp)
									 {
										$valid_hra=$hra_emp;
									 }
											   
								$hra = round($valid_hra);
								}
							 	else if($key['emp_spouse_hra'] <= $hra_emp){
									$valid_hra = 6000-$key['emp_spouse_hra'];
									if($valid_hra>$hra_emp)
									{
									$valid_hra=$hra_emp;
									}
									$hra = round($valid_hra);
								}
							}
							else
							{
								$hra = round($hra_emp);
							}
						}
					}
				}
					
				/*if($key['spouse_medical_allowance']=='1')
				{
					 $ma = 0;
				}*/
				// add by nd on 31_07_17 for no salary
				/*else if($sal_type_for_leave=='8')
				{
				$ma = 0;
				}*/
				//end of add by nd on 31_07_17 for no salary
				/*else
				{
					$ma = $max_ma;
				}*/
				//$ma = $max_ma;
				if($key['emp_diff_able']=='1'){
					
					// Comment by ND on 16_10_2017_for fonal_salary_code_edit   
					
					//if($key['conv_allow_status']=='1')
					//{
					 	$cal_ma=round(($basic*5)/100);
						if($cal_ma>=400)
						{
							$conveyance_allowance = $conveyance_allowance_max;
						}
						else
						{
							$conveyance_allowance=round($cal_ma);
						}
					//}
					//else
					//{
					//			$conveyance_allowance = 0;
					//}
					
					// End of Comment by ND on 16_10_2017_for fonal_salary_code_edit
					
				}
				else
				{
					$conveyance_allowance = 0;
				}
				
				// Comment by ND on 16_10_2017_for fonal_salary_code_edit 
				
				/*if($state10=='3219')
				{
						$hill_p = ($pay_in_band/100)*$hill_allowance_per;
						$hill_g = ($grade_pay/100)*$hill_allowance_per;
						$hill_allowance_amt = $hill_p+$hill_g;
						if($hill_allowance_amt > 1500)
						{
							 $hill_allowance = 1500;
						}
						else
						{
						 $hill_allowance = $hill_allowance_amt;
						}
				}
				else
				{
						 $hill_allowance = 0;
				}*/
				
				// End of Comment by ND on 16_10_2017_for fonal_salary_code_edit
				
				$cpf = getAmount($dise,$empcd,'cpf','',$emp_id_pk);
				
			    //$gross_salary = round($pay_in_band+$grade_pay+$da+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance);
				//Deduction part
				
				// comment by ND on 03_07_2017 for gpf calculation from table	
				/*if(strtotime($emp_first_join_match_date)>=strtotime(date('Y-m-d')) && strtotime($emp_first_join_match_date)>0)
				{
					 $gpf=0;
					
				}	
				else if(strtotime($date)<=strtotime(date('Y-m-d')) && strtotime($date)>0)
			    {
				       $gpf=0;
			    }
			    else
			    {
			     	if($sal_save[0]['gpf']!=0)
				    {
					    $gpf=$sal_save[0]['gpf'];	
					}
					else
					{
						$gpf = getAmount($dise,$empcd,'gpf','',$emp_id_pk);
						
					}
				}*/
				//End of comment by ND on 03_07_2017 for gpf calculation from table
				$gpf = getAmount($dise,$empcd,'gpf','',$emp_id_pk);  //changed by ND on 03_07_2017
			
				//if($sal_save[0]['pf_loan'])
				//{
					//$pfl=$sal_save[0]['pf_loan'];
				//}
				//else
				//{
					$scc = getAmount($dise,$empcd,'scc','',$emp_id_pk);  //changed Rupesh
				//}
				$cpf_deduct = $cpf*2;
				
	
				if($sal_save[0]['i_tax'])
				{
					 $itax=$sal_save[0]['i_tax'].'';
					//echo 1;
				}
				else
				{
					 $itax = getAmount($dise,$empcd,'itax','',$emp_id_pk).'';
					
				}
					
				//if($sal_save[0]['gsli'])
				//{
					//$gsli=$sal_save[0]['gsli'];
					
				//}
				//else
				//{
				$gsli = getAmount($dise,$empcd,'gsli','',$emp_id_pk); //changed Rupesh
				$ssl = getAmount($dise,$empcd,'ssl','',$emp_id_pk);  //Added Rupesh
				$scl = getAmount($dise,$empcd,'scl','',$emp_id_pk);  //Added Rupesh
				$arrear_amount = getAmount($dise,$empcd,'arrear','',$emp_id_pk);  //Added Rupesh
				$festival_loan = getAmount($dise,$empcd,'festival_loan','',$emp_id_pk); //changed Rupesh
				//}
				$overdrawn = getAmount($dise,$empcd,'ovd','',$emp_id_pk);
				//echo $total_deduct = $gpf+$pfl+$cpf_deduct+$ptax+$itax+$gsli;
				$total_deduct = $gpf+$scc+$cpf_deduct+$ptax+$itax+$gsli+$festival_loan+$ssl+$scl+$arrear_amount;
				
				//added bonus amount.
				$gross_salary = round($pay_in_band+$grade_pay+$da+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance+$emp_bonus);
				//echo $net_salary = $gross_salary-$total_deduct;
				 $net_salary = $gross_salary-$total_deduct;
				
				if($key['emp_diff_able']=='1')
				{
					$ptax = 0;
					$total_deduct = $gpf+$scc+$cpf_deduct+$ptax+$itax+$gsli+$festival_loan+$ssl+$scl+$arrear_amount; //added by nirupam on 02_05_2017 when ptax= 0 for diif_able person
					$net_salary = $gross_salary-$total_deduct; //added by nirupam on 02_05_2017 when ptax= 0 for diif_able person
				}
				else
				{
					  if(strtotime($suspend_effect_date)<=strtotime(date('Y-m-d')) && strtotime($suspend_effect_date)>0)
					  {
					 //$gross_salary_sus=round($pay_in_band_sus+$grade_pay_sus+$da_sus+$interim_relief+$hra+$ma+$conveyance_allowance+$cpf+$hill_allowance);
					 //commented by Rupesh
					   $ptax = empPtax($gross_salary);  //Done from gross_salary_sus to gross_salary by Rupesh.
					 }
					 else
					 {
						  $ptax = empPtax($gross_salary);
					 }
					$total_deduct = $gpf+$scc+$cpf_deduct+$ptax+$itax+$gsli+$festival_loan+$ssl+$scl+$arrear_amount;  // added by nirupam for ptax calculation on 22_03_2017	
				}

			}
			if($sal_type_for_leave=='8')
				{
					$pay_in_band = 0;
					//$consolidated_pay=0;
					$grade_pay = 0;
					$basic = 0;
					$da = 0;
					$interim_relief = 0;
					$hra = 0;
					$ma = 0;
					//echo $max_ma =0;
					
					// by nd on 31_07_2017 for ma
					//$max_ma=0;
					// by nd on 31_07_2017 for ma
					$conveyance_allowance = 0;
					$hill_allowance = 0;
					$cpf = 0;
					$gross_salary = 0; 
					$gpf = 0;
					$scc = 0;
					$cpf_deduct = 0;
					$ptax = 0;
					$itax = 0;
					$ovd = 0;
					$gsli = 0;
					$ssl = 0;
					$scl = 0;
					$festival_loan = 0;
					$emp_bonus = 0;
					$net_salary = 0;
					$total_deduct = 0;
					$arrear_amount = 0;
				}	
			
			
		}
				//Final Check 121217 Start
				/*if(($cooperative_loan)>0)
				{
					$cooperative_loan_cause=1;	
				}
				else
				{
					$cooperative_loan_cause=0;	
				}
				
				if(($hbl_loan)>0)
				{
					$hbl_loan_cause=1;	
				}
				else
				{
					$hbl_loan_cause=0;	
				}
				
				if(($festival_loan)>0)
				{
					$festival_loan_cause=1;	
				}
				else
				{
					$festival_loan_cause=0;	
				}*/
				$cooperative_loan_cause=0;
				$hbl_loan_cause=0;
				$festival_loan_cause=0;	
				//Final Check 121217 End
				
				//No Salary for leave Start
				/*if($sal_type_for_leave=='8')
				{
					$pay_in_band = 0;
					//$consolidated_pay=0;
					$grade_pay = 0;
					
					$basic = 0;
					$da = 0;
					$interim_relief = 0;
					$hra = 0;
					$ma = 0;
					$conveyance_allowance = 0;
					$hill_allowance = 0;
					$cpf = 0;
					$gross_salary = 0; 
					$gpf = 0;
					$scc = 0;
					$cpf_deduct = 0;
					$ptax = 0;
					$itax = 0;
					$ovd = 0;
					$gsli = 0;
					$ssl = 0;
					$scl = 0;
					$festival_loan = 0;
					$emp_bonus = 0;
					$net_salary = 0;
					$total_deduct = 0;
					$arrear_amount = 0;
				}*/
				//No Salary for leave End					
				
				
				$_SESSION['tchname'.$emp_id_pk] = $crypto->encode($key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'],4);
				$_SESSION['municipality_id_fk'.$emp_id_pk] = $crypto->encode($key['municipality_id_fk'],4); //Added on 25_08_2017
				
				$_SESSION['bankname'.$emp_id_pk] = $crypto->encode($key['emp_bank_name'],4);
		        $_SESSION['accountno'.$emp_id_pk] = $crypto->encode($key['emp_acc_no'],4);
				$_SESSION['bank_ifsc'.$emp_id_pk] = $crypto->encode($key['emp_ifsc_no'],4);
				$_SESSION['salary_source'.$emp_id_pk] = $crypto->encode($key['salary_source'],4);
				$_SESSION['code'.$emp_id_pk] = $crypto->encode($key['emp_system_code'],4);
				$_SESSION['emp_id_pk'.$emp_id_pk] = $crypto->encode($key['emp_id_pk'],4);
				if($key['rank']){
					$_SESSION['rank'.$emp_id_pk] = $crypto->encode($key['rank'],4);
				}else{
					$_SESSION['rank'.$emp_id_pk] = $crypto->encode(0,4);
				}
				$_SESSION['basic'.$emp_id_pk] = $crypto->encode($basic,4);
				if($pay_in_band!=0){ 
					$_SESSION['pay_in_band'.$emp_id_pk] = $crypto->encode($pay_in_band,4);
				}else{
					$_SESSION['pay_in_band'.$emp_id_pk] = $crypto->encode(0,4);
					//$_SESSION['pay_in_band'.$emp_id_pk] = $crypto->encode($consolidated_pay,4);
				}
				if($consolidated_pay!=0){
				$_SESSION['consolidated_pay'.$emp_id_pk] = $crypto->encode($consolidated_pay,4);
				}else{
					
					$_SESSION['consolidated_pay'.$emp_id_pk] = $crypto->encode(0,4);
					//$_SESSION['pay_in_band'.$emp_id_pk] = $crypto->encode($consolidated_pay,4);
				}
				if($grade_pay){ 
					$_SESSION['grade_pay'.$emp_id_pk] = $crypto->encode($grade_pay,4);
				}else{
					$_SESSION['grade_pay'.$emp_id_pk] = $crypto->encode(0,4);
				}
				$_SESSION['da'.$emp_id_pk] = $crypto->encode($da,4);
				/*if($interim_relief == '')
				{
					$interim_relief = 0;
				}*/
				if($interim_relief == '')
				{
					$_SESSION['interim_relief'.$emp_id_pk] = $crypto->encode(0,4);
				}
				else
				{
					$_SESSION['interim_relief'.$emp_id_pk] = $crypto->encode($interim_relief,4);
				}
				$_SESSION['hra'.$emp_id_pk] = $crypto->encode($hra,4);
				$_SESSION['ma'.$emp_id_pk] = $crypto->encode($ma,4);
				$_SESSION['conveyance_allowance'.$emp_id_pk] = $crypto->encode($conveyance_allowance,4);
				$_SESSION['hill_allowance'.$emp_id_pk] = $crypto->encode($hill_allowance,4);
				$_SESSION['cpf'.$emp_id_pk] = $crypto->encode($cpf,4);
				$_SESSION['gross_salary'.$emp_id_pk] = $crypto->encode($gross_salary,4);
				$_SESSION['gpf'.$emp_id_pk] = $crypto->encode($gpf,4);
				$_SESSION['scc'.$emp_id_pk] = $crypto->encode($scc,4);
				$_SESSION['cpf_deduct'.$emp_id_pk] = $crypto->encode($cpf_deduct,4);
				$_SESSION['ptax'.$emp_id_pk] = $crypto->encode($ptax,4);
				$_SESSION['itax'.$emp_id_pk] = $crypto->encode($itax,4);
				$_SESSION['ovd'.$emp_id_pk] = $crypto->encode($overdrawn,4);
				$_SESSION['gsli'.$emp_id_pk] = $crypto->encode($gsli,4);
				$_SESSION['ssl'.$emp_id_pk] = $crypto->encode($ssl,4);
				$_SESSION['scl'.$emp_id_pk] = $crypto->encode($scl,4);
				$_SESSION['festival_loan'.$emp_id_pk] = $crypto->encode($festival_loan,4); 
				$_SESSION['festival_loan_cause'.$emp_id_pk] = $crypto->encode($festival_loan_cause,4);
				$_SESSION['emp_bonus'.$emp_id_pk] = $crypto->encode($emp_bonus,4);
				$_SESSION['emp_retirement_date'.$emp_id_pk] = $crypto->encode($retirement_date,4);
				
					
				$loan_deductions = $db->fetch_table("SELECT installment_amount,no_of_installment, counter,reminder_amount FROM mad_loan_deduction WHERE status in (2) AND approval_status in (3) AND emp_id_fk='".$emp_id_pk."'");
				 $total_loan_deduction = 0;
				 //echo $sal_type_for_leave;
				if($sal_type_for_leave != '8')    //THIS IS FOR NO SALARY FOR LEAVE TAKEN 
				{
					foreach($loan_deductions as $deductions)
					{
						if($deductions['counter'] < $deductions['no_of_installment'])
						{
							 $total_loan_deduction = $total_loan_deduction + $deductions['installment_amount'];
						}
						else
						{
							$total_loan_deduction = $total_loan_deduction + $deductions['reminder_amount'];
						}
					}
				}
				else
				{  
					 $total_loan_deduction = 0;				//THIS IS FOR NO SALARY FOR LEAVE TAKEN 
				}
				//echo  $total_loan_deduction;
				$total_deduct = $total_deduct+$total_loan_deduction;
				if($sal_chk[0]['salary_type']!='8' && $sal_chk[0]['salary_type']!='2') // by nirupam on 28_03_2017 for no salary and part salary not showing the net salary
				{
				$net_salary = $gross_salary-$total_deduct; // comment by nirupam on 29_03_2017 for full salary showing the net salary wrong
				}
				$_SESSION['total_loan_deduction'.$emp_id_pk] = $crypto->encode($total_loan_deduction,4);
				$_SESSION['net_salary'.$emp_id_pk] = $crypto->encode($net_salary,4); 
				
				$_SESSION['salary_type'.$emp_id_pk] = $crypto->encode($sal_type_for_leave,4);
				$_SESSION['part_day'.$emp_id_pk] = $crypto->encode($insert_val_for_working_days,4); 
				$_SESSION['no_salary_cause'.$emp_id_pk] = $crypto->encode($insert_no_salary_cause,4); 
				$_SESSION['part_salary_cause'.$emp_id_pk] = $crypto->encode($insert_part_salary_cause,4); 
				if($arrear_amount != '')
				{			
					$_SESSION['total_arrear_deduction'.$emp_id_pk] = $crypto->encode($arrear_amount,4);
				}
				else
				{
					$_SESSION['total_arrear_deduction'.$emp_id_pk] = $crypto->encode(0,4);
				}
				$_SESSION['suspend_salary_status'.$emp_id_pk] = $crypto->encode($suspend_salary_status,4);
			?>
            <?php // For increment module nirupam 23_03_17
			//$emp_id_pk=$key['emp_id_pk'];
			//$arr1 = $db->fetch_table("select emp_retirement_date from mad_employee_master where emp_id_pk='".$emp_id_pk."'");
			/*$arr1=$db->fetch_table("select emp_retirement_date from mad_employee_master
 			WHERE emp_id_pk='".$emp_id_pk."'
			");*/
		/*	 $retirement_date=$key['emp_retirement_date'];
			
			 $retirement_date_str=(explode ('-',$retirement_date)); 
			 
		     $retirement_date_str_final=$retirement_date_str[0].$retirement_date_str[1]; 
			  $current_date=date("Ym");
			 $date=date("Ym", strtotime("+2 months"));
			 
			if($retirement_date_str_final<=$date)
			{
				echo hi;
			}
			else
			{
				echo hello;
			}*/
			 ?>
            
            <tr>
            <td id="show"><?php echo $count; ?></td>
            <td style="min-width:100px; text-align:left;"><?php echo $key['emp_first_name'].' '.$key['emp_second_name'].' '.$key['emp_last_name'] ?></td>
            <!--<td><?php echo round($consolidated_pay); ?></td>-->
            <td><?php echo round($pay_in_band); ?></td>
           
            <td><?php echo round($grade_pay); ?></td>
            <td><?php echo round($da); ?></td>
            <td><?php echo round($hra); ?></td>
            <td><?php echo $ma; ?></td>
            <td><?php echo $conveyance_allowance; ?></td>
             <!--<td><?php echo round($hill_allowance); ?></td>-->
             <td><?php echo round($interim_relief); ?></td>
             <td><?php 
			 if($sal_chk[0]['salary_type']!='8') // by nirupam on 28_03_2017 for no salary not showing the net salary
				{
					echo round($emp_bonus); 
				}
				else
				{
					$emp_bonus = 0;
					$_SESSION['emp_bonus'.$emp_id_pk] = $crypto->encode($emp_bonus,4);
					echo $emp_bonus;
				}
			 ?></td>
            <td><?php echo round($gross_salary); ?></td>
            <td><?php echo $gpf;  ?></td>
            <td><?php echo $ssl;  ?></td>
            <td><?php echo $scl;  ?></td>
            <td><?php echo $scc; ?></td>
          <!-- <td><?php //echo round($cpf_deduct); ?></td>-->
            <td><?php echo $ptax; ?></td>
            <td><?php echo $itax; ?></td>
            <td><?php echo $gsli; ?></td>
            <td><?php echo $overdrawn; ?></td>
           
            <!--<td><?php //if($cooperative_loan){echo $cooperative_loan;} else {echo 0;} ?></td>
             <td><?php //if( $hbl_loan){echo $hbl_loan;} else {echo 0;} ?></td>-->
             <!-- <td><?php //if($festival_loan){echo $festival_loan;} else {echo 0;} ?></td>-->
            <td><?php echo $festival_loan; ?></td>
            <td><?php echo $arrear_amount; ?></td>
              
              <?php
			 	 
			  ?>
              
                 <td class="loan"><a data-toggle="modal" data-target=".bs-example-modal-lg-loan" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> ">
				<?php 
				
				//if(($sal_chk[0]['salary_type'] != '8')) // by nirupam on 28_03_2017 for no salary not showing the net salary
				/*if($sal_chk[0]['salary_type'] != '8') // by nirupam on 28_03_2017 for no salary not showing the net salary
				{
					echo $sal_type_for_leave;
				echo $total_loan_deduction;
				}
				else
				{
				echo "0";	
				}*/
				echo $total_loan_deduction; //Changed On 25_08_2017
				
				 ?></a></td>
            <td><?php echo round($net_salary); ?></td>
            
            <input type="hidden" name="gp_id" id="gp_id" value="<?php echo $crypto->encode($key['municipality_id_fk'],4) ?>" />
            
            <td class="edit"><?php if($sal_save_status_flag[0]['status_flag'] == 7){ ?><img style="opacity:0.5;" width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit" title="Edit">
            <?php } else{?>
            <a data-toggle="modal" data-target=".bs-example-modal-lg" href="" id="<? echo $crypto->encode($key['emp_id_pk'],4) ?> "><img width="25" src="<?= $config['base_url'] ?>themes/default/image/edit_icon.png" alt="Edit" title="Edit"></a>
            <?php
			}
			?>
            </td>
            <!--<div id="test_div">Hello</div>-->
                        
                <?php
                	$saved = 0;
                	for($i =0 ; $i < $tch_count ; $i++){
						
						if($key['emp_id_pk'] == $sal_saved[$i]['emp_id_fk'])
						
						{
						 $saved = 1;
						// $j = 0; 
				//if(!isset($sal_saved[$j]['is_saved']))
				//{	
							if($sal_save_status_flag[0]['status_flag'] == 7){		
				?>
				<td><img style="opacity:0.5;" width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Saved" title="Saved"></td>
				<?php 
							}
							else
							{
				?>
                <td><img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Saved" title="Saved"></td>
				<?php	
							}
					 	}
                	}
				//}
				//else if(isset($sal_saved[$j]['is_saved']) && ($sal_saved[$j]['is_saved'] == 0))	 
				//{
				if($saved == 0){ //echo "dd"; 
				
							if($sal_save_status_flag[0]['status_flag'] == 7){	
				?>
                 <td><img style="opacity:0.5;" width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Save" title="Save"></td>			
				 
                
				<?php //$count=$count+1;
							}
							else
							{
								$extra_monthly_leave_save_butz = $db->fetch_table("select extra_leave_taken from mad_monthly_employee_leave where status='2' AND lock_status='2' AND delete_status='1' AND edit_status='1' AND approval_status='3' AND emp_id_fk='".$key['emp_id_pk']."' AND month = '".date('m')."' AND year = '".date("Y")."'");
								$loan_details_unlocked_status = $db->fetch_table("SELECT loan_id_pk FROM mad_loan_deduction 
																WHERE status in('2') AND approval_status in('3') AND lock_status in('4')
																AND emp_id_fk = '".$key['emp_id_pk']."'");
								if(count($extra_monthly_leave_save_butz) < 1)
								{
								?>
									<td><a onclick="alert('Please Upload And Approve Monthly Leave Details And Try Again...');"><img id="img_id<?=$count?>" width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Save" title="Save"></a></td>
                                <?php
								}
								else if(count($loan_details_unlocked_status) > 0)
								{
								?>
									<td><a onclick="alert('Please Approve Unlocked Loan Details Of This Employee And Try Again...');"><img id="img_id<?=$count?>" width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Save" title="Save"></a></td>
                                <?php		
								}
								else
								{
								?>
                <td class="save" id="save"><div class="save_id<?=$count?>"><a emp_id_pk="<? echo $crypto->encode($key['emp_id_pk'],4) ?>"empcd="<?php echo $crypto->encode( $key['empcd'],4) ?>"sec_tok="<?=$enc_token?>" name="<?= $key['emp_first_name']." ".$key['emp_second_name']." ".$key['emp_last_name'] ?>" show="<?=$count?>"><img id="img_id<?=$count?>" onclick="return hidemsg(this.id)" width="25" src="<?= $config['base_url'] ?>themes/default/image/save_icon.png" alt="Save" title="Save"></a>
                </div>
                </td>
                                <?php
								}
							}
				}
				//}
				//$j++;
					//echo $sal_saved[1]['empcd'];
					
                ?>
           
            <?php $count += 1 ; ?></tr> <? } } else {?> <tr><td colspan="23" style="color:#F00; font-size:18px"><strong>No data found</strong></td></tr> <?php }?>
            </table>
        
<style>
		  #save
			{
		    cursor: pointer;
			}
         .finalize{
         	text-align: center;
         	margin-left: 370px;
         }
         .finalize ul {
				list-style-type:none;
				margin:0;
				padding:0;
				overflow:hidden;
			}
			.finalize li {
				float:left;
			}
			.finalize a:link, .finalize a:visited {
				display:block;
				width:133px;
				font-weight:bold;
				color:#FFFFFF;
				text-align:center;
				height:32px;
				text-decoration:none;
				text-transform:uppercase;
				background-image: url('<?=$config['base_url']?>themes/default/image/finalize_button.png');
			}
			.finalize a:hover, .finalize a:active {
				
				background-image: url('<?=$config['base_url']?>themes/default/image/finalize_button.png');
				background-position: 0px 32px;
			}
         </style>
        
      
            <div class="form-group" id="sal_save" style="display:none;">
            <div class="col-sm-offset-5 col-sm-7" style="margin-top:1%">
              <!--<a href="<?= $config['base_url'] ?>page/intra_mad/ddo_deo/sal_requisition/ajax_rq_finalize.php" class="btn btn-success btn-sm">Salary Finalize</a>-->
              <a href="javascript:void(0);" class="btn btn-success btn-sm" data-toggle="modal" data-target="#finalize_confirm">Salary Finalize</a>
            </div>
          </div>
          
            <?php 
		 if(count($tch) == count($sal_saved)){ 
         	//if(count($tch)){
			//if(count($sal_save_status_flag) != count($tch)){ 
			if(count($sal_saved_removed) != count($tch)){
				if(count($sal_saved_locked_or_finalized) < 1){
         	?>
            <div class="form-group" id="sal_save" >
            <div class="col-sm-offset-5 col-sm-7" style="margin-top:1%">
              <a class="btn btn-success btn-sm" href="javascript:void(0);" data-toggle="modal" data-target="#finalize_confirm">Salary Finalize</a>
            </div>
          </div>
         	
		<?php } } }}  else { ?>
            <script>
					$('.emplist').css('display','none');
					 //$("#wait").css("display", "none");
					window.setTimeout(function(){
							window.location.replace("<?php echo $config['base_url'] . 'page/intra_mad/ddo/sal_requisition/view_salary_requisition.php'; ?>");
					},3000);
            </script>
			
			<?php } ?>
	   
<!-----------------------------------------------------------------MODAL Start---------------------------------->


<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width: 1100px;margin-left: -10.5%;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Salary Details</h4>
      </div>
      <div class="modal-body"> 
      <div id="mbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>


<div class="modal fade bs-example-modal-lg-loan" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="width: 850px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Loan Details</h4>
      </div>
      <div class="modal-body"> 
      <div id="loanmbody"> 
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>      
      </div>
    </div>
  </div>
</div>
<?php require '../../../../page/municipality_admin/footer_js.php'; ?>
<script>
		$(document).ready(function(){
			
			//var tch=(<?=count($tch)?>);  //commented for status_flag = 7
			//alert (tch);
		  var sal_saved_removed = <?=count($sal_saved_removed)?>;
		  //alert(sal_saved_removed);
		  if(sal_saved_removed > 0)
		  {
			  var tch=(<?=count($tch_removed)?>);
		  }
		  else
		  {
			  var tch=(<?=count($tch)?>);
		  }
		  //alert(tch);
			
		  $( "tr:odd" ).css( "background-color", "#DDF7FF" );
		  $( "tr:even" ).css( "background-color", "#FFFFFF" );
		  $( ".divRow:last" ).css( "border-radius", "0px 0px 5px 5px" );
		  $("#wait").css("display","none");
		  $("#dialog-confirm").css("display", "none");
		  $("#saving").css("display", "none");
		
		  $(".edit a").click(function() {	
        //var link = $(this).attr('href');
		var link = $(this).attr('id');
		//alert(link);
		var link1= $("#gp_id").val();
		//alert(link);
		//alert(link1);
		//$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/sal_requisition/ajax_requisition_edit.php?id='+link+'&gp_id='+link1, function(data)
		$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/sal_requisition/ajax_requisition_edit.php?id='+link+'&mu_id='+link1, function(data){
				// alert(data);
				 $("#mbody").html(data);
		       });
		    });
			
			 $(".loan a").click(function() {
				 //alert('gg');	
        //var link = $(this).attr('href');
		var link = $(this).attr('id');
		//alert(link);
		var link1= $("#gp_id").val();
		//alert(link);
		//alert(link1);
		//$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/sal_requisition/ajax_emp_loan_details_vew.php?id='+link+'&gp_id='+link1, function(data)
		$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/sal_requisition/ajax_emp_loan_details_vew.php?id='+link+'&mu_id='+link1, function(data){
				// alert(data);
				 $("#loanmbody").html(data);
		       });
		    });
			

		$(".save a").click(function() {	
		$("#sal_save").hide();
		
        var emp_id_pk = $(this).attr('emp_id_pk');
		var name=$(this).attr('name');
		var empcd = $(this).attr('empcd');
		var sec_tok = $(this).attr('sec_tok');
		var show = $(this).attr('show');
		//alert(show);
		
	    $.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/sal_requisition/ajax_rq_save.php?emp_id_pk='+emp_id_pk+'&empcd='+empcd+'&sec_tok='+sec_tok, function(data){
		//$("#test_div").html(data);
		var result = $.parseJSON(data);
		//alert(result);
		
				//alert(result[0]);
				//alert(result[1]);
				var sal_save=result[1];
				//alert('save');
				//alert(sal_save);
				//alert(tch);
				//$("#mbody").html(data);  
				if(tch==sal_save)
				{
					
					$("#sal_save").show();
					
					}
					else{
						$("#sal_save").hide();
						}
				if(data==1)
				{ 
				$('.border_val').html('<div class="alert alert-danger" style="text-align:center"><strong>Time Out!...Please Try Again...<strong></div>');
				}
				
				 if(result[0]==2)
				{ 
					$('.border_val').html('<div class="alert alert-success" style="text-align:center"><strong>Salary Requisition of '+name+' Has Been Saved Successfully.</strong></div>');
					$('.save_id'+show+'').html('<img width="25" src="<?= $config['base_url'] ?>themes/default/image/saved_icon.png" alt="Edit">');
					
				}
					
					if(data==3)
				{
					$('.border_val').html('<div class="alert alert-danger" style="text-align:center"><strong>Salary Requisition of '+name+' Has Not Been Saved. Please Try Again...</strong></div>');
				}
				
				 if(data==5)
				{
					$('.border_val').html('<div class="alert alert-danger" style="text-align:center"><strong>Salary Requisition of '+name+' Has Not Been Saved. Please Try Again...</strong></div>');
					}
				if(data==99)
				{
					$('.border_val').html('<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Net Salary.</strong></div>');
					}
					
				//alert(data);
					
					
		       });
		    });

			
			$("#finalize").click(function() {	
		//alert('in function');
		$.post('<?= $config['base_url'] ?>page/intra_mad/ddo_deo/sal_requisition/ajax_rq_finalize.php', function(data){
				//alert(data);
				 //$("#mbody").html(data);
		       });
		    });
			
		});
       </script>
 
<!-----------------------------------------------------------------MODAL END---------------------------------------------------->

<div class="modal fade bs-example-modal-sm" id="finalize_confirm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Employee Salary Requisition Finalization</h4>
      </div>
      <div class="modal-body"> 
      <p class="alert alert-info" style="color: #ce8f22; background-image: none; box-shadow: none;
    text-shadow: none; padding: 9px 19px 9px 15px; border-radius: 3px 3px 3px 3px; border: 1px solid #bfd4be; -webkit-transition: all 0.2s linear 0s; transition: all 0.2s linear 0s;"><strong><i class="fa fa-exclamation-triangle" style="font-size:20px;"></i><font color="#0F4D6D">Please make sure that all the salary components to be reflected in the current month are already submitted in respective modules and are present in the salary requisition.</font></strong></p>
      </div>
      <div class="modal-footer">
      <div class="btn-group">
		<a class="btn btn-success" href="<?= $config['base_url'] ?>page/intra_mad/ddo_deo/sal_requisition/ajax_rq_finalize.php" />YES</a>
        <button type="button" class="btn btn-warning" data-dismiss="modal">NO</button>      
      </div>
      </div>
    </div>
  </div>
</div>
<?php @pg_close($con); ?>
