<?php
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
ob_start();
session_start();
require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
require_once '../../../../includes/library/database.class.php';



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


$zp_id = $_SESSION['location']['district_id'];
$salary_monthyear = date('Ym');
$db = new database();

$upd = $db->update("UPDATE prd_employee_salary_save
						SET status_flag = 2
					WHERE
						zp_id_fk = '".$zp_id."'
						AND status_flag = 1 AND is_saved=1 and delete_status=1 and salary_monthyear='".$salary_monthyear."'
					");
				
            


       ////////////////////////////// END OF UPDATE LOAN AND OTHER DEDUCTION TABLE //////////////////////////////////////////// //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
					$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>Supplementary Salary Requisition Has Been Sent For Approval Successfully...</strong></div>';
					header('location:zp_emp_sal_requisition.php');
			exit(0);
}
						

} else {
 
	
					pg_query("rollback"); 
					$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Supplementary Salary Requisition Has Not Been Sent For Approval. Please Try Again...</strong></div>';
					header('location:zp_emp_sal_requisition.php');
					exit(0);
} 

@pg_close($con);
?>
