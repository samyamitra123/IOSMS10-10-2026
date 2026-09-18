


<?php


session_start();
ob_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
require_once '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
require '../../includes/library/database.class.php';
require_once '../../includes/library/cryptography.class.php';
require_once '../../includes/library/myvalidation.class.php';

require_once '../all_function/fun_store/ifms_functions.php';
//require '../../../page_visite.php';

 $sec_time_token=$_POST['sec_tok']; 
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
$crypto=new cryptography();
$db=new database();

if($sec_time_token!=$enc_session)
{
	
	
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	header('Location:intra.php');
}
else
{
 $form_flage=$crypto->decode($_POST['form_flage'],4);
	
	 $edit_status=$crypto->decode($_POST['edit_id'],4);  
	 
	 $delete_id=$crypto->decode($_POST['delete_id'],4); 
	 
	 $delete_f=$crypto->decode($_POST['delete_f'],4); 
	 
	 $emp_id_const=$_POST['search1']; 
	 
	 $emp_id_cons_st=$_SESSION['emp_id_const'];
		
		
		$application_id_check=$db->fetch_table("SELECT application_id,application_id_pk FROM intra_pri_cg_profile_master WHERE emp_id_const = '".$emp_id_cons_st."'");
		
		$app_id=$application_id_check[0]['application_id'];
		
		/*$employee_check=$db->fetch_table("SELECT application_id_pk FROM intra_pri_cg_profile_master WHERE emp_id_const = '".$emp_id_cons_st."'");*/
	 
	 if($form_flage=="partA")
	 {
		// echo 23; die;
		$emp_id_const=$_POST['search1'];
		$tch_fname=strtoupper($_POST['tch_fname']);
		$tch_mname=strtoupper($_POST['tch_mname']);
		$tch_lname=strtoupper($_POST['tch_lname']);
		$vice_desig=$_POST['vice_desig'];
		$first_join_date=date("Y-m-d",strtotime($_POST['first_join_date']));
		$group=$_POST['group']; 
		 $gp_id_fk=$_POST['gp_id_fk'];
		$ps_id_fk=$_POST['ps_id_fk'];
		$zp_id_fk=$_POST['zp_id_fk'];
		//$group='634';
		
		if($_POST['gp_id_fk']!='0' && $_POST['gp_id_fk']!='' )
		{
			 $gp_id_fk=$_POST['gp_id_fk']; 
			$ps_id_fk=0;
			$zp_id_fk=0;
		}
		else if($_POST['ps_id_fk']!='0' && $_POST['ps_id_fk']!='')
		{
			$gp_id_fk=0; 
			$ps_id_fk=$_POST['ps_id_fk'];
			$zp_id_fk=0;
		}
		else if($_POST['zp_id_fk']!='0' && $_POST['zp_id_fk']!='')
		{
			$gp_id_fk=0; 
			$ps_id_fk=0;
			$zp_id_fk=$_POST['zp_id_fk'];
		}
		
		
		
		$last_pay_drawn=$_POST['last_pay_drawn'];
		$nomine_details=$_POST['nomine_details'];
		
		 $employee_type=$_POST['employee_type']; 
		
		if($employee_type=='1993')
		{
		$death_date=date("Y-m-d",strtotime($_POST['Death_date']));
		}
		else
		{
			$death_date=date("Y-m-d",strtotime($_POST['premature_date']));
		}
		$family_name=$_POST['family_name']; 
		$family_age=$_POST['family_age']; 
		$family_quali=$_POST['family_quali']; 
		$family_relation=$_POST['family_relation'];
		$proposal=$_POST['proposal'];
		
		 $pk=$_POST['id_pk'];
		// var_dump($family_name); die;
		 // var_dump($family_age); 
		 
		  /************************************* Family Detail Insert *******************************************/
		
		  
		if(count($application_id_check)=='1')
		{
			
			//echo 111; die;
			$total_no_of_relation=count($family_name);
			
			 for($i=0; $i<$total_no_of_relation; $i++){ 
			
		
			$query=$db->update("UPDATE intra_pri_cg_relation_master_submit SET
												family_name='".$family_name[$i]."',
												family_age='".$family_age[$i]."',
												family_quali='".$family_quali[$i]."',
												family_relation='".$family_relation[$i]."', 
												status='1'
												WHERE  employee_id='".$emp_id_cons_st."' and id_pk='".$pk[$i]."'");
			 }
			
			
			
			$query=$db->update("UPDATE intra_pri_cg_profile_master SET
												emp_first_name='".$tch_fname."',
												emp_second_name='".$tch_mname."',
												emp_last_name='".$tch_lname."',
												emp_desig='".$vice_desig."', 
												emp_first_join_date='".$first_join_date."',
												emp_group='".$group."' ,
												death_date='".$death_date."' ,
												last_pay_drawn='".$last_pay_drawn."' ,
												nomine_details='".$nomine_details."' ,
												part_a_from_status='1' ,
												gp_id_fk='".$gp_id_fk."' ,
												ps_id_fk='".$ps_id_fk."' ,
												zp_id_fk='".$zp_id_fk."',
												employee_type='".$employee_type."'
												
												WHERE  emp_id_const='".$emp_id_cons_st."'");
		}
		else
		{
			 $total_no_of_relation=count($family_name);
		  
		  for($i=0; $i<$total_no_of_relation; $i++){ 
		  
										
			$family_insert=$db->insert("INSERT INTO intra_pri_cg_relation_master_submit
								(
									family_name,
									family_age,
									family_quali,
									family_relation,
									employee_id,
									status
									)
									VALUES 
										('".$family_name[$i]."',
										'".$family_age[$i]."',
										'".$family_quali[$i]."',
										'".$family_relation[$i]."',
										'".$emp_id_const."',
										'1'
										)");
		  }
			
			
			/*echo ("INSERT INTO intra_pri_cg_profile_master
								(
									emp_id_const,
									emp_first_name,
									emp_second_name,
									emp_last_name,
									emp_desig,
									emp_first_join_date,
									emp_group,
									last_pay_drawn,
									death_date,
									nomine_details,
									part_a_from_status,
									gp_id_fk,
									ps_id_fk,
									zp_id_fk,
									proposal
									)
									VALUES 
										('".$emp_id_const."',
										'".$tch_fname."',
										'".$tch_mname."',
										'".$tch_lname."',
										'".$vice_desig."',
										'".$first_join_date."',
										'".$group."',
										'".$last_pay_drawn."',
										'".$death_date."',
										'".$nomine_details."',
										'1',
										'".$gp_id_fk."',
										'".$ps_id_fk."',
										'".$zp_id_fk."',
										'".$proposal."'
										)");die;*/
		  /*************************************************************************************************************/
		  
		  
		 	
		  $query=$db->insert("INSERT INTO intra_pri_cg_profile_master
								(
									emp_id_const,
									emp_first_name,
									emp_second_name,
									emp_last_name,
									emp_desig,
									emp_first_join_date,
									emp_group,
									last_pay_drawn,
									death_date,
									nomine_details,
									part_a_from_status,
									gp_id_fk,
									ps_id_fk,
									zp_id_fk,
									proposal,
									employee_type
									)
									VALUES 
										('".$emp_id_const."',
										'".$tch_fname."',
										'".$tch_mname."',
										'".$tch_lname."',
										'".$vice_desig."',
										'".$first_join_date."',
										'".$group."',
										'".$last_pay_drawn."',
										'".$death_date."',
										'".$nomine_details."',
										'1',
										'".$gp_id_fk."',
										'".$ps_id_fk."',
										'".$zp_id_fk."',
										'".$proposal."',
										'".$employee_type."'
										)");
		}
										
										
if($query)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>PART A  Profile Submitted Successfully...</strong></div>';
			header('Location:intra.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>PART A Profile Submitted failed...</strong></div>';
			header('Location:intra.php');
		}


	 }
	 
			if($edit_status=="partA_edit")
			{
			$query=$db->update("UPDATE intra_pri_cg_profile_master SET 
			part_a_from_status='0'
			WHERE 
			emp_id_const='".$_SESSION['emp_id_const']."'");
			
				if($query==true)
				{
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> PART A  Profile Now redy For Edit ...</strong></div>';
					header('Location:intra.php');
				}
				else
				{
					$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>PART A Profile Now Not redy For Edit ......</strong></div>';
					header('Location:intra.php');
				}
			
			
			}
	 
	 
	  if($form_flage=="partB")
	 {
		 
		 
		   //$emp_id_const=$_POST['search1']; 
		  $applicant_name=strtoupper($_POST['applicant_name']);
		   $applicant_relation=$_POST['relation_employee'];
		  
		  $applicant_birth=date("Y-m-d",strtotime($_POST['applicant_birth']));
		  $applicant_sex=$_POST['drpSex']; 
		   $nationality=$_POST['nationality'];
		  $applicant_mobile_no=$_POST['applicant_mobile_no'];
		   $religion=$_POST['religion']; 
		  //$group='634';
		  $applicant_quali=$_POST['applicant_quali'];
		  $applicant_cast=$_POST['applicant_cast'];
		  $differently_able=$_POST['differently_able']; 
		  
		  
		$present_address_state=trim($_POST['present_address_state']);
		$present_house_no=strtoupper(trim($_POST['present_house_no']));
		$present_street=strtoupper(trim($_POST['present_street']));
		$present_town_vill=strtoupper(trim($_POST['present_town_vill']));
		$present_post_office=strtoupper(trim($_POST['present_post_office']));
		$present_pin=trim($_POST['present_pin']);
		$present_police_station=strtoupper(trim($_POST['present_police_station']));
		
		$candidate_p_roforma=$_POST['candidate_p_roforma'];
		$candidate_p_roforma_remarks=$_POST['candidate_p_roforma_remarks'];
		$candidate_unfit_cer=$_POST['candidate_unfit_cer'];
		$candidate_unfit_cer_remarks=$_POST['candidate_unfit_cer_remarks'];
		$divorce_decree=$_POST['divorce_decree'];
		$divorce_decree_remarks=$_POST['divorce_decree_remarks'];
		
	 $application_date=date("Y-m-d",strtotime($_POST['application_date']));
		if($_POST['present_city_district']=='')
		{
		$present_city_district=0;
		}
		else
		{
		$present_city_district=trim($_POST['present_city_district']);
		}
		//$present_others_dist=strtoupper(trim($_POST['present_others_dist']));
		  
		
		  $query=$db->update("UPDATE intra_pri_cg_profile_master SET
												applicant_name='".$applicant_name."',
												relation_employee='".$applicant_relation."',
												applicant_birth='".$applicant_birth."',
												applicant_sex='".$applicant_sex."', 
												nationality='".$nationality."',
												applicant_mobile_no='".$applicant_mobile_no."' , 
												applicant_religion='".$religion."' ,
												applicant_quali='".$applicant_quali."' ,
												applicant_cast='".$applicant_cast."' ,
												differently_able='".$differently_able."' ,
												present_address_state='".$present_address_state."' ,
												present_house_no='".$present_house_no."' ,
												present_street='".$present_street."' ,
												present_town_vill='".$present_town_vill."' ,
												present_post_office='".$present_post_office."' ,
												present_pin='".$present_pin."' ,
												present_police_station='".$present_police_station."',
												present_district='".$present_city_district."',
												candidate_p_roforma='".$candidate_p_roforma."',
												candidate_p_roforma_remarks='".$candidate_p_roforma_remarks."',
												candidate_unfit_cer='".$candidate_unfit_cer."',
												candidate_unfit_cer_remarks='".$candidate_unfit_cer_remarks."',
												divorce_decree='".$divorce_decree."',
												divorce_decree_remarks='".$divorce_decree_remarks."',
												application_date='".$application_date."',
												part_b_from_status='1'
												WHERE  emp_id_const='".$_SESSION['emp_id_const']."'");
		  
		  
		 
										
										
if($query==true)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>PART B  Profile Submitted Successfully...</strong></div>';
			header('Location:intra.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>PART B Profile Submitted failed...</strong></div>';
			header('Location:intra.php');
		}


	 }
	 
	 
	 if($edit_status=="partB_edit")
			{
			$query=$db->update("UPDATE intra_pri_cg_profile_master SET 
			part_b_from_status='0'
			WHERE 
			emp_id_const='".$_SESSION['emp_id_const']."'");
			
				if($query==true)
				{
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> PART B  Profile Now redy For Edit ...</strong></div>';
					header('Location:intra.php');
				}
				else
				{
					$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>PART B Profile Now Not redy For Edit ......</strong></div>';
					header('Location:intra.php');
				}
			
			
			}
	 
	 
	   if($form_flage=="partC")
	 {
		 
		 
		 if($_POST['family_pension']==''){
			 
			 $family_pension=0;
		 }
		 else
		 {
			 $family_pension=trim($_POST['family_pension']); 
		 }
		 
		 if($_POST['death_gratuity']==''){
			 
			 $death_gratuity=0;
		 }
		 else
		 {
			 $death_gratuity=trim($_POST['death_gratuity']); 
		 }
		 
		  if($_POST['group_insurance']==''){
			 
			 $group_insurance=0;
		 }
		 else
		 {
			 $group_insurance=trim($_POST['group_insurance']); 
		 }
		 
		 if($_POST['encashment_leave']==''){
			 
			 $encashment_leave=0;
		 }
		 else
		 {
			 $encashment_leave=trim($_POST['encashment_leave']); 
		 }
		 
		 if($_POST['any_payment']==''){
			 
			 $any_payment=0;
		 }
		 else
		 {
			 $any_payment=trim($_POST['any_payment']); 
		 }
		 if($_POST['total_lumsum']==''){
			 
			 $total_lumsum=0;
		 }
		 else
		 {
			 $total_lumsum=trim($_POST['total_lumsum']); 
		 }
		 
		 
		 
		 
		 if($_POST['calculation_hospitalization']==''){
			 
			 $calculation_hospitalization=0;
		 }
		 else
		 {
			 $calculation_hospitalization=trim($_POST['calculation_hospitalization']); 
		 }
		 
		 if($_POST['caluculation_expen']==''){
			 
			 $caluculation_expen=0;
		 }
		 else
		 {
			 $caluculation_expen=trim($_POST['caluculation_expen']); 
		 }
		 if($_POST['interest_calculation']==''){
			 
			 $interest_calculation=0;
		 }
		 else
		 {
			 $interest_calculation=trim($_POST['interest_calculation']); 
		 }
		 
		 if($_POST['move_immovable']==''){
			 
			 $move_immovable=0;
		 }
		 else
		 {
			 $move_immovable=trim($_POST['move_immovable']); 
		 }
		 
		 if($_POST['income_dependant_employee']==''){
			 
			 $income_dependant_employee=0;
		 }
		 else
		 {
			 $income_dependant_employee=trim($_POST['income_dependant_employee']); 
		 }
		 if($_POST['total_income']==''){
			 
			 $total_income=0;
		 }
		 else
		 {
			 $total_income=trim($_POST['total_income']); 
		 }
		 if($_POST['percentage_monthly_income']==''){
			 
			 $percentage_monthly_income=0;
		 }
		 else
		 {
			 $percentage_monthly_income=trim($_POST['percentage_monthly_income']); 
		 }
		  
		 
		 
		  
		  
		  
		
		
		
		
		
		//$present_others_dist=strtoupper(trim($_POST['present_others_dist']));
		  
		  
		
		  $query=$db->update("UPDATE intra_pri_cg_profile_master SET
												family_pension='".$family_pension."',
												death_gratuity='".$death_gratuity."',
												group_insurance='".$group_insurance."',
												encashment_leave='".$encashment_leave."',
												any_payment='".$any_payment."',
												total_lumsum='".$total_lumsum."',
												calculation_hospitalization='".$calculation_hospitalization."',
												caluculation_expen='".$caluculation_expen."',
												interest_calculation='".$interest_calculation."',
												move_immovable='".$move_immovable."',
												income_dependant_employee='".$income_dependant_employee."',
												total_income='".$total_income."',
												percentage_monthly_income='".$percentage_monthly_income."',
												part_c_from_status='1'
												
												
												WHERE  emp_id_const='".$_SESSION['emp_id_const']."'");
		  
		  
		 
										
										
if($query==true)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>PART C  Profile Submitted Successfully...</strong></div>';
			header('Location:intra.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>PART C Profile Submitted failed...</strong></div>';
			header('Location:intra.php');
		}


	 }
	 
	 if($edit_status=="partC_edit")
			{
			$query=$db->update("UPDATE intra_pri_cg_profile_master SET 
			part_c_from_status='0'
			WHERE 
			emp_id_const='".$_SESSION['emp_id_const']."'");
			
				if($query==true)
				{
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> PART C  Profile Now redy For Edit ...</strong></div>';
					header('Location:intra.php');
				}
				else
				{
					$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>PART C Profile Now Not redy For Edit ......</strong></div>';
					header('Location:intra.php');
				}
			
			
			}
	 
	 
	 
	   if($form_flage=="partD")
	 {
		 
		 
		   //$emp_id_const=$_POST['search1']; 
		  
		//$present_others_dist=strtoupper(trim($_POST['present_others_dist']));
		$name_officer_first=strtoupper($_POST['name_officer_first']);
		$name_officer_second=strtoupper($_POST['name_officer_second']);
		$name_officer_third=strtoupper($_POST['name_officer_third']);
		$memo_no_ec=$_POST['memo_no_ec'];
		
		$memo_date_ec=date("Y-m-d",strtotime($_POST['memo_date_ec']));
		$date_inquery=date("Y-m-d",strtotime($_POST['date_inquery'])); 
		$comment_officer=$_POST['comment_officer'];
		$candidate_fulfil_rules=$_POST['candidate_fulfil_rules'];
		$candidate_fulfil_remarks=$_POST['candidate_fulfil_remarks']; 
		//$group='634';
		$clear_vacany_roster=$_POST['clear_vacany_roster'];
		$clear_vacany_roster_remarks=$_POST['clear_vacany_roster_remarks'];
		$candidate_fulfil_all=$_POST['candidate_fulfil_all']; 
		$candidate_fulfil_all_remarks=$_POST['candidate_fulfil_all_remarks'];
		$candidate_enquiry_recommedation=$_POST['candidate_enquiry_recommedation'];
		$candidate_enquiry_recommedation_remarks=$_POST['candidate_enquiry_recommedation_remarks'];
		
		 $candidate_any_relaxation=$_POST['candidate_any_relaxation']; 
		$candidate_any_relaxation_remarks=$_POST['candidate_any_relaxation_remarks'];
		
		if($candidate_any_relaxation=='0')
		{
		//echo 222; die;
		$check_age_value='0';
		$check_education_value='0';
		
		}
	else
		{
		
			if($_POST['check_age_value']=='1')
			{
			//echo 111; die;
			
			$check_age_value=1; 
			}
			else
			{
			//echo 113; die;
			 $check_age_value=0; 
			}
			
			if($_POST['check_education_value']=='1')
			{
			//echo  4444; die;
			 $check_education_value=1; 
			}
			else
			{
				//echo 5555;die;
			$check_education_value=0;
			}
		}
		
		$note=$_POST['note']; 
		$histroy=$_POST['histroy'];
		$flag="CG";
		$emp_id_const=$_SESSION['emp_id_const'];
		
		
		/*$application_id_check=$db->fetch_table("SELECT application_id FROM intra_pri_cg_profile_master WHERE emp_id_const = '".$emp_id_const."'");*/
		
		if(count($application_id_check)=='1')
		{
		
		//echo 1; die;
		  
			if($application_id_check[0]['application_id']!='')
			{
			$application_id=$application_id_check[0]['application_id'];
			}
			else
			{
			$application_id = application_id($flag,$emp_id_const);
			
			$insert_request=$db->insert("INSERT INTO intra_pri_application_master(application_id)
			VALUES (
			'".$application_id."'
			)");
			
			}
		
		}
		else
		{
			//echo 2; die;
			
		$application_id = application_id($flag,$emp_id_const);
		
		$insert_request=$db->insert("INSERT INTO intra_pri_application_master(application_id)
		VALUES (
		'".$application_id."'
		)");
		
		}
		
		
		
		
		
		 
		// echo  $application_id; die;
		  
		$query=$db->update("UPDATE intra_pri_cg_profile_master SET
		name_officer_first='".$name_officer_first."',
		name_officer_second='".$name_officer_second."',
		name_officer_third='".$name_officer_third."',
		memo_no_ec='".$memo_no_ec."',
		memo_date_ec='".$memo_date_ec."',
		date_inquery='".$date_inquery."',
		comment_officer='".$comment_officer."',
		candidate_fulfil_rules='".$candidate_fulfil_rules."',
		candidate_fulfil_remarks='".$candidate_fulfil_remarks."',
		clear_vacany_roster='".$clear_vacany_roster."',
		clear_vacany_roster_remarks='".$clear_vacany_roster_remarks."',
		candidate_fulfil_all='".$candidate_fulfil_all."',
		candidate_fulfil_all_remarks='".$candidate_fulfil_all_remarks."',
		candidate_enquiry_recommedation='".$candidate_enquiry_recommedation."',
		candidate_enquiry_recommedation_remarks='".$candidate_enquiry_recommedation_remarks."',
		candidate_any_relaxation='".$candidate_any_relaxation."',
		candidate_any_relaxation_remarks='".$candidate_any_relaxation_remarks."',
		check_age_value='".$check_age_value."',
		check_education_value='".$check_education_value."',
		note='".$note."',
		histroy='".$histroy."',
		application_id='".$application_id."',
		part_d_from_status='1'
		WHERE  emp_id_const='".$_SESSION['emp_id_const']."'");
		
		  
		 
										
										
if($query==true)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>PART D  Profile Submitted Successfully...</strong></div>';
			header('Location:intra.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>PART D Profile Submitted failed...</strong></div>';
			header('Location:intra.php');
		}


	 }
	 
	 if($edit_status=="partD_edit")
			{
			$query=$db->update("UPDATE intra_pri_cg_profile_master SET 
			part_d_from_status='0'
			WHERE 
			emp_id_const='".$_SESSION['emp_id_const']."'");
			
				if($query==true)
				{
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong> PART D  Profile Now redy For Edit ...</strong></div>';
					header('Location:intra.php');
				}
				else
				{
					$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>PART D Profile Now Not redy For Edit ......</strong></div>';
					header('Location:intra.php');
				}
			
			
			}
	 
	  if($form_flage=="final")
	 {
		 
		 
		$update_oc_final=$db->update("UPDATE intra_pri_cg_profile_master SET 
			status='1'
			WHERE 
			emp_id_const='".$_SESSION['emp_id_const']."'");
			
			
			
			  if($update_oc_final){
        $insert_request_forwarding = $db->insert("INSERT INTO intra_pri_forwarding(application_id, from_officer_id_const, status, service_type, emp_id_const)
            VALUES ('".$app_id."', '".$_SESSION['user_info']['officer_id_const']."',1,'3', '".$_SESSION['emp_id_const']."')");
    }
			
				if($insert_request_forwarding==true)
				{
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>COMPASSIONATE GROUND APPLICATION SUBMITED SUCCESSFULLY </strong></div>';
					header('Location:intra.php');
				}
				else
				{
					$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>COMPASSIONATE GROUND APPLICATION SUBMITED FAILED.</strong></div>';
					header('Location:intra.php');
				}
	 }
	 
	 if($delete_id=="delete")
			{
				$query=$db->update("UPDATE intra_pri_file_upload SET 
			status='0'
			WHERE 
			emp_id_const='".$_SESSION['emp_id_const']."' and flag='".$delete_f."' and status='1'");
				
				if($query==true)
				{
					$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>DOCUMENT SUCCESSFULLY REVOMED </strong></div>';
					header('Location:intra.php');
				}
				else
				{
					$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong> DOCUMENT REMOVED FAILED.</strong></div>';
					header('Location:intra.php');
				}
			}
	
	
	die;
	
/////////////////////////////////////////////////////////////////////////////// SERVER SIDE VALIDATION  ///////////////////////////////////////////////////////////////////////////////////////////////

	
	
	
	
	

		
		
		
}

?>

