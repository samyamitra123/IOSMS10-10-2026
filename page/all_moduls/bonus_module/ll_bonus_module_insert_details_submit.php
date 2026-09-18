<?php
ob_start();
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

){
header('Location: '. $config['base_url'] . "page/login.php");
exit;
}

if(!isset($_SERVER['HTTP_REFERER'])){
header('Location:'.$config['base_url']."page/error.php?id=1");
exit("Do not paste URL directly");

} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
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
else if($_SESSION['user_info']['stake_abbr']=='ADMINISTRATOR')
{
	$logged_user='ADMINISTRATOR';
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
$emp_count=$_POST['emp_count'];
if($sec_time_token!=$enc_session)
{
	$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!...Please Try Again.</strong></div>';
	$emp_id=$cryptoGraph->encode($_POST['emp_id_pk'],4);
	//include 'emp_bonus_module_entry_view.php';
	exit;
}
else
{
	//$bonusmonth = $_POST['bonusmonth'];
	$bonusyear = $_POST['bonusyear']; 
	$monthyear = $bonusyear.$bonusmonth;
	$bonus_amount = $_POST['bonus_amount'];
	$emp_bonus_category = $_POST['bonus_category'];
	$bonus_name = $_POST['bonus_name'];	
	$gp_id_fk = $_SESSION['location']['gp_id'];
	$ps_id_fk = $_SESSION['location']['ps_id'];
	$zp_id_fk = $_SESSION['location']['district_id'];
	$state_id = $_SESSION['location']['state_code'];
	
	
	/*if($validator->blank_select($bonusmonth) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Bonus Month.</strong></div>';
		header('location:ll_bonus_module_entry.php');
		exit;
	} */
	if($validator->blank_select($bonusyear) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Bonus Year.</strong></div>';
		header('location:ll_bonus_module_entry.php');
		exit;
	} 
	if($validator->blank_select($emp_bonus_category) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Bonus Category.</strong></div>';
		header('location:ll_bonus_module_entry.php');
		exit;
	}
	if($validator->blank_select($bonus_amount) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Bonus Amount.</strong></div>';
		header('location:ll_bonus_module_entry.php');
		exit;
	}
	if($validator->pattern_number($bonus_amount) == FALSE || $bonus_amount == 0)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Valid Bonus Amount.</strong></div>';
		header('location:ll_bonus_module_entry.php');
		exit;
	}
	if($validator->blank_select($bonus_name) == FALSE)
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Bonus Name.</strong></div>';
		header('location:ll_bonus_module_entry.php');
		exit;
	}

	$db = new database();
	
	/*if($logged_user=='GP')
	{
		
		$category_check=$db->fetch_table("SELECT bonus_type_id_pk FROM prd_bonus_type_details WHERE bonus_category='".$emp_bonus_category."' AND substr(bonus_monthyear,1,4)='".$bonusyear."' AND active_status in (1,2) AND gp_id_fk='".$gp_id_fk."'");
	
		if(count($category_check)>0)
		{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>This Bonus Category has already been entered for this current year.</strong></div>';
			header('location:ll_bonus_module_entry.php');
			exit;
		}
		
		
		$query_insert=$db->insert("INSERT into prd_bonus_type_details
											(
											bonus_category,
											bonus_amount,
											bonus_monthyear,
											gp_id_fk,
											ps_id_fk,
											zp_id_fk,
											employee_total_number,
											entry_time,
											entry_ip,
											active_status,
											bonus_name
											)
											VALUES(
											'".$emp_bonus_category."',
											'".$bonus_amount."',
											'".$monthyear."',
											'".$gp_id_fk."',
											0,
											0,
											0,
											now(),
											'".$_SERVER['REMOTE_ADDR']."',
											'1',
											'".$bonus_name."'
											)");
	}
	else if($logged_user=='DA')
	{
	
		$category_check=$db->fetch_table("SELECT bonus_type_id_pk FROM prd_bonus_type_details WHERE bonus_category='".$emp_bonus_category."' AND substr(bonus_monthyear,1,4)='".$bonusyear."' AND active_status in (1,2) AND ps_id_fk='".$ps_id_fk."'");
	
		if(count($category_check)>0)
		{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>This Bonus Category has already been entered for this current year.</strong></div>';
			header('location:ll_bonus_module_entry.php');
			exit;
		}
		
		$query_insert=$db->insert("INSERT into prd_bonus_type_details
											(
											bonus_category,
											bonus_amount,
											bonus_monthyear,
											gp_id_fk,
											ps_id_fk,
											zp_id_fk,
											employee_total_number,
											entry_time,
											entry_ip,
											active_status,
											bonus_name
											)
											VALUES(
											'".$emp_bonus_category."',
											'".$bonus_amount."',
											'".$monthyear."',
											0,
											'".$ps_id_fk."',
											0,
											0,
											now(),
											'".$_SERVER['REMOTE_ADDR']."',
											'1',
											'".$bonus_name."'
											)");
	}
	else if($logged_user=='zpdaa')
	{
		$category_check=$db->fetch_table("SELECT bonus_type_id_pk FROM prd_bonus_type_details WHERE bonus_category='".$emp_bonus_category."' AND substr(bonus_monthyear,1,4)='".$bonusyear."' AND active_status in (1,2) AND zp_id_fk='".$zp_id_fk."'");
	
		if(count($category_check)>0)
		{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>This Bonus Category has already been entered for this current year.</strong></div>';
			header('location:ll_bonus_module_entry.php');
			exit;
		}
		
		$query_insert=$db->insert("INSERT into prd_bonus_type_details
											(
											bonus_category,
											bonus_amount,
											bonus_monthyear,
											gp_id_fk,
											ps_id_fk,
											zp_id_fk,
											employee_total_number,
											entry_time,
											entry_ip,
											active_status,
											bonus_name
											)
											VALUES(
											'".$emp_bonus_category."',
											'".$bonus_amount."',
											'".$monthyear."',
											0,
											0,
											'".$zp_id_fk."',
											0,
											now(),
											'".$_SERVER['REMOTE_ADDR']."',
											'1',
											'".$bonus_name."'
											)");
	}*/
	
	if($logged_user=='ADMINISTRATOR')
	{   

		$lms_query=$db->fetch_table("select bonus_monthyear,bonus_category from prd_bonus_type_details where 
		active_status='1' and bonus_name in('401','400') and delete_status='1' and  bonus_category='".$emp_bonus_category."' and bonus_monthyear='".$bonusyear."' ");
		/*$category_check=$db->fetch_table("SELECT bonus_type_id_pk FROM prd_bonus_type_details WHERE bonus_category='".$emp_bonus_category."' AND bonus_monthyear='".$bonusyear."' AND active_status in (1,2)");*/
	
		/*if(count($category_check)>0)
		{
			$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>This Bonus Category has already been entered for this current year.</strong></div>';
			header('location:ll_bonus_module_entry.php');
			exit;
		}*/
		
		if(!empty($lms_query))
		{
			
			$upd=$db->update("UPDATE prd_bonus_type_details SET active_status='0',delete_status='0' WHERE active_status='1'  and bonus_category='".$lms_query[0]['bonus_category']."' and bonus_monthyear='".$bonusyear."' ");
			
			
			if($upd)
			{
				$query_insert=$db->insert("INSERT into prd_bonus_type_details
													(
													bonus_category,
													bonus_amount,
													bonus_monthyear,
													employee_total_number,
													entry_time,
													entry_ip,
													active_status,
													bonus_name,
													delete_status
													)
													VALUES(
													'".$emp_bonus_category."',
													'".$bonus_amount."',
													'".$bonusyear."',
													0,
													now(),
													'".$_SERVER['REMOTE_ADDR']."',
													'1',
													'".$bonus_name."',
													'1'
													)");
			}
		}
		else 
		{
		
			$upd=$db->update("UPDATE prd_bonus_type_details SET active_status='0' WHERE active_status='1' and bonus_monthyear='".$bonusyear."'  ");          
			if($upd)
			{
			$query_insert=$db->insert("INSERT into prd_bonus_type_details
											(
											bonus_category,
											bonus_amount,
											bonus_monthyear,
											employee_total_number,
											entry_time,
											entry_ip,
											active_status,
											bonus_name,
											delete_status
											)
											VALUES(
											'".$emp_bonus_category."',
											'".$bonus_amount."',
											'".$bonusyear."',
											0,
											now(),
											'".$_SERVER['REMOTE_ADDR']."',
											'1',
											'".$bonus_name."',
											'1'
											)");
			}
			
		}
	}
	
	if($query_insert)
	{
		$_SESSION['msg']='<div class="alert alert-success" style="text-align:center"><strong>Employee Bonus Details Has Been Saved Successfully.</strong></div>';
			header('location:ll_bonus_module_entry.php');
			exit;
	}
	else
	{
		$_SESSION['msg']='<div class="alert alert-danger" style="text-align:center"><strong>Employee Bonus Details Has Not Been Saved Successfully.</strong></div>';
		header('location:ll_bonus_module_entry.php');
		exit;
	}
}
?>

<?php @pg_close($con); ?>

