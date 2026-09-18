<?php


ob_start();
session_start();
//error_reporting(0);
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
//require '../../page_visite.php';
require '../../../../includes/library/myvalidation.class.php';


$crypto = new cryptography();

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])
	

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//--------------------------------------------------------------QUERY---------------------------------------------------------
function fun_common($tcode, $code){
		foreach ($code as $key) {
			if($key['code'] == $tcode){
				return $key['description'];
			}
		}
	}
function addzero($val){
	if(strlen($val) == 1){
		return '0'.$val;
	}
	else {
		return $val;
	}
}

$db = new database();
if($crypto->decode($_REQUEST['id'], 4) == "" || $crypto->decode($_REQUEST['id'], 4) == NULL)
{ ?>
    <div class="ui-state-error ui-corner-all">
    	<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> Wrong Data inserted</p>
    </div>
<?php		
} 
else 
{
	
////////////////////////////////////////////////////////////////////////////////////////// STOP SALARY /////////////////////////////////////////////////////////////////////////////////////////////

	
	if($_REQUEST['flag'] == 'stop')
	{

		if($_REQUEST['reason_date']=="")
		{
			$reason_date="0001-01-01";
		}
		else
		{
			$reason_date=date("Y-m-d", strtotime($_REQUEST['reason_date']));
			$next_month = date("Y-m-01", strtotime("$reason_date +1 month")); 
		}
		
		if(!$validator->blank_select($_POST['reason']) )
		{
			echo '<div class="alert alert-danger" style="text-align:center"><strong>Please choose reason!!.</strong></div>';exit;
		}
		
		if($_POST['reason']=='1990')
		{ 
			if(!$validator->blank_select($_POST['trs_lvl_select']) )
			{
				echo '<div class="alert alert-danger" style="text-align:center"><strong>Please select transferred level</strong></div>';
				exit;
			}
			
			if($_POST['trs_lvl_select']=='555')
			{ 
				if(!$validator->blank_select($_POST['transfer_district']) )
				{
					echo '<div class="alert alert-danger" style="text-align:center"><strong>Please select the District where the employee will be transferred.</strong></div>';
					exit;
				}
				
				if(!$validator->blank_select($_POST['transfer_block']) )
				{
					echo '<div class="alert alert-danger" style="text-align:center"><strong>Please select the Block where the employee will be transferred.</strong></div>';
					exit;
				}
				
				if(!$validator->blank_select($_POST['transfer_gp']) )
				{
					echo '<div class="alert alert-danger" style="text-align:center"><strong>Please select the GP where the employee will be transferred.</strong></div>';
					exit;
				}
				$new_gp_id=$_POST['transfer_gp'];
				$new_block_id=$_POST['transfer_block'];
				$new_district_id=$_POST['transfer_district'];
				$new_ps_id='0';
			}
			else if($_POST['trs_lvl_select']=='556')
			{
				if(!$validator->blank_select($_POST['transfer_district_ps']) )
				{
					echo '<div class="alert alert-danger" style="text-align:center"><strong>Please select the District where the employee will be transferred.</strong></div>';
					exit;
				}
				
				if(!$validator->blank_select($_POST['transfer_ps']) )
				{
					echo '<div class="alert alert-danger" style="text-align:center"><strong>Please select the Block where the employee will be transferred.</strong></div>';
					exit;
				}
				$new_gp_id='0';
				$new_block_id='0';
				$new_ps_id=$_POST['transfer_ps'];
				$new_district_id=$_POST['transfer_district_ps'];
			}
			else
			{
				$new_gp_id='0';
				$new_block_id='0';
				$new_district_id='0';
				$new_ps_id='0';
			}
		
		
		}
		if( $_POST['reason']=='1990' )
		{
			
									$transfer_emp=$db->	insert("
									INSERT INTO
									prd_employee_transfer(
										gp_id_fk,
										emp_first_name,
										emp_second_name,
										emp_last_name,
										emp_dob,
										emp_sex,emp_caste,
										emp_voter_id,
										emp_aadhar_no,
										emp_edu_quali,
										emp_desig,
										emp_first_join_date,
										emp_conf_join_date,
										emp_join_prsnt_post_date,
										emp_join_prsnt_office_date,
										emp_retirement_date,
										emp_termination_date,
										emp_status_deputation,
										emp_group,
										emp_desig_first_app,
										emp_next_increment_date,
										emp_next_increment_amount,
										emp_cosolidated_pay,
										emp_pay_band,
										emp_pay_in_payband,
										emp_pay_scale,
										emp_bank_name,
										emp_bank_branch,
										emp_branch_code,
										emp_micr_no,
										emp_acc_no,
										emp_ifsc_no,
										emp_father_name,
										emp_mother_name,
										emp_religion,
										emp_mother_tongue,
										emp_marital_status,
										emp_spouse_name,
										emp_spouse_job_status,
										emp_spouse_details,
										emp_spouse_pay,
										emp_spouse_hra,
										emp_spouse_res,
										emp_spouse_house_schm,
										emp_pan_no,
										emp_blood_grp,
										emp_height,
										emp_diff_able,
										emp_disable_status,
										emp_idf_mark,
										pre_state,
										emp_pre_house_no,
										emp_pre_street_no,
										emp_pre_vill,
										emp_pre_post,
										emp_pre_pin,
										emp_pre_dist,
										emp_pre_dist_others,
										per_state,
										emp_per_house_no,
										emp_per_street_no,
										emp_per_vill,
										emp_per_post,
										emp_per_pin,
										emp_per_dist,
										emp_per_dist_others,
										emp_land_no,
										emp_mobile_no,
										emp_mail_id,
										emp_system_code,
										emp_form_status,
										emp_status,
										entry_time,
										entry_ip,
										empcd,
										emp_grade_pay,
										emp_id_const,
										bank_upd_status,
										bank_msg_flag,
										conf_dt_flag,
										interim_relief,
										spouse_medical_allowance,
										conv_allow_status,
										transfer_emp_status,
										emp_id_fk,
										transfer_district_id_fk,
										transfer_block_id_fk,
										transfer_gp_id_fk,
										transfer_date,
										transfer_remarks,
										transfer_alert_date,
										transfer_level,
										ps_id_fk,
										transfer_ps_id_fk
									)
																
									SELECT 
										gp_id_fk,
										emp_first_name,
										emp_second_name,
										emp_last_name,
										emp_dob,
										emp_sex,
										emp_caste,
										emp_voter_id,
										emp_aadhar_no,
										emp_edu_quali,
										emp_desig,
										emp_first_join_date,
										emp_conf_join_date,
										emp_join_prsnt_post_date,
										emp_join_prsnt_office_date,
										emp_retirement_date,
										emp_termination_date,
										emp_status_deputation,
										emp_group,
										emp_desig_first_app,
										emp_next_increment_date,
										emp_next_increment_amount,
										emp_cosolidated_pay,
										emp_pay_band,
										emp_pay_in_payband,
										emp_pay_scale,
										emp_bank_name,
										emp_bank_branch,
										emp_branch_code,
										emp_micr_no,
										emp_acc_no,
										emp_ifsc_no,
										emp_father_name,
										emp_mother_name,
										emp_religion,
										emp_mother_tongue,
										emp_marital_status,
										emp_spouse_name,emp_spouse_job_status,
										emp_spouse_details,
										emp_spouse_pay,
										emp_spouse_hra,
										emp_spouse_res,
										emp_spouse_house_schm,
										emp_pan_no,
										emp_blood_grp,
										emp_height,
										emp_diff_able,
										emp_disable_status,
										emp_idf_mark,
										pre_state,
										emp_pre_house_no,
										emp_pre_street_no,
										emp_pre_vill,
										emp_pre_post,
										emp_pre_pin,
										emp_pre_dist,
										emp_pre_dist_others,
										per_state,
										emp_per_house_no,
										emp_per_street_no,
										emp_per_vill,
										emp_per_post,
										emp_per_pin,
										emp_per_dist,
										emp_per_dist_others,
										emp_land_no,
										emp_mobile_no,
										emp_mail_id,
										emp_system_code,
										emp_form_status,
										emp_status,
										entry_time,
										entry_ip,
										empcd,
										emp_grade_pay,
										emp_id_const,
										bank_upd_status,
										bank_msg_flag,
										conf_dt_flag,
										interim_relief,
										spouse_medical_allowance,
										conv_allow_status,
										'0',
										'".$crypto->decode($_REQUEST['id'], 4)."',
										'".$new_district_id."',
										'".$new_block_id."',
										'".$new_gp_id."',
										'".$reason_date."',
										'".$_REQUEST['reason_sus']."',
										'".$next_month."',
										'".$_REQUEST['trs_lvl_select']."',
										'".$_SESSION['location']['ps_id']."',
										'".$new_ps_id."'    
										from prd_employee_master where emp_id_pk='".$crypto->decode($_REQUEST['id'], 4)."'
									");
									
	
		}
		
		if( $transfer_emp )
		{
			
			$security = $db->insert("
									INSERT INTO
									prd_salary_log (
									ip,
									date,
									browser,
									os,
									salary_status_id_fk,
									created_by,
									created_by_stake,
									gp_id_fk,
									emp_id_fk,
									ps_id_fk
									
									)
									VALUES 			(
									'".$_SESSION['user_agent']['USER_IP']."',
									now(),
									'".$_SESSION['user_agent']['BROWSER']."',
									'".$_SESSION['user_agent']['OS']."',
									2,
									'".$_SESSION['user_info']['stake_user']."',
									'".$_SESSION['user_info']['stake_level']."',
									'0',
									".$crypto->decode($_REQUEST['id'], 4).",
									'".$_SESSION['location']['ps_id']."'
									)
									");	
			
			if($security)
			{
				echo '<div class="alert alert-success" style="text-align:center"><strong>Employee has been transferred successfully.</strong></div>';exit;
			} 
			else 
			{
				echo '<div class="alert alert-danger" style="text-align:center"><strong>Employee transfer fails.</strong></div>';exit;
			}
		} 
		else 
		{
			echo '<div class="alert alert-danger" style="text-align:center"><strong>Please select reason!!.</strong></div>';exit;
		}

	} 
	
	
	
////////////////////////////////////////////////////////////////////////////////////////// START SALARY /////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	elseif ($_REQUEST['flag'] == 'start') 
	{
		$transfer_start = $db->update("
										UPDATE prd_employee_transfer
										SET
										transfer_emp_status = 2
										WHERE transfer_emp_status in('0','1') AND emp_id_fk = ". $crypto->decode($_REQUEST['id'],4).";
		");
		
		
		
		
		
		$security = $db->insert("
									INSERT INTO
									prd_salary_log 
									(
										ip,
										date,
										browser,
										os,
										salary_status_id_fk,
										created_by,
										created_by_stake,
										gp_id_fk,
										emp_id_fk,
										ps_id_fk
									)
									VALUES 
									(
										'".$_SESSION['user_agent']['USER_IP']."',
										now(),
										'".$_SESSION['user_agent']['BROWSER']."',
										'".$_SESSION['user_agent']['OS']."',
										1,
										'".$_SESSION['user_info']['stake_user']."',
										'".$_SESSION['user_info']['stake_level']."',
										'0',
										". $crypto->decode($_REQUEST['id'], 4).",
										'".$_SESSION['location']['ps_id']."'
									);
								");	
		
		if( $transfer_start)
		{
			echo '<div class="alert alert-success" style="text-align:center"><strong>Employee has been revoked successfully!!.</strong></div>';exit;
		} 
		else 
		{
			echo '<div class="alert alert-danger" style="text-align:center"><strong>Employee revoke error!!.</strong></div>';exit;
		}
	} 
	else 
	{
		echo '<div class="alert alert-danger" style="text-align:center"><strong>Wrong Data inserted!!!!.</strong></div>';exit;
	}
		
}
