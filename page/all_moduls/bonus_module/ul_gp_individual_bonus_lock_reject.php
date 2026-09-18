<?php
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


header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
$current_monthyear = date("Ym");
$cryptoGraph=new cryptography();

/*-----------------------------------------------------EMPLOYEE FINALIZE----------------------------------------------------------------------*/
$db = new database();

$current_year = date("Y");
$prev_yrr=$current_year-1;
$next_year=$current_year+1;

if(isset($_POST['lock_submit']))
{
	$all_bonus_type_id=$cryptoGraph->decode($_POST['all_bonus_type_id'],4);

	$lock_bonus=$db->update("
									UPDATE prd_employee_bonus_details bonus set
									bonus_status='4'
									FROM prd_location_master_gp gp
									WHERE
									gp.gp_id_pk=bonus.gp_id_fk AND gp.block_id_fk='".$_SESSION['location']['block_id']."'
									AND bonus_status=3 AND delete_status=1  AND monthyear='".$prev_yrr.$current_year."'
								");
	
	
	if($lock_bonus)
	{ 
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>GP Bonus Details Has Been Locked Successfully.</strong></div>';
		header('location:ul_gp_bonus_module_entry_view.php');
		exit(0);
	} 
	else
	{ 
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>GP Bonus Details Has Not Been Locked. Please Try Again...</strong></div>';
		header('location:ul_gp_bonus_module_entry_view.php');
		exit(0);
	}
}



/*-----------------------------------------------------EMPLOYEE REJECT----------------------------------------------------------------------*/

if(isset($_POST['reject_submit']))
{
	$gp_id=$cryptoGraph->decode($_POST['rej_gp_id'],4);
	$reject_bonus=$db->update("
								UPDATE prd_employee_bonus_details set
								bonus_status='1'
								WHERE
								gp_id_fk = '".$gp_id."'
								AND bonus_status=3 AND delete_status=1 AND monthyear='".$prev_yrr.$current_year."'
								");
	
	if($reject_bonus)
	{ 
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>GP Bonus Details Has Been Rejected Successfully.</strong></div>';
		header('location:ul_gp_bonus_module_entry_view.php');
		exit(0);
	} 
	else
	{ 
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>GP Bonus Details Has Not Been Rejected. Please Try Again...</strong></div>';
		header('location:ul_gp_bonus_module_entry_view.php');
		exit(0);
	}
}

@pg_close($con);
?>