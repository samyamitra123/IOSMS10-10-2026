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
	include 'zp_profile_form.php';
	exit;
}
else
{
	$dise_name= strtoupper($_POST['dise_name']);
	$aeo_name= strtoupper($_POST['aeo_name']);
	/*$secretary_name= strtoupper($_POST['secretary_name']);
	$fc_cao_name= strtoupper($_POST['fc_cao_name']);
	$accountant_name= strtoupper($_POST['accountant_name']);*/
	//$ps_name=$_POST['ps_name'];
	//$executive_officer_name=$_POST['executive_name'];
	//$mobile_no=$_POST['mobile_no'];
	$pan_no=strtoupper($_POST['pan_no']);
	$tan_no=strtoupper($_POST['tan_no']);
	$gst_no=strtoupper($_POST['gst_no']);
	$pl_code=strtoupper($_POST['pl_code']);
	$road_name= strtoupper($_POST['road_name']);
	$vill_name= strtoupper($_POST['vill_name']);
	$post_office= strtoupper($_POST['post_office']);
	$pollice_st= strtoupper($_POST['pollice_st']);
	$district_id= $_POST['district_id'];
	//$ps_id=$_POST['ps_id'];
	$ddo_code=strtoupper($_POST['ddo_code']);
	$pin=$_POST['pin'];
	
	if($_POST['contactno']=='')
	{
		$contact_no=0;
	}
	else
	{
		$contact_no=$_POST['contactno'];
	}
	$email= $_POST['email'];


/////////////////////////////////////////////////////////////////////////////// SERVER SIDE VALIDATION  ///////////////////////////////////////////////////////////////////////////////////////////////

	
	if($validator->blank_select($dise_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter District Name.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	/*else if($validator->blank_select($ps_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter PS Name.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($mobile_no) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Mobile Number.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($executive_officer_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Executive Officer Name.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}*/
	
	else if($validator->blank_select($aeo_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter AEO Name.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	/*else if($validator->blank_select($secretary_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Secretary Name.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($fc_cao_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter FA & CAO Name.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($accountant_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Accountant Name.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}*/
	else if($validator->blank_select($tan_no) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Tan Number.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($pl_code) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter PL Operator Code.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($road_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Road Name.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($vill_name) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Villege/Town Name.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($post_office) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Post Office.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($pollice_st) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Police Station.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	
	else if($validator->pattern_number($mobile_no) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Mobile Number.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($pin) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Insert Pincode</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->pattern_number($pin) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Pincode.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	
	else if($validator->pattern_number($contact_no) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Numeric Contact Number.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if($validator->blank_select($ddo_code) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter DDO code.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if(!empty($mobile_no) && strlen($mobile_no)!=10)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Mobile Number Should be 10 Digit Long.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if(!empty($pin) && strlen($pin)!=6)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Pincode Should be 6 Digit Long.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	/*else if($validator->blank_select($ddo_code) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter DDO code.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}*/
	else if($validator->blank_select($email) == FALSE)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Email Id.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else if(!empty($contact_no) && strlen($contact_no)>12)
	{
		$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Contact Number Should be Maximum 12 Digit Long.</strong></div>';
		include 'zp_profile_form.php';
		exit;
	}
	else
	{

		$db=new database();
		//$lms_query=$db->fetch_table("select ps_id_fk from psemp_ps_profile where ps_id_fk='".$ps_id."' and district_id_fk='".$district_id."'");
		
		$lms_query=$db->fetch_table("select district_id_fk from zpemp_zp_profile where district_id_fk='".$district_id."'");
	if(empty($lms_query))
		{

						$query=$db->insert("INSERT INTO zpemp_zp_profile
								(
									
									district_id_fk,
									aeo_name,
									road_name , 
									post_office_name,  
									police_station_name, 
									pin_code, 
									cotract_no, 
									email, 
									last_upd_time, 
									ip_address,
									vill_name,
									tan_no,
									gst_no,
									pl_code,
									ddo_code,
									pan_no
									)
									VALUES 
									(
									'".$district_id."',
									'$aeo_name',
									'$road_name',
									'$post_office',
									'$pollice_st',
									'$pin',
									'$contact_no',
									'$email',
									'now()',
									'".$_SERVER['REMOTE_ADDR']."',
									'$vill_name',
									'$tan_no',
									'$gst_no',
									'$pl_code',
									 '$ddo_code',
									 '$pan_no')");
										
						$upd=$db->update("UPDATE prd_location_master_district SET zp_status='0',
						 					zp_unlock_status='0'
											WHERE district_id_pk='".$district_id."'");
		} 
		else
		{
				$upd=$db->update("UPDATE prd_location_master_district SET zp_status='0',
				 					zp_unlock_status='0' 
									WHERE district_id_pk='".$district_id."'");
	
			//if($upd)
			//{
				
				$query=$db->update("UPDATE zpemp_zp_profile SET
												district_id_fk='".$district_id."',
												aeo_name='$aeo_name',
												road_name='$road_name' , 
												post_office_name='$post_office',
												police_station_name='$pollice_st', 
												pin_code='$pin',
												cotract_no='$contact_no',
												email='$email', 
												last_upd_time='now()' , 
												ip_address='".$_SERVER['REMOTE_ADDR']."',
												vill_name='$vill_name',
												tan_no='$tan_no',
												gst_no='$gst_no',
												pl_code='$pl_code',
												ddo_code='$ddo_code',
												pan_no='$pan_no'
												WHERE district_id_fk='".$district_id."'");
			//}
			
		
    	}
		
		if($query)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>ZP profile submitted Successfully...</strong></div>';
			header('Location:zp_profile_form.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>ZP profile submitted failed...</strong></div>';
			header('Location:zp_profile_form.php');
		}
	}
}

?>

