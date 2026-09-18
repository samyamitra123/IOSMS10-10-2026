<?php

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';

//require_once '../../../../includes/library/cryptography.class.php';
//require_once '../../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

//$cryp = new cryptography();

if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '.$config['base_url']."page/login.php");
	exit;
}

error_reporting(0);

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

header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

$zp_id = $_SESSION['location']['district_id'];

$db = new database();

$unlocked_emp_check=$db->fetch_table("
										SELECT emp_id_pk,emp_first_name,emp_second_name,emp_last_name,emp_status
										FROM prd_employee_master
										WHERE zp_id_fk='".$zp_id."' AND emp_unlock_status='4'
									");

if(count($unlocked_emp_check)>0)
{
	$emp_list="<ul style='text-align:left;'>";
	for($i=0;$i<count($unlocked_emp_check);$i++)
	{
		if($unlocked_emp_check[$i]['emp_status']=='10' || $unlocked_emp_check[$i]['emp_status']=='5')
		{
			$emp_position="Dealing Assistant (Establishment)";
		}
		if($unlocked_emp_check[$i]['emp_status']=='6' || $unlocked_emp_check[$i]['emp_status']=='7')
		{
			$emp_position="Secretary";
		}
		if($unlocked_emp_check[$i]['emp_status']=='3')
		{
			$emp_position="AEO";
		}
		$emp_list=$emp_list."<li>".$unlocked_emp_check[$i]['emp_first_name']." ".$unlocked_emp_check[$i]['emp_second_name']." ".$unlocked_emp_check[$i]['emp_last_name']." waiting for edit at ".$emp_position." level</li>";
	}
	$emp_list=$emp_list."</ul>";
	
	$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Salary Requisition Has Not Been Sent For Approval Due to following employees:<br>'.$emp_list.'</strong></div>';
	header('Location:'.$config['base_url'].'page/intra_zp/salary_daa/sal_requisition/zp_emp_sal_requisition.php');
	exit(0);
}
else
{
	
	$upd = $db->update("UPDATE prd_employee_salary_save
						SET status_flag = 2
						WHERE
						zp_id_fk = '".$zp_id."'
						AND (status_flag = 1 OR status_flag = 5) AND is_saved=1;
						");
	
	////////////////////////////// UPDATE LOAN AND OTHER DEDUCTION TABLE //////////////////////////////////////////// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*$get_deduction_data = $db->fetch_table("SELECT emp_id_fk, salary_type, FROM mad_employee_salary_save
	WHERE municipality_id_fk = '".$municipality_code."'
	AND (status_flag = 1 OR status_flag = 5) 
	AND salary_monthyear = '".date('Ym')."' 
	AND is_saved in ('1')
	AND delete_status in ('1')");
	foreach($get_deduction_data as $deductin_data)
	{
	
	if($deductin_data['salary_type'] != '8')
	{
	$update_other_deduction = $db->update("UPDATE mad_other_deduction 
	SET last_deduction_by = '".$municipality_code1."', 
	last_deduction_time = now() 
	WHERE emp_id_fk = '".$deductin_data['emp_id_fk']."' 
	AND status in('2')
	AND approval_status in('3')
	AND delete_status in('1')");
	
	$update_loan_deduction = $db->update("UPDATE mad_loan_deduction 
	SET last_deduction_by = '".$municipality_code1."', 
	last_deduction_time = now() 
	WHERE emp_id_fk = '".$deductin_data['emp_id_fk']."' 
	AND status in('2')
	AND approval_status in('3')
	AND delete_status in('1')");
	}
	}*/
	
	
	////////////////////////////// END OF UPDATE LOAN AND OTHER DEDUCTION TABLE //////////////////////////////////////////// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if($upd)
	{
	
		//	$security = $db->insert("
		//							INSERT INTO
		//							zpemp_salary_log (
		//												ip,
		//												date,
		//												browser,
		//												os,
		//												priv_salary_status,
		//												present_salary_status,
		//												created_by,
		//												zp_id_fk
		//											)
		//							VALUES 			(
		//												'".$_SESSION['user_agent']['USER_IP']."',
		//												now(),
		//												'".$_SESSION['user_agent']['BROWSER']."',
		//												'".$_SESSION['user_agent']['OS']."',
		//												1,
		//												2,
		//												'".$_SESSION['user_info']['stake_user']."',
		//												'".$_SESSION['location']['district_id']."'	
		//											)
		//				
		//						");
		//if($upd && $security){
		if($upd )
		{
			pg_query("commit"); 
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Salary Requisition Has Been Sent For Approval Successfully...</strong></div>';
			header('Location:'.$config['base_url'].'page/intra_zp/salary_daa/sal_requisition/zp_emp_sal_requisition.php');
			exit(0);
		}
	} 
	else 
	{
		pg_query("rollback"); 
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Salary Requisition Has Not Been Sent For Approval. Please Try Again...</strong></div>';
		header('Location:'.$config['base_url'].'page/intra_zp/salary_daa/sal_requisition/zp_emp_sal_requisition.php');
		exit(0);
	} 
	@pg_close($con);
}
?>
