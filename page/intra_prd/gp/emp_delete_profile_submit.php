<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';

$db = new database();
$gp_id=$db->fetch_table("select gp_code from prd_location_master_gp where gp_id_pk='".$_SESSION['location']['gp_id']."'");

 $gp_code=$gp_id['0']['gp_code'];



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
//print_r($_POST);exit;

	$db = new database();
	
	$reason=$_POST['reason'];
	$emp_id_pk=$_POST['emp_id_pk']; 
	$status=$_POST['status'];
	
	 
	/*if($validator->blank_select(trim($block_code))==FALSE || $validator->pattern_number(trim($block_code))==FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Invalid block code.</strong></div>';	
	    header('location:show_block_list.php') ;
		exit(0);
	}
	else if($validator->blank_select(trim($block_id_fk))==FALSE || $validator->pattern_number(trim($block_id_fk))==FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Invalid block code.</strong></div>';	
	    header('location:show_block_list.php') ;
		exit(0);
	}*/
	/* if($validator->blank_select(trim($emp_id_pk))==FALSE || $validator->pattern_number(trim($emp_id_pk))==FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Invalid employee Id.</strong></div>';	
	    header('location:show_block_list.php') ;
		exit(0);
	}*/
	if($status==1)
	{
	$delete_reason=trim($reason);
	if($validator->blank_select($delete_reason)==FALSE)
	{
		
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Please enter reason for Delete.</strong></div>';	
	  header('location:employee_edit_details.php') ;
		exit(0);
	}
	}
		
		
		
	
//$update=$db->update("UPDATE prd_employee_master SET emp_status=11 where emp_id_pk='".$_REQUEST['id']."' AND gp_id_fk='".$_SESSION['location']['gp_id']."'");
	

		
		
if($status==1)
{	

 $prd_employee_master_archive_delete=$db->insert("INSERT INTO  prd_employee_master_archive_delete (emp_id_pk,
  gp_id_fk,
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
  delete_stake_user )
  
SELECT emp_id_pk ,
  gp_id_fk,
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
  '$reason',
  '$gp_code'

FROM prd_employee_master
where emp_id_pk='$emp_id_pk' AND gp_id_fk='".$_SESSION['location']['gp_id']."'");


	if($prd_employee_master_archive_delete){
		
			$delete= $db->update("delete from prd_employee_master where emp_id_pk='$emp_id_pk' AND gp_id_fk='".$_SESSION['location']['gp_id']."'");
			
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center;"><strong>Employee profile has been  successfully Delete.</strong></div>';	
			header('location:employee_edit_details.php') ;
			exit(0);	
	}
	else{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Delete failed.</strong></div>';	
		header('location:employee_edit_details.php') ;
		exit(0);
	}
	}
	
	
	
	/*else if($status==0)
	{
		$update=$db->update("UPDATE prd_employee_master SET emp_status=11  where emp_id_pk='$emp_id_pk' AND gp_id_fk='".$_SESSION['location']['gp_id']."'");
		$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center;"><strong>Employee Profile has been successfully Send for Delete.</strong></div>';	
			header('location:emp_edit_details.php') ;
			exit(0);	
		
	}
	else{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Delete failed.</strong></div>';	
		header('location:emp_edit_details.php') ;
		exit(0);
	}*/
?>