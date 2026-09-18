<?php
session_start();
ob_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	include 'ps_profile_form.php';
	exit;
}
else
{
	$dise_name=$_POST['dise_name'];
	$ps_name=$_POST['ps_name'];
	$executive_officer_name=$_POST['executive_name'];
	$mobile_no=$_POST['mobile_no'];
	$road_name=$_POST['road_name'];
	$vill_name=$_POST['vill_name'];
	$post_office=$_POST['post_office'];
	$pollice_st=$_POST['pollice_st'];
	$district_id=$_POST['district_id'];
	$ps_id=$_POST['ps_id'];
	$pin=$_POST['pin'];
	$pl_code_pf=$_POST['pl_code_pf'];
$t_code_pf=$_POST['t_code_pf'];
	
	if($_POST['contactno']=='')
	{
		$contact_no=0;
	}
	else
	{
		$contact_no=$_POST['contactno'];
	}
	$email=$_POST['email'];
	$ddo_code=$_POST['ddo_code'];
	$t_code=strtoupper($_POST['t_code']);


/////////////////////////////////////////////////////////////////////////////// SERVER SIDE VALIDATION  ///////////////////////////////////////////////////////////////////////////////////////////////

	
	if($validator->blank_select($dise_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter District Name.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($ps_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter PS Name.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($mobile_no) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Mobile Number.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($executive_officer_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Executive Officer Name.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($road_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Road Name.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($vill_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Villege/Town Name.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($post_office) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Post Office.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($pollice_st) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Police Station.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	
	else if($validator->pattern_number($mobile_no) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Mobile Number.</strong></div>';
		include 'gp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($pin) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Insert Pincode</strong></div>';
		include 'school_profile_form.php';
		exit;
	}
	else if($validator->pattern_number($pin) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Pincode.</strong></div>';
		include 'school_profile_form.php';
		exit;
	}
	else if($validator->pattern_number($contact_no) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Contact Number.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	
	else if(!empty($mobile_no) && strlen($mobile_no)!=10)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Mobile Number Should be 10 Digit Long.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if(!empty($pin) && strlen($pin)!=6)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Pincode Should be 6 Digit Long.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if(!empty($contact_no) && strlen($contact_no)>12)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Contact Number Should be Maximum 12 Digit Long.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($ddo_code) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter DDO code.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($t_code) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter TREASURY Code.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($email) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Email Id.</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	
	else if($validator->blank_select($pl_code_pf) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter PL OPERATOR CODE(PF SUBSCRIPTION).</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else if($validator->blank_select($t_code_pf) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>TREASURY CODE(PF SUBSCRIPTION).</strong></div>';
		include 'ps_profile_form.php';
		exit;
	}
	else
	{

		$db=new database();
		pg_query('BEGIN');
				
				$query=$db->update("UPDATE psemp_ps_profile SET
												ps_id_fk='".$ps_id."',
												district_id_fk='".$district_id."',
												exe_officer_name='$executive_officer_name', 
												mobile_no='$mobile_no',
												road_name='$road_name' , 
												post_office_name='$post_office',
												police_station_name='$pollice_st', 
												pin_code='$pin',
												cotract_no='$contact_no',
												email='$email', 
												last_upd_time='now()' , 
												ip_address='".$_SERVER['REMOTE_ADDR']."',
												vill_name='$vill_name',
												ddo_code='$ddo_code',treasury_code='$t_code',
												pl_code_pf='$pl_code_pf',
												t_code_pf='$t_code_pf'
												WHERE ps_id_fk='".$ps_id."' and district_id_fk='".$district_id."'");
		
			$insert_ps_status = $db->insert("
								INSERT INTO psemp_ps_profile_update_status 
								(
									ip_address,
									prev_status,
									ps_id_fk,
									date,
									present_status
								)
								VALUES 
								(
									'".$_SESSION['user_agent']['USER_IP']."',
									'2',
									'".$_SESSION['location']['ps_id']."',
									now(),
									'2'
								);
								
								");
		if($query && $insert_ps_status)
		{
			pg_query('COMMIT');
			header('Location:ps_profile_form.php?confirm=success');
		}
		else
		{
			pg_query('ROLLBACK');
			header('Location:ps_profile_form.php?confirm=false');
		}
	}
}

?>

