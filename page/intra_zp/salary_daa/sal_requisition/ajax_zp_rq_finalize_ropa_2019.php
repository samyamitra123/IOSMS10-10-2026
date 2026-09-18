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

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location:'.$config['base_url']."page/error.php?id=1");
    exit("Do not paste URL directly");
    
} elseif (strpos($_SERVER['HTTP_REFERER'], $config['base_url']) === false) {
    // substring is not found in string
    header('Location:'. $config['base_url']."page/error.php?id=2");
    exit("<p style='background-color:#f00;'>Wrong website referer found</p>");
}

header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");

//$municipality_code1 = $_SESSION['location']['gpcode'];
//$municipality_code = substr($municipality_code1,0,7);
$zp_id = $_SESSION['location']['district_id'];
$salary_monthyear = date('Ym');
$db = new database();
$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

$upd = $db->update("UPDATE prd_employee_salary_save
						SET status_flag = 2
					WHERE
						zp_id_fk = '".$zp_id."'
						AND status_flag = 1 AND is_saved=1 and delete_status=1 and salary_monthyear='".$salary_monthyear."' AND ropa_status='1'  AND requisition_type='".$requisition_type."'
					");
				
           
if($upd){

	
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
												1,
												2,
												'".$_SESSION['user_info']['stake_user']."',
												'".$_SESSION['location']['district_id']."'	
											)
				
						");
	
						
if($upd && $security){

					pg_query("commit"); 
					$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Salary Requisition Has Been Sent For Approval Successfully...</strong></div>';
					header('location:zp_emp_sal_requisition_ropa_2019.php');
			exit(0);
}
						

} else {
 
	
					pg_query("rollback"); 
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Salary Requisition Has Not Been Sent For Approval. Please Try Again...</strong></div>';
					header('location:zp_emp_sal_requisition_ropa_2019.php');
					exit(0);
} 

@pg_close($con);
?>
