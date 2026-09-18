<?
ob_start();
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';

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

if(!isset($_SERVER['HTTP_REFERER']))
{
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} 
elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) 
{
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");


//////////////////////////////////////////////// USER IDENTIFICATION //////////////////////////////////////////////////////

if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)')
{
	$logged_user='zpdaa';
}
else if($_SESSION['user_info']['stake_abbr']=='ACCOUNTANT')
{
	$logged_user='zpacc';
}
else if($_SESSION['user_info']['stake_abbr']=='FC&CAO')
{
	$logged_user='zpddo';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$cryptoGraph=new cryptography();
$sec_time_token=$_POST['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('369'.$session_token);

$emp_id_pk=urldecode(base64_decode($_POST['send_bonus_emp_id']));
$gp_id_fk = $_SESSION['location']['gp_id'];
$ps_id_fk = $_SESSION['location']['ps_id'];
$zp_id_fk = $_SESSION['location']['district_id'];
$bonus_type_id=$_POST['send_bonus_type_id'];

$db = new database();	

if($emp_id_pk!="")
{
	if($logged_user=='zpdaa')
	{
		$query_update=$db->update(" UPDATE prd_employee_bonus_details SET bonus_status=3 
									WHERE emp_id_fk in (".$emp_id_pk.") AND bonus_type_id_fk='".$bonus_type_id."' 
									AND bonus_status=2 AND delete_status='1' AND zp_id_fk='".$zp_id_fk."'");
	}
	else if($logged_user=='DA')
	{
		$query_update=$db->update(" UPDATE prd_employee_bonus_details SET bonus_status=3 
									WHERE emp_id_fk in (".$emp_id_pk.") AND bonus_type_id_fk='".$bonus_type_id."' 
									AND bonus_status=2 AND delete_status='1' AND ps_id_fk='".$ps_id_fk."'");
	}
	else if($logged_user=='GP')
	{
		$query_update=$db->update(" UPDATE prd_employee_bonus_details SET bonus_status=3 
									WHERE emp_id_fk in (".$emp_id_pk.") AND bonus_type_id_fk='".$bonus_type_id."' 
									AND bonus_status=2 AND delete_status='1' AND gp_id_fk='".$gp_id_fk."'");
	}
}
	
	
if($query_update)
{
	$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Employee Bonus Details Has Been Sent Successfully.</strong></div>';
	header('location:ll_emp_bonus_module_sal_form.php');
	exit(0);
}
else
{
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Employee Bonus Details Has Not Been Sent. Please Try Again...</strong></div>';
	header('location:ll_emp_bonus_module_sal_form.php');
	exit(0);
}
@pg_close($con);
?>