<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require '../../../includes/library/myvalidation.class.php';

if($_SERVER['HTTP_REFERER']==''){
header("Location:../../../dashboard.php");
}

if (
	!isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

)
{
	header('Location: '.$config['base_url']."page/login.php");
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

header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

$current_monthyear = date("Ym");
$cryptoGraph=new cryptography();

/*-----------------------------------------------------EMPLOYEE FINALIZE----------------------------------------------------------------------*/
$db = new database();


if(isset($_POST['lock_submit']))
{
	$all_gp_id=$cryptoGraph->decode($_POST['all_gp_id'],4);
	
	$lock_bonus=$db->update("
								UPDATE prd_festival_advance_employee_details fad SET festival_advance_status=4
								FROM prd_employee_master emp
								WHERE fad.emp_id_fk=emp.emp_id_pk AND emp.gp_id_fk in (".$all_gp_id.")
								AND festival_advance_status=3 AND substr(fad_monthyear,1,4)='".date('Y')."'
								");
	
	
	if($lock_bonus)
	{ 
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>GP Festival Advance Details Has Been Locked Successfully.</strong></div>';
		header('location:ul_fad_gp_list.php');
		exit(0);
	} 
	else
	{ 
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>GP Festival Advance Details Has Not Been Locked. Please Try Again...</strong></div>';
		header('location:ul_fad_gp_list.php');
		exit(0);
	}
}



/*-----------------------------------------------------EMPLOYEE REJECT----------------------------------------------------------------------*/

if(isset($_POST['reject_submit']))
{
	$bonus_type_id=$cryptoGraph->decode($_POST['bonus_type_id'],4);
	$gp_id=$cryptoGraph->decode($_POST['rej_gp_id'],4);
	$reject_bonus=$db->update("
								 UPDATE prd_festival_advance_employee_details fad SET festival_advance_status=2
								FROM prd_employee_master emp
								WHERE fad.emp_id_fk=emp.emp_id_pk AND emp.gp_id_fk='".$gp_id."'
								AND festival_advance_status=3 AND substr(fad_monthyear,1,4)='".date('Y')."'
								");
	
	if($reject_bonus)
	{ 
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>GP Festival Advance Details Has Been Rejected Successfully.</strong></div>';
		header('location:ul_fad_gp_list.php');
		exit(0);
	} 
	else
	{ 
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>GP Festival Advance Details Has Not Been Rejected. Please Try Again...</strong></div>';
		header('location:ul_fad_gp_list.php');
		exit(0);
	}
}

@pg_close($con);
?>