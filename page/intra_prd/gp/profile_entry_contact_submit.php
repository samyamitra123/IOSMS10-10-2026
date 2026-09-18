<?
ob_start();
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';

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
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	$emp_id=$cryptoGraph->encode($_REQUEST['emp_id_pk'],4);
	include 'profile_entry_contact_form.php';
	exit;
}
else{
//error_reporting(0);
		$emp_id_pk=$_REQUEST['emp_id_pk'];
		$present_address_state=trim($_POST['present_address_state']);
		$present_house_no=strtoupper(trim($_POST['present_house_no']));
		$present_street=strtoupper(trim($_POST['present_street']));
		$present_town_vill=strtoupper(trim($_POST['present_town_vill']));
		$present_post_office=strtoupper(trim($_POST['present_post_office']));
		$present_pin=trim($_POST['present_pin']);
		$present_city_district=trim($_POST['present_city_district']);
		$present_others_dist=strtoupper(trim($_POST['present_others_dist']));
		
		$permanent_address_state=trim($_POST['permanent_address_state']);
		$permanent_house_no=strtoupper(trim($_POST['permanent_house_no']));
		$permanent_street=strtoupper(trim($_POST['permanent_street']));
		$permanent_town_vill=strtoupper(trim($_POST['permanent_town_vill']));
		$permanent_post_office=strtoupper(trim($_POST['permanent_post_office']));
		$permanent_pin=trim($_POST['permanent_pin']);
		$permanent_city_district=trim($_POST['permanent_city_district']);
		$permanent_others_dist=strtoupper(trim($_POST['permanent_others_dist']));
		
		$landline_no=$_POST['landline_no']==''?'0':trim($_POST['landline_no']);
		$mobile_no=$_POST['mobile_no']==''?'0':trim($_POST['mobile_no']);
		$email=trim($_POST['email']);
		
		$form_status=$_REQUEST['form_status'];
		if($form_status>5){
			$emp_form_status=$form_status;
		}
		else{
			$emp_form_status=5;
		}
		

if($validator->pattern_match_alphanumeric($present_house_no)==FALSE || $validator->pattern_match_alphanumeric($present_street)==FALSE || $validator->pattern_match_alphanumeric($present_town_vill)==FALSE || $validator->pattern_match_alphanumeric($present_post_office)==FALSE || $validator->pattern_match_alphanumeric($present_others_dist)==FALSE || $validator->pattern_match_alphanumeric($permanent_house_no)==FALSE || $validator->pattern_match_alphanumeric($permanent_street)==FALSE || $validator->pattern_match_alphanumeric($permanent_town_vill)==FALSE || $validator->pattern_match_alphanumeric($permanent_post_office)==FALSE || $validator->pattern_match_alphanumeric($permanent_others_dist)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Alphanumeric Value.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
		}
		
		if($validator->blank_select($present_address_state)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Present State.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
			
		}else if($validator->blank_select($present_town_vill)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Present Town/Village.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
			
		}else if($validator->blank_select($present_post_office)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Present Post Office.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
			
		}else if($validator->blank_select($present_pin)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Present Pin.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
			
		}else if($validator->int_val($present_pin)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Pin Value.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
			
		}else if($present_address_state=='32'){
			if($validator->blank_select($present_city_district)==FALSE){
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Present District.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_contact_form.php';
				exit;
			}
		}else if($present_address_state=='others'){
			if($validator->blank_select($present_others_dist)==FALSE){
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Present District.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_contact_form.php';
				exit;
			}
		}
		if($validator->blank_select($permanent_address_state)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Permanent State.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
			
		}else if($validator->blank_select($permanent_town_vill)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Permanent Town/Village.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
		}else if($validator->blank_select($permanent_post_office)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Permanent Post Office.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
		}else if($validator->blank_select($permanent_pin)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Permanent Pin.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
		}else if($validator->int_val($permanent_pin)==FALSE){
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Pin Value.</strong></div>';
			$emp_id=$cryptoGraph->encode($emp_id_pk,4);
			include 'profile_entry_contact_form.php';
			exit;
		}else if($permanent_address_state=='32'){
			if($validator->blank_select($permanent_city_district)==FALSE){
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Permanent District.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_contact_form.php';
				exit;
			}
		}else if($permanent_address_state=='others'){
			if($validator->blank_select($permanent_others_dist)==FALSE){
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Permanent District.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_contact_form.php';
				exit;
			}
		}
		if($landline_no!='' || $landline_no!=NULL){
			if($validator->pattern_number($landline_no)==FALSE){
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Land Phone Number.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_contact_form.php';
				exit;
			}
		}
		if($mobile_no==''){
			    $error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Mobile Phone Number.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_contact_form.php';
				exit;
		}
		if($mobile_no!='' || $mobile_no!=NULL){
			if($validator->pattern_number($mobile_no)==FALSE){
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Mobile Phone Number.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_contact_form.php';
				exit;
			}
		}
		if($email!='' || $email!=NULL){
			if($validator->pattern_match_email($email)==FALSE){
				$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Email.</strong></div>';
				$emp_id=$cryptoGraph->encode($emp_id_pk,4);
				include 'profile_entry_contact_form.php';
				exit;
			}
		}



/*		
		echo "update prd_employee_master set 
		pre_state='".$present_address_state."', 
		emp_pre_house_no='".$present_house_no."',
		emp_pre_street_no='".$present_street."',
		emp_pre_vill='".$present_town_vill."', 
		emp_pre_post='".$present_post_office."',
		emp_pre_pin='".$present_pin."',
		emp_pre_dist='".$present_city_district."',
		emp_pre_dist_others='".$present_others_dist."',
		per_state='".$permanent_address_state."',
		emp_per_house_no='".$permanent_house_no."',
		emp_per_street_no='".$permanent_street."',
		emp_per_vill='".$permanent_town_vill."',
		emp_per_post='".$permanent_post_office."',
		emp_per_pin='".$permanent_pin."',
		emp_per_dist='".$permanent_city_district."',
		emp_per_dist_others='".$permanent_others_dist."',
		emp_land_no='".$landline_no."',
		emp_mobile_no='".$mobile_no."',
		emp_mail_id='".$email."',
		emp_form_status='5',
		entry_time='now()',
		entry_ip='".$_SERVER['REMOTE_ADDR']."'
		WHERE
		emp_id_pk='$emp_id_pk'";
		exit;*/
		
		$db=new database();
		$insrt_contact=$db->update("update prd_employee_master set 
		pre_state='".$present_address_state."', 
		emp_pre_house_no='".$present_house_no."',
		emp_pre_street_no='".$present_street."',
		emp_pre_vill='".$present_town_vill."', 
		emp_pre_post='".$present_post_office."',
		emp_pre_pin='".$present_pin."',
		emp_pre_dist='".$present_city_district."',
		emp_pre_dist_others='".$present_others_dist."',
		per_state='".$permanent_address_state."',
		emp_per_house_no='".$permanent_house_no."',
		emp_per_street_no='".$permanent_street."',
		emp_per_vill='".$permanent_town_vill."',
		emp_per_post='".$permanent_post_office."',
		emp_per_pin='".$permanent_pin."',
		emp_per_dist='".$permanent_city_district."',
		emp_per_dist_others='".$permanent_others_dist."',
		emp_land_no='".$landline_no."',
		emp_mobile_no='".$mobile_no."',
		emp_mail_id='".$email."',
		emp_form_status='$emp_form_status',
		entry_time='now()',
		entry_ip='".$_SERVER['REMOTE_ADDR']."'
		WHERE
		emp_id_pk='$emp_id_pk'");
		//print_r($insrt_contact);
		//exit;
		
if($insrt_contact){
$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>Employee Profile Submitted Successfully...</strong></div>';
header('Location:employee_edit_details.php');
exit(0);
//echo "Hello";
}
else{
header('Location:profile_entry_contact.php?emp_id_pk='.$cryptoGraph->encode($emp_id_pk,4).'&confirm=false');
exit(0);
}
}
?>