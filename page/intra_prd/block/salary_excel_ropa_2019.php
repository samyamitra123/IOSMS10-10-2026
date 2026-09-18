<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
?>

<?
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}


$db=new database();
$monthyear =$_REQUEST['ye'].$_REQUEST['mo'];

$requisition=$db->fetch_table("SELECT code FROM prd_dise_code_master WHERE code_master_id_pk='405'");
$requisition_type=$requisition[0]['code'];

$arr=$db->fetch_table("SELECT gp_name,gp.gp_code,emp_first_name,emp_second_name,emp_last_name,emp_pan_no,emp_bank_name,
	   				   emp_bank_branch,emp_micr_no,emp_ifsc_no,emp_acc_no,consolidated_pay,pay_payband,tch_grade_pay,
	   				   basic,da,sal.interim_relief,hra,ma,conv_allow,other_deduction,hill_allowance,gross_salary,gpf,pf_loan,p_tax,i_tax,gsli,overdrawn,net,
										gp.gp_name,
										emp.emp_first_name,
										emp.emp_second_name,
										emp.emp_last_name,
										emp.emp_pay_in_payband,
										emp.emp_desig,
										emp.emp_pay_band,
										emp.emp_first_join_date
										
									FROM
										prd_employee_salary_save as sal
									
									INNER JOIN 
										prd_employee_master as emp
										ON sal.emp_id_fk = emp.emp_id_pk
									INNER JOIN 
										prd_location_master_gp as gp
										ON sal.gp_code = CAST(gp.gp_code AS text)
								
										WHERE
												sal.block_code = '".$_SESSION['user_info']['stake_user']."'
											AND cast(emp.gp_id_fk as character varying)= sal.gp_id_fk 
											AND sal.net!='0'
											AND (sal.status_flag = 1 OR sal.status_flag = 2 OR sal.status_flag = 3 OR sal.status_flag = 4 )
											AND delete_status=1
											AND salary_monthyear='".$monthyear."'
											AND sal.requisition_type='".$requisition_type."'
											AND sal.ropa_status IN ('1')
										ORDER BY gp.gp_name ASC");
										
									
		
	
	if(!$arr)	
	{
		$arr=$db->fetch_table("SELECT gp_name,gp.gp_code,emp_first_name,emp_second_name,emp_last_name,emp_pan_no,emp_bank_name,
	   				   emp_bank_branch,emp_micr_no,emp_ifsc_no,emp_acc_no,consolidated_pay,pay_payband,tch_grade_pay,
	   				   basic,da,sal.interim_relief,hra,ma,conv_allow,hill_allowance,gross_salary,gpf,pf_loan,p_tax,i_tax,gsli,overdrawn,net,
										gp.gp_name,
										emp.emp_first_name,
										emp.emp_second_name,
										emp.emp_last_name,
										emp.emp_pay_in_payband,
										emp.emp_desig,
										emp.emp_pay_band,
										emp.emp_first_join_date
										
									FROM
										prd_monthly_salary_archive_final as sal
									
									INNER JOIN 
										prd_employee_master as emp
										ON sal.emp_id_fk = emp.emp_id_pk
									INNER JOIN 
										prd_location_master_gp as gp
										ON sal.gp_code = CAST(gp.gp_code AS text)
								
										WHERE
												sal.block_code =  '".$_SESSION['user_info']['stake_user']."'
											AND cast(emp.gp_id_fk as character varying) = sal.gp_id_fk 
											AND sal.net!='0'
											AND (sal.status_flag = 1 OR sal.status_flag = 2 OR sal.status_flag = 3 OR sal.status_flag = 4)
											AND delete_status='1'
											AND salary_monthyear='".$monthyear."'
											AND sal.requisition_type='".$requisition_type."'
											AND sal.ropa_status IN ('1')
										ORDER BY gp.gp_name ASC");

			
	}
		
		
		
		
		
		
		
		
										
if(count($arr)>0)
{	

echo "1";
/** Error reporting */
exit;
}
else
{
	echo "0";
}										