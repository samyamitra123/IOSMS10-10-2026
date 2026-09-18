<?php

session_start();
error_reporting(0);

//ob_start();
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
require_once '../all_function/fun_store/ifms_functions.php';
//print_r($_SERVER['HTTP_REFERER']); exit;
if($_SERVER['HTTP_REFERER']==''){
	header("Location:dashboard_intra_pri.php");
}
//$cryptoGraph=new cryptography();
$crypto=new cryptography();

//$form_flage=$crypto->decode($_POST['form_flage'],4); 
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
  $age_condon_year = rtrim(str_replace('Years','',$_POST['age_condon_year']));
  $age_condon_month = rtrim(str_replace('Months','',$_POST['age_condon_month'])); 
  $age_condon_days = rtrim(str_replace('Days','',$_POST['age_condon_days']));
  $emp_id_const_del = $_POST['emp_id_const'];
  //$cg_id = $crypto->decode($_POST['cg_id'],4); 
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

  //print_r($_POST); 
  $Query = "SELECT overage_condonation_pk from intra_pri_overage_condonation_master WHERE emp_id_const ='".$emp_id_const."'";
  $ocid = $db->fetch_table($Query);
  //print_r($ocid); exit;
  if(count($ocid) > 0)
  {
     $QueryArg = array (
												"emp_first_name='".$tch_fname."'",
												"emp_second_name='".$tch_mname."'",
												"emp_last_name='".$tch_lname."'",
												"emp_dob='".$tch_dob."'",
												"emp_sex='".$drpSex."'",
												"emp_desig='".$vice_desig."'",
												"emp_first_join_date='".$Date_of_first_Posting."'",
												"appoinment_authority='".$Appointment_Authorization."'",
												"notice_date='".$date_of_notification_employee_notice."'",
												"court_case='".$court_case."'",
												"court_case_details='".$case_details."'",
												"condon_year='".$age_condon_year."'",
												"condon_month='".$age_condon_month."'",
												"condon_days='".$age_condon_days."'",
											);
     $QueryArg = 'UPDATE intra_pri_overage_condonation_master SET '.implode(', ', $QueryArg).' WHERE overage_condonation_pk='.$ocid[0]['overage_condonation_pk'];
     $insert_query = $db->update($QueryArg);
     //print_r($QueryArg); exit;
  }
  else
  {
  	$Query = "INSERT INTO intra_pri_overage_condonation_master
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
												'".$first_GP_or_PS_posted_id."', '".$age_condon_year."', '".$age_condon_month."', '".$age_condon_days."', '".$catagory_id."')";
          //print_r($Query); exit;
					$insert_query = $db->insert($Query);
										
						if($insert_query){
							$insert_request=$db->insert("INSERT INTO intra_pri_application_master (application_id, service_type) VALUES ('".$application_id."', '4')");
						}	
						//print_r($insert_request); exit;

  }	


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


