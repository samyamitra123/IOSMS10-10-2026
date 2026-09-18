<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();

require '../../../../includes/config/config.php';
require '../../../../includes/config/database.config.php';
require '../../../../includes/library/database.class.php';
require '../../../../includes/library/cryptography.class.php';

require '../../../../includes/library/myvalidation.class.php';

//require '../../../page_visite.php';

$crypto = new cryptography();

$sal_action=$crypto->decode($_POST['sal_action'],4);



//------------------------------- LOGICAL AREA ---------------------------------------------------------------------------------
//
//Detect referer page from external domain
//if(!isset($_SERVER['HTTP_REFERER'])){
//    header('Location: '. $config['base_url'] . "page/error.php?id=1");
//    exit("Do not paste URL directly");
//    
//} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
//    // substring is not found in string
//    header('Location: '. $config['base_url'] . "page/error.php?id=2");
//    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
//}
//redirect to login page when login session not found
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}

//------------------------------ PAGE VARIABLES --------------------------------------------------------------------------------

//Page variables
$common['title'] = "VIEW SALARY REQUISITION | iOSMS | Govt. of West Bengal ";
?>
 <?php  date('M').','.date('Y'); 
 
$db=new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

if($sal_action=='forward')
{
	$db=new database();
	
	$unlocked_emp_check=$db->fetch_table("
										SELECT sal.emp_id_fk,emp.emp_first_name,emp.emp_second_name,emp.emp_last_name,emp.emp_status 
										FROM prd_employee_salary_save sal
										INNER JOIN
										prd_employee_master emp 
										ON
										sal.zp_id_fk=emp.zp_id_fk AND sal.emp_id_fk=emp.emp_id_pk
										WHERE sal.zp_id_fk='".$_SESSION['location']['district_id']."' 
										AND emp.emp_unlock_status='4' AND sal.salary_monthyear='".date('Ym')."' AND sal.ropa_status='1'
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
		
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Salary Forward failed Due to following employees:<br>'.$emp_list.'</strong></div>';
		header('Location:zp_salary_requisition_view_ropa_2019.php');
	}
	else
	{
			
		$query_lock=$db->update("UPDATE prd_employee_salary_save SET status_flag='3'  
								WHERE zp_id_fk = '".$_SESSION['location']['district_id']."' 
								AND status_flag='2'  AND delete_status='1' AND salary_monthyear='".date('Ym')."' 
								AND is_saved='1' AND requisition_type='".$requisition_type."' AND ropa_status='1' "); 
		
		if($query_lock)
		{		
			$security = $db->insert("
									INSERT INTO
									zpemp_salary_log (
									ip,
									date,
									browser,
									os,
									priv_salary_status,
									present_salary_status,
									created_by,
									zp_id_fk
									
									)
									VALUES 			
									(
									'".$_SESSION['user_agent']['USER_IP']."',
									now(),
									'".$_SESSION['user_agent']['BROWSER']."',
									'".$_SESSION['user_agent']['OS']."',
									'2',
									'3',
									'".$_SESSION['user_info']['stake_user']."',
									'".$_SESSION['location']['district_id']."'
									)
									");
			$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Salary Forwarded Successfully...</strong></div>';
			header('Location:zp_salary_requisition_view_ropa_2019.php');
		}
		
		else
		{
			$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Salary Forward failed. Please try again...</strong></div>';
			header('Location:zp_salary_requisition_view_ropa_2019.php');
		}
	}
}
else if($sal_action=='unlock')
{
	$db=new database();
	
	$query_unlock=$db->update("UPDATE prd_employee_salary_save SET status_flag='1'  
								WHERE zp_id_fk = '".$_SESSION['location']['district_id']."' AND status_flag='2'  
								AND delete_status='1' AND salary_monthyear='".date('Ym')."' AND is_saved='1'  
								AND requisition_type='".$requisition_type."' AND ropa_status='1' "); 
		
	if($query_unlock)
	{
			
		$security = $db->insert("
								INSERT INTO
								zpemp_salary_log (
								ip,
								date,
								browser,
								os,
								priv_salary_status,
								present_salary_status,
								created_by,
								zp_id_fk
								
								)
								VALUES 			(
								'".$_SESSION['user_agent']['USER_IP']."',
								now(),
								'".$_SESSION['user_agent']['BROWSER']."',
								'".$_SESSION['user_agent']['OS']."',
								'2',
								'1',
								'".$_SESSION['user_info']['stake_user']."',
								'".$_SESSION['location']['district_id']."'
								)
		
		");
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center;"><strong>Salary Unlocked Successfully...</strong></div>';
		header('Location:zp_salary_requisition_view_ropa_2019.php');
		
	}
	else
	{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center;"><strong>Salary Unlock failed. Please try again...</strong></div>';
		header ('Location:zp_salary_requisition_view_ropa_2019.php');
	}
}
?>