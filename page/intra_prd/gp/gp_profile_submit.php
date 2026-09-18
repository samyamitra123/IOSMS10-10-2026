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
	include 'gp_profile_form.php';
	exit;
}
else{

$dise_code=$_REQUEST['dise_code'];
$gp_name=$_REQUEST['gp_name'];
$block=$_REQUEST['block'];
$pradhan_name=$_REQUEST['pradhan_name'];
$mobile_no=$_REQUEST['mobile_no'];
$road_name=$_REQUEST['road_name'];
$vill_name=$_REQUEST['vill_name'];
$post_office=$_REQUEST['post_office'];
$pollice_st=$_REQUEST['pollice_st'];
//$pin=$_REQUEST['pin'];
//$contact_no=$_REQUEST['contactno'];
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
if($validator->blank_select($dise_code) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter GP Code.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if($validator->blank_select($gp_name) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter GP Name.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if($validator->blank_select($mobile_no) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Mobile Number.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if($validator->blank_select($road_name) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Road Name.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if($validator->blank_select($vill_name) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Villege/Town Name.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if($validator->blank_select($post_office) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Post Office.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if($validator->blank_select($pollice_st) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Police Station.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if($validator->pattern_number($dise_code) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric GP Code.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if($validator->pattern_number($mobile_no) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Mobile Number.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if($validator->pattern_number($pin) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Pincode.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if($validator->pattern_number($contact_no) == FALSE)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Contact Number.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if(!empty($dise_code) && strlen($dise_code)!=10)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>GP Code Should be 10 Digit Long.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if(!empty($mobile_no) && strlen($mobile_no)!=10)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Mobile Number Should be 10 Digit Long.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if(!empty($pin) && strlen($pin)!=6)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Pincode Should be 6 Digit Long.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if(!empty($contact_no) && strlen($contact_no)>12)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Contact Number Should be Maximum 12 Digit Long.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else{


$db=new database();

$lms_query=$db->fetch_table("select gp_id_pk,gp_code from prd_location_master_gp where gp_id_pk='".$_SESSION['location']['gp_id']."'");
$prof_query=$db->fetch_table("select gp_id_fk,gp_code from prd_gp_profile where gp_id_fk='".$_SESSION['location']['gp_id']."'");
/*echo "select gp_id_pk,gp_code from prd_location_master_gp where gp_id_pk='".$_SESSION['location']['gp_id']."'";
echo "select gp_id_pk,gp_code from prd_gp_profile where gp_id_fk='".$_SESSION['location']['gp_id']."'";
echo pg_num_rows($lms_query);
echo pg_num_rows($prof_query);
exit;*/

if(!empty($lms_query) && !empty($prof_query)){

/*echo "UPDATE prd_gp_profile
							   SET gp_code='$dise_code', 
							   gram_pradhan_name='$pradhan_name', 
							   mobile_no='$mobile_no', 
							   road_name='$road_name', 
							   vill_name='$vill_name', 
							   post_office='$post_office', 
							   police_station='$pollice_st', 
							   pin_code='$pin', 
							   contact_no='$contact_no',
							   email_id='$email', 
							   last_upd_time='now()', 
							   ip_address='".$_SERVER['REMOTE_ADDR']."'
							 WHERE gp_id_fk='".$_SESSION['location']['gp_id']."'";
							 exit;
*/

$upd=$db->update("UPDATE prd_location_master_gp SET flag='0' WHERE gp_id_pk='".$_SESSION['location']['gp_id']."'");
$query=$db->update("UPDATE prd_gp_profile
							   SET gp_code='$dise_code', 
							   gram_pradhan_name='$pradhan_name', 
							   mobile_no='$mobile_no', 
							   road_name='$road_name', 
							   vill_name='$vill_name', 
							   post_office='$post_office', 
							   police_station='$pollice_st', 
							   pin_code='$pin', 
							   contact_no='$contact_no',
							   email_id='$email', 
							   last_upd_time='now()', 
							   ip_address='".$_SERVER['REMOTE_ADDR']."'
							 WHERE gp_id_fk='".$_SESSION['location']['gp_id']."'");
}
else if(!empty($lms_query)){
	
	/*echo "INSERT INTO prd_gp_profile(
            gp_id_fk, gp_code, gram_pradhan_name, mobile_no, road_name, 
            vill_name, post_office, police_station, pin_code, email_id, last_upd_time, 
            ip_address)
    		VALUES ('".$lms_query[0]['gp_id_pk']."', '$dise_code','$pradhan_name','$mobile_no','$road_name', 
            '$vill_name','$post_office','$pollice_st','$pin','$email','now()','".$_SERVER['REMOTE_ADDR']."');";*/
			
$upd=$db->update("UPDATE prd_location_master_gp SET flag='0' WHERE gp_id_pk='".$_SESSION['location']['gp_id']."'");			
$query=$db->insert("INSERT INTO prd_gp_profile(
            gp_id_fk, gp_code, gram_pradhan_name, mobile_no, road_name, 
            vill_name, post_office, police_station, pin_code, email_id, last_upd_time, 
            ip_address,contact_no)
    		VALUES ('".$lms_query[0]['gp_id_pk']."', '$dise_code','$pradhan_name','$mobile_no','$road_name', 
            '$vill_name','$post_office','$pollice_st','$pin','$email','now()','".$_SERVER['REMOTE_ADDR']."','$contact_no');");
}
//exit;
if($query){
	header('Location:gp_profile_form.php?confirm=success');
}
else{
	header('Location:gp_profile_form.php?confirm=false');
}
}
}
?>