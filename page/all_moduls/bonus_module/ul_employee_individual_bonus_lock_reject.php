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
$prev_year=$current_year-1;
$next_year=$current_year+1;

if(isset($_POST['lock_submit']))
{
	
	if($logged_user=='BDO')
	{
		$gp_id=$cryptoGraph->decode($_POST['enc_gp_id'],4);
		$lock_bonus=$db->update("
										UPDATE prd_employee_bonus_details set
										bonus_status='4'
										WHERE
										gp_id_fk = '".$gp_id."'
										AND bonus_status=3 AND delete_status=1
										AND monthyear='".$prev_year.$current_year."'
									");
	}
	else if($logged_user=='EO')
	{
	
		$lock_bonus=$db->update("
										UPDATE prd_employee_bonus_details set
										bonus_status='4'
										WHERE
										ps_id_fk = '".$_SESSION['location']['ps_id']."'
										AND bonus_status=3 AND delete_status=1
										AND monthyear='".$prev_year.$current_year."'
									");
	}
	
	else if($logged_user=='zpacc')
	{
	
		$lock_bonus=$db->update("
										UPDATE prd_employee_bonus_details set
										bonus_status='4'
										WHERE
										zp_id_fk = '".$_SESSION['location']['district_id']."'
										AND bonus_status=3 AND delete_status=1
										AND monthyear='".$prev_year.$current_year."'
									");
	}
	
	if($lock_bonus)
	{ 
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>Employee Bonus Details Has Been Locked Successfully.</strong></div>';
		if($logged_user=='BDO')
		{
			header('location:ul_emp_bonus_module_entry_view.php?id='.$_POST['enc_gp_id']);
		}
		else
		{
			header('location:ul_emp_bonus_module_entry_view.php');
		}
		exit(0);
	} 
	else
	{ 
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Employee Bonus Details Has Not Been Locked. Please Try Again...</strong></div>';
		header('location:ul_emp_bonus_module_entry_view.php');
		exit(0);
	}
}



/*-----------------------------------------------------EMPLOYEE REJECT----------------------------------------------------------------------*/

if(isset($_POST['reject_submit']))
{
	
	$emp_id=$cryptoGraph->decode($_POST['rej_emp_id'],4);
	
	if($logged_user=='BDO')
	{
		$gp_id=$cryptoGraph->decode($_POST['enc_gp_id'],4);
		$reject_bonus=$db->update("
									UPDATE prd_employee_bonus_details set
									bonus_status='1'
									WHERE
									emp_id_fk='".$emp_id."'
									AND gp_id_fk = '".$gp_id."'
									AND bonus_status=3 AND delete_status=1
									AND monthyear='".$prev_year.$current_year."'
									");
	}
	else if($logged_user=='EO')
	{
	
		$reject_bonus=$db->update("
									UPDATE prd_employee_bonus_details set
									bonus_status='1'
									WHERE
									emp_id_fk='".$emp_id."'
									AND ps_id_fk = '".$_SESSION['location']['ps_id']."'
									AND bonus_status=3 AND delete_status=1
									AND monthyear='".$prev_year.$current_year."'
									");
	}
	else if($logged_user=='zpacc')
	{
	
		$reject_bonus=$db->update("
									UPDATE prd_employee_bonus_details set
									bonus_status='1'
									WHERE
									emp_id_fk='".$emp_id."'
									AND zp_id_fk = '".$_SESSION['location']['district_id']."'
									AND bonus_status=3 AND delete_status=1
									AND monthyear='".$prev_year.$current_year."'
									");
	}
	
	
	
	if($reject_bonus)
	{ 
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>Employee Bonus Details Has Been Rejected Successfully.</strong></div>';
		if($logged_user=='BDO')
		{
			header('location:ul_emp_bonus_module_entry_view.php?id='.$_POST['enc_gp_id']);
		}
		else
		{
			header('location:ul_emp_bonus_module_entry_view.php');
		}
		
		exit(0);
	} 
	else
	{ 
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Employee Bonus Details Has Not Been Rejected. Please Try Again...</strong></div>';
		header('location:ul_emp_bonus_module_entry_view.php');
		exit(0);
	}
}
//////////////////////////////////////////////////////Employee Reject Bonus/////////////////////////////////////////////////


if(isset($_POST['unlock_submit']))
{

//$db=new database();
//$crypto = new cryptography();

 $id=$cryptoGraph->decode($_POST['flag_new'],4); 
 $stake=$cryptoGraph->decode($_POST['stake_new'],4); 
 
	if($stake=="BDO")
	{
		$id_fk="gp_id_fk='".$id."'";
	}
	else if($logged_user=='EO')
	{
	 $id=$cryptoGraph->decode($_POST['flag_new'],4); 
	$stake=$cryptoGraph->decode($_POST['stake_new'],4); 
		$id_fk="ps_id_fk='".$id."'";
	}
	else if($logged_user='zpacc')
	{
	$id=$cryptoGraph->decode($_POST['flag_new'],4); 
	$stake=$cryptoGraph->decode($_POST['stake_new'],4); 
	$id_fk="zp_id_fk='".$id."'";
	}

//echo ("UPDATE prd_employee_bonus_details SET bonus_status='2'  where ".$id_fk." and bonus_status='3' "); die;
	$query_unlock=$db->update("UPDATE prd_employee_bonus_details SET bonus_status='2'  where ".$id_fk." and bonus_status='3' "); 

	if($stake=="BDO")
	{
		if($query_unlock)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Bonus Unlocked Successfully...</strong></div>';
			header('Location:ul_gp_bonus_module_entry_view.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Bonus Unlocking failed. Please try again...</strong></div>';
			header('Location:ul_gp_bonus_module_entry_view.php');
		}
	}
	else if($stake=="EO")
	{
		if($query_unlock)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Bonus Unlocked Successfully...</strong></div>';
			header('Location:ul_emp_bonus_module_entry_view.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Bonus Unlocking failed. Please try again...</strong></div>';
			header('Location:ul_emp_bonus_module_entry_view.php');
		}
	}
	
	else if($stake=="ZP")
	{
		if($query_unlock)
		{
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Bonus Unlocked Successfully...</strong></div>';
			header('Location:ul_emp_bonus_module_entry_view.php');
		}
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Bonus Unlocking failed. Please try again...</strong></div>';
			header('Location:ul_emp_bonus_module_entry_view.php');
		}
	}

}
//////////////////////////////////////////////////////Employee  Unlock bonus/////////////////////////////////////////////////


@pg_close($con);
?>