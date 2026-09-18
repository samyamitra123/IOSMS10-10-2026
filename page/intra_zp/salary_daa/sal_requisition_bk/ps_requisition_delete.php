<?php

//error_reporting(0);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
$str=$_SESSION['user_info']['stake_user'];
$state10=substr($str,0,4); 

require_once '../../../../includes/config/config.php';
require_once '../../../../includes/config/database.config.php';
//die('1234');
require_once '../../../../includes/library/database.class.php';
require_once '../../../../includes/library/cryptography.class.php';
require_once '../../../../includes/library/myvalidation.class.php';
//require '../../../page_visite.php';

$crypto = new cryptography();
$db = new database();

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];
		
//$sec_time_token=$_GET['sec_tok'];
$session_token=$_SESSION['security_token'];
$enc_session=md5('371371371'.$session_token);

	/*if($sec_time_token!=$enc_session){
			$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.<strong></div>';
			header('location:emp_sal_requisition.php');
			exit(0);
	}else{*/
			$emp_id_pk =  $crypto->decode($_GET['emp_id_pk'],4);
			//$empcd =  $crypto->decode($_GET['empcd'],4);
			$name =  $crypto->decode($_GET['name'],4);
			
			$delete_employee_salary = $db->update("UPDATE prd_employee_salary_save
												SET delete_status = '0'
												WHERE emp_id_fk = '".$emp_id_pk."' 
												AND status_flag in ('1') AND is_saved in('1')
												AND delete_status in ('1')");
												
												
			if($delete_employee_salary){					
				$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center"><strong>The salary requisition of '.$tchname.' has been successfully deleted.</strong></div>';
				header('location:ps_emp_sal_requisition.php');
				exit(0);
			}else{
				$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center"><strong>Sorry !!! Salary Requisition Deletion Fails.</strong></div>';
				header('location:ps_emp_sal_requisition.php');
				exit(0);
			}
												
			 
	//}
?>