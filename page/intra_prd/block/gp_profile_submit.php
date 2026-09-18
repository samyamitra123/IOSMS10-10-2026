<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
ob_start();
//error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
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

$dise_code=$_REQUEST['dise_code'];
$gp_name=$_REQUEST['gp_name'];
$block=$_REQUEST['block'];
$pradhan_name=$_REQUEST['pradhan_name'];
$mobile_no=$_REQUEST['mobile_no'];
$road_name=$_REQUEST['road_name'];
$vill_name=$_REQUEST['vill_name'];
$post_office=$_REQUEST['post_office'];
$pollice_st=$_REQUEST['pollice_st'];
$pin=$_REQUEST['pin'];
$email=$_REQUEST['email'];
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);
//echo $enc_session;
//echo $sec_time_token;
$validator = new Validation();



if($sec_time_token!=$enc_session)
{
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
	include 'gp_profile_form.php';
	exit;
}
else{
	//echo $dise_code;exit;
			//echo "else";
			
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
			else if(!empty($dise_code) && strlen($dise_code)!=10)
			{
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>GP Code Should be 10 Digit Long.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}
			else if(!empty($mobile_no) && strlen($mobile_no)!=10)
			{
				//echo "fdfdaf1";exit;
			$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Mobile Number Should be 10 Digit Long.</strong></div>';
			include 'gp_profile_form.php';
			exit;
			}else{
				
			$db=new database();

$lms_query=$db->fetch_table("select gp_id_pk,gp_code from prd_location_master_gp where gp_code='".$dise_code."'");

//echo "select gp_id_pk,gp_code from prd_location_master_gp where gp_code='".$dise_code."'";exit;

$prof_query=$db->fetch_table("select gp_id_fk,gp_code from prd_gp_profile where gp_code='".$dise_code."'");

/*echo "SELECT COUNT(mobile_no) AS cnt
    FROM prd_gp_profile prof
    INNER JOIN prd_stack_user_login login
        ON prof.gp_code = login.stake_user::bigint
    WHERE mobile_no = '".$mobile_no."'
      AND stake_credential_flag = '1'";exit;*/

$mob_check=$db->fetch_table("SELECT COUNT(mobile_no) AS cnt
    FROM prd_gp_profile prof
    INNER JOIN prd_stack_user_login login
        ON prof.gp_code = login.stake_user::bigint
    WHERE mobile_no = '".$mobile_no."'
      AND stake_credential_flag = '1'");

//echo "dffafsdf3";exit;
//echo count($lms_query);exit;
/*echo "select gp_id_pk,gp_code from prd_location_master_gp where gp_id_pk='".$_SESSION['location']['gp_id']."'";
echo "select gp_id_pk,gp_code from prd_gp_profile where gp_id_fk='".$_SESSION['location']['gp_id']."'";
echo pg_num_rows($lms_query);
echo pg_num_rows($prof_query);
exit;*/

//$login_id=$db->fetch_table('select max(id) as id from prd_stack_user_login');
$login_id=$db->fetch_table('select max(login_id_pk) as id from prd_stack_user_login');
$id=$login_id[0]['id']+1;
//echo $id;exit;

if(count($lms_query)>0){
	//echo "dff";exit;
	//header('Location:gp_profile_form.php?confirm=fails');
	//exit;
	$error_msg='<div class="alert alert-danger" style="text-align:center"><strong>Gp Profile Already Exists.</strong></div>';
			include 'gp_profile_form.php';
			exit;
}
else if($mob_check[0]['cnt']>0){
	header('Location:gp_profile_form.php?confirm=fails_mob');
	exit;
}
else{
	
	/*echo "INSERT INTO prd_stack_user_login(stake_level_id_fk, stake_user, stake_password,stake_credential_flag,id,entry_time,entry_ip) VALUES('64','$dise_code','".sha1('b12e94e2a4ccff058f380331e4c4debaf3a33bf4')."','1','$id','now()','".$_SERVER['REMOTE_ADDR']."')";
	exit;*/

	pg_query('Begin');
	$max_pk=$db->fetch_table("Select max(login_id_pk) AS pk from prd_stack_user_login");
	$login_id_pk=$max_pk[0]['pk']+1;
	$query=$db->insert("INSERT INTO prd_location_master_gp(block_id_fk, gp_code, gp_name) VALUES('$block','$dise_code','$gp_name')");
	$query1=$db->insert("INSERT INTO prd_gp_profile(gp_code, gram_pradhan_name, mobile_no) VALUES('$dise_code','$pradhan_name','$mobile_no')");
	$query2=$db->insert("INSERT INTO prd_stack_user_login(login_id_pk,stake_level_id_fk, stake_user, stake_password,stake_credential_flag,entry_time,entry_ip) VALUES('".$login_id_pk."','64','$dise_code','".sha1('b12e94e2a4ccff058f380331e4c4debaf3a33bf4')."','1','now()','".$_SERVER['REMOTE_ADDR']."')");
}
if($query && $query1 && $query2){
	//echo "in commit";
	pg_query('COMMIT');
	header('Location:gp_profile_form.php?confirm=success');
	exit(0);
}
else{
	//echo "in abort";
	pg_query('ROLLBACK');
	header('Location:gp_profile_form.php?confirm=false');
	exit(0);
}
}

}
?>