<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
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
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	include 'bdo_profile_form.php';
	exit;
}
else{


$bdo_name=$_REQUEST['bdo_name'];
$mobile_no=$_REQUEST['mobile_no'];
$road_name=$_REQUEST['road_name'];
$vill_name=$_REQUEST['vill_name'];
$post_office=$_REQUEST['post_office'];
$pollice_st=$_REQUEST['pollice_st'];
$operator_code_pf=$_REQUEST['operator_code_pf'];
$t_code_pf=$_REQUEST['t_code_pf'];
if($_REQUEST['pin']==''){
$pin=0;
}else{
$pin=$_REQUEST['pin'];
}
if($_REQUEST['contactno']==''){
$contact_no=0;
}else{
$contact_no=$_REQUEST['contactno'];
}
$email=$_REQUEST['email'];
$tan=$_REQUEST['tan'];
if($validator->blank_select($bdo_name) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter BDO Name.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if($validator->blank_select($mobile_no) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Mobile Number.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if($validator->blank_select($road_name) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Road Name.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if($validator->blank_select($vill_name) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Villege/Town Name.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if($validator->blank_select($post_office) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Post Office.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if($validator->blank_select($pollice_st) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Police Station.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if($validator->blank_select($tan) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter TAN number.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if($validator->pattern_number($mobile_no) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Mobile Number.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if($validator->pattern_number($pin) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Pincode.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if($validator->pattern_number($contact_no) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Contact Number.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			
			else if($validator->blank_select($operator_code_pf) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter OPERATOR CODE(PF SUBSCRIPTION).</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			
			
			else if($validator->blank_select($t_code_pf) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter TREASURY CODE(PF SUBSCRIPTION).</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if(!empty($mobile_no) && strlen($mobile_no)!=10)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Mobile Number Should be 10 Digit Long.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if(!empty($pin) && strlen($pin)!=6)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Pincode Should be 6 Digit Long.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else if(!empty($contact_no) && strlen($contact_no)>12)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Contact Number Should be Maximum 12 Digit Long.</strong></div>';
			include 'bdo_profile_form.php';
			exit;
			}
			else{


$db=new database();


$prof_query=$db->fetch_table("select block_code from prd_block_profile where block_code='".$_SESSION['location']['block_code']."'");




$upd=$db->update("UPDATE prd_location_master_block SET block_status='0' WHERE block_code='".$_SESSION['location']['block_code']."'");
if(!empty($prof_query)){
	
	
	$query1=$db->update("UPDATE prd_dise_admin
							   SET operator_code_pf='$operator_code_pf', 
							   t_code_pf='$t_code_pf'
							 WHERE block_code='".$_SESSION['location']['block_code']."'");
/*echo "UPDATE prd_block_profile
							   SET bdo_name='$bdo_name', 
							   mobile_no='$mobile_no', 
							   road_name='$road_name', 
							   vill_name='$vill_name', 
							   post_office='$post_office', 
							   police_station='$pollice_st', 
							   pin_code='$pin', 
							   contact_no='$contact_no',
							   email_id='$email', 
							   tan_no='$tan',
							   last_upd_time='now()', 
							   ip_address='".$_SERVER['REMOTE_ADDR']."'
							 WHERE block_code='".$_SESSION['location']['block_code']."'";exit;*/
							 
			
$query=$db->update("UPDATE prd_block_profile
							   SET bdo_name='$bdo_name', 
							   mobile_no='$mobile_no', 
							   road_name='$road_name', 
							   vill_name='$vill_name', 
							   post_office='$post_office', 
							   police_station='$pollice_st', 
							   pin_code='$pin', 
							   contact_no='$contact_no',
							   email_id='$email', 
							   tan_no='$tan',
							   last_upd_time='now()', 
							   ip_address='".$_SERVER['REMOTE_ADDR']."'
							 WHERE block_code='".$_SESSION['location']['block_code']."'");
}else{
/*echo "INSERT INTO prd_block_profile(block_code, bdo_name, mobile_no, road_name, vill_name, 
            post_office, police_station, pin_code, email_id, contact_no, 
            tan_no, last_upd_time, ip_address) VALUES('".$_SESSION['location']['block_code']."','$bdo_name','$mobile_no','$road_name','$vill_name','$post_office','$pollice_st','$pin','$email','$contact_no','$tan','now()','".$_SERVER['REMOTE_ADDR']."')";exit;*/
			
			$query1=$db->update("UPDATE prd_dise_admin
							   SET operator_code_pf='$operator_code_pf', 
							   t_code_pf='$t_code_pf'
							 WHERE block_code='".$_SESSION['location']['block_code']."'");
							 
							 
$query=$db->insert("INSERT INTO prd_block_profile(block_code, bdo_name, mobile_no, road_name, vill_name, 
            post_office, police_station, pin_code, email_id, contact_no, 
            tan_no, last_upd_time, ip_address) VALUES('".$_SESSION['location']['block_code']."','$bdo_name','$mobile_no','$road_name','$vill_name','$post_office','$pollice_st','$pin','$email','$contact_no','$tan','now()','".$_SERVER['REMOTE_ADDR']."')");
}

if($query){
	header('Location:bdo_profile_form.php?confirm=success');
}
else{
	header('Location:bdo_profile_form.php?confirm=false');
}
}
}
?>