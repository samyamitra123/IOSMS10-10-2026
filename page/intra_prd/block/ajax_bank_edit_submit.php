<?
ob_start();
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';

/*error_reporting(0);
echo "<pre>";
print_r($_POST);
echo "</pre>";*/
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
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
	$gp_id=$_REQUEST['gp_id'];
	$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
    exit(0);
}
else{
$gp_id=$_REQUEST['gp_id'];
$emp_id_pk=$_REQUEST['emp_id_pk'];
$txt_bank=$_REQUEST['txt_bank'];
$tch_bank_branch=$_REQUEST['tch_bank_branch']==''?'':$_REQUEST['tch_bank_branch'];
$tch_branch_code=$_REQUEST['tch_branch_code']==''?'':$_REQUEST['tch_branch_code'];
$tch_micr_code=$_REQUEST['tch_micr_code']==''?0:$_REQUEST['tch_micr_code'];
$txt_account=$_REQUEST['txt_account'];
$txt_ifsc=$_REQUEST['txt_ifsc'];
$db = new database();
			$code_data = $db->fetch_table("
											SELECT code, description
											FROM prd_dise_code_master;
	
										");
			$bank_data = $db->fetch_table("
											SELECT bank_code
											FROM prd_dise_bank_master;
	
										");
			
		   
		   $acc_no_check=$db->fetch_table("select emp_acc_no from prd_employee_master where emp_status in(1,6,10) and 
		   emp_acc_no !=(select emp_acc_no from prd_employee_master where emp_status in(1,6,10) and emp_id_pk='$emp_id_pk' 
		   and gp_id_fk='".$gp_id."')");											
			

 if($validator->blank_select($txt_account) == TRUE)
			{
				foreach($acc_no_check as $key){
					if($txt_account == $key['emp_acc_no'])
					{
						$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Account number already exists. Please Enter valid account number.</strong></div>';
					header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
				    exit(0);
					}
				
				}
			}
	
			if($validator->blank_select($txt_bank) == FALSE)
			{
			$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Bank Name.</strong></div>';
			header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
		    exit(0);
			}
			else if($validator->blank_select($txt_account) == FALSE)
			{
			$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Account Number.</strong></div>';
			header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
		    exit(0);;
			}
			else if($validator->blank_select($txt_ifsc) == FALSE)
			{
			$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter IFSC Number.</strong></div>';
			header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
		    exit(0);
			}
			else if($validator->valid_bank($txt_bank,$bank_data) == FALSE)
			{
			$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Valid Bank Name.</strong></div>';
			header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
		    exit(0);
			}
			else if($validator->pattern_number($txt_account) == FALSE)
			{
				$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Account No.</strong></div>';
				header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
		    	exit(0);
			}
			else if($validator->pattern_match_alphanumeric($tch_branch_code) == FALSE)
			{
				$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Branch Code.</strong></div>';
				header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
		   		exit(0);
			}
			else if($validator->pattern_match_alphanumeric($txt_ifsc) == FALSE)
			{
				$_SESSION['bank_msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid IFSC.</strong></div>';
				header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
		    	exit(0);
			}
			else{

				$db = new database();	
				$query_archive=$db->insert("Insert into prd_employee_master_archive(emp_id_pk, gp_id_fk, emp_first_name, emp_second_name, emp_last_name, 
       emp_dob, emp_sex, emp_caste, emp_voter_id, emp_aadhar_no, emp_edu_quali, 
       emp_desig, emp_first_join_date, emp_conf_join_date, emp_join_prsnt_post_date, 
       emp_join_prsnt_office_date, emp_retirement_date, emp_termination_date, 
       emp_status_deputation, emp_group, emp_desig_first_app, emp_next_increment_date, 
       emp_next_increment_amount, emp_cosolidated_pay, emp_pay_band, 
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
       bank_upd_status) SELECT emp_id_pk, gp_id_fk, emp_first_name, emp_second_name, emp_last_name, 
										   emp_dob, emp_sex, emp_caste, emp_voter_id, emp_aadhar_no, emp_edu_quali, 
										   emp_desig, emp_first_join_date, emp_conf_join_date, emp_join_prsnt_post_date, 
										   emp_join_prsnt_office_date, emp_retirement_date, emp_termination_date, 
										   emp_status_deputation, emp_group, emp_desig_first_app, emp_next_increment_date, 
										   emp_next_increment_amount, emp_cosolidated_pay, emp_pay_band, 
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
										   emp_status, entry_time, entry_ip, empcd, emp_grade_pay, emp_id_const,bank_upd_status
  										   FROM prd_employee_master where emp_id_pk='$emp_id_pk'");											 
				$query_insert=$db->update("UPDATE prd_employee_master set
											emp_bank_name='$txt_bank',
											emp_bank_branch='$tch_bank_branch',
											emp_branch_code='$tch_branch_code',
											emp_micr_no='$tch_micr_code',
											emp_acc_no='$txt_account',
											emp_ifsc_no='$txt_ifsc',
											bank_upd_status='0',
											bank_msg_flag=1,
											entry_time='now()',
											entry_ip='".$_SERVER['REMOTE_ADDR']."'
										 WHERE
											 emp_id_pk='$emp_id_pk'");
				
				if($query_insert){
				$_SESSION['bank_msg']='<div class="alert alert-success" style="text-align:center"><strong>Bank Details Updated Successfully...</strong></div>';	
				header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
				exit(0);
				}
				else{
				$_SESSION['bank_msg']='<div class="alert alert-success" style="text-align:center"><strong>Bank Details Updation Fails...</strong></div>';	
				header('Location:update_bank_details.php?gp_id='.$cryptoGraph->encode($gp_id,4));
				exit(0);
				}
}
}
?>