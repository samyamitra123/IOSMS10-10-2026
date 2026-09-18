<?php

session_start();
error_reporting(0);

ob_start();
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
require_once '../all_function/fun_store/ifms_functions.php';

if($_SERVER['HTTP_REFERER']==''){
	header("Location:dashboard_intra_pri.php");
}
//$cryptoGraph=new cryptography();
$crypto=new cryptography();

$form_flage=$crypto->decode($_POST['form_flage'],4); 
  $emp_id_const = $_POST['tch_emp_id'];
  $tch_fname = $_POST['tch_fname'];
  $tch_mname = $_POST['tch_mname'];
  $tch_lname =  $_POST['tch_lname'];
  $tch_dob = date('Y-m-d',strtotime($_POST['tch_dob']));
  $drpSex = $_POST['drpSex'];
  $vice_desig = $_POST['vice_desig'];
  $Name_of_GP_or_PS_posted_id = $_POST['Name_of_GP_or_PS_posted_id'];
  $Appointment_MEMO = $_POST['Appointment_MEMO'];
  $Date_of_first_Posting= date('Y-m-d',strtotime($_POST['Date_of_first_Posting']));
  $gp_ps_zp_identity= $_POST['gp_ps_zp_identity'];
  $Appointment_Authorization= $_POST['Appointment_Authorization'];
  $date_of_notification_employee_notice= date('Y-m-d',strtotime($_POST['date_of_notification_employee_notice']));
  $court_case= $_POST['court_case'];
  $case_details= $_POST['case_details'];
  $proposal_id= $_POST['proposal_id'];
  $case_history = $_POST['case_history'];
  $observation = $_POST['observation'];
  $first_vice_desig = $_POST['first_vice_desig'];
  $first_GP_or_PS_posted = $_POST['first_GP_or_PS_posted'];
  $first_gp_ps_zp_identity = $_POST['first_gp_ps_zp_identity'];
  $first_GP_or_PS_posted_id = $_POST['first_GP_or_PS_posted_id'];
  $catagory_id = $_POST['catagory_id'];
  $age_condon_year = $_POST['age_condon_year'];
  $age_condon_month = $_POST['age_condon_month'];
  $age_condon_days = $_POST['age_condon_days'];
  $emp_id_const_del = $_POST['emp_id_const'];
  $cg_id = $crypto->decode($_POST['cg_id'],4); 
  $delete_id=$crypto->decode($_POST['delete_id'],4); 
  $delete_f=$crypto->decode($_POST['delete_f'],4); 
  $flag="OAC";
	$application_id = application_id($flag,$emp_id_const);

	if($gp_ps_zp_identity == "GP"){
		$gp_id_fk = $Name_of_GP_or_PS_posted_id;
		$ps_id_fk = 0;
		$zp_id_fk = 0;
	}
	else if($gp_ps_zp_identity == "PS"){
		$gp_id_fk =  0 ;
		$ps_id_fk = $Name_of_GP_or_PS_posted_id;
		$zp_id_fk = 0;
	}
	else if($gp_ps_zp_identity == "ZP"){
		$gp_id_fk =  0 ;
		$ps_id_fk = 0;
		$zp_id_fk = $Name_of_GP_or_PS_posted_id;
	}
  //var_dump($emp_id_const); die;
$db=new database();
/*
$application_id_check=$db->fetch_table("SELECT application_id FROM intra_pri_overage_condonation_master WHERE emp_id_const = '".$emp_id_const."' AND active_status= '1' "); */

	if($form_flage=="partA")
	 {
								
	$insert_query = $db->insert("INSERT INTO intra_pri_overage_condonation_master
						(
							emp_id_const,
							emp_first_name,
							emp_second_name,
							emp_last_name,
							emp_dob,
							emp_sex,
							emp_desig,
							gp_id_fk,
							ps_id_fk,
							zp_id_fk,
							emp_first_memo_no,emp_desig_first_app,
							emp_first_join_date, proposal, appoinment_authority, notice_date, court_case, court_case_details, application_id,
							emp_first_gp_ps_zp_code, condon_year, condon_month, condon_days, emp_caste
							)
							VALUES
								('".$emp_id_const."',
								'".$tch_fname."',
								'".$tch_mname."',
								'".$tch_lname."',
								'".$tch_dob."',
								'".$drpSex."',
								'".$vice_desig."',
								'".$gp_id_fk."',
								'".$ps_id_fk."',
								'".$zp_id_fk."',
								'".$Appointment_MEMO."','".$first_vice_desig."',
								'".$Date_of_first_Posting."', '".$proposal_id."', '".$Appointment_Authorization."',
								'".$date_of_notification_employee_notice."', '".$court_case."', '".$case_details."', '".$application_id."',
								'".$first_GP_or_PS_posted_id."', '".$age_condon_year."', '".$age_condon_month."', '".$age_condon_days."', '".$catagory_id."')");
								
		if($insert_query){
			$insert_request=$db->insert("INSERT INTO intra_pri_application_master (application_id, service_type) VALUES ('".$application_id."', '4')");
		}						
	//$insert_pk = $db->fetch_table(" SELECT overage_condonation_pk FROM intra_pri_overage_condonation_master WHERE emp_id_const = '".$emp_id_const."'");							
	//$_SESSION['user_info']['emp_id_const'] = $emp_id_const;
	
//echo json_encode(array($insert_pk[0]['overage_condonation_pk'], $insert_query));

$_SESSION['user_info']['emp_id_const'] = $emp_id_const;
if($insert_query)
		{
			
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Submitted Successfully...</strong></div>';
			header('Location:intre_pri_overage_condonation_form.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Profile Submitted failed...</strong></div>';
			header('Location:intre_pri_overage_condonation_form.php');
		}
	 }
else if($form_flage=="partB"){
		 
		

	$update_oc = $db->update(" UPDATE intra_pri_overage_condonation_master SET
								case_history = '".$case_history."' , observation= '".$observation."'
								WHERE emp_id_const ='".$emp_id_const."' AND overage_condonation_pk = '".$cg_id."'");
			$_SESSION['user_info']['emp_id_const'] = $emp_id_const;					
	if($update_oc)
		{
			
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Profile Updated Successfully...</strong></div>';
			header('Location:intre_pri_overage_condonation_form.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Profile Updated failed...</strong></div>';
			header('Location:intre_pri_overage_condonation_form.php');
		}
								
	 }
	 
	 
	 
	 if($delete_id=="delete")
			{	
			$query=$db->update("UPDATE intra_pri_file_upload SET status='0' WHERE emp_id_const='".$emp_id_const_del."' and flag='".$delete_f."' and status='1'");
			$_SESSION['user_info']['emp_id_const'] = $emp_id_const_del;
			
				if($query==true)
				{
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>DOCUMENT SUCCESSFULLY REVOMED </strong></div>';
					header('Location:intre_pri_overage_condonation_form.php');
				}
				else
				{
					$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> DOCUMENT REMOVED FAILED.</strong></div>';
					header('Location:intre_pri_overage_condonation_form.php');
				}
			}
?>


