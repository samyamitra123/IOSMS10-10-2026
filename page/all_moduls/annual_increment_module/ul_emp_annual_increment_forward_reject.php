<?php
session_start();
require_once '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require_once '../../../includes/library/database.class.php';
require_once '../../../includes/library/cryptography.class.php';

$cryptoGraph=new cryptography();

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
else if($_SESSION['user_info']['stake_abbr']=='SECRETARY')
{
	$logged_user='zpsec';
}
else
{
	$logged_user=$_SESSION['user_info']['stake_abbr'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	

$cryptoGraph=new cryptography();



$emp_id=$cryptoGraph->decode($_POST['annu_emp'],4);
$gp_id=$cryptoGraph->decode($_POST['annu_gp'],4);
$increment_amount=$cryptoGraph->decode($_POST['inc_amt'],4); 
$action=$_POST['action']; 
$db = new database();

$pension_stat_fetch=$db->fetch_table(" SELECT emp_cosolidated_pay,emp_pension_status FROM prd_employee_master WHERE emp_id_pk='".$emp_id."' ");
$emp_consolidated_pay=$pension_stat_fetch[0]['emp_cosolidated_pay'];
$pension_stat=$pension_stat_fetch[0]['emp_pension_status'];
if($pension_stat=='0')
{
	$update_pension='0';
}
elseif($pension_stat=='1' || $pension_stat=='2') 
{
	$update_pension='2';
}

													
if($action=="accept")
{
	$db = new database();
	
	pg_query('BEGIN');
	
	$update_status = $db->update("UPDATE prd_employee_annual_increment_details  
									SET zp_forward_status='1'
									WHERE emp_id_fk = '".$emp_id."' and annual_increment_amount='".$increment_amount."'
									AND status='1' AND substr(effective_monthyear,1,4)='".date('Y')."'");
	
	
	
	
	
	if( $update_status )	
	{
		pg_query('COMMIT');											
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center"><strong>Employee Annual Increment Details Has Been Forwarded Successfully.</strong></div>';	
		header('location:ul_emp_annual_increment_forward_reject_view.php?gp_id_fk='.$_POST['annu_gp']);
		exit;													
	}
	else
	{
		pg_query('ROLLBACK');
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Acceptance Failed!!! </strong></div>';	
		header('location:ul_emp_annual_increment_forward_reject_view.php?gp_id_fk='.$_POST['annu_gp']);
		exit;
	}
	
}
else if($action=="reject")
{
	
	$db = new database();
	
	$update_status = $db->update("UPDATE prd_employee_annual_increment_details  SET status='3' , zp_forward_status='0'
									WHERE emp_id_fk = '".$emp_id."' and annual_increment_amount='".$increment_amount."'
									AND status='1'");
	
	
	if( $update_status)	
	{											
	
		$_SESSION["msg"]='<div class="alert alert-success" style="text-align:center"><strong>Employee Annual Increment Details Has Been Rejected Successfully.</strong></div>';	
		header('location:ul_emp_annual_increment_forward_reject_view.php');
		exit;													
	}
	else
	{
		$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Rejection Failed!!! </strong></div>';	
		header('location:ul_emp_annual_increment_forward_reject_view.php');
		exit;
	}													

}
else
{
	$_SESSION["msg"]='<div class="alert alert-danger" style="text-align:center"><strong>Security Error!!! </strong></div>';	
	header('location:ul_emp_annual_increment_forward_reject_view.php');
	exit;
}
 