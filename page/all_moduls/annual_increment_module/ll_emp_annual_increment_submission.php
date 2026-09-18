<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
ob_start();
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
//print_r($_POST);die;


if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}

if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
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
else if($_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Establishment)')
{
	$logged_user='zpdae';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$cryptoGraph=new cryptography();

$emp_id=$cryptoGraph->decode($_POST['emp_id_annu'],4);
$annu_status=$_POST['inc_annu_status']; 
$increment_amount=$cryptoGraph->decode($_POST['inc_amt_annu'],4);
$finan_yr=$cryptoGraph->decode($_POST['finan_yr'],4);
$increment_condisation= $_POST['increment_condisation']; 
$effective_month_year=date('Y').'07';
$increment_date=date('Y').'-07-01';			
$db = new database();


	
if($logged_user == 'GP')
{

	$emp_pre_data = $db->fetch_table("SELECT   
									emp_id_pk,
									emp_cosolidated_pay,
									emp_pay_scale,
									emp_pay_in_payband,
									emp_grade_pay,
									emp_pay_band,
									gp_id_fk
								FROM
									prd_employee_master 
								WHERE
									emp_id_pk = '".$emp_id."'
									AND gp_id_fk = '".$_SESSION['location']['gp_id']."'");
								
	$gp_id_fk = $emp_pre_data[0]['gp_id_fk'];
	$ps_id_fk=0;
	$zp_id_fk=0;
}
else if($logged_user == 'DA')
{
	$emp_pre_data = $db->fetch_table("SELECT   
									emp_id_pk,
									emp_cosolidated_pay,
									emp_pay_scale,
									emp_pay_in_payband,
									emp_grade_pay,
									emp_pay_band,
									ps_id_fk
									
								FROM
									prd_employee_master 
								WHERE
									emp_id_pk = '".$emp_id."'
									AND ps_id_fk = '".$_SESSION['location']['ps_id']."'");
								
		
	$ps_id_fk = $emp_pre_data[0]['ps_id_fk'];
	$gp_id_fk=0;
	$zp_id_fk=0;
		
}  
else if($logged_user == 'zpdaa' || $logged_user == 'zpdae')
{
	$emp_pre_data = $db->fetch_table("SELECT   
									emp_id_pk,
									emp_cosolidated_pay,
									emp_pay_scale,
									emp_pay_in_payband,
									emp_grade_pay,
									emp_pay_band,
									zp_id_fk
									
								FROM
									prd_employee_master 
								WHERE
									emp_id_pk = '".$emp_id."'
									AND zp_id_fk = '".$_SESSION['location']['district_id']."'");
								
		
	$zp_id_fk=$emp_pre_data[0]['zp_id_fk'];
	$ps_id_fk =0;
	$gp_id_fk=0;
		
} 
 
$emp_consolidated_pay=$emp_pre_data[0]['emp_cosolidated_pay'];									
$emp_pay_band=$emp_pre_data[0]['emp_pay_band']; 
$emp_pay_in_payband=$emp_pre_data[0]['emp_pay_in_payband'];
$emp_grade_pay=$emp_pre_data[0]['emp_grade_pay'];
$emp_pay_scale=$emp_pre_data[0]['emp_pay_scale']; 

if($emp_pre_data[0]['emp_pay_band']=='' || $emp_pre_data[0]['emp_pay_band']=='0')
{
	$emp_pay_band=0;
}
else
{
	$emp_pay_band=$emp_pre_data[0]['emp_pay_band'];
}
if($emp_pre_data[0]['emp_grade_pay']=='' || $emp_pre_data[0]['emp_grade_pay']=='0')
{
	$emp_grade_pay=0;
}
else
{
	$emp_grade_pay=$emp_pre_data[0]['emp_grade_pay'];
}

if($emp_pre_data[0]['emp_pay_scale']=='' || $emp_pre_data[0]['emp_pay_scale']=='0')
{
	$emp_pay_scale=0;
}
else
{
	$emp_pay_scale=$emp_pre_data[0]['emp_pay_scale'];
}
				
if($emp_consolidated_pay=='0')
{				
	//$new_pay_in_payband=$emp_pay_in_payband+$increment_amount;        checking needed////////////////
	//$new_consolidated_pay=0;
	
	$new_pay_in_payband=$increment_amount;
	$new_consolidated_pay=0;
}
else
{
	$new_pay_in_payband=0;
	$new_consolidated_pay=$emp_consolidated_pay+$increment_amount;
}
	
	  


				
	$db = new database();							
	if($annu_status=='3')
	{
		if($logged_user == 'GP')
		{

			$update_old_data=$db->update("
										UPDATE prd_employee_annual_increment_details SET status=0 WHERE emp_id_fk='".$emp_id."' AND gp_id_fk='".$gp_id_fk."' AND status='3'
										");
		}
		else if($logged_user == 'DA')
		{
			
			$update_old_data=$db->update("
										UPDATE prd_employee_annual_increment_details SET status=0 WHERE emp_id_fk='".$emp_id."' AND ps_id_fk='".$ps_id_fk."' AND status='3'
										");
		}
		else if($logged_user == 'zpdaa' || $logged_user == 'zpdae')
		{
			
			$update_old_data=$db->update("
										UPDATE prd_employee_annual_increment_details SET status=0 WHERE emp_id_fk='".$emp_id."' AND zp_id_fk='".$zp_id_fk."' AND status='3'
										");
		}
	}
	
	if($annu_status!=3 || $update_old_data)
	{
		$insert_new_data = $db->insert("INSERT INTO prd_employee_annual_increment_details 
													(emp_id_fk,
														gp_id_fk,
														ps_id_fk,
														previous_emp_pay_band,
														previous_emp_pay_in_payband,
														previous_emp_pay_scale,
														previous_emp_grade_pay,
														new_emp_pay_band,
														new_emp_pay_in_payband,
														new_emp_pay_scale,
														new_emp_grade_pay,
														annual_increment_amount,
														status,
														annual_increment_date,
														effective_monthyear,
														zp_id_fk,
														financial_year,
														previous_consolidated_pay,
														new_consolidated_pay,
														increment_condisation
													) 
													VALUES 
													(
														'".$emp_id."',
														'".$gp_id_fk."',
														'".$ps_id_fk."',
														'".$emp_pay_band."',
														'".$emp_pay_in_payband."',
														'".$emp_pay_scale."',
														'".$emp_grade_pay."',
														'".$emp_pay_band."',
														'".$new_pay_in_payband."',
														'".$emp_pay_scale."',
														'".$emp_grade_pay."',
														'".$increment_amount."',
														'1',
														'".$increment_date."',
														'".$effective_month_year."',
														'".$zp_id_fk."',
														'".$finan_yr."',
														'".$emp_consolidated_pay."',
														'".$new_consolidated_pay."','".$increment_condisation."')")	;
	
	
	}
	  					
						 
if($insert_new_data)
{
	$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center"><strong>Employee Annual Increment Details Has Been Sent Successfully.</strong></div>';	
	header('location:ll_emp_annual_increment_entry_view.php');
	exit;
		
} 
else
{ 
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Employee Annual Increment Details Has Not Been Sent. Please Try Again...</strong></div>';
	header('Location:ll_emp_annual_increment_entry_view.php');	
	exit;
}	



?>
