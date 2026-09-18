<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
session_start();
require '../../../includes/config/config.php';
require '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
require_once '../../../includes/library/myvalidation.class.php';
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
//print_r($_POST);exit;

	$db = new database();
	$block_code=$_POST['block_code'];
	$block_id_fk=$_POST['block_id_fk'];
	$status=$_POST['status'];
	$reason=$_POST['reason'];
	
	if($validator->blank_select(trim($block_code))==FALSE || $validator->pattern_number(trim($block_code))==FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Invalid block code.</strong></div>';	
	    header('location:show_block_list.php') ;
		exit(0);
	}
	else if($validator->blank_select(trim($block_id_fk))==FALSE || $validator->pattern_number(trim($block_id_fk))==FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Invalid block code.</strong></div>';	
	    header('location:show_block_list.php') ;
		exit(0);
	}
	else if($validator->blank_select(trim($status))==FALSE || $validator->pattern_number(trim($status))==FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Invalid status for unlock.</strong></div>';	
	    header('location:show_block_list.php') ;
		exit(0);
	}
	else if($validator->blank_select($reason)==FALSE)
	{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Please select reason for unlock.</strong></div>';	
	    header('location:show_block_list.php') ;
		exit(0);
	}
	

	$db->update("UPDATE prd_employee_salary_save SET status_flag=2 WHERE block_code='".trim($block_code)."' AND salary_monthyear='".date('Ym')."' AND status_flag=3");
	if($db){
			$date=date("Ym");
			$insert = $db->insert("insert into prd_admin_unlock (reason,block_code,block_id_fk,user_ip,browser_type,entry_time,status,salary_monthyear) values ('".$reason."','".trim($block_code)."','".trim($block_id_fk)."','".$_SESSION['user_agent']['USER_IP']."','".$_SESSION['user_agent']['BROWSER']."',now(),'".trim($status)."','".$date."')");
			$_SESSION['msg']= '<div class="alert alert-success" style="text-align:center;"><strong>Salary has been successfully unlocked.</strong></div>';	
			header('location:show_block_list.php') ;
			exit(0);	
	}
	else{
		$_SESSION['msg']= '<div class="alert alert-danger" style="text-align:center;"><strong>Unlock failed.</strong></div>';	
		header('location:show_block_list.php') ;
		exit(0);
	}
?>